<?php

	/*
		Rainbox Asset Manager
		Handles login requests
	*/

	if ($reply["type"] == "login")
	{
		$reply["accepted"] = true;

		$username = "";
		$password = "";

        $data = file_get_contents('php://input');
        if (isset($data["username"]) and isset($data["password"]))
        {
            $username = $data["username"];
			$password = $data["password"];
        }
        else
        {
            $data = json_decode(file_get_contents('php://input'));
            if ($data)
            {
                if (isset($data->{'username'})) $username = $data->{'username'};
				if (isset($data->{'password'})) $password = $data->{'password'};
            }
        }

		if (strlen($username) > 0 AND strlen($password) > 0)
		{
			//query the database
			$rep = $db->prepare("SELECT password,name,shortName,folderPath,uuid,role FROM " . $tablePrefix . "users WHERE shortName = :username ;");
			$rep->execute(array('username' => $username));
			$testPass = $rep->fetch();
			$rep->closeCursor();

			//check password
            //hash
            $password = hash("sha3-512", $testPass["uuid"] . $password );
			if ($testPass["password"] == $password)
			{
				$_SESSION["login"] = true;
				$content = array();
				$content["name"] = $testPass["name"];
				$content["shortName"] = $testPass["shortName"];
				$content["uuid"] = $testPass["uuid"];
				$content["folderPath"] = $testPass["folderPath"];
                $content["role"] = $testPass["role"];
				$reply["content"] = $content;
				$reply["message"] = "Successful login. Welcome " . $username . "!";
				$reply["success"] = true;
				echo json_encode($reply);
			}
			else
			{
				$_SESSION["login"] = false;
				$reply["message"] = "Invalid username or password";
				$reply["success"] = false;
				session_destroy();
				echo json_encode($reply);
			}
		}
		else
		{
			$_SESSION["login"] = false;
			$reply["message"] = "Invalid request, missing username or password";
			$reply["success"] = false;
			session_destroy();
			echo json_encode($reply);
		}
	}
?>
