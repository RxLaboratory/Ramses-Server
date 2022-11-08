<?php
	// Enable compression if the client supports it
	$useGzip = substr_count($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip');
	if ($useGzip)
	{
		ob_start("ob_gzhandler");
	}
	else
	{
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

    $ramsesVersion = "0.7.0-Beta";
	$installed = file_exists($__ROOT__."/config/config_security.php");

	// Set the timezone to UTC so it matches the SQL db
	date_default_timezone_set('UTC');

	// The encryption key
	if( $installed ) include( $__ROOT__."/config/config_security.php" );
	else $encrypt_key = '';

	if (file_exists($__ROOT__."/config/config_server_uuid.php")) include( $__ROOT__."/config/config_server_uuid.php" );
	else $server_uuid = createServerUuid();

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

	//prepare log
	$log = new Logger();
	if ($useGzip) $log->debugLog("GZIP compression enabled", "DEBUG");
	else $log->debugLog("GZIP compression disabled", "DEBUG");
	$log->debugLog("Preparing session", "DEBUG");

	// Get domain and path
	$addressArray = explode("/", $serverAddress);
	$domain = array_shift($addressArray);
	$path = "/" . join("/",$addressArray);
	if (!endsWith($path, "/")) $path = $path . "/";
	// Init session
	SessionManager::sessionStart("Ramses_Server", $cookieTimeout, $path, $domain, $forceSSL );

	$log->debugLog("Session started", "DEBUG");
	
	// Init session variables
	if (!isset($_SESSION["token"])) $_SESSION["token"] = "";
	if (!isset($_SESSION["clientVersion"])) $_SESSION["clientVersion"] = "unknown";
	if (!isset($_SESSION["discard_after"])) $_SESSION["discard_after"] = 0;
	if (!isset($_SESSION["uuid"])) $_SESSION["uuid"] = "unknown";
	
	//add the "_" after table prefix if needed
	setupTablePrefix();

	// Parse body content to make it quickly available later
	// Check the content type, accept application/json or application/x-www-form-urlencoded
	$allHeaders = getallheaders();
	$rawBody = file_get_contents('php://input');
	$log->requestLog($allHeaders, $rawBody);

	$ok = true;
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
	}
	// If json, parse it right now
	$bodyContent = array();
	if ($ok)
	{
		$bodyContent = json_decode($rawBody, true);
	}
	else
	{
		$reply["success"] = false;
		$reply["message"] = "Sorry, malformed request. We accept only application/json POST";
		$log->debugLog("Malformed request, Content-Type is not application/json.", "WARNING");
		printAndDie();
	}
?>