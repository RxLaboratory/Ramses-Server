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

    if ( acceptReply( "fetch" ) )
    {
        if (!isset($_SESSION["syncData"])) $_SESSION["syncData"] = array();

        if ( !$_SESSION["syncData"]["commited"] )
        {
            $reply["success"] = false;
            $reply["message"] = "The push session has not been commited yet, you can't retrieve the data if you don't commit changes.";
            printAndDie();
        }

        $numTables = count( $_SESSION["syncData"] );

        if ($numTables == 0)
        {
            $reply["success"] = false;
            $reply["message"] = "Nothing to pull, you need to push something first.";
            printAndDie();
        }

        // Count the number of pages
        $tables = array();
        foreach( $_SESSION["syncData"] as $tableName => $table )
        {
            $tableInfo = array();
            $tableInfo["name"] = $tableName;
            $tableInfo["rowCount"] = count($table["out"]);
            $tableInfo["deleteCount"] = count($table["deleted"]);
            $tableInfo["pageCount"] = ceil( $tableInfo["rowCount"] / 100 );
            $tables[] = $tableInfo;
        }

        $reply["content"]["tables"] = $tables;
        $reply["content"]["tableCount"] = $numTables;
        $reply["success"] = true;
        $reply["message"] = "There are {$numTables} tables to pull from the server.";
        printAndDie();
    }
?>
