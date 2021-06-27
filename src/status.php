<?php
    /*
		Ramses: Rx Asset Management System
        
        This program is licensed under the GNU General Public License.

        Copyright (C) 20202-2021 Nicolas Dufresne and Contributors.

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


	// ========= UPDATE STATUS ==========
	if (isset($_GET["updateStatus"]))
	{
		$reply["accepted"] = true;
		$reply["query"] = "updateStatus";

		$comment = getArg ( "comment" );
		$version = getArg ( "version" );
		$completionRatio = getArg ("completionRatio" );
		$stateUuid = getArg ( "stateUuid" );
		$uuid = getArg ("uuid" );
        $published = getArg("published", 0);
        $assignedUserUuid = getArg("assignedUserUuid");
        $timeSpent = getArg("timeSpent", -1);
        $date = getArg("date");

		if ( $uuid != "" && $stateUuid != "" )
		{
            $qString = "UPDATE {$statusTable}
                SET `stateId` = (SELECT {$statesTable}.`id` FROM {$statesTable} WHERE {$statesTable}.`uuid` = :stateUuid )";

            $values = array('stateUuid' => $stateUuid, 'uuid' => $uuid);

            if ($completionRatio != "")
            {
                $qString = $qString . ", completionRatio= :completionRatio";
                $values["completionRatio"] = (int)$completionRatio;
            }

            if ($version != "")
            {
                $qString = $qString . ", version= :version";
                $values["version"] = (int)$version;
            }

            if ($comment != "")
            {
                $qString = $qString . ", comment= :comment";
                $values["comment"] = $comment;
            }

            if ($published != 0)
            {
                $qString = $qString . ", published= :published";
                $values["published"] = $published;
            }

            if ($assignedUserUuid != "")
            {
                if ($assignedUserUuid == "NULL" ) $qString = $qString . ", assignedUserId= NULL";
                else 
                {
                    $qString = $qString . ", assignedUserId= (SELECT {$usersTable}.`id` FROM {$usersTable} WHERE {$usersTable}.`uuid` = :assignedUserUuid )";
                    $values["assignedUserUuid"] = $assignedUserUuid;
                }
            }

            if ($timeSpent >= 0)
            {
                $qString = $qString . ", timeSpent= :timeSpent";
                $values["timeSpent"] = $timeSpent;
            }

            if ($date != "")
            {
                $qString = $qString . ", date= :date";
                $values["date"] = $date;
            }

            $qString = $qString . " WHERE uuid= :uuid ;";

            $rep = $db->prepare($qString);
            $rep->execute($values);
            $rep->closeCursor();

            $reply["message"] = "Status updated.";
            $reply["success"] = true;
		}
		else
		{
			$reply["message"] = "Invalid request, missing values";
			$reply["success"] = false;
		}

	}

	// ========= REMOVE STATUS ==========
	else if (isset($_GET["removeStatus"]))
	{
		$reply["accepted"] = true;
		$reply["query"] = "removeStatus";

		$uuid = getArg ("uuid" );

		if (strlen($uuid) > 0)
		{
            $rep = $db->prepare("UPDATE {$statusTable} SET removed = 1 WHERE uuid= :uuid ;");
            $rep->execute(array('uuid' => $uuid));
            $rep->closeCursor();

            $reply["message"] = "Status removed.";
            $reply["success"] = true;
		}
		else
		{
			$reply["message"] = "Invalid request, missing values";
			$reply["success"] = false;
		}
	}


    // ========= SET STATUS USER ==========
	else if (isset($_GET["setStatusUser"]))
	{
		$reply["accepted"] = true;
		$reply["query"] = "setStatusUser";

		$uuid = getArg ("uuid" );
		$userUuid = getArg ("userUuid" );

		if (strlen($uuid) > 0)
		{
            $rep = $db->prepare("UPDATE {$statusTable}
                SET `userId` = (SELECT {$usersTable}.`id` FROM {$usersTable} WHERE {$usersTable}.`uuid` = :userUuid )
                WHERE uuid= :uuid ;");
            $rep->execute(array('uuid' => $uuid, 'userUuid' => $userUuid));
            $rep->closeCursor();

            $reply["message"] = "Status user changed.";
            $reply["success"] = true;
		}
		else
		{
			$reply["message"] = "Invalid request, missing values";
			$reply["success"] = false;
		}
	}

?>
