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

    function assignUsers($users, $projectUuid) {
        global $log, $reply;

        if (strlen($projectUuid) == 0)
        {
            $reply["message"] = "The project UUID is required";
            $reply["success"] = false;
            $log->debugLog("Missing project", "WARNING");
            printAndDie();
        }

        // Get the project and the user ids from their uuid
        $q = new DBQuery();
        $userIds = $q->getUserIds($users);
        if (empty($userIds)) {
            $reply["success"] = false;
            $reply["message"] = "Warning! Can't find any user id from these uuids.";
            $log->debugLog("assignUsers: Can't get any user id.", "WARNING");
            printAndDie();
        }

        $projectId = $q->getProjectId($projectUuid);
        if ($projectId < 0) {
            $reply["success"] = false;
            $reply["message"] = "Warning! Can't find the project $projectUuid in the database, sorry.";
            $log->debugLog("Can't find project $projectUuid.", "WARNING");
            printAndDie();
        }

        // Update the ProjectUser table
        $q->assignUsers($userIds, $projectId);

        $reply["message"] = "Users assigned!";
        $reply["success"] = true;
        printAndDie();
    }

    if ( acceptReply( "assignUser" ) )
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

        assignUsers([$userUuid], $projectUuid);
    }

    if ( acceptReply( "assignUsers" ) )
    {
        if (!isAdmin())
        {
            $reply["success"] = false;
            $reply["message"] = "Warning! Missing privileges. You must be an administrator to assign users to projects.";
            $log->debugLog("Refused: not admin.", "WARNING");
            printAndDie();
        }

        $users = getArg("users", array());
        $projectUuid = getArg("project");

        if (!is_array($users)) {
            $reply["success"] = false;
            $reply["message"] = "Warning! You must provide a user list (a JSON Array) to assign users.";
            $log->debugLog("createUsers: 'users' is not an Array.", "WARNING");
            printAndDie();
        }

        if (empty($users)) {
            $reply["success"] = false;
            $reply["message"] = "Warning! You must provide a non-empty user list (a JSON Array) to assign users.";
            $log->debugLog("createUsers: 'users' is empty.", "WARNING");
            printAndDie();
        }

        assignUsers($users, $projectUuid);
    }

