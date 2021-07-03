<?php
	
	/*
		Rainbox Asset Manager
		Database access
	*/

	try
	{
		$db = new PDO('mysql:host=' . $sqlHost . ';port=' . $sqlPort . ';dbname=' . $sqlDBName . ';charset=utf8', $sqlUser, $sqlpassword,array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'"));
	}
	catch (Exception $e)
	{
		echo "Oops, something went wrong with the database. Here's the error: <br />";
		die('Error: ' . $e->getMessage());
	}
?>