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

    if ( acceptReply( "pull" ) )
    {
        // There may be a uuid and a table for a single-object pull
        $table = getArg("table");

        if ($table == "")
        {
            $reply["success"] = false;
            $reply["message"] = "Malformed request, sorry. The table name is missing.";
            printAndDie();
        }

        $uuid = getArg("uuid");

        // Single object pull
        if ($uuid != "")
        {
            // Create the table if it doesn't exists
            createTable( $table );

            $qStr = "SELECT `uuid`, `data`, `modified`, `removed`, `project` ";
            if ($table == "RamUser") $qStr = $qStr . ", `userName` ";
            $qStr = $qStr . " FROM `{$tablePrefix}{$table}` WHERE `uuid` = :uuid ;";

            $q = new DBQuery();
            $q->prepare($qStr);
            $q->bindStr("uuid", $uuid);
            $q->execute();

            $r = $q->fetch();
            if ($r)
            {
                $reply["content"]["uuid"] = $r["uuid"];
                $reply["content"]["modified"] = $r["modified"];
                $reply["content"]["removed"] = (int)$r["removed"];
                $reply["content"]["project"] = $r["project"];

                if ($table == "RamUser")
                {
                    $reply["content"]["userName"] = $r["userName"];
                    // We need to decrypt the user data
                    $data = $r["data"];
                    $reply["content"]["data"] = decrypt( $data );
                }
                else
                {
                    $reply["content"]["data"] = $r["data"];
                }

                $reply["success"] = true;
                $reply["message"] = "Pulled data: OK!";
            }
            else
            {
                $reply["content"]["uuid"] = "";
                $reply["content"]["modified"] = "";
                $reply["content"]["removed"] = 0;
                $reply["content"]["project"] = "";
                $reply["content"]["data"] = "";

                if ($table == "RamUser") $reply["content"]["userName"] = "";

                $reply["success"] = true;
                $reply["message"] = "Pulled data: this object doesn't exist.";
            }

            $q->close();

            printAndDie();
        }

        // The page to send
        $page = getArg("page", 1);

        // Pull the 
    }
?>