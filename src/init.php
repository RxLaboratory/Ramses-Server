<?php
	// Enable compression if the client supports it
	if(substr_count($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip'))
	{
		debugLog("GZip compression is enabled");
		ob_start("ob_gzhandler");
	}
	else
	{
		debugLog("GZip compression is disabled");
		ob_start();
	}

	require_once($__ROOT__."/config/config.php");
	require_once($__ROOT__."/functions.php");
	require_once($__ROOT__."/logger.php");
	require_once($__ROOT__."/session_manager.php");

	// Get the server address
	/*$currentURL = $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"];
	$currentURL = explode("?", $currentURL)[0];
	$serverAddress = $currentURL;
	debugLog("This is the current server address: " . $serverAddress);*/

    $ramsesVersion = "0.5.1-Beta";
	$installed = file_exists($__ROOT__."/config/config_security.php");

	// Set the timezone to UTC so it matches the SQL db
	date_default_timezone_set('UTC');

	// The encryption key
	if( $installed ) include( $__ROOT__."/config/config_security.php" );
	else $encrypt_key = '';

	// Enable dev mode
	if ($devMode)
	{
		ini_set('display_errors', '1');
		ini_set('display_startup_errors', '1');
		error_reporting(E_ALL);
	}

	// Start session

	// server should keep session data for AT LEAST  sessionTimeout
	ini_set('session.gc_maxlifetime', $sessionTimeout);

	// Get domain and path
	$addressArray = explode("/", $serverAddress);
	$domain = array_shift($addressArray);
	$path = "/" . join("/",$addressArray);
	if (!endsWith($path, "/")) $path = $path . "/";
	// Init session
	SessionManager::sessionStart("Ramses_Server", $cookieTimeout, $path, $domain, $forceSSL );
	
	// Init session variables
	if (!isset($_SESSION["token"])) $_SESSION["token"] = "";
	if (!isset($_SESSION["clientVersion"])) $_SESSION["clientVersion"] = "unknown";
	if (!isset($_SESSION["discard_after"])) $_SESSION["discard_after"] = 0;
	if (!isset($_SESSION["uuid"])) $_SESSION["uuid"] = "unknown";
	
	//add the "_" after table prefix if needed
	setupTablePrefix();

	//prepare log
	$log = new Logger();

	// Parse body content to make it quickly available later
	// Check the content type, accept application/json or application/x-www-form-urlencoded
	$allHeaders = getallheaders();
	if (isset($allHeaders['Content-Type']))
	{
		$cType = $allHeaders['Content-Type'];
		$contentArray = explode(";", $cType);
		$contentType = "";
		$charset = "";
		$ok = false;
		foreach( $contentArray as $c)
		{
			$c = trim($c);
			if ($c == "application/json")
			{
				$ok = true;
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
		if ($ok)
		{
			$rawBody = file_get_contents('php://input');
			$bodyContent = json_decode($rawBody, true);
		}
		else
		{
			$reply["success"] = false;
			$reply["message"] = "Sorry, malformed request. We accept only application/json POST";
			die(json_encode($reply));
		}
	}
?>