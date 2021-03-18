<?php

	/*
		Rainbox Asset Manager
		Installs the SQL Database
	*/

    //connect to database
    include('../config.php');
    include('../db.php');
    include('../functions.php');

    setupTablePrefix();

    $sql = file_get_contents('ramses_scheme.sql');

    $qr = $db->exec($sql);
    $qr;

    //Setup admin user
    $uuid = "bVda5hjqDNLFJia9DCmwwH2p";
    $shortName = "Admin";
    $name = "Administrator";
    $pswd = hashPassword("0b17bfa7938d75031d1754ab56c27062d967e92ca04f2ba5b4ebf920528936b95f9a9fc96a2ef8fb921463cd97aa94026079891f6f4c6e273ce5956c9da72c92", $uuid);
    $qString = "INSERT INTO " . $tablePrefix . "users (name,shortName,uuid,password,role) VALUES ( :name , :shortName , :uuid, :password, 'admin' ) ON DUPLICATE KEY UPDATE shortName = VALUES(shortName), name = VALUES(name);";
    $values = array('name' => $name,'shortName' => $shortName, 'password' => $pswd, 'uuid' => $uuid);
    $rep = $db->prepare($qString);
    $rep->execute($values);
    $rep->closeCursor();
    

    echo "Ramses installed, you can now remove the <code>install</code> directory.<br />The default user is \"Admin\" with password \"password\".<br />Do not forget to change this name and password!";
?>