<?php
    // If this file is called directly, abort.
    if (!defined('RAMROOT')) die;


    /*
        Ramses: Rx Asset Management System
        
        This program is licensed under the GNU General Public License.

        Copyright (C) 20202-2024 Nicolas Dufresne and Contributors.

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

    require_once(RAMROOT."/functions.php");
    require_once(RAMROOT."/reply.php");

    if ( acceptReply( "createUsers" ) )
    {
        if (!isAdmin())
        {
            $reply["success"] = false;
            $reply["message"] = "Warning! Missing privileges. You must be an administrator to create users.";
            $log->debugLog("Refused: not admin.", "WARNING");
            printAndDie();
        }

        $users = getArg("users", array());
        if (!is_array($users)) {
            $reply["success"] = false;
            $reply["message"] = "Warning! You must provide a user list (a JSON Array) to create users.";
            $log->debugLog("createUsers: 'users' is not an Array.", "WARNING");
            printAndDie();
        }

        if (empty($users)) {
            $reply["success"] = false;
            $reply["message"] = "Warning! You must provide a non-empty user list (a JSON Array) to create users.";
            $log->debugLog("createUsers: 'users' is empty.", "WARNING");
            printAndDie();
        }

        $parsedUsers = array();
        $uuids = array();

        foreach($users as $user) {
            
            // Parse user data
            $userPassword = $user['password'] ?? "";
            $userMail = $user['email'] ?? "";
            $userShortName = $user['username'] ?? "";
            $userName = $userShortName;
            $userData = $user['data'] ?? "";
            $userUuid = $user['uuid'] ?? uuid();

            if ($userData != "") {
                $userData = json_decode($userData, true);
                if ($userMail =="")
                    $userMail = $userData["email"] ?? "";
                if ($userShortName == "")
                    $userShortName = $userData["shortName"] ?? "";
                $userName = $userData["name"] ?? $userShortName;
            }
            else
                $userData = array();

            if ($userShortName == "") {
                $reply["success"] = false;
                $reply["message"] = "Warning! Missing username.";
                $log->debugLog("createUsers: Some users are missing their username.", "WARNING");
                printAndDie();
            }

            // Generate a password if needed
            if ($userPassword == "") {

                $userPassword = generatePassword(5);
                // Send email
                if ($userMail != "")
                {
                    sendMail(
                        $userMail,
                        $userName,
                        "Welcome to Ramses",
"Saluton $userName!

You've been invited to a Ramses server at $serverAddress.

Your username is: $userShortName
Your temporary password is: $userPassword

Do not forget to change this password!

Koran dankon,
Via Ramses Server."
                    );
                }
                
            }

            $parsedUser = array();
            $parsedUser['uuid'] = $userUuid;
            $parsedUser['username'] = $userShortName;
            $parsedUser['password'] = preHashPassword($userPassword);
            $parsedUser['email'] = $userMail;
            $parsedUser['data'] = json_encode($userData);
            $parsedUsers[] = $parsedUser;

            $uuids[] = $userUuid;
        }

        $q = new DBQuery();
        $q->createUsers($parsedUsers);

        $reply["success"] = true;
        $reply["message"] = "Users succesfully created.";
        $reply["content"] = $uuids;
        printAndDie();
    }

