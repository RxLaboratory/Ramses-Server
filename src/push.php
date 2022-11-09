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

        // Check incoming data

        if ($table == "")
        {
            $reply["success"] = false;
            $reply["message"] = "Malformed request, sorry. The table name is missing.";
            printAndDie();
        }

        // Store pagination
        $_SESSION["pageCount"] = $pageCount;
        $_SESSION["tableCount"] = $tableCount;
        if (!isset($_SESSION["syncData"])) $_SESSION["syncData"] = array();

        // If this table does not exist, it has to be created
        createTable( $table );
        // Create the deletedData table in case it doesn't exist yet
        createDeletedDataTable();

        // Get the current rows
        if (!isset($_SESSION["syncData"][$table]))
        {
            $_SESSION["syncData"][$table] = array();
            $_SESSION["syncData"][$table]["current"] = array();
            $_SESSION["syncData"][$table]["out"] = array();
            $_SESSION["syncData"][$table]["in"] = array();
            $_SESSION["syncData"][$table]["deleted"] = array();

            // Get this table rows
            $currentRows = array();
            $qStr = "SELECT `uuid`, `data`, `modified`, `removed` ";
            if ($table == "RamUser") $qStr = $qStr. ", `userName` ";
            $qStr = "FROM `{$tablePrefix}{$table}` WHERE `modified` >= :modified ;";

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
                $row["removed"] = $r["removed"];
                if ($table == "RamUser") $row["userName"] = $r["userName"];
                $_SESSION["syncData"][$table]["current"][$row["uuid"]] = $row;
            }

            $q->close();
        }

        $current = $_SESSION["syncData"][$table]["current"];
        $out = $_SESSION["syncData"][$table]["out"];
        $in = $_SESSION["syncData"][$table]["in"];
        $deletedUuids = $_SESSION["syncData"][$table]["deleted"];

        // Process received rows
        foreach ($inRows as $inRow)
        {
            $uuid = $inRow["uuid"];

            // Not set, it's either a new one or it may have been deleted
            if (!isset( $currentRows[$uuid] ) )
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
        $_SESSION["syncData"][$table]["out"] = $out;
        $_SESSION["syncData"][$table]["in"] = $in;
        $_SESSION["syncData"][$table]["deleted"] = $deletedUuids;
        
        // Finished if we're not commiting
        if (!$commit)
        {
            $reply["success"] = true;
            $reply["message"] = "Accepted data. Waiting for commit.";
            printAndDie();
        }


        foreach( $_SESSION["syncData"] as $tableName => $inTable )
        {
            // Insert new rows right now
            if ($inTable == "RamUser") $qStr = "INSERT INTO `{$tablePrefix}{$tableName}` (`uuid`, `data`, `modified`, `removed`, `password`, `userName` ) VALUES ";
            else $qStr = "INSERT INTO `{$tablePrefix}{$tableName}` (`uuid`, `data`, `modified`, `removed`) VALUES ";
            
            $values = array();
            foreach ($inTable["in"] as $newRow)
            {
                $uuid = $newRow["uuid"];
                $data = $newRow["data"];
                $modified = $newRow["modified"];
                $removed = $newRow["removed"];

                if ($tableName == "RamUser")
                {
                    // Encrypt data
                    $data = encrypt($data);
                    $userName = $newRow["userName"];

                    $values[] = "( '{$uuid}', '{$data}', '{$modified}', {$removed}, '-', '{$userName}' )";
                }
                else
                {
                    $values[] = "( '{$uuid}', '{$data}', '{$modified}', {$removed} )";
                }
            }

            $qStr = $qStr . join(", ", $values) . " ";
            if ($sqlMode == 'sqlite') $qStr = $qStr . " ON CONFLICT(uuid) DO UPDATE SET ";
            else $qStr = $qStr . " AS excluded ON DUPLICATE KEY UPDATE ";
            
            if ($tableName == "RamUser") $qStr = $qStr . "`data` = excluded.data, `modified` = excluded.modified, `removed` = excluded.removed, `userName` = excluded.userName ;";
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
                printAndDie();
            }
        }

        $reply["success"] = true;
        $reply["message"] = "Data saved!";
        printAndDie();
    }

?>