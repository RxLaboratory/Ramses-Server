<?php

    function addProjectColumns()
    {
        global $sqlMode, $tablePrefix;

        // Get all tables
        $qStr = "";
        if ($sqlMode == 'sqlite')
        {
            $qStr = "SELECT 
                    name
                FROM 
                    sqlite_master
                WHERE 
                    type ='table' AND 
                    name NOT LIKE 'sqlite_%';";
        }
        else
        {
            $qStr = "SHOW TABLES LIKE '{$tablePrefix}Ram%'; ";
        }

        $projectTableNames = [
            "RamAsset",
            "RamAssetGroup",
            "RamPipe",
            "RamPipeFile",
            "RamScheduleComment",
            "RamScheduleEntry",
            "RamSequence",
            "RamShot",
            "RamStatus",
            "RamStep"
        ];

        $q = new DBQuery();

        $q->prepare($qStr);
        $q->execute();

        while ($row = $q->fetch())
        {
            $table = $row[0];

            if (hasProjectColumn($table)) continue;

            echo "Adding the 'project' column for table: <code>{$table}</code>.<br>";
            flush();

            if ($sqlMode == 'sqlite') $qStr = "ALTER TABLE `{$table}` ADD COLUMN 'project' TEXT;";
            else $qStr = "ALTER TABLE `{$table}` ADD `project` TEXT NULL AFTER `data`;";

            $q2 = new DBQuery();
            $q2->prepare($qStr);
            $q2->execute();
            $q2->close();

            echo "  • Populating...<br>";
            flush();

            $qStr = "SELECT `uuid`, `data` FROM `{$table}`";
            $q2->prepare($qStr);
            $q2->execute();
            while ($entry = $q2->fetch())
            {
                $uuid = $entry["uuid"];
                $dataStr = $entry["data"];
                $data = json_decode($dataStr, true);
                $projUuid = "";
                if (isset($data["project"])) $projUuid = $data["project"];

                if ($projUuid == "")
                {
                    // For some tables, find the project info in another table
                    $otherTable = "";
                    $objKey = "";
                    if ($table == "{$tablePrefix}RamScheduleEntry" || $table == "{$tablePrefix}RamStatus")
                    {
                        $otherTable = "RamStep";
                        $objKey = "step";
                    }
                    else if ($table == "{$tablePrefix}RamPipe")
                    {
                        $otherTable = "RamStep";
                        $objKey = "inputStep";
                    }

                    $otherUuid = $data[$objKey];
                    if ($otherUuid != "")
                    {
                        $qStr = "SELECT `data` FROM `{$tablePrefix}{$otherTable}` WHERE `uuid` = :uuid";
                        $q3 = new DBQuery();
                        $q3->prepare($qStr);
                        $q3->bindStr("uuid", $otherUuid);
                        $q3->execute();

                        if ($r = $q3->fetch())
                        {
                            $otherDataStr = $r['data'];
                            $otherData = json_decode($otherDataStr, true);
                            $projUuid = $otherData["project"];
                        }

                        $q3->close();
                    }
                }

                $qStr = "UPDATE `{$table}` SET `project` = :projectUuid WHERE `uuid` = :uuid;";
                $q3 = new DBQuery();
                $q3->prepare($qStr);
                $q3->bindStr("uuid", $uuid);
                $q3->bindStr("projectUuid", $projUuid);
                $q3->execute();
                $q3->close();
            }
            $q2->close();

            echo "  • OK!<br>";
            flush();
        }

        $q->close();
    }

    function hasProjectColumn( $table )
    {
        global $sqlMode;

        $qStr = "";
        if ($sqlMode == 'sqlite')
        {
            $qStr = "PRAGMA table_info({$table});";
            $q = new DBQuery();

            $q->prepare($qStr);
            $q->execute();
            
            while ($row = $q->fetch())
            {
                if ($row['name'] == 'project')
                {
                    $q->close();
                    return true;
                }
            }

            $q->close();
            return false;
        }
        else
        {
            $qStr = "SHOW COLUMNS FROM `{$table}` LIKE 'project';";

            $q = new DBQuery();

            $q->prepare($qStr);
            $q->execute();

            if ($q->fetch())
            {
                $q->close();
                return true;
            }

            $q->close();
            return false;
        }
    }
?>