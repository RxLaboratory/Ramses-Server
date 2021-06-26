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

	// Edit this configuration file before running the install script at /install/index.php

	// === DEV MODE ===
	// Comment out these lines to disable dev mode !important!
	ini_set('display_errors', '1');
	ini_set('display_startup_errors', '1');
	error_reporting(E_ALL);1

	// ==== SQL SETTINGS ====

	// Host URL
	$sqlHost = "localhost";
	$sqlPort = 3306;
	// Database name
	$sqlDBName = "ramses";
	// User
	$sqlUser = "ramses";
	// Password
	$sqlpassword = "rZ63G4eW";
	// Table prefix
	// DO NOT CHANGE THIS, not working yet
	$tablePrefix = "ram";

	// ==== SESSION SETTINGS ====

	// Session timeout (seconds)
	$sessionTimeout = 1200;

	// ==== SECURITY ====

	// This key is used for password and other sensible data encryption.
	// It should be a random and unique string to your server instance.
	$serverKey = "BkDgj2dqLJbZY4US";
?>
