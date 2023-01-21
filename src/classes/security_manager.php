<?php
    /*
		Ramses: Rx Asset Management System
        
        This program is licensed under the GNU General Public License.

        Copyright (C) 20202-2021 Nicolas Dufresne and Contributors.

        This program is free software;
        you can redistribute it and/or modify it
        under the terms of the GNU General Public License
        as published by the Free Software Foundation;
        either version 3 of the License, or (at your option) any later version.

        This program is distributed in the hope that it will be useful,
        but WITHOUT ANY WARRANTY; without even the implied warranty of
        MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
        See the GNU General Public License for more details.

        You should have received a copy of the *GNU General Public License* along with this program.
        If not, see http://www.gnu.org/licenses/.
	*/

    /**
     * Useful methods to handle strings
     */
    class SecurityManager
    {
        static private $encryption_key = '';
        static private $password_cost = 7;

        static function init()
        {
            global $__ROOT__;

            if (file_exists($__ROOT__."/config/config_security.php")) {
                include($__ROOT__."/config/config_security.php");
                SecurityManager::$encryption_key = $encrypt_key;
                SecurityManager::$password_cost = $pass_cost;
            }
        }
        
        /**
         * Generates a random password
         */
        static function generatePassword( $length=8 )
        {
           $bytes = openssl_random_pseudo_bytes( 30 );
           return substr(
               base64_encode($bytes),
               0,
               $length);
        }

        static function createEncryptionKey ()
        {
            global $__ROOT__;

            // Compute appropriate cost for passwords
            $timeTarget = 0.1; // 100 milliseconds 
            do {
                SecurityManager::$password_cost++;
                $start = microtime(true);
                password_hash("test", PASSWORD_BCRYPT, ["cost" => SecurityManager::$password_cost]);
                $end = microtime(true);
            } while (($end - $start) < $timeTarget);


            // Generate unique encryption key
            $key_size = 32; // 256 bits
            SecurityManager::$encryption_key = openssl_random_pseudo_bytes($key_size, $strong);

            $configSecFile = fopen($__ROOT__."/config/config_security.php", "w");
            $encryption_key_txt = base64_encode(SecurityManager::$encryption_key);
            $cost = SecurityManager::$password_cost;
            $ok = fwrite($configSecFile, "<?php\n\$encrypt_key = base64_decode('{$encryption_key_txt}');\n\$pass_cost = {$cost};\n?>");
            fclose($configSecFile);

            if (!$ok || !file_exists($__ROOT__."/config/config_security.php"))
                return false;

            chmod( $__ROOT__."/config/config_security.php", 0600 );

            return true;
        }

        static function encryptionKey()
        {
            return SecurityManager::$encryption_key;
        }

        static function passwordCost()
        {
            return SecurityManager::$password_cost;
        }

        static function createServerUuid()
        {
            global $__ROOT__;
            // Create this server's UUID
            $configUUIDFile = fopen($__ROOT__."/config/config_server_uuid.php", "w");
            $server_uuid = UUID::create();
            $ok = fwrite($configUUIDFile, "<?php\n\$server_uuid = \"{$server_uuid}\";\n?>");
            fclose($configUUIDFile);
            if ($ok) return $server_uuid;
            else return "";
        }

        static function serverUuid()
        {
            global $__ROOT__;
            if (file_exists($__ROOT__."/config/config_server_uuid.php")) include( $__ROOT__."/config/config_server_uuid.php" );
	        else $server_uuid = SecurityManager::createServerUuid();
            return $server_uuid;
        }

        static function encrypt( $txt )
        {
            $encryption_key = SecurityManager::encryptionKey();
            if ( $encryption_key == '' ) return '';
    
            // Generate an initialization vector
            $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length('aes-256-cbc'));
    
            $enc_txt = openssl_encrypt(
                $txt,                 // data
                'AES-256-CBC',        // cipher and mode
                $encryption_key,      // secret key
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
        static function decrypt( $data )
        {
            $encryption_key = SecurityManager::encryptionKey();
            if ( $encryption_key == '' ) return '';
            if (!SecurityManager::isEncrypted($data)) return $data;

            $dec_txt = "";

            try {
                list($encrypted_data, $iv) = explode('::', base64_decode($data), 2);
                $iv = base64_decode( $iv );    

                $dec_txt = openssl_decrypt(
                    $encrypted_data,
                    'AES-256-CBC',
                    $encryption_key,
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
        static function isEncrypted( $data )
        {
            $test = base64_decode($data, true);
            if (!$test) return false;
            if( !strpos($test, '::') ) return false;
            return true;
        }

        /**
         * Hashes a password with a salt at the beginning
         */
        static function hashPassword($pswd, $salt)
        {
            $pass_cost = SecurityManager::$password_cost;
            return password_hash(  $salt . $pswd ,  PASSWORD_DEFAULT, ['cost' => $pass_cost]);
        }

        static function checkPassword( $pswd, $salt, $testPswd )
        {
            $pswd = $salt . $pswd;
            return password_verify($pswd, $testPswd);
        }

    }
?>