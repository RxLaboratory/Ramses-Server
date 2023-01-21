<?php
	require_once($__ROOT__."/config/config.php");

	// Enable compression if the client supports it
	$useGzip = substr_count($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip');
	if ($disableCompression) $useGzip = false;
	if ($useGzip)
	{
		ob_start("ob_gzhandler");
	}
	else
	{
		ob_start();
	}

	require_once($__ROOT__."/functions.php");

	// Get the server address
	/*$currentURL = $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"];
	$currentURL = explode("?", $currentURL)[0];
	$serverAddress = $currentURL;
	debugLog("This is the current server address: " . $serverAddress);*/

	$installed = file_exists($__ROOT__."/config/config_security.php");
	$maintenance = file_exists($__ROOT__."/maintenance");

	// Set the timezone to UTC so it matches the SQL db
	date_default_timezone_set('UTC');

	// Enable dev mode
	if ($devMode)
	{
		ini_set('display_errors', '1');
		ini_set('display_startup_errors', '1');
		error_reporting(E_ALL);
	}

	// Start session

	// server should keep session data for AT LEAST sessionTimeout
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
	if (!StrUtils::endsWith($path, "/")) $path = $path . "/";
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

	// Check the request
	if (!RequestParser::$isJson)
	{
		$reply["success"] = false;
		$reply["message"] = "Sorry, malformed request. We accept only application/json POST";
		$log->debugLog("Malformed request, Content-Type is not application/json.", "WARNING");
		printAndDie();
	}
?>