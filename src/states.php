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

	// ========= CREATE STATE ==========
	if ( acceptReply("createState", 'admin') )
	{
		$name = getArg("name");
        $shortName = getArg("shortName");
        $uuid = getArg("uuid", uuid());

		$q = new DBQuery();
		$q->insert( "states", array( 'name', 'shortName', 'uuid' ));

		$q->bindName( $name );
		$q->bindShortName( $shortName );
		$q->bindStr( "uuid", $uuid, true );

		$q->execute("State '{$shortName}' added.");
		$q->close();
	}

	// ========= GET STATES ==========
	else if (hasArg("getStates") || hasArg("init"))
	{
		if (hasArg("getStates")) {
			$reply["accepted"] = true;
			$reply["query"] = "getStates";
		}
		

		$rep = $db->query( "SELECT
			`name`, `shortName`, `color`, `completionRatio`, `uuid`, `comment`
			FROM {$statesTable}
			WHERE `removed` = 0
			ORDER BY `completionRatio`, `shortName`, `name` ;"
			);
		$states = Array();
		while ($state = $rep->fetch())
		{
			$stat = Array();
			$stat['name'] = $state['name'];
			$stat['shortName'] = $state['shortName'];
			$stat['comment'] = $state['comment'];
			$stat['color'] = $state['color'];
			$stat['completionRatio'] = (int) $state['completionRatio'];
			$stat['uuid'] = $state['uuid'];
			$states[] = $stat;
		}
		$rep->closeCursor();

		if (hasArg("getStates")) {
			$reply["content"] = $states;
			$reply["message"] = "States list retreived";
			$reply["success"] = true;
		} else {
			$reply["content"]["states"] = $states;
		}
	}

	// ========= UPDATE STATE ==========
	else if ( acceptReply("updateState", 'admin') )
	{
		$name = getArg( "name" );
		$shortName = getArg( "shortName" );
		$color = getArg( "color" );
		$completionRatio = getArg( "completionRatio" );
		$uuid = getArg( "uuid" );
		$comment = getArg( "comment" );

		$q = new DBQuery();
		$q->update(
			"templatesteps",
			array(
				'name',
				'shortName',
				'comment',
				'color',
				'completionRatio'
			),
			$uuid
		);

		$q->bindName( $name );
		$q->bindShortName( $shortName );
		$q->bindStr( "comment", $comment );
		$q->bindInt( "completionRatio", $completionRatio );
		$q->bindStr( "color", $color );

		$q->execute("State '{$shortName}' updated.");
		$q->close();
	}

	// ========= REMOVE STATE ==========
	else if ( acceptReply("removeState", 'admin') )
	{
		$uuid = getArg("uuid");
		$q = new DBQuery();
		$q->remove( "states", $uuid );
	}
?>
