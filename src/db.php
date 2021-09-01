<?php
	
	/*
		Rainbox Asset Manager
		Database access
	*/

	try
	{
		if ( $sqlMode == 'mysql' )
			$db = new PDO('mysql:host=' . $sqlHost . ';port=' . $sqlPort . ';dbname=' . $sqlDBName . ';charset=utf8', $sqlUser, $sqlpassword,array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'"));
		else if ( $sqlMode == 'sqlite' )
		{
			// Creat sqlite folder
			if ( !is_dir( 'db' ) ) {
				mkdir( 'db', 0600 );
			}
			$db = new PDO( 'sqlite:' . __DIR__ . '/db/db.sqlite' );
		}
		else
		{
			die ( "Sorry, unknown database mode: " . $sqlMode );
		}
	}
	catch (Exception $e)
	{
		echo ("Oops, something went wrong with the database. Here's the error: <br />");
		die('Error: ' . $e->getMessage());
	}
?>