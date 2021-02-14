<?php
	//configuration and init
	include ("../config.php");
	include ("../init.php");
	include ("../functions.php");

    //connect to database
    include('../db.php');

    //Set a password to a specific user
    if (isset($_GET["setPassword"]))
    {
        $username = $_GET["username"];
        $password = $_GET["password"];

        //query the database for the user uuid
        $rep = $db->prepare("SELECT uuid FROM " . $tablePrefix . "users WHERE shortName = :username ;");
        $rep->execute(array('username' => $username));
        $testPass = $rep->fetch();
        $rep->closeCursor();

        //hash password (official ramses client side)
        $password = hash(sha3-512, $password . $username );
        //hash password (server side)
        $uuid = $testPass["uuid"];
        $password = hashPassword( $password, $uuid );

        //set in the database
        $rep = $db->prepare("UPDATE " . $tablePrefix . "users SET password = :password WHERE uuid= :uuid ;" );
        $ok = $rep->execute(array('uuid' => $uuid, 'password' => $password));
        //$rep->debugDumpParams();
        $rep->closeCursor();

        //echo the new hashed password
        echo "UUID: " . $uuid . "<br />";
        echo $password;
    }
    else if (isset($_GET["login"]))
    {
        $username = "";
		$password = "";

		if (isset($_GET["username"])) $username = $_GET["username"];
		if (isset($_GET["password"])) $password = $_GET["password"];

		if (strlen($username) > 0 AND strlen($password) > 0)
		{
			//query the database
			$rep = $db->prepare("SELECT password,name,shortName,folderPath,uuid,role FROM " . $tablePrefix . "users WHERE shortName = :username ;");
			$rep->execute(array('username' => $username));
			$testPass = $rep->fetch();
			$rep->closeCursor();

			//check password
            //hash password (official ramses client side)
            $password = hash(sha3-512, $password . $username );
            //hash (server side)
			$uuid = $testPass["uuid"];
            $password = hashPassword( $password, $uuid );

			if ($testPass["password"] == $password)
			{
				$token = login();
				echo "token:<br />" . $token;
			}
			else
			{
				echo "Invalid username or password";
			}
		}
    }
    else
    {
        echo "Nothing to do.";
    }
?>