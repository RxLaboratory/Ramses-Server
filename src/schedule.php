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

    // ========= CREATE ENTRY ==========
    if (hasArg("createSchedule"))
    {
        $reply["accepted"] = true;
		$reply["query"] = "createSchedule";

        $uuid = getArg("uuid", uuid());
        $userUuid = getArg("userUuid");
        $stepUuid = getArg("stepUuid");
        $date = getArg("date");

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
            //$rep->debugDumpParams();
			$ok = $rep->execute();
			$rep->closeCursor();

            if ($ok)
            {
                $reply["message"] = "Schedule updated.";
			    $reply["success"] = true;
            }
            else 
            {
                $reply["message"] = $rep->errorInfo()[2];
			    $reply["success"] = false;
            }
        }
    }

    // ========= UPDATE ENTRY ==========
    if (hasArg("updateSchedule"))
    {
        $reply["accepted"] = true;
		$reply["query"] = "updateSchedule";

        $uuid = getArg("uuid", uuid());
        $userUuid = getArg("userUuid");
        $stepUuid = getArg("stepUuid");
        $date = getArg("date");
        $comment = getArg("comment");

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
            //$rep->debugDumpParams();
			$ok = $rep->execute();
			$rep->closeCursor();

            if ($ok)
            {
                $reply["message"] = "Schedule updated.";
			    $reply["success"] = true;
            }
            else 
            {
                $reply["message"] = $rep->errorInfo()[2];
			    $reply["success"] = false;
            }
        }
    }

    // ========= DELETE ENTRY ==========
    if (hasArg("removeSchedule"))
    {
        $reply["accepted"] = true;
        $reply["query"] = "removeSchedule";

        $uuid = getArg("uuid", uuid());

        if ( checkArgs( array( $uuid ) ) && isLead() )
        {
            $qString = "DELETE FROM {$scheduleTable}
            WHERE `uuid` = :uuid ;";

            $rep = $db->prepare($qString);
            $rep->bindValue(':uuid', $uuid, PDO::PARAM_STR);
            //$rep->debugDumpParams();
            $ok = $rep->execute();
            $rep->closeCursor();

            if ($ok)
            {
                $reply["message"] = "Schedule updated.";
                $reply["success"] = true;
            }
            else 
            {
                $reply["message"] = $rep->errorInfo()[2];
                $reply["success"] = false;
            }
        }
    }


?>