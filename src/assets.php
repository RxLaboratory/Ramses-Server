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

	// ========= CREATE ASSET ==========
	if (isset($_GET["createAsset"]))
	{
		$reply["accepted"] = true;
		$reply["query"] = "createAsset";

		$name = $_GET["name"] ?? "";
		$shortName = $_GET["shortName"] ?? "";
		$assetGroupUuid = $_GET["assetGroupUuid"] ?? "";
		$tags = $_GET["tags"] ?? "";
		$uuid = $_GET["uuid"] ?? "";

		if (strlen($shortName) > 0)
		{
			// Only if lead
            if ( isLead() )
            {
				$qString = "INSERT INTO {$assetsTable} (`name`, `shortName`, `assetGroupId`, `tags`, `uuid`)
				VALUES (
					:name,
					:shortName,
					(SELECT {$assetgroupsTable}.`id` FROM {$assetgroupsTable} WHERE `uuid` = :assetGroupUuid ),
					:tags,";
				
				$values = array( 'name' => $name,'shortName' => $shortName, 'assetGroupUuid' => $assetGroupUuid, 'tags' => $tags);

				if (strlen($uuid) > 0)
				{
					$qString = $qString . ":uuid ";
					$values['uuid'] = $uuid;
				}
				else
				{
					$qString = $qString . "uuid() ";
				}

				$qString = $qString . ") ON DUPLICATE KEY UPDATE shortName = VALUES(shortName), name = VALUES(name), tags = VALUES(tags), removed = 0;";

				$rep = $db->prepare($qString);
				$ok = $rep->execute($values);
				$rep->closeCursor();

				if ($ok) $reply["message"] = "Asset \"" . $shortName . "\" added.";
				else $reply["message"] = $rep->errorInfo();

				$reply["success"] = $ok;
			}
			else
            {
                $reply["message"] = "Insufficient rights, you need to be Lead to create assets.";
                $reply["success"] = false;
            }
		}
		else
		{
			$reply["message"] = "Invalid request, missing values";
			$reply["success"] = false;
		}
	}
	
	// ========= UPDATE ASSET ==========
	else if (isset($_GET["updateAsset"]))
	{
		$reply["accepted"] = true;
		$reply["query"] = "updateAsset";

		$name = getArg( "name" );
		$shortName = getArg( "shortName" );
		$tags = getArg( "tags" );
		$assetGroupUuid = getArg( "assetGroupUuid" );
		$uuid = getArg( "uuid" );
		$comment = getArg( "comment" );

		if (strlen($shortName) > 0 AND strlen($uuid) > 0)
		{
			// Only if lead
            if ( isLead() )
            {
				$qString = "INSERT INTO {$assetsTable} (`name`, `shortName`, `tags`, `uuid`, `assetGroupId`, `comment`)
				VALUES(
					:name ,
					:shortName,
					:tags,
					:uuid,
					(SELECT {$assetgroupsTable}.`id` FROM {$assetgroupsTable} WHERE `uuid` = :assetGroupUuid ),
					:comment
				)
				AS newAsset
				ON DUPLICATE KEY UPDATE
					`name` = newAsset.`name`,
					`tags` = newAsset.`tags`,
					`assetGroupId` = newAsset.`assetGroupId`,
					`comment` = newAsset.`comment`,
					`removed` = 0;
				UPDATE {$assetsTable}
				SET `shortName` = :shortName
				WHERE `uuid` = :uuid;";

				$values = array('name' => $name,'shortName' => $shortName, 'tags' => $tags, 'assetGroupUuid' => $assetGroupUuid, 'uuid' => $uuid, 'comment' => $comment);

                $rep = $db->prepare($qString);
                $rep->execute($values);
                $rep->closeCursor();

				$reply["message"] = "Asset \"" . $shortName . "\" updated.";
				$reply["success"] = true;
			}
			else
            {
                $reply["message"] = "Insufficient rights, you need to be Lead to update asset information.";
                $reply["success"] = false;
            }
		}
		else
		{
			$reply["message"] = "Invalid request, missing values";
			$reply["success"] = false;
		}

	}

	// ========= REMOVE ASSET ==========
	else if (isset($_GET["removeAsset"]))
	{
		$reply["accepted"] = true;
		$reply["query"] = "removeAsset";

		$uuid = $_GET["uuid"] ?? "";

		if (strlen($uuid) > 0)
		{
			//only if admin
			if (isLead())
			{
				$rep = $db->prepare("UPDATE " . $tablePrefix . "assets SET removed = 1 WHERE uuid= :uuid ;");
				$rep->execute(array('uuid' => $uuid));
				$rep->closeCursor();

				$reply["message"] = "Asset " . $uuid . " removed.";
				$reply["success"] = true;
			}
			else
            {
                $reply["message"] = "Insufficient rights, you need to be Lead to remove assets.";
                $reply["success"] = false;
            }
		}
		else
		{
			$reply["message"] = "Invalid request, missing values";
			$reply["success"] = false;
		}
	}

	// ========= SET STATUS ==========
	else if (isset($_GET["setAssetStatus"]))
	{
		$reply["accepted"] = true;
		$reply["query"] = "setAssetStatus";

		$uuid = getAarg("uuid", uuid() );
		$assetUuid = getAarg("assetUuid");
		$completionRatio = getAarg("completionRatio", -1);
		$userUuid = getAarg("userUuid", $_SESSION["userUuid"]);
		$stateUuid = getAarg("stateUuid");
		$comment = getAarg("comment");
		$version = getAarg("version", 1);
		$stepUuid = getAarg("stepUuid");
		$assignedUserUuid = getAarg("assignedUserUuid");

		if (strlen($assetUuid) > 0 && strlen($userUuid) > 0 && strlen($stateUuid) > 0 && strlen($stepUuid) > 0 )
		{
			if ($assignedUserUuid == "") $assignUser = "NULL";
			else $assignUser = "(SELECT {$usersTable}.`id` FROM {$usersTable} WHERE {$usersTable}.`uuid` = :assignedUserUuid )";

			$qString = "INSERT INTO {$statusTable} (
				`uuid`,
				`userId`,
				`stateId`,
				`stepId`,
				`assetId`,
				`assignedUserId`
				)
				VALUES(
					:uuid ,
					(SELECT {$usersTable}.`id` FROM {$usersTable} WHERE {$usersTable}.`uuid` = :userUuid ),
					(SELECT {$statesTable}.`id` FROM {$statesTable} WHERE {$statesTable}.`uuid` = :stateUuid ),
					(SELECT {$stepsTable}.`id` FROM {$stepsTable} WHERE {$stepsTable}.`uuid` = :stepUuid ),
					(SELECT {$assetsTable}.`id` FROM {$assetsTable} WHERE {$assetsTable}.`uuid` = :assetUuid ), " .
					$assignUser .
				")
				ON DUPLICATE KEY UPDATE
					`removed` = 0;";

			$rep = $db->prepare($qString);
			$rep->bindValue(':uuid', $uuid, PDO::PARAM_STR);
			$rep->bindValue(':stateUuid', $stateUuid, PDO::PARAM_STR);
			$rep->bindValue(':userUuid', $userUuid, PDO::PARAM_STR);
			$rep->bindValue(':stepUuid', $stepUuid, PDO::PARAM_STR);
			$rep->bindValue(':assetUuid', $assetUuid, PDO::PARAM_STR);
			if ($assignedUserUuid == "") $rep->bindValue(':assignedUserUuid', $assignedUserUuid, PDO::PARAM_STR);
			//$rep->debugDumpParams();
			$rep->execute();
			$rep->closeCursor();

			$qString = "UPDATE {$statusTable}
				SET
					`completionRatio` = :completionRatio ,
					`version` = :version ,
					`comment` = :comment
				WHERE `uuid` = :uuid ;";
			$rep = $db->prepare($qString);
			$rep->bindValue(':uuid', $uuid, PDO::PARAM_STR);
			$rep->bindValue(':completionRatio', $completionRatio, PDO::PARAM_INT);
			$rep->bindValue(':version', $version, PDO::PARAM_INT);
			$rep->bindValue(':comment', $comment, PDO::PARAM_STR);
			//$rep->debugDumpParams();
			$rep->execute();
			$rep->closeCursor();

			$reply["message"] = "Asset status updated.";
			$reply["success"] = true;
		}
		else
		{
			$reply["message"] = "Invalid request, missing values";
			$reply["success"] = false;
		}
	}
?>
