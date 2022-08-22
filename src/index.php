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

	//configuration and init 
	include ("config.php");
	include ("config_logs.php");
	include ("functions.php");
	include ("logger.php");
	include ("init.php");

	//prepare reply
	include ("reply.php");

	//get request metadata
	include ("clientmetadata.php");

	//ping
	include ("ping.php");

	$now = time();

	if (!$reply['accepted'])
	{
		//queries
		if ($installed)
		{
			// Check if session has expired
			$expired = $now > $_SESSION['discard_after'];

			if ($expired)
			{
				// this session has worn out its welcome; kill it and start a brand new one
				session_unset();
				session_destroy();
				session_start();

				$reply["message"] = "Your session has expired, you need to log-in.";
				$reply["query"] = "loggedout";
				$reply["success"] = false;
				$reply["accepted"] = false;
				logout("Disconnected (Session expired)");
				$_SESSION["expired"] = false;
			}
			else {
				//secured operations, check token first
				$token = getArg("token");
				if ($token != $_SESSION["sessionToken"])
				{
					$reply["message"] = "Invalid token! [Warning] This may be a security issue!";
					$reply["query"] = "loggedout";
					$reply["success"] = false;
					$reply["accepted"] = false;
					logout("Disconnected (Invalid token)");
				}
				else
				{
					//connect to database
					include('db.php');

					//login
					include ("login.php");

					if (!$reply['accepted'])
					{

					}
				}			
			}			
		}
		else
		{
			$reply["message"] = "This Ramses server is not installed yet.";
		}
	}

	// Set time out
	$_SESSION['discard_after'] = $now + $sessionTimeout;

	echo json_encode($reply);
?>
