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
        $email = getArg("email");

        $log->debugLog("Reseting password for {$uuid}.", "INFO");

        if ($uuid == "" && $email == "")
        {
            $reply["message"] = "You must provide either an email or a uuid, sorry!";
            $reply["success"] = false;
            $log->debugLog("Refused: empty uuid.", "WARNING");
            printAndDie();
        }


        // We need to check the role: only admins can reset passwords for anyone
        // Using the uuid
        // and users can reset their only their own
        if ($uuid != "" && $_SESSION["userUuid"] != $uuid && !isAdmin())
        {
            if ($currentPassword == "")
            {
                $reply["message"] = "Only administrators can reset other people's password.";
                $reply["success"] = false;
                $log->debugLog("Refused: not an admin.", "WARNING");
                printAndDie();
            }
        }

        // Get the UUID from the email
        if ($uuid == "") {
            $q = new DBQuery();
            $uuid = $q->userUuidFromEmail($email);

            if ($uuid == "") {
                $reply["message"] = "Can't find any user with this email ($email), sorry!";
                $reply["success"] = false;
                $log->debugLog("Refused: no UUID corresponding to this email ($email).", "WARNING");
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

        // Hash
        $newPassword = hashPassword(
            preHashPassword($userPassword),
            $uuid
        );

        // Save it
        $qStr = "UPDATE `{$tablePrefix}RamUser` SET `password` = :password WHERE `uuid` = :uuid ;";
        $q->prepare($qStr);
        $q->bindStr("uuid", $uuid);
        $q->bindStr("password", $newPassword);
        $q->execute();
        $q->close();

        if (!$q->isOK())
        {
            $reply["message"] = "An SQL Error has occured, the password hasn't been changed, sorry.";
            $reply["success"] = false;
            printAndDie();
        }

        // Send user email
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

        // Send admin email
        sendMail(
            EMAIL_ADMIN,
            "Ramses Administrator",
            "Security notice: A user password has been reset",
"Saluton administrator,

This is a security notice.

The password for user $userName ($userEmail) with UUID: $uuid
has been reset.

If you receive lots of security notices like this one, there may be an issue with your server,
or worse, someone trying to attack it.
You should contact the users to confirm they've asked to reset their passwords.

Koran dankon,
Via Ramses Server."
        );
    

        $reply["content"] = array();
        $reply["success"] = true;
        $reply["message"] = "A new password has been sent to: $userEmail";
        $log->debugLog("Password changed.", "INFO");
        printAndDie();
    }

