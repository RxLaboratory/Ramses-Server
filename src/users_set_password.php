<?php

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

    require_once($__ROOT__."/functions.php");
    require_once($__ROOT__."/reply.php");

    if ( acceptReply( "setPassword" ) )
    {
        $newPassword = getArg("newPassword");
        $currentPassword = getArg("currentPassword");
        $uuid = getArg("uuid");

        $log->debugLog("Changing password for {$uuid}.", "INFO");

        if ($newPassword == "")
        {
            $reply["message"] = "The password can't be empty, sorry!";
            $reply["success"] = false;
            $log->debugLog("Refused: empty password.", "WARNING");
            printAndDie();
        }

        if ($uuid == "")
        {
            $reply["message"] = "The uuid can't be empty, sorry!";
            $reply["success"] = false;
            $log->debugLog("Refused: empty uuid.", "WARNING");
            printAndDie();
        }

        // We need to check the role: only admins can set passwords without specifying the current one
        // if and only if they're not changing their own password
        if ($_SESSION["userUuid"] == $uuid || !isAdmin())
        {
            if ($currentPassword == "")
            {
                $reply["message"] = "You need to specify your current password too.";
                $reply["success"] = false;
                $log->debugLog("Refused: empty current password.", "WARNING");
                printAndDie();
            }
        }
        
        // Check the current password first
        if ($currentPassword != "")
        {
            $q = new DBQuery();
            $q->prepare("SELECT `password` FROM `{$tablePrefix}RamUser` WHERE `uuid` = :uuid ");
            $q->bindStr("uuid", $uuid );
            $q->execute("");
            $row = $q->fetch();
            $q->close();
            if (!$row)
            {
                $reply["message"] = "I can't find this user in the database...";
                $reply["success"] = false;
                $log->debugLog("Refused: invalid user.", "WARNING");
                printAndDie();
            }
            $testPassword = $row['password'];

            if ( !checkPassword($currentPassword, $uuid, $testPassword ) )
            {
                $reply["message"] = "Wrong current password, sorry!";
                $reply["success"] = false;
                $log->debugLog("Refused: invalid current password.", "WARNING");
                printAndDie();
            }
        }

        // Hash
        $newPassword = hashPassword($newPassword, $uuid);

        $q = new DBQuery();
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

        $reply["content"] = array();
        $reply["success"] = true;
        $reply["message"] = "Password changed!";
        $log->debugLog("Password changed.", "INFO");
        printAndDie();
    }

?>