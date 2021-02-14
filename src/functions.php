<?php
    /**
	 * Logs in and returns the new session token
	 */
	function login()
	{
		$_SESSION["login"] = true;
		//Generate token
		$token = bin2hex(random_bytes(20));
		$_SESSION["token"] = $token;
		return $token;
	}

    /**
     * Logs out and reset the session token
     */
    function logout()
    {
        $_SESSION["login"] = false;
        $_SESSION["token"] = "";
        session_destroy();
    }

    /**
     * Hashes a password using the user shortname
     */
    function hashPassword($p, $u)
    {
        return hash( "sha3-512", $u . $p . $serverKey );
    }
?>