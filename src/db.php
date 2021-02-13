<?php
	
	/*
		Rainbox Asset Manager
		Database access
	*/

	try
	{
		$db = new PDO('mysql:host=' . $sqlHost . ';dbname=' . $sqlDBName . ';charset=utf8', $sqlUser, $sqlpassword,array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
	}
	catch (Exception $e)
	{
		echo "Oops";
		die('Error : ' . $e->getMessage());
	}
?>