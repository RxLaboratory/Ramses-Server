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
				$qString = "INSERT INTO " . $tablePrefix . "assets (name, shortName, assetGroupId, tags, uuid)
				VALUES (
					:name,
					:shortName,
					(SELECT " . $tablePrefix . "assetgroups.id FROM " . $tablePrefix . "assetgroups WHERE uuid = :assetGroupUuid ),
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

				$qString = $qString . ") ON DUPLICATE KEY UPDATE shortName = VALUES(shortName), name = VALUES(name), tags = VALUES(tags);";

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

		$name = $_GET["name"] ?? "";
		$shortName = $_GET["shortName"] ?? "";
		$tags = $_GET["tags"] ?? "";
		$assetGroupUuid = $_GET["assetGroupUuid"] ?? "";
		$uuid = $_GET["uuid"] ?? "";

		if (strlen($shortName) > 0 AND strlen($uuid) > 0)
		{
			// Only if lead
            if ( isProjectLead() )
            {
				$qString = "UPDATE " . $tablePrefix . "assets
				SET
					`name`= :name ,
					`shortName`= :shortName,
					`tags` = :tags,
					`assetGroupId` = (SELECT " . $tablePrefix . "assetgroups.id FROM " . $tablePrefix . "assetgroups WHERE uuid = :assetGroupUuid )
				WHERE
					uuid= :uuid ;";
				$values = array('name' => $name,'shortName' => $shortName, 'tags' => $tags, 'assetGroupUuid' => $assetGroupUuid, 'uuid' => $uuid);

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
			if (isProjectAdmin())
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
/*
	// TODO shot - Asset assignment
	if ($reply["type"] == "assignAsset")
	{
		$reply["accepted"] = true;

		$assetId = "";
		$shotId = "";

		$data = json_decode(file_get_contents('php://input'));
		if ($data)
		{
			if (isset($data->{'assetId'})) $assetId = $data->{'assetId'};
			if (isset($data->{'shotId'})) $shotId = $data->{'shotId'};
		}

		if (strlen($assetId) > 0 AND strlen($shotId) > 0)
		{
			$q = "INSERT INTO " . $tablePrefix . "assetstatuses (shotId,assetId)
			VALUES (
				 ( SELECT id FROM " . $tablePrefix . "shots WHERE uuid = :shotId ) ,
				 ( SELECT id FROM " . $tablePrefix . "assets WHERE uuid = :assetId )
				 ) ON DUPLICATE KEY UPDATE shotId = VALUES(shotId);";

			$rep = $db->prepare($q);
			$rep->execute(array('shotId' => $shotId , 'assetId' => $assetId));
			$rep->closeCursor();
			$reply["message"] = "Asset assigned.";
			$reply["success"] = true;

		}
		else
		{
			$reply["message"] = "Invalid request, missing values.";
			$reply["success"] = false;
		}
	}

	if ($reply["type"] == "assignAssets")
	{
		$reply["accepted"] = true;

		$assignments = array();

		$data = json_decode(file_get_contents('php://input'));
		if ($data)
		{
			if (isset($data->{'assignments'})) $assignments = $data->{'assignments'};
		}

		if (count($assignments) > 0)
		{
			$q = "INSERT INTO " . $tablePrefix . "assetstatuses (shotId,assetId) VALUES ";
			$placeHolder = "(( SELECT id FROM " . $tablePrefix . "shots WHERE uuid = ? ),( SELECT id FROM " . $tablePrefix . "assets WHERE uuid = ? ))";

			$placeHolders = array();
			$values = array();
			foreach($assignments as $assignment)
			{
				$placeHolders[] = $placeHolder;
				$values[] = $assignment->{'shotId'};
				$values[] = $assignment->{'assetId'};
			}

			$q = $q . implode(",",$placeHolders);
			$q = $q . " ON DUPLICATE KEY UPDATE shotId = VALUES(shotId);";

			$rep = $db->prepare($q);
			$rep->execute($values);
			$rep->closeCursor();
			$reply["message"] = "Assets assigned.";
			$reply["success"] = true;
		}
		else
		{
			$reply["message"] = "Invalid request, missing values";
			$reply["success"] = false;
		}
	}

	if ($reply["type"] == "addAssignAssets")
	{
		$reply["accepted"] = true;

		$assets = array();
		$stageId = "";

		$data = json_decode(file_get_contents('php://input'));
		if ($data)
		{
			if (isset($data->{'assets'})) $assets = $data->{'assets'};
			if (isset($data->{'stageId'})) $stageId = $data->{'stageId'};
		}

		if (strlen($stageId) > 0 AND count($assets) > 0)
		{
			$qAdd = "INSERT INTO " . $tablePrefix . "assets (name,shortName,statusId,comment,uuid,stageId) VALUES ";
			$qAssign = "INSERT INTO " . $tablePrefix . "assetstatuses (shotId,assetId) VALUES ";
			$placeHolderAdd = "(?,?,( SELECT id FROM " . $tablePrefix . "status WHERE uuid = ? ),?,?,( SELECT id FROM " . $tablePrefix . "stages WHERE uuid = ? ))";
			$placeHolderAssign = "(( SELECT id FROM " . $tablePrefix . "shots WHERE uuid = ? ),( SELECT id FROM " . $tablePrefix . "assets WHERE uuid = ? ))";

			$placeHoldersAdd = array();
			$placeHoldersAssign = array();
			$values = array();

			//add values
			foreach($assets as $asset)
			{
				$placeHoldersAdd[] = $placeHolderAdd;
				$values[] = $asset->{'name'};
				$values[] = $asset->{'shortName'};
				$values[] = $asset->{'statusId'};
				$values[] = $asset->{'comment'};
				$values[] = $asset->{'uuid'};
				$values[] = $stageId;
			}
			//assign values
			foreach($assets as $asset)
			{
				$placeHoldersAssign[] = $placeHolderAssign;
				$values[] = $asset->{'shotId'};
				$values[] = $asset->{'uuid'};
			}

			$qAdd = $qAdd . implode(",",$placeHoldersAdd);
			$qAdd = $qAdd . " ON DUPLICATE KEY UPDATE shortName = VALUES(shortName), name = VALUES(name);\n";
			$qAssign = $qAssign . implode(",",$placeHoldersAssign);
			$qAssign = $qAssign . " ON DUPLICATE KEY UPDATE shotId = VALUES(shotId);";
			$q = $qAdd . $qAssign;

			$rep = $db->prepare($q);
			$rep->execute($values);
			$rep->closeCursor();
			$reply["message"] = "Assets added and assigned.";
			$reply["success"] = true;
		}
		else
		{
			$reply["message"] = "Invalid request, missing values";
			$reply["success"] = false;
		}

	}

	if ($reply["type"] == "unAssignAsset")
	{
		$reply["accepted"] = true;

		$assetId = "";
		$shotId = "";

		$data = json_decode(file_get_contents('php://input'));
		if ($data)
		{
			$assetId = $data->{'assetId'};
			$shotId = $data->{'shotId'};
		}

		if (strlen($assetId) > 0 AND strlen($shotId) > 0)
		{
			$q = "DELETE " . $tablePrefix . "assetstatuses FROM " . $tablePrefix . "assetstatuses WHERE shotId = ( SELECT id FROM " . $tablePrefix . "shots WHERE uuid = :shotId ) AND assetId = ( SELECT id FROM " . $tablePrefix . "assets WHERE uuid = :assetId ) ;";

			$rep = $db->prepare($q);
			$rep->execute(array('shotId' => $shotId , 'assetId' => $assetId));
			$rep->closeCursor();
			$reply["message"] = "Asset un-assigned.";
			$reply["success"] = true;

		}
		else
		{
			$reply["message"] = "Invalid request, missing values.";
			$reply["success"] = false;
		}



	}

	// TODO Asset statuses
	if ($reply["type"] == "setAssetStatus")
	{
		$reply["accepted"] = true;

		$assetId = "";
		$statusId = "";

		$data = json_decode(file_get_contents('php://input'));
		if ($data)
		{
			if (isset($data->{'statusId'})) $statusId = $data->{'statusId'};
			if (isset($data->{'assetId'})) $assetId = $data->{'assetId'};
		}

		if (strlen($statusId) > 0 AND strlen($assetId) > 0)
		{
			$q = "UPDATE " . $tablePrefix . "assets SET statusId= ( SELECT id FROM " . $tablePrefix . "status WHERE uuid = :statusId ) WHERE uuid= :assetId ;";

			$rep = $db->prepare($q);
			$rep->execute(array('statusId' => $statusId, 'assetId' => $assetId));
			$rep->closeCursor();

			$reply["message"] = "Status for the asset (id:" . $assetId . ") has been updated.";
			$reply["success"] = true;

		}
		else
		{
			$reply["message"] = "Invalid request, missing values.";
			$reply["success"] = false;
		}
	}*/
?>
