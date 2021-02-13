<?php

	/*
		Rainbox Asset Manager
		Installs the SQL Database
	*/

    //connect to database
    include('../config.php');
    include('../db.php');

    $sql = file_get_contents('ramses_scheme.sql');

    echo "Ramses installed, you can now remove the <code>install</code> directory.<br />The default user is \"Admin\" with password \"password\".<br />Do not forget to change this name and password!";
?>