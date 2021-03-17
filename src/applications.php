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
                ". $tablePrefix . "filetypes.`type`
            FROM " . $tablePrefix . "applicationfiletype
            JOIN " . $tablePrefix . "filetypes
            ON " . $tablePrefix . "applicationfiletype.`fileTypeId` = " . $tablePrefix . "filetypes.`id`
            WHERE applicationId= " . $aid . " AND " . $tablePrefix . "filetypes.`removed` = 0
            ORDER BY " . $tablePrefix . "filetypes.`name`, " . $tablePrefix . "filetypes.`shortName` ;";

        $repFileTypes = $db->query( $qString );
        while ($ft = $repFileTypes->fetch())
        {
            $fileType = array();
            $fileType['uuid'] = $ft['uuid'];
            $fileType['type'] = $ft['type'];

            $fileTypes[] = $fileType['uuid'];
        }

        return $fileTypes;
    }

    // ========= CREATE ==========
    if (isset($_GET["createApplication"]))
    {
        $reply["accepted"] = true;
        $reply["query"] = "createApplication";

        $name = $_GET["name"] ?? "";
		$shortName = $_GET["shortName"] ?? "";
        $executableFilePath = $_GET["executableFilePath"] ?? "";
		$uuid = $_GET["uuid"] ?? "";

        if (strlen($shortName) > 0)
        {
            // Only if admin
            if ( isProjectAdmin() )
            {
                $qString = "INSERT INTO " . $tablePrefix . "applications (`name`,`shortName`,`executableFilePath`,`uuid`) VALUES ( :name , :shortName , :executableFilePath , ";
                $values = array('name' => $name,'shortName' => $shortName, 'uuid' => $uuid, 'executableFilePath' => $executableFilePath);

                if (strlen($uuid) > 0)
                {
                    $qString = $qString . ":uuid";
                    $values['uuid'] = $uuid;
                }
                else 
                {
                    $qString = $qString . "uuid()";
                }

                $qString = $qString . " ) ON DUPLICATE KEY UPDATE shortName = VALUES(shortName), name = VALUES(name), executableFilePath = VALUES(executableFilePath), removed = 0;";

                $rep = $db->prepare($qString);
                $rep->execute($values);
                $rep->closeCursor();         
    
                $reply["message"] = "Application \"" . $shortName . "\" created.";
                $reply["success"] = true;
            }
            else
            {
                $reply["message"] = "Insufficient rights, you need to be Project Admin to create applications.";
                $reply["success"] = false;
            }
        }
        else
        {
            $reply["message"] = "Invalid request, missing values";
            $reply["success"] = false;
        }
    }

    // ========= UPDATE ==========
	else if (isset($_GET["updateApplication"]))
	{
		$reply["accepted"] = true;
		$reply["query"] = "updateApplication";

		$name = $_GET["name"] ?? "";
		$shortName = $_GET["shortName"] ?? "";
        $executableFilePath = $_GET["executableFilePath"] ?? "";
		$uuid = $_GET["uuid"] ?? "";

		if (strlen($shortName) > 0 AND strlen($uuid) > 0)
		{
			// Only if admin
            if ( isProjectAdmin() )
            {
				$qString = "UPDATE " . $tablePrefix . "applications
				SET
					`name`= :name ,
					`shortName`= :shortName,
                    `executableFilePath`= :executableFilePath
				WHERE
					uuid= :uuid ;";
				$values = array('name' => $name,'shortName' => $shortName,'executableFilePath' => $executableFilePath, 'uuid' => $uuid);

                $rep = $db->prepare($qString);
				
                $rep->execute($values);
                $rep->closeCursor();

				$reply["message"] = "Application \"" . $shortName . "\" updated.";
				$reply["success"] = true;
			}
			else
            {
                $reply["message"] = "Insufficient rights, you need to be Project Admin to update application information.";
                $reply["success"] = false;
            }
		}
		else
		{
			$reply["message"] = "Invalid request, missing values";
			$reply["success"] = false;
		}
	}

	// ========= REMOVE ==========
	else if (isset($_GET["removeApplication"]))
	{
		$reply["accepted"] = true;
		$reply["query"] = "removeApplication";

		$uuid = $_GET["uuid"] ?? "";

		if (strlen($uuid) > 0)
		{
			//only if project admin
			if (isProjectAdmin())
			{
				$rep = $db->prepare("UPDATE " . $tablePrefix . "applications SET removed = 1 WHERE uuid= :uuid ;");
				$rep->execute(array('uuid' => $uuid));
				$rep->closeCursor();

				$reply["message"] = "Application " . $uuid . " removed.";
				$reply["success"] = true;
			}
			else
            {
                $reply["message"] = "Insufficient rights, you need to be Project Admin to remove applications.";
                $reply["success"] = false;
            }
		}
		else
		{
			$reply["message"] = "Invalid request, missing values";
			$reply["success"] = false;
		}
	}

    // ========= GET ==========
    else if (isset($_GET["getApplications"]))
    {
        $reply["accepted"] = true;
        $reply["query"] = "getApplications";
        
        $rep = $db->prepare("SELECT `name`,`shortName`,`executableFilePath`,`id`,`uuid` FROM " . $tablePrefix . "applications WHERE removed = 0;");
        $rep->execute();

        $applications = Array();

        while ($a = $rep->fetch())
        {
            $application = Array();
			$application['name'] = $a['name'];
			$application['shortName'] = $a['shortName'];
			$application['uuid'] = $a['uuid'];
			$application['executableFilePath'] = $a['executableFilePath'];
            $application['fileTypes'] = getFileTypes($a['id']);

			$applications[] = $application;
        }

        $rep->closeCursor();

		$reply["content"] = $applications;
		$reply["message"] = "Application list retrieved.";
		$reply["success"] = true;
    }

    // ========= ASSIGN FILE TYPE ==========
	else if (isset($_GET["assignFileType"]))
	{
		$reply["accepted"] = true;
		$reply["query"] = "assignFileType";

		$fileTypeUuid = $_GET["fileTypeUuid"] ?? "";
		$applicationUuid = $_GET["applicationUuid"] ?? "";
		$type = $_GET["type"] ?? "native";

		if (strlen($fileTypeUuid) > 0 && strlen($applicationUuid) > 0)
		{
			//only if lead
			if (isProjectAdmin())
			{
				$qString = "INSERT INTO " . $tablePrefix . "applicationfiletype (`applicationId`, `fileTypeId`, `type`) VALUES (
					( SELECT " . $tablePrefix . "applications.`id` FROM " . $tablePrefix . "applications WHERE " . $tablePrefix . "applications.`uuid` = :applicationUuid ),
					( SELECT " . $tablePrefix . "filetypes.`id` FROM " . $tablePrefix . "filetypes WHERE " . $tablePrefix . "filetypes.`uuid` = :fileTypeUuid ),
                    :type
					) ON DUPLICATE KEY UPDATE " . $tablePrefix . "applicationfiletype.`removed` = 0 ;";

				$rep = $db->prepare($qString);

				$ok = $rep->execute( array('fileTypeUuid' => $fileTypeUuid, 'applicationUuid' => $applicationUuid, 'type' => $type ) );
				$rep->closeCursor();

				if ($ok) $reply["message"] = "File type assigned to application as " . $type . " type.";
				else $reply["message"] = $rep->errorInfo();

				$reply["success"] = $ok;
			}
			else
            {
                $reply["message"] = "Insufficient rights, you need to be Project Admin to assign file types.";
                $reply["success"] = false;
            }
		}
		else 
		{
			$reply["message"] = "Invalid request, missing values";
			$reply["success"] = false;
		}
	}

	// ========= REMOVE FILE TYPE ==========
	else if (isset($_GET["unassignFileType"]))
	{
		$reply["accepted"] = true;
		$reply["query"] = "unassignFileType";

		$fileTypeUuid = $_GET["fileTypeUuid"] ?? "";
		$applicationUuid = $_GET["applicationUuid"] ?? "";

		if (strlen($fileTypeUuid) > 0 && strlen($applicationUuid) > 0)
		{
			//only if lead
			if (isProjectAdmin())
			{
				$q = "DELETE " . $tablePrefix . "applicationfiletype FROM " . $tablePrefix . "applicationfiletype WHERE
                        applicationId= ( SELECT " . $tablePrefix . "applications.id FROM " . $tablePrefix . "applications WHERE " . $tablePrefix . "applications.uuid = :applicationUuid )
                    AND
                        filetypeId= ( SELECT " . $tablePrefix . "filetypes.id FROM " . $tablePrefix . "filetypes WHERE " . $tablePrefix . "filetypes.uuid = :fileTypeUuid )
                    ;";
				$rep = $db->prepare($q);
				$ok = $rep->execute(array('applicationUuid' => $applicationUuid,'fileTypeUuid' => $fileTypeUuid));
				$rep->closeCursor();
	
				$reply["message"] = "File type unassigned from application.";
				$reply["success"] = true;	
			}
			else
            {
                if ($ok) $reply["message"] = "Insufficient rights, you need to be Project Admin to assign file types.";
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
