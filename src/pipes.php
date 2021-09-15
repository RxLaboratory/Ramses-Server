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
	if (hasArg("createPipe"))
	{
		$reply["accepted"] = true;
		$reply["query"] = "createPipe";

		$inputUuid = getArg ( "inputUuid" );
		$outputUuid = getArg ( "outputUuid" );
		$uuid = getArg ( "uuid" );

		if ( $outputUuid != "" && $outputUuid != $inputUuid)
		{
			// Only if admin
            if ( isProjectAdmin() )
            {
				// Create pipe
				$qString = "INSERT INTO {$pipesTable} ( inputStepId, outputStepId, uuid ) 
				VALUES (
					(SELECT {$stepsTable}.`id` FROM {$stepsTable} WHERE `uuid` = :inputUuid ),
					(SELECT {$stepsTable}.`id` FROM {$stepsTable} WHERE `uuid` = :outputUuid ),";

				$values = array('inputUuid' => $inputUuid,'outputUuid' => $outputUuid);
				
				if ( $uuid != "" )
				{
					$qString = $qString . ":uuid ";
					$values['uuid'] = $uuid;
				}
				else
				{
					$qString = $qString . "uuid() ";
				}

				$qString = $qString . ") ON DUPLICATE KEY UPDATE removed = 0;";

				$rep = $db->prepare($qString);
				$ok = $rep->execute($values);
				$rep->closeCursor();

				if ($ok) $reply["message"] = "Pipe created.";
				else $reply["message"] = $rep->errorInfo();

				$reply["success"] = $ok;
			}
			else
            {
                $reply["message"] = "Insufficient rights, you need to be Admin to create pipes.";
                $reply["success"] = false;
            }
		}
		else
		{
			$reply["message"] = "Invalid request, missing values";
			$reply["success"] = false;
		}
	}

    // ========= UPDATE ==========
	else if (hasArg("updatePipe"))
	{
		acceptReply( "updatePipe" );

		$inputUuid = getArg ( "inputUuid" );
		$outputUuid = getArg ( "outputUuid" );
		$uuid = getArg ( "uuid" );
		$comment = getArg ( "comment" );

		// Only if admin
		if ( checkArgs( array( $uuid ) ) && isProjectAdmin() )
		{	
			$qString = "UPDATE {$pipesTable} SET `comment` = :comment";
			if ($inputUuid != "") $qString = $qString . ", `inputStepId` = (SELECT {$stepsTable}.`id` FROM {$stepsTable} WHERE `uuid` = :inputUuid )";
			if ($outputUuid != "") $qString = $qString . ", `outputStepId` = (SELECT {$stepsTable}.`id` FROM {$stepsTable} WHERE `uuid` = :outputUuid )";
			$qString = $qString . " WHERE `uuid` = :uuid;";

			$q = $db->prepare( $qString );
			
			$q->bindValue(':uuid', $uuid, PDO::PARAM_STR);
			$q->bindValue(':comment', $comment, PDO::PARAM_STR);
			if ($inputUuid != "") $q->bindValue(':inputUuid', $inputUuid, PDO::PARAM_STR);
			if ($outputUuid != "") $q->bindValue(':outputUuid', $outputUuid, PDO::PARAM_STR);

			sqlRequest( $q, "Pipe updated." );
			$q->closeCursor();
		}

	}

    // ========= REMOVE ==========
	else if (hasArg("removePipe"))
	{
		$reply["accepted"] = true;
		$reply["query"] = "removePipe";

		$uuid = getArg ( "uuid" );

		if (strlen($uuid) > 0)
		{
			//only if admin
			if (isProjectAdmin())
			{
				$rep = $db->prepare("UPDATE {$pipesTable} SET removed = 1 WHERE `uuid`= :uuid ;");
				
                $ok = $rep->execute(array('uuid' => $uuid));
				$rep->closeCursor();

				if ($ok) $reply["message"] = "Pipe removed.";
				else $reply["message"] = $rep->errorInfo();

				$reply["success"] = $ok;
			}
			else
            {
                $reply["message"] = "Insufficient rights, you need to be Admin to remove pipes.";
                $reply["success"] = false;
            }
		}
		else
		{
			$reply["message"] = "Invalid request, missing values";
			$reply["success"] = false;
		}
	}

	// ========= ASSIGN ==========
	else if (hasArg("assignPipeFile"))
	{
		$reply["accepted"] = true;
		$reply["query"] = "assignPipeFile";

		$pipeFileUuid = getArg( "pipeFileUuid" );
		$pipeUuid = getArg( "pipeUuid" );

		if ($pipeFileUuid != '' && $pipeUuid != '')
		{
			//only if lead
			if (isProjectAdmin())
			{
				$queryStr = "INSERT INTO {$pipefilepipeTable} (`pipeId`, `pipeFileId`)
					VALUES (
					( SELECT {$pipesTable}.`id` FROM {$pipesTable} WHERE {$pipesTable}.`uuid` = :pipeUuid ),
					( SELECT {$pipefileTable}.`id` FROM {$pipefileTable} WHERE {$pipefileTable}.`uuid` = :pipeFileUuid )
					) ; ";

				$values = array(
					'pipeFileUuid' => $pipeFileUuid,
					'pipeUuid' => $pipeUuid
				);

				$queryStr = $queryStr . ") ON DUPLICATE KEY UPDATE {$pipefilepipeTable}.`removed` = 0 ;";
				$rep = $db->prepare($queryStr);
				$ok = $rep->execute( $values );
				$rep->closeCursor();

				if ($ok) $reply["message"] = "New File assigned to pipe.";
				else $reply["message"] = $rep->errorInfo();

				$reply["success"] = $ok;
			}
			else
            {
                $reply["message"] = "Insufficient rights, you need to be Project Admin to modify the pipeline.";
                $reply["success"] = false;
            }
		}
		else 
		{
			$reply["message"] = "Invalid request, missing values";
			$reply["success"] = false;
		}
	}

	// ========= UNASSIGN ==========
	else if (hasArg("unassignPipeFile"))
	{
		$reply["accepted"] = true;
		$reply["query"] = "unassignPipeFile";

		$pipeFileUuid = getArg( "pipeFileUuid" );
		$pipeUuid = getArg( "pipeUuid" );

		if ($pipeFileUuid != '' && $pipeUuid != '')
		{
			//only if lead
			if (isProjectAdmin())
			{
				$queryStr = "DELETE {$pipefilepipeTable} FROM {$pipefilepipeTable}
					WHERE {$pipefilepipeTable}.`pipeFileId` = 
							( SELECT {$pipefileTable}.`id` FROM {$pipefileTable} WHERE {$pipefileTable}.`uuid` = :pipeFileUuid )
						AND {$pipefilepipeTable}.`pipeId` = 
							( SELECT {$pipesTable}.`id` FROM {$pipesTable} WHERE {$pipesTable}.`uuid` = :pipeUuid ) ;";
				$rep = $db->prepare($queryStr);
				$ok = $rep->execute( array(
					'pipeFileUuid' => $pipeFileUuid,
					'pipeUuid' => $pipeUuid
				));
				$rep->closeCursor();

				if ($ok) $reply["message"] = "File removed from pipe.";
				else $reply["message"] = $rep->errorInfo();

				$reply["success"] = $ok;
			}
			else
            {
                $reply["message"] = "Insufficient rights, you need to be Project Admin to modify the pipeline.";
                $reply["success"] = false;
            }
		}
		else 
		{
			$reply["message"] = "Invalid request, missing values";
			$reply["success"] = false;
		}
	}
?>
