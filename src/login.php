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

	if ( acceptReply( "login" ) )
	{
		$username = getArg( "username" );
		$password = getArg( "password" );
		$hashAlgo = getArg( "hashAlgo", "sha3-512" );

		$q = new DBQuery();
		$q->prepare( "SELECT `password`,`name`,`email`,`folderPath`,`uuid`,`role` FROM {$tablePrefix}users WHERE `shortName` = :shortName AND removed = 0;" );
		$q->bindShortName( $username );
		$q->execute();

		$testPass = $q->fetch();
		$q->close();

		if ( $testPass )
		{
			$found = true;
			$uuid = $testPass["uuid"];

			//check password
			if ( checkPassword($password, $uuid, $testPass["password"]) )
			{
				//login
				$role = $testPass["role"];
				// Role is hashed, find it
				$role = checkRole($role);
				// Name is encrypted
				$name = decrypt( $testPass["name"] );
				$token = login($uuid, $role, $username, $name);
				//reply content
				$content = array();
				$content["name"] = $name;
				$content["shortName"] = $username;
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
				$_SESSION["userId"] = $username;
				$_SESSION["userUuid"] = $uuid;
				logout("Connexion refused (invalid password)");
			}
		}
		else
		{
			$reply["message"] = "Invalid username";
			$reply["success"] = false;
			$_SESSION["userId"] = $username;
			logout("Connexion refused (invalid username)");
		}		
	}
?>
