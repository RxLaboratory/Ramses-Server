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
    if ( acceptReply( "createFileType", 'projectAdmin' ) )
    {
        $name = getArg("name");
		$shortName = getArg("shortName");
        $extensions = getArg("extensions");
		$uuid = getArg("uuid", uuid());

        // remove leading "."
        if ( strpos( $shortName, '.' ) == 0 ) $shortName = substr( $shortName, 1);

        $q = new DBQuery();

        $q->insert( "filetypes", array( 'name', 'shortName', 'extensions', 'uuid' ));
        $q->bindName( $name );
        $q->bindShortName( $shortName );
		$q->bindStr( "extensions", $extensions );
		$q->bindStr( "uuid", $uuid, true );

        $q->execute("File type '{$shortName}' created.");
		$q->close();
    }

    // ========= UPDATE ==========
	else if ( acceptReply( "updateFileType", 'projectAdmin' ) )
	{
		$name = getArg( "name" );
		$shortName = getArg( "shortName" );
        $extensions = getArg( "extensions" );
        $previewable = getArg( "previewable" );
		$uuid = getArg( "uuid" );
		$comment = getArg( "comment" );

        // remove leading "."
        if ( strpos( $shortName, '.' ) == 0 ) $shortName = substr( $shortName, 1);

        $q = new DBQuery();
        $q->update(
			"filetypes",
			array(
				'name',
				'shortName',
				'extensions',
                'previewable',
                'comment'
			),
			$uuid
		);

        $q->bindName( $name );
        $q->bindShortName( $shortName );
        $q->bindStr( "comment", $comment );
        $q->bindStr( "extensions", $extensions );
		$q->bindInt( "previewable", $previewable );

        $q->execute("File type '{$shortName}' updated.");
		$q->close();
	}

	// ========= REMOVE ==========
	else if ( acceptReply( "removeFileType", 'projectAdmin' ) )
	{
		$uuid = getArg ( "uuid" );
		$q = new DBQuery();
		$q->remove( "filetypes", $uuid );
	}

    // ========= GET ==========
    else if (acceptReply("getFileTypes") || hasArg("init"))
    {
        $q = new DBQuery();
		$filetypes = $q->getAll("filetypes",
			array(
				'name',
				'shortName',
				'uuid',
				'extensions',
				'previewable',
				'comment'
			),
			array(
				'shortName',
				'name'
			)
		);

		// Adjust values
		for ($f = 0; $f < count($filetypes); $f++)
		{
			$filetypes[$f]['previewable'] = (int)$filetypes[$f]['previewable'];
		}

		if (hasArg("init") )
        {
            $reply["content"]["fileTypes"] = $filetypes;
        }
        else 
        {
            $reply["content"] = $filetypes;
            $reply["message"] = "File Type list retreived";
            $reply["success"] = true;
        }
    }
?>
