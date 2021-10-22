<?php
    /*
		Ramses: Rx Asset Management System
        
        This program is licensed under the GNU General Public License.

        Copyright (C) 20202-2021 Nicolas Dufresne and Contributors.

        This program is free software;
        you can redistribute it and/or modify it
        under the terms of the GNU General Public License
        as published by the Free Software Foundation;
        either version 3 of the License, or (at your option) any later version.

        This program is distributed in the hope that it will be useful,
        but WITHOUT ANY WARRANTY; without even the implied warranty of
        MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
        See the GNU General Public License for more details.

        You should have received a copy of the *GNU General Public License* along with this program.
        If not, see http://www.gnu.org/licenses/.
	*/

    if ( acceptReply( "updateUser" ) )
    {
        $name = getArg( "name" );
        $shortName = getArg( "shortName" );
        $uuid = getArg( "uuid" );
        $role = getArg( "role" );
        $folderPath = getArg( "folderPath" );
        $comment = getArg( "comment" );
        $email = getArg( "email" );
        $color = getArg( "color", "#e3e3e3" );

        if ( isSelf($uuid) || isAdmin() )
        {
            $q = new DBQuery();
            $q->update(
                "users",
                array(
                    'shortName',
                    'name',
                    'role',
                    'folderPath',
                    'comment',
                    'email',
                    'color'
                ),
                $uuid
            );

            // hash the role
            $role = hashRole( $role );
            // encrypt data
            $name = encrypt( $name );
            $email = encrypt( $email );

            $q->bindStr( "name", $name );
            $q->bindShortName( $shortName );
            $q->bindEmail( $email );
            $q->bindStr( "color", $color );
            $q->bindStr( "role", $role );
            $q->bindStr( "folderPath", $folderPath );
            $q->bindStr( "comment", $comment );
            
            $q->execute("User \"{$shortName}\" updated.");
		    $q->close();
        }
    }
    else if (acceptReply("updatePassword"))
    {
        $current = getArg("current");
        $new = getArg("new");
        $uuid = getArg("uuid");

        $q = new DBQuery();

        $ok = false;
        //if self, we need to check the current password
        if ( isSelf($uuid) && strlen($current) > 0 )
        {
            //check password
            $r = $q->get('users', array('password'), $uuid);
            $ok = checkPassword( $current, $uuid, $r["password"] );
            if (!$ok)
            {
                $reply["message"] = "Wrong current password.";
                $reply["success"] = false;
            }
        }
        else if ( isSelf($uuid) ) // if self and no current password
        {
            $reply["message"] = "Missing current password.";
            $reply["success"] = false;
        }
        else if ( isAdmin() ) // if admin, no need for current password
        {
            $ok = true;
        }
        else // not self, not admin
        {
            $reply["message"] = "Insufficient rights, you need to be Admin to change other users' passwords.";
            $reply["success"] = false;
        }      

        //update password
        if ($ok)
        {
            //hash new password
            $new = hashPassword( $new, $uuid );

            $q = new DBQuery();
            $q->update(
                "users",
                array(
                    'password'
                ),
                $uuid
            );
           
            $q->bindStr( "password", $new );

            $q->execute("Password succesfully updated.");
		    $q->close();
        }

    }
    else if (hasArg("getUsers") || hasArg("init"))
    {
        if (hasArg("getUsers") )
        {
            $reply["accepted"] = true;
            $reply["query"] = "getUsers";    
        }
        
        $rep = $db->prepare("SELECT `name`,`shortName`,`folderPath`,`uuid`,`role`,`comment`,`email`,`color` FROM {$tablePrefix}users WHERE removed = 0;");
        $rep->execute();

        $users = Array();

        while ($user = $rep->fetch())
        {
            $u = Array();
			$u['name'] = decrypt( $user['name'] );
			$u['shortName'] = $user['shortName'];
			$u['comment'] = $user['comment'];
			$u['uuid'] = $user['uuid'];
			$u['folderPath'] = $user['folderPath'];
			$u['email'] = decrypt( $user['email'] );
			$u['role'] = checkRole( $user['role'] );
			$u['color'] = $user['color'];

			$users[] = $u;
        }

        $rep->closeCursor();

        if (hasArg("getUsers") )
        {
            $reply["content"] = $users;
            $reply["message"] = "Users list retrieved.";
            $reply["success"] = true;
        }
        else 
        {
            $reply["content"]["users"] = $users;
        }
    }
    else if ( acceptReply("createUser", 'admin') )
    {
        $name = getArg("name");
        $shortName = getArg("shortName");
        $email = getArg("email");
        $uuid = getArg("uuid", uuid());
        $password = getArg("password");
        $color = getArg( "color", "#e3e3e3" );

        // Ecnryption and hash
        $password = hashPassword($password, $uuid);
        $name = encrypt( $name );
        $email = encrypt( $email );

        $q = new DBQuery();
        $q->insert( "users", array( 'name', 'shortName', 'uuid', 'password', 'email', 'color' ));

        $q->bindStr( 'name', $name, true );
        $q->bindShortName( $shortName );
        $q->bindStr( "uuid", $uuid, true );
        $q->bindStr( "password", $password, true );
        $q->bindStr( "email", $email );
        $q->bindStr( "color", $color );

        $q->execute("User '{$shortName}' created.");
        $q->close();
    }
    else if ( acceptReply("removeUser", 'admin') )
    {
        $uuid = getArg("uuid");
        $q = new DBQuery();
		$q->remove( "users", $uuid );
    }
?>
