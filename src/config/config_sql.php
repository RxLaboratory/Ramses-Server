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