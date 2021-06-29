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

	// ========= CREATE SEQUENCE ==========
	if (isset($_GET["createSequence"]))
	{
		$reply["accepted"] = true;
		$reply["query"] = "createSequence";

		$name = $_GET["name"] ?? "";
		$shortName = $_GET["shortName"] ?? "";
		$projectUuid = $_GET["projectUuid"] ?? "";
		$uuid = $_GET["uuid"] ?? "";

		if (strlen($shortName) > 0 && strlen($projectUuid) > 0)
		{
			// Only if admin
            if ( isProjectAdmin() )
            {
				// Create sequence
				$qString = "INSERT INTO " . $tablePrefix . "sequences (name,shortName,projectId,uuid) 
				VALUES (
					:name,
					:shortName , 
					(SELECT " . $tablePrefix . "projects.id FROM " . $tablePrefix . "projects WHERE uuid = :projectUuid ),";

				$values = array('name' => $name,'shortName' => $shortName, 'projectUuid' => $projectUuid);
				
				if (strlen($uuid) > 0)
				{
					$qString = $qString . ":uuid ";
					$values['uuid'] = $uuid;
				}
				else
				{
					$qString = $qString . "uuid() ";
				}

				$qString = $qString . ") ON DUPLICATE KEY UPDATE shortName = VALUES(shortName), name = VALUES(name);";

				$rep = $db->prepare($qString);
				$ok = $rep->execute($values);
				$rep->closeCursor();

				if ($ok) $reply["message"] = "Sequence \"" . $shortName . "\" added.";
				else $reply["message"] = $rep->errorInfo();

				$reply["success"] = $ok;
			}
			else
            {
                $reply["message"] = "Insufficient rights, you need to be Admin to create sequences.";
                $reply["success"] = false;
            }
		}
		else
		{
			$reply["message"] = "Invalid request, missing values";
			$reply["success"] = false;
		}
	}

	// ========= UPDATE SEQUENCE ==========
	else if (isset($_GET["updateSequence"]))
	{
		$reply["accepted"] = true;
		$reply["query"] = "updateSequence";

		$name = getArg( "name" );
		$shortName = getArg( "shortName" );
		$uuid = getArg( "uuid" );
		$comment = getArg( "comment" );

		if (strlen($shortName) > 0 AND strlen($uuid) > 0)
		{
			// Only if admin
            if ( isProjectAdmin() )
            {
				$qString = "UPDATE {$sequencesTable} SET `name`= :name ,`shortName`= :shortName, `comment`= :comment WHERE uuid= :uuid ;";
				$values = array('name' => $name,'shortName' => $shortName, 'uuid' => $uuid, 'comment' => $comment);

                $rep = $db->prepare($qString);
				
                $rep->execute($values);
                $rep->closeCursor();

				$reply["message"] = "Sequence \"" . $shortName . "\" updated.";
				$reply["success"] = true;
			}
			else
            {
                $reply["message"] = "Insufficient rights, you need to be Project Admin to update sequence information.";
                $reply["success"] = false;
            }
		}
		else
		{
			$reply["message"] = "Invalid request, missing values";
			$reply["success"] = false;
		}

	}

	// ========= REMOVE ASSET GROUP ==========
	else if (isset($_GET["removeSequence"]))
	{
		$reply["accepted"] = true;
		$reply["query"] = "removeSequence";

		$uuid = $_GET["uuid"] ?? "";

		if (strlen($uuid) > 0)
		{
			//only if admin
			if (isProjectAdmin())
			{
				$rep = $db->prepare("UPDATE " . $tablePrefix . "sequences SET removed = 1 WHERE uuid= :uuid ;");
				$rep->execute(array('uuid' => $uuid));
				$rep->closeCursor();

				$reply["message"] = "Asset Group " . $uuid . " removed.";
				$reply["success"] = true;
			}
			else
            {
                $reply["message"] = "Insufficient rights, you need to be Admin to remove sequences.";
                $reply["success"] = false;
            }
		}
		else
		{
			$reply["message"] = "Invalid request, missing values";
			$reply["success"] = false;
		}
	}

	else if (isset($_GET["setSequenceOrder"]))
	{
		$reply["accepted"] = true;
		$reply["query"] = "setShotOrder";

		$order = getArg("order");
		$uuid = getArg("uuid");

		if ($uuid != "" && $order != "")
		{
			// Only if lead
			if ( isLead() )
			{
				//Move given sequence
				$qString = "UPDATE {$sequencesTable}
					SET `order` = :order
					WHERE `uuid` = :uuid ;";

				$rep = $db->prepare($qString);
				$rep->bindValue(':uuid', $uuid, PDO::PARAM_STR);
				$rep->bindValue(':order', $order, PDO::PARAM_STR);
				$ok = $rep->execute();
				$rep->closeCursor();

				if($ok)
				{
					$reply["message"] = "Sequence moved.";
					$reply["success"] = true;
				}
				else 
				{
					$reply["message"] = $rep.errorInfo()[2];
					$reply["success"] = false;
				}
				
			}
			else
			{
				$reply["message"] = "Insufficient rights, you need to be Lead to update shot order.";
				$reply["success"] = false;
			}
		}
		else
		{
			$reply["message"] = "Invalid request, missing values";
			$reply["success"] = false;
		}
	}

?>
