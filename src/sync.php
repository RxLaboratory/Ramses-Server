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

    if ( acceptReply( "sync" ) )
    {
        $q = new DBQuery();
        $q->vacuum();

        // Create the sync cache folder
        $syncCachePath = $__ROOT__."/sync_cache";
        if (!is_dir($syncCachePath)) {
            $log->debugLog("Creating the '{$syncCachePath}' folder.", "DEBUG");
            if (!mkdir($syncCachePath)) {
                $reply["success"] = false;
                $reply["message"] = "Can't sync: failed creating the '{$syncCachePath}' folder. An administrator may try to create it manually, and give it read/write access to the server.";
                $log->debugLog("Can't create the '{$syncCachePath}' folder.", "CRITICAL");
                printAndDie();
            };
        }

        // Clean older sync data if any

        // Previous sync for this session
        if (isset($_SESSION["syncCachePath"]))
        {
            $syncFolder = $_SESSION["syncCachePath"];
            if (is_dir($syncFolder))
            {
                $log->debugLog("Deleting previous Sync cache at '{$syncFolder}'", "DEBUG");
                deleteDir($syncFolder);
            }
        }

        // Syncs which are too old
        $syncFolders = glob($syncCachePath . "/" . "*/", GLOB_MARK);
        foreach( $syncFolders as $syncFolder)
        {
            $syncTime = filectime($syncFolder);
            $now = time();
            if ($now - $syncTime > 3600) // an hour
            {
                $log->debugLog("Deleting old Sync cache at '{$syncFolder}'", "DEBUG");
                deleteDir($syncFolder);
            }
        }

        // Create a new cache folder
        $folderName = "";
        if (isset($_SESSION["uuid"]) && $_SESSION["uuid"] != "") {
            $folderName = $_SESSION["uuid"];
        }
        $folderName = $folderName . "-" . uniqid();
        $syncCacheFolder = $syncCachePath . "/" . $folderName;

        $log->debugLog("Creating the '{$syncCacheFolder}' folder.", "DEBUG");
        if (!mkdir($syncCacheFolder)) {
            $reply["success"] = false;
            $reply["message"] = "Can't sync: failed creating the '{$syncCacheFolder}' folder. Make sure Ramses has read and write access in the '$syncCachePath' folder.";
            $log->debugLog("Can't create the '{$syncCacheFolder}' folder.", "CRITICAL");
            printAndDie();
        }

        $log->debugLog("Created new Sync cache at '{$syncCacheFolder}'", "DEBUG");
        $_SESSION["syncCachePath"] = $syncCacheFolder;
        $_SESSION["syncCommited"] = false;

        $reply["success"] = true;
        $reply["message"] = "Sync session started. You can now push your changes.";
        printAndDie();
    }
?>
