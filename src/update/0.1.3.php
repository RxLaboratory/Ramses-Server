<?php
    if ( $currentVersion == '0.1.3-alpha' )
    {
        // ==== Encryption keys ====

        echo ( " ▸ Generating new encryption key.<br />" );

        $encrypt_key = createEncryptionKey();
        $encrypt_key_txt = base64_encode($encrypt_key);
        echo( "This will be the encryption key for this server:<br /><strong>{$encrypt_key_txt}</strong><br />" );
        echo( "It's been saved in <code>config_security.php</code>. You may backup this file now.<br />" );

        echo ( "     ▪ OK!<br />" );

        ob_flush();
        flush();

        // ==== Update Table Structure ====

        echo ( " ▸ Updating user table.<br />" );
        
        $rep = $db->query( "LOCK TABLES {$usersTable} WRITE;
            ALTER TABLE {$usersTable}
            ADD COLUMN `email` VARCHAR(255) NULL AFTER `password`,
            CHANGE COLUMN `role` `role` VARCHAR(255) NOT NULL DEFAULT 'standard',
            CHANGE COLUMN `shortName` `shortName` VARCHAR(255) NOT NULL ;
            UNLOCK TABLES;");
        
        $ok = $rep->execute();
        $rep->closeCursor();

        if (!$ok)
        {
            echo( "    ▫ Failed. Could not update data, here's the error:<br />" );
            die( print_r($db->errorInfo(), true) );
        }

        echo ( "     ▪ OK!<br />" );

        ob_flush();
        flush();

        // ==== Update Data ====

        echo ( " ▸ Encrypting user data.<br />" );
        
        ob_flush();
        flush();

        // get users
        $rep = $db->query( "SELECT `id`, `role`, `name`, `shortname`, `email` FROM {$usersTable};" );
        
        while ($user = $rep->fetch())
		{
            $role = checkRole( $user['role'] );
            $role = hashRole( $role );

            $name = $user['name'];
            $shortname = $user['shortname'];
            $email = $user['email'];

            // Only if not already encrypted
            if( !isEncrypted( $shortname ) )
            {
                $name = encrypt( $name );
                $shortname = encrypt( $shortname );
                $email = encrypt( $email );
            }

            $id = (int)$user['id'];

            $roleQuery = $db->prepare("UNLOCK TABLES;
                UPDATE {$usersTable}
                SET `role`= :role, `name`= :name, `shortname`= :shortname, `email`= :email
                WHERE `id`= :id;" );

            $roleQuery->bindValue(':role', $role, PDO::PARAM_STR);
            $roleQuery->bindValue(':name', $name, PDO::PARAM_STR);
            $roleQuery->bindValue(':shortname', $shortname, PDO::PARAM_STR);
            $roleQuery->bindValue(':email', $email, PDO::PARAM_STR);
            $roleQuery->bindValue(':id', $id, PDO::PARAM_INT);
            $ok = $roleQuery->execute();
            $roleQuery->closeCursor();

            if (!$ok)
            {
                echo( "    ▫ Failed. Could not update data, here's the error:<br />" );
                die( print_r($db->errorInfo(), true) );
            }

            echo ( "." );
            ob_flush();
            flush();
        }

        echo ( "<br />" );
        echo ( "     ▪ OK!<br />" );

        // Ready for the next step
        $currentVersion = '0.2.0-alpha';
    }
?>