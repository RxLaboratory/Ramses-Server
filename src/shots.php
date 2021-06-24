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

	$shotsTable = $tablePrefix . "shots";
	$sequencesTable = $tablePrefix . "sequences";

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
				$qString = "INSERT INTO {$shotsTable} (`name`, `shortName`, `sequenceId`, `duration`, `order`, `uuid`)
				VALUES (
					:name,
					:shortName,
					(SELECT {$sequencesTable}.id FROM {$sequencesTable} WHERE uuid = :sequenceUuid ),
					:duration,
					:order,";
				$values = array( 'name' => $name,'shortName' => $shortName, 'sequenceUuid' => $sequenceUuid, 'duration' => (int)$duration);

				if (strlen($order) > 0)
				{
					$values['order'] = (int)$order;
					// We need to move all other shots
					$orderString = "UPDATE {$shotsTable}
						SET order = order + 1
						WHERE order >= :order
						AND sequenceId = (SELECT {$sequencesTable}.id FROM {$sequencesTable} WHERE uuid = :sequenceUuid );";
					$orderValues = array( 'order' => $order, 'sequenceUuid' => $sequenceUuid);
					$rep = $db->prepare($qString);
					$rep->execute($values);
					$rep->closeCursor();
				}
				else 
				{
					$qOrder = "SELECT COUNT(*) as c FROM {$shotsTable}
						WHERE sequenceId =  (SELECT {$sequencesTable}.id FROM {$sequencesTable} WHERE uuid = :sequenceUuid );";
					
					$rep = $db->prepare($qOrder);
					$rep->execute( array('sequenceUuid' => $sequenceUuid) );
					if ($o = $rep->fetch()) $order = (int)$o['c'];
					else $order = 0;
					$rep->closeCursor();

					$values['order'] = $order;
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
				$qString = "UPDATE {$shotsTable}
					SET
						`name`= :name,
						`shortName`= :shortName";
				$values = array('name' => $name,'shortName' => $shortName);

				if (strlen($sequenceUuid) > 0)
				{
					$qString = $qString . ",`sequenceId` = (SELECT {$sequencesTable}.id FROM {$sequencesTable} WHERE {$sequencesTable}.uuid = :sequenceUuid )";
					$values['sequenceUuid'] = $sequenceUuid;
				}

				if (strlen($duration) > 0)
				{
					$qString = $qString . ",duration = :duration";
					$values['duration'] = (float)$duration;
				}

				$qString = $qString . " WHERE uuid= :uuid ;";
				$values['uuid'] = $uuid;

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

	// ========= SET ORDER ==========
	else if (isset($_GET["setSshotOrder"]))
	{
		$reply["accepted"] = true;
		$reply["query"] = "setSshotOrder";

		$order = $_GET["order"] ?? "";
		$uuid = $_GET["uuid"] ?? "";

		if (strlen($uuid) > 0 && strlen($order) > 0)
		{
			// Only if lead
			if ( isLead() )
			{
			//Move given step
			$qString = "UPDATE {$shotsTable}
			SET `order` = :order
			WHERE `uuid` = :uuid;";
			$values = array('order'  => $order,'uuid'  => $uuid);

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
				// Get previous order and project
				$qString = "SELECT {$shotsTable}.`order`, {$sequencesTable}.`projectId`
					FROM {$shotsTable}
					JOIN {$sequencesTable} ON {$shotsTable}.`sequenceId` = {$sequencesTable}.`id`
					WHERE {$shotsTable}.`uuid` = :uuid;";
				$rep = $db->prepare($qString);
				$rep->execute(array('uuid' => $uuid));
				$previous = -1;
				$projectId = -1;
				if ($r = $rep->fetch())
				{
					$previous = (int)$r['order'];
					$projectId = (int)$r['projectId'];
				}
				$rep->closeCursor();

				// Update
				$order = (int)$order;

				if ($previous > $order)
				{
					//Move all other shots
					$qString = "UPDATE {$shotsTable}
						JOIN {$sequencesTable} ON {$shotsTable}.`sequenceId` = {$sequencesTable}.`id`
						SET
							{$shotsTable}.`order` = {$shotsTable}.`order` + 1
						WHERE
							{$shotsTable}.`order` >= :order
							AND
							{$shotsTable}.`order` < :previous
							AND
							{$sequencesTable}.`projectId` = :projectId;";
					$values = array('order' => $order, 'previous' => $previous, 'projectId' => $projectId);

					$rep = $db->prepare($qString);
					$rep->execute($values);
					$rep->closeCursor();
				}
				else if ($previous >= 0)
				{
					//Move all other shots
					$qString = "UPDATE {$shotsTable}
						JOIN {$sequencesTable} ON {$shotsTable}.`sequenceId` = {$sequencesTable}.`id`
						SET
							{$shotsTable}.`order` = {$shotsTable}.`order` - 1
						WHERE
							{$shotsTable}.`order` <= :order
							AND
							{$shotsTable}.`order` > :previous
							AND
							{$sequencesTable}.`projectId` = :projectId;";
					$values = array('order' => $order, 'previous' => $previous, 'projectId' => $projectId);

					$rep = $db->prepare($qString);
					$rep->execute($values);
					$rep->closeCursor();
				}

				//Move given shot
				$qString = "UPDATE {$shotsTable}
					SET `order` = :order
					WHERE `uuid` = :uuid;";
				$values = array('order'  => $order,'uuid'  => $uuid);

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
				// Get prev order
				$qString = "SELECT `order`
					FROM {$shotsTable}
					WHERE `uuid` = :uuid;";
				$rep = $db->prepare($qString);
				$rep->execute(array('uuid' => $uuid));
				$previous = 0;
				if ($r = $rep->fetch()) $previous = (int)$r['order'];
				$rep->closeCursor();

				$rep = $db->prepare("UPDATE {$shotsTable}
						SET `order` = `order` - 1
						WHERE `order` > :previous;
					UPDATE {$shotsTable}
						SET `removed` = 1, `order` = -1
						WHERE `uuid` = :uuid ;");

				$rep->execute(array('uuid' => $uuid, 'previous' => $previous));
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

	// ========= SET STATUS ==========
	else if (isset($_GET["setShotStatus"]))
	{
		$reply["accepted"] = true;
		$reply["query"] = "setShotStatus";

		$uuid = $_GET["uuid"] ?? uuid() ;
		$shotUuid = $_GET["shotUuid"] ?? "";
		$completionRatio = $_GET["completionRatio"] ?? -1;
		$userUuid = $_GET["userUuid"] ?? $_SESSION["userUuid"];
		$stateUuid = $_GET["stateUuid"] ?? "";
		$comment = $_GET["comment"] ?? "";
		$version = $_GET["version"] ?? 1;
		$stepUuid = $_GET["stepUuid"] ?? 1;

		if (strlen($shotUuid) > 0 && strlen($userUuid) > 0 && strlen($stateUuid) > 0 && strlen($stepUuid) > 0 )
		{
			$qString = "INSERT INTO {$statusTable} (
				`uuid`,
				`completionRatio`,
				`userId`,
				`stateId`,
				`comment`,
				`version`,
				`stepId`,
				`shotId`
				)
				VALUES(
					:uuid ,
					:completionRatio ,
					(SELECT {$usersTable}.`id` FROM {$usersTable} WHERE {$usersTable}.`uuid` = :userUuid),
					(SELECT {$statesTable}.`id` FROM {$statesTable} WHERE {$statesTable}.`uuid` = :stateUuid),
					:comment,
					:version,
					(SELECT {$stepsTable}.`id` FROM {$stepsTable} WHERE {$stepsTable}.`uuid` = :stepUuid),
					(SELECT {$shotsTable}.`id` FROM {$shotsTable} WHERE {$shotsTable}.`uuid` = :shotUuid)
				)
				AS newStatus
				ON DUPLICATE KEY UPDATE
					`comment` = newStatus.`comment`,
					`completionRatio` = newStatus.`completionRatio`,
					`version` = newStatus.`version`,
					`removed` = 0;";

			$rep = $db->prepare($qString);
			$rep->execute(array(
				'uuid' => $uuid,
				'completionRatio' => $completionRatio,
				'userUuid' => $userUuid,
				'stateUuid' => $stateUuid,
				'comment' => $comment,
				'stepUuid' => $stepUuid,
				'version' => $version,
				'shotUuid' => $shotUuid
			));
			$rep->closeCursor();

			$reply["message"] = "Shot status updated.";
			$reply["success"] = true;
		}
		else
		{
			$reply["message"] = "Invalid request, missing values";
			$reply["success"] = false;
		}
	}
?>
