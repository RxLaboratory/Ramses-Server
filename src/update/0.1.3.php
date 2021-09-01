<?php
    if ( $currentVersion == '0.1.3-alpha' )
    {
        // ==== Update Table Structure ====

        echo ( " ▸ Updating user table.<br />" );
        
        $rep = $db->query( "ALTER TABLE `ramses`.`ram_users` 
            CHANGE COLUMN `role` `role` VARCHAR(255) CHARACTER SET 'utf8' COLLATE 'utf8_unicode_ci' NOT NULL DEFAULT 'standard' ;");
        
        $ok = $rep->execute();
        $rep->closeCursor();

        if (!$ok)
        {
            echo( "    ▫ Failed. Could not update data, here's the error:<br />" );
            die( print_r($db->errorInfo(), true) );
        }


        // ==== Update Data ====

        echo ( " ▸ Encrypting user roles.<br />" );

        // get users
        $rep = $db->query( "SELECT `id`, `role` FROM {$usersTable};" );
        
        while ($user = $rep->fetch())
		{
            $role = checkRole( $user['role'] );
            $role = hashRole( $role );
            $id = (int)$user['id'];            

            $roleQuery = $db->prepare("UPDATE {$usersTable}
                SET `role`= :role
                WHERE `id`= :id;" );

            $roleQuery->bindValue(':role', $role, PDO::PARAM_STR);
            $roleQuery->bindValue(':id', $id, PDO::PARAM_INT);
            $ok = $roleQuery->execute();
            $roleQuery->closeCursor();

            if (!$ok)
            {
                echo( "    ▫ Failed. Could not update data, here's the error:<br />" );
                die( print_r($db->errorInfo(), true) );
            }
        }

        echo ( "     ▪ OK!<br />" );

        // Ready for the next step
        $currentVersion = '0.2.0-alpha';
    }
?>