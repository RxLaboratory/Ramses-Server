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

	// ========= CREATE ==========
	if (isset($_GET["createShot"]))
	{
		$reply["accepted"] = true;
		$reply["query"] = "createShot";

		$name = $_GET["name"] ?? "";
		$shortName = $_GET["shortName"] ?? "";
		$sequenceUuid = $_GET["sequenceUuid"] ?? "";
		$duration = $_GET["duration"] ?? "0";
		$order = $_GET["order"] ?? "";
		$uuid = $_GET["uuid"] ?? "";

		if (strlen($shortName) > 0)
		{
			// Only if lead
            if ( isLead() )
            {
				$shotsTable = $tablePrefix . "shots";
				$sequencesTable = $tablePrefix . "sequences";

				$qString = "INSERT INTO :shots (name, shortName, sequenceId, duration, order, uuid)
				VALUES (
					:name,
					:shortName,
					(SELECT :sequences.id FROM :sequences WHERE uuid = :sequenceUuid ),
					:duration,";
				$values = array( 'name' => $name,'shortName' => $shortName, 'sequenceUuid' => $assetGroupUuid, 'duration' => (int)$duration, 'sequences' => $sequencesTable, 'shots' => $shotsTable);

				if (strlen($order) > 0)
				{
					$qString = $qString . ":order,";
					$values['order'] = $order;
					// We need to move all other shots
					$orderString = "UPDATE :shots
						SET order = order + 1
						WHERE order >= :order
						AND sequenceId = (SELECT :sequences.id FROM :sequences WHERE uuid = :sequenceUuid );";
					$orderValues = array( 'order' => $order, 'sequenceUuid' => $sequenceUuid, 'sequences' => $sequencesTable, 'shots' => $shotsTable);
					$rep = $db->prepare($qString);
					$rep->execute($values);
					$rep->closeCursor();
				}
				else 
				{
					$qString = $qString . "( SELECT COUNT(*) FROM :shots
						WHERE sequenceId =  (SELECT :sequences.id FROM :sequences WHERE uuid = :sequenceUuid ) ),";
				}

				if (strlen($uuid) > 0)
				{
					$qString = $qString . ":uuid ";
					$values['uuid'] = $uuid;
				}
				else
				{
					$qString = $qString . "uuid() ";
				}

				$qString = $qString . ") ON DUPLICATE KEY UPDATE name = VALUES(name), duration = VALUES(duration), removed = 0;";

				$rep = $db->prepare($qString);
				$ok = $rep->execute($values);
				$rep->closeCursor();

				if ($ok) $reply["message"] = "Shot \"" . $shortName . "\" added.";
				else $reply["message"] = $rep->errorInfo();

				$reply["success"] = $ok;
			}
			else
            {
                $reply["message"] = "Insufficient rights, you need to be Lead to create shots.";
                $reply["success"] = false;
            }
		}
		else
		{
			$reply["message"] = "Invalid request, missing values";
			$reply["success"] = false;
		}
	}

	// ========= UPDATE ==========
	else if (isset($_GET["updateShot"]))
	{
		$reply["accepted"] = true;
		$reply["query"] = "updateAsset";

		$name = $_GET["name"] ?? "";
		$shortName = $_GET["shortName"] ?? "";
		$sequenceUuid = $_GET["sequenceUuid"] ?? "";
		$duration = $_GET["duration"] ?? "";
		$uuid = $_GET["uuid"] ?? "";

		if (strlen($shortName) > 0 AND strlen($uuid) > 0)
		{
			// Only if lead
            if ( isLead() )
            {
				$shotsTable = $tablePrefix . "shots";
				$sequencesTable = $tablePrefix . "sequences";

				$qString = "UPDATE :shots
					SET
						`name`= :name,
						`shortName`= :shortName";
				$values = array('name' => $name,'shortName' => $shortName, 'uuid' => $uuid);

				if (strlen($sequenceUuid) > 0)
				{
					$qString = $qString . ",`sequenceId` = (SELECT :sequences.id FROM :sequences WHERE uuid = :sequenceUuid )";
					$values['sequenceUuid'] = $sequenceUuid;
					$values['sequences'] = $sequencesTable;
				}

				if (strlen($duration) > 0)
				{
					$qString = $qString . ",duration = :duration";
					$values['duration'] = (int)$duration;
				}

				$qString = $qString . "WHERE uuid= :uuid ;";

				$rep = $db->prepare($qString);
				$ok = $rep->execute($values);
				$rep->closeCursor();

				if ($ok) $reply["message"] = "Shot \"" . $shortName . "\" updated.";
				else $reply["message"] = $rep->errorInfo();

				$reply["success"] = $ok;
			}
			else
            {
                $reply["message"] = "Insufficient rights, you need to be Lead to update shot information.";
                $reply["success"] = false;
            }
		}
		else
		{
			$reply["message"] = "Invalid request, missing values";
			$reply["success"] = false;
		}

	}

	// ========= MOVE ==========
	else if (isset($_GET["moveShot"]))
	{
		$reply["accepted"] = true;
		$reply["query"] = "moveShot";

		$order = $_GET["order"] ?? "";
		$uuid = $_GET["uuid"] ?? "";

		if (strlen($uuid) > 0 && strlen($order) > 0)
		{
			// Only if lead
            if ( isLead() )
			{
				// Update
				$order = (int)$order;
				$shotsTable = $tablePrefix . "shots";

				if ($previous > $order)
				{
					//Move all other shots
					$qString = "UPDATE :shots
						SET
							order = order + 1
						WHERE
							order >= :order
							AND
							order < (SELECT order FROM :shots WHERE uuid = :uuid)
							AND
							sequenceId = (SELECT sequenceId FROM :shots WHERE uuid = :uuid);";
					$values = array('order' => $order, 'shots' => $shotsTable);

					$rep = $db->prepare($qString);
					$rep->execute($values);
					$rep->closeCursor();
				}
				else 
				{
					//Move all other shots
					$qString = "UPDATE :shots
						SET
							order = order - 1
						WHERE
							order <= :order
							AND
							order > (SELECT order FROM :shots WHERE uuid = :uuid)
							AND
							sequenceId = (SELECT sequenceId FROM :shots WHERE uuid = :uuid);";
					$values = array('order' => $order, 'shots' => $shotsTable);

					$rep = $db->prepare($qString);
					$rep->execute($values);
					$rep->closeCursor();
				}

				//Move given shot
				$qString = "UPDATE :shots
					SET order = :order
					WHERE uuid = :uuid;";
				$values = array('order'  => $order,'uuid'  => $uuid, 'shots' => $shotsTable);

				$rep = $db->prepare($qString);
				$rep->execute($values);
				$rep->closeCursor();

				$reply["message"] = "Shot moved.";
				$reply["success"] = true;
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

	// ========= REMOVE ==========
	else if (isset($_GET["removeShot"]))
	{
		$reply["accepted"] = true;
		$reply["query"] = "removeShot";

		$uuid = $_GET["uuid"] ?? "";

		if (strlen($uuid) > 0)
		{
			//only if admin
			if (isLead())
			{
				$shotsTable = $tablePrefix . "shots";

				$rep = $db->prepare("UPDATE :shots
						SET order = order - 1
						WHERE order > (SELECT order FROM :shots WHERE uuid = :uuid);
					UPDATE :shots
						SET removed = 1, order = -1
						WHERE uuid = :uuid ;");

				$rep->execute(array('uuid' => $uuid, 'shots' => $shotsTable));
				$rep->closeCursor();

				$reply["message"] = "Shot removed.";
				$reply["success"] = true;
			}
			else
            {
                $reply["message"] = "Insufficient rights, you need to be Lead to remove shots.";
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
