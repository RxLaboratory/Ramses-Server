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
	if (isset($_GET["createStep"]))
	{
		$reply["accepted"] = true;
		$reply["query"] = "createStep";

		$name = "";
		$shortName = "";
		$projectUuid = "";
		$uuid = "";

		if (isset($_GET["name"])) $name = $_GET["name"];
        if (isset($_GET["shortName"])) $shortName = $_GET["shortName"];
        if (isset($_GET["projectUuid"])) $projectUuid = $_GET["projectUuid"];
        if (isset($_GET["uuid"])) $uuid = $_GET["uuid"];

		if (strlen($shortName) > 0 && strlen(projectUuid) > 0)
		{
			// Only if admin
            if ( isAdmin() )
            {
				// Create step
				$qString = "INSERT INTO " . $tablePrefix . "steps (name,shortName,projectId,uuid) 
				VALUES (
					:name,
					:shortName , 
					(SELECT id FROM " . $tablePrefix . "projects WHERE uuid = projectUuid ),";

				$values = array('name' => $name,'shortName' => $shortName);
				
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

				if ($ok) $reply["message"] = "Step \"" . $shortName . "\" added.";
				else $reply["message"] = $rep->errorInfo();

				$reply["success"] = $ok;
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
	else if (isset($_GET["getSteps"]))
	{
		$reply["accepted"] = true;
		$reply["query"] = "getSteps";

		$rep = $db->query("SELECT name,shortName,uuid,type FROM " . $tablePrefix . "steps ORDER BY shortName,name;");
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

		$reply["content"] = $steps;
		$reply["message"] = "Steps list retreived";
		$reply["success"] = true;
	}

	// ========= UPDATE STEP ==========
	else if (isset($_GET["updateStep"]))
	{
		$reply["accepted"] = true;
		$reply["query"] = "updateStep";

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
				$qString = "UPDATE " . $tablePrefix . "steps SET name= :name ,shortName= :shortName";
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
	else if (isset($_GET["removeStep"]))
	{
		$reply["accepted"] = true;
		$reply["query"] = "removeStep";

		$uuid = "";

		if (isset($_GET["uuid"])) $uuid = $_GET["uuid"];

		if (strlen($uuid) > 0)
		{
			//only if admin
			if (isAdmin())
			{
				$rep = $db->prepare("DELETE " . $tablePrefix . "steps FROM " . $tablePrefix . "steps WHERE uuid= :uuid ;");
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

	// ========= ASSIGN USER ==========
	else if (isset($_GET["assignUser"]))
	{
		$reply["accepted"] = true;
		$reply["query"] = "assignUser";

		$userUuid = "";
		$stepUuid = "";

		if (isset($_GET["userUuid"])) $userUuid = $_GET["userUuid"];
		if (isset($_GET["stepUuid"])) $stepUuid = $_GET["stepUuid"];

		if (strlen($stepUuid) > 0 && strlen($userUuid) > 0)
		{
			//only if lead
			if (isLead())
			{
				$qString = "INSERT INTO " . $tablePrefix . "stepuser (stepId, userId) VALUES (
					( SELECT " . $tablePrefix . "steps.id FROM steps WHERE " . $tablePrefix . "steps.uuid = :stepUuid ),
					( SELECT " . $tablePrefix . "users.id FROM users WHERE " . $tablePrefix . "users.uuid = :userUuid )
					) ON DUPLICATE KEY UPDATE " . $tablePrefix . "stepuser.id = " . $tablePrefix . "stepuser.id ;";

				$rep = $db.prepare($qString);
				$ok = $rep.execute( array('stepUuid' => $stepUuid, 'userUuid' => $userUuid) );
				$rep.closeCursor();

				if ($ok) $reply["message"] = "User assigned to step.";
				else $reply["message"] = $rep->errorInfo();

				$reply["success"] = $ok;
			}
			else
            {
                $reply["message"] = "Insufficient rights, you need to be Lead to assign users.";
                $reply["success"] = false;
            }
		}
		else 
		{
			$reply["message"] = "Invalid request, missing values";
			$reply["success"] = false;
		}
	}

	// ========= REMOVE USER ==========
	else if (isset($_GET["unassignUser"]))
	{
		$reply["accepted"] = true;
		$reply["query"] = "assignUser";

		$userUuid = "";
		$stepUuid = "";

		if (isset($_GET["userUuid"])) $userUuid = $_GET["userUuid"];
		if (isset($_GET["stepUuid"])) $stepUuid = $_GET["stepUuid"];

		if (strlen($stepUuid) > 0 && strlen($userUuid) > 0)
		{
			//only if lead
			if (isLead())
			{
				$q = "DELETE " . $tablePrefix . "stepuser FROM " . $tablePrefix . "stepuser WHERE
				stepId= ( SELECT " . $tablePrefix . "steps.id FROM " . $tablePrefix . "steps WHERE " . $tablePrefix . "steps.uuid = :stepUuid )
				AND
				userId= ( SELECT " . $tablePrefix . "users.id FROM " . $tablePrefix . "users WHERE " . $tablePrefix . "users.uuid = :userUuid )
				;";
				$rep = $db->prepare($q);
				$ok = $rep->execute(array('stepUuid' => $stepUuid,'userUuid' => $userUuid));
				$rep->closeCursor();
	
				$reply["message"] = "User unassigned from step.";
				$reply["success"] = true;	
			}
			else
            {
                if ($ok) $reply["message"] = "Insufficient rights, you need to be Lead to assign users.";
				else $reply["message"] = $rep->errorInfo();
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
