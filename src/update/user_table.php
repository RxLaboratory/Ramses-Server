<?php 
    // If this file is called directly, abort.
    if (!defined('RAMROOT')) die;

    function addEmailAndRoleColumns()
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

        if (!hasColumn($tablePrefix . "RamUser", 'role')) 
        {
            echo "Adding the 'role' column for table: <code>RamUser</code>.<br>";
            flush();

            $qStr = "ALTER TABLE `{$tablePrefix}RamUser`
                        ADD COLUMN `role` TEXT NULL;";

            $q = new DBQuery();
            $q->prepare($qStr);
            $q->execute();
            $q->close();
        }

        echo "Updating email and role columns for table: <code>RamUser</code>.<br>";
        flush();

        $qStr = "SELECT `id`, `data`, `email`,`role` FROM `{$tablePrefix}RamUser`";
        $q = new DBQuery();
        $q->prepare($qStr);
        $q->execute();
        while ($entry = $q->fetch())
        {
            $email = $entry["email"] ?? "";
            $role = $entry["role"] ?? "";
            if (trim($email) != "" && trim($role) != "")
                continue;

            $id = (int)$entry["id"];
            $dataStr = $entry["data"];
            $data = json_decode(
                decrypt($dataStr),
                true
            );

            if($email == "")
                $email = $data["email"] ?? "";
            if($role == "")
                $role = $data["role"] ?? "standard";
            
            $email = encrypt($email);
            $role = encrypt($role);
            
            $qStr = "UPDATE `{$tablePrefix}RamUser` SET `email` = :email , `role` = :userRole
                     WHERE `id` = :id;";

            $q2 = new DBQuery();
            $q2->prepare($qStr);
            $q2->bindInt("id", $id);
            $q2->bindStr("email", $email);
            $q2->bindStr("userRole", $role);
            $q2->execute();
            $q2->close();
        }
        $q->close();
    }