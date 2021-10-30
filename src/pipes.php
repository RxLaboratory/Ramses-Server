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
	if ( acceptReply( "createPipe", 'projectAdmin' ) )
	{
		$inputUuid = getArg ( "inputUuid" );
		$outputUuid = getArg ( "outputUuid" );
		$uuid = getArg ( "uuid" );

		$q = new DBQuery();
		$inputStepId = $q->id('steps', $inputUuid);
		$outputStepId = $q->id('steps', $outputUuid);

		$q->insert( "pipes", array( 'inputStepId', 'outputStepId', 'uuid' ));

		$q->bindStr( "uuid", $uuid, true );
		$q->bindInt( "inputStepId", $inputStepId );
		$q->bindInt( "outputStepId", $outputStepId );

		$q->execute("Pipe created.");
		$q->close();
	}

    // ========= UPDATE ==========
	else if ( acceptReply( "updatePipe", 'projectAdmin' ) )
	{
		$inputUuid = getArg ( "inputUuid" );
		$outputUuid = getArg ( "outputUuid" );
		$uuid = getArg ( "uuid" );
		$comment = getArg ( "comment" );

		$q = new DBQuery();
		$inputStepId = $q->id('steps', $inputUuid);
		$outputStepId = $q->id('steps', $outputUuid);

		$q->update(
			"pipes",
			array(
				'comment',
				'inputStepId',
				'outputStepId'
			),
			$uuid
		);

		$q->bindStr( "comment", $comment );
		$q->bindInt( "inputStepId", $inputStepId );
		$q->bindInt( "outputStepId", $outputStepId );

		$q->execute("Pipe updated.");
		$q->close();
	}

    // ========= REMOVE ==========
	else if ( acceptReply( "removePipe", 'projectAdmin' ) )
	{
		$uuid = getArg ( "uuid" );
		$q = new DBQuery();
		$q->remove( "pipes", $uuid, false );
	}

	// ========= ASSIGN ==========
	else if ( acceptReply( "assignPipeFile", 'projectAdmin' ) )
	{
		$pipeFileUuid = getArg( "pipeFileUuid" );
		$pipeUuid = getArg( "pipeUuid" );

		$q = new DBQuery();
		$pipeId = $q->id("pipes", $pipeUuid);
		$pipeFileId = $q->id("pipefile", $pipeFileUuid);

		$q->insert('pipefilepipe', array('pipeId', 'pipeFileId'));
		$q->bindInt( "pipeId", $pipeId );
		$q->bindInt( "pipeFileId", $pipeFileId );

		$q->execute("New File assigned to pipe.");
		$q->close();
	}

	// ========= UNASSIGN ==========
	else if ( acceptReply( "unassignPipeFile", 'projectAdmin' ) )
	{
		$pipeFileUuid = getArg( "pipeFileUuid" );
		$pipeUuid = getArg( "pipeUuid" );

		$q = new DBQuery();
		$pipeId = $q->id("pipes", $pipeUuid);
		$pipeFileId = $q->id("pipefile", $pipeFileUuid);

		$q->prepare( "UPDATE {$tablePrefix}pipefilepipe
			SET
				removed = 1,
				latestUpdate = :udpateTime
			WHERE
				pipeId= :pipeId
				AND
				pipeFileId= :pipeFileId
			;");

		$q->bindInt( "pipeId", $pipeId );
		$q->bindInt( "pipeFileId", $pipeFileId );
		$q->bindStr( 'udpateTime', dateTimeStr() );

		$q->execute("File removed from pipe.");
		$q->close();
	}
?>
