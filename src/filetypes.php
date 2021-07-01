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

    // ========= CREATE ==========
    if (hasArg("createFileType"))
    {
        $reply["accepted"] = true;
        $reply["query"] = "createFileType";

        $name = getArg("name");
		$shortName = getArg("shortName");
        $extensions = getArg("extensions");
		$uuid = getArg("uuid");

        if (strlen($shortName) > 0)
        {
            // Only if admin
            if ( isProjectAdmin() )
            {
                $qString = "INSERT INTO " . $tablePrefix . "filetypes (`name`,`shortName`,`extensions`,`uuid`) VALUES ( :name , :shortName , :extensions , ";
                $values = array('name' => $name,'shortName' => $shortName, 'uuid' => $uuid, 'extensions' => $extensions);

                if (strlen($uuid) > 0)
                {
                    $qString = $qString . ":uuid";
                    $values['uuid'] = $uuid;
                }
                else 
                {
                    $qString = $qString . "uuid()";
                }

                $qString = $qString . " ) ON DUPLICATE KEY UPDATE shortName = VALUES(shortName), name = VALUES(name), extensions = VALUES(extensions), removed = 0;";

                $rep = $db->prepare($qString);
                $rep->execute($values);
                $rep->closeCursor();         
    
                $reply["message"] = "File type \"" . $shortName . "\" created.";
                $reply["success"] = true;
            }
            else
            {
                $reply["message"] = "Insufficient rights, you need to be Project Admin to create file types.";
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
	else if (hasArg("updateFileType"))
	{
		$reply["accepted"] = true;
		$reply["query"] = "updateFileType";

		$name = getArg( "name" );
		$shortName = getArg( "shortName" );
        $extensions = getArg( "extensions" );
        $previewable = getArg( "previewable" );
		$uuid = getArg( "uuid" );
		$comment = getArg( "comment" );

		if (strlen($shortName) > 0 AND strlen($uuid) > 0)
		{
			// Only if admin
            if ( isProjectAdmin() )
            {
				$qString = "UPDATE {$filetypesTable}
				SET
					`name`= :name ,
					`shortName`= :shortName,
                    `extensions`= :extensions,
                    `previewable` = :previewable,
                    `comment` = :comment
				WHERE
					uuid= :uuid ;";
				$values = array('name' => $name,'shortName' => $shortName,'extensions' => $extensions, 'previewable' => $previewable, 'uuid' => $uuid, 'comment' => $comment);

                $rep = $db->prepare($qString);
				
                $rep->execute($values);
                $rep->closeCursor();

				$reply["message"] = "File type \"" . $shortName . "\" updated.";
				$reply["success"] = true;
			}
			else
            {
                $reply["message"] = "Insufficient rights, you need to be Project Admin to update file type information.";
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
	else if (hasArg("removeFileType"))
	{
		$reply["accepted"] = true;
		$reply["query"] = "removeFileType";

		$uuid = getArg("uuid");

		if (strlen($uuid) > 0)
		{
			//only if project admin
			if (isProjectAdmin())
			{
				$rep = $db->prepare("UPDATE " . $tablePrefix . "filetypes SET removed = 1 WHERE uuid= :uuid ;");
				$rep->execute(array('uuid' => $uuid));
				$rep->closeCursor();

				$reply["message"] = "File type " . $uuid . " removed.";
				$reply["success"] = true;
			}
			else
            {
                $reply["message"] = "Insufficient rights, you need to be Project Admin to remove file types.";
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
    else if (hasArg("getFileTypes") || hasArg("init"))
    {
        if (hasArg("getFileTypes")) {
            $reply["accepted"] = true;
            $reply["query"] = "getFileTypes";
        }
        
        
        $rep = $db->prepare("SELECT
                `name`,`shortName`,`extensions`,`previewable`,`uuid`, `comment`
            FROM " . $tablePrefix . "filetypes
            WHERE removed = 0
            ORDER BY `shortName`, `name`
            ;");
        $rep->execute();

        $filetypes = Array();

        while ($f = $rep->fetch())
        {
            $filetype = Array();
			$filetype['name'] = $f['name'];
			$filetype['shortName'] = $f['shortName'];
			$filetype['comment'] = $f['comment'];
			$filetype['uuid'] = $f['uuid'];
			$filetype['extensions'] = $f['extensions'];
			$filetype['previewable'] = (int) $f['previewable'];

			$filetypes[] = $filetype;
        }

        $rep->closeCursor();

        if (hasArg("getFileTypes")) {
            $reply["content"] = $filetypes;
            $reply["message"] = "File types list retrieved.";
            $reply["success"] = true;
        } else {
            $reply["content"]["fileTypes"] = $filetypes;
        }
    }
?>
