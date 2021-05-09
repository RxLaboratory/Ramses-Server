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

    if (isset($_GET["updateUser"]))
    {
        $reply["accepted"] = true;
        $reply["query"] = "updateUser";

        $name = getArg( "name" );
        $shortName = getArg( "shortName" );
        $uuid = getArg( "uuid" );
        $role = getArg( "role" );
        $folderPath =getArg( "folderPath" );

        if ( $shortName != "" && $uuid != "")
        {
            // Only if self or admin
            if ( isSelf($uuid) || isAdmin() )
            {
                $qString = "INSERT INTO {$usersTable} (`shortName`, `name`, `role`, `folderPath`, `uuid`, `password`)
                    VALUES(
                        :shortName,
                        :name,
                        :role,
                        :folderPath,
                        :uuid,
                        ''
                        )
                    AS newUser
                    ON DUPLICATE KEY UPDATE
                        `name` = newUser.`name`,
                        `role` = newUser.`role`,
                        `folderPath` = newUser.`folderPath`,
                        `removed` = 0;
                    UPDATE {$usersTable}
                    SET `shortName` = :shortName
                    WHERE `uuid` = :uuid;";

                $values = array('shortName' => $shortName, 'name' => $name, 'uuid' => $uuid, 'role' => $role, 'folderPath' => $folderPath);

                $rep = $db->prepare($qString);
                $rep->execute($values);
                $rep->closeCursor();

                $reply["message"] = "User \"" . $shortName . "\" updated.";
                $reply["success"] = true;
            }
            else
            {
                $reply["message"] = "Insufficient rights, you need to be Admin to update other users.";
                $reply["success"] = false;
            }
        }
        else
        {
            $reply["message"] = "Invalid request, missing values";
            $reply["success"] = false;
        }

    }
    else if (isset($_GET["updatePassword"]))
    {
        $reply["accepted"] = true;
        $reply["query"] = "updatePassword";

        $current = "";
        $new = "";
        $uuid = "";

        if (isset($_GET["current"])) $current = $_GET["current"];
        if (isset($_GET["new"])) $new = $_GET["new"];
        if (isset($_GET["uuid"])) $uuid = $_GET["uuid"];

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

                //hash current password
                $current = hashPassword( $current, $uuid );

                $ok = $testPass["password"] == $current;
                
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
    else if (isset($_GET["getUsers"]) || isset($_GET["init"]))
    {
        if (isset($_GET["getUsers"]) )
        {
            $reply["accepted"] = true;
            $reply["query"] = "getUsers";    
        }
        
        $rep = $db->prepare("SELECT name,shortName,folderPath,uuid,role FROM " . $tablePrefix . "users WHERE removed = 0;");
        $rep->execute();

        $users = Array();

        while ($user = $rep->fetch())
        {
            $u = Array();
			$u['name'] = $user['name'];
			$u['shortName'] = $user['shortName'];
			$u['uuid'] = $user['uuid'];
			$u['folderPath'] = $user['folderPath'];
			$u['role'] = $user['role'];

			$users[] = $u;
        }

        $rep->closeCursor();

        if (isset($_GET["getUsers"]) )
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
    else if (isset($_GET["createUser"]))
    {
        $reply["accepted"] = true;
        $reply["query"] = "createUser";

        $name = "";
        $shortName = "";
        $uuid = "";
        $password = "";

        if (isset($_GET["name"])) $name = $_GET["name"];
        if (isset($_GET["shortName"])) $shortName = $_GET["shortName"];
        if (isset($_GET["uuid"])) $uuid = $_GET["uuid"];
        if (isset($_GET["password"])) $password = $_GET["password"];

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
    else if (isset($_GET["removeUser"]))
    {
        $reply["accepted"] = true;
        $reply["query"] = "createUser";

        $uuid = "";

        if (isset($_GET["uuid"])) $uuid = $_GET["uuid"];

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
