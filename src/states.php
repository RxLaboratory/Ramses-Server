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
	else if (acceptReply("getStates") || hasArg("init"))
	{
		$q = new DBQuery();
		$states = $q->getAll("states",
			array(
				'name',
				'shortName',
				'uuid',
				'color',
				'completionRatio',
				'comment'
			),
			array(
				'completionRatio',
				'shortName',
				'name'
			)
		);

		// Adjust values
		for ($s = 0; $s < count($states); $s++)
		{
			$states[$s]['completionRatio'] = (int)$states[$s]['completionRatio'];
		}


		if (hasArg("init") )
        {
            $reply["content"]["states"] = $states;
        }
        else 
        {
            $reply["content"] = $states;
            $reply["message"] = "State list retreived";
            $reply["success"] = true;
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
			"states",
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
