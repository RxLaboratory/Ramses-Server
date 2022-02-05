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
	if ( acceptReply("createSequence", 'projectAdmin') )
	{
		$name = getArg("name");
		$shortName = getArg("shortName");
		$projectUuid = getArg("projectUuid");
		$uuid = getArg("uuid");
		$color = getArg("color", "#434343");

		$q = new DBQuery();
		$projectId = $q->id('projects', $projectUuid);

		$q->insert( "sequences", array( 'name', 'shortName', 'projectId', 'uuid', 'color' ));

		$q->bindName( $name );
		$q->bindShortName( $shortName );
		$q->bindStr( "uuid", $uuid, true );
		$q->bindInt( "projectId", $projectId );
		$q->bindStr( "color", $color );

		$q->execute("Sequence '{$shortName}' added.");
		$q->close();
	}

	// ========= UPDATE SEQUENCE ==========
	else if ( acceptReply("updateSequence", 'projectAdmin') )
	{
		$name = getArg( "name" );
		$shortName = getArg( "shortName" );
		$uuid = getArg( "uuid" );
		$comment = getArg( "comment" );
		$color = getArg("color", "#434343");

		$q = new DBQuery();
		$q->update(
			"sequences",
			array(
				'name',
				'shortName',
				'comment',
				'color'
			),
			$uuid
		);

		$q->bindName( $name );
		$q->bindShortName( $shortName );
		$q->bindStr( "comment", $comment );
		$q->bindStr( "color", $color );

		$q->execute("Sequence '{$shortName}' updated.");
		$q->close();
	}

	// ========= REMOVE SEQUENCE ==========
	else if ( acceptReply("removeSequence", 'projectAdmin') )
	{
		$uuid = getArg("uuid");
        $q = new DBQuery();
		$q->remove( "sequences", $uuid );
	}

	else if ( acceptReply("setSequenceOrder", 'lead') )
	{
		$order = getArg("order");
		$uuid = getArg("uuid");

		$q = new DBQuery();
		$q->update(
			"sequences",
			array(
				'order'
			),
			$uuid
		);

		$q->bindInt( "order", $order );

		$q->execute("Sequence moved.");
		$q->close();
	}

?>
