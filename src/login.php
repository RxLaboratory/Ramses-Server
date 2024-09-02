<?php
    require_once($__ROOT__."/functions.php");
    require_once($__ROOT__."/reply.php");

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

        $log->debugLog("Login...", "INFO");

        if (strlen($username) == 0)
        {
            $reply["message"] = "Missing user name";
            $reply["success"] = false;
            $log->debugLog("Missing user name", "WARNING");
            logout("Connexion refused (invalid password)");
        }

        if (strlen($password) == 0)
        {
            $reply["message"] = "Missing password";
            $reply["success"] = false;
            $log->debugLog("Missing password", "WARNING");
            logout("Connexion refused (invalid password)");
        }

        $q = new DBQuery();
        $q->vacuum();
        $q->prepare( "SELECT `id`, `uuid`,`userName`,`password`,`data`, `modified` FROM `{$tablePrefix}RamUser` WHERE `userName` = :username AND removed = 0;" );
        $q->bindStr( "username", $username );
        $q->execute();

        // In case there are multiple users with the same name, find the one with the right password
        // and remove the others
        $ok = false;
        $users = [];
        while ($row = $q->fetch())
        {
            $users[] = $row;
            $ok = true;
        }

        $q->close();

        if (!$ok)
        {
            $reply["message"] = "Invalid password or username";
            $reply["success"] = false;
            $log->debugLog("Invalid username: {$username}", "WARNING");
            logout("Connexion refused (invalid username: {$username})");
        }
        
        $ok = false;
        $uuid = "";
        foreach($users as $user)
        {
            $uuid = $user["uuid"];
            $userid = $user["id"];
            $dataStr = $user["data"];
            $tPassword = $user["password"];
            $modified = $user["modified"];
            // decrypt data
            $dataStr = decrypt( $dataStr );
            $dataArr = json_decode( $dataStr, true);

            //check password
            if ( !checkPassword($password, $uuid, $tPassword ) )
            {
                continue;
            }

            // Get other data for the log
            $name = "unknown";
            if ( isset($dataArr["name"]) ) $name = $dataArr["name"];
            $role = "unknown";
            if ( isset($dataArr["role"]) ) $role = $dataArr["role"];

            $log->debugLog("{$name} has logged in as {$role}.", "INFO");

            // Login
            $token = login($userid, $uuid, $role, $username, $name);

            //reply content
            $content = array();
            $content["username"] = $username;
            $content["uuid"] = $uuid;
            $content["token"] = $token;
            $content["data"] = $dataStr;
            $content["modified"] = $modified;
            $reply["content"] = $content;
            $reply["message"] = "Successful login. Welcome " . $content["username"] . "!";
            $reply["success"] = true;

            $ok = true;
            break;
        }

        if (!$ok)
        {
            $reply["message"] = "Invalid password or username";
            $reply["success"] = false;
            $log->debugLog("Invalid password", "WARNING");
            logout("Connexion refused (invalid password)");
        }
        else if ($uuid != "")
        {
            // Remove other users with the same username if any
            $q->prepare( "UPDATE `{$tablePrefix}RamUser` SET `removed` = 1, `modified` = :modified WHERE `userName` = :username AND `uuid` != :uuid;" );
            $q->bindStr( "username", $username );
            $q->bindStr( "uuid", $uuid );
            $q->bindStr( "modified", gmdate("Y-m-d H:i:s") );
            $q->execute();
            $q->close();
        }

        printAndDie();
    }
?>