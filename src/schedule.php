<?php
    /*
		Ramses: Rx Asset Management System
        
        This program is licensed under the GNU General Public License.

        Copyright (C) 2020-2021 Nicolas Dufresne and Contributors.

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

    function createEntry($uuid, $userUuid, $stepUuid, $date)
    {
        global $usersTable, $stepsTable, $scheduleTable, $db;

        if ( checkArgs( array( $userUuid, $stepUuid, $date ) ) && isLead() )
        {
            $qString = "INSERT INTO {$scheduleTable} ( `uuid`, `userId`, `stepId`, `date` )
            VALUES (
                :uuid,
                (SELECT {$usersTable}.`id` FROM {$usersTable} WHERE {$usersTable}.`uuid` = :userUuid ),
                (SELECT {$stepsTable}.`id` FROM {$stepsTable} WHERE {$stepsTable}.`uuid` = :stepUuid ),
                :date
                )
            ON DUPLICATE KEY UPDATE `date` = VALUES(`date`) ;";

            $rep = $db->prepare($qString);
            $rep->bindValue(':uuid', $uuid, PDO::PARAM_STR);
            $rep->bindValue(':userUuid', $userUuid, PDO::PARAM_STR);
            $rep->bindValue(':stepUuid', $stepUuid, PDO::PARAM_STR);
            $rep->bindValue(':date', $date, PDO::PARAM_STR);

            $ok = sqlRequest( $rep, "Schedule updated.");

            $rep->closeCursor();

            return $ok;
        }
        return false;
    }

    function updateEntry($uuid, $userUuid, $stepUuid, $date, $comment)
    {
        global $usersTable, $stepsTable, $scheduleTable, $db;

        if ( checkArgs( array( $userUuid, $stepUuid, $date ) ) && isLead() )
        {
            $qString = "UPDATE {$scheduleTable}
            SET
                `userId` = (SELECT {$usersTable}.`id` FROM {$usersTable} WHERE {$usersTable}.`uuid` = :userUuid ),
                `stepId` = (SELECT {$stepsTable}.`id` FROM {$stepsTable} WHERE {$stepsTable}.`uuid` = :stepUuid ),
                `date` = :date,
                `comment` = :comment
            WHERE `uuid` = :uuid ;";

            $rep = $db->prepare($qString);
            $rep->bindValue(':uuid', $uuid, PDO::PARAM_STR);
            $rep->bindValue(':userUuid', $userUuid, PDO::PARAM_STR);
            $rep->bindValue(':stepUuid', $stepUuid, PDO::PARAM_STR);
            $rep->bindValue(':date', $date, PDO::PARAM_STR);
            $rep->bindValue(':comment', $comment, PDO::PARAM_STR);
            
            $ok = sqlRequest( $rep, "Schedule updated.");

			$rep->closeCursor();

            return $ok;
        }
    }

    function deleteEntry( $uuid )
    {
        global $scheduleTable, $db;

        if ( checkArgs( array( $uuid ) ) && isLead() )
        {
            $qString = "DELETE FROM {$scheduleTable}
            WHERE `uuid` = :uuid ;";

            $rep = $db->prepare($qString);
            $rep->bindValue(':uuid', $uuid, PDO::PARAM_STR);
            
            $ok = sqlRequest( $rep, "Schedule updated.");

            $rep->closeCursor();
        }
    }

    // ========= CREATE ENTRY ==========
    if (hasArg("createSchedule"))
    {
        acceptReply( "createSchedule" );

        $uuid = getArg("uuid", uuid());
        $userUuid = getArg("userUuid");
        $stepUuid = getArg("stepUuid");
        $date = getArg("date");

        createEntry($uuid, $userUuid, $stepUuid, $date);
    }

    // ========= CREATE ENTRIES ==========
    else if (hasArg("createSchedules"))
    {
        acceptReply( "createSchedules" );

        $entries = getArg("entries", array());

        if (count($entries) == 0)
        {
            $reply["message"] = "Schedule updated.";
            $reply["success"] = true;
        }

        foreach($entries as $entry)
        {
            $uuid = getAttr("uuid", $entry, uuid());
            $userUuid = getAttr("userUuid", $entry);
            $stepUuid = getAttr("stepUuid", $entry);
            $date = getAttr("date", $entry);

            createEntry($uuid, $userUuid, $stepUuid, $date);
        }
    }

    // ========= UPDATE ENTRY ==========
    else if (hasArg("updateSchedule"))
    {
        acceptReply( "updateSchedule" );

        $uuid = getArg("uuid", uuid());
        $userUuid = getArg("userUuid");
        $stepUuid = getArg("stepUuid");
        $date = getArg("date");
        $comment = getArg("comment");

        updateEntry($uuid, $userUuid, $stepUuid, $date, $comment);
    }

    // ========= UPDATE ENTRIES ==========
    else if (hasArg("updateSchedules"))
    {
        acceptReply( "updateSchedules" );

        $entries = getArg("entries", array());

        if (count($entries) == 0)
        {
            $reply["message"] = "Schedule updated.";
            $reply["success"] = true;
        }

        foreach($entries as $entry)
        {
            $uuid = getAttr("uuid", $entry, uuid());
            $userUuid = getAttr("userUuid", $entry);
            $stepUuid = getAttr("stepUuid", $entry);
            $date = getAttr("date", $entry);
            $comment = getAttr("comment", $entry);

            updateEntry($uuid, $userUuid, $stepUuid, $date, $comment);
        }
    }

    // ========= DELETE ENTRY ==========
    else if (hasArg("removeSchedule"))
    {
        acceptReply( "removeSchedule" );

        $uuid = getArg("uuid", uuid());

        deleteEntry( $uuid );
    }

    // =========== DELETE ENTRIES ======
    else if (hasArg("removeSchedules"))
    {
        acceptReply( "removeSchedules" );

        $uuid = getArg("uuid", uuid());

        $entries = getArg("entries", array());

        if (count($entries) == 0)
        {
            $reply["message"] = "Schedule updated.";
            $reply["success"] = true;
        }

        foreach($entries as $entry)
        {
            $uuid = getAttr("uuid", $entry, uuid());

            deleteEntry( $uuid );
        }
    }

?>