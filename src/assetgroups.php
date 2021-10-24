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

	// ========= CREATE ASSET GROUP ==========
	if ( acceptReply("createAssetGroup", 'projectAdmin') )
	{

		$name = getArg( "name" );
		$shortName = getArg( "shortName" );
		$projectUuid = getArg( "projectUuid" );
		$uuid = getArg( "uuid" );

		$q = new DBQuery();
		$projectId = $q->id('projects', $projectUuid);
		
		$q->insert( "assetgroups", array( 'name', 'shortName', 'projectId', 'uuid' ));

		$q->bindName( $name );
		$q->bindShortName( $shortName );
		$q->bindStr( "uuid", $uuid, true );
		$q->bindInt( "projectId", $projectId );

		$q->execute("Asset Group '{$shortName}' added.");
		$q->close();
	}

	// ========= UPDATE ASSET GROUP ==========
	else if ( acceptReply("updateAssetGroup", 'projectAdmin') )
	{
		$name = getArg( "name" );
		$shortName = getArg( "shortName" );
		$uuid = getArg( "uuid" );
		$comment = getArg( "comment" );

		$q = new DBQuery();
		$q->update(
			"assetgroups",
			array(
				'name',
				'shortName',
				'comment'
			),
			$uuid
		);

		$q->bindName( $name );
		$q->bindShortName( $shortName );
		$q->bindStr( "comment", $comment );

		$q->execute("Asset Group '{$shortName}' updated.");
		$q->close();
	}

	// ========= REMOVE ASSET GROUP ==========
	else if ( acceptReply("removeAssetGroup", 'projectAdmin') )
	{
		$uuid = getArg("uuid");
        $q = new DBQuery();
		$q->remove( "assetgroups", $uuid );
	}

?>
