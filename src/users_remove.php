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

    if ( acceptReply( "removeUsers" ) )
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
            $reply["message"] = "Warning! You must provide a user list (a JSON Array) to remove users.";
            $log->debugLog("removeUsers: 'users' is not an Array.", "WARNING");
            printAndDie();
        }

        if (empty($users)) {
            $reply["success"] = false;
            $reply["message"] = "Warning! You must provide a non-empty user list (a JSON Array) to remove users.";
            $log->debugLog("removeUsers: 'users' is empty.", "WARNING");
            printAndDie();
        }

        $q = new DBQuery();
        $usersData = $q->removeUsers($users);

        if ($usersData === false) {
            $reply["success"] = false;
            $reply["message"] = "Something went wrong when removing the users. This is probably a bug in the server, please report it to the developpers!";
            $log->debugLog("removeUsers: DB Query failed.", "WARNING");
            printAndDie();
        }

        $count = count($usersData);
        if ($count == 0) {
            $reply["success"] = true;
            $reply["message"] = "Nothing to remove";
            $reply["content"] = array();
            printAndDie();
        }

        // Send emails
        $usersStr = '';
        $adminEmail = EMAIL_ADMIN;
        foreach($usersData as $data) {
            // Get data
            $userData = json_decode($data['data'], true);
            $userName = $userData['name'] ?? $userData['shortName'] ?? '';
            if ($userName != '') $userName = ' '.$userName;
            $userMail = $data['email'];
            if ($userMail == '')
                continue;

            $usersStr .= '-'. $userName . ' (' . $userMail . ")\r\n";

            // Send email to the user
            sendMail(
                $userMail,
                $userName,
                "You've been removed from a Ramses server.",
                <<<BODY
                Saluton$userName!

                You've been removed from the Ramses server at $serverAddress.

                If you believe this is an error,
                please contact your Ramses administrator at $adminEmail.

                Koran dankon,
                Via Ramses Server.
                BODY
            );
        }

        // Send email to the admin
        sendMail(
            $adminEmail,
            'Ramses Administrator',
            "$count users have been removed from your Ramses server.",
            <<<BODY
            Saluton Ramses administrator!

            $count users have been removed from your Ramses server at $serverAddress.

            This is the list of removed users:
            $usersStr

            Note that the data associated to these users has not been deleted,
            they've just been disabled and unassigned from all projects.

            Koran dankon,
            Via Ramses Server.
            BODY
        );

        $reply["success"] = true;
        $reply["message"] = "$count users removed!";
        $reply["content"] = $usersData;
        printAndDie();
    }

