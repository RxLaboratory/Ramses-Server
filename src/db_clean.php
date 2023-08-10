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
        global $tablePrefix, $log;

        $now = time();
        $previous = 0;

        $q = new DBQuery();
        $q->prepare("SELECT `lastDBClean` FROM `{$tablePrefix}ServerData` ;");
        $q->execute();
        if ($r = $q->fetch()) $previous = strtotime($r["lastDBClean"]);
        $q->close();

        // An hour
        $elapsed = $now-$previous;

        if ($elapsed < 3600) return false;

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
        createTable("RamStatusHistory");

        $q = new DBQuery();

        // === Delete removed statuses ===

        $q->prepare("DELETE FROM `{$tablePrefix}RamStatus` WHERE `removed` = 1 ;");
        $q->execute();
        $q->close();

        if (!$q->isOK()) $log->debugLog("Can't save delete removed statuses from RamStatus", "DEBUG");

        // === Move the oldest statuses to their statushistory table ===

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
        $log->debugLog("Found {$moveCount} status to move to history.", "DEBUG");

        // Move the data to the other table
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

                if ($sqlMode == 'sqlite') 
                    $qStr = "INSERT INTO `{$tablePrefix}RamStatusHistory` (`uuid`, `data`, `modified`, `removed`) 
                            SELECT `uuid`, `data`, `modified`, `removed` FROM `{$tablePrefix}RamStatus` 
                            WHERE {$condition} 
                            ON CONFLICT(uuid) DO UPDATE SET 
                            `data` = excluded.data, `modified` = excluded.modified, `removed` = excluded.removed ;";
                else
                    $qStr = "INSERT INTO `{$tablePrefix}RamStatusHistory`  (`uuid`, `data`, `modified`, `removed`) 
                            SELECT new.uuid, new.data, new.modified, new.removed 
                            FROM ( SELECT `uuid`, `data`, `modified`, `removed` FROM `{$tablePrefix}RamStatus` 
                            WHERE  {$condition} ) 
                            AS new 
                            ON DUPLICATE KEY UPDATE `data` = new.data, `modified` = new.modified, `removed` = new.removed ;";

                $q->prepare($qStr);
                $q->execute();
                $q->close();

                $q->prepare("DELETE FROM `{$tablePrefix}RamStatus` WHERE {$condition} ;");
                $q->execute();
                $q->close();
            }
        }

        $log->debugLog("Finished cleaning RamStatus and RamStatusHistory", "DEBUG");
    }

    function db_clean_ramSchedule()
    {
        global $tablePrefix, $log, $SQLMaxRowPerRequest, $sqlMode;

        // Make sure the tables exit
        createTable("RamScheduleEntry");

        $q = new DBQuery();

        // === Delete removed schedule entries ===

        $q->prepare("DELETE FROM `{$tablePrefix}RamScheduleEntry` WHERE `removed` = 1 ;");
        $q->execute();
        $q->close();

        if (!$q->isOK()) $log->debugLog("Can't save delete removed schedule entries from RamScheduleEntry", "DEBUG");

        $log->debugLog("Finished cleaning RamScheduleEntry", "DEBUG");

        // Add the project in schedule entries data
        $q->prepare("SELECT `uuid`, `data` FROM `{$tablePrefix}RamScheduleEntry` ;");
        $q->execute();

        // Parse all data
        // To speed up things, keep step/project association
        $stepProjectUuids = array();
        $uuidsToRemove = array();
        $updateData = array();
        while($r = $q->fetch())
        {
            $dataStr = $r["data"];
            $uuid = $r["uuid"];
            $data = json_decode( $dataStr, true );
            $projUuid = "";
            if (isset($data["project"])) $projUuid = $data["project"];
            if ($projUuid != "") continue;
            if (!isset($data["step"])) {
                $uuidsToRemove[] = $uuid;
                continue;
            }
            $stepUuid = $data["step"];
            if ($stepUuid == "" || $stepUuid = "none") {
                $uuidsToRemove[] = $uuid;
                continue;
            }
            if (isset($stepProjectUuids[$stepUuid])) {
                $projUuid = $stepProjectUuids[$stepUuid];
            }
            if ($projUuid == "") {
                // Get the project
                $qProj = new DBQuery();
                $qProj->prepare( "SELECT `data` FROM `{$tablePrefix}RamStep` WHERE `uuid` = :uuid ;" );
                $qProj->bindStr("uuid", $stepUuid );
                $qProj->execute();
                $p = $qProj->fetch();
                if ($p) {
                    $pDataStr = $p["data"];
                    $pData = json_decode( $dataStr, true );
                    if (isset($pData["project"])) $projUuid = $pData["project"];
                    if ($projUuid != "") $stepProjectUuids[$stepUuid] = $projUuid;
                }
                $qProj->close();
            }
            if ($projUuid != "") {
                $data["project"] = $projUuid;
                $newData = array();
                $newData['uuid'] = $uuid;
                $newData['data'] = $data;
                $updateData[] = $newData;
            }
            else {
                $uuidsToRemove[] = $uuid;
            }
        }
        $q->close();

        $removeCount = count($uuidsToRemove);
        $updateCount = count($updateData);
        $log->debugLog("Found {$removeCount} invalid or empty schedule entries to remove.", "DEBUG");
        $log->debugLog("Found {$updateCount} schedule entries to update with the project data.", "DEBUG");

        if ($removeCount != 0)
        {
            $remove = 0;
            while ($remove < $removeCount)
            {
                $condition = " `uuid` = '" . $uuidsToRemove[$remove] . "' ";
                $remove++;
                for ($i = 0; $i < $SQLMaxRowPerRequest; $i++)
                {
                    if ($remove == $removeCount) break;
                    $condition .=  " OR `uuid` = '" . $uuidsToRemove[$remove] . "' ";
                    $remove++;
                }

                $q->prepare("DELETE FROM `{$tablePrefix}RamScheduleEntry` WHERE {$condition} ;");
                $q->execute();
                $q->close();
            }
        }

        if ($updateCount != 0)
        {
            $update = 0;
            while ($update < $updateCount)
            {
                $d = $updateData[$update];
                $uuid = $d['uuid'];
                $data = json_encode( $d['data'] );
                if ($sqlMode == 'sqlite') $data = str_replace("'", "''", $data);
                else $data = str_replace("'", "\\'", $data);
                $values = " ( `uuid` = '" . $uuid . "', '" . $data . "' ) ";
                $update++;
                for ($i = 0; $i < $SQLMaxRowPerRequest; $i++)
                {
                    if ($update == $updateCount) break;
                    $d = $updateData[$update];
                    $uuid = $d['uuid'];
                    $data = json_encode( $d['data'] );
                    $values .= ", ( `uuid` = '" . $uuid . "', '" . $data . "' ) ";
                    $update++;
                }

                if ($sqlMode == 'sqlite') 
                    $qStr = "INSERT INTO `{$tablePrefix}RamScheduleEntry` (`uuid`, `data`) {$values}
                            ON CONFLICT(uuid) DO UPDATE SET 
                            `data` = excluded.data ;";
                else
                    $qStr = "INSERT INTO `{$tablePrefix}RamStatusHistory`  (`uuid`, `data`) {$values}
                            AS new 
                            ON DUPLICATE KEY UPDATE `data` = new.data, `modified` = new.modified, `removed` = new.removed ;";

                $q->prepare($qStr);
                $q->execute();
                $q->close();
            }
        }
    }
    
    //  Runs the clean
    function db_clean()
    {
        if (!db_clean_createTable()) return;
        if (!db_clean_checkDate()) return;

        set_time_limit(60);
        db_clean_ramStatus();

        db_clean_ramSchedule();

        // A vacuum for SQLite
        $q = new DBQuery();
        $q->vacuum();

        db_clean_saveDate();
    }

    db_clean();
?>