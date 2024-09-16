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

    if ( acceptReply( "resetPassword" ) )
    {
        $uuid = getArg("uuid");

        $log->debugLog("Reseting password for {$uuid}.", "INFO");

        if ($uuid == "")
        {
            $reply["message"] = "The uuid can't be empty, sorry!";
            $reply["success"] = false;
            $log->debugLog("Refused: empty uuid.", "WARNING");
            printAndDie();
        }

        // We need to check the role: only admins can reset passwords for anyone
        // and users can reset their only their own
        if ($_SESSION["userUuid"] != $uuid && !isAdmin())
        {
            if ($currentPassword == "")
            {
                $reply["message"] = "Only administrators can reset other people's password.";
                $reply["success"] = false;
                $log->debugLog("Refused: not an admin.", "WARNING");
                printAndDie();
            }
        }
        
        // Get the user email and name
        $q = new DBQuery();
        $user = $q->userDetails($uuid);
        if (!$user)
        {
            $reply["message"] = "I can't find this user in the database...";
            $reply["success"] = false;
            $log->debugLog("Refused: invalid user.", "WARNING");
            printAndDie();
        }
        if ($user['email'] == "")
        {
            $reply["message"] = "I can't find this user's email in the database...";
            $reply["success"] = false;
            $log->debugLog("Refused: missing email.", "WARNING");
            printAndDie();
        }

        // Generate a new password
        $userPassword = generatePassword(6);
        $userName = $user['name'];
        $userEmail = $user['email'];
        $ramsesAdminEmail = EMAIL_ADMIN;
        // Send email
        sendMail(
            $userEmail,
            $userName,
            "Ramses server password reset",
"Saluton $userName!

Someone requested a password reset for your access to your Ramses server at $serverAddress.

This is your new temporary password: $userPassword
As a reminder, the email associated to your account is: $userEmail

Do not forget to change this password after you log in!

If you did not request this change,
please contact your Ramses administrator ($ramsesAdminEmail) as soon as possible.

Koran dankon,
Via Ramses Server."
        );


        $reply["content"] = array();
        $reply["success"] = true;
        $reply["message"] = "Password changed!";
        $log->debugLog("Password changed.", "INFO");
        printAndDie();
    }

