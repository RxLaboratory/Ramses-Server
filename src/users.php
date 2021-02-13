<?php
    /*
        Rainbox Asset Manager
        User Management
    */
    // ========= UPDATE STATUS ==========
    if ($reply["type"] == "updateUser")
    {
        $reply["accepted"] = true;

        $name = "";
        $shortName = "";
        $uuid = "";

        $data = json_decode(file_get_contents('php://input'));
        if ($data)
        {
            if(isset($data->{'name'})) $name = $data->{'name'};
            if(isset($data->{'shortName'})) $shortName = $data->{'shortName'};
            if(isset($data->{'uuid'})) $uuid = $data->{'uuid'};
        }

        if (strlen($shortName) > 0 AND strlen($uuid) > 0)
        {
            $qString = "UPDATE " . $tablePrefix . "users SET shortName= :shortName ,name= :name WHERE uuid= :uuid ;";

            $rep = $db->prepare($qString);
            $rep->execute(array('shortName' => $shortName, 'name' => $name, 'uuid' => $uuid));
            $rep->closeCursor();

            $reply["message"] = "User \"" . $shortName . "\" updated.";
            $reply["success"] = true;
        }
        else
        {
            $reply["message"] = "Invalid request, missing values";
            $reply["success"] = false;
        }

    }
    else if ($reply["type"] == "updatePassword")
    {
        $reply["accepted"] = true;

        $current = "";
        $new = "";
        $uuid = "";

        $data = json_decode(file_get_contents('php://input'));
        if ($data)
        {
            if(isset($data->{'current'})) $current = $data->{'current'};
            if(isset($data->{'new'})) $new = $data->{'new'};
            if(isset($data->{'uuid'})) $uuid = $data->{'uuid'};
        }

        if (strlen($new) > 0 AND strlen($current) > 0 AND strlen($uuid) > 0)
        {
            //check password
            $rep = $db->prepare("SELECT password FROM " . $tablePrefix . "users WHERE uuid= :uuid ;");
            $rep->execute(array('uuid' => $uuid));
            $testPass = $rep->fetch();
            $rep->closeCursor();

            //check password
            if ($testPass["password"] == $current)
            {
                $qString = "UPDATE " . $tablePrefix . "users SET password= :new WHERE uuid= :uuid AND password= :current ;";

                $rep = $db->prepare($qString);
                $rep->execute(array('new' => $new, 'current' => $current, 'uuid' => $uuid));
                $rep->closeCursor();

                $reply["message"] = "Password succesfully updated.";
                $reply["success"] = true;
            }
            else
            {
                $reply["message"] = "Wrong current password.";
                $reply["success"] = false;
            }
        }
        else
        {
            $reply["message"] = "Invalid request, missing values.";
            $reply["success"] = false;
        }
    }
    else if ($reply["type"] == "getUsers")
    {
        $reply["accepted"] = true;
        
        $rep = $db->prepare("SELECT name,shortName,folderPath,uuid,role FROM " . $tablePrefix . "users ;");
        $rep->execute();

        $users = Array();

        while ($user = $rep->fetch())
        {
            $u = Array();
			$u['name'] = $user['name'];
			$u['shortName'] = $user['shortName'];
			$u['uuid'] = $user['uuid'];
			$u['folderPath'] = $user['folderPath'];
			$u['role'] = $user['role'];

			$users[] = $u;
        }

        $rep->closeCursor();

		$reply["content"] = $users;
		$reply["message"] = "Users list retrieved.";
		$reply["success"] = true;
    }

?>
