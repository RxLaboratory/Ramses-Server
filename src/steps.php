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
	if ( acceptReply("createStep", 'projectAdmin') )
	{
		$name = getArg("name");
		$shortName = getArg("shortName");
		$projectUuid = getArg("projectUuid");
		$uuid = getArg("uuid", uuid());

		$q = new DBQuery();
		$projectId = $q->id("projects", $projectUuid);

		$q->insert('steps', array('name','shortName','projectId','uuid'));

		$q->bindName( $name );
		$q->bindShortName( $shortName );
		$q->bindInt( "projectId", $projectId, true );
		$q->bindStr( "uuid", $uuid, true );

		$q->execute("Step '{$shortName}' added.");
		$q->close();
	}

	// ========= UPDATE STEP ==========
	else if ( acceptReply("updateStep", 'projectAdmin') )
	{
		$name = getArg( "name" );
		$shortName = getArg( "shortName" );
		$uuid = getArg( "uuid" );
		$type = getArg( "type" );
		$comment = getArg( "comment" );
		$color = getArg( "color" );

		$q = new DBQuery();
		$q->update(
			"steps",
			array(
				'name',
				'shortName',
				'comment',
				'type',
				'color'
			),
			$uuid
		);

		$q->bindName( $name );
		$q->bindShortName( $shortName );
		$q->bindStr( "comment", $comment );
		$q->bindStr( "type", $type );
		$q->bindStr( "color", $color );

		$q->execute("Step \"{$shortName}\" updated.");
		$q->close();	
	}

	// =========== STEP ESTIMATIONS ==========
	else if ( acceptReply("setStepEstimations", 'projectAdmin') )
	{
		$uuid = getArg( "uuid" );
		$method = getArg( "method", "shot" );
		$veryEasy = getArg( "veryEasy", "0.2" );
		$easy = getArg( "easy", "0.5" );
		$medium = getArg( "medium", "1" );
		$hard = getArg( "hard", "2" );
		$veryHard = getArg( "veryHard", "3" );
		$multiplyGroupUuid = getArg( "multiplyGroupUuid" );

		$q = new DBQuery();
		$multiplyGroupId = $q->id("assetgroups", $multiplyGroupUuid);

		$q = new DBQuery();
		$q->update(
			"steps",
			array(
				'estimationMethod',
				'estimationVeryEasy',
				'estimationEasy',
				'estimationMedium',
				'estimationHard',
				'estimationVeryHard',
				'estimationMultiplyGroupId'
			),
			$uuid
		);

		$q->bindStr( "estimationMethod", $method );
		$q->bindStr( "estimationVeryEasy", $veryEasy );
		$q->bindStr( "estimationEasy", $easy );
		$q->bindStr( "estimationMedium", $medium );
		$q->bindStr( "estimationHard", $hard );
		$q->bindStr( "estimationVeryHard", $veryHard );
		$q->bindStr( "estimationMultiplyGroupId", $multiplyGroupId );

		$q->execute("Step estimations updated.");
		$q->close();
	}

	// ========= REMOVE STEP ==========
	else if ( acceptReply("removeStep", 'projectAdmin') )
	{
		$uuid = getArg("uuid");
        $q = new DBQuery();
		$q->remove( "steps", $uuid );
	}

	// ========= SET ORDER ==========
	else if ( acceptReply("setStepOrder", 'projectAdmin') )
	{
		$order = getArg("order");
		$uuid = getArg("uuid");

		$q = new DBQuery();
		$q->update(
			"steps",
			array(
				'order'
			),
			$uuid
		);

		$q->bindInt( "order", $order );

		$q->execute("Step moved.");
		$q->close();
	}

	// ========= MOVE ==========
	else if ( acceptReply("moveStep", 'projectAdmin') )
	{
		$order = getArg("order");
		$uuid = getArg("uuid");

		$q = new DBQuery();
		$r = $q.get( "steps", array('order', 'projectId'), $uuid);

		$previous = -1;
		$projectId = -1;

		if ($r)
		{
			$previous = (int)$r['order'];
			$projectId = (int)$r['projectId'];
		}

		$order = (int)$order;

		if ($previous > $order)
		{
			//Move all other steps
			$qString = "UPDATE {$tablePrefix}steps
				SET
					{$tablePrefix}steps.`order` = {$tablePrefix}steps.`order` + 1,
					latestUpdate = :udpateTime
				WHERE
					{$tablePrefix}steps.`order` >= :order
					AND
					{$tablePrefix}steps.`order` < :previous
					AND
					{$tablePrefix}steps.`projectId` = :projectId;";
			
			$q->prepare($qString);
			$q->bindInt('order', $order);
			$q->bindInt('previous', $previous);
			$q->bindInt('projectId', $projectId);
			$q->bindStr('udpateTime', dateTimeStr() );

			$q->execute();
			$rep->close();
		}
		else if ($previous >= 0)
		{
			//Move all other steps
			$qString = "UPDATE {$tablePrefix}steps
				SET
					{$tablePrefix}steps.`order` = {$tablePrefix}steps.`order` - 1,
					latestUpdate = :udpateTime
				WHERE
					{$tablePrefix}steps.`order` <= :order
					AND
					{$tablePrefix}steps.`order` > :previous
					AND
					{$tablePrefix}steps.`projectId` = :projectId;";

			$q->prepare($qString);
			$q->bindInt('order', $order);
			$q->bindInt('previous', $previous);
			$q->bindInt('projectId', $projectId);
			$q->bindStr('udpateTime', dateTimeStr() );
			
			$q->execute();
			$rep->close();
		}

		$q->update(
			"steps",
			array(
				'order'
			),
			$uuid
		);

		$q->bindInt( "order", $order );

		$q->execute("Step moved.");
		$q->close();
	}

	// ========= ASSIGN APPLICATION ==========
	else if ( acceptReply("assignApplication", 'projectAdmin') )
	{
		$stepUuid = getArg("stepUuid");
		$applicationUuid = getArg("applicationUuid");

		$q = new DBQuery();
		$stepId = $q->id("steps", $stepUuid);
		$applicationId = $q->id("applications", $applicationUuid);

		$q->insert( "stepapplication", array('stepId', 'applicationId'));
		$q->bindInt( "stepId", $stepId );
		$q->bindInt( "applicationId", $applicationId );

		$q->execute("Application assigned to step.");
		$q->close();
	}

	// ========= REMOVE APPLICATION ==========
	else if ( acceptReply( "unassignApplication", 'projectAdmin' ) )
	{
		$stepUuid = getArg("stepUuid");
		$applicationUuid = getArg("applicationUuid");

		$q = new DBQuery();
		$stepId = $q->id("steps", $stepUuid);
		$applicationId = $q->id("applications", $applicationUuid);
		$q->prepare( "UPDATE {$tablePrefix}stepapplication
			SET
				removed = 1,
				latestUpdate = :udpateTime
			WHERE
				stepId= :stepId
				AND
				applicationId= :applicationId
			;");
		$q->bindStr( 'udpateTime', dateTimeStr() );
		$q->bindInt( 'stepId', $stepId );
		$q->bindInt( 'applicationId', $applicationId );

		$q->execute("Application unassigned from step.");
		$q->close();
	}
?>
