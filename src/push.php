<?php
    require_once($__ROOT__."/functions.php");
    require_once($__ROOT__."/reply.php");

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

    if ( acceptReply( "push" ) )
    {
        // The table name
        $table = getArg("table" );
        // The rows
        $inRows = getArg("rows", array() );
        // The sync date
        $prevSync = getArg("previousSyncDate", "1818-05-05 00:00:00");
        // Do we have everything (commit)
        $commit = getArg("commit", true);

        // Store pagination
        if (!isset($_SESSION["syncCachePath"]))
        {
            $reply["success"] = false;
            $reply["message"] = "Not ready to accept data; you need to call 'sync' first.";
            $_SESSION["syncCommited"] = false;
            printAndDie();
        }

        if (!is_dir($_SESSION["syncCachePath"]))
        {
            $reply["success"] = false;
            $reply["message"] = "The Sync cache had not been created. Call 'sync' again. If the problem persists, this may be a misconfiguration of the server.";
            $_SESSION["syncCommited"] = false;
            printAndDie();
        }

        // Check incoming data

        if ($table != "" && $table != "RamStatusHistory")
        {
            $log->debugLog("Receiving " . $table, "DEBUG");

            // If this table does not exist, it has to be created
            createTable( $table );
            // Create the deletedData table in case it doesn't exist yet
            createDeletedDataTable();

            // This table cache folder
            $tableCacheFolder = $_SESSION["syncCachePath"] . "/{$table}";
            // And its cache files
            $currentCacheFile = $tableCacheFolder . "/current.json";
            $outCacheFile = $tableCacheFolder . "/out.json";
            $inCacheFile = $tableCacheFolder . "/in.json";
            $deletedCacheFile = $tableCacheFolder . "/deleted.json";
            $inUuidsCacheFile = $tableCacheFolder . "/inUuids.json";

            // Get the data
            $current = array();
            $out = array();
            $in = array();
            $deletedUuids = array();
            $inUuids = array();
            if (!is_dir($tableCacheFolder))
            {
                update_time_limit(30);

                mkdir($tableCacheFolder);

                // Get this table rows
                $qStr = "SELECT `uuid`, `data`, `modified`, `removed` ";
                if ($table == "RamUser") $qStr = $qStr. ", `userName` ";
                $qStr = $qStr . "FROM `{$tablePrefix}{$table}` WHERE `modified` >= :modified ;";

                $q = new DBQuery();
                $q->prepare($qStr);
                $q->bindStr("modified", $prevSync);
                $q->execute();

                // Store
                while ($r = $q->fetch())
                {
                    $row = array();
                    $row["uuid"] = $r["uuid"];
                    $row["data"] = $r["data"];
                    $row["modified"] = $r["modified"];
                    $row["removed"] = (int)$r["removed"];
                    if ($table == "RamUser")
                    {
                        $row["userName"] = $r["userName"];
                        $row["data"] = decrypt($row["data"]);
                    }
                    $current[$row["uuid"]] = $row;
                }

                $q->close();

                // Save cache
                saveCache($currentCacheFile, $current);
            }
            else
            {
                // Load cache
                $current = loadCache($currentCacheFile);
                $out = loadCache($outCacheFile);
                $in = loadCache($inCacheFile);
                $deletedUuids = loadCache($deletedCacheFile);
                $inUuids = loadCache($inUuidsCacheFile);
            }

            update_time_limit(30);

            $log->debugLog("Got " . count($inRows) . " rows.", "DEBUG");

            // Process received rows
            foreach ($inRows as $inRow)
            {
                $uuid = $inRow["uuid"];

                // Store
                $inUuids[] = $uuid;

                // Not set, it's either a new one or it may have been deleted
                if (!isset( $current[$uuid] ) )
                {
                    // Check if it's been deleted
                    $qStr = "SELECT `uuid` FROM `{$tablePrefix}deletedData` WHERE `uuid` = :uuid ;";
                    $q = new DBQuery();
                    $q->prepare($qStr);
                    $q->bindStr("uuid", $uuid);
                    $q->execute();

                    if ($q->fetch()) $deletedUuids = $uuid;
                    else $in[] = $inRow;

                    continue;
                }

                // Check modification date
                $currentRow = $current[$uuid];

                // Same date, ignore
                if ($currentRow["modified"] == $inRow["modified"]) continue;

                $currentDate = strtotime( $currentRow["modified"] );
                $inDate = strtotime( $inRow["modified"] );

                // Same date, ignore
                if ($inDate == $currentDate) continue;
                // Newer, to update later
                if ($inDate > $currentDate) $in[] = $inRow;
                // Older, send ours
                else $out[] = $currentRow;
            }

            // Store results
            saveCache($outCacheFile, $out);
            saveCache($inCacheFile, $in);
            saveCache($deletedCacheFile, $deletedUuids);
            saveCache($inUuidsCacheFile, $inUuids);
        }
        
        // Finished if we're not commiting
        if (!$commit)
        {
            $reply["success"] = true;
            $reply["message"] = "Accepted data. Waiting for commit.";
            $reply["content"]["commited"] = false;
            $_SESSION["syncData"]["commited"] = false;
            printAndDie();
        }

        if ($commit && $_SESSION["syncData"]["commited"] )
        {
            $reply["success"] = false;
            $reply["message"] = "This sync session has already been commited. Start a new session to commit new changes.";
            $reply["content"]["commited"] = true;
            printAndDie();
        }

        // Commit changes
        // Load cache
        $tableCacheFolders = glob($_SESSION["syncCachePath"] . "/" . "*/", GLOB_MARK);
        foreach( $tableCacheFolders as $tableCacheFolder )
        {
            update_time_limit(30);

            // The table name is the name of the folder
            $tableName = basename($tableCacheFolder);

            // Get the incoming rows from cache
            $inCacheFile = $tableCacheFolder . "/in.json";
            $in = loadCache($inCacheFile);

            $log->debugLog("Committing " . $tableName, "DEBUG");

            if ($tableName == "RamUser") $qStrHeader = "INSERT INTO `{$tablePrefix}{$tableName}` (`uuid`, `data`, `modified`, `removed`, `password`, `userName` ) VALUES ";
            else $qStrHeader = "INSERT INTO `{$tablePrefix}{$tableName}` (`uuid`, `data`, `modified`, `removed`) VALUES ";
            
            $log->debugLog("Found " . count( $in ) . " Rows", "DEBUG");

            $startRow = 0;
            while($startRow < count( $in ))
            {
                $values = array();

                $endRow = $startRow + $SQLMaxRowPerRequest;
                $endRow = min($endRow, count($in));

                for ($i = $startRow; $i < $endRow; $i++)
                {
                    $newRow = $in[$i];
                    // Escape uuid
                    $uuid = $db->quote($newRow["uuid"]);
                    // Data will be encrypter/escaped later
                    $data = $newRow["data"];
                    // Escape date
                    $modified = $db->quote($newRow["modified"]);
                    // Should be either 1 or 0, nothing else
                    $removed = $newRow["removed"];
                    if (!$removed || $removed == 0 || $removed == "0" || $removed == "" || $removed == "false") $removed = 0;
                    else $removed = 1;

                    if ($tableName == "RamUser")
                    {
                        // Encrypt data
                        $data = $db->quote(
                            encrypt($data)
                        );
                        // Escape user name
                        $userName = $db->quote($newRow["userName"]);

                        $values[] = "( $uuid, $data, $modified, $removed, '-', $userName )";
                    }
                    else
                    {
                        // Escape data
                        $data = $db->quote($data);

                        $values[] = "( $uuid, $data, $modified, $removed )";
                    }
                }

                $startRow += $SQLMaxRowPerRequest;

                $qStr = $qStrHeader . join(", ", $values) . " ";

                if ($sqlMode == 'sqlite') $qStr = $qStr . " ON CONFLICT(uuid) DO UPDATE SET ";
                else if ($sqlMode == 'mysql') $qStr = $qStr . " AS excluded ON DUPLICATE KEY UPDATE ";
                else $qStr = $qStr . " ON DUPLICATE KEY UPDATE ";
               
                if ($tableName == "RamUser" && $sqlMode != 'mariadb') $qStr = $qStr . "`data` = excluded.data, `modified` = excluded.modified, `removed` = excluded.removed, `userName` = excluded.userName ;";
                else if ($tableName == "RamUser" && $sqlMode == 'mariadb') $qStr = $qStr . "`data` = VALUES(`data`), `modified` = VALUES(`modified`), `removed` = VALUES(`removed`), `userName` = VALUES(`userName`) ;";
                else if ($sqlMode == 'mariadb') $qStr = $qStr . "`data` = VALUES(`data`), `modified` = VALUES(`modified`), `removed` = VALUES(`removed`) ;";
                else $qStr = $qStr . "`data` = excluded.data, `modified` = excluded.modified, `removed` = excluded.removed ;";
        
                $q = new DBQuery();
                $q->prepare($qStr);
                $q->execute();
                $q->close();

                // Check if it worked
                if (!$q->isOK())
                {
                    $reply["success"] = false;
                    $reply["message"] = "Failed updating objects in {$tableName}, sorry.";
                    $log->debugLog("Failed when inserting/updating new rows in {$tableName}.\n" . $q->errorInfo(), "WARNING");
                    $reply["content"]["commited"] = false;
                    printAndDie();
                }
            }

            // Get the current rows
            $currentCacheFile = $tableCacheFolder . "/current.json";
            $current = loadCache($currentCacheFile);
            $inUuidsCacheFile = $tableCacheFolder . "/inUuids.json";
            $inUuids = loadCache($inUuidsCacheFile);
            $outCacheFile = $tableCacheFolder . "/out.json";
            $out = loadCache($outCacheFile);

            // Add the new current rows to the out rows
            foreach ($current as $currentRow)
            {
                $currentUuid = $currentRow["uuid"];
                if (!in_array($currentUuid, $inUuids))
                {
                    $out[] = $currentRow;
                }
            }

            // Store results
            saveCache($outCacheFile, $out);
            saveCache($inCacheFile, $in);
            saveCache($inUuidsCacheFile, $inUuids);
        }

        $_SESSION["syncCommited"] = true;

        $reply["success"] = true;
        $reply["message"] = "Data saved!";
        $reply["content"]["commited"] = true;
        printAndDie();
    }

?>