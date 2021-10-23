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


	// ========= UPDATE STATUS ==========
	if ( acceptReply("updateStatus") )
	{
		$comment = getArg ( "comment" );
		$version = getArg ( "version" );
		$completionRatio = getArg ("completionRatio" );
		$stateUuid = getArg ( "stateUuid" );
		$uuid = getArg ("uuid" );
        $published = getArg("published", 0);
        $assignedUserUuid = getArg("assignedUserUuid");
        $timeSpent = getArg("timeSpent", -1);
        $date = getArg("date");
        $estimation = getArg("estimation");
        $difficulty = getArg("difficulty");

        $q = new DBQuery();

        $stateId = $q->id("states", $stateUuid);

        $assignedUserId = $q->id('users', $assignedUserUuid);

		$q->update(
			"status",
			array(
				'stateId',
				'completionRatio',
				'version',
				'comment',
				'published',
				'assignedUserId',
				'timeSpent',
				'date',
				'difficulty',
				'estimation'
			),
			$uuid
		);

        $q->bindInt( "stateId", $stateId );
		$q->bindStr( "completionRatio", $completionRatio );
		$q->bindInt( "version", $version );
		$q->bindStr( "comment", $comment );
		$q->bindInt( "published", $published );
        $q->bindInt( 'assignedUserId', $assignedUserId );
		$q->bindInt( "timeSpent", $timeSpent );
		$q->bindStr( "date", $date );
		$q->bindStr( "difficulty", $difficulty );
		$q->bindStr( "estimation", $estimation );

        $q->execute("Status updated.");
		$q->close();	
	}

	// ========= REMOVE STATUS ==========
	else if ( acceptReply("removeStatus") )
	{
        $uuid = getArg("uuid");
		$q = new DBQuery();
		$q->remove( "status", $uuid, false );
	}

    // ========= SET STATUS USER ==========
	else if ( acceptReply("setStatusUser") )
	{
		$uuid = getArg ("uuid" );
		$userUuid = getArg ("userUuid" );

        $q = new DBQuery();
        $userId = $q->id("users", $userUuid);
        $q->update(
			"status",
			array(
				'userId'
			),
			$uuid
		);
        
        $q->bindInt( "userId", $userId );

        $q->execute( "Status user changed." );
		$q->close();	
	}

?>
