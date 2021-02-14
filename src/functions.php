<?php
    /**
	 * Logs in and returns the new session token
	 */
	function login()
	{
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
        $_SESSION["login"] = false;
        $_SESSION["sessionToken"] = "";
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