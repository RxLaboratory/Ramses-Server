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

	// ========= Functions ===============
	function getSteps( $pid, $puuid )
	{
		global $tablePrefix, $db;

		$steps = array();
		//get steps
		$qString = "SELECT 
				" . $tablePrefix . "steps.`uuid`,
				" . $tablePrefix . "steps.`shortName`,
				" . $tablePrefix . "steps.`name`,
				" . $tablePrefix . "steps.`type`,
				" . $tablePrefix . "steps.`id`,
				" . $tablePrefix . "steps.`order`
			FROM " . $tablePrefix . "steps
			WHERE projectId=" . $pid . " AND removed = 0 
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
			$step['projectUuid'] = $puuid;

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

			//get applications
			$qString = "SELECT 
				" . $tablePrefix . "applications.`uuid`
			FROM " . $tablePrefix . "stepapplication
			JOIN " . $tablePrefix . "applications
			ON " . $tablePrefix . "stepapplication.`applicationId` = " . $tablePrefix . "applications.`id`
			WHERE stepId=" . $s['id'] . " AND " . $tablePrefix . "applications.`removed` = 0 
			ORDER BY " . $tablePrefix . "applications.`name`, " . $tablePrefix . "applications.`shortName`;";
			$repApplications = $db->query( $qString );
			while ($a = $repApplications->fetch())
			{
				$step['applications'][] = $a['uuid'];
			}

			$steps[] = $step;
		}
		return $steps;
	}

	function getPipes( $pid, $puuid )
	{
		global $tablePrefix, $db;

		$pipes = array();
		//get pipes
		$qString = "SELECT 
				pipes.`uuid`,
				inputSteps.`uuid` as inputStepUuid,
				outputSteps.`uuid` as outputStepUuid,
				colorspaces.`uuid` as colorSpaceUuid,
				filetypes.`uuid` as filetypeUuid
			FROM " . $tablePrefix . "pipes AS pipes
			LEFT JOIN " . $tablePrefix . "steps AS inputSteps
				ON pipes.inputStepId = inputSteps.id
			LEFT JOIN " . $tablePrefix . "steps AS outputSteps
				ON pipes.outputStepId = outputSteps.id
			LEFT JOIN " . $tablePrefix . "colorspaces AS colorspaces
				ON pipes.colorSpaceId = colorspaces.id
			LEFT JOIN " . $tablePrefix . "filetypes AS filetypes
				ON pipes.filetypeId = filetypes.id
			WHERE inputSteps.projectId=" . $pid . " AND pipes.removed = 0 ;";
		$repPipes = $db->query( $qString );

		while ($p = $repPipes->fetch())
		{
			$pipe = array();
			$pipe['uuid'] = $p['uuid'];
			$pipe['inputStepUuid'] = $p['inputStepUuid'];
			$pipe['outputStepUuid'] = $p['outputStepUuid'];
			$pipe['colorSpaceUuid'] = $p['colorSpaceUuid'];
			$pipe['filetypeUuid'] = $p['filetypeUuid'];
			$pipe['projectUuid'] = $puuid;

			$pipes[] = $pipe;
		}

		return $pipes;
	}

	function getAssetGroups( $pid, $puuid )
	{
		global $tablePrefix, $db;

		$assetGroups = array();

		$qString = "SELECT 
				" . $tablePrefix . "assetgroups.`id`,
				" . $tablePrefix . "assetgroups.`uuid`,
				" . $tablePrefix . "assetgroups.`shortName`,
				" . $tablePrefix . "assetgroups.`name`
			FROM " . $tablePrefix . "assetgroups
			WHERE projectId=" . $pid . " AND removed = 0 
			ORDER BY " . $tablePrefix . "assetgroups.`shortName`, " . $tablePrefix . "assetgroups.`name`;";
		$repAssetGroups = $db->query( $qString );
		while($ag = $repAssetGroups->fetch())
		{
			$assetGroup = array();
			$assetGroup['uuid'] = $ag['uuid'];
			$assetGroup['shortName'] = $ag['shortName'];
			$assetGroup['name'] = $ag['name'];
			$assetGroup['projectUuid'] = $puuid;

			$assetGroup['assets'] = getAssets($ag['id'], $assetGroup['uuid'] );

			$assetGroups[] = $assetGroup;
		}

		return $assetGroups;
	}

	function getAssets( $aid, $auuid )
	{
		global $tablePrefix, $db;

		$assets = array();
		$qString = "SELECT
				" . $tablePrefix . "assets.`uuid`,
				" . $tablePrefix . "assets.`name`,
				" . $tablePrefix . "assets.`shortName`,
				" . $tablePrefix . "assets.`tags`
			FROM " . $tablePrefix . "assets
			WHERE `assetGroupId`=" . $aid . " AND `removed` = 0
			ORDER BY `shortName`, `name`;";
		$repAssets = $db->query( $qString );
		while ($a = $repAssets->fetch())
		{
			$asset = array();
			$asset['uuid'] = $a['uuid'];
			$asset['shortName'] = $a['shortName'];
			$asset['name'] = $a['name'];
			$asset['tags'] = $a['tags'];
			$asset['assetGroupUuid'] = $auuid;
			
			$assets[] = $asset;
		}

		return $assets;
	}

	function getSequences($pid, $puuid)
	{
		global $tablePrefix, $db;

		$sequences = array();
		$qString = "SELECT 
					" . $tablePrefix . "sequences.`id`,
					" . $tablePrefix . "sequences.`uuid`,
					" . $tablePrefix . "sequences.`shortName`,
					" . $tablePrefix . "sequences.`name`
				FROM " . $tablePrefix . "sequences
				WHERE projectId=" . $pid . " AND removed = 0 
				ORDER BY " . $tablePrefix . "sequences.`shortName`, " . $tablePrefix . "sequences.`name`;";
		$repSequences = $db->query( $qString );
		while($s = $repSequences->fetch())
		{
			$sequence = array();

			$sequence['uuid'] = $s['uuid'];
			$sequence['shortName'] = $s['shortName'];
			$sequence['name'] = $s['name'];
			$sequence['projectUuid'] = $puuid;

			$sequence['shots'] = getShots($s['id'], $sequence['uuid']);

			$sequences[] = $sequence;
		}

		return $sequences;
	}

	function getShots( $sid, $suuid )
	{
		global $tablePrefix, $db;

		$shotsTable = $tablePrefix . "shots";

		$shots = array();

		$qString = "SELECT
				{$shotsTable}.`uuid`,
				{$shotsTable}.`name`,
				{$shotsTable}.`shortName`,
				{$shotsTable}.`duration`,
				{$shotsTable}.`order`
			FROM {$shotsTable}
			WHERE `sequenceId` = " . $sid . " AND `removed` = 0
			ORDER BY `sequenceId`, `order`, `shortName`;";

		$repShots = $db->query( $qString );

		while ($s = $repShots->fetch())
		{
			$shot = array();
			$shot['uuid'] = $s['uuid'];
			$shot['shortName'] = $s['shortName'];
			$shot['name'] = $s['name'];
			$shot['duration'] = (float)$s['duration'];
			$shot['order'] = (int)$s['order'];
			$shot['sequenceUuid'] = $suuid;
			
			$shots[] = $shot;
		}

		$repShots->closeCursor();

		return $shots;
	}

	function getProject( $sqlRep )
	{
		global $tablePrefix, $db;

		$project = Array();
		$project['name'] = $sqlRep['name'];
		$project['shortName'] = $sqlRep['shortName'];
		$project['folderPath'] = $sqlRep['folderPath'];
		$project['uuid'] = $sqlRep['uuid'];

		$project['steps'] = getSteps($sqlRep['id'], $sqlRep['uuid']);
		$project['pipes'] = getPipes($sqlRep['id'], $sqlRep['uuid']);
		$project['assetGroups'] = getAssetGroups($sqlRep['id'], $sqlRep['uuid']);
		$project['sequences'] = getSequences($sqlRep['id'], $sqlRep['uuid']);

		return $project;
	}

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
	else if (isset($_GET["getProjects"]))
	{
		$reply["accepted"] = true;
		$reply["query"] = "getProjects";

		$rep = $db->query("SELECT `name`,`shortName`,`uuid`,`folderPath`,`id` FROM " . $tablePrefix . "projects WHERE removed = 0 ORDER BY `shortName`,`name`;");

		$projects = array();
		while ($p = $rep->fetch()) $projects[] = getProject( $p );

		$rep->closeCursor();

		$reply["content"] = $projects;
		$reply["message"] = "Projects list retrieved";
		$reply["success"] = true;
	}

	// ========= GET SINGLE PROJECT ========
	else if (isset($_GET["getProject"]))
	{
		$reply["accepted"] = true;
		$reply["query"] = "getProject";

		$uuid = $_GET["uuid"] ?? "";
	
		$rep = $db->prepare("SELECT `name`,`shortName`,`uuid`,`folderPath`,`id` FROM " . $tablePrefix . "projects WHERE `uuid` = :uuid ;");
		$rep->execute( array('uuid' => $uuid) );
		$p = $rep->fetch();
		$rep->closeCursor();

		$reply["content"] = getProject( $p );
		$reply["message"] = "Project retrieved";
		$reply["success"] = true;
	}

	// ========= UPDATE PROJECT ==========
	else if (isset($_GET["updateProject"]))
	{
		$reply["accepted"] = true;
		$reply["query"] = "updateProject";

		$name = $_GET["name"] ?? "";
		$shortName = $_GET["shortName"] ?? "";
		$uuid = $_GET["uuid"] ?? "";
		$folderPath = $_GET["folderPath"] ?? "";

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
