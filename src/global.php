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

    // GLOBAL VARIABLES AND SETTINGS //

    // The version
    $ramsesVersion = "0.9.2-Beta";

    // Settings
    // Number of pages returned by fetch and pull
    $pageRowCount = 1000;
    // No more than a 1000 rows if SQLite anyway
    $SQLMaxRowPerRequest = 5000;

    // DEFAULT CONFIG VALUES //
    // Don't change these, edit config.php instead //

    // Set defaults for config

    $devMode = false;
	$logLevel = 'WARNING';
	$sqlMode = 'sqlite';
	$tablePrefix = "Ramses";
	$serverAddress = "localhost/ramses";
	$forceSSL = true;
	$disableCompression = false;
	$sessionTimeout = 1800;
	$cookieTimeout = 43200;
	$clientKey = "drHSV2XQ";

    // Logs config

    $enableLogs = false;
    $connexionLogs = false;
    $requestLogs = false;
    $debugLogs = false;
    $logsExpiration = 2;

?>