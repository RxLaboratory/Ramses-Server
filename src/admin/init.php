<?php

    $installed = file_exists($__ROOT__."/config/config_security.php");
    $maintenance = file_exists($__ROOT__."/maintenance");

    // Get domain and path
	$addressArray = explode("/", $serverAddress);
	$domain = array_shift($addressArray);
	$path = "/" . join("/",$addressArray);
	if (!StrUtils::endsWith($path, "/")) $path = $path . "/";
	// Init session
	SessionManager::sessionStart("Ramses_Server", $cookieTimeout, $path, $domain, $forceSSL );

    // Init session variables
	if (!isset($_SESSION["loggedin"])) $_SESSION["loggedin"] = false;
	if (!isset($_SESSION["discard_after"])) $_SESSION["discard_after"] = 0;
?>