<?php 
    // If this file is called directly, abort.
    if (!defined('RAMROOT')) die;

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
    
  	// ==== Performance & Storage ====

	// Frequency for database clean-up
	// Clean-ups help reduce the database size and fix potential errors,
	// But they may make the sync a bit longer,
	// so they should not be run too often.
	// The default is once a day and seems to be fine.
	// Default: 24 hours (86400 s)
	$dbCleanFrequency = 86400;

	// === DEV MODE & DEBUG Info ===

	// Activates printing the SQL & PHP errors.
	// For security reasons, it is important to set this to false in production mode
	$devMode = false;
	// Sets the minimum level of the logs in the server replies sent to the clients
	// One of: 'DATA', 'DEBUG', 'INFO', 'WARNING', 'CRITICAL', 'FATAL'
	$logLevel = 'WARNING';