<?php

	/*
		Rainbox Asset Manager
		Installs the SQL Database
	*/

    define('RAMROOT',dirname(__FILE__));

    echo ("Beginning installation...<br/>");
    flush();

    // global constants
	require_once(RAMROOT."/global.php");
    // config
    require_once(RAMROOT."/config/config.php");

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

        if (!is_dir(RAMROOT."/data")) mkdir(RAMROOT."/data");
        $ok = copy(RAMROOT."/install/ramses.sqlite", RAMROOT."/data/ramses_data");

        if (!$ok)
        {
            die( "Sorry, something went wrong while writing the database. Make sure the server has write access to the data folder." );
        }

        echo ( "The new database is ready!<br />" );
        flush();
    }

    echo ( "Connecting to the database...<br />" );
    flush();

    require_once(RAMROOT."/functions.php");
    require_once(RAMROOT."/db.php");

    echo ( "Database found and working!<br />" );
    flush();

    setupTablePrefix();

    echo ( "Generating encryption keys...<br />" );
    flush();

    $encrypt_key = createEncryptionKey();
    if ($encrypt_key === ""){
        echo("<strong>> Can't create the encryption key!</strong><br>");
        echo("Check if the server has write permissions in the <code>config</code> folder.");
        die();
    }
    $encrypt_key_txt = base64_encode($encrypt_key);
    echo( "The encryption key has been saved in <code>config/config_security.php</code>. You should backup this file now.<br />" );
    flush();

    echo ( "Generating server identifier...<br />" );
    flush();
    if (createServerUuid() != "") echo ( "The server universal id has been saved.<br />" );
    else echo( "<strong>Warning</strong>: The server UUID can't be generated.<br />");
    flush();

    include(RAMROOT."/config/config_security.php");
    include(RAMROOT."/config/config_server_uuid.php");

    // Set the DB
    echo ( "Writing the new database scheme (using MySQL)...<br />" );
    flush();

    // Create the needed tables
    createTable("RamProject", true);
    createUserTable(true);
    createProjectUserTable(true);

    if ( !$q->isOK() )
        die( "Sorry, something went wrong while writing the database." );

    echo ( "Database tables are ready!<br />" );
    flush();

    //Setup admin user
    echo( "Setuping the administrator user...<br />" );
    flush();

    $uuid = uuid();
    //Prepare password
    cleanServerAddress();
    $clearPassword = generatePassword(5);
    $pswd = preHashPassword($clearPassword);
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
    
    // Send an email to the admin
    sendMail(
        EMAIL_ADMIN,
        "Ramses Administrator",
        "Your Ramses server has been installed",
"Saluton!

Ramses has been correctly installed, you can now remove the 'install' directory.

The default user is \"Admin\" with password:
$clearPassword

Do not forget to change this name and password!

Koran dankon,
Your new Ramses Server."
    );

    echo (
        "<p>Ramses has been correctly installed, you can now <strong>remove the <code>install</code> directory</strong>.</p>
        <p>The default user is <strong>\"Admin\" with password <code>$clearPassword</code></strong>.
        <br />Do not forget to change this name and password!</p>"
        );
?>
