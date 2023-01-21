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

	// If there's no parameter, redirect to the admin section
	if (count($_GET) == 0) {
		header("Location: admin/");
		die();
	}

	$__ROOT__ = dirname(__FILE__);

	// Measure time spent by the script
	$scriptStartTime = time();

	require_once("include.php");

	require_once("functions.php");

	// INIT
	include("init.php");

	//prepare reply
	require_once("reply.php");

	// Maintenance mode
	if ($maintenance)
	{
		$reply["success"] = false;
		$reply["accepted"] = false;
		$reply["message"] = "The server is under maintenance. Please try again later.";
		$log->debugLog("The server is under maintenance.", "WARNING");
		printAndDie();
	}

	//get request metadata
	include("clientmetadata.php");

	// check version
	include('check_client_version.php');

	//ping
	include("ping.php");

	//if the server is not installed, can't do anything more
	if (!$installed)
	{
		$reply["success"] = false;
		$reply["accepted"] = false;
		$reply["message"] = "This Ramses server is not installed yet.";
		$log->debugLog("The server is not installed yet.", "FATAL");
		printAndDie();
	}

	// this session has worn out its welcome; kill it and start a brand new one
	if ($sessionTimeout >= 0 && time() > $_SESSION['discard_after'])
	{
		$log->debugLog("Session has expired.", "WARNING");
		logout("Disconnected (Session expired)", "Your session has expired, you need to log-in.");
	}

	//connect to database
	require_once('db.php');

	//login
	include("login.php");

	//secured operations, check token first
	$token = RequestParser::getArg("token");
	if ($token != $_SESSION["token"]){
		$log->debugLog("Disconnected (Invalid token).", "WARNING");
		logout("Disconnected (Invalid token)", "Invalid token! [Warning] This may be a security issue!");
	}

	include("db_clean.php");
	include("sync.php");
	include("push.php");
	include("fetch.php");
	include("pull.php");
	include("set_password.php");
	include("clean.php");

	printAndDie();

?>
