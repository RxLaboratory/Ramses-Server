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
        global $tablePrefix;

        $q = new DBQuery();
        $q->prepare("SELECT
                filetypes.`uuid`,
                applicationfiletype.`type`
            FROM {$tablePrefix}applicationfiletype as applicationfiletype
            JOIN {$tablePrefix}filetypes as filetypes
                ON applicationfiletype.`fileTypeId` = filetypes.`id`
            WHERE applicationfiletype.`applicationId`= {$aid}
                AND filetypes.`removed` = 0
                AND applicationfiletype.`removed` = 0
            ORDER BY filetypes.`name`, filetypes.`shortName` ;"
        );
        $q->execute();
        $fileTypes = array();
        while ($ft = $q->fetch())
        {
            $fileType = array();
            $fileType['uuid'] = $ft['uuid'];
            $fileType['type'] = $ft['type'];

            $fileTypes[] = $fileType;
        }
        $q->close();

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
    else if (acceptReply("getApplications") || hasArg("init"))
    {
        $q = new DBQuery();
		$applications = $q->getAll("applications",
			array(
				'name',
				'shortName',
				'uuid',
				'executableFilePath',
				'id',
				'comment'
			),
			array(
				'shortName',
				'name'
			)
		);

		// Adjust values
		for ($a = 0; $a < count($applications); $a++)
		{
            $applications[$a]['fileTypes'] = getFileTypes($applications[$a]['id']);
		}


		if (hasArg("init") )
        {
            $reply["content"]["applications"] = $applications;
        }
        else 
        {
            $reply["content"] = $applications;
            $reply["message"] = "Application list retreived";
            $reply["success"] = true;
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
