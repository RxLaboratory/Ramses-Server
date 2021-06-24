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
	if (isset($_GET["createPipeFile"]))
	{
		$reply["accepted"] = true;
		$reply["query"] = "createPipeFile";

        $uuid = getArg( "uuid" );
		$shortName = getArg( "shortName" );
		$fileTypeUuid = getArg ( "fileTypeUuid" );
		$colorSpaceUuid = getArg ( "colorSpaceUuid" );
		$projectUuid = getArg( "projectUuid" );

        if ($shortName != '' && $projectUuid != '')
		{
			//only if lead
			if (isProjectAdmin())
			{
				$queryStr = "INSERT INTO {$pipefileTable} (`shortName`, `projectId`, filetypeId, `colorSpaceId`, `uuid`)
					VALUES (
					:shortName,
					( SELECT {$projectsTable}.`id` FROM {$projectsTable} WHERE {$projectsTable}.`uuid` = :projectUuid ),
					( SELECT {$filetypesTable}.`id` FROM {$filetypesTable} WHERE {$filetypesTable}.`uuid` = :fileTypeUuid ),
					( SELECT {$colorspacesTable}.`id` FROM {$colorspacesTable} WHERE {$colorspacesTable}.`uuid` = :colorSpaceUuid ),";

				$values = array(
					'shortName' => $shortName,
					'projectUuid' => $projectUuid,
					'fileTypeUuid' => $fileTypeUuid,
					'colorSpaceUuid' => $colorSpaceUuid
				);

				if ( $uuid != "" )
				{
					$queryStr = $queryStr . ":uuid ";
					$values['uuid'] = $uuid;
				}
				else
				{
					$qStqueryStrring = $queryStr . "uuid() ";
				}

				$queryStr = $queryStr . ") ON DUPLICATE KEY UPDATE {$pipefileTable}.`removed` = 0 ;";
				$rep = $db->prepare($queryStr);
				$ok = $rep->execute( $values );
				$rep->closeCursor();

				if ($ok) $reply["message"] = "Pipe file created.";
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

    // ========= REMOVE ==========
	else if (isset($_GET["removePipeFile"]))
	{
		$reply["accepted"] = true;
		$reply["query"] = "removePipeFile";

		$uuid = getArg ( "uuid" );

        if ($uuid != '')
		{
			//only if lead
			if (isProjectAdmin())
			{
				$queryStr = "UPDATE {$pipefileTable} SET `removed` = 1 
					WHERE `uuid` = :uuid ;";
				$rep = $db->prepare($queryStr);
				$ok = $rep->execute( array(
					'uuid' => $uuid
				));
				$rep->closeCursor();

				if ($ok) $reply["message"] = "Pipe file removed.";
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

    // ========= UPDATE ==========
	else if (isset($_GET["updatePipeFile"]))
	{
        $uuid = getArg( "uuid" );
		$shortName = getArg( "shortName" );
		$fileTypeUuid = getArg ( "fileTypeUuid" );
		$colorSpaceUuid = getArg ( "colorSpaceUuid" );
		$comment = getArg ( "comment" );

        if ( $uuid != "" && ($shortName != "" or $fileTypeUuid != "" or $colorSpaceUuid != ""))
		{
			// Only if admin
            if ( isProjectAdmin() )
            {
				$qString = "UPDATE {$pipefileTable} SET `comment` = :comment, ";
                $setArray = array();
				$values = array('uuid' => $uuid, 'comment' => $comment);
				
				if ($shortName != "")
				{
					$setArray[] = " `shortName`= :shortName ";
                    $values["shortName"] = $shortName;
				}
                if ($fileTypeUuid != "")
				{
					$setArray[] = "`filetypeId`= (SELECT {$filetypesTable}.`id` FROM {$filetypesTable} WHERE `uuid` = :fileTypeUuid )";
                    $values["fileTypeUuid"] = $fileTypeUuid;
				}
                if ($colorSpaceUuid != "")
				{
					$setArray[] = "`colorSpaceId`= (SELECT {$colorspacesTable}.`id` FROM {$colorspacesTable} WHERE `uuid` = :colorSpaceUuid )";
                    $values["colorSpaceUuid"] = $colorSpaceUuid;
				}
 
				$qString = $qString . join(",", $setArray) . " WHERE `uuid`= :uuid ;";
			
				$rep = $db->prepare($qString);
				
                $ok = $rep->execute($values);
				$rep->closeCursor();

				if ($ok) $reply["message"] = "Pipe file updated.";
				else $reply["message"] = $rep->errorInfo();

				$reply["success"] = $ok;
			}
			else
            {
                $reply["message"] = "Insufficient rights, you need to be Admin to update pipe information.";
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
