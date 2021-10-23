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

	// ========= CREATE ASSET ==========
	if ( acceptReply( "createAsset", 'lead' ) )
	{
		$name = getArg("name");
		$shortName = getArg("shortName");
		$assetGroupUuid = getArg("assetGroupUuid");
		$tags = getArg("tags");
		$uuid = getArg("uuid");

		$q = new DBQuery();
		$assetGroupId = $q->id('assetgroups', $assetGroupUuid);

		$q->insert( "assets", array( 'name', 'shortName', 'assetGroupId', 'tags', 'uuid' ));

		$q->bindName( $name );
		$q->bindShortName( $shortName );
		$q->bindStr( "uuid", $uuid, true );
		$q->bindInt( "assetGroupId", $assetGroupId );
		$q->bindStr( "tags", $tags );

		$q->execute("Asset '{$shortName}' added.");
		$q->close();
	}
	
	// ========= UPDATE ASSET ==========
	else if ( acceptReply( "updateAsset", 'lead' ) )
	{
		$name = getArg( "name" );
		$shortName = getArg( "shortName" );
		$tags = getArg( "tags" );
		$assetGroupUuid = getArg( "assetGroupUuid" );
		$uuid = getArg( "uuid" );
		$comment = getArg( "comment" );

		$q = new DBQuery();
		$assetGroupId = $q->id('assetgroups', $assetGroupUuid);

		$q->update(
			"assets",
			array(
				'name',
				'shortName',
				'comment',
				'assetGroupId',
				'tags'
			),
			$uuid
		);

		$q->bindName( $name );
		$q->bindShortName( $shortName );
		$q->bindStr( "comment", $comment );
		$q->bindInt( "assetGroupId", $assetGroupId );
		$q->bindStr( "tags", $tags );
		
		$q->execute("Asset '{$shortName}' updated.");
		$q->close();
	}

	// ========= REMOVE ASSET ==========
	else if ( acceptReply( "removeAsset", 'lead' ) )
	{
		$uuid = getArg ( "uuid" );
		$q = new DBQuery();
		$q->remove( "assets", $uuid );
	}

	// ========= SET STATUS ==========
	else if ( acceptReply( "setAssetStatus") )
	{
		$uuid = getArg("uuid", uuid() );
		$assetUuid = getArg("assetUuid");
		$completionRatio = getArg("completionRatio", -1);
		$userUuid = getArg("userUuid", $_SESSION["userUuid"]);
		$stateUuid = getArg("stateUuid");
		$comment = getArg("comment");
		$version = getArg("version", 1);
		$stepUuid = getArg("stepUuid");
		$assignedUserUuid = getArg("assignedUserUuid");

		$q = new DBQuery();

		if ($assignedUserUuid == "") $assignedUserId = "NULL";
		else $assignedUserId = $q->id('users', $assignedUserUuid);

		$userId = $q->id('users', $userUuid);
		$stateId = $q->id('states', $stateUuid);
		$stepId = $q->id('steps', $stepUuid);
		$assetId = $q->id('assets', $assetUuid);

		$q->insert( "status", array( 'uuid', 'userId', 'stateId', 'stepId', 'assetId', 'assignedUserId', 'completionRatio', 'version', 'comment' ));

		$q->bindStr( 'uuid', $uuid );
		$q->bindInt( 'userId', $userId );
		$q->bindInt( 'stateId', $stateId );
		$q->bindInt( 'stepId', $stepId );
		$q->bindInt( 'assetId', $assetId );
		$q->bindInt( 'completionRatio', $completionRatio );
		$q->bindInt( 'version', $version );
		$q->bindStr( 'comment', $comment );
		if ($assignedUserUuid == "") $q->bindStr( 'assignedUserId', $assignedUserId );
		else $q->bindInt( 'assignedUserId', $assignedUserId );

		$q->execute("Asset status updated.");
		$q->close();
	}
?>
