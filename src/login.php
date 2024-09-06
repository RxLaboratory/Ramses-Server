<?php
    // If this file is called directly, abort.
    if (!defined('RAMROOT')) die;

    require_once(RAMROOT."/functions.php");
    require_once(RAMROOT."/reply.php");

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
        $email = getArg( "email" );
        $password = getArg( "password" );

        $log->debugLog("Login...", "INFO");

        if (strlen($email) == 0)
        {
            $reply["message"] = "Missing email";
            $reply["success"] = false;
            $log->debugLog("Missing email", "WARNING");
            logout("Connexion refused (missing email)");
        }

        if (strlen($password) == 0)
        {
            $reply["message"] = "Missing password";
            $reply["success"] = false;
            $log->debugLog("Missing password", "WARNING");
            logout("Connexion refused (missing password)");
        }

        $q = new DBQuery();
        $q->vacuum();
        $q->prepare( "SELECT `id`, `uuid`,`email`,`password`, `data`, `modified`, `role` FROM `{$tablePrefix}RamUser` WHERE removed = 0;" );
        $q->execute();

        // In case there are multiple users with the same email, find the one with the right password
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
            $reply["message"] = "Invalid password or email";
            $reply["success"] = false;
            $log->debugLog("Invalid email: {$email}", "WARNING");
            logout("Invalid password or email");
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
            $temail = $user["email"];
            $role = $user["role"];
            // decrypt data
            $dataStr = decrypt( $dataStr );
            $dataArr = json_decode( $dataStr, true);
            $temail = decrypt($temail);

            // check email
            if ($temail != $email)
            {
                continue;
            }

            //check password
            if ( !checkPassword($password, $uuid, $tPassword ) )
            {
                continue;
            }

            // Get other data for the log
            $name = "Unknown User";
            if ( isset($dataArr["name"]) ) $name = $dataArr["name"];

            $log->debugLog("{$name} has logged in as {$role}.", "INFO");

            // Login
            $token = login($userid, $uuid, $role, $email, $name);

            //reply content
            $content = array();
            $content["uuid"] = $uuid;
            $content["token"] = $token;
            $content["data"] = $dataStr;
            $content["modified"] = $modified;
            $reply["content"] = $content;
            $reply["message"] = "Successful login. Welcome " . $name . "!";
            $reply["success"] = true;

            $ok = true;
            break;
        }

        if (!$ok)
        {
            $reply["message"] = "Invalid password or email";
            $reply["success"] = false;
            $log->debugLog("Invalid password or email", "WARNING");
            logout("Invalid password or email");
        }
        else if ($uuid != "")
        {
            // Remove other users with the same email if any
            $removeUuids = [];
            $i = 0;
            foreach($users as $user) {
                $temail = $user["email"];
                $temail = decrypt($temail);
                $tuuid = $user["uuid"];

                if ($temail == $email && $tuuid != $uuid) {
                    $removeUuids[] = " `uuid` = '$tuuid' ";
                }
            }

            if (!empty($removeUuids)) {
                $removeUuids = join(" OR ", $removeUuids);
                $q->prepare( "UPDATE `{$tablePrefix}RamUser` SET `removed` = 1, `modified` = :modified WHERE $removeUuids ;" );
                $q->bindStr( "modified", gmdate("Y-m-d H:i:s") );
                $q->execute();
                $q->close();
            }
        }

        printAndDie();
    }
