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
        if (!is_dir($syncCachePath)) mkdir($syncCachePath);

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
        mkdir($syncCacheFolder);
        $log->debugLog("Created new Sync cache at '{$syncCacheFolder}'", "DEBUG");
        $_SESSION["syncCachePath"] = $syncCacheFolder;
        $_SESSION["syncCommited"] = false;

        $reply["success"] = true;
        $reply["message"] = "Sync session started. You can now push your changes.";
        printAndDie();
    }
?>
