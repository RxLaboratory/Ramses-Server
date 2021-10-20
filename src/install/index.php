<?php

	/*
		Rainbox Asset Manager
		Installs the SQL Database
	*/

    //connect to database

    echo ( "Connecting to the database...<br />" );

    include('../config.php');
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

    echo ( "Writing the new database scheme...<br />" );

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

    //Setup admin user
    $uuid = uuid();
    $shortName = "Admin";
    $name = encrypt("Administrator");
    $email = encrypt("");
    $pswd = hashPassword("0b17bfa7938d75031d1754ab56c27062d967e92ca04f2ba5b4ebf920528936b95f9a9fc96a2ef8fb921463cd97aa94026079891f6f4c6e273ce5956c9da72c92", $uuid);   
    $comment = "The default Administrator user. Don't forget to rename it and change its password!";
    $role = hashRole('admin');
   
    $qString = "INSERT INTO
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