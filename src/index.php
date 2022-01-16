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

	if (!$reply['accepted'])
	{
		//queries
		if ($installed)
		{
			//connect to database
			include('db.php');

			//login first
			include ("login.php");

			if (!$reply['accepted'])
			{
				//secured operations, check token first
				$token = getArg("token");
				if ( $_SESSION["sessionToken"] != "")
				{
					if ($token == $_SESSION["sessionToken"])
					{
						if (hasArg("init"))
						{
							$reply["accepted"] = true;
							$reply["query"] = "init";

							// The reply is completed in corresponding categories

							$reply["message"] = "Initial data retrieved.";
							$reply["success"] = true;
						}

						include ("users.php");
						include ("projects.php");
						include ("steps.php");
						include ("templatesteps.php");
						include ("states.php");
						include ("templateassetgroups.php");
						include ("assetgroups.php");
						include ("assets.php");
						include ("sequences.php");
						include ("filetypes.php");
						include ("applications.php");
						include ("pipefiles.php");
						include ("pipes.php");
						include ("shots.php");
						include ("status.php");
						include ("schedule.php");
					}
					else 
					{
						$reply["message"] = "Invalid token! How did you arrive here?";
						$reply["query"] = "loggedout";
						$reply["success"] = false;
						$reply["accepted"] = false;
						logout("Disconnected (Invalid token)");
					}
				}
				else
				{
					$reply["message"] = "Your session has expired, you need to log-in.";
					$reply["query"] = "loggedout";
					$reply["success"] = false;
					$reply["accepted"] = false;
					logout("Disconnected (Session expired)");
				}
			}
		}
		else
		{
			$reply["message"] = "This Ramses server is not installed yet.";
		}
	}

	

	echo json_encode($reply);
?>
