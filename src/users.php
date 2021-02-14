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

    if (isset($_GET["updateUser"]))
    {
        $reply["accepted"] = true;
        $reply["query"] = "updateUser";

        $name = "";
        $shortName = "";
        $uuid = "";

        if (isset($_GET["name"])) $name = $_GET["name"];
        if (isset($_GET["shortName"])) $shortName = $_GET["shortName"];
        if (isset($_GET["uuid"])) $uuid = $_GET["uuid"];

        if (strlen($shortName) > 0 AND strlen($uuid) > 0)
        {
            $qString = "UPDATE " . $tablePrefix . "users SET shortName= :shortName ,name= :name WHERE uuid= :uuid ;";
            $rep = $db->prepare($qString);
            $rep->execute(array('shortName' => $shortName, 'name' => $name, 'uuid' => $uuid));
            $rep->closeCursor();

            $reply["message"] = "User \"" . $shortName . "\" updated.";
            $reply["success"] = true;
        }
        else
        {
            $reply["message"] = "Invalid request, missing values";
            $reply["success"] = false;
        }

    }
    else if (isset($_GET["updatePassword"]))
    {
        $reply["accepted"] = true;
        $reply["query"] = "updatePassword";

        $current = "";
        $new = "";
        $uuid = "";

        if (isset($_GET["current"])) $current = $_GET["current"];
        if (isset($_GET["new"])) $new = $_GET["new"];
        if (isset($_GET["uuid"])) $uuid = $_GET["uuid"];

        if (strlen($new) > 0 AND strlen($current) > 0 AND strlen($uuid) > 0)
        {
            //check password
            $rep = $db->prepare("SELECT password FROM " . $tablePrefix . "users WHERE uuid= :uuid ;");
            $rep->execute(array('uuid' => $uuid));
            $testPass = $rep->fetch();
            $rep->closeCursor();

            //hash current password
            $current = hashPassword( $current, $uuid );

            //check password
            if ($testPass["password"] == $current)
            {
                //hash new password
                $new = hashPassword( $new, $uuid );

                $qString = "UPDATE " . $tablePrefix . "users SET password= :new WHERE uuid= :uuid ;";
                $rep = $db->prepare($qString);
                $rep->execute(array('new' => $new, 'uuid' => $uuid));
                $rep->closeCursor();

                $reply["message"] = "Password succesfully updated.";
                $reply["success"] = true;
            }
            else
            {
                $reply["message"] = "Wrong current password.";
                $reply["success"] = false;
            }
        }
        else
        {
            $reply["message"] = "Invalid request, missing values.";
            $reply["success"] = false;
        }
    }
    else if (isset($_GET["getUsers"]))
    {
        $reply["accepted"] = true;
        $reply["query"] = "getUsers";
        
        $rep = $db->prepare("SELECT name,shortName,folderPath,uuid,role FROM " . $tablePrefix . "users ;");
        $rep->execute();

        $users = Array();

        while ($user = $rep->fetch())
        {
            $u = Array();
			$u['name'] = $user['name'];
			$u['shortName'] = $user['shortName'];
			$u['uuid'] = $user['uuid'];
			$u['folderPath'] = $user['folderPath'];
			$u['role'] = $user['role'];

			$users[] = $u;
        }

        $rep->closeCursor();

		$reply["content"] = $users;
		$reply["message"] = "Users list retrieved.";
		$reply["success"] = true;
    }

?>
