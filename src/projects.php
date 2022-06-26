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
		global $tablePrefix;

		$q = new DBQuery();
		$q->prepare( "SELECT 
			steps.`uuid`,
			steps.`shortName`,
			steps.`name`,
			steps.`type`,
			steps.`id`,
			steps.`color`,
			steps.`estimationMethod`,
			steps.`estimationVeryEasy`,
			steps.`estimationEasy`,
			steps.`estimationMedium`,
			steps.`estimationHard`,
			steps.`estimationVeryHard`,
			steps.`estimationMultiplyGroupId`,
			steps.`order`,
			steps.`latestUpdate`,
			steps.`removed`,
			steps.`comment`,
			steps.`publishSettings`
			FROM {$tablePrefix}steps as steps
			WHERE steps.projectId = {$pid}
				AND steps.`removed` = 0
			ORDER BY steps.`order`, steps.`shortName`, steps.`name`
		;");

		$q->execute();

		$steps = array();
		
		while ($s = $q->fetch())
		{
			$qa = new DBQuery();

			$step = array();
			$step['uuid'] = $s['uuid'];
			$step['shortName'] = $s['shortName'];
			$step['name'] = $s['name'];
			$step['comment'] = $s['comment'];
			$step['publishSettings'] = $s['publishSettings'];
			$step['type'] = $s['type'];
			$step['color'] = $s['color'];
			$step['order'] = (int)$s['order'];
			$step['latestUpdate'] = $s['latestUpdate'];
			$step['removed'] = (int)$s['removed'];
			$step['estimationMethod'] = $s['estimationMethod'];
			$step['estimationVeryEasy'] = (float)$s['estimationVeryEasy'];
			$step['estimationEasy'] = (float)$s['estimationEasy'];
			$step['estimationMedium'] = (float)$s['estimationMedium'];
			$step['estimationHard'] = (float)$s['estimationHard'];
			$step['estimationVeryHard'] = (float)$s['estimationVeryHard'];
			$step['multiplyGroupUuid'] = $qa->uuid( "assetgroups", $s['estimationMultiplyGroupId'] );
			$step['projectUuid'] = $puuid;

			//get applications
			
			$qa->prepare( "SELECT {$tablePrefix}applications.`uuid`
				FROM {$tablePrefix}stepapplication
				JOIN {$tablePrefix}applications
					ON {$tablePrefix}stepapplication.`applicationId` = {$tablePrefix}applications.`id`
				WHERE {$tablePrefix}stepapplication.`stepId` = " . $s['id'] .
					" AND {$tablePrefix}applications.`removed` = 0
					AND {$tablePrefix}stepapplication.`removed` = 0
				ORDER BY {$tablePrefix}applications.`name`, {$tablePrefix}applications.`shortName`;"
			);
			$qa->execute();

			while ($a = $qa->fetch())
			{
				$step['applications'][] = $a['uuid'];
			}

			$qa->close();

			$steps[] = $step;
		}

		$q->close();
		return $steps;
	}

	function getProjectUsers( $projectId )
	{
		global $tablePrefix;

		$q = new DBQuery();
		$q->prepare( "SELECT {$tablePrefix}users.`uuid`
			FROM {$tablePrefix}projectuser
			JOIN {$tablePrefix}users
				ON {$tablePrefix}projectuser.`userId` = {$tablePrefix}users.`id`
			WHERE {$tablePrefix}projectuser.`projectId`= {$projectId}
				AND {$tablePrefix}users.`removed` = 0
				AND {$tablePrefix}projectuser.`removed` = 0
			ORDER BY {$tablePrefix}users.`name`, {$tablePrefix}users.`shortName`;"
		);
		$q->execute();

		$users = array();

		while ($u = $q->fetch())
		{
			$users[] = $u['uuid'];
		}

		return $users;
	}

	function getPipes( $pid, $puuid )
	{
		global $tablePrefix;

		$q = new DBQuery();
		$q->prepare( "SELECT 
				pipes.`id`,
				pipes.`uuid`,
				inputSteps.`uuid` as inputStepUuid,
				outputSteps.`uuid` as outputStepUuid,
				pipes.`removed`,
				pipes.`latestUpdate`
			FROM {$tablePrefix}pipes AS pipes
			JOIN {$tablePrefix}steps AS inputSteps
				ON pipes.inputStepId = inputSteps.id
			JOIN {$tablePrefix}steps AS outputSteps
				ON pipes.outputStepId = outputSteps.id
			WHERE inputSteps.projectId = {$pid} AND pipes.removed = 0 ;"
		);
		$q->execute();

		$pipes = array();
		while ($p = $q->fetch())
		{
			$pipe = array();
			$pipe['uuid'] = $p['uuid'];
			$pipe['inputStepUuid'] = $p['inputStepUuid'];
			$pipe['outputStepUuid'] = $p['outputStepUuid'];
			$pipe['removed'] = (int)$p['removed'];
			$pipe['latestUpdate'] = $p['latestUpdate'];
			$pipe['pipeFiles'] = getPipeFilesUuids( $p['id'] );
			$pipe['projectUuid'] = $puuid;

			$pipes[] = $pipe;
		}
		$q->close();

		return $pipes;
	}

	function getPipeFilesUuids( $pipeId )
	{
		global $tablePrefix;

		$q = new DBQuery();
		$q->prepare( "SELECT pipeFiles.`uuid`
			FROM {$tablePrefix}pipefilepipe AS pfp
			LEFT JOIN {$tablePrefix}pipefile AS pipeFiles
				ON pipeFiles.`id` = pfp.`pipeFileId`
			WHERE pfp.`pipeId` = {$pipeId}
				AND pipeFiles.`removed` = 0
				AND pfp.`removed` = 0 ;"
		);
		$q->execute();

		$pipeFiles = array();
		while ($p = $q->fetch())
		{
			$pipeFileUuid = $p['uuid'];
			$pipeFiles[] = $pipeFileUuid;
		}
		$q->close();

		return $pipeFiles;
	}

	function getPipeFiles( $projectId, $projectUuid )
	{
		global $tablePrefix;

		$q = new DBQuery();
		$q->prepare( "SELECT
				pipeFiles.`uuid`,
				pipeFiles.`shortName`,
				pipeFiles.`comment`,
				fileTypes.`uuid` as fileTypeUuid,
				colorSpaces.`uuid` as colorSpaceUuid,
				pipeFiles.`customSettings`,
				pipeFiles.`removed`,
				pipeFiles.`latestUpdate`
			FROM {$tablePrefix}pipefile AS pipeFiles
				LEFT JOIN {$tablePrefix}colorspaces AS colorSpaces
					ON pipeFiles.`colorSpaceId` = colorSpaces.`id`
				LEFT JOIN {$tablePrefix}filetypes AS fileTypes
					ON pipeFiles.`filetypeId` = fileTypes.`id`
				WHERE pipeFiles.`projectId` = {$projectId} AND pipeFiles.`removed` = 0 ;"
		);
		$q->execute();

		$pipeFiles = array();
		while ($p = $q->fetch())
		{
			$pipeFile = array();
			$pipeFile['uuid'] = $p['uuid'];
			$pipeFile['shortName'] = $p['shortName'];
			$pipeFile['comment'] = $p['comment'];
			$pipeFile['fileTypeUuid'] = $p['fileTypeUuid'];
			$pipeFile['colorSpaceUuid'] = $p['colorSpaceUuid'];
			$pipeFile['customSettings'] = $p['customSettings'];
			$pipeFile['removed'] = (int)$p['removed'];
			$pipeFile['latestUpdate'] = $p['latestUpdate'];
			$pipeFile['projectUuid'] = $projectUuid;
			$pipeFiles[] = $pipeFile;
		}
		return $pipeFiles;
	}

	function getAssetGroups( $pid, $puuid )
	{
		global $tablePrefix;

		$q = new DBQuery();
		$q->prepare("SELECT 
				`uuid`,
				`shortName`,
				`comment`,
				`name`,
				`removed`,
				`latestUpdate`
			FROM {$tablePrefix}assetgroups
			WHERE `projectId`= {$pid} AND `removed` = 0 
			ORDER BY `shortName`, `name`;"
		);
		$q->execute();

		$assetGroups = array();
		while($ag = $q->fetch())
		{
			$assetGroup = array();
			$assetGroup['uuid'] = $ag['uuid'];
			$assetGroup['shortName'] = $ag['shortName'];
			$assetGroup['comment'] = $ag['comment'];
			$assetGroup['name'] = $ag['name'];
			$assetGroup['removed'] = (int)$ag['removed'];
			$assetGroup['latestUpdate'] = $ag['latestUpdate'];
			$assetGroup['projectUuid'] = $puuid;

			$assetGroups[] = $assetGroup;
		}
		$q->close();

		return $assetGroups;
	}

	function getAssets( $projectId )
	{
		global $tablePrefix;

		$q = new DBQuery();
		$q->prepare( "SELECT
				assets.`id`,
				assets.`uuid`,
				assets.`name`,
				assets.`shortName`,
				assets.`comment`,
				assets.`tags`,
				assets.`id`,
				assets.`removed`,
				assets.`latestUpdate`,
				assetgroups.`uuid` AS assetGroupUuid
			FROM {$tablePrefix}assets AS assets
			JOIN {$tablePrefix}assetgroups AS assetgroups
				ON assetgroups.`id` = assets.`assetGroupId`
			WHERE assetgroups.`projectId` = {$projectId} AND assets.`removed` = 0
			ORDER BY assets.`shortName`, assets.`name`;"
		);
		$q->execute();

		$assets = array();
		while ($a = $q->fetch())
		{
			$asset = array();
			$asset['uuid'] = $a['uuid'];
			$asset['shortName'] = $a['shortName'];
			$asset['comment'] = $a['comment'];
			$asset['name'] = $a['name'];
			$asset['tags'] = $a['tags'];
			$asset['assetGroupUuid'] = $a['assetGroupUuid'];
			$asset['removed'] = (int)$a['removed'];
			$asset['latestUpdate'] = $a['latestUpdate'];
			$asset['statusHistory'] = getAssetStatusHistory( $a['id'], $asset['uuid'] );
			
			$assets[] = $asset;
		}

		$q->close();

		return $assets;
	}

	function getAssetStatusHistory( $aid, $auuid )
	{
		global $tablePrefix;

		$q = new DBQuery();
		$q->prepare( "SELECT
				stats.`uuid`,
				stats.`completionRatio`,
				stats.`comment`,
				stats.`version`,
				stats.`date`,
				stats.`published`,
				stats.`timeSpent`,
				stats.`difficulty`,
				stats.`estimation`,
				stats.`removed`,
				stats.`latestUpdate`,
				`users`.`uuid` as `userUuid`,
				stats.`assignedUserId`,
				states.`uuid` as `stateUuid`,
				steps.`uuid` as `stepUuid`
			FROM {$tablePrefix}status as stats
			JOIN {$tablePrefix}users as `users`
				ON `users`.`id` = stats.`userId`
			JOIN {$tablePrefix}states as states
				ON states.`id` = stats.`stateId`
			JOIN {$tablePrefix}steps as steps
				ON steps.`id` = stats.`stepId`
			WHERE stats.`assetId` = {$aid}
				AND stats.`removed` = 0
				AND steps.`removed` = 0
			ORDER BY `date`;"
		);
		$q->execute();

		$statusHistory = array();
		while ($s = $q->fetch())
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
			$qa = new DBQuery();
			$status['assignedUserUuid'] = $qa->uuid("users", $s['assignedUserId']);
			$status['published'] = (int)$s['published'];
			$status['timeSpent'] = (int)$s['timeSpent'];
			$status['difficulty'] = $s['difficulty'];
			$status['estimation'] = (float)$s['estimation'];
			$status['removed'] = (int)$s['removed'];
			$status['latestUpdate'] = $s['latestUpdate'];
			$status['assetUuid'] = $auuid;

			$statusHistory[] = $status;
		}

		$q->close();

		return $statusHistory;
	}

	function getSequences($pid, $puuid)
	{
		global $tablePrefix;

		$q = new DBQuery();
		$q->prepare("SELECT 
				`uuid`,
				`shortName`,
				`comment`,
				`name`,
				`order`,
				`removed`,
				`latestUpdate`,
				`color`
			FROM {$tablePrefix}sequences
			WHERE `projectId`= {$pid} AND `removed` = 0 
			ORDER BY `shortName`, `name`;"
		);
		$q->execute();

		$sequences = array();
		while($s = $q->fetch())
		{
			$sequence = array();

			$sequence['uuid'] = $s['uuid'];
			$sequence['shortName'] = $s['shortName'];
			$sequence['comment'] = $s['comment'];
			$sequence['name'] = $s['name'];
			$sequence['order'] = (int)$s['order'];
			$sequence['removed'] = (int)$s['removed'];
			$sequence['latestUpdate'] = $s['latestUpdate'];
			$sequence['projectUuid'] = $puuid;
			$sequence['color'] = $s['color'];

			$sequences[] = $sequence;
		}

		return $sequences;
	}

	function getShots( $projectId )
	{
		global $tablePrefix;

		$q = new DBQuery();
		$q->prepare( "SELECT
				shots.`id`,
				shots.`uuid`,
				shots.`name`,
				shots.`shortName`,
				shots.`comment`,
				shots.`duration`,
				shots.`order`,
				shots.`removed`,
				shots.`latestUpdate`,
				sequences.`uuid` AS sequenceUuid
			FROM {$tablePrefix}shots as shots
			JOIN {$tablePrefix}sequences as sequences
				ON sequences.`id` = shots.`sequenceId`
			WHERE sequences.`projectId` = {$projectId} AND shots.`removed` = 0
			ORDER BY shots.`order`, shots.`shortName`, shots.`name`;"
		);

		$q->execute();
		$shots = array();
		while ($s = $q->fetch())
		{
			$shot = array();
			$shot['uuid'] = $s['uuid'];
			$shot['shortName'] = $s['shortName'];
			$shot['comment'] = $s['comment'];
			$shot['name'] = $s['name'];
			$shot['duration'] = (float)$s['duration'];
			$shot['order'] = (int)$s['order'];
			$shot['sequenceUuid'] = $s['sequenceUuid'];
			$shot['removed'] = (int)$s['removed'];
			$shot['latestUpdate'] = $s['latestUpdate'];
			$shot['statusHistory'] = getShotStatusHistory( $s['id'], $shot['uuid'] );
			$shot['assetUuids'] = getShotAssets( $s['id'] );
			
			$shots[] = $shot;
		}

		$q->close();

		return $shots;
	}

	function getShotAssets( $shotId )
	{
		global $tablePrefix;
		$q = new DBQuery();
		$q->prepare("SELECT assets.`uuid`
			FROM {$tablePrefix}shotasset as shotasset
			JOIN {$tablePrefix}assets as assets
				ON assets.`id` = shotasset.`assetId`
			WHERE shotasset.`shotId` = {$shotId} AND shotasset.`removed` = 0;"
		);
		$q->execute();

		$assets = array();
		while ($a = $q->fetch())
		{
			$assetUuid = $a['uuid'];
			$assets[] = $assetUuid;
		}

		$q->close();

		return $assets;
	}

	function getShotStatusHistory( $sid, $suuid )
	{
		global $tablePrefix;

		$q = new DBQuery();
		$q->prepare( "SELECT
				stats.`uuid`,
				stats.`completionRatio`,
				stats.`comment`,
				stats.`version`,
				stats.`date`,
				stats.`published`,
				stats.`timeSpent`,
				stats.`difficulty`,
				stats.`estimation`,
				stats.`removed`,
				stats.`latestUpdate`,
				`users`.`uuid` as `userUuid`,
				stats.assignedUserId,
				states.`uuid` as `stateUuid`,
				steps.`uuid` as `stepUuid`
			FROM {$tablePrefix}status as stats
			JOIN {$tablePrefix}users as `users`
				ON `users`.`id` = stats.`userId`
			JOIN {$tablePrefix}states as states
				ON states.`id` = stats.`stateId`
			JOIN {$tablePrefix}steps as steps
				ON steps.`id` = stats.`stepId`
			WHERE stats.`shotId` = {$sid}
				AND stats.`removed` = 0
				AND steps.`removed` = 0
			ORDER BY `date`;"
		);
		$q->execute();

		$statusHistory = array();
		while ($s = $q->fetch())
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
			$qa = new DBQuery();
			$status['assignedUserUuid'] = $qa->uuid("users", $s['assignedUserId']);
			$status['published'] = (int)$s['published'];
			$status['timeSpent'] = (int)$s['timeSpent'];
			$status['difficulty'] = $s['difficulty'];
			$status['estimation'] = (float)$s['estimation'];
			$status['removed'] = (int)$s['removed'];
			$status['latestUpdate'] = $s['latestUpdate'];
			$status['shotUuid'] = $suuid;

			$statusHistory[] = $status;
		}

		$q->close();

		return $statusHistory;
	}

	function getSchedule( $projectId )
	{
		global $tablePrefix;

		$q = new DBQuery();
		$q->prepare( "SELECT
				schedule.`uuid`,
				schedule.`date`,
				schedule.`comment`,
				schedule.`removed`,
				schedule.`latestUpdate`,
				users.`uuid` AS userUuid,
				steps.`uuid` AS stepUuid
			FROM {$tablePrefix}schedule as schedule
			JOIN {$tablePrefix}steps as steps
				ON steps.`id` = schedule.`stepId`
			JOIN {$tablePrefix}users as users
				ON users.`id` = schedule.`userId`
			WHERE steps.`projectId` = {$projectId}
				AND schedule.`removed` = 0
				AND steps.`removed` = 0
				AND users.`removed` = 0
			ORDER BY `date`, `stepUuid`, `userUuid`;"
		);
		$q->execute();
		$schedule = array();
		
		while ($s = $q->fetch())
		{
			$entry['uuid'] = $s['uuid'];
			$entry['date'] = $s['date'];
			$entry['comment'] = $s['comment'];
			$entry['userUuid'] = $s['userUuid'];
			$entry['stepUuid'] = $s['stepUuid'];
			$entry['removed'] = (int)$s['removed'];
			$entry['latestUpdate'] = $s['latestUpdate'];

			$schedule[] = $entry;
		}

		$q->close();

		return $schedule;
	}

	function getScheduleComments( $projectId, $projectUuid )
	{
		global $tablePrefix;

		$q = new DBQuery();
		$q->prepare( "SELECT
			comments.`uuid`,
			comments.`date`,
			comments.`comment`,
			comments.`color`,
			comments.`removed`,
			comments.`latestUpdate`
			FROM {$tablePrefix}schedulecomments as comments
			WHERE comments.`projectId` = {$projectId} AND comments.`removed` = 0
			ORDER BY `date`;"
		);

		$q->execute();
		$comments = array();

		while ($c = $q->fetch())
		{
			$comment['uuid'] = $c['uuid'];
			$comment['date'] = $c['date'];
			$comment['comment'] = $c['comment'];
			$comment['color'] = $c['color'];
			$comment['projectUuid'] = $projectUuid;
			$comment['removed'] = (int)$c['removed'];
			$comment['latestUpdate'] = $c['latestUpdate'];

			$comments[] = $comment;
		}

		$q->close();

		return $comments;
	}

	function getProject( $project, $details=true )
	{
		// Adjust project values
		$project['framerate'] = (float)$project['framerate'];
		$project['aspectRatio'] = (float)$project['aspectRatio'];
		$project['width'] = (float)$project['width'];
		$project['height'] = (float)$project['height'];

		$project['users'] = getProjectUsers( $project['id'] );

		if ($details) {
			$project['pipeFiles'] = getPipeFiles($project['id'], $project['uuid']);
			$project['steps'] = getSteps($project['id'], $project['uuid']);
			$project['pipes'] = getPipes($project['id'], $project['uuid']);
			$project['assetGroups'] = getAssetGroups($project['id'], $project['uuid']);
			$project['assets'] = getAssets($project['id']);
			$project['sequences'] = getSequences($project['id'], $project['uuid']);
			$project['shots'] = getShots($project['id']);
			$project['schedule'] = getSchedule($project['id']);
			$project['scheduleComments'] = getScheduleComments($project['id'], $project['uuid']);
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
	if ( acceptReply("createProject", 'admin') )
	{
		$name = getArg("name");
		$shortName = getArg("shortName");
		$uuid = getArg("uuid", uuid());

		$q = new DBQuery();
		$q->insert( "projects", array( 'name', 'shortName', 'uuid' ));

		$q->bindName( $name );
		$q->bindShortName( $shortName );
		$q->bindStr( "uuid", $uuid, true );

		$q->execute("Project '{$shortName}' added.");
		$q->close();
	}

	// ========= GET PROJECTS ==========
	else if ( acceptReply("getProjects") || hasArg("init") )
	{
		$q = new DBQuery();
        $projects = $q->getAll("projects",
			array(
				'name',
				'shortName',
				'uuid',
				'folderPath',
				'id',
				'framerate',
				'width',
				'height',
				'aspectRatio',
				'comment',
				'deadline'
			),
			array(
				'shortName',
				'name'
			)
		);

		for ($p = 0; $p < count($projects); $p++)
		{
			$projects[$p] = getProject( $projects[$p], false );
		}
		
		if (hasArg("init") )
        {
            $reply["content"]["projects"] = $projects;
        }
        else 
        {
            $reply["content"] = $projects;
            $reply["message"] = "Project list retreived";
            $reply["success"] = true;
        }
	}

	// ========= GET SINGLE PROJECT ========
	else if ( acceptReply("getProject") )
	{
		$uuid = getArg("uuid");

		$q = new DBQuery();
        $project = $q->get(
			"projects",
			array(
				'name',
				'shortName',
				'uuid',
				'folderPath',
				'id',
				'framerate',
				'width',
				'height',
				'aspectRatio',
				'comment',
				'deadline'
			),
			$uuid
		);

		$reply["content"] = getProject( $project );
		$reply["message"] = "Project retrieved";
		$reply["success"] = true;
	}

	// ========= UPDATE PROJECT ==========
	else if (acceptReply("updateProject", 'admin'))
	{
		$name = getArg( "name" );
		$shortName = getArg( "shortName" );
		$uuid = getArg( "uuid" );
		$folderPath = getArg( "folderPath" );
		$framerate = getArg( "framerate" );
		$width = getArg( "width" );
		$height = getArg( "height" );
		$aspectRatio = getArg( "aspectRatio", (int)$width / (int)$height );
		$comment = getArg( "comment" );
		$deadline = getArg( "deadline" );

		$q = new DBQuery();
		$q->update(
			"projects",
			array(
				'name',
				'shortName',
				'folderPath',
				'framerate',
				'width',
				'height',
				'aspectRatio',
				'comment',
				'deadline'
			),
			$uuid
		);

		$q->bindName( $name );
		$q->bindShortName( $shortName );
		$q->bindStr( "comment", $comment );
		$q->bindStr( "folderPath", $folderPath );
		$q->bindFloat( "framerate", $framerate, 4 );
		$q->bindInt( "width", $width );
		$q->bindInt( "height", $height );
		$q->bindFloat( "aspectRatio", $aspectRatio );
		$q->bindStr( "deadline", $deadline );

		$q->execute("Project \"{$shortName}\" updated.");
		$q->close();
	}

	// ========= REMOVE PROJECT ==========
	else if (acceptReply("removeProject", 'admin'))
	{
		$uuid = getArg("uuid");
		$q = new DBQuery();
		$q->remove( "projects", $uuid );
	}

	// ========= ASSOCIATE STEP WITH PROJECT ==========
	else if ( acceptReply("assignStep", 'projectAdmin') )
	{
		$stepUuid = getArg("stepUuid");
		$projectUuid = getArg("projectUuid");

		$q = new DBQuery();
		$stepInfo = $q->get(
			"templatesteps",
			array(
				'name',
				'shortName',
				'type',
				'comment',
				'color',
				'estimationMethod',
				'estimationVeryEasy',
				'estimationEasy',
				'estimationMedium',
				'estimationHard',
				'estimationVeryHard',
				'autoCreateAssets'
			),
			$stepUuid
		);
		$projectId = $q->id("projects", $projectUuid);

		$q->insert(
			"steps",
			array(
				'name',
				'shortName',
				'type',
				'comment',
				'color',
				'estimationMethod',
				'estimationVeryEasy',
				'estimationEasy',
				'estimationMedium',
				'estimationHard',
				'estimationVeryHard',
				'autoCreateAssets',
				'projectId'
			)
		);

		$q.bindName( $stepInfo['name'] );
		$q.bindShortName( $stepInfo['shortName'] );
		$q.bindInt( 'autoCreateAssets', $stepInfo['autoCreateAssets'] );
		$q.bindStr( 'type', $stepInfo['type'] );
		$q.bindStr( 'comment', $stepInfo['comment'] );
		$q.bindStr( 'color', $stepInfo['color'] );
		$q.bindStr( 'estimationMethod', $stepInfo['estimationMethod'] );
		$q.bindStr( 'estimationVeryEasy', $stepInfo['estimationVeryEasy'] );
		$q.bindStr( 'estimationEasy', $stepInfo['estimationEasy'] );
		$q.bindStr( 'estimationMedium', $stepInfo['estimationMedium'] );
		$q.bindStr( 'estimationHard', $stepInfo['estimationHard'] );
		$q.bindStr( 'estimationVeryHard', $stepInfo['estimationVeryHard'] );
		$q.bindInt( 'projectId', $projectId );

		$q->execute( "Step associated with project." );
		$q->close();
	}

	// ========= ASSIGN USER ==========
	else if ( acceptReply( "assignUser", 'admin' ) )
	{
		$userUuid = getArg("userUuid");
		$projectUuid = getArg("projectUuid");

		$q = new DBQuery();
		$userId = $q->id("users", $userUuid);
		$projectId = $q->id("projects", $projectUuid);
		
		$q->insert('projectuser', array('projectId', 'userId'));
		$q->bindInt( "userId", $userId );
		$q->bindInt( "projectId", $projectId );

		$q->execute("User assigned to project.");
		$q->close();
	}

	// ========= REMOVE USER ==========
	else if ( acceptReply( "unassignUser", 'admin' ) )
	{
		$userUuid = getArg("userUuid");
		$projectUuid = getArg("projectUuid");

		$q = new DBQuery();
		$userId = $q->id("users", $userUuid);
		$projectId = $q->id("projects", $projectUuid);

		$q->prepare( "UPDATE {$tablePrefix}projectuser
			SET
				removed = 1,
				latestUpdate = :udpateTime
			WHERE
				userId= :userId
				AND
				projectId= :projectId
			;");

		$q->bindStr( 'udpateTime', dateTimeStr() );
		$q->bindInt( 'userId', $userId );
		$q->bindInt( 'projectId', $projectId );

		$q->execute("User unassigned from project.");
		$q->close();
	}
?>
