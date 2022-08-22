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
        flush();

        $ok = copy("ramses.sqlite", "../ramses_data");

        if (!$ok)
        {
            die( "Sorry, something went wrong while writing the database. Make sure the server has write access to its folder." );
        }

        echo ( "The new database is ready!<br />" );
        flush();
    }

    echo ( "Connecting to the database...<br />" );
    flush();

    include('../functions.php');
    include('../db.php');

    echo ( "Database found and working!<br />" );
    flush();

    setupTablePrefix();

    echo ( "Generating encryption keys...<br />" );
    flush();

    $encrypt_key = createEncryptionKey();
    $encrypt_key_txt = base64_encode($encrypt_key);
    echo( "This will be the encryption key for this server:<br /><strong>{$encrypt_key_txt}</strong><br />" );
    echo( "It's been saved in <code>config_security.php</code>. You may backup this file now.<br />" );
    flush();

    include('../config_security.php');

    // Set the DB if MySQL (if SQLite, the file is already available)
    if ($sqlMode != "sqlite")
    {
        echo ( "Writing the new database scheme (using MySQL)...<br />" );
        flush();

        // Create the RamUser Table
        $ok = createTable("RamUser");

        if ( !$ok )
        {
            die( "Sorry, something went wrong while writing the database." );
        }

        echo ( "Database tables are ready!<br />" );
    }

    echo( "Setuping the administrator user...<br />" );
    flush();
    
    //Setup admin user
    $uuid = uuid();
    //Prepare password
    $pswd = str_replace("/", "", $serverAddress) . "password" . $clientKey;
    $pswd = hash("sha3-512", $pswd);
    $pswd = hashPassword($pswd, $uuid);
    $data = encrypt("{\"name\":\"Administrator\",\"shortName\":\"Admin\",\"password\":\"{$pswd}\",\"comment\":\"The default Administrator user. Don't forget to rename it and change its password!\",\"color\":\"#b3b3b3\",\"role\":\"admin\"}");
    
    $q = new DBQuery();
    $qStr = "REPLACE INTO {$tablePrefix}RamUser ( `uuid`, `data` )
		VALUES ( :uuid, :data );
		COMMIT;";
    $q->prepare( $qStr );

    $q->bindStr('uuid', $uuid);
    $q->bindStr('data', $data);
    
    $q->execute("");
    $q->close();

    if ( !$q->isOK() )
    {
        echo( "Could not create the administrator, here's the error:<br />" );
        die( print_r($q->errorInfo(), true) );
    }
    
    echo ( "<p>Ramses has been correctly installed, you can now <strong>remove the <code>install</code> directory</strong>.</p><p>The default user is <strong>\"Admin\" with password <code>password</code></strong>.<br />Do not forget to change this name and password!</p>" );
?>
