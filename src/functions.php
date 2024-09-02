<?php
    require_once($__ROOT__."/config/config.php");
    require_once($__ROOT__."/logger.php");
    require_once($__ROOT__."/session_manager.php");
    require_once($__ROOT__."/reply.php");

    if (!function_exists('getallheaders')) {
        function getallheaders() {
            $headers = [];
            foreach ($_SERVER as $name => $value) {
                if (substr($name, 0, 5) == 'HTTP_') {
                    $headers[str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($name, 5)))))] = $value;
                }
            }
            return $headers;
        }
    }

    function debugSessionVar() {
        global $log;
        $jsonSession = $_SESSION;
        if (isset($jsonSession["token"]))
            $jsonSession["token"] = "Hidden-Token";
        $jsonSession = json_encode($jsonSession);
        //$log->debugLog("These are the session variables:\n" . $jsonSession, "DATA");
    }

    function createEncryptionKey ()
    {
        global $__ROOT__;
        // Compute appropriate cost for passwords
        $timeTarget = 0.1; // 100 milliseconds 
        $cost = 7;
        do {
            $cost++;
            $start = microtime(true);
            password_hash("test", PASSWORD_BCRYPT, ["cost" => $cost]);
            $end = microtime(true);
        } while (($end - $start) < $timeTarget);


        // Generate unique encryption key
        $key_size = 32; // 256 bits
        $encryption_key = openssl_random_pseudo_bytes($key_size, $strong);

        $configSecFile = fopen($__ROOT__."/config/config_security.php", "w");
        if (!$configSecFile) return "";
        $encryption_key_txt = base64_encode($encryption_key);
        $ok = fwrite($configSecFile, "<?php\n\$encrypt_key = base64_decode('{$encryption_key_txt}');\n\$pass_cost = {$cost};\n?>");
        fclose($configSecFile);

        if (!$ok || !file_exists($__ROOT__."/config/config_security.php"))
        {
            echo( "    ▫ Failed. Could not write the key file. We need write permissions in the ramses folder." );
            echo("<p>If you can't grant this permission, copy the code below, and paste it in a new <strong>config/config_security.php</strong> file,");
            echo("<br />upload this new file to the server, and refresh this page.</p>");
            die("<strong><code>&lt;?php\n\$encrypt_key = base64_decode('{$encryption_key_txt}');\n\$pass_cost = {$cost};\n?&gt;</strong></code>");
        }

        chmod( $__ROOT__."/config/config_security.php", 0600 );

        return $encryption_key;
    }

    function createServerUuid()
    {
        global $__ROOT__;
        // Create this server's UUID
        $configUUIDFile = fopen($__ROOT__."/config/config_server_uuid.php", "w");
        $server_uuid = uuid();
        $ok = fwrite($configUUIDFile, "<?php\n\$server_uuid = \"{$server_uuid}\";\n?>");
        fclose($configUUIDFile);
        if ($ok) return $server_uuid;
        else return "";
    }

    /**
     * Checks if a given PHP function is enabled on the server
     * @param string $function_name The name of the function
     * @return boolean
     */
    function is_function_enabled($function_name)
    {
        // False if the function is not in the list of disabled functions
        return strpos(
            ini_get('disable_functions'),
            $function_name
            ) === false;
    }

    /**
     * Cleans the global $serverAddress value,
     * i.e. removes the protocol if it is included.
     * @return Array Strings: the domain (without port) and path.
     */
    function cleanServerAddress()
    {
        global $serverAddress;
        
        // Remove protocol
        $serverAddress  = str_replace('http://', '', $serverAddress);
        $serverAddress  = str_replace('https://', '', $serverAddress);

        // Get domain and path
        $addressArray = explode("/", $serverAddress);
        $domain = array_shift($addressArray);

        // Remove port
        $domain = explode(':', $domain);
        $domain = array_shift($domain);
        $path = "/" . join("/",$addressArray);
        if (!endsWith($path, "/")) $path = $path . "/";

        return [$domain, $path];
    }

    /**
     * A safe way to set_time_limit
     * i.e. No error if the server doesn't allow it,
     * or if it is unlimited
     * @param int $timeout
     */
    function update_time_limit($timeout)
    {
        $max = (int)ini_get('max_execution_time');
        
        //Don't bother if unlimited
        if ($max == 0)
            return;

        // If the function is disabled, can't do
        if (!is_function_enabled('set_time_limit'))
            return;

        // Ignore errors
        @set_time_limit($timeout);
    }

    /**
     * Encrypts some text to store in the database
     */
    function encrypt( $txt )
    {
        global $encrypt_key;
        if ( $encrypt_key == '' ) return '';

        // Generate an initialization vector
        $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length('aes-256-cbc'));

        $enc_txt = openssl_encrypt(
            $txt,                 // data
            'AES-256-CBC',        // cipher and mode
            $encrypt_key,         // secret key
            0,                    // options (not used)
            $iv                   // initialisation vector
        );

        // The IV may contain the separator ('::'), base64 encoding it fixes the issue
        $iv = base64_encode( $iv );
        return base64_encode($enc_txt . '::' . $iv);
    }

    /**
     * Decrypts text stored in the database (base64)
     */
    function decrypt( $data )
    {
        global $encrypt_key;
        if ( $encrypt_key == '' ) return '';
        if (!isEncrypted($data)) return $data;

        $dec_txt = "";

        try {
            list($encrypted_data, $iv) = explode('::', base64_decode($data), 2);
            $iv = base64_decode( $iv );    

            $dec_txt = openssl_decrypt(
                $encrypted_data,
                'AES-256-CBC',
                $encrypt_key,
                0,
                $iv
            );
        }
        catch (exception $e) {
            return "";
        }

        return $dec_txt;
    }

    /**
     * Checks if a data is already encrypted
     */
    function isEncrypted( $data )
    {
        $test = base64_decode($data, true);
        if (!$test) return false;
        if( !strpos($test, '::') ) return false;
        return true;
    }

    /**
     * Logs in and returns the new session token
     */
    function login($userid, $uuid, $role, $id, $name)
    {
        global $log, $_SESSION;

        // Keep session info
        $_SESSION["userid"] = $userid;
        $_SESSION["userUuid"] = $uuid;
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
    function logout($message = "Logged out.")
    {
        global $reply, $_SESSION;

        $reply["message"] = $message;
        $reply["query"] = "loggedout";
        $reply["success"] = false;
        $reply["accepted"] = false;

        $_SESSION["userid"] = -1;
        $_SESSION["userUuid"] = "unknown";

        SessionManager::sessionEnd();
        
        printAndDie();
    }

    function isAdmin()
    {
        global $_SESSION, $tablePrefix;
        $q = new DBQuery();
        $q->prepare("SELECT `data` FROM `{$tablePrefix}RamUser` WHERE `id` = :userid ;");
        $q->bindStr("userid", $_SESSION["userid"]);
        $q->execute();
        $row = $q->fetch();
        $q->close();
        
        $data = decrypt( $row['data'] );
        $data = json_decode($data, true);

        if (!isset($data['role'])) return false;
        return strtolower($data['role']) == 'admin';
    }

    function setCurrentProject($projectUuid)
    {
        global $tablePrefix, $log;

        // Check if the current user is assigned to this project
        // And keep the project in the session vars

        $q = new DBQuery();
        $qstr = "SELECT `{$tablePrefix}ServerProjectUser`.`project_id`, `{$tablePrefix}RamProject`.`uuid`
                FROM `{$tablePrefix}ServerProjectUser`
                LEFT JOIN `{$tablePrefix}RamProject`
                    ON `{$tablePrefix}ServerProjectUser`.`project_id` = `{$tablePrefix}RamProject`.`id`
                WHERE `{$tablePrefix}RamProject`.`uuid` = :projectUuid ;";
        $q->prepare($qstr);
        $q->bindStr("projectUuid", $projectUuid);
        $q->execute();
        $project = $q->fetch();
        $q->close();
        if (!$project) {
            $reply["message"] = "Sorry, either this project doesn't exist or you're not assigned to it ($projectUuid).";
            $reply["success"] = false;
            $log->debugLog("User not assigned to project, or missing project $projectUuid", "WARNING");
            printAndDie();
        }

        $_SESSION["projectUuid"] = $project["uuid"];
        $_SESSION["projectid"] = $project["project_id"];

        return $_SESSION["projectUuid"];
    }

    /**
     * Hashes a password adding the user id at the beginning
     */
    function hashPassword($pswd, $uuid)
    {
        global $pass_cost;
        return password_hash(  $uuid . $pswd ,  PASSWORD_DEFAULT, ['cost' => $pass_cost]);
    }

    function checkPassword( $pswd, $uuid, $testPswd )
    {
        $pswd = $uuid . $pswd;
        return password_verify($pswd, $testPswd);
    }

    /**
     * Tests if a string starts with a substring
     */
    function startsWith( $string, $substring ) {
        $length = strlen( $substring );
        return substr( $string, 0, $length ) === $substring;
    }

    /**
        * Tests if a string ends with a substring
        */
    function endsWith( $string, $substring ) {
        $length = strlen( $substring );
        if( !$length ) {
            return true;
        }
        return substr( $string, -$length ) === $substring;
    }

    /**
        * Prepares the prefix for SQL table names (adds a "_" at the end if needed)
        */
    function setupTablePrefix() {
        global $tablePrefix;
        if (strlen($tablePrefix) > 0 && !endsWith($tablePrefix, "_")) $tablePrefix = $tablePrefix . "_";
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

    /**
        * Gets an argument from the url
        */
    function getArg($name, $defaultValue = "")
    {
        global $bodyContent;

        $decordedArg = "";

        if (isset($bodyContent[$name]))
        {
            $decordedArg = $bodyContent[$name];
        }

        if ($decordedArg === "") return $defaultValue;       

        if ( is_string($decordedArg) )
            return checkForbiddenWords( $decordedArg );
        else
            return $decordedArg;
    }

    function getAttr($name, $obj, $defaultValue = "")
    {
        $attr = "";
        if (isset($obj[$name]))
            $attr = $obj[$name];

        if ($attr == "") return $defaultValue;

        return $attr;
    }

    /**
        * Check if the URL has the given arg
        */
    function hasArg( $name )
    {
        return isset($_GET[$name]);
    }

    /**
        * Generates a pseudo-random UUID
        */
    function uuid() {
        return sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',

        // 32 bits for "time_low"
        mt_rand(0, 0xffff), mt_rand(0, 0xffff),

        // 16 bits for "time_mid"
        mt_rand(0, 0xffff),

        // 16 bits for "time_hi_and_version",
        // four most significant bits holds version number 4
        mt_rand(0, 0x0fff) | 0x4000,

        // 16 bits, 8 bits for "clk_seq_hi_res",
        // 8 bits for "clk_seq_low",
        // two most significant bits holds zero and one for variant DCE1.1
        mt_rand(0, 0x3fff) | 0x8000,

        // 48 bits for "node"
        mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
        );
    }

    /**
     * Recursively deletes a directory
     * @param string The path to delete, must be a directory
     * @return bool true on success
     */
    function deleteDir($dirPath) {
        if (! is_dir($dirPath)) {
            throw new InvalidArgumentException("$dirPath must be a directory");
        }
        if (substr($dirPath, strlen($dirPath) - 1, 1) != '/') {
            $dirPath .= '/';
        }
        $files = glob($dirPath . '*', GLOB_MARK);
        foreach ($files as $file) {
            if (is_dir($file)) {
                if (!deleteDir($file)) return false;
            } else {
                if (!unlink($file)) return false;
            }
        }
        return rmdir($dirPath);
    }

    function acceptReply($queryName)
    {
        global $reply;

        // Already accepted
        if ($reply["accepted"]) return false;
        // Not this query
        if (!hasArg($queryName)) return false;

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

    function createFolder( $path, $recursive=false, $addIndex = true )
    {
        if (is_file($path)) return;
        if (!is_dir($path)) mkdir($path, 0700, $recursive);
        
        if ($addIndex)
        {
            if (substr($path, strlen($path) - 1, 1) != '/') {
                $path .= '/';
            }

            if (is_file($path . "index.html")) return;

            $file = fopen($path . "index.html", "a");
            if (!$file) return;
            fwrite($file, "<h1>Forbidden</h1>");
            fclose($file);
        }
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
                    `id` INT PRIMARY KEY NOT NULL UNIQUE AUTO_INCREMENT,
                    `uuid` varchar(36) NOT NULL UNIQUE,
                    `data` mediumtext NOT NULL,
                    `project` mediumtext NULL,
                    `modified` timestamp NOT NULL,
                    `removed` tinyint(1) NOT NULL DEFAULT 0
                    ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
                ";
        $q->prepare($qStr);

        $q->execute();
        $q->close();
        return $q->isOK();
    }

    function createUserTable( $drop = false )
    {
        global $tablePrefix;

        if ( !createTable("RamUser", $drop) )
            return false;

        // Add username and password rows
        $q = new DBQuery();
        $q->prepare("ALTER TABLE `{$tablePrefix}RamUser`
            ADD  `userName` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL AFTER `uuid`,
            ADD  `password` TEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL AFTER `userName`;
            ");

        $q->execute();
        $q->close();
    }

    function createProjectUserTable( $drop = false )
    {
        global $tablePrefix, $sqlMode;
        
        $name = "ServerProjectUser";
        $q = new DBQuery();
        $qStr = "";
        if ($drop) $qStr = "DROP TABLE IF EXISTS `{$tablePrefix}{$name}`; ";
        if ($sqlMode == 'sqlite') $qStr = $qStr . "CREATE TABLE IF NOT EXISTS `{$tablePrefix}{$name}` (
                    `id`	INTEGER NOT NULL UNIQUE,
                    `user_id`	INTEGER NOT NULL,
                    `project_id`	INTEGER NOT NULL,
                    PRIMARY KEY(`id` AUTOINCREMENT),
                    CONSTRAINT `projectUser` UNIQUE(`user_id`,`project_id`),
                    CONSTRAINT `user` FOREIGN KEY(`user_id`) 
                        REFERENCES `{$tablePrefix}RamUser`(`id`) 
                        ON DELETE CASCADE ON UPDATE CASCADE,
                    CONSTRAINT `project` FOREIGN KEY(`project_id`) 
                        REFERENCES `{$tablePrefix}RamProject`(`id`) 
                        ON DELETE CASCADE ON UPDATE CASCADE
                    );";
        else $qStr = $qStr . "CREATE TABLE IF NOT EXISTS `{$tablePrefix}{$name}` (
                    `id` INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
                    `user_id` INT NOT NULL,
                    `project_id` INT NOT NULL
                    ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
                    ALTER TABLE `{$tablePrefix}{$name}` 
                        ADD CONSTRAINT `user` FOREIGN KEY (`user_id`) 
                            REFERENCES `{$tablePrefix}RamUser`(`id`) 
                            ON DELETE CASCADE ON UPDATE CASCADE; 
                    ALTER TABLE `{$tablePrefix}{$name}` 
                        ADD CONSTRAINT `project` FOREIGN KEY (`project_id`) 
                            REFERENCES `{$tablePrefix}RamProject`(`id`) 
                            ON DELETE CASCADE ON UPDATE CASCADE;
                    ALTER TABLE `{$tablePrefix}{$name}`
                        ADD UNIQUE `projectUser` (`user_id`, `project_id`); 
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
                    `id` int(11) NOT NULL UNIQUE AUTO_INCREMENT PRIMARY KEY,
                    `uuid` varchar(36) COLLATE utf8_unicode_ci NOT NULL
                    ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
                ";

        $q->prepare($qStr);

        $q->execute();
        $q->close();
        return $q->isOK();
    }

    function printAndDie()
    {
        global $reply, $sessionTimeout, $log, $_SESSION, $server_uuid, $scriptStartTime;

        $reply["serverUuid"] = $server_uuid;
        $reply["timeSpent"] = time() - $scriptStartTime;

        // Set time out
        $_SESSION['discard_after'] = time() + $sessionTimeout;

        $log->debugLog("Sending the reply.");
        debugSessionVar();

        die( json_encode($reply, JSON_INVALID_UTF8_SUBSTITUTE ) );
    }

    // Useful functions to handle cache
    function loadCache($filePath)
    {
        $file = fopen($filePath, "r");
        if ($file)
        {
            // Lock shared
            if (!flock($file, LOCK_SH))
                return array();

            $dataStr = fread($file, filesize($filePath));

            // Unlock
            flock($file, LOCK_UN);
            fclose($file);
            
            return json_decode($dataStr, true);
        }
        else return array();
    }

    function saveCache($filePath, $rows)
    {
        $cacheStr = json_encode($rows);
        $file = fopen($filePath, "w");
        if ($file)
        {
            // Lock exclusive
            if (!flock($file, LOCK_EX))
                return;

            fwrite($file, $cacheStr);
            fflush($file);

            // Unlock
            flock($file, LOCK_UN);
            fclose($file);
        }
        
    }
?>
