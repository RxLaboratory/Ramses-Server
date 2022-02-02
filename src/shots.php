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
	if ( acceptReply("createShot", 'lead') )
	{
		$name = getArg("name");
		$shortName = getArg("shortName");
		$sequenceUuid = getArg("sequenceUuid");
		$duration = getArg("duration");
		$order = getArg("order", -1);
		$uuid = getArg("uuid", uuid());

		$q = new DBQuery();
		$sequenceId = $q->id('sequences', $sequenceUuid);

		// Get the order
		if ($order == -1)
		{
			$q->prepare("SELECT COUNT(*) as c FROM {$tablePrefix}shots
				WHERE sequenceId = :sequenceId;");

			$q->bindInt( 'sequenceId', $sequenceId);
			$q->execute();
			$r = $q->fetch();
			$q->close();

			if ($r) $order = (int)$r['c'];
			else $order = 0;
		}
		// Move other shots
		else 
		{
			$q->prepare("UPDATE {$tablePrefix}shots
				SET `order` = order + 1, `latestUpdate`= :updateTime
				WHERE order >= :order
				AND sequenceId = :sequenceId;");
			
			$q->bindInt( 'sequenceId', $sequenceId);
			$q->bindInt( 'order', $order);
			$q->bindStr( 'updateTime', dateTimeStr() );

			$q->execute();
			$q->close();
		}

		$q->insert( "shots", array( 'name', 'shortName', 'sequenceId', 'duration', 'order', 'uuid' ));

		$q->bindName( $name );
		$q->bindShortName( $shortName );
		$q->bindStr( "uuid", $uuid, true );
		$q->bindInt( "sequenceId", $sequenceId );
		$q->bindStr( "duration", $duration );
		$q->bindInt( "order", $order );

		$q->execute("Shot '{$shortName}' added.");
		$q->close();
	}

	// ========= UPDATE ==========
	else if ( acceptReply("updateShot", 'lead') )
	{
		$name = getArg( "name" );
		$shortName = getArg( "shortName" );
		$sequenceUuid = getArg( "sequenceUuid" );
		$duration = getArg( "duration" );
		$uuid = getArg( "uuid" );
		$comment = getArg( "comment" );

		$q = new DBQuery();
		$sequenceId = $q->id("sequences", $sequenceUuid);
		$q->update(
			"shots",
			array(
				'name',
				'shortName',
				'comment',
				'sequenceId',
				'duration'
			),
			$uuid
		);

		$q->bindName( $name );
		$q->bindShortName( $shortName );
		$q->bindStr( "comment", $comment );
		$q->bindInt( "sequenceId", $sequenceId );
		$q->bindStr( "duration", $duration );

		$q->execute("Shot '{$shortName}' updated.");
		$q->close();
	}

	// ========= SET ORDER ==========
	else if ( acceptReply("setShotOrder", 'lead') )
	{
		$order = getArg("order");
		$uuid = getArg("uuid");

		$q = new DBQuery();
		$q->update(
			"shots",
			array(
				'order'
			),
			$uuid
		);

		$q->bindInt( "order", $order );

		$q->execute("Shot moved.");
		$q->close();
	}

	// ========= MOVE ==========
	else if ( acceptReply("moveShot", 'lead') )
	{
		$order = getArg("order");
		$uuid = getArg("uuid");

		$q = new DBQuery();

		// Get previous order and project
		$q->prepare("SELECT {$tablePrefix}shots.`order`, {$tablePrefix}sequences.`projectId`
			FROM {$tablePrefix}shots
			JOIN {$tablePrefix}sequences ON {$tablePrefix}shots.`sequenceId` = {$tablePrefix}sequences.`id`
			WHERE {$tablePrefix}shots.`uuid` = :uuid
		;");

		$q->bindStr( 'uuid', $uuid );
		$q->execute();
		$r = $q->fetch();
		$q->close();

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
			//Move all other shots
			$q->prepare("UPDATE {$tablePrefix}shots
				JOIN {$tablePrefix}sequences ON {$tablePrefix}shots.`sequenceId` = {$tablePrefix}sequences.`id`
				SET
					{$tablePrefix}shots.`order` = {$tablePrefix}shots.`order` + 1, {$tablePrefix}shots.`latestUpdate`= :updateTime
				WHERE
					{$tablePrefix}shots.`order` >= :order
					AND
					{$tablePrefix}shots.`order` < :previous
					AND
					{$tablePrefix}sequences.`projectId` = :projectId
				;");
			
			$q->bindInt('order', $order);
			$q->bindInt('previous', $previous);
			$q->bindInt('projectId', $projectId);
			$q->bindStr('updateTime', dateTimeStr());

			$q->execute();
			$q->close();
		}
		else if ($previous >= 0)
		{
			//Move all other shots
			$q->prepare("UPDATE {$tablePrefix}shots
				JOIN {$tablePrefix}sequences ON {$tablePrefix}shots.`sequenceId` = {$tablePrefix}sequences.`id`
				SET
					{$tablePrefix}shots.`order` = {$tablePrefix}shots.`order` - 1, {$tablePrefix}shots.`latestUpdate`= :updateTime
				WHERE
				{$tablePrefix}shots.`order` <= :order
					AND
					{$tablePrefix}shots.`order` > :previous
					AND
					{$tablePrefix}sequences.`projectId` = :projectId
				;");
			
			$q->bindInt('order', $order);
			$q->bindInt('previous', $previous);
			$q->bindInt('projectId', $projectId);
			$q->bindStr('updateTime', dateTimeStr());

			$q->execute();
			$q->close();
		}

		$q->update(
			"shots",
			array(
				'order'
			),
			$uuid
		);

		$q->bindInt( "order", $order );

		$q->execute("Shot moved.");
		$q->close();
	}

	// ========= REMOVE ==========
	else if ( acceptReply("removeShot", 'lead') )
	{
		$uuid = getArg("uuid");

		$q = new DBQuery();
		// Get and Update order
		$r = $q->get('shots', array('order'), $uuid);
		$previous = $r['order'];
	
		$q->prepare("UPDATE {$tablePrefix}shots
				SET `order` = `order` - 1
				WHERE `order` > :previous;
			;");

		$q->bindInt('previous', $previous);
		$q->execute();
		$q->close();

		$q->remove('shots', $uuid);
	}

	// ========= SET STATUS ==========
	else if ( acceptReply("setShotStatus") )
	{
		$uuid = getArg("uuid", uuid() );
		$shotUuid =  getArg("shotUuid");
		$completionRatio = getArg("completionRatio", -1);
		$userUuid = getArg("userUuid", $_SESSION["userUuid"]);
		$stateUuid = getArg("stateUuid");
		$comment = getArg("comment");
		$version = getArg("version", 1);
		$stepUuid = getArg("stepUuid");
		$assignedUserUuid = getArg("assignedUserUuid");

		$q = new DBQuery();

	    $assignedUserId = $q->id('users', $assignedUserUuid);

		$userId = $q->id('users', $userUuid);
		$stateId = $q->id('states', $stateUuid);
		$stepId = $q->id('steps', $stepUuid);
		$shotId = $q->id('shots', $shotUuid);

		$q->insert( "status", array( 'uuid', 'userId', 'stateId', 'stepId', 'shotId', 'assignedUserId', 'completionRatio', 'version', 'comment' ));

		$q->bindStr( 'uuid', $uuid );
		$q->bindInt( 'userId', $userId );
		$q->bindInt( 'stateId', $stateId );
		$q->bindInt( 'stepId', $stepId );
		$q->bindInt( 'shotId', $shotId );
		$q->bindInt( 'completionRatio', $completionRatio );
		$q->bindInt( 'version', $version );
		$q->bindStr( 'comment', $comment );
		$q->bindInt( 'assignedUserId', $assignedUserId );

		$q->execute("Shot status updated.");
		$q->close();
	}

	// ========= ASSIGN ASSET ========
	else if ( acceptReply("assignAsset", 'lead') )
	{
		$uuid = getArg( "uuid" );
		$assetUuid = getArg( "assetUuid" );

		$q = new DBQuery();
		$assetId = $q->id("assets", $assetUuid);
		$shotId = $q->id("shots", $uuid);

		$q->insert('shotasset', array('shotId', 'assetId'));

		$q->bindInt( "shotId", $shotId );
		$q->bindInt( "assetId", $assetId );

		$q->execute("Asset assigned to shot.");
		$q->close();
	}

	// ========= UNASSIGN ASSET ========
	else if ( acceptReply("unassignAsset", 'lead') )
	{
		$uuid = getArg( "uuid" );
		$assetUuid = getArg( "assetUuid" );

		$q = new DBQuery();
		$assetId = $q->id("assets", $assetUuid);
		$shotId = $q->id("shots", $uuid);

		$q->prepare( "UPDATE {$tablePrefix}shotasset
			SET
				removed = 1,
				latestUpdate = :udpateTime
			WHERE
				assetId= :assetId
				AND
				shotId= :shotId
			;");

		$q->bindStr( 'udpateTime', dateTimeStr() );
		$q->bindInt( 'assetId', $assetId );
		$q->bindInt( 'shotId', $shotId );

		$q->execute("Asset unassigned from shot.");
		$q->close();
	}
?>
