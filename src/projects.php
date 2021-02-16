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

	// ========= CREATE PROJECT ==========
	if (isset($_GET["createProject"]))
	{
		$reply["accepted"] = true;
        $reply["query"] = "createProject";

		$name = "";
		$shortName = "";
		$uuid = "";

		if (isset($_GET["name"])) $name = $_GET["name"];
        if (isset($_GET["shortName"])) $shortName = $_GET["shortName"];
        if (isset($_GET["uuid"])) $uuid = $_GET["uuid"];

		if (strlen($shortName) > 0)
		{
			// Only if admin
            if ( isAdmin() )
            {
				if (strlen($uuid) > 0)
				{
					$qString = "INSERT INTO " . $tablePrefix . "projects (name,shortName,uuid) VALUES ( :name , :shortName , :uuid ) ON DUPLICATE KEY UPDATE name = VALUES(name) , shortName = VALUES(shortName);";
					$values = array('name' => $name, 'shortName' => $shortName, 'uuid' => $uuid);
				}
				else
				{
					$qString = "INSERT INTO " . $tablePrefix . "projects (name,shortName,uuid) VALUES ( :name , :shortName , uuid() );";
					$values = array('name' => $name, 'shortName' => $shortName);
				}

				$rep = $db->prepare($qString);
				$rep->execute($values);
				$rep->closeCursor();

				$reply["message"] = "Project " . $shortName . " created.";
				$reply["success"] = true;

			}
			else
            {
                $reply["message"] = "Insufficient rights, you need to be Admin to create projects.";
                $reply["success"] = false;
            }
		}
		else
		{
			$reply["message"] = "Invalid request, missing values";
			$reply["success"] = false;
		}
	}

	// ========= GET PROJECTS ==========
	//TODO only projects assigned to the user (if not admin)
	else if (isset($_GET["getProjects"]))
	{
		$reply["accepted"] = true;
		$reply["query"] = "getProjects";


		$rep = $db->query("SELECT name,shortName,uuid,folderPath,id FROM " . $tablePrefix . "projects ORDER BY shortName,name;");
		$projects = Array();

		while ($project = $rep->fetch())
		{
			$proj = Array();
			$proj['name'] = $project['name'];
			$proj['shortName'] = $project['shortName'];
			$proj['folderPath'] = $project['folderPath'];
			$proj['uuid'] = $project['uuid'];
			//get steps
			$projectSteps = Array();
			$repSteps = $db->query("SELECT " . $tablePrefix . "steps.uuid as stepId FROM " . $tablePrefix . "projectstep JOIN " . $tablePrefix . "steps ON " . $tablePrefix . "steps.id = " . $tablePrefix . "projectstep.stepId WHERE projectId=" . $project['id'] . " ORDER BY " . $tablePrefix . "steps.shortName;");
			while ($projectStep = $repSteps->fetch())
			{
				$projectSteps[] = $projectStep['stepId'];
			}
			$proj['steps'] = $projectSteps;
			//get shots
			$projectShots = Array();
			$repShots = $db->query("SELECT " . $tablePrefix . "shots.uuid as shotId FROM " . $tablePrefix . "projectshot JOIN " . $tablePrefix . "shots ON " . $tablePrefix . "shots.id = " . $tablePrefix . "projectshot.shotId WHERE projectId=" . $project['id'] . ";");
			while ($projectShot = $repShots->fetch())
			{
				$projectShots[] = $projectShot['shotId'];
			}
			//get asset groups
			$projectAssetGroups = Array();
			$repAssetGroups = $db->query("SELECT " . $tablePrefix . "assetgroups.uuid as assetgroupId FROM " . $tablePrefix . "projectassetgroup JOIN " . $tablePrefix . "assetgroups ON " . $tablePrefix . "assetgroups.id = " . $tablePrefix . "projectassetgroup.assetgroupId WHERE projectId=" . $project['id'] . ";");
			while ($projectAssetGroup = $repAssetGroups->fetch())
			{
				$projectAssetGroups[] = $projectAssetGroup['assetgroupId'];
			}
			$proj['assetGroups'] = $projectAssetGroups;
			$projects[] = $proj;
		}
		$rep->closeCursor();

		$reply["content"] = $projects;
		$reply["message"] = "Projects list retrieved";
		$reply["success"] = true;
	}

	// ========= UPDATE PROJECT ==========
	else if (isset($_GET["updateProject"]))
	{
		$reply["accepted"] = true;
		$reply["query"] = "updateProject";

		$name = "";
		$shortName = "";
		$uuid = "";
		$folderPath = "";

		if (isset($_GET["name"])) $name = $_GET["name"];
        if (isset($_GET["shortName"])) $shortName = $_GET["shortName"];
        if (isset($_GET["uuid"])) $uuid = $_GET["uuid"];
        if (isset($_GET["folderPath"])) $folderPath = $_GET["folderPath"];

		if (strlen($shortName) > 0 AND strlen($uuid) > 0)
		{
			// Only if admin
            if ( isAdmin() )
            {
				$rep = $db->prepare("UPDATE " . $tablePrefix . "projects SET name= :name ,shortName= :shortName, folderPath= :folderPath WHERE uuid= :uuid ;");
				$rep->execute(array('name' => $name,'shortName' => $shortName, 'folderPath' => $folderPath, 'uuid' => $uuid));
				$rep->closeCursor();

				$reply["message"] = "Project \"" . $shortName . "\" updated.";
				$reply["success"] = true;
			}
			else
            {
                $reply["message"] = "Insufficient rights, you need to be Admin to update project information.";
                $reply["success"] = false;
            }
		}
		else
		{
			$reply["message"] = "Invalid request, missing values";
			$reply["success"] = false;
		}

	}

	// ========= REMOVE PROJECT ==========
	else if (isset($_GET["removeProject"]))
	{
		$reply["accepted"] = true;
		$reply["query"] = "removeProject";

		$uuid = "";

		if (isset($_GET["uuid"])) $uuid = $_GET["uuid"];

		if (strlen($uuid) > 0)
		{
			//only if admin
			if (isAdmin())
			{
				$rep = $db->prepare("DELETE " . $tablePrefix . "projects FROM " . $tablePrefix . "projects WHERE uuid= :uuid ;");
				$rep->execute(array('uuid' => $uuid));
				$rep->closeCursor();

				$reply["message"] = "Project " . $uuid . " removed.";
				$reply["success"] = true;
			}
            else
            {
                $reply["message"] = "Insufficient rights, you need to be Admin to remove projects.";
                $reply["success"] = false;
            }

		}
		else
		{
			$reply["message"] = "Invalid request, missing values";
			$reply["success"] = false;
		}
	}

	// ========= ASSOCIATE STAGE WITH PROJECT ==========
	else if (isset($_GET["assignStep"]))
	{
		$reply["accepted"] = true;
		$reply["query"] = "assignStep";

		$stepUuid = "";
		$projectUuid = "";

		if (isset($_GET["stepUuid"])) $stepUuid = $_GET["stepUuid"];
		if (isset($_GET["projectUuid"])) $projectUuid = $_GET["projectUuid"];

		if (strlen($stepUuid) > 0 AND strlen($projectUuid) > 0)
		{
			if (isAdmin())
			{
				$q = "INSERT INTO " . $tablePrefix . "projectstep (stepId,projectId) VALUES (
				( SELECT " . $tablePrefix . "steps.id FROM " . $tablePrefix . "steps WHERE " . $tablePrefix . "steps.uuid = :stepUuid )
				,
				( SELECT " . $tablePrefix . "projects.id FROM " . $tablePrefix . "projects WHERE " . $tablePrefix . "projects.uuid = :projectUuid )
				) ON DUPLICATE KEY UPDATE " . $tablePrefix . "projectstep.id = " . $tablePrefix . "projectstep.id ;";

				$rep = $db->prepare($q);
				$rep->execute(array('stepUuid' => $stepUuid,'projectUuid' => $projectUuid));
				$rep->closeCursor();

				$reply["message"] = "Step " . $stepUuid . " associated with project " . $projectUuid . ".";
				$reply["success"] = true;
			}
			else
            {
                $reply["message"] = "Insufficient rights, you need to be Admin to assign steps to projects.";
                $reply["success"] = false;
            }
		}
		else
		{
			$reply["message"] = "Invalid request, missing values";
			$reply["success"] = false;
		}
	}

	// ========= REMOVE STAGE FROM PROJECT ==========
	else if (isset($_GET["unassignStep"]))
	{
		$reply["accepted"] = true;

		$stepUuid = "";
		$projectUuid = "";

		if (isset($_GET["stepUuid"])) $stepUuid = $_GET["stepUuid"];
		if (isset($_GET["projectUuid"])) $projectUuid = $_GET["projectUuid"];

		if (strlen($stepUuid) > 0 AND strlen($projectUuid) > 0)
		{
			if (isAdmin())
			{
				$q = "DELETE " . $tablePrefix . "projectstep FROM " . $tablePrefix . "projectstep WHERE
				stepId= ( SELECT " . $tablePrefix . "steps.id FROM " . $tablePrefix . "steps WHERE " . $tablePrefix . "steps.uuid = :stepUuid )
				AND
				projectId= ( SELECT " . $tablePrefix . "projects.id FROM " . $tablePrefix . "projects WHERE " . $tablePrefix . "projects.uuid = :projectUuid )
				;";
				$rep = $db->prepare($q);
				$rep->execute(array('stepUuid' => $stepUuid,'projectUuid' => $projectUuid));
				$rep->closeCursor();
	
				$reply["message"] = "Stage " . $stepUuid . " removed from project " . $projectUuid . ".";
				$reply["success"] = true;	
			}
			else 
			{
				$reply["message"] = "Insufficient rights, you need to be Admin to unassign steps from projects.";
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
