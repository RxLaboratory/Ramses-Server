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

    if ( acceptReply( "getUsers" ) )
    {
        $projectUuid = getArg("project");
        if ($projectUuid == "")
            $projectUuid = $_SESSION["projectUuid"];

        // List the users assigned to this project
        // IF is admin or the current user is assigned to the current project
        $q = new DBQuery();

        $qstr = "SELECT `{$tablePrefix}RamUser`.`uuid`, `{$tablePrefix}RamUser`.`data`, `{$tablePrefix}RamUser`.`modified`, `{$tablePrefix}RamUser`.`userName`, `{$tablePrefix}RamUser`.`removed`
                FROM `{$tablePrefix}RamUser`
                LEFT JOIN `{$tablePrefix}ServerProjectUser`
                    ON `{$tablePrefix}RamUser`.`id` = `{$tablePrefix}ServerProjectUser`.`user_id`
                LEFT JOIN `{$tablePrefix}RamProject`
                    ON `{$tablePrefix}ServerProjectUser`.`project_id` = `{$tablePrefix}RamProject`.`id`
                WHERE `{$tablePrefix}RamUser`.`removed` = 0
                    AND `{$tablePrefix}RamProject`.`uuid` = :projectUuid ";

        $admin = isAdmin();

        if (!$admin) {
            $qstr .= "AND `{$tablePrefix}ServerProjectUser`.`user_id` = :userId ";
        }

        $qstr .= ";";

        $q->prepare($qstr);
        $q->bindStr("projectUuid", $projectUuid);
        if (!$admin)
            $q->bindStr("userId", $_SESSION["userid"]);

        $users = array();
        $q->execute();
        while($r = $q->fetch()) {
            $user = array();
            $user["uuid"] = $r["uuid"];
            $user["modified"] = $r["modified"];
            $user["removed"] = (int)$r["removed"];
            $user["userName"] = $r["userName"];
            $user["data"] = json_decode(decrypt($r["data"]));
            $users[] = $user;
        }
        $q->close();

        $reply["success"] = true;
        $reply["message"] = "Got the list of users for project $projectUuid.";
        $reply["content"] = $users;
        printAndDie();
    }

?>