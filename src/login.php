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

	if (hasArg("login"))
	{
		$reply["accepted"] = true;
		$reply["query"] = "login";

		$username = getArg( "username" );
		$password = getArg( "password" );

		if (strlen($username) > 0 AND strlen($password) > 0)
		{
			//query the database
			$rep = $db->prepare("SELECT `password`,`name`,`shortName`,`email`,`folderPath`,`uuid`,`role` FROM " . $tablePrefix . "users WHERE removed = 0;");
			$rep->execute(array('username' => $username));

			$found = false;

			while ( $testPass = $rep->fetch() )
			{
				// Check username
				$testUserName = decrypt( $testPass['shortName'] );

				if ( $testUserName != $username ) continue;

				$found = true;
				$uuid = $testPass["uuid"];

				//check password
				if ( checkPassword($password, $uuid, $testPass["password"]) )
				{
					//login
					$role = $testPass["role"];
					// Role is hashed, find it
					$role = checkRole($role);
					$token = login($uuid, $role);
					//reply content
					$content = array();
					$content["name"] = decrypt( $testPass["name"] );
					$content["shortName"] = $testUserName;
					$content["uuid"] = $uuid;
					$content["folderPath"] = $testPass["folderPath"];
					$content["role"] = $role;
					$content["token"] = $token;
					$content["email"] = decrypt( $testPass["email"] );
					$reply["content"] = $content;
					$reply["message"] = "Successful login. Welcome " . $content["name"] . "!";
					$reply["success"] = true;
				}
				else
				{
					$reply["message"] = "Invalid password";
					$reply["success"] = false;
					logout();
				}
			}
			$rep->closeCursor();
			
			if (!$found)
			{
				$reply["message"] = "Invalid username";
				$reply["success"] = false;
				logout();
			}
		}			
		else
		{
			$reply["message"] = "Invalid request, missing username or password";
			$reply["success"] = false;
			logout();
		}
	}
?>
