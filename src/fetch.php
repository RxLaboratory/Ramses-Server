<?php
    // If this file is called directly, abort.
    if (!defined('RAMROOT')) die;

    require_once(RAMROOT."/functions.php");
    require_once(RAMROOT."/reply.php");

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

    if ( acceptReply( "fetch" ) )
    {
        if (!isset($_SESSION["syncCachePath"]))
        {
            $reply["success"] = false;
            $reply["message"] = "Not ready to fetch data; you need to call 'sync' and 'push' some data first.";
            $_SESSION["syncCommited"] = false;
            printAndDie();
        }

        if (!is_dir($_SESSION["syncCachePath"]))
        {
            $log->debugLog("Server Sync cache not found: '" . $_SESSION["syncCachePath"] . "'", "WARNING");
            $reply["success"] = false;
            $reply["message"] = "The Sync cache had not been created. Call 'sync' again. If the problem persists, this may be a misconfiguration of the server.";
            $_SESSION["syncCommited"] = false;
            printAndDie();
        }

        if ( !$_SESSION["syncCommited"] )
        {
            $reply["success"] = false;
            $reply["message"] = "The push session has not been commited yet, you can't retrieve the data if you don't commit changes.";
            printAndDie();
        }

        // Count the number of pages
        $tableCacheFolders = glob($_SESSION["syncCachePath"] . "/" . "*/", GLOB_MARK);
        
        $tables = array();
        $numTables = 0;
        foreach( $tableCacheFolders as $tableCacheFolder )
        {
            // The table name is the name of the folder
            $tableName = basename($tableCacheFolder);

            // Load cache
            $outCacheFile = $tableCacheFolder . "/out.json";
            $out = loadCache($outCacheFile);
            if (!$out) $out = array(); // If the cache file is empty, out is null
            $deletedCacheFile = $tableCacheFolder . "/deleted.json";
            $deletedUuids = loadCache($deletedCacheFile);
            if (!$deletedUuids) $deletedUuids = array(); // If the cache file is empty, deletedUuids is null

            $tableInfo = array();
            $tableInfo["name"] = $tableName;
            $tableInfo["rowCount"] = count($out);
            $tableInfo["deleteCount"] = count($deletedUuids);
            $tableInfo["pageCount"] = ceil( $tableInfo["rowCount"] / $pageRowCount );
            if ($tableInfo["pageCount"] == 0 && $tableInfo["deleteCount"] > 0)
                $tableInfo["pageCount"] = 1;
            $tables[] = $tableInfo;
            $numTables++;
        }

        if ($numTables == 0)
        {
            $reply["success"] = false;
            $reply["message"] = "Nothing to pull, you need to push something first.";
            printAndDie();
        }

        $reply["content"]["tables"] = $tables;
        $reply["content"]["tableCount"] = $numTables;
        $reply["success"] = true;
        $reply["message"] = "There are {$numTables} tables to pull from the server.";
        printAndDie();
    }

