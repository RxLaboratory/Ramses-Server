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
		global $stepsTable, $assetgroupsTable, $db, $usersTable, $stepuserTable, $applicationsTable, $stepapplicationTable;

		$steps = array();
		//get steps
		$qString = "SELECT 
				{$stepsTable}.`uuid`,
				{$stepsTable}.`shortName`,
				{$stepsTable}.`comment`,
				{$stepsTable}.`name`,
				{$stepsTable}.`type`,
				{$stepsTable}.`id`,
				{$stepsTable}.`color`,
				{$stepsTable}.`estimationMethod`,
				{$stepsTable}.`estimationVeryEasy`,
				{$stepsTable}.`estimationEasy`,
				{$stepsTable}.`estimationMedium`,
				{$stepsTable}.`estimationHard`,
				{$stepsTable}.`estimationVeryHard`,
				{$assetgroupsTable}.`uuid` as multiplyGroupUuid,
				{$stepsTable}.`order`
			FROM {$stepsTable}
			LEFT JOIN {$assetgroupsTable} ON {$assetgroupsTable}.`id` = {$stepsTable}.`estimationMultiplyGroupId`
			WHERE {$stepsTable}.`projectId`= {$pid} AND {$stepsTable}.`removed` = 0 
			ORDER BY `order`, `shortName`, `name`;";

		$repSteps = $db->query( $qString );
		while ($s = $repSteps->fetch())
		{
			$step = array();
			$step['uuid'] = $s['uuid'];
			$step['shortName'] = $s['shortName'];
			$step['comment'] = $s['comment'];
			$step['name'] = $s['name'];
			$step['type'] = $s['type'];
			$step['order'] = (int)$s['order'];
			$step['color'] = $s['color'];
			$step['estimationMethod'] = $s['estimationMethod'];
			$step['estimationVeryEasy'] = (float)$s['estimationVeryEasy'];
			$step['estimationEasy'] = (float)$s['estimationEasy'];
			$step['estimationMedium'] = (float)$s['estimationMedium'];
			$step['estimationHard'] = (float)$s['estimationHard'];
			$step['estimationVeryHard'] = (float)$s['estimationVeryHard'];
			$step['multiplyGroupUuid'] = $s['multiplyGroupUuid'];
			$step['projectUuid'] = $puuid;

			//get applications
			$qString = "SELECT 
				{$applicationsTable}.`uuid`
			FROM {$stepapplicationTable}
			JOIN {$applicationsTable}
			ON {$stepapplicationTable}.`applicationId` = {$applicationsTable}.`id`
			WHERE {$stepapplicationTable}.`stepId` = " . $s['id'] . " AND {$applicationsTable}.`removed` = 0 
			ORDER BY {$applicationsTable}.`name`, {$applicationsTable}.`shortName`;";

			$repApplications = $db->query( $qString );
			while ($a = $repApplications->fetch())
			{
				$step['applications'][] = $a['uuid'];
			}

			$steps[] = $step;
		}
		return $steps;
	}

	function getProjectUsers( $projectId )
	{
		global $db, $usersTable, $projectuserTable;

		$users = array();

		//get users
		$qString = "SELECT 
			{$usersTable}.`uuid`
		FROM {$projectuserTable}
		JOIN {$usersTable}
		ON {$projectuserTable}.`userId` = {$usersTable}.`id`
		WHERE {$projectuserTable}.`projectId`= {$projectId} AND {$usersTable}.`removed` = 0 
		ORDER BY {$usersTable}.`name`, {$usersTable}.`shortName`;";

		$repUsers = $db->query( $qString );
		while ($u = $repUsers->fetch())
		{
			$users[] = $u['uuid'];
		}

		return $users;
	}

	function getPipes( $pid, $puuid )
	{
		global $pipesTable, $stepsTable, $db;

		$pipes = array();
		//get pipes
		$qString = "SELECT 
				pipes.`id`,
				pipes.`uuid`,
				inputSteps.`uuid` as inputStepUuid,
				outputSteps.`uuid` as outputStepUuid
			FROM {$pipesTable} AS pipes
			LEFT JOIN {$stepsTable} AS inputSteps
				ON pipes.inputStepId = inputSteps.id
			LEFT JOIN {$stepsTable} AS outputSteps
				ON pipes.outputStepId = outputSteps.id
			WHERE inputSteps.projectId=" . $pid . " AND pipes.removed = 0 ;";
		$repPipes = $db->query( $qString );

		while ($p = $repPipes->fetch())
		{
			$pipe = array();
			$pipe['uuid'] = $p['uuid'];
			$pipe['inputStepUuid'] = $p['inputStepUuid'];
			$pipe['outputStepUuid'] = $p['outputStepUuid'];
			$pipe['pipeFiles'] = getPipeFilesUuids( $p['id'] );
			$pipe['projectUuid'] = $puuid;

			$pipes[] = $pipe;
		}

		return $pipes;
	}

	function getPipeFilesUuids( $pipeId )
	{
		global $pipefilepipeTable, $pipefileTable, $db;

		$pipeFiles = array();
		// get
		$queryStr = "SELECT
			pipeFiles.`uuid`
		FROM {$pipefilepipeTable} AS pfp
			LEFT JOIN {$pipefileTable} AS pipeFiles
				ON pipeFiles.`id` = pfp.`pipeFileId`
			WHERE pfp.`pipeId` = {$pipeId} AND pipeFiles.`removed` = 0 ;" ;
		
		$repPipeFiles = $db->query( $queryStr );
		while ($p = $repPipeFiles->fetch())
		{
			$pipeFileUuid = $p['uuid'];
			$pipeFiles[] = $pipeFileUuid;
		}
		return $pipeFiles;
	}

	function getPipeFiles( $projectId, $projectUuid )
	{
		global $pipefilepipeTable, $pipefileTable, $colorspacesTable, $filetypesTable, $db;

		$pipeFiles = array();
		// get
		$queryStr = "SELECT
			pipeFiles.`uuid`,
			pipeFiles.`shortName`,
			pipeFiles.`comment`,
			fileTypes.`uuid` as fileTypeUuid,
			colorSpaces.`uuid` as colorSpaceUuid,
			customSettings
		FROM {$pipefileTable} AS pipeFiles
			LEFT JOIN {$colorspacesTable} AS colorSpaces
				ON pipeFiles.`colorSpaceId` = colorSpaces.`id`
			LEFT JOIN {$filetypesTable} AS fileTypes
				ON pipeFiles.`filetypeId` = fileTypes.`id`
			WHERE pipeFiles.`projectId` = {$projectId} AND pipeFiles.`removed` = 0 ;" ;
		
		$repPipeFiles = $db->query( $queryStr );
		while ($p = $repPipeFiles->fetch())
		{
			$pipeFile = array();
			$pipeFile['uuid'] = $p['uuid'];
			$pipeFile['shortName'] = $p['shortName'];
			$pipeFile['comment'] = $p['comment'];
			$pipeFile['fileTypeUuid'] = $p['fileTypeUuid'];
			$pipeFile['colorSpaceUuid'] = $p['colorSpaceUuid'];
			$pipeFile['customSettings'] = $p['customSettings'];
			$pipeFile['projectUuid'] = $projectUuid;
			$pipeFiles[] = $pipeFile;
		}
		return $pipeFiles;
	}

	function getAssetGroups( $pid, $puuid )
	{
		global $tablePrefix, $db;

		$assetGroups = array();

		$qString = "SELECT 
				" . $tablePrefix . "assetgroups.`uuid`,
				" . $tablePrefix . "assetgroups.`shortName`,
				" . $tablePrefix . "assetgroups.`comment`,
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
			$assetGroup['comment'] = $ag['comment'];
			$assetGroup['name'] = $ag['name'];
			$assetGroup['projectUuid'] = $puuid;

			$assetGroups[] = $assetGroup;
		}

		return $assetGroups;
	}

	function getAssets( $projectId )
	{
		global $assetsTable, $assetgroupsTable, $db;

		$assets = array();
		$qString = "SELECT
				{$assetsTable}.`id`,
				{$assetsTable}.`uuid`,
				{$assetsTable}.`name`,
				{$assetsTable}.`shortName`,
				{$assetsTable}.`comment`,
				{$assetsTable}.`tags`,
				{$assetsTable}.`id`,
				{$assetgroupsTable}.`uuid` AS assetGroupUuid
			FROM {$assetsTable}
			LEFT JOIN {$assetgroupsTable} ON {$assetgroupsTable}.`id` = {$assetsTable}.`assetGroupId`
			WHERE {$assetgroupsTable}.`projectId` = ". $projectId  ." AND {$assetsTable}.`removed` = 0
			ORDER BY `shortName`, `name`;";
		$repAssets = $db->query( $qString );
		while ($a = $repAssets->fetch())
		{
			$asset = array();
			$asset['uuid'] = $a['uuid'];
			$asset['shortName'] = $a['shortName'];
			$asset['comment'] = $a['comment'];
			$asset['name'] = $a['name'];
			$asset['tags'] = $a['tags'];
			$asset['assetGroupUuid'] = $a['assetGroupUuid'];
			$asset['statusHistory'] = getAssetStatusHistory( $a['id'], $asset['uuid'] );
			
			$assets[] = $asset;
		}

		return $assets;
	}

	function getAssetStatusHistory( $aid, $auuid )
	{
		global $db, $statusTable, $usersTable, $statesTable, $stepsTable;

		$statusHistory = array();
		$qString = "SELECT
				{$statusTable}.`uuid`,
				{$statusTable}.`completionRatio`,
				{$statusTable}.`comment`,
				{$statusTable}.`version`,
				{$statusTable}.`date`,
				{$statusTable}.`published`,
				{$statusTable}.`timeSpent`,
				{$statusTable}.`difficulty`,
				{$statusTable}.`estimation`,
				`users`.`uuid` as `userUuid`,
				`assignedUsers`.`uuid` as `assignedUserUuid`,
				{$statesTable}.`uuid` as `stateUuid`,
				{$stepsTable}.`uuid` as `stepUuid`
			FROM {$statusTable}
			LEFT JOIN {$usersTable} as `users` ON `users`.`id` = {$statusTable}.`userId`
			LEFT JOIN {$usersTable} as `assignedUsers` ON `assignedUsers`.`id` = {$statusTable}.`assignedUserId`
			JOIN {$statesTable} ON {$statesTable}.`id` = {$statusTable}.`stateId`
			JOIN {$stepsTable} ON {$stepsTable}.`id` = {$statusTable}.`stepId`
			WHERE {$statusTable}.`assetId` = '" . $aid . "' AND {$statusTable}.`removed` = 0
			ORDER BY `date`;";

		$repStatusHistory = $db->query( $qString );

		while ($s = $repStatusHistory->fetch())
		{
			$status = array();
			$status['uuid'] = $s['uuid'];
			$status['completionRatio'] = (int)$s['completionRatio'];
			$status['comment'] = $s['comment'];
			$status['version'] = (int)$s['version'];
			$status['date'] = $s['date'];
			$status['userUuid'] = $s['userUuid'];
			$status['stateUuid'] = $s['stateUuid'];
			$status['stepUuid'] = $s['stepUuid'];
			$status['assignedUserUuid'] = $s['assignedUserUuid'];
			$status['published'] = (int)$s['published'];
			$status['timeSpent'] = (int)$s['timeSpent'];
			$status['difficulty'] = $s['difficulty'];
			$status['estimation'] = (float)$s['estimation'];
			$status['assetUuid'] = $auuid;

			$statusHistory[] = $status;
		}

		return $statusHistory;
	}

	function getSequences($pid, $puuid)
	{
		global $sequencesTable, $db;

		$sequences = array();
		$qString = "SELECT 
					{$sequencesTable}.`uuid`,
					{$sequencesTable}.`shortName`,
					{$sequencesTable}.`comment`,
					{$sequencesTable}.`name`,
					{$sequencesTable}.`order`
				FROM {$sequencesTable}
				WHERE `projectId` = {$pid} AND `removed` = 0 
				ORDER BY {$sequencesTable}.`shortName`, {$sequencesTable}.`name`;";

		$repSequences = $db->query( $qString );
		while($s = $repSequences->fetch())
		{
			$sequence = array();

			$sequence['uuid'] = $s['uuid'];
			$sequence['shortName'] = $s['shortName'];
			$sequence['comment'] = $s['comment'];
			$sequence['name'] = $s['name'];
			$sequence['order'] = (int)$s['order'];
			$sequence['projectUuid'] = $puuid;

			$sequences[] = $sequence;
		}

		return $sequences;
	}

	function getShots( $projectId )
	{
		global $shotsTable, $sequencesTable, $db;

		$shots = array();

		$qString = "SELECT
				{$shotsTable}.`id`,
				{$shotsTable}.`uuid`,
				{$shotsTable}.`name`,
				{$shotsTable}.`shortName`,
				{$shotsTable}.`comment`,
				{$shotsTable}.`duration`,
				{$shotsTable}.`order`,
				{$sequencesTable}.`uuid` AS sequenceUuid
			FROM {$shotsTable}
			LEFT JOIN {$sequencesTable} ON {$sequencesTable}.`id` = {$shotsTable}.`sequenceId`
			WHERE {$sequencesTable}.`projectId` = ". $projectId  ." AND {$shotsTable}.`removed` = 0
			ORDER BY `order`, `shortName`, `name`;";

		$repShots = $db->query( $qString );

		while ($s = $repShots->fetch())
		{
			$shot = array();
			$shot['uuid'] = $s['uuid'];
			$shot['shortName'] = $s['shortName'];
			$shot['comment'] = $s['comment'];
			$shot['name'] = $s['name'];
			$shot['duration'] = (float)$s['duration'];
			$shot['order'] = (int)$s['order'];
			$shot['sequenceUuid'] = $s['sequenceUuid'];
			$shot['statusHistory'] = getShotStatusHistory( $s['id'], $shot['uuid'] );
			$shot['assetUuids'] = getShotAssets( $s['id'] );
			
			$shots[] = $shot;
		}

		$repShots->closeCursor();

		return $shots;
	}

	function getShotAssets( $shotId )
	{
		global $db, $shotassetTable, $assetsTable;

		$assets = array();
		$qString = "SELECT {$assetsTable}.`uuid`
			FROM {$shotassetTable}
			LEFT JOIN {$assetsTable} ON {$assetsTable}.`id` = {$shotassetTable}.`assetId`
			WHERE {$shotassetTable}.`shotId` = {$shotId} ;";

		$repAssets = $db->prepare( $qString );
		//$repAssets->debugDumpParams();
		$repAssets->execute();

		while ($a = $repAssets->fetch())
		{
			$assetUuid = $a['uuid'];
			$assets[] = $assetUuid;
		}

		$repAssets->closeCursor();

		return $assets;
	}

	function getShotStatusHistory( $sid, $suuid )
	{
		global $db, $statusTable, $usersTable, $statesTable, $stepsTable;

		$statusHistory = array();
		$qString = "SELECT
				{$statusTable}.`uuid`,
				{$statusTable}.`completionRatio`,
				{$statusTable}.`comment`,
				{$statusTable}.`version`,
				{$statusTable}.`date`,
				{$statusTable}.`published`,
				{$statusTable}.`timeSpent`,
				{$statusTable}.`difficulty`,
				{$statusTable}.`estimation`,
				users.`uuid` as `userUuid`,
				assignedUsers.`uuid` as `assignedUserUuid`,
				{$statesTable}.`uuid` as `stateUuid`,
				{$stepsTable}.`uuid` as `stepUuid`
			FROM {$statusTable}
			LEFT JOIN {$usersTable} as `users` ON `users`.`id` = {$statusTable}.`userId`
			LEFT JOIN {$usersTable} as `assignedUsers` ON `assignedUsers`.`id` = {$statusTable}.`assignedUserId`
			JOIN {$statesTable} ON {$statesTable}.`id` = {$statusTable}.`stateId`
			JOIN {$stepsTable} ON {$stepsTable}.`id` = {$statusTable}.`stepId`
			WHERE {$statusTable}.`shotId` = '" . $sid . "' AND {$statusTable}.`removed` = 0
			ORDER BY `date`;";

		$repStatusHistory = $db->query( $qString );

		while ($s = $repStatusHistory->fetch())
		{
			$status = array();
			$status['uuid'] = $s['uuid'];
			$status['completionRatio'] = (int)$s['completionRatio'];
			$status['comment'] = $s['comment'];
			$status['version'] = (int)$s['version'];
			$status['date'] = $s['date'];
			$status['userUuid'] = $s['userUuid'];
			$status['stateUuid'] = $s['stateUuid'];
			$status['stepUuid'] = $s['stepUuid'];
			$status['assignedUserUuid'] = $s['assignedUserUuid'];
			$status['published'] = (int)$s['published'];
			$status['timeSpent'] = (int)$s['timeSpent'];
			$status['difficulty'] = $s['difficulty'];
			$status['estimation'] = (float)$s['estimation'];
			$status['shotUuid'] = $suuid;

			$statusHistory[] = $status;
		}

		return $statusHistory;
	}

	function getSchedule( $projectId )
	{
		global $scheduleTable, $usersTable, $stepsTable, $db;

		$schedule = array();

		$qString = "SELECT
				{$scheduleTable}.`uuid`,
				{$scheduleTable}.`date`,
				{$scheduleTable}.`comment`,
				{$usersTable}.`uuid` AS userUuid,
				{$stepsTable}.`uuid` AS stepUuid
			FROM {$scheduleTable}
			LEFT JOIN {$stepsTable} ON {$stepsTable}.`id` = {$scheduleTable}.`stepId`
			LEFT JOIN {$usersTable} ON {$usersTable}.`id` = {$scheduleTable}.`userId`
			WHERE {$stepsTable}.`projectId` = {$projectId}
			ORDER BY `date`, `stepUuid`, `userUuid`;";
		
		$rep = $db->query($qString);
		//$rep->debugDumpParams();

		while ($s = $rep->fetch())
		{
			$entry['uuid'] = $s['uuid'];
			$entry['date'] = $s['date'];
			$entry['comment'] = $s['comment'];
			$entry['userUuid'] = $s['userUuid'];
			$entry['stepUuid'] = $s['stepUuid'];

			$schedule[] = $entry;
		}

		$rep->closeCursor();

		return $schedule;
	}

	function getProject( $sqlRep, $details=true )
	{
		global $tablePrefix, $db;

		$project = Array();
		$project['name'] = $sqlRep['name'];
		$project['shortName'] = $sqlRep['shortName'];
		$project['comment'] = $sqlRep['comment'];
		$project['folderPath'] = $sqlRep['folderPath'];
		$project['uuid'] = $sqlRep['uuid'];
		$project['framerate'] = (float)$sqlRep['framerate'];
		$project['width'] = (int)$sqlRep['width'];
		$project['height'] = (int)$sqlRep['height'];
		$project['aspectRatio'] = (float)$sqlRep['aspectRatio'];
		$project['deadline'] = $sqlRep['deadline'];
		$project['users'] = getProjectUsers( $sqlRep['id'] );

		if ($details) {
			$project['pipeFiles'] = getPipeFiles($sqlRep['id'], $sqlRep['uuid']);
			$project['steps'] = getSteps($sqlRep['id'], $sqlRep['uuid']);
			$project['pipes'] = getPipes($sqlRep['id'], $sqlRep['uuid']);
			$project['assetGroups'] = getAssetGroups($sqlRep['id'], $sqlRep['uuid']);
			$project['assets'] = getAssets($sqlRep['id']);
			$project['sequences'] = getSequences($sqlRep['id'], $sqlRep['uuid']);
			$project['shots'] = getShots($sqlRep['id']);
			$project['schedule'] = getSchedule($sqlRep['id']);
		} else {
			$project['pipeFiles'] = Array();
			$project['steps'] = Array();
			$project['pipes'] = Array();
			$project['assetGroups'] = Array();
			$project['assets'] = Array();
			$project['sequences'] = Array();
			$project['shots'] = Array();
			$project['schedule'] = Array();
		}

		return $project;
	}

	// ========= CREATE PROJECT ==========
	if (hasArg("createProject"))
	{
		$reply["accepted"] = true;
        $reply["query"] = "createProject";

		$name = "";
		$shortName = "";
		$uuid = "";

		$name = getArg("name");
        $shortName = getArg("shortName");
        $uuid = getArg("uuid");

		if (strlen($shortName) > 0)
		{
			// Only if admin
            if ( isAdmin() && validateName( $name ) && validateShortName( $shortName ) )
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
		}
		else
		{
			$reply["message"] = "Invalid request, missing values";
			$reply["success"] = false;
		}
	}

	// ========= GET PROJECTS ==========
	else if ( hasArg("getProjects") || hasArg("init") )
	{
		if (hasArg("getProjects")) {
			$reply["accepted"] = true;
			$reply["query"] = "getProjects";
		}
		

		$rep = $db->query("SELECT
				`name`,`shortName`,`uuid`,`folderPath`,`id`, `framerate`, `width`, `height`, `aspectRatio`, `comment`, `deadline`
			FROM {$projectsTable}
			WHERE `removed` = 0
			ORDER BY `shortName`,`name`;");

		//$rep->debugDumpParams();

		$projects = array();

		while ($p = $rep->fetch()) $projects[] = getProject( $p, false );

		$rep->closeCursor();

		if ( hasArg("getProjects") ) {
			$reply["content"] = $projects;
			$reply["message"] = "Projects list retrieved";
			$reply["success"] = true;
		} else {
			$reply["content"]["projects"] = $projects;
		}
	}

	// ========= GET SINGLE PROJECT ========
	else if (hasArg("getProject"))
	{
		$reply["accepted"] = true;
		$reply["query"] = "getProject";

		$uuid = getArg("uuid");

		if (checkArgs(array($uuid)))
		{
			$rep = $db->prepare("SELECT
					`name`,`shortName`,`uuid`,`folderPath`,`id`, `framerate`, `width`, `height`, `aspectRatio`, `comment`, `deadline`
				FROM {$projectsTable}
				WHERE `uuid` = :uuid ;");

			$rep->execute( array('uuid' => $uuid) );
			//$rep->debugDumpParams();

			$p = $rep->fetch();

			$reply["content"] = getProject( $p );
			$reply["message"] = "Project retrieved";
			$reply["success"] = true;

			$rep->closeCursor();
		}
	}

	// ========= UPDATE PROJECT ==========
	else if (hasArg("updateProject"))
	{
		$reply["accepted"] = true;
		$reply["query"] = "updateProject";

		$name = getArg( "name" );
		$shortName = getArg( "shortName" );
		$uuid = getArg( "uuid" );
		$folderPath = getArg( "folderPath" );
		$framerate = getArg( "framerate" );
		$width = getArg( "width" );
		$height = getArg( "height" );
		$aspectRatio = getArg( "aspectRatio" );
		$comment = getArg( "comment" );
		$deadline = getArg( "deadline" );

		if (strlen($shortName) > 0 AND strlen($uuid) > 0)
		{
			// Only if admin
            if ( isAdmin() && validateName( $name ) && validateShortName( $shortName ))
            {
				$qString = "UPDATE {$projectsTable}
					SET `name`= :name ,`shortName`= :shortName, `comment`= :comment";

				$values = array('name' => $name,'shortName' => $shortName, 'uuid' => $uuid, 'comment' => $comment );

				if (strlen($folderPath) > 0)
                {
                    $qString = $qString . ", `folderPath`= :folderPath";
                    $values["folderPath"] = $folderPath;
                }

				if (strlen($framerate) > 0)
                {
                    $qString = $qString . ", `framerate`= :framerate";
                    $values["framerate"] = $framerate;
                }

				if (strlen($width) > 0)
                {
                    $qString = $qString . ", `width`= :width";
                    $values["width"] = $width;
                }

				if (strlen($height) > 0)
                {
                    $qString = $qString . ", `height`= :height";
                    $values["height"] = $height;
                }

				if (strlen($aspectRatio) > 0)
                {
                    $qString = $qString . ", `aspectRatio`= :aspectRatio";
                    $values["aspectRatio"] = $aspectRatio;
                }

				if (strlen($deadline) > 0)
                {
                    $qString = $qString . ", `deadline`= :deadline";
                    $values["deadline"] = $deadline;
                }

				$qString = $qString . " WHERE uuid= :uuid ;";

                $rep = $db->prepare($qString);
                $rep->execute($values);
                $rep->closeCursor();

				$reply["message"] = "Project \"" . $shortName . "\" updated.";
				$reply["success"] = true;
			}
		}
		else
		{
			$reply["message"] = "Invalid request, missing values";
			$reply["success"] = false;
		}

	}

	// ========= REMOVE PROJECT ==========
	else if (hasArg("removeProject"))
	{
		$reply["accepted"] = true;
		$reply["query"] = "removeProject";

		$uuid = "";

		$uuid = getArg("uuid");

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
	else if (hasArg("assignStep"))
	{
		$reply["accepted"] = true;
		$reply["query"] = "assignStep";

		$stepUuid = "";
		$projectUuid = "";

		$stepUuid = getArg("stepUuid");
		$projectUuid = getArg("projectUuid");

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

	// ========= ASSIGN USER ==========
	else if (hasArg("assignUser"))
	{
		acceptReply( "assignUser" );

		$userUuid = getArg("userUuid");
		$projectUuid = getArg("projectUuid");

		if ( checkArgs(array($userUuid, $projectUuid)) && isProjectAdmin())
		{

			$qString = "INSERT INTO {$projectuserTable} (`projectId`, `userId`) VALUES (
				( SELECT {$projectsTable}.`id` FROM {$projectsTable} WHERE {$projectsTable}.`uuid` = :projectUuid ),
				( SELECT {$usersTable}.`id` FROM {$usersTable} WHERE {$usersTable}.`uuid` = :userUuid )
				) ON DUPLICATE KEY UPDATE {$projectuserTable}.`id` = {$projectuserTable}.`id` ;";

			$rep = $db->prepare($qString);
			$rep->bindValue(':userUuid', $userUuid, PDO::PARAM_STR);
			$rep->bindValue(':projectUuid', $projectUuid, PDO::PARAM_STR);

			sqlRequest( $rep, "User assigned to project." );

			$rep->closeCursor();
		}

	}

	// ========= REMOVE USER ==========
	else if (hasArg("unassignUser"))
	{
		acceptReply( "unassignUser" );

		$userUuid = getArg("userUuid");
		$projectUuid = getArg("projectUuid");

		if ( checkArgs(array($userUuid, $projectUuid)) && isProjectAdmin())
		{

			$q = "DELETE {$projectuserTable}  FROM {$projectuserTable} WHERE
			projectId= ( SELECT {$projectsTable}.`id` FROM {$projectsTable} WHERE {$projectsTable}.`uuid` = :projectUuid )
			AND
			userId= ( SELECT {$usersTable}.`id` FROM {$usersTable} WHERE {$usersTable}.`uuid` = :userUuid )
			;";
			$rep = $db->prepare($q);
			$rep->bindValue(':userUuid', $userUuid, PDO::PARAM_STR);
			$rep->bindValue(':projectUuid', $projectUuid, PDO::PARAM_STR);

			sqlRequest( $rep, "User unassigned from project." );

			$rep->closeCursor();
		}

	}
?>
