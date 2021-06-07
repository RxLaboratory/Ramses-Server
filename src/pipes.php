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
	if (isset($_GET["createPipe"]))
	{
		$reply["accepted"] = true;
		$reply["query"] = "createPipe";

		$inputUuid = getArg ( "inputUuid" );
		$outputUuid = getArg ( "outputUuid" );
		$uuid = getArg ( "uuid" );

		if ( $uuid != "" && $outputUuid != "" && $outputUuid != $inputUuid)
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
	else if (isset($_GET["updatePipe"]))
	{
		$reply["accepted"] = true;
		$reply["query"] = "updatePipe";

		$inputUuid = getArg ( "inputUuid" );
		$outputUuid = getArg ( "outputUuid" );
		$uuid = getArg ( "uuid" );

		if ( $uuid != "" && ($inputUuid != "" or $outputUuid != ""))
		{
			// Only if admin
            if ( isProjectAdmin() )
            {
				$qString = "UPDATE {$pipesTable} SET ";
                $setArray = array();
				$values = array('uuid' => $uuid);
				
				if ($inputUuid != "")
				{
					$setArray[] = "`inputStepId`= (SELECT {$stepsTable}.`id` FROM {$stepsTable} WHERE `uuid` = :inputUuid )";
                    $values["inputUuid"] = $inputUuid;
				}
                if ($outputUuid != "")
				{
					$setArray[] = "`outputStepId`= (SELECT {$stepsTable}.`id` FROM {$stepsTable} WHERE `uuid` = :outputUuid )";
                    $values["outputUuid"] = $outputUuid;
				}


				$qString = $qString . join(",", $setArray) . " WHERE `uuid`= :uuid ;";
			
				$rep = $db->prepare($qString);
				
                $ok = $rep->execute($values);
				$rep->closeCursor();

				if ($ok) $reply["message"] = "Pipe updated.";
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

    // ========= REMOVE ==========
	else if (isset($_GET["removePipe"]))
	{
		$reply["accepted"] = true;
		$reply["query"] = "removePipe";

		$uuid = "";

		if (isset($_GET["uuid"])) $uuid = $_GET["uuid"];

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

?>
