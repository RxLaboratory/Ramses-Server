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
	include ("functions.php");
	include ("init.php");

	//prepare reply
	include ("reply.php");

	//ping
	include ("ping.php");
	//queries
	if ($installed)
	{
		//connect to database
		include('db.php');

		//login first
		include ("login.php");

		//secured operations, check token first
		$token = "";
		if (isset($_GET["token"])) $token = $_GET["token"];
		if ( isset($_SESSION["sessionToken"]) && $_SESSION["sessionToken"] != "" && $token == $_SESSION["sessionToken"] )
		{
			include ("users.php");
			include ("projects.php");
			include ("steps.php");
			include ("templatesteps.php");
		}
		else if (!$reply["accepted"])
		{
			$reply["message"] = "Invalid token. Are you logged in?";
		}
	}
	else
	{
		if ($reply["type"] != "ping")
		{
			$reply["message"] = "This Ramses server is not installed yet.";
		}
	}

	echo json_encode($reply);
?>
