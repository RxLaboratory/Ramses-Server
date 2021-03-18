<?php
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
        return $_SESSION["userRole"] == "admin";
    }

    /**
     * Checks if the current user has project admin rights
     */
    function isProjectAdmin()
    {
        return $_SESSION["userRole"] == "admin" || $_SESSION["userRole"] == "project";
    }

    /**
     * Checks if the current user has lead rights
     */
    function isLead()
    {
        return $_SESSION["userRole"] == "admin" || $_SESSION["userRole"] == "lead" || $_SESSION["userRole"] == "project";
    }

    /**
     * Checks if this uuid is the current logged in user
     */
    function isSelf($uuid)
    {
        return $uuid == $_SESSION["userUuid"];
    }

    /**
     * Hashes a password using the user shortname
     */
    function hashPassword($p, $u)
    {
        GLOBAL $serverKey;
        return hash( "sha3-512", $u . $p . $serverKey );
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

   function setupTablePrefix() {
        global $tablePrefix;
        if (strlen($tablePrefix) > 0 && !endsWith($tablePrefix, "_")) $tablePrefix = $tablePrefix . "_";
   }
?>