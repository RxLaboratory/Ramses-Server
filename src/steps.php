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

	// ========= CREATE STEP ==========
	if (hasArg("createStep"))
	{
		$reply["accepted"] = true;
		$reply["query"] = "createStep";

		$name = "";
		$shortName = "";
		$projectUuid = "";
		$uuid = "";

		$name = getArg("name");
        $shortName = getArg("shortName");
        $projectUuid = getArg("projectUuid");
        $uuid = getArg("uuid");

		if (strlen($shortName) > 0 && strlen($projectUuid) > 0)
		{
			// Only if admin
            if ( isProjectAdmin() )
            {
				// Create step
				$qString = "INSERT INTO " . $tablePrefix . "steps (name,shortName,projectId,uuid) 
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

				if ($ok) $reply["message"] = "Step \"" . $shortName . "\" added.";
				else $reply["message"] = $rep->errorInfo();

				$reply["success"] = $ok;
			}
			else
            {
                $reply["message"] = "Insufficient rights, you need to be Admin to create steps.";
                $reply["success"] = false;
            }
		}
		else
		{
			$reply["message"] = "Invalid request, missing values";
			$reply["success"] = false;
		}
	}

	// ========= UPDATE STEP ==========
	else if (hasArg("updateStep"))
	{
		$reply["accepted"] = true;
		$reply["query"] = "updateStep";

		$name = getArg( "name" );
		$shortName = getArg( "shortName" );
		$uuid = getArg( "uuid" );
		$type = getArg( "type" );
		$comment = getArg( "comment" );
		$color = getArg( "color" );

		if ($shortName != "" && $uuid != "")
		{
			// Only if admin
            if ( isProjectAdmin() )
            {
				$qString = "UPDATE {$stepsTable} SET `name`= :name ,`shortName`= :shortName, `comment`= :comment";

				$values = array('name' => $name,'shortName' => $shortName, 'uuid' => $uuid, 'comment' => $comment);
				
				if ($type != "")
				{
					$qString = $qString . ", `type`= :type";
                    $values["type"] = $type;
				}

				if ($color != "")
				{
					$qString = $qString . ", `color`= :color";
                    $values["color"] = $color;
				}

				$qString = $qString . " WHERE uuid= :uuid ;";
			
				$rep = $db->prepare($qString);
                $rep->execute($values);
                $rep->closeCursor();

				$reply["message"] = "Step \"" . $shortName . "\" updated.";
				$reply["success"] = true;
			}
			else
            {
                $reply["message"] = "Insufficient rights, you need to be Admin to update step information.";
                $reply["success"] = false;
            }
		}
		else
		{
			$reply["message"] = "Invalid request, missing values";
			$reply["success"] = false;
		}
	}

	// =========== STEP ESTIMATIONS ==========
	else if (hasArg( "setStepEstimations" ))
	{
		$reply["accepted"] = true;
		$reply["query"] = "setStepEstimations";

		$uuid = getArg( "uuid" );
		$method = getArg( "method", "shot" );
		$veryEasy = getArg( "veryEasy", "0.2" );
		$easy = getArg( "easy", "0.5" );
		$medium = getArg( "medium", "1" );
		$hard = getArg( "hard", "2" );
		$veryHard = getArg( "veryHard", "3" );
		$multiplyGroupUuid = getArg( "multiplyGroupUuid" );

		if ( $uuid != "")
		{
			// Only if admin
            if ( isProjectAdmin() )
            {
				$qString = "UPDATE {$stepsTable}
					SET
						`estimationMethod`= :method ,
						`estimationVeryEasy`= :veryEasy,
						`estimationEasy`= :easy,
						`estimationMedium`= :medium,
						`estimationHard`= :hard,
						`estimationVeryHard`= :veryHard,
						`estimationMultiplyGroupId`= (SELECT {$assetgroupsTable}.`id` FROM {$assetgroupsTable} WHERE {$assetgroupsTable}.`uuid` = :multiplyGroupUuid )
					WHERE `uuid`= :uuid;";

				$rep = $db->prepare($qString);
				$rep->bindValue(':uuid', $uuid, PDO::PARAM_STR);
				$rep->bindValue(':method', $method, PDO::PARAM_STR);
				$rep->bindValue(':veryEasy', $veryEasy, PDO::PARAM_STR);
				$rep->bindValue(':easy', $easy, PDO::PARAM_STR);
				$rep->bindValue(':medium', $medium, PDO::PARAM_STR);
				$rep->bindValue(':hard', $hard, PDO::PARAM_STR);
				$rep->bindValue(':veryHard', $veryHard, PDO::PARAM_STR);
				$rep->bindValue(':multiplyGroupUuid', $multiplyGroupUuid, PDO::PARAM_STR);
				//$rep->debugDumpParams();
				$ok = $rep->execute();
				$rep->closeCursor();

				if ($ok)
				{
					$reply["message"] = "Step updated.";
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
                $reply["message"] = "Insufficient rights, you need to be Project Admin to update step information.";
                $reply["success"] = false;
            }
		}
		else 
		{
			$reply["message"] = "Invalid request, missing values";
			$reply["success"] = false;
		}
	}

	// ========= REMOVE STEP ==========
	else if (hasArg("removeStep"))
	{
		$reply["accepted"] = true;
		$reply["query"] = "removeStep";

		$uuid = "";

		$uuid = getArg("uuid");

		if (strlen($uuid) > 0)
		{
			//only if admin
			if (isProjectAdmin())
			{
				$rep = $db->prepare("UPDATE " . $tablePrefix . "steps SET removed = 1 WHERE uuid= :uuid ;");
				$rep->execute(array('uuid' => $uuid));
				$rep->closeCursor();

				$reply["message"] = "Step " . $uuid . " removed.";
				$reply["success"] = true;
			}
			else
            {
                $reply["message"] = "Insufficient rights, you need to be Admin to remove steps.";
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
	else if (hasArg("setStepOrder"))
	{
		$reply["accepted"] = true;
		$reply["query"] = "setStepOrder";

		$order = getArg("order");
		$uuid = getArg("uuid");

		if (strlen($uuid) > 0 && strlen($order) > 0)
		{
			// Only if lead
			if ( isProjectAdmin() )
			{
			//Move given step
			$qString = "UPDATE {$stepsTable}
			SET `order` = :order
			WHERE `uuid` = :uuid;";
			$values = array('order'  => $order,'uuid'  => $uuid);

			$rep = $db->prepare($qString);
			$rep->execute($values);
			$rep->closeCursor();

			$reply["message"] = "Step moved.";
			$reply["success"] = true;
			}
			else
			{
				$reply["message"] = "Insufficient rights, you need to be Project Admin to update step order.";
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
	else if (hasArg("moveStep"))
	{
		$reply["accepted"] = true;
		$reply["query"] = "moveStep";

		$order = getArg("order");
		$uuid = getArg("uuid");

		if (strlen($uuid) > 0 && strlen($order) > 0)
		{
			// Only if lead
			if ( isProjectAdmin() )
			{
				// Get previous order and project
				$qString = "SELECT {$stepsTable}.`order`, {$stepsTable}.`projectId`
					FROM {$stepsTable}
					WHERE {$stepsTable}.`uuid` = :uuid;";
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
					//Move all other steps
					$qString = "UPDATE {$stepsTable}
						SET
							{$stepsTable}.`order` = {$stepsTable}.`order` + 1
						WHERE
							{$stepsTable}.`order` >= :order
							AND
							{$stepsTable}.`order` < :previous
							AND
							{$stepsTable}.`projectId` = :projectId;";
					$values = array('order' => $order, 'previous' => $previous, 'projectId' => $projectId);

					$rep = $db->prepare($qString);
					$rep->execute($values);
					$rep->closeCursor();
				}
				else if ($previous >= 0)
				{
					//Move all other steps
					$qString = "UPDATE {$stepsTable}
						SET
							{$stepsTable}.`order` = {$stepsTable}.`order` - 1
						WHERE
							{$stepsTable}.`order` <= :order
							AND
							{$stepsTable}.`order` > :previous
							AND
							{$stepsTable}.`projectId` = :projectId;";
					$values = array('order' => $order, 'previous' => $previous, 'projectId' => $projectId);

					$rep = $db->prepare($qString);
					$rep->execute($values);
					$rep->closeCursor();
				}

				//Move given step
				$qString = "UPDATE {$stepsTable}
					SET `order` = :order
					WHERE `uuid` = :uuid;";
				$values = array('order'  => $order,'uuid'  => $uuid);

				$rep = $db->prepare($qString);
				$rep->execute($values);
				$rep->closeCursor();

				$reply["message"] = "Step moved.";
				$reply["success"] = true;
			}
			else
			{
				$reply["message"] = "Insufficient rights, you need to be Project Admin to update step order.";
				$reply["success"] = false;
			}
		}
		else
		{
			$reply["message"] = "Invalid request, missing values";
			$reply["success"] = false;
		}
	}

	// ========= ASSIGN APPLICATION ==========
	else if (hasArg("assignApplication"))
	{
		$reply["accepted"] = true;
		$reply["query"] = "assignApplication";

		$stepUuid = getArg("stepUuid");
		$applicationUuid = getArg("applicationUuid");

		if (strlen($stepUuid) > 0 && strlen($applicationUuid) > 0)
		{
			//only if lead
			if (isProjectAdmin())
			{
				$qString = "INSERT INTO " . $tablePrefix . "stepapplication (`stepId`, `applicationId`) VALUES (
					( SELECT " . $tablePrefix . "steps.`id` FROM " . $tablePrefix . "steps WHERE " . $tablePrefix . "steps.`uuid` = :stepUuid ),
					( SELECT " . $tablePrefix . "applications.`id` FROM " . $tablePrefix . "applications WHERE " . $tablePrefix . "applications.`uuid` = :applicationUuid )
					) ON DUPLICATE KEY UPDATE removed = 0 ;";

				$rep = $db->prepare($qString);

				$ok = $rep->execute( array('stepUuid' => $stepUuid, 'applicationUuid' => $applicationUuid) );
				$rep->closeCursor();

				if ($ok) $reply["message"] = "Application assigned to step.";
				else $reply["message"] = $rep->errorInfo();

				$reply["success"] = $ok;
			}
			else
            {
                $reply["message"] = "Insufficient rights, you need to be Lead to assign applications.";
                $reply["success"] = false;
            }
		}
		else 
		{
			$reply["message"] = "Invalid request, missing values";
			$reply["success"] = false;
		}
	}

	// ========= REMOVE APPLICATION ==========
	else if (hasArg("unassignApplication"))
	{
		$reply["accepted"] = true;
		$reply["query"] = "unassignApplication";

		$stepUuid = getArg("stepUuid");
		$applicationUuid = getArg("applicationUuid");

		if (strlen($stepUuid) > 0 && strlen($applicationUuid) > 0)
		{
			//only if lead
			if (isProjectAdmin())
			{
				$q = "DELETE " . $tablePrefix . "stepapplication FROM " . $tablePrefix . "stepapplication WHERE
					stepId= ( SELECT " . $tablePrefix . "steps.id FROM " . $tablePrefix . "steps WHERE " . $tablePrefix . "steps.uuid = :stepUuid )
				AND
					applicationId= ( SELECT " . $tablePrefix . "applications.id FROM " . $tablePrefix . "applications WHERE " . $tablePrefix . "applications.uuid = :applicationUuid )
				;";
				$rep = $db->prepare($q);
				$ok = $rep->execute(array('stepUuid' => $stepUuid,'applicationUuid' => $applicationUuid));
				$rep->closeCursor();
	
				$reply["message"] = "Application unassigned from step.";
				$reply["success"] = true;	
			}
			else
            {
                if ($ok) $reply["message"] = "Insufficient rights, you need to be Lead to assign applications.";
				else $reply["message"] = $rep->errorInfo();
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
