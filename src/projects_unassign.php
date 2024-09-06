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

    if ( acceptReply( "unassignUser" ) )
    {
        if (!isAdmin())
        {
            $reply["success"] = false;
            $reply["message"] = "Warning! Missing privileges. You must be an administrator to assign users to projects.";
            $log->debugLog("Refused: not admin.", "WARNING");
            printAndDie();
        }

        $userUuid = getArg("user");
        $projectUuid = getArg("project");

        if (strlen($userUuid) == 0)
        {
            $reply["message"] = "The user UUID is required";
            $reply["success"] = false;
            $log->debugLog("Missing user", "WARNING");
            printAndDie();
        }

        if (strlen($projectUuid) == 0)
        {
            $reply["message"] = "The project UUID is required";
            $reply["success"] = false;
            $log->debugLog("Missing project", "WARNING");
            printAndDie();
        }

        // Get the project and the user id from their uuid
        $q = new DBQuery();
        $q->prepare("SELECT `id` FROM `{$tablePrefix}RamUser` WHERE `uuid` = :userUuid;");
        $q->bindStr( "userUuid", $userUuid );
        $q->execute();
        $userId = (int)$q->fetch()["id"] ?? -1;
        $q->close();
        if ($userId < 0) {
            $reply["success"] = false;
            $reply["message"] = "Can't find the user $userUuid in the database, sorry.";
            $log->debugLog("Can't find user $userUuid.", "WARNING");
            printAndDie();
        }

        $q = new DBQuery();
        $q->prepare("SELECT `id` FROM `{$tablePrefix}RamProject` WHERE `uuid` = :projectUuid;");
        $q->bindStr( "projectUuid", $projectUuid );
        $q->execute();
        $projectId = (int)$q->fetch()["id"] ?? -1;
        $q->close();
        if ($projectId < 0) {
            $reply["success"] = false;
            $reply["message"] = "Can't find the project $userUuid in the database, sorry.";
            $log->debugLog("Can't find project $userUuid.", "WARNING");
            printAndDie();
        }

        // Update the ProjectUser table
        $q->unassignUser($userId, $projectId);

        $reply["message"] = "User unassigned!";
        $reply["success"] = true;
        printAndDie();
    }

