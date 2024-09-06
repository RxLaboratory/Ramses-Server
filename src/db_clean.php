<?php
    // If this file is called directly, abort.
    if (!defined('RAMROOT')) die;

    
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

    // Functions

    // Creates the server Meta data table
    function db_clean_createTable()
    {
        global $tablePrefix, $sqlMode, $log;

        $q = new DBQuery();

        // Prepare: make sure our server metadata table is available
        $qStr = "";
        if ($sqlMode == 'sqlite') $qStr = $qStr . "CREATE TABLE IF NOT EXISTS `{$tablePrefix}ServerData` (
                    `id`	INTEGER NOT NULL UNIQUE,
                    `lastDBClean`	timestamp NOT NULL,
                    PRIMARY KEY(`id` AUTOINCREMENT) );";
        else $qStr = $qStr . "CREATE TABLE IF NOT EXISTS `{$tablePrefix}ServerData` (
                    `id` int(11) NOT NULL UNIQUE AUTO_INCREMENT,
                    `lastDBClean` timestamp NOT NULL,
                    PRIMARY KEY (`id`)
                    ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
                ";
        $q->prepare($qStr);
        $q->execute();
        $q->close();
        if (!$q->isOK()) $log->debugLog("Can't access or create the Server Metadata table", "WARNING");
        return $q->isOK();
    }

    // Checks the date
    function db_clean_checkDate()
    {
        global $tablePrefix, $log, $dbCleanFrequency;

        $now = time();
        $previous = 0;

        $q = new DBQuery();
        $q->prepare("SELECT `lastDBClean` FROM `{$tablePrefix}ServerData` ;");
        $q->execute();
        if ($r = $q->fetch()) $previous = strtotime($r["lastDBClean"]);
        $q->close();

        // An hour
        $elapsed = $now-$previous;

        if ($elapsed < $dbCleanFrequency) return false;

        $log->debugLog("It's time to clean the database", "DEBUG");

        return true;
    }

    function db_clean_saveDate()
    {
        global $tablePrefix, $log;
        $now = new DateTime();
        $now = $now->format('Y-m-d H:i:s');
        $q = new DBQuery();
        $q->prepare("DELETE FROM `{$tablePrefix}ServerData` ;");
        $q->execute();
        $q->close();

        if (!$q->isOK()) 
        {
            $log->debugLog("Can't delete the previous date of the database clean", "DEBUG");
            return;
        }

        $q->prepare("INSERT INTO `{$tablePrefix}ServerData` (`lastDBClean`) VALUES ( :newDate )  ;");
        $q->bindStr("newDate", $now);
        $q->execute();
        $q->close();

        if (!$q->isOK()) $log->debugLog("Can't save the date of the database clean", "DEBUG");
    }

    function db_clean_ramStatus()
    {
        global $tablePrefix, $log, $SQLMaxRowPerRequest, $sqlMode;

        // Make sure the tables exit
        createTable("RamStatus");

        $q = new DBQuery();

        // === Delete removed statuses ===

        //$q->prepare("DELETE FROM `{$tablePrefix}RamStatus` WHERE `removed` = 1 ;");
        //$q->execute();
        //$q->close();

        if (!$q->isOK()) $log->debugLog("Can't save delete removed statuses from RamStatus", "DEBUG");

        // === Mark obsolete statuses as removed ===
        // They will be deleted during the next clean up

        // List all statuses
        $q->prepare("SELECT `uuid`, `data` FROM `{$tablePrefix}RamStatus` ;");
        $q->execute();

        // Parse all data
        $latestItemStepData = array();
        $statusToMove = array();
        $rowCount = 0;
        while($r = $q->fetch())
        {
            $rowCount++;
            $dataStr = $r["data"];
            $data = json_decode( $dataStr, true );
            if (!isset($data["item"])) continue;
            $itemUuid = $data["item"];
            if (!isset($data["step"])) continue;
            $stepUuid = $data["step"];
            $uuid = $r["uuid"];

            if ( !isset($latestItemStepData[$itemUuid]) ) $latestItemStepData[$itemUuid] = array();
            if ( !isset($latestItemStepData[$itemUuid][$stepUuid]))
            {
                // save data
                $stepData = array();
                $stepData["uuid"] = $uuid;
                $stepData["data"] = $data;
                $latestItemStepData[$itemUuid][$stepUuid] = $stepData;
            }
            else
            {
                $other = $latestItemStepData[$itemUuid][$stepUuid];
                $otherData = $other["data"];
                // dates may not be set, for some reason
                if (!isset($data["date"])) $data["date"] = "1818-05-05 00:00:00";
                if (!isset($otherData["date"])) $otherData["date"] = "1818-05-05 00:00:00";
                // compare dates
                $currentDate = strtotime($data["date"]);
                $otherDate = strtotime($otherData["date"]);
                if ($currentDate > $otherDate)
                {
                    $otherUuid = $other["uuid"];
                    $statusToMove[] = $otherUuid;

                    // save data
                    $stepData = array();
                    $stepData["uuid"] = $uuid;
                    $stepData["data"] = $data;
                    $latestItemStepData[$itemUuid][$stepUuid] = $stepData;
                }
                else
                {
                    $statusToMove[] = $uuid;
                }
            }
        }
        $q->close();

        $moveCount = count($statusToMove);

        $log->debugLog("Found {$rowCount} status rows.", "DEBUG");
        $log->debugLog("Found {$moveCount} obsolete status to be removed.", "DEBUG");

        // Remove obsolete data
        if ($moveCount != 0)
        {
            $moved = 0;
            while($moved < $moveCount)
            {
                $condition = " `uuid` = '" . $statusToMove[$moved] . "' ";
                $moved++;
                for ($i = 0; $i < $SQLMaxRowPerRequest; $i++)
                {
                    if ($moved == $moveCount) break;
                    $condition .=  " OR `uuid` = '" . $statusToMove[$moved] . "' ";
                    $moved++;
                }

                $qStr = "UPDATE `{$tablePrefix}RamStatus`
                        SET `removed` = 1
                        WHERE {$condition} ;";

                $q->prepare($qStr);
                $q->execute();
                $q->close();
            }
        }

        // === Delete the RamStatusHistory table if it exists ===
        // The use of this table has been deprecated

        $qStr = "DROP TABLE IF EXISTS `{$tablePrefix}RamStatusHistory`; ";
        $q->prepare($qStr);
        $q->execute();
        $q->close();

        $log->debugLog("Finished cleaning RamStatus", "DEBUG");
    }

    function db_clean_ramScheduleEntry()
    {
        global $tablePrefix, $log;

        // Make sure the tables exit
        createTable("RamScheduleEntry");
        createTable("RamScheduleRow");
        createTable("RamStep");

        $q = new DBQuery();

        // === Delete removed schedule entries ===

        //$q->prepare("DELETE FROM `{$tablePrefix}RamScheduleEntry` WHERE `removed` = 1 ;");
        //$q->execute();
        //$q->close();
        //if (!$q->isOK()) $log->debugLog("Can't save delete removed schedule entries from RamScheduleEntry", "DEBUG");

        // === Update schedule entries ===

        // Check the existing rows to clean entries if they use a non-existing row
        // Row data by uuid
        $existingRows = array();
        $qRows = new DBQuery();
        $qRows->prepare("SELECT `uuid`, `data` FROM `{$tablePrefix}RamScheduleRow` ;");
        $qRows->execute();
        while($row = $qRows->fetch()) {
            $rowUuid = $row["uuid"] ?? "";
            if ($rowUuid == "")
                continue;

            $dataStr = $row["data"] ?? "";
            if ($dataStr == "")
                continue;

            $existingRows[$rowUuid] = json_decode($dataStr, true);
        }
        $qRows->close();

        // Add the project in schedule entries data
        // And add row info if it's missing (old client versions may not include it)
        $q->prepare("SELECT `uuid`, `data` FROM `{$tablePrefix}RamScheduleEntry` ;");
        $q->execute();

        // Parse all data
        // To speed up things,
        // keep step/project association
        // and row/project association
        // and user/row association per project
        $stepProjectUuids = array();
        $rowProjectUuids = array();
        $userRowUuids = array();

        $uuidsToRemove = array();
        $updateData = [];
        $updateUuids = [];
        $newRowsData = [];
        $newRowsUuids = [];

        while($r = $q->fetch())
        {
            $dataStr = $r["data"];
            $uuid = $r["uuid"];
            $data = json_decode( $dataStr, true );

            $projUuid = $data["project"] ?? "";
            $rowUuid = $data["row"] ?? "";

            if (!isset($existingRows[$rowUuid]))
                $rowUuid = "";

            // If there already is a row and a project,
            // we're fine
            if ($projUuid != "" && $rowUuid != "")
                continue;

            // Do we need to update the entry?
            $update = false;

            // Set the project if needed
            if ($projUuid == "") {

                // Find it from the step or the row
                $stepUuid = $data["step"] ?? "";

                // Clean this, it's invalid
                if (($stepUuid == "" || $stepUuid == "none") &&
                    $rowUuid == "") {
                    $uuidsToRemove[] = $uuid;
                    continue;
                }

                if (isset($stepProjectUuids[$stepUuid])) {
                    $projUuid = $stepProjectUuids[$stepUuid];
                }
                else if (isset($rowProjectUuids[$rowUuid])) {
                    $projUuid = $rowProjectUuids[$rowUuid];
                }

                // Find the project from the step
                if ($projUuid == "" && $stepUuid != "" && $stepUuid != "none") {
                    // Get the project
                    $qProj = new DBQuery();
                    $qProj->prepare( "SELECT `data` FROM `{$tablePrefix}RamStep` WHERE `uuid` = :uuid ;" );
                    $qProj->bindStr("uuid", $stepUuid );
                    $qProj->execute();
                    $p = $qProj->fetch();
                    if ($p) {
                        $pDataStr = $p["data"];
                        $pData = json_decode( $pDataStr, true );
                        if (isset($pData["project"])) $projUuid = $pData["project"];
                        if ($projUuid != "") $stepProjectUuids[$stepUuid] = $projUuid;
                    }
                    $qProj->close();
                }
                // Or from the row
                if ($projUuid == "" && $rowUuid != "") {
                    // Get the project
                    $qProj = new DBQuery();
                    $qProj->prepare( "SELECT `data` FROM `{$tablePrefix}RamScheduleRow` WHERE `uuid` = :uuid ;" );
                    $qProj->bindStr("uuid", $rowUuid );
                    $qProj->execute();
                    $p = $qProj->fetch();
                    if ($p) {
                        $pDataStr = $p["data"];
                        $pData = json_decode( $pDataStr, true );
                        if (isset($pData["project"])) $projUuid = $pData["project"];
                        if ($projUuid != "") $rowProjectUuids[$stepUuid] = $projUuid;
                    }
                    $qProj->close();
                }

                // Update if found
                if ($projUuid != "") {
                    $data["project"] = $projUuid;
                    $update = true;
                }
            }

            // Set the row if needed
            if ($rowUuid == "") {

                // Get/Create it from the user
                $userUuid = $data["user"] ?? "";

                // Clean this, it's invalid
                if ($userUuid == "") {
                    $uuidsToRemove[] = $uuid;
                    continue;
                }

                // If we don't have the project,
                // We can't set a row
                if ($rowUuid == "" && $projUuid == "") {
                    $uuidsToRemove[] = $uuid;
                    continue;
                }

                $projectRows = $userRowUuids[$projUuid] ?? array();
                $rowUuid = $projectRows[$userUuid] ?? "";

                // Find the row for this user & project
                if ($rowUuid == "") {
                    // Get the row
                    foreach($existingRows as $rUuid => $rData){
                        // Check User
                        $qrUserUuid = $rData["user"] ?? "";
                        if ($qrUserUuid != $userUuid) 
                            continue;

                        // Check Project
                        $qrProjUuid = $rData["project"] ?? "";
                        if ($qrProjUuid != $projUuid) 
                            continue;

                        $rowUuid = $rUuid;
                        if ($rowUuid != "") {
                            if (!isset($userRowUuids[$projUuid]))
                                $userRowUuids[$projUuid] = array();
                            $userRowUuids[$projUuid][$userUuid] = $rowUuid;
                        }
                    }
                }

                // Create a new row
                if ($rowUuid == "") {

                    $rowData = array();
                    $rowData["comment"] = "Row automatically created on database clean-up.";
                    $rowData["name"] = "User";
                    $rowData["order"] = "0";
                    $rowData["project"] = $projUuid;
                    $rowData["user"] = $userUuid;
                    $rowData["shortName"] = "user-row";
                    $rowUuid = uuid();

                    $newRowsData[] = $rowData;
                    $newRowsUuids[] = $rowUuid;

                    $existingRows[$rowUuid] = $rowData;
                }

                // Update 
                if ($rowUuid != "") {
                    $data["row"] = $rowUuid;
                    $update = true;
                }
            }

            // Make sure the date doesn't include any time
            $date = $data["date"] ?? "";
            $date = explode(" ", $date);
            if (count($date) > 1) {
                $data["date"] = $date[0];
                $update = true;
            }

            // Add to the update list
            if ($update) {
                $updateData[] = $data;
                $updateUuids[] = $uuid;
            }
        }
        $q->close();

        $removeCount = count($uuidsToRemove);
        $updateCount = count($updateData);
        $newRowsCount = count($newRowsData);
        $log->debugLog("Found {$removeCount} invalid or empty schedule entries to remove.", "DEBUG");
        $log->debugLog("Found {$updateCount} schedule entries to update with row and project data.", "DEBUG");
        $log->debugLog("Found {$newRowsCount} new schedule rows to be created.", "DEBUG");

        // Remove
        $q->remove("RamScheduleEntry", $uuidsToRemove);
        
        // Create needed rows
        $q->createOrUpdate("RamScheduleRow", $newRowsData, $newRowsUuids);

        // Update
        $q->createOrUpdate("RamScheduleEntry", $updateData, $updateUuids);

        $log->debugLog("Schedule entries updated.", "DEBUG");
    }

    function db_clean_ramScheduleComment()
    {
        global $tablePrefix, $log;

        // Make sure the tables exit
        createTable("RamScheduleEntry");
        createTable("RamScheduleRow");
        createTable("RamScheduleComment");

        $q = new DBQuery();

        // Check the existing schedule rows
        // Row data by uuid
        $existingRows = $q->get("RamScheduleRow", true);       

        // === Update Schedule Comments ===
        
        // Get comments
        $q->prepare("SELECT `uuid`, `data`, `modified` FROM `{$tablePrefix}RamScheduleComment` WHERE `removed` = 0 ;");
        $q->execute();

        // Note rows per project
        $projectNoteRowUuids = array();

        // New data
        $updateData = [];
        $newRowsData = [];
        $newRowsUuids = [];
        $commentUuidsToRemove = [];

        while($r = $q->fetch())
        {
            $dataStr = $r["data"];
            $uuid = $r["uuid"];
            $data = json_decode( $dataStr, true );

            $projUuid = $data["project"] ?? "";
            $comment = $data["comment"] ?? "";
            $color = $data["color"] ?? "";
            if ($projUuid == "" || ($comment == "" && $color == "")) {
                $commentUuidsToRemove[] = $uuid;
                continue;
            }

            // Get the notes row for the given project
            $rowUuid = $projectNoteRowUuids[$projUuid] ?? "";

            if ($rowUuid == "") {
                foreach($existingRows as $rUuid => $rData) {
                    $pUuid = $rData["data"]["project"] ?? "";

                    // Same project
                    if ($pUuid != $projUuid)
                        continue;

                    // No user
                    $uUuid = $rData["user"] ?? "";
                    if ($uUuid != "" && $uUuid != "none")
                        continue;

                    // Shortname must be "notes-row"
                    $name = $rData["shortName"] ?? "";
                    if ($name != "notes-row")
                        continue;

                    // Found it!
                    $rowUuid = $rUuid;
                    $projectNoteRowUuids[$projUuid] = $rowUuid;
                    break;
                }
            }

            // Not found, create it
            if ($rowUuid == "") {
                $rowData = array();
                $rowData["comment"] = "Row automatically created on database clean-up.";
                $rowData["name"] = "Notes";
                $rowData["order"] = "0";
                $rowData["project"] = $projUuid;
                $rowData["user"] = "";
                $rowData["shortName"] = "notes-row";
                $rowUuid = uuid();

                $newRowsData[] = $rowData;
                $newRowsUuids[] = $rowUuid;
                
                $rData = array();
                $rData["data"] = $rowData;
                $existingRows[$rowUuid] = $rData;
                $projectNoteRowUuids[$projUuid] = $rowUuid;
            }

            // Create a Schedule entry
            $entryData = array();
            $commentArr = explode("\n", $comment);
            $entryData["name"] = array_shift($commentArr);
            $entryData["comment"] = join("\n", $commentArr);
            $entryData["color"] = $color;
            $entryData["row"] = $rowUuid;
            $entryData["project"] = $projUuid;
            $entryData["shortName"] = "note";
            $entryData["step"] = "";
            // Get the date without time
            $date = explode(" ", $data["date"])[0];
            $entryData["date"] = $date;
            $updateData[] = $entryData;

            $commentUuidsToRemove[] = $uuid;
        }

        $q->close();

        $updateCount = count($updateData);
        $rowsCount = count($newRowsData);
        $log->debugLog("Found {$updateCount} schedule comments which are copied to schedule entries.", "DEBUG");
        $log->debugLog("Found {$rowsCount} schedule rows to create to store old notes.", "DEBUG");

        // Remove comments
        $q->remove("RamScheduleComment", $commentUuidsToRemove);

        // Create needed rows
        $q->createOrUpdate("RamScheduleRow", $newRowsData, $newRowsUuids);

        // Create schedule entries
        $q->createOrUpdate("RamScheduleEntry", $updateData);

        $log->debugLog("Schedule comments updated.", "DEBUG");
    }
    
    //  Runs the clean
    function db_clean($force=false)
    {
        global $log;

        if (!db_clean_createTable()) return;
        if (!$force && !db_clean_checkDate()) return;

        $log->debugLog("Running scheduled DB Clean...", "DEBUG");

        update_time_limit(60);

        db_clean_ramStatus();
        db_clean_ramScheduleEntry();
        db_clean_ramScheduleComment();

        // A vacuum for SQLite
        $q = new DBQuery();
        $q->vacuum();

        db_clean_saveDate();

        $log->debugLog("DB Cleaned.", "DEBUG");
    }

    db_clean();
