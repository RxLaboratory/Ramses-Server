<?php

	/*
		Rainbox Asset Manager
		Installs the SQL Database
	*/

    $__ROOT__ = dirname(dirname(__FILE__));

    echo ("Beginning installation...<br/>");
    flush();

    // global constants
	require_once($__ROOT__."/global.php");
    // config
    require_once($__ROOT__."/config/config.php");

    // Get current address
    /*$currentURL = $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"];
	$currentURL = explode("?", $currentURL)[0];
	$currentURL = explode("install/", $currentURL)[0];
	$currentURL = explode("install", $currentURL)[0];
	$serverAddress = $currentURL;*/

    //connect to database

    if ($sqlMode == "sqlite") // Copy the default db first
    {
        echo ( "Writing the new database scheme (using SQLite)...<br />" );
        flush();

        if (!is_dir($__ROOT__."/data")) mkdir($__ROOT__."/data");
        $ok = copy($__ROOT__."/install/ramses.sqlite", $__ROOT__."/data/ramses_data");

        if (!$ok)
        {
            die( "Sorry, something went wrong while writing the database. Make sure the server has write access to its folder." );
        }

        echo ( "The new database is ready!<br />" );
        flush();
    }

    echo ( "Connecting to the database...<br />" );
    flush();

    require_once($__ROOT__."/functions.php");
    require_once($__ROOT__."/db.php");

    echo ( "Database found and working!<br />" );
    flush();

    setupTablePrefix();

    echo ( "Generating encryption keys...<br />" );
    flush();

    $encrypt_key = createEncryptionKey();
    if ($encrypt_key === ""){
        echo("<strong>> Can't create the encryption key!</strong><br>");
        echo("Check the server has write permissions in the <code>condig</code> folder.");
        die();
    }
    $encrypt_key_txt = base64_encode($encrypt_key);
    echo( "The encryption key has been saved in <code>config/config_security.php</code>. You should backup this file now.<br />" );
    flush();

    echo ( "Generating server identifier...<br />" );
    flush();
    if (createServerUuid() != "") echo ( "The server universal has been saved.<br />" );
    else echo( "<strong>Warning</strong>: The server UUID can't be generated.<br />");
    flush();

    include($__ROOT__."/config/config_security.php");
    include($__ROOT__."/config/config_server_uuid.php");

    // Set the DB if MySQL (if SQLite, the file is already available)
    if ($sqlMode != "sqlite")
    {
        echo ( "Writing the new database scheme (using MySQL)...<br />" );
        flush();

        // Create the RamUser Table
        createTable("RamUser", true);

        // Add username and password rows
        $q = new DBQuery();
        $q->prepare("ALTER TABLE `{$tablePrefix}RamUser`
            ADD  `userName` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL AFTER `uuid`,
            ADD  `password` TEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL AFTER `userName`;
            ");

        $q->execute();
        $q->close();

        if ( !$q->isOK() )
        {
            die( "Sorry, something went wrong while writing the database." );
        }

        // Create the deletedData table
                

        echo ( "Database tables are ready!<br />" );
    }
    // Rename the user table to use the prefix
    else
    {
        echo( "Preparing the database...<br />");
        $q = new DBQuery();
        $q->prepare("ALTER TABLE `RamUser` RENAME TO `{$tablePrefix}RamUser`;");
        $q->execute();
        $q->close();
        if (!$q->isOK()) echo( "Something went wrong: Can't rename the users table.<br />");
        else echo( "Successfully prepared the database.<br />");
    }

    echo( "Setuping the administrator user...<br />" );
    flush();
    
    //Setup admin user
    $uuid = uuid();
    //Prepare password
    cleanServerAddress();
    $pswd = str_replace("/", "", $serverAddress) . "password" . $clientKey;
    $pswd = hash("sha3-512", $pswd);
    $pswd = hashPassword($pswd, $uuid);
    $data = encrypt("{\"name\":\"Administrator\",\"shortName\":\"Admin\",\"comment\":\"The default Administrator user. Don't forget to rename it and change its password!\",\"color\":\"#b3b3b3\",\"role\":\"admin\"}");
    
    $q = new DBQuery();
    $qStr = "REPLACE INTO `{$tablePrefix}RamUser` ( `uuid`, `userName`, `password`, `data`, `modified` )
		VALUES ( :uuid, 'Admin', :password, :data, '1970-01-01 12:00:00' );
		COMMIT;";
    $q->prepare( $qStr );

    $q->bindStr('uuid', $uuid);
    $q->bindStr('data', $data);
    $q->bindStr('password', $pswd);
    
    $q->execute();
    $q->close();

    if ( !$q->isOK() )
    {
        echo( "Could not create the administrator, here's the error:<br />" );
        die( print_r($q->errorInfo(), true) );
    }
    
    echo ( "<p>Ramses has been correctly installed, you can now <strong>remove the <code>install</code> directory</strong>.</p><p>The default user is <strong>\"Admin\" with password <code>password</code></strong>.<br />Do not forget to change this name and password!</p>" );
?>
