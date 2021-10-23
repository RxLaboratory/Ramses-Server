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

    function getFileTypes( $aid )
    {
        global $tablePrefix, $db;

        $fileTypes = array();
        $qString = "SELECT
                ". $tablePrefix . "filetypes.`uuid`,
                ". $tablePrefix . "applicationfiletype.`type`
            FROM {$tablePrefix}applicationfiletype
            JOIN {$tablePrefix}filetypes
            ON {$tablePrefix}applicationfiletype.`fileTypeId` = {$tablePrefix}filetypes.`id`
            WHERE applicationId= " . $aid . " AND {$tablePrefix}filetypes.`removed` = 0
            ORDER BY {$tablePrefix}filetypes.`name`, {$tablePrefix}filetypes.`shortName` ;";

        $repFileTypes = $db->query( $qString );
        while ($ft = $repFileTypes->fetch())
        {
            $fileType = array();
            $fileType['uuid'] = $ft['uuid'];
            $fileType['type'] = $ft['type'];

            $fileTypes[] = $fileType;
        }

        return $fileTypes;
    }

    // ========= CREATE ==========
    if ( acceptReply("createApplication", 'projectAdmin') )
    {
        $name = getArg("name");
		$shortName = getArg("shortName");
        $executableFilePath = getArg("executableFilePath");
		$uuid = getArg("uuid");

        $q = new DBQuery();

        $q->insert( "applications", array( 'name', 'shortName', 'executableFilePath', 'uuid' ));

		$q->bindName( $name );
		$q->bindShortName( $shortName );
		$q->bindStr( "uuid", $uuid, true );
		$q->bindStr( "executableFilePath", $executableFilePath );

		$q->execute("Application '{$shortName}' added.");
		$q->close();
    }

    // ========= UPDATE ==========
	else if ( acceptReply("updateApplication", 'projectAdmin') )
	{
		$name = getArg( "name" );
		$shortName = getArg( "shortName" );
        $executableFilePath = getArg( "executableFilePath" );
        $comment = getArg( "comment" );
		$uuid = getArg( "uuid" );

        $q = new DBQuery();
		$q->update(
			"applications",
			array(
				'name',
				'shortName',
				'executableFilePath',
                'comment'
			),
			$uuid
		);

		$q->bindName( $name );
		$q->bindShortName( $shortName );
		$q->bindStr( "comment", $comment );
		$q->bindStr( "executableFilePath", $executableFilePath );

		$q->execute("Application '{$shortName}' updated.");
		$q->close();
	}

	// ========= REMOVE ==========
	else if ( acceptReply("removeApplication", 'projectAdmin') )
	{
		$uuid = getArg("uuid");
        $q = new DBQuery();
		$q->remove( "applications", $uuid );
	}

    // ========= GET ==========
    else if (hasArg("getApplications") || hasArg("init"))
    {
        if (hasArg("getApplications")) {
            $reply["accepted"] = true;
            $reply["query"] = "getApplications";
        }
        
        
        $rep = $db->prepare("SELECT
                `name`,`shortName`,`executableFilePath`,`id`,`uuid`,`comment`
            FROM {$tablePrefix}applications
            WHERE removed = 0
            ORDER BY `name`, `shortName`
            ;");
        $rep->execute();

        $applications = Array();

        while ($a = $rep->fetch())
        {
            $application = Array();
			$application['name'] = $a['name'];
			$application['shortName'] = $a['shortName'];
			$application['comment'] = $a['comment'];
			$application['uuid'] = $a['uuid'];
			$application['executableFilePath'] = $a['executableFilePath'];
            $application['fileTypes'] = getFileTypes($a['id']);

			$applications[] = $application;
        }

        $rep->closeCursor();

        if (hasArg("getApplications")) {
            $reply["content"] = $applications;
            $reply["message"] = "Application list retrieved.";
            $reply["success"] = true;
        } else {
            $reply["content"]["applications"] = $applications;
        }
		
    }

    // ========= ASSIGN FILE TYPE ==========
	else if ( acceptReply("assignFileType", 'projectAdmin') )
	{
		$fileTypeUuid = getArg("fileTypeUuid");
		$applicationUuid = getArg("applicationUuid");
		$type = getArg("type", "native");

        $q = new DBQuery();
		$fileTypeId = $q->id("filetypes", $fileTypeUuid);
		$applicationId = $q->id("applications", $applicationUuid);

        $q->insert('applicationfiletype', array('fileTypeId', 'applicationId','type'));
		$q->bindInt( "fileTypeId", $fileTypeId );
		$q->bindInt( "applicationId", $applicationId );
		$q->bindStr( "type", $type );

		$q->execute("File type assigned to application as {$type} type.");
		$q->close();
	}

	// ========= REMOVE FILE TYPE ==========
	else if ( acceptReply("assignFileType", 'projectAdmin') )
	{
		$fileTypeUuid = getArg("fileTypeUuid");
		$applicationUuid = getArg("applicationUuid");
        $type = getArg("type");

        $q = new DBQuery();
		$fileTypeId = $q->id("filetypes", $fileTypeUuid);
		$applicationId = $q->id("applications", $applicationUuid);

        $q->prepare( "UPDATE {$tablePrefix}applicationfiletype
			SET
				`removed` = 1,
				`latestUpdate` = :udpateTime
			WHERE
                `fileTypeId`= :fileTypeId
				AND
				`applicationId`= :applicationId
                AND
                `type`= :type
			;");

		$q->bindStr( 'udpateTime', dateTimeStr() );
		$q->bindStr( 'type',$type );
		$q->bindInt( 'fileTypeId', $fileTypeId );
		$q->bindInt( 'applicationId', $applicationId );

		$q->execute("File type unassigned from application.");
		$q->close();
	}
?>
