<?php

	/*
		Rainbox Asset Manager
		Installs the SQL Database
	*/

    //connect to database
    include('../config.php');
    include('../db.php');

    $sql = file_get_contents('ramses_scheme.sql');
    $qr = $db->exec($sql);

    echo "Ramses installed, you can now remove the <code>install</code> directory.";
?>