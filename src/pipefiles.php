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
	if ( acceptReply( "createPipeFile", 'projectAdmin' ) )
	{
		acceptReply( "createPipeFile" );

        $uuid = getArg( "uuid", uuid() );
		$shortName = getArg( "shortName" );
		$fileTypeUuid = getArg ( "fileTypeUuid" );
		$colorSpaceUuid = getArg ( "colorSpaceUuid" );
		$projectUuid = getArg( "projectUuid" );

		$q = new DBQuery();
		$fileTypeId = $q->id('filetypes', $fileTypeUuid);
		$colorSpaceId = $q->id('colorspaces', $colorSpaceUuid);
		$projectId = $q->id('projects', $projectUuid);

		$q->insert( "pipefile", array( 'shortName', 'projectId', 'filetypeId', 'colorSpaceId', 'uuid' ));

		$q->bindStr( "uuid", $uuid, true );
		$q->bindShortName( $shortName );
		$q->bindInt( "projectId", $projectId );
		$q->bindInt( "filetypeId", $filetypeId );
		$q->bindInt( "colorSpaceId", $colorSpaceId );

		$q->execute("Pipe file updated.");
		$q->close();
	}

    // ========= REMOVE ==========
	else if ( acceptReply( "removePipeFile", 'projectAdmin' ) )
	{
		$uuid = getArg ( "uuid" );
		$q = new DBQuery();
		$q->remove( "pipefile", $uuid );
    }

    // ========= UPDATE ==========
	else if ( acceptReply( "updatePipeFile", 'projectAdmin' ) )
	{
        $uuid = getArg( "uuid" );
		$shortName = getArg( "shortName" );
		$fileTypeUuid = getArg ( "fileTypeUuid" );
		$colorSpaceUuid = getArg ( "colorSpaceUuid" );
		$comment = getArg ( "comment" );
		$customSettings = getArg( "customSettings" );

		$q = new DBQuery();
		$fileTypeId = $q->id('filetypes', $fileTypeUuid);
		$colorSpaceId = $q->id('colorspaces', $colorSpaceUuid);
		$q->update(
			"pipefile",
			array(
				'comment',
				'shortName',
				'filetypeId',
				'colorSpaceId',
				'customSettings'
			),
			$uuid
		);

		$q->bindStr( "comment", $comment );
		$q->bindShortName( $shortName );
		$q->bindInt( "filetypeId", $filetypeId );
		$q->bindInt( "colorSpaceId", $colorSpaceId );
		$q->bindStr( "customSettings", $customSettings );

		$q->execute("Pipe file updated.");
		$q->close();
    }
?>
