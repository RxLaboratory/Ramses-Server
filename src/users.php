<?php
    /*
        Rainbox Asset Manager
        User Management
    */
    // ========= UPDATE STATUS ==========
    if ($reply["type"] == "updateUser")
    {
        $reply["accepted"] = true;

        $userName = "";
        $firstName = "";
        $lastName = "";
        $password = "";
        $uuid = "";

        $data = json_decode(file_get_contents('php://input'));
        if ($data)
        {
            if(isset($data->{'userName'})) $userName = $data->{'userName'};
            if(isset($data->{'firstName'})) $firstName = $data->{'firstName'};
            if(isset($data->{'lastName'})) $lastName = $data->{'lastName'};
            if(isset($data->{'password'})) $password = $data->{'password'};
            if(isset($data->{'uuid'})) $uuid = $data->{'uuid'};

        }

        if (strlen($userName) > 0 AND strlen($uuid) > 0 AND strlen($password) > 0)
        {
            $qString = "UPDATE " . $tablePrefix . "users SET userName= :userName ,firstName= :firstName ,lastName= :lastName ,password= :password WHERE uuid= :uuid ;";

            $rep = $db->prepare($qString);
            $rep->execute(array('userName' => $userName, 'firstName' => $firstName, 'lastName' => $lastName, 'password' => $password, 'uuid' => $uuid));
            $rep->closeCursor();

            $reply["message"] = "User " . $userName . " updated.";
            $reply["success"] = true;
        }
        else
        {
            $reply["message"] = "Invalid request, missing values";
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
