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

    if ( acceptReply( "setCurrentProject" ) )
    {
        $projectUuid = getArg("project");
        if ($projectUuid == "") {
            $reply["message"] = "The project UUID is required.";
            $reply["success"] = false;
            $log->debugLog("Missing project", "WARNING");
            printAndDie();
        }

        $projectUuid = setCurrentProject($projectUuid);

        $reply["success"] = true;
        $reply["message"] = "Current project set to $projectUuid.";
        $reply["content"] = $_SESSION["projectUuid"];
        printAndDie();
    }

?>