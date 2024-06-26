<?php
    /*
		Ramses: Rx Asset Management System
        
        This program is licensed under the GNU General Public License.

        Copyright (C) 2020-2024 Nicolas Dufresne and Contributors.

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

	// ==== SQL SETTINGS ====

	// either 'mysql', 'mariadb' or 'sqlite'
	// Note: with 'mysql', the minimum version of MySQL is 8.0.19
	$sqlMode = 'sqlite';
	// Table prefix
	// should be a random character string (use https://duckduckgo.com/?q=generate+password)
	// you can setup multiple instances on the same DB
	// if each use a different prefix
	$tablePrefix = "5H3VBzSY";

	// ==== MySQL/MariaDB SETTINGS ====

	// Host URL
	$sqlHost = "db";
	$sqlPort = 3306;
	// Database name
	$sqlDBName = "ramses";
	// User
	$sqlUser = "ramses";
	// Password
	$sqlpassword = "password";

	// ==== Performance & Storage ====

	// Frequency for database clean-up
	// Clean-ups help reduce the database size and fix potential errors,
	// But they may make the sync a bit longer,
	// so they should not be run too often.
	// The default is once a day and seems to be fine.
	// Default: 24 hours (86400 s)
	$dbCleanFrequency = 86400;

	// ==== SESSION & SECURITY SETTINGS ====

	// This must be the server public adress, exactly as used in the clients
	$serverAddress = "localhost/ramses";

	// Whether to accept only SSL connections
	// This should always be true, except maybe on dev environments.
	$forceSSL = true;

	// Compression may cause issues on some servers,
	// Set this to true to deactivate it.
	$disableCompression = false;

	// Session timeout (seconds)
	// The client will be disconnected after being idle for this time
	// 30 minutes by default (1800) are more than enough:
	// The official client makes a call at least every 2 minutes by default.
	// A negative value completely deactivates the timeout check.
	$sessionTimeout = 1800;
	// Max Session timeout (seconds)
	// The client will be disconnected no matter what after this time
	// 12 hours by default( 43200 )
	$cookieTimeout = 43200;

	// This should never be changed, unless you change the key before building the official client or implementing your own client.
	// It can be used to make sure only your own client, built by yourself, can connect to your own server. In this case, keep it secret!
	// It is used to hash passwords.
	$clientKey = "drHSV2XQ";

	// === DEV MODE & DEBUG Info ===

	// Activates printing the SQL & PHP errors.
	// For security reasons, it is important to set this to false in production mode
	$devMode = false;
	// Sets the minimum level of the logs in the server replies sent to the clients
	// One of: 'DATA', 'DEBUG', 'INFO', 'WARNING', 'CRITICAL', 'FATAL'
	$logLevel = 'WARNING';
?>
