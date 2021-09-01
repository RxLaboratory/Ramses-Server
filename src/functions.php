<?php

    function createEncryptionKey ()
    {
        $key_size = 32; // 256 bits
        $encryption_key = openssl_random_pseudo_bytes($key_size, $strong);

        $configSecFile = fopen("../config_security.php", "w");
        $encryption_key_txt = base64_encode($encryption_key);
        fwrite($configSecFile, "<?php \$encrypt_key = base64_decode('{$encryption_key_txt}'); ?>");
        fclose($configSecFile);
        chmod( "../config_security.php", 0600 );

        return $encryption_key;
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

        return base64_encode($enc_txt . '::' . $iv);
    }

    /**
     * Decrypts text stored in the database (base64)
     */
    function decrypt( $data )
    {
        global $encrypt_key;
        if ( $encrypt_key == '' ) return '';

        list($encrypted_data, $iv) = explode('::', base64_decode($data), 2);

        $dec_txt = openssl_decrypt(
            $encrypted_data,
            'AES-256-CBC',
            $encrypt_key,
            0,
            $iv
        );

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
    function login($uuid, $role)
    {
        //Keep session info
        $_SESSION["userRole"] = $role;
        $_SESSION["userUuid"] = $uuid;
        $_SESSION["login"] = true;
        //Generate token
        $_SESSION["sessionToken"] = bin2hex(random_bytes(20));
        return $_SESSION["sessionToken"];
    }

    /**
     * Logs out and reset the session token
     */
    function logout()
    {
        $_SESSION["userRole"] = "standard";
        $_SESSION["userUuid"] = "";
        $_SESSION["login"] = false;
        $_SESSION["sessionToken"] = "";
        session_destroy();
    }

    /**
     * Checks if the current user has admin rights
     */
    function isAdmin()
    {
        global $reply;

        $ok = $_SESSION["userRole"] == "admin";
        if (!$ok)
        {
            $reply["message"] = "Insufficient rights, you need to be Admin.";
            $reply["success"] = false;
        }
        return $ok;
    }

    /**
     * Checks if the current user has project admin rights
     */
    function isProjectAdmin()
    {
        global $reply;

        $ok = $_SESSION["userRole"] == "admin" || $_SESSION["userRole"] == "project";
        if (!$ok)
        {
            $reply["message"] = "Insufficient rights, you need to be Project Admin.";
            $reply["success"] = false;
        }
        return $ok;
    }

    /**
     * Checks if the current user has lead rights
     */
    function isLead()
    {
        global $reply;

        $ok = $_SESSION["userRole"] == "admin" || $_SESSION["userRole"] == "lead" || $_SESSION["userRole"] == "project";
        if (!$ok)
        {
            $reply["message"] = "Insufficient rights, you need to be Lead.";
            $reply["success"] = false;
        }
        return $ok;
    }

    /**
     * Checks if this uuid is the current logged in user
     */
    function isSelf($uuid)
    {
        global $reply;

        $ok = $uuid == $_SESSION["userUuid"];
        if (!$ok)
        {
            $reply["message"] = "Insufficient rights.";
            $reply["success"] = false;
        }
        return $ok;
    }

    /**
     * Hashes a password adding the user id at the beginning
     */
    function hashPassword($pswd, $uuid)
    {
        return password_hash(  $uuid . $pswd ,  PASSWORD_DEFAULT, ['cost' => 13]);
    }

    function checkPassword( $pswd, $uuid, $testPswd )
    {
        $pswd = $uuid . $pswd;
        return password_verify($pswd, $testPswd);
    }

    /**
     * Hashes the role to store it in the database
     */
    function hashRole( $r )
    {
        return hashPassword( $r, 'role' );
    }

    /**
     * Checks the hashed role got from the database and returns the plain text role
     */
    function checkRole ( $r )
    {
        if ($r == 'admin') return 'admin';
        if ($r == 'project') return 'project';
        if ($r == 'lead') return 'lead';
        if ($r == 'standard') return 'standard';
        if ( checkPassword( 'admin', 'role', $r ) ) return 'admin';
        if ( checkPassword( 'project', 'role', $r) ) return 'project';
        if ( checkPassword( 'lead', 'role', $r) ) return 'lead';
        return 'standard';
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
        global $contentInPost, $contentAsJson, $bodyContent;

        $decordedArg = "";

        // First, try from URL
        if ( hasArg( $name ) )
        {
            $decordedArg = rawurldecode ( $_GET[$name] );
        }
        // Not found, get from body
        else if ($contentInPost)
        {
            if ($contentAsJson)
            {
                if (isset($bodyContent[$name]))
                    $decordedArg = $bodyContent[$name];
            }
            else 
            {
                if (isset($_POST[$name]))
                    $decordedArg = rawurldecode ( $_POST[$name] );
            }
        }

        if ($decordedArg == "") return $defaultValue;       

        return checkForbiddenWords( $decordedArg );
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

    function checkArgs( $arglist )
    {
        global $reply;

        $ok = true;
        foreach( $arglist as $arg )
        {
            if ($arg == "")
            {
                $ok = false;
                break;
            }
        }
        if (!$ok)
        {
            $reply["message"] = "Invalid request, missing values";
            $reply["success"] = false;
        }
        return $ok;
    }

    function sqlRequest( $request, $message, $debug = false )
    {
        global $reply;

        if ($debug) $request->debugDumpParams();

        $ok = $request->execute();

        if (!$ok)
        {
            $reply["message"] = $rep->errorInfo()[2];
            $reply["success"] = false;
        }
        else if ($message != "")
        {
            $reply["message"] = $message;
            $reply["success"] = true;
        }
        
        return $ok;
    }

    function acceptReply($queryName)
    {
        global $reply;
        $reply["accepted"] = true;
		$reply["query"] = $queryName;
    }
?>