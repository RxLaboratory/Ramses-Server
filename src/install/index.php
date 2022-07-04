<?php

	/*
		Rainbox Asset Manager
		Installs the SQL Database
	*/

    include('../config.php');

    //connect to database

    if ($sqlMode == "sqlite") // Copy the default db first
    {
        echo ( "Writing the new database scheme (using SQLite)...<br />" );

        $ok = copy("ramses.sqlite", "../ramses_data");

        if (!$ok)
        {
            die( "Sorry, something went wrong while writing the database. Make sure the server has write access to its folder." );
        }

        echo ( "The new database is ready!<br />" );
    }

    echo ( "Connecting to the database...<br />" );

    include('../functions.php');
    include('../db.php');

    echo ( "Database found and working!<br />" );

    setupTablePrefix();

    echo ( "Generating encryption keys...<br />" );

    $encrypt_key = createEncryptionKey();
    $encrypt_key_txt = base64_encode($encrypt_key);
    echo( "This will be the encryption key for this server:<br /><strong>{$encrypt_key_txt}</strong><br />" );
    echo( "It's been saved in <code>config_security.php</code>. You may backup this file now.<br />" );

    include('../config_security.php');

    // Set the DB if MySQL (if SQLite, the file is already available)
    if ($sqlMode != "sqlite")
    {
        echo ( "Writing the new database scheme (using MySQL)...<br />" );

        $sql = file_get_contents('ramses_scheme.sql');
        // Run the installer SQL Script
        $qr = $db->exec($sql);
        
        if ( $qr === false )
        {
            echo( "Sorry, something went wrong while writing the database. Here's the error:<br />" );
            die( print_r($db->errorInfo(), true) );
        }

        echo ( "Database tables are ready!<br />" );

        echo ( "Inserting default data...<br />" );

        $sql = file_get_contents('ramses_data.sql');
        // Run the data SQL Script
        $qr = $db->exec($sql);
        
        if ( $qr === false )
        {
            echo( "Sorry, something went wrong while writing the database. Here's the error:<br />" );
            die( print_r($db->errorInfo(), true) );
        }

        echo ( "The new data is ready!<br />" );
    }

    echo( "Setuping the administrator user...<br />" );

    //Setup admin user
    $uuid = uuid();
    $shortName = "Admin";
    $name = encrypt("Administrator");
    $email = encrypt("");
    //Prepare password
    $pswd = str_replace("/", "", $serverAddress) . "password" . $clientKey;
    $pswd = hash("sha3-512", $pswd);
    $pswd = hashPassword($pswd, $uuid);
    $comment = "The default Administrator user. Don't forget to rename it and change its password!";
    $role = hashRole('admin');
   
    $qString = "REPLACE INTO
        {$tablePrefix}users (
            `name`,
            `shortName`,
            `uuid`,
            `password`,
            `role`,
            `comment`,
            `email` )
        VALUES (
            :name ,
            :shortName ,
            :uuid,
            :password,
            :role,
            :comment,
            :email );
        COMMIT;";

    $rep = $db->prepare($qString);
    $rep->bindValue(':uuid', $uuid, PDO::PARAM_STR);
    $rep->bindValue(':name', $name, PDO::PARAM_STR);
    $rep->bindValue(':shortName', $shortName, PDO::PARAM_STR);
    $rep->bindValue(':password', $pswd, PDO::PARAM_STR);
    $rep->bindValue(':comment', $comment, PDO::PARAM_STR);
    $rep->bindValue(':role', $role, PDO::PARAM_STR);
    $rep->bindValue(':email', $email, PDO::PARAM_STR);

    $ok = $rep->execute();
    $rep->closeCursor();

    if (!$ok)
    {
        echo( "Could not create the administrator, here's the error:<br />" );
        die( print_r($db->errorInfo(), true) );
    }
    
    echo ( "<p>Ramses has been correctly installed, you can now <strong>remove the <code>install</code> directory</strong>.</p><p>The default user is <strong>\"Admin\" with password \"password\"</strong>.<br />Do not forget to change this name and password!</p>" );
?>