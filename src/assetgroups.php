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
	if (hasArg("createAssetGroup"))
	{
		$reply["accepted"] = true;
		$reply["query"] = "createAssetGroup";

		$name = getArg( "name" );
		$shortName = getArg( "shortName" );
		$projectUuid = getArg( "projectUuid" );
		$uuid = getArg( "uuid" );

		if (strlen($shortName) > 0 && strlen($projectUuid) > 0)
		{
			// Only if admin
            if ( isProjectAdmin() && validateName( $name ) && validateShortName( $shortName ) )
            {
				// Create step
				$qString = "INSERT INTO " . $tablePrefix . "assetgroups (name,shortName,projectId,uuid) 
				VALUES (
					:name,
					:shortName , 
					(SELECT " . $tablePrefix . "projects.id FROM " . $tablePrefix . "projects WHERE uuid = :projectUuid ),";

				$values = array('name' => $name,'shortName' => $shortName, 'projectUuid' => $projectUuid);
				
				if (strlen($uuid) > 0)
				{
					$qString = $qString . ":uuid ";
					$values['uuid'] = $uuid;
				}
				else
				{
					$qString = $qString . "uuid() ";
				}

				$qString = $qString . ") ON DUPLICATE KEY UPDATE shortName = VALUES(shortName), name = VALUES(name);";

				$rep = $db->prepare($qString);
				$ok = $rep->execute($values);
				$rep->closeCursor();

				if ($ok) $reply["message"] = "Asset Group \"" . $shortName . "\" added.";
				else $reply["message"] = $rep->errorInfo();

				$reply["success"] = $ok;
			}
		}
		else
		{
			$reply["message"] = "Invalid request, missing values";
			$reply["success"] = false;
		}
	}

	// ========= UPDATE ASSET GROUP ==========
	else if (hasArg("updateAssetGroup"))
	{
		$reply["accepted"] = true;
		$reply["query"] = "updateAssetGroup";

		$name = getArg( "name" );
		$shortName = getArg( "shortName" );
		$uuid = getArg( "uuid" );
		$comment = getArg( "comment" );

		if (strlen($shortName) > 0 AND strlen($uuid) > 0)
		{
			// Only if admin
            if ( isProjectAdmin()&& validateName( $name ) && validateShortName( $shortName ) )
            {
				$qString = "UPDATE {$assetgroupsTable}
					SET
						`name`= :name ,
						`shortName`= :shortName,
						`comment`= :comment
					WHERE `uuid` = :uuid ;";
				$values = array('name' => $name,'shortName' => $shortName, 'uuid' => $uuid, 'comment' => $comment);

                $rep = $db->prepare($qString);
				
                $rep->execute($values);
                $rep->closeCursor();

				$reply["message"] = "Asset Group \"" . $shortName . "\" updated.";
				$reply["success"] = true;
			}
		}
		else
		{
			$reply["message"] = "Invalid request, missing values";
			$reply["success"] = false;
		}

	}

	// ========= REMOVE ASSET GROUP ==========
	else if (hasArg("removeAssetGroup"))
	{
		$reply["accepted"] = true;
		$reply["query"] = "removeAssetGroup";

		$uuid = getArg("uuid");

		if (strlen($uuid) > 0)
		{
			//only if admin
			if (isProjectAdmin())
			{
				$rep = $db->prepare("UPDATE " . $tablePrefix . "assetgroups SET removed = 1 WHERE uuid= :uuid ;");
				$rep->execute(array('uuid' => $uuid));
				$rep->closeCursor();

				$reply["message"] = "Asset Group " . $uuid . " removed.";
				$reply["success"] = true;
			}
			else
            {
                $reply["message"] = "Insufficient rights, you need to be Admin to remove asset groups.";
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
