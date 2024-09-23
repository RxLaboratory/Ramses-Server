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

	define('RAMROOT',dirname(__FILE__));

	// Measure time spent by the script
	$scriptStartTime = time();

	//global constants
	require_once("global.php");

	//configuration and init 
	require_once("config/config.php");
	require_once("functions.php");
	require_once("logger.php");
	require_once("session_manager.php");

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

	// ======== PUBLIC INTERFACE ========

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

	//connect to database
	require_once('db.php');

	//login
	include("users_reset_password.php");
	include("login.php");
	include("logout.php");

	// this session has worn out its welcome; kill it and start a brand new one
	if ($sessionTimeout >= 0 && time() > $_SESSION['discard_after'])
	{
		$log->debugLog("Session has expired.", "WARNING");
		logout("Your session has expired, you need to log-in.");
	}

	// ======== START PRIVATE INTERFACE ========
	// >>>>>>>> Check token to get into the private area
	$token = getArg("token");
	if ($token != $_SESSION["token"]){
		$log->debugLog("Disconnected (Invalid token).", "WARNING");
		logout("Invalid token! [Warning] This may be a security issue!");
	}

	//regularly clean the db
	include("db_clean.php");

	// projects and users management
	include("users_set_password.php");
	include("users_email.php");
	include("projects_get.php");
	include("projects_get_users.php");
	include("projects_set_current.php");

	// Sync methods
	include("sync.php");
	include("push.php");
	include("fetch.php");
	include("pull.php");

	// ========= ADMIN INTERFACE ========
	// >>>>>>>> Check if we're admin to get into this area
	// Admin
	if (!isAdmin())
		printAndDie();

	include("clean.php");
	include("projects_create.php");
	include("projects_remove.php");
	include("projects_assign.php");
	include("projects_unassign.php");
	include("users_create.php");
	include("users_remove.php");
	include("users_role.php");
	include("users_get.php");

	printAndDie();
