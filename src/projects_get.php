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

    if ( acceptReply( "getProjects" ) )
    {
        // List the projects the user is assigned to,
        // or all projects if isAdmin()
        $q = new DBQuery();
        $qstr = "SELECT `{$tablePrefix}RamProject`.`uuid`, `{$tablePrefix}RamProject`.`data`, `{$tablePrefix}RamProject`.`modified`, `{$tablePrefix}RamProject`.`removed`
                FROM `{$tablePrefix}RamProject` ";

        $admin = isAdmin();
        if (!$admin)
            $qstr .= "LEFT JOIN `{$tablePrefix}ServerProjectUser`
                    ON `{$tablePrefix}RamProject`.`id` = `{$tablePrefix}ServerProjectUser`.`project_id` ";

        $qstr .= "WHERE `{$tablePrefix}RamProject`.`removed` = 0 ";

        if (!$admin)
            $qstr .= "AND `{$tablePrefix}ServerProjectUser`.`user_id` = :userid ";

        $qstr .= ";";

        $q->prepare($qstr);
        if (!$admin)
            $q->bindInt("userid", $_SESSION["userid"]);

        $projects = array();
        $q->execute();
        while($r = $q->fetch()) {
            $project = array();
            $project["uuid"] = $r["uuid"];
            $project["modified"] = $r["modified"];
            $project["removed"] = (int)$r["removed"];
            $project["data"] = json_decode($r["data"]);
            $projects[] = $project;
        }
        $q->close();

        $reply["success"] = true;
        $reply["message"] = "Got the list of projects for the current user.";
        $reply["content"] = $projects;
        printAndDie();
        
    }

?>