<?php

    function createEncryptionKey ()
    {
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

        $configSecFile = fopen("../config_security.php", "w");
        $encryption_key_txt = base64_encode($encryption_key);
        $ok = fwrite($configSecFile, "<?php\n\$encrypt_key = base64_decode('{$encryption_key_txt}');\n\$pass_cost = {$cost};\n?>");
        fclose($configSecFile);

        if (!$ok || !file_exists("../config_security.php"))
        {
            echo( "    ▫ Failed. Could not write the key file. We need write permissions in the ramses folder." );
            echo("<p>If you can't grant this permission, copy the code below, and paste it in a new <strong>config_security.php</strong> file,");
            echo("<br />upload this new file to the server, and refresh this page.</p>");
            die("<strong><code>&lt;?php\n\$encrypt_key = base64_decode('{$encryption_key_txt}');\n\$pass_cost = {$cost};\n?&gt;</strong></code>");
        }

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
    function login($uuid, $role, $id, $name)
    {
        global $log;
        //Keep session info
        $_SESSION["userRole"] = $role;
        $_SESSION["userUuid"] = $uuid;
        $_SESSION["userId"] = $id;
        $_SESSION["userName"] = $name;
        $_SESSION["login"] = true;
        //Log
        $log->login();
        //Generate token
        $_SESSION["sessionToken"] = bin2hex(random_bytes(20));
        return $_SESSION["sessionToken"];
    }

    /**
     * Logs out and reset the session token
     */
    function logout($reason="logout")
    {
        global $log;
        //Log
        $log->logout($reason);

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
        global $pass_cost;
        return password_hash(  $uuid . $pswd ,  PASSWORD_DEFAULT, ['cost' => $pass_cost]);
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
        return password_hash(  $r ,  PASSWORD_DEFAULT, ['cost' => 4]);
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
        if ( checkPassword( 'admin', '', $r ) ) return 'admin';
        if ( checkPassword( 'project', '', $r) ) return 'project';
        if ( checkPassword( 'lead', '', $r) ) return 'lead';
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

    /**
     * Recursively deletes a directory
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
                deleteDir($file);
            } else {
                unlink($file);
            }
        }
        rmdir($dirPath);
    }

    function acceptReply($queryName, $role = "")
    {
        global $reply;

        // Already accepted
        if ($reply["accepted"]) return false;
        // Not this query
        if (!hasArg($queryName)) return false;

        // Got the right one!
        $reply["query"] = $queryName;
        $reply["accepted"] = true;

        // Check privileges
        if ($role == 'admin') if (!isAdmin()) return false;
        if ($role == 'projectAdmin') if (!isProjectAdmin()) return false;
        if ($role == 'lead') if (!isLead()) return false;

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
?>