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

    if ( acceptReply( "setUserRole" ) )
    {
        if (!isAdmin())
        {
            $reply["success"] = false;
            $reply["message"] = "Warning! Missing privileges. You must be an administrator to change user roles.";
            $log->debugLog("Refused: not admin.", "WARNING");
            printAndDie();
        }

        $userUuid = getArg("user", "");
        $userRole = getArg("role", "");
        if ($userUuid == "") {
            $reply["success"] = false;
            $reply["message"] = "Warning! Missing user UUID.";
            $log->debugLog("setUserRole: missing uuid.", "WARNING");
            printAndDie();
        }
        if ($userRole == "") {
            $reply["success"] = false;
            $reply["message"] = "Warning! Missing user role.";
            $log->debugLog("setUserRole: missing role.", "WARNING");
            printAndDie();
        }

        if ($userUuid == $_SESSION["userUuid"]) {
            $reply["success"] = false;
            $reply["message"] = "Warning! You can't change your own role, sorry.";
            $log->debugLog("setUserRole: trying to change own role.", "WARNING");
            printAndDie();
        }

        $q = new DBQuery();
        $q->setUserRole($userUuid, $userRole);

        $reply["success"] = true;
        $reply["message"] = "User role changed to '$userRole' for user $userUuid.";
        if ($userRole == 'admin') sendMail(
                EMAIL_ADMIN,
                "Ramses Administrator",
                "Security notice: A user has been granted admnistrator privileges",
"Saluton administrator,

This is a security notice.

A user with the UUID $userUuid has been granted administrator privileges on the Ramses server located at $serverAddress
The user who's made this change has the UUID {$_SESSION["userUuid"]} and the Database ID {$_SESSION["userid"]}

If you recognise the Ramses Administrator who's made this change recently, you can ignore this message.

Koran dankon,
Via Ramses Server."
            );
        
        printAndDie();
    }