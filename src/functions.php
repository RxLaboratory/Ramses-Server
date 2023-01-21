<?php
    require_once($__ROOT__."/reply.php");

    function debugSessionVar() {
        global $log;
        $jsonSession = $_SESSION;
        if (isset($jsonSession["token"]))
            $jsonSession["token"] = "Hidden-Token";
        $jsonSession = json_encode($jsonSession);
        //$log->debugLog("These are the session variables:\n" . $jsonSession, "DATA");
    }

    /**
     * Logs in and returns the new session token
     */
    function login($uuid, $role, $id, $name)
    {
        global $log, $_SESSION;

        // Keep session info
        $_SESSION["uuid"] = $uuid;
        // Generate a new token
        $_SESSION["token"] = bin2hex(random_bytes(20));
        //Log
        $log->login($uuid, $role, $id, $name);

        SessionManager::regenerateSession();

        return $_SESSION["token"];
    }

    /**
     * Logs out and reset the session token
     */
    function logout($reason="logout", $message = "Logged out.")
    {
        global $log, $reply, $_SESSION;

        $uuid = "unknown";
        if (isset($_SESSION["uuid"])) $uuid = $_SESSION["uuid"];
        //Log
        $log->logout($uuid, $reason);

        $reply["message"] = $message;
        $reply["query"] = "loggedout";
        $reply["success"] = false;
        $reply["accepted"] = false;

        SessionManager::sessionEnd();
        
        printAndDie();
    }

    function isAdmin()
    {
        global $_SESSION, $tablePrefix;
        $q = new DBQuery();
        $q->prepare("SELECT `data` FROM `{$tablePrefix}RamUser` WHERE `uuid` = :uuid ;");
        $q->bindStr("uuid", $_SESSION['uuid']);
        $q->execute();
        $row = $q->fetch();
        $q->close();
        
        $data = SecurityManager::decrypt( $row['data'] );
        $data = json_decode($data, true);

        if (!isset($data['role'])) return false;
        return strtolower($data['role']) == 'admin';
    }

    

    /**
        * Prepares the prefix for SQL table names (adds a "_" at the end if needed)
        */
    function setupTablePrefix() {
        global $tablePrefix;
        if (strlen($tablePrefix) > 0 && !StrUtils::endsWith($tablePrefix, "_")) $tablePrefix = $tablePrefix . "_";
    }

    function checkForbiddenWords($str)
    {
        // These may be forbidden with some providers; use %word% instead and update.
        // We know it's safe, all queries are correctly prepared here using PDO queries.
        $forbidden = array("and", "or", "if", "else", "insert", "update", "select", "drop", "alter");
        foreach($forbidden as $word)
        {
            // Replace correctly formatted forbidden words with actual words
            $str = str_replace("%" . $word . "%", " " . $word, $str);
        }
        
        return $str;
    }


    

    
    

    function acceptReply($queryName)
    {
        global $reply;

        // Already accepted
        if ($reply["accepted"]) return false;
        // Not this query
        if (!RequestParser::hasArg($queryName)) return false;

        // Got the right one!
        $reply["query"] = $queryName;
        $reply["accepted"] = true;

        return true;
    }
    
    function dateTimeStr()
    {
        $currentDate = new DateTime();
        return $currentDate->format('Y-m-d H:i:s');
    }

    function versionLowerThan( $version, $other )
    {
        $re = "/^(0|[1-9]\d*)\.(0|[1-9]\d*)\.(0|[1-9]\d*)(?:-((?:0|[1-9]\d*|\d*[a-zA-Z-][0-9a-zA-Z-]*)(?:\.(?:0|[1-9]\d*|\d*[a-zA-Z-][0-9a-zA-Z-]*))*))?(?:\+([0-9a-zA-Z-]+(?:\.[0-9a-zA-Z-]+)*))?$/i";
        $c = array();
        $v = array();
        preg_match($re, $version, $c);
        preg_match($re, $other, $v);

        $cn = count($c);
        $vn = count($v);
        if ($cn < 2 || $vn < 2) return strcmp($version,$other ) > 0;

        //compare major version
        if ( strcmp( $c[1],$v[1] ) > 0 ) return true;
        if ( strcmp( $c[1],$v[1] ) < 0 ) return false;

        if ($cn < 3 || $vn < 3) return strcmp($version,$other ) > 0;

        //minor
        if ( strcmp( $c[2],$v[2] ) > 0 ) return true;
        if ( strcmp( $c[2],$v[2] ) < 0 ) return false;

        if ($cn < 4 || $vn < 4) return strcmp($version,$other ) > 0;

        //patch
        if ( strcmp( $c[3],$v[3] ) > 0 ) return true;
        if ( strcmp( $c[3],$v[3] ) < 0 ) return false;

        if ($cn < 5 && $vn < 5) return strcmp($version,$other ) > 0;
		if ($vn < 5) return false;
		if ($cn < 5) return true;

        //build 
        if ( strcmp( $c[4],$v[4] ) > 0 ) return true;
        if ( strcmp( $c[4],$v[4] ) < 0 ) return false;

        return strcmp($version,$other ) > 0;
    }

    function createTable( $name, $drop = false )
    {
        global $tablePrefix, $sqlMode;
        
        $q = new DBQuery();
        $qStr = "";
        if ($drop) $qStr = "DROP TABLE IF EXISTS `{$tablePrefix}{$name}`; ";
        if ($sqlMode == 'sqlite') $qStr = $qStr . "CREATE TABLE IF NOT EXISTS `{$tablePrefix}{$name}` (
                    `id`	INTEGER NOT NULL UNIQUE,
                    `uuid`	TEXT NOT NULL UNIQUE,
                    `data`	TEXT NOT NULL DEFAULT '{}',
                    `project`	TEXT,
                    `modified`	timestamp NOT NULL,
                    `removed`	INTEGER NOT NULL DEFAULT 0,
                    PRIMARY KEY(`id` AUTOINCREMENT) );";
        else $qStr = $qStr . "CREATE TABLE IF NOT EXISTS `{$tablePrefix}{$name}` (
                    `id` int(11) NOT NULL,
                    `uuid` varchar(36) COLLATE utf8_unicode_ci NOT NULL,
                    `data` mediumtext NOT NULL,
                    `project` mediumtext NULL,
                    `modified` timestamp NOT NULL,
                    `removed` tinyint(4) NOT NULL DEFAULT 0
                    ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
                ALTER TABLE `{$tablePrefix}{$name}`
                    ADD PRIMARY KEY (`id`),
                    ADD UNIQUE KEY `uuid` (`uuid`);
                ALTER TABLE `{$tablePrefix}{$name}`
                    MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
                ";
        $q->prepare($qStr);

        $q->execute();
        $q->close();
        return $q->isOK();
    }

    function createDeletedDataTable()
    {
        global $tablePrefix, $sqlMode;

        $q = new DBQuery();
        $qStr = "";

        if ($sqlMode == 'sqlite') $qStr = "CREATE TABLE IF NOT EXISTS `{$tablePrefix}deletedData` (
                    `id`	INTEGER NOT NULL UNIQUE,
                    `uuid`	TEXT NOT NULL UNIQUE,
                    PRIMARY KEY(`id` AUTOINCREMENT) );";

        else $qStr = "CREATE TABLE IF NOT EXISTS `{$tablePrefix}deletedData` (
                    `id` int(11) NOT NULL,
                    `uuid` varchar(36) COLLATE utf8_unicode_ci NOT NULL
                    ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
                ALTER TABLE `{$tablePrefix}deletedData`
                    ADD PRIMARY KEY (`id`),
                    ADD UNIQUE KEY `uuid` (`uuid`);
                ALTER TABLE `{$tablePrefix}deletedData`
                    MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
                ";

        $q->prepare($qStr);

        $q->execute();
        $q->close();
        return $q->isOK();
    }

    function printAndDie()
    {
        global $reply, $sessionTimeout, $log, $_SESSION, $scriptStartTime;

        $reply["serverUuid"] = SecurityManager::serverUuid();
        $reply["timeSpent"] = time() - $scriptStartTime;

        // Set time out
        $_SESSION['discard_after'] = time() + $sessionTimeout;

        $log->debugLog("Sending the reply.");
        debugSessionVar();

        die( json_encode($reply) );
    }

    
?>
