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

    if (hasArg("updateUser"))
    {
        acceptReply( "updateUser" );

        $name = getArg( "name" );
        $shortName = getArg( "shortName" );
        $uuid = getArg( "uuid" );
        $role = getArg( "role" );
        $folderPath = getArg( "folderPath" );
        $comment = getArg( "comment" );

        if ( checkArgs( array( $shortName, $uuid ) ) && ( isSelf($uuid) || isAdmin() ) )
        {
            // Only if self or admin
            if ( isSelf($uuid) || isAdmin() )
            {
                $qString = "UPDATE {$usersTable}
                    SET
                        `shortName` = :shortName,
                        `name` = :name,
                        `role` = :role,
                        `folderPath` = :folderPath,
                        `comment` = :comment
                    WHERE `uuid` = :uuid;";
               
                $rep = $db->prepare($qString);
                $rep->bindValue(':uuid', $uuid, PDO::PARAM_STR);
                $rep->bindValue(':shortName', $shortName, PDO::PARAM_STR);
                $rep->bindValue(':name', $name, PDO::PARAM_STR);
                $rep->bindValue(':role', $role, PDO::PARAM_STR);
                $rep->bindValue(':folderPath', $folderPath, PDO::PARAM_STR);
                $rep->bindValue(':comment', $comment, PDO::PARAM_STR);

                sqlRequest( $rep,  "User {$shortName} updated." );

                $rep->closeCursor();
            }
        }
    }
    else if (hasArg("updatePassword"))
    {
        $reply["accepted"] = true;
        $reply["query"] = "updatePassword";

        $current = "";
        $new = "";
        $uuid = "";

        $current = getArg("current");
        $new = getArg("new");
        $uuid = getArg("uuid");

        if (strlen($new) > 0 AND strlen($uuid) > 0)
        {
            $ok = false;
            //if self, we need to check the current password
            if ( isSelf($uuid) && strlen($current) > 0 )
            {
                //check password
                $rep = $db->prepare("SELECT password FROM " . $tablePrefix . "users WHERE uuid= :uuid ;");
                $rep->execute(array('uuid' => $uuid));
                $testPass = $rep->fetch();
                $rep->closeCursor();

                $current = $uuid . $current;

                $ok = password_verify($current, $testPass["password"]);
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

                $qString = "UPDATE " . $tablePrefix . "users SET password= :new WHERE uuid= :uuid ;";
                $rep = $db->prepare($qString);
                $rep->execute(array('new' => $new, 'uuid' => $uuid));
                $rep->closeCursor();

                $reply["message"] = "Password succesfully updated.";
                $reply["success"] = true;
            }
        }
        else
        {
            $reply["message"] = "Invalid request, missing values.";
            $reply["success"] = false;
        }
    }
    else if (hasArg("getUsers") || hasArg("init"))
    {
        if (hasArg("getUsers") )
        {
            $reply["accepted"] = true;
            $reply["query"] = "getUsers";    
        }
        
        $rep = $db->prepare("SELECT `name`,`shortName`,`folderPath`,`uuid`,`role`,`comment` FROM " . $tablePrefix . "users WHERE removed = 0;");
        $rep->execute();

        $users = Array();

        while ($user = $rep->fetch())
        {
            $u = Array();
			$u['name'] = $user['name'];
			$u['shortName'] = $user['shortName'];
			$u['comment'] = $user['comment'];
			$u['uuid'] = $user['uuid'];
			$u['folderPath'] = $user['folderPath'];
			$u['role'] = $user['role'];

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
    else if (hasArg("createUser"))
    {
        $reply["accepted"] = true;
        $reply["query"] = "createUser";

        $name = "";
        $shortName = "";
        $uuid = "";
        $password = "";

        $name = getArg("name");
        $shortName = getArg("shortName");
        $uuid = getArg("uuid");
        $password = getArg("password");

        if (strlen($shortName) > 0 and strlen($password) > 0)
        {
            // Only if admin
            if ( isAdmin() )
            {
                $password = hashPassword($password, $uuid);

                if (strlen($uuid) > 0)
                {
                    $qString = "INSERT INTO " . $tablePrefix . "users (name,shortName,uuid,password)
                        VALUES ( :name , :shortName , :uuid, :password )
                        ON DUPLICATE KEY UPDATE shortName = VALUES(shortName), name = VALUES(name), removed = 0, uuid = VALUES(uuid);";
                    $values = array('name' => $name,'shortName' => $shortName, 'uuid' => $uuid, 'password' => $password);
                }
                else
                {
                    $qString = "INSERT INTO " . $tablePrefix . "users (name,shortName,uuid,password)
                        VALUES ( :name , :shortName , uuid(), :password )
                        ON DUPLICATE KEY UPDATE shortName = VALUES(shortName), name = VALUES(name), removed = 0, uuid = VALUES(uuid);";
                    $values = array('name' => $name,'shortName' => $shortName, 'password' => $password);
                }
    
                $rep = $db->prepare($qString);
                $rep->execute($values);
                $rep->closeCursor();         
    
                $reply["message"] = "User \"" . $shortName . "\" created.";
                $reply["success"] = true;
            }
            else
            {
                $reply["message"] = "Insufficient rights, you need to be Admin to create users.";
                $reply["success"] = false;
            }

            
        }
        else
        {
            $reply["message"] = "Invalid request, missing values";
            $reply["success"] = false;
        }
    }
    else if (hasArg("removeUser"))
    {
        $reply["accepted"] = true;
        $reply["query"] = "createUser";

        $uuid = "";

        $uuid = getArg("uuid");

        if (strlen($uuid) > 0)
        {
            //only if admin
            if (isAdmin())
            {
                $rep = $db->prepare("UPDATE " . $tablePrefix . "users SET removed = 1 WHERE uuid= :uuid ;");
                $rep->execute(array('uuid' => $uuid));
                $rep->closeCursor();
    
                $reply["message"] = "User " . $uuid . " removed.";
                $reply["success"] = true;
            }
            else
            {
                $reply["message"] = "Insufficient rights, you need to be Admin to remove users.";
                $reply["success"] = false;
            }
            
        }
        else
        {
            $reply["message"] = "Invalid request, missing values";
            $reply["success"] = false;
        }
    }

?>
