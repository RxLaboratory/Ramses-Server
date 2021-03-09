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


		$rep = $db->query("SELECT `name`,`shortName`,`uuid`,`folderPath`,`id` FROM " . $tablePrefix . "projects WHERE removed = 0 ORDER BY `shortName`,`name`;");

		$projects = array();

		while ($p = $rep->fetch())
		{
			$project = Array();
			$project['name'] = $p['name'];
			$project['shortName'] = $p['shortName'];
			$project['folderPath'] = $p['folderPath'];
			$project['uuid'] = $p['uuid'];

			$project['steps'] = array();
			$project['assetGroups'] = array();

			//get steps
			$qString = "SELECT 
						" . $tablePrefix . "steps.`uuid`,
						" . $tablePrefix . "steps.`shortName`,
						" . $tablePrefix . "steps.`name`,
						" . $tablePrefix . "steps.`type`,
						" . $tablePrefix . "steps.`id`,
						" . $tablePrefix . "steps.`order`
					FROM " . $tablePrefix . "steps
					WHERE projectId=" . $p['id'] . " AND removed = 0 
					ORDER BY `order`, `shortName`, `name`;";
			$repSteps = $db->query( $qString );
			while ($s = $repSteps->fetch())
			{
				$step = array();
				$step['uuid'] = $s['uuid'];
				$step['shortName'] = $s['shortName'];
				$step['name'] = $s['name'];
				$step['type'] = $s['type'];
				$step['order'] = (int) $s['order'];
				$step['projectUuid'] = $project['uuid'];
				
				$step['users'] = array();

				//get users
				$qString = "SELECT 
					" . $tablePrefix . "users.`uuid`
					FROM " . $tablePrefix . "stepuser
					JOIN " . $tablePrefix . "users
					ON " . $tablePrefix . "stepuser.`userId` = " . $tablePrefix . "users.`id`
					WHERE stepId=" . $s['id'] . " AND " . $tablePrefix . "users.`removed` = 0 
					ORDER BY " . $tablePrefix . "users.`name`, " . $tablePrefix . "users.`shortName`;";
				$repUsers = $db->query( $qString );
				while ($u = $repUsers->fetch())
				{
					$step['users'][] = $u['uuid'];
				}

				$project['steps'][] = $step;
			}

			//get asset groups
			$qString = "SELECT 
						" . $tablePrefix . "assetgroups.`id`,
						" . $tablePrefix . "assetgroups.`uuid`,
						" . $tablePrefix . "assetgroups.`shortName`,
						" . $tablePrefix . "assetgroups.`name`
					FROM " . $tablePrefix . "assetgroups
					WHERE projectId=" . $p['id'] . " AND removed = 0 
					ORDER BY " . $tablePrefix . "assetgroups.`shortName`, " . $tablePrefix . "assetgroups.`name`;";
			$repAssetGroups = $db->query( $qString );
			while($ag = $repAssetGroups->fetch())
			{
				$assetGroup = array();
				$assetGroup['uuid'] = $ag['uuid'];
				$assetGroup['shortName'] = $ag['shortName'];
				$assetGroup['name'] = $ag['name'];
				$assetGroup['projectUuid'] = $project['uuid'];

				$assetGroup['assets'] = array();

				//get assets
				$qString = "SELECT
						" . $tablePrefix . "assets.`uuid`,
						" . $tablePrefix . "assets.`name`,
						" . $tablePrefix . "assets.`shortName`,
						" . $tablePrefix . "assets.`tags`
					FROM " . $tablePrefix . "assets
					WHERE `assetGroupId`=" . $ag['id'] . " AND `removed` = 0
					ORDER BY `shortName`, `name`;";
				$repAssets = $db->query( $qString );
				while ($a = $repAssets->fetch())
				{
					$asset = array();
					$asset['uuid'] = $a['uuid'];
					$asset['shortName'] = $a['shortName'];
					$asset['name'] = $a['name'];
					$asset['tags'] = $a['tags'];
					$asset['assetGroup'] = $assetGroup['uuid'];
					
					$assetGroup['assets'][] = $a;
				}

				$project['assetGroups'][] = $assetGroup;
			}

			// TODO get shots
			/*$projectShots = Array();
			$repShots = $db->query("SELECT " . $tablePrefix . "shots.uuid as shotId FROM " . $tablePrefix . "projectshot JOIN " . $tablePrefix . "shots ON " . $tablePrefix . "shots.id = " . $tablePrefix . "projectshot.shotId WHERE projectId=" . $project['id'] . ";");
			while ($projectShot = $repShots->fetch())
			{
				$projectShots[] = $projectShot['shotId'];
			}
			$proj['assetGroups'] = $projectAssetGroups;*/


			$projects[] = $project;
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
				$qString = "UPDATE " . $tablePrefix . "projects SET name= :name ,shortName= :shortName";
				$values = array('name' => $name,'shortName' => $shortName, 'uuid' => $uuid);

				if (strlen($folderPath) > 0)
                {
                    $qString = $qString . ", folderPath= :folderPath";
                    $values["folderPath"] = $folderPath;
                }

				$qString = $qString . " WHERE uuid= :uuid ;";

                $rep = $db->prepare($qString);
                $rep->execute($values);
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
				$rep = $db->prepare("UPDATE " . $tablePrefix . "projects SET removed = 1 WHERE uuid= :uuid ;");
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

	// ========= ASSOCIATE STEP WITH PROJECT ==========
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
				$q = "INSERT INTO " . $tablePrefix . "steps (name,shortName,autoCreateAssets,type,projectId) VALUES (
				( SELECT " . $tablePrefix . "templatesteps.name FROM " . $tablePrefix . "templatesteps WHERE " . $tablePrefix . "templatesteps.uuid = :stepUuid ),
				( SELECT " . $tablePrefix . "templatesteps.shortName FROM " . $tablePrefix . "templatesteps WHERE " . $tablePrefix . "templatesteps.uuid = :stepUuid ),
				( SELECT " . $tablePrefix . "templatesteps.autoCreateAssets FROM " . $tablePrefix . "templatesteps WHERE " . $tablePrefix . "templatesteps.uuid = :stepUuid ),
				( SELECT " . $tablePrefix . "templatesteps.type FROM " . $tablePrefix . "templatesteps WHERE " . $tablePrefix . "templatesteps.uuid = :stepUuid ),
				( SELECT " . $tablePrefix . "projects.id FROM " . $tablePrefix . "projects WHERE " . $tablePrefix . "projects.uuid = :projectUuid )
				) ON DUPLICATE KEY UPDATE " . $tablePrefix . "steps.id = " . $tablePrefix . "steps.id ;";

				$rep = $db->prepare($q);
				$ok = $rep->execute(array('stepUuid' => $stepUuid,'projectUuid' => $projectUuid));
				$rep->closeCursor();

				if ($ok) $reply["message"] = "Step associated with project.";
				else $reply["message"] = $rep->errorInfo();

				$reply["success"] = $ok;
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
?>
