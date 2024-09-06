<?php 
    // If this file is called directly, abort.
    if (!defined('RAMROOT')) die;

    function addEmailColumn()
    {
        global $sqlMode, $tablePrefix;

        if (!hasColumn($tablePrefix . "RamUser", 'email')) 
        {
            echo "Adding the 'email' column for table: <code>RamUser</code>.<br>";
            flush();

            $qStr = "ALTER TABLE `{$tablePrefix}RamUser`
                        ADD COLUMN `email` TEXT NOT NULL;";

            $q = new DBQuery();
            $q->prepare($qStr);
            $q->execute();
            $q->close();
        }

        echo "Updating email column for table: <code>RamUser</code>.<br>";
        flush();

        $qStr = "SELECT `id`, `data`, `email` FROM `{$tablePrefix}RamUser`";
        $q = new DBQuery();
        $q->prepare($qStr);
        $q->execute();
        while ($entry = $q->fetch())
        {
            $email = $entry["email"] ?? "";
            if (trim($email) != "")
                continue;

            $id = (int)$entry["id"];
            $dataStr = $entry["data"];
            $data = json_decode(
                decrypt($dataStr),
                true
            );

            $email = $data["email"] ?? "";
            if ($email == "")
                continue;

            echo "Setting email $email for user ID $id.<br>";
            flush();
            //$email = encrypt($email);
            
            $qStr = "UPDATE `{$tablePrefix}RamUser` SET `email` = :email
                     WHERE `id` = :id;";

            $q2 = new DBQuery();
            $q2->prepare($qStr);
            $q2->bindInt("id", $id);
            $q2->bindStr("email", $email);
            $q2->execute();
            $q2->close();
        }
        $q->close();
    }