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

	// ==== RAMSES SERVER CONFIG ====

	// Edit the configuration files
	// listed just below
	// before running the install script at /install/index.php

	require_once(RAMROOT.'/config/config_sql.php');
	require_once(RAMROOT.'/config/config_emails.php');
	require_once(RAMROOT.'/config/config_session.php');
	require_once(RAMROOT.'/config/config_debug.php');
	require_once(RAMROOT.'/config/config_logs.php');

