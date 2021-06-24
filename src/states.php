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

	// ========= CREATE STATE ==========
	if (isset($_GET["createState"]))
	{
		$reply["accepted"] = true;
		$reply["query"] = "createState";

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
				//if an id is provided
				if (strlen($uuid) > 0)
				{
					$qString = "INSERT INTO " . $tablePrefix . "states (name,shortName,uuid) VALUES ( :name , :shortName , :uuid ) ON DUPLICATE KEY UPDATE shortName = VALUES(shortName), name = VALUES(name);";
					$values = array('name' => $name, 'shortName' => $shortName, 'uuid' => $uuid);
				}
				else
				{
					$qString = "INSERT INTO " . $tablePrefix . "status (name,shortName,uuid) VALUES ( :name , :shortName , uuid() ) ON DUPLICATE KEY UPDATE shortName = VALUES(shortName), name = VALUES(name);";
					$values = array('name' => $name, 'shortName' => $shortName);
				}


				$rep = $db->prepare($qString);
				$rep->execute($values);
				$rep->closeCursor();

				$reply["message"] = "State " . $shortName . " added.";
				$reply["success"] = true;
			}
			else
            {
                $reply["message"] = "Insufficient rights, you need to be Admin to create states.";
                $reply["success"] = false;
            }
		}
		else
		{
			$reply["message"] = "Invalid request, missing values";
			$reply["success"] = false;
		}
	}

	// ========= GET STATES ==========
	else if (isset($_GET["getStates"]) || isset($_GET["init"]))
	{
		if (isset($_GET["getStates"])) {
			$reply["accepted"] = true;
			$reply["query"] = "getStates";
		}
		

		$rep = $db->query( "SELECT
			`name`, `shortName`, `color`, `completionRatio`, `uuid`
			FROM {$statesTable}
			WHERE `removed` = 0
			ORDER BY `completionRatio`, `shortName`, `name` ;"
			);
		$states = Array();
		while ($state = $rep->fetch())
		{
			$stat = Array();
			$stat['name'] = $state['name'];
			$stat['shortName'] = $state['shortName'];
			$stat['color'] = $state['color'];
			$stat['completionRatio'] = (int) $state['completionRatio'];
			$stat['uuid'] = $state['uuid'];
			$states[] = $stat;
		}
		$rep->closeCursor();

		if (isset($_GET["getStates"])) {
			$reply["content"] = $states;
			$reply["message"] = "States list retreived";
			$reply["success"] = true;
		} else {
			$reply["content"]["states"] = $states;
		}
	}

	// ========= UPDATE STATE ==========
	else if (isset($_GET["updateState"]))
	{
		$reply["accepted"] = true;
		$reply["query"] = "updateState";

		$name = getArg( "name" );
		$shortName = getArg( "shortName" );
		$color = getArg( "color" );
		$completionRatio = getArg( "completionRatio" );
		$uuid = getArg( "uuid" );
		$comment = getArg( "comment" );

		if (strlen($shortName) > 0 AND strlen($uuid) > 0)
		{
			// Only if admin
            if ( isAdmin() )
            {

				$qString = "UPDATE {$statesTable} SET `name`= :name ,`shortName`= :shortName, `comment`= :comment";
				$values = array('name' => $name,'shortName' => $shortName, 'uuid' => $uuid, 'comment' => $comment);

				if (strlen($color) > 0)
				{
					//add # on color if needed
					if (strlen($color) == 6) $color = "#" . $color;
					$qString = $qString . ", color= :color";
					$values["color"] = $color;
				}

				if (strlen($completionRatio) > 0)
				{
					$qString = $qString . ", completionRatio= :completionRatio";
					$values["completionRatio"] = (int)$completionRatio;
				}

				$qString = $qString . " WHERE uuid= :uuid ;";

				$rep = $db->prepare($qString);
				$rep->execute($values);
				$rep->closeCursor();

				$reply["message"] = "State \"" . $shortName . "\" updated.";
				$reply["success"] = true;
			}
			else
            {
                $reply["message"] = "Insufficient rights, you need to be Admin to update state information.";
                $reply["success"] = false;
            }
		}
		else
		{
			$reply["message"] = "Invalid request, missing values";
			$reply["success"] = false;
		}

	}

	// ========= REMOVE STATE ==========
	else if (isset($_GET["removeState"]))
	{
		$reply["accepted"] = true;
		$reply["query"] = "removeState";

		$uuid = "";

		if (isset($_GET["uuid"])) $uuid = $_GET["uuid"];

		if (strlen($uuid) > 0)
		{
			//only if admin
			if (isAdmin())
			{
				$rep = $db->prepare("UPDATE " . $tablePrefix . "states SET removed = 1 WHERE uuid= :uuid ;");
				$rep->execute(array('uuid' => $uuid));
				$rep->closeCursor();

				$reply["message"] = "State " . $uuid . " removed.";
				$reply["success"] = true;
			}
			else
            {
                $reply["message"] = "Insufficient rights, you need to be Admin to remove states.";
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
