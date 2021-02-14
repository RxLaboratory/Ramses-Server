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

	if (isset($_GET["login"]))
	{
		$reply["accepted"] = true;
		$reply["query"] = "login";

		$username = "";
		$password = "";

		if (isset($_GET["username"])) $username = $_GET["username"];
		if (isset($_GET["password"])) $password = $_GET["password"];

		if (strlen($username) > 0 AND strlen($password) > 0)
		{
			//query the database
			$rep = $db->prepare("SELECT password,name,shortName,folderPath,uuid,role FROM " . $tablePrefix . "users WHERE shortName = :username ;");
			$rep->execute(array('username' => $username));
			$testPass = $rep->fetch();
			$rep->closeCursor();

			//check password
            //hash
			$uuid = $testPass["uuid"];
            $password = hashPassword( $password, $uuid );

			if ($testPass["password"] == $password)
			{
				$token = login();
				$content = array();
				$content["name"] = $testPass["name"];
				$content["shortName"] = $testPass["shortName"];
				$content["uuid"] = $uuid;
				$content["folderPath"] = $testPass["folderPath"];
                $content["role"] = $testPass["role"];
                $content["token"] = $token;
				$reply["content"] = $content;
				$reply["message"] = "Successful login. Welcome " . $testPass["name"] . "!";
				$reply["success"] = true;
			}
			else
			{
				$reply["message"] = "Invalid username or password";
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
