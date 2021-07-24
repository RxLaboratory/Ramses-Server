<?php
    $ramsesVersion = "0.1.2-alpha";
	$installed = !file_exists("install/index.php");

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
	if (!isset($_SESSION["sessionToken"])) $_SESSION["sessionToken"] = "";
	if (!isset($_SESSION["userRole"])) $_SESSION["userRole"] = "standard";
	if (!isset($_SESSION["userUuid"])) $_SESSION["userUuid"] = "";
	if (!isset($_SESSION["login"])) $_SESSION["login"] = false;
	if (!isset($_SESSION["discard_after"])) $_SESSION["discard_after"] = 0;

	$now = time();

	if ($now > $_SESSION['discard_after'])
	{
		// this session has worn out its welcome; kill it and start a brand new one
		session_unset();
		session_destroy();
		session_start();

		if (!isset($_SESSION["sessionToken"])) $_SESSION["sessionToken"] = "";
		if (!isset($_SESSION["userRole"])) $_SESSION["userRole"] = "standard";
		if (!isset($_SESSION["userUuid"])) $_SESSION["userUuid"] = "";
		if (!isset($_SESSION["login"])) $_SESSION["login"] = false;
		if (!isset($_SESSION["discard_after"])) $_SESSION["discard_after"] = 0;
	
	}
	
	$_SESSION['discard_after'] = $now + $sessionTimeout;

	//add the "_" after table prefix if needed
	setupTablePrefix();

	//build table names
	$applicationfiletypeTable = $tablePrefix . "applicationfiletype";
	$applicationsTable = $tablePrefix . "applications";
	$assetgroupsTable = $tablePrefix . "assetgroups";
	$assetsTable = $tablePrefix . "assets";
	$colorspacesTable = $tablePrefix . "colorspaces";
	$filetypesTable = $tablePrefix . "filetypes";
	$pipesTable = $tablePrefix . "pipes";
	$projectassetgroupTable = $tablePrefix . "projectassetgroup";
	$projectsTable = $tablePrefix . "projects";
	$sequencesTable = $tablePrefix . "sequences";
	$shotsTable = $tablePrefix . "shots";
	$statesTable = $tablePrefix . "states";
	$statusTable = $tablePrefix . "status";
	$stepapplicationTable = $tablePrefix . "stepapplication";
	$stepsTable = $tablePrefix . "steps";
	$projectuserTable = $tablePrefix . "projectuser";
	$templateassetgroupsTable = $tablePrefix . "templateassetgroups";
	$templatestepsTable = $tablePrefix . "templatesteps";
	$usersTable = $tablePrefix . "users";
	$pipefileTable = $tablePrefix . "pipefile";
	$pipefilepipeTable = $tablePrefix . "pipefilepipe";
	$shotassetTable = $tablePrefix . "shotasset";
	$scheduleTable = $tablePrefix . "schedule";

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