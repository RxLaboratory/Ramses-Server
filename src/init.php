<?php
    $ramsesVersion = "0.2.14-alpha";
	$installed = file_exists("config_security.php");

	// Set the timezone to UTC so it matches the SQL db
	date_default_timezone_set('UTC');

	// The encryption key
	if( $installed ) include( 'config_security.php' );
	else $encrypt_key = '';

	if ($devMode)
	{
		ini_set('display_errors', '1');
		ini_set('display_startup_errors', '1');
		error_reporting(E_ALL);
	}

	// server should keep session data for AT LEAST  sessionTimeout
	ini_set('session.gc_maxlifetime', $sessionTimeout);

	// each client should remember their session id for EXACTLY sessionTimeout
	session_set_cookie_params($cookieTimeout);

	session_start();
	
	// Init session variables
	if (!isset($_SESSION["sessionToken"])) $_SESSION["sessionToken"] = bin2hex(random_bytes(20));
	if (!isset($_SESSION["expired"])) $_SESSION["expired"] = false;
	if (!isset($_SESSION["userRole"])) $_SESSION["userRole"] = "standard";
	if (!isset($_SESSION["userUuid"])) $_SESSION["userUuid"] = "";
	if (!isset($_SESSION["userId"])) $_SESSION["userId"] = "";
	if (!isset($_SESSION["userName"])) $_SESSION["userName"] = "";
	if (!isset($_SESSION["login"])) $_SESSION["login"] = false;
	if (!isset($_SESSION["clientVersion"])) $_SESSION["clientVersion"] = "unknown";
	if (!isset($_SESSION["discard_after"])) $_SESSION["discard_after"] = 0;

	//add the "_" after table prefix if needed
	setupTablePrefix();

	//prepare log
	$log = new Logger();

	// Parse body content to make it quickly available later
	// Check the content type, accept either application/json or application/x-www-form-urlencoded
	$allHeaders = getallheaders();
	if (isset($allHeaders['Content-Type']))
	{
		$cType = $allHeaders['Content-Type'];
		$contentArray = explode(";", $cType);
		$contentType = "";
		$charset = "";
		$contentAsJson = false;
		$contentInPost = false;
		foreach( $contentArray as $c)
		{
			$c = trim($c);
			if ($c == "application/json")
			{
				$contentAsJson = true;
				$contentInPost = true;
				continue;
			}

			if ($c == "application/x-www-form-urlencoded")
			{
				$contentInPost = true;
				$contentAsJson = false;
				continue;
			}

			if (startsWith($c, "charset="))
			{
				$charsetArray = explode($c, "=");
				if (count($charsetArray) == 2)
				{
					$charset = trim($charsetArray[1]);
				}
				continue;
			}
		}

		// If json, parse it right now
		$bodyContent = array();
		if ($contentAsJson)
		{
			$rawBody = file_get_contents('php://input');
			$bodyContent = json_decode($rawBody, true);
		}
	}
	
	
?>