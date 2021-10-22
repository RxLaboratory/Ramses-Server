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

    // ========= CREATE TEMPLATE ASSET GROUP ==========
	if ( acceptReply("createTemplateAssetGroup", 'admin') )
	{
        $name = getArg("name");
		$shortName = getArg("shortName");
		$uuid = getArg("uuid", uuid());

		$q = new DBQuery();
		$q->insert('templateassetgroups', array('name','shortName','uuid'));

		$q->bindStr( 'uuid', $uuid );
		$q->bindName( 'name', $name );
		$q->bindShortName( 'shortName', $shortName );

		$q->execute("Template Asset Group '{$shortName}' added.");
		$q->close();
    }


    // ========= GET TEMPLATE ASSET GROUPS ==========
	else if (hasArg("getTemplateAssetGroups") || hasArg("init"))
	{
		if (hasArg("getTemplateAssetGroups"))
		{
			$reply["accepted"] = true;
			$reply["query"] = "getTemplateAssetGroups";
		}

		$rep = $db->query("SELECT `name`,`shortName`,`uuid`, `comment` FROM {$tablePrefix}templateassetgroups WHERE removed = 0 ORDER BY shortName,name;");
		$assetGroups = Array();
		while ($assetGroup = $rep->fetch())
		{
			$ag = Array();
			$ag['name'] = $assetGroup['name'];
			$ag['shortName'] = $assetGroup['shortName'];
			$ag['comment'] = $assetGroup['comment'];
			$ag['uuid'] = $assetGroup['uuid'];
			$assetGroups[] = $ag;
		}
		$rep->closeCursor();

		if (hasArg("getTemplateAssetGroups"))
		{
			$reply["content"] = $assetGroups;
			$reply["message"] = "Asset groups list retreived";
			$reply["success"] = true;
		}
		else {
			$reply["content"]["templateAssetGroups"] = $assetGroups;
		}
	}

	// ========= UPDATE ASSET GROUP ==========
	else if ( acceptReply("updateTemplateAssetGroup", 'admin') )
	{
		$name = getArg( "name" );
		$shortName = getArg( "shortName" );
		$uuid = getArg( "uuid" );
		$comment = getArg( "comment" );

		$q = new DBQuery();
		$q->update(
			"templateassetgroups",
			array(
				'name',
				'shortName',
				'comment'
			),
			$uuid
		);

		$q->bindName( "name", $name );
		$q->bindShortName( $shortName );
		$q->bindStr( "comment", $comment );

		$q->execute("Asset Group  '{$shortName}' updated.");
		$q->close();	
	}

	// ========= REMOVE ASSET GROUP ==========
	else if ( acceptReply("removeTemplateAssetGroup", 'admin') )
	{
		$uuid = getArg("uuid");
        $q = new DBQuery();
		$q->remove( "templateassetgroups", $uuid );
	}
?>