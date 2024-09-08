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

    if ( acceptReply( "getEmail" ) )
    {
        $uuid = getArg("uuid");
        $log->debugLog("Getting email for {$uuid}.", "INFO");

        if ($uuid == "")
        {
            $reply["message"] = "The uuid can't be empty, sorry!";
            $reply["success"] = false;
            $log->debugLog("Refused: empty uuid.", "WARNING");
            printAndDie();
        }

        // We need to check the role: only admins can get emails
        // or a user if it's his own
        if ($_SESSION["userUuid"] != $uuid && !isAdmin())
        {
            $reply["message"] = "You need to be an administrator to get the email of another user.";
            $reply["success"] = false;
            $log->debugLog("Refused: {$_SESSION["userUuid"]} is trying to get the email of another user.", "WARNING");
            printAndDie();
        }

        $q = new DBQuery();
        $currentEmail = $q->userEmail($uuid);

        $reply["success"] = true;
        $reply["message"] = "Got the user email $currentEmail.";
        $reply["content"] = $currentEmail;
        printAndDie();
    }

    if ( acceptReply( "setEmail" ) )
    {
        $newEmail = getArg("email");
        $uuid = getArg("uuid");

        $log->debugLog("Changing email for {$uuid}.", "INFO");

        if ($newEmail == "")
        {
            $reply["message"] = "The email can't be empty, sorry!";
            $reply["success"] = false;
            $log->debugLog("Refused: empty email.", "WARNING");
            printAndDie();
        }

        if ($uuid == "")
        {
            $reply["message"] = "The uuid can't be empty, sorry!";
            $reply["success"] = false;
            $log->debugLog("Refused: empty uuid.", "WARNING");
            printAndDie();
        }

        // We need to check the role: only admins can set emails
        // or a user if it's his own
        if ($_SESSION["userUuid"] != $uuid && !isAdmin())
        {
            $reply["message"] = "You need to be an administrator to change the email of another user.";
            $reply["success"] = false;
            $log->debugLog("Refused: {$_SESSION["userUuid"]} is trying to set the email of another user.", "WARNING");
            printAndDie();
        }

        
        $q = new DBQuery();
        $currentEmail = $q->userEmail($uuid);

        if ($currentEmail == $newEmail) {
            $reply["success"] = true;
            $reply["message"] = "Nothing to change, this is the same email as the previous one.";
            printAndDie();
        }

        $q->setEmail($uuid, $newEmail);

        $reply["success"] = true;
        $reply["message"] = "User email changed fromn '$currentEmail' to '$newEmail' for user $uuid.";

        $adminEmail = EMAIL_ADMIN;

        sendMail(
            $currentEmail,
            "Ramses user",
            "Security notice: Your Ramses account email address has been changed",
"Saluton,

This is a security notice.

The email address associated to your Ramses account at $serverAddress has been changed from $currentEmail to $newEmail.

If you did not request this change, please contact a Ramses administrator as soon as possible.
You can also warn $adminEmail.

Koran dankon,
Via Ramses Server."
        );

        sendMail(
            $newEmail,
            "Ramses user",
            "Security notice: Your Ramses account email address has been changed",
"Saluton,

This is a security notice.

You have successfully changed the email address associated to your Ramses account at $serverAddress to this current address ($newEmail).

If you did not request this change, please contact a Ramses administrator as soon as possible.
You can also warn $adminEmail.

Koran dankon,
Via Ramses Server."
        );

        printAndDie();
    }