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

    // ========= CREATE TEMPLATE ASSET GROUP ==========
	if (isset($_GET["createTemplateAssetGroup"]))
	{
        $reply["accepted"] = true;
		$reply["query"] = "createTemplateAssetGroup";

        $name = $_GET["name"] ?? "";
		$shortName = $_GET["shortName"] ?? "";
		$uuid = $_GET["uuid"] ?? "";

        if (strlen($shortName) > 0)
		{
            if (isAdmin())
            {
                if (strlen($uuid) > 0)
				{
                    $qString = "INSERT INTO " . $tablePrefix . "templateassetgroups (name,shortName,uuid) VALUES ( :name , :shortName , :uuid ) ON DUPLICATE KEY UPDATE shortName = VALUES(shortName), name = VALUES(name);";
                    $values = array('name' => $name,'shortName' => $shortName, 'uuid' => $uuid);
                }
                else 
                {
                    $qString = "INSERT INTO " . $tablePrefix . "templateassetgroups (name,shortName,uuid) VALUES ( :name , :shortName , uuid() ) ON DUPLICATE KEY UPDATE shortName = VALUES(shortName), name = VALUES(name);";
                    $values = array('name' => $name,'shortName' => $shortName);
                }

                $rep = $db->prepare($qString);
				$rep->execute($values);
				$rep->closeCursor();

                $reply["message"] = "Asset Group " . $shortName . " added.";
				$reply["success"] = true;
            }
            else
            {
                $reply["message"] = "Insufficient rights, you need to be Admin to create asset groups.";
                $reply["success"] = false;
            }
        }
        else
		{
			$reply["message"] = "Invalid request, missing values";
			$reply["success"] = false;
		}
    }

    // ========= GET TEMPLATE ASSET GROUPS ==========
	else if (isset($_GET["getTemplateAssetGroups"]) || isset($_GET["init"]))
	{
		if (isset($_GET["getTemplateAssetGroups"]))
		{
			$reply["accepted"] = true;
			$reply["query"] = "getTemplateAssetGroups";
		}

		$rep = $db->query("SELECT `name`,`shortName`,`uuid`, `comment` FROM " . $tablePrefix . "templateassetgroups WHERE removed = 0 ORDER BY shortName,name;");
		$assetGroups = Array();
		while ($assetGroup = $rep->fetch())
		{
			$ag = Array();
			$ag['name'] = $assetGroup['name'];
			$ag['shortName'] = $assetGroup['shortName'];
			$ag['comment'] = $assetGroup['comment'];
			$ag['uuid'] = $assetGroup['uuid'];
			$assetGroups[] = $ag;
		}
		$rep->closeCursor();

		if (isset($_GET["getTemplateAssetGroups"]))
		{
			$reply["content"] = $assetGroups;
			$reply["message"] = "Asset groups list retreived";
			$reply["success"] = true;
		}
		else {
			$reply["content"]["templateAssetGroups"] = $assetGroups;
		}
	}

	// ========= UPDATE ASSET GROUP ==========
	else if (isset($_GET["updateTemplateAssetGroup"]))
	{
		$reply["accepted"] = true;
		$reply["query"] = "updateTemplateAssetGroup";

		$name = getArg( "name" );
		$shortName = getArg( "shortName" );
		$uuid = getArg( "uuid" );
		$comment = getArg( "comment" );

		if (strlen($shortName) > 0 AND strlen($uuid) > 0)
		{
			// Only if admin
            if ( isAdmin() )
            {
				$qString = "UPDATE {$templateassetgroupsTable}
					SET
						`name`= :name,
						`shortName`= :shortName,
						`comment`= :comment
					WHERE uuid= :uuid ;";
				$values = array( 'name' => $name,'shortName' => $shortName, 'uuid' => $uuid, 'comment' => $comment );
			
				$rep = $db->prepare($qString);
                $rep->execute($values);
                $rep->closeCursor();

				$reply["message"] = "Asset Group \"" . $shortName . "\" updated.";
				$reply["success"] = true;
			}
			else
            {
                $reply["message"] = "Insufficient rights, you need to be Admin to update asset group information.";
                $reply["success"] = false;
            }
		}
		else
		{
			$reply["message"] = "Invalid request, missing values";
			$reply["success"] = false;
		}

	}

	// ========= REMOVE ASSET GROUP ==========
	else if (isset($_GET["removeTemplateAssetGroup"]))
	{
		$reply["accepted"] = true;
		$reply["query"] = "removeTemplateAssetGroup";

		$uuid = $_GET["uuid"] ?? "";

		if (strlen($uuid) > 0)
		{
			//only if admin
			if (isAdmin())
			{
				$rep = $db->prepare("UPDATE " . $tablePrefix . "templateassetgroups SET removed = 1 WHERE uuid= :uuid ;");
				$rep->execute(array('uuid' => $uuid));
				$rep->closeCursor();

				$reply["message"] = "Asset group " . $uuid . " removed.";
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