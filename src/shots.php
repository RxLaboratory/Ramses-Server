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
	if (hasArg("createShot"))
	{
		$reply["accepted"] = true;
		$reply["query"] = "createShot";

		$name = getArg("name");
		$shortName = getArg("shortName");
		$sequenceUuid = getArg("sequenceUuid");
		$duration = getArg("duration");
		$order = getArg("order");
		$uuid = getArg("uuid");

		if (strlen($shortName) > 0)
		{
			// Only if lead
            if ( isLead() && validateName( $name ) && validateShortName( $shortName ) )
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
		}
		else
		{
			$reply["message"] = "Invalid request, missing values";
			$reply["success"] = false;
		}
	}

	// ========= UPDATE ==========
	else if (hasArg("updateShot"))
	{
		$reply["accepted"] = true;
		$reply["query"] = "updateAsset";

		$name = getArg( "name" );
		$shortName = getArg( "shortName" );
		$sequenceUuid = getArg( "sequenceUuid" );
		$duration = getArg( "duration" );
		$uuid = getArg( "uuid" );
		$comment = getArg( "comment" );

		if (strlen($shortName) > 0 AND strlen($uuid) > 0)
		{
			// Only if lead
            if ( isLead() && validateName( $name ) && validateShortName( $shortName ) )
            {
				$qString = "UPDATE {$shotsTable}
					SET
						`name`= :name,
						`shortName`= :shortName,
						`comment`= :comment";
				$values = array('name' => $name,'shortName' => $shortName, 'comment' => $comment);

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
		}
		else
		{
			$reply["message"] = "Invalid request, missing values";
			$reply["success"] = false;
		}

	}

	// ========= SET ORDER ==========
	else if (hasArg("setShotOrder"))
	{
		$reply["accepted"] = true;
		$reply["query"] = "setShotOrder";

		$order = getArg("order");
		$uuid = getArg("uuid");

		if (strlen($uuid) > 0 && strlen($order) > 0)
		{
			// Only if lead
			if ( isLead() )
			{
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

	// ========= MOVE ==========
	else if (hasArg("moveShot"))
	{
		$reply["accepted"] = true;
		$reply["query"] = "moveShot";

		$order = getArg("order");
		$uuid = getArg("uuid");

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
	else if (hasArg("removeShot"))
	{
		$reply["accepted"] = true;
		$reply["query"] = "removeShot";

		$uuid = getArg("uuid");

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
	else if (hasArg("setShotStatus"))
	{
		$reply["accepted"] = true;
		$reply["query"] = "setShotStatus";

		$uuid = getArg("uuid", uuid() );
		$shotUuid =  getArg("shotUuid");
		$completionRatio = getArg("completionRatio", -1);
		$userUuid = getArg("userUuid", $_SESSION["userUuid"]);
		$stateUuid = getArg("stateUuid");
		$comment = getArg("comment");
		$version = getArg("version", 1);
		$stepUuid = getArg("stepUuid");
		$assignedUserUuid = getArg("assignedUserUuid");

		if (strlen($shotUuid) > 0 && strlen($userUuid) > 0 && strlen($stateUuid) > 0 && strlen($stepUuid) > 0 )
		{
			if ($assignedUserUuid == "") $assignUser = "NULL";
			else $assignUser = "(SELECT {$usersTable}.`id` FROM {$usersTable} WHERE {$usersTable}.`uuid` = :assignedUserUuid )";

			$qString = "INSERT INTO {$statusTable} (
				`uuid`,
				`userId`,
				`stateId`,
				`stepId`,
				`shotId`,
				`assignedUserId`
				)
				VALUES(
					:uuid ,
					(SELECT {$usersTable}.`id` FROM {$usersTable} WHERE {$usersTable}.`uuid` = :userUuid ),
					(SELECT {$statesTable}.`id` FROM {$statesTable} WHERE {$statesTable}.`uuid` = :stateUuid ),
					(SELECT {$stepsTable}.`id` FROM {$stepsTable} WHERE {$stepsTable}.`uuid` = :stepUuid ),
					(SELECT {$shotsTable}.`id` FROM {$shotsTable} WHERE {$shotsTable}.`uuid` = :shotUuid ), " .
					$assignUser .
				")
				ON DUPLICATE KEY UPDATE
					`removed` = 0;";

			$rep = $db->prepare($qString);
			$rep->bindValue(':uuid', $uuid, PDO::PARAM_STR);
			$rep->bindValue(':stateUuid', $stateUuid, PDO::PARAM_STR);
			$rep->bindValue(':userUuid', $userUuid, PDO::PARAM_STR);
			$rep->bindValue(':stepUuid', $stepUuid, PDO::PARAM_STR);
			$rep->bindValue(':shotUuid', $shotUuid, PDO::PARAM_STR);
			if ($assignedUserUuid != "") $rep->bindValue(':assignedUserUuid', $assignedUserUuid, PDO::PARAM_STR);
			//$rep->debugDumpParams();
			$rep->execute();
			$rep->closeCursor();

			$qString = "UPDATE {$statusTable}
				SET
					`completionRatio` = :completionRatio ,
					`version` = :version ,
					`comment` = :comment
				WHERE `uuid` = :uuid ;";
			$rep = $db->prepare($qString);
			$rep->bindValue(':uuid', $uuid, PDO::PARAM_STR);
			$rep->bindValue(':completionRatio', $completionRatio, PDO::PARAM_INT);
			$rep->bindValue(':version', $version, PDO::PARAM_INT);
			$rep->bindValue(':comment', $comment, PDO::PARAM_STR);
			//$rep->debugDumpParams();
			$rep->execute();
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

	// ========= ASSIGN ASSET ========
	else if (hasArg("assignAsset"))
	{
		$reply["accepted"] = true;
		$reply["query"] = "assignAsset";

		$uuid = getArg( "uuid" );
		$assetUuid = getArg( "assetUuid" );

		if ($uuid != "" && $assetUuid != "")
		{
			if (isLead())
			{
				$qString = "INSERT INTO {$shotassetTable} ( `shotId`, `assetId` )
					VALUES(
						(SELECT {$shotsTable}.`id` FROM {$shotsTable} WHERE {$shotsTable}.`uuid` = :uuid ),
						(SELECT {$assetsTable}.`id` FROM {$assetsTable} WHERE {$assetsTable}.`uuid` = :assetUuid )
						);";

				$rep = $db->prepare($qString);
				$rep->bindValue(':uuid', $uuid, PDO::PARAM_STR);
				$rep->bindValue(':assetUuid', $assetUuid, PDO::PARAM_STR);
				//$rep->debugDumpParams();
				$rep->execute();
				$rep->closeCursor();

				$reply["message"] = "Asset assigned.";
				$reply["success"] = true;

			}
			else
			{
				$reply["message"] = "Insufficient rights, you need to be Lead to assign assets to shots.";
                $reply["success"] = false;
			}
		}
		else
		{
			$reply["message"] = "Invalid request, missing values";
			$reply["success"] = false;
		}
	}

	// ========= UNASSIGN ASSET ========
	else if (hasArg("unassignAsset"))
	{
		$reply["accepted"] = true;
		$reply["query"] = "unassignAsset";

		$uuid = getArg( "uuid" );
		$assetUuid = getArg( "assetUuid" );

		if ($uuid != "" && $assetUuid != "")
		{
			if (isLead())
			{
				$qString = "DELETE {$shotassetTable} FROM {$shotassetTable}
					WHERE
						shotId = (SELECT {$shotsTable}.`id` FROM {$shotsTable} WHERE {$shotsTable}.`uuid` = :uuid )
						AND
						assetId = (SELECT {$assetsTable}.`id` FROM {$assetsTable} WHERE {$assetsTable}.`uuid` = :assetUuid )
					;";

				$rep = $db->prepare($qString);
				$rep->bindValue(':uuid', $uuid, PDO::PARAM_STR);
				$rep->bindValue(':assetUuid', $assetUuid, PDO::PARAM_STR);
				//$rep->debugDumpParams();
				$rep->execute();
				$rep->closeCursor();

				$reply["message"] = "Asset unassigned.";
				$reply["success"] = true;

			}
			else
			{
				$reply["message"] = "Insufficient rights, you need to be Lead to assign assets to shots.";
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
