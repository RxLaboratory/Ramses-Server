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
        $q = new DBQuery();
        $q->prepare("
                        DROP TABLE IF EXISTS `{$tablePrefix}RamUser`;
                        CREATE TABLE `{$tablePrefix}RamUser` (
                            `id` int(11) NOT NULL,
                            `uuid` varchar(36) COLLATE utf8_unicode_ci NOT NULL,
                            `userName` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'NEW',
                            `data` text NOT NULL,
                            `modified` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
                            `removed` tinyint(4) NOT NULL DEFAULT 0
                            ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
                        ALTER TABLE `{$tablePrefix}RamUser`
                            ADD PRIMARY KEY (`id`),
                            ADD UNIQUE KEY `uuid` (`uuid`);
                        ALTER TABLE `{$tablePrefix}RamUser`
                            MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
                    ");

        $q->execute();
        $q->close();
        
        if ( !$q->isOK() )
        {
            echo( "Sorry, something went wrong while writing the database. Here's the error:<br />" );
            die( print_r($q->errorInfo(), true) );
        }

        echo ( "Database tables are ready!<br />" );
    }

    echo( "Setuping the administrator user...<br />" );
    flush();
    
    //Setup admin user
    $uuid = uuid();
    $username = "Admin";
    //Prepare password
    $pswd = str_replace("/", "", $serverAddress) . "password" . $clientKey;
    $pswd = hash("sha3-512", $pswd);
    $pswd = hashPassword($pswd, $uuid);
    $data = encrypt("{\"name\":\"Administrator\",\"password\":\"{$pswd}\",\"comment\":\"The default Administrator user. Don't forget to rename it and change its password!\",\"color\":\"#b3b3b3\",\"role\":\"admin\"}");
    
    $q = new DBQuery();
    $qStr = "REPLACE INTO {$tablePrefix}RamUser ( `uuid`, `userName`, `data` )
		VALUES ( :uuid, :userName, :data );
		COMMIT;";
    $q->prepare( $qStr );

    $q->bindStr('uuid', $uuid);
    $q->bindStr('userName', $username);
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
