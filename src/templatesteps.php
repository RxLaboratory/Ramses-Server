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

	// ========= CREATE STEP ==========
	if (isset($_GET["createTemplateStep"]))
	{
		$reply["accepted"] = true;
		$reply["query"] = "createTemplateStep";

		$name = "";
		$shortName = "";
		$uuid = "";

		$name = $_GET["name"] ?? "";
		$shortName = $_GET["shortName"] ?? "";
		$uuid = $_GET["uuid"] ?? "";

		if (strlen($shortName) > 0)
		{
			// Only if admin
            if ( isAdmin() )
            {
				if (strlen($uuid) > 0)
				{
					$qString = "INSERT INTO " . $tablePrefix . "templatesteps (name,shortName,uuid) VALUES ( :name , :shortName , :uuid ) ON DUPLICATE KEY UPDATE shortName = VALUES(shortName), name = VALUES(name);";
					$values = array('name' => $name,'shortName' => $shortName, 'uuid' => $uuid);
				}
				else
				{
					$qString = "INSERT INTO " . $tablePrefix . "templatesteps (name,shortName,uuid) VALUES ( :name , :shortName , uuid() ) ON DUPLICATE KEY UPDATE shortName = VALUES(shortName), name = VALUES(name);";
					$values = array('name' => $name,'shortName' => $shortName);
				}

				$rep = $db->prepare($qString);
				$rep->execute($values);
				$rep->closeCursor();

				$reply["message"] = "Step " . $shortName . " added.";
				$reply["success"] = true;

			}
			else
            {
                $reply["message"] = "Insufficient rights, you need to be Admin to create steps.";
                $reply["success"] = false;
            }
		}
		else
		{
			$reply["message"] = "Invalid request, missing values";
			$reply["success"] = false;
		}
	}

	// ========= GET STEPS ==========
	else if (isset($_GET["getTemplateSteps"]) || isset($_GET["init"]))
	{
		if (isset($_GET["getTemplateSteps"]))
		{
			$reply["accepted"] = true;
			$reply["query"] = "getTemplateSteps";
		}
		

		$rep = $db->query("SELECT name,shortName,uuid,type FROM " . $tablePrefix . "templatesteps WHERE removed = 0 ORDER BY shortName,name;");
		$steps = Array();
		while ($step = $rep->fetch())
		{
			$s = Array();
			$s['name'] = $step['name'];
			$s['shortName'] = $step['shortName'];
			$s['type'] = $step['type'];
			$s['uuid'] = $step['uuid'];
			$steps[] = $s;
		}
		$rep->closeCursor();

		if (isset($_GET["getTemplateSteps"]))
		{
			$reply["content"] = $steps;
			$reply["message"] = "Steps list retreived";
			$reply["success"] = true;
		}
		else 
		{
			$reply["content"]["templateSteps"] = $steps;
		}
	}

	// ========= UPDATE STEP ==========
	else if (isset($_GET["updateTemplateStep"]))
	{
		$reply["accepted"] = true;
		$reply["query"] = "updateTemplateStep";

		$name = "";
		$shortName = "";
		$uuid = "";
		$type = "";

		if (isset($_GET["name"])) $name = $_GET["name"];
        if (isset($_GET["shortName"])) $shortName = $_GET["shortName"];
        if (isset($_GET["uuid"])) $uuid = $_GET["uuid"];
		if (isset($_GET["type"])) $type = $_GET["type"];

		if (strlen($shortName) > 0 AND strlen($uuid) > 0)
		{
			// Only if admin
            if ( isAdmin() )
            {
				$qString = "UPDATE " . $tablePrefix . "templatesteps SET name= :name ,shortName= :shortName";
				$values = array('name' => $name,'shortName' => $shortName, 'uuid' => $uuid);
				
				if (strlen($type) > 0)
				{
					$qString = $qString . ", type= :type";
                    $values["type"] = $type;
				}

				$qString = $qString . " WHERE uuid= :uuid ;";
				
				$rep = $db->prepare($qString);
                $rep->execute($values);
                $rep->closeCursor();

				$reply["message"] = "Step \"" . $shortName . "\" updated.";
				$reply["success"] = true;
			}
			else
            {
                $reply["message"] = "Insufficient rights, you need to be Admin to update step information.";
                $reply["success"] = false;
            }
		}
		else
		{
			$reply["message"] = "Invalid request, missing values";
			$reply["success"] = false;
		}

	}

	// ========= REMOVE STEP ==========
	else if (isset($_GET["removeTemplateStep"]))
	{
		$reply["accepted"] = true;
		$reply["query"] = "removeTemplateStep";

		$uuid = "";

		if (isset($_GET["uuid"])) $uuid = $_GET["uuid"];

		if (strlen($uuid) > 0)
		{
			//only if admin
			if (isAdmin())
			{
				$rep = $db->prepare("UPDATE " . $tablePrefix . "templatesteps SET removed = 1 WHERE uuid= :uuid ;");
				$rep->execute(array('uuid' => $uuid));
				$rep->closeCursor();

				$reply["message"] = "Step " . $uuid . " removed.";
				$reply["success"] = true;
			}
			else
            {
                $reply["message"] = "Insufficient rights, you need to be Admin to remove steps.";
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
