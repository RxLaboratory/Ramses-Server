<?php

    require_once($__ROOT__."/functions.php");
    require_once($__ROOT__."/reply.php");

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

    if ( acceptReply( "setPassword" ) )
	{
        $password = getArg("password");
        $uuid = getArg("uuid");

        if ($password == "")
        {
            $reply["message"] = "The password can't be empty, sorry!";
            $reply["success"] = false;
            printAndDie();
        }

        if ($uuid == "")
        {
            $reply["message"] = "The uuid can't be empty, sorry!";
            $reply["success"] = false;
            printAndDie();
        }

        // Hash
        $password = hashPassword($password, $uuid);

        $q = new DBQuery();
        $qStr = "UPDATE {$tablePrefix}RamUser SET `password` = :password WHERE `uuid` = :uuid ;";
        $q->prepare($qStr);
        $q->bindStr("uuid", $uuid);
        $q->bindStr("password", $password);
        $q->execute();
        $q->close();

        if (!$q->isOK())
        {
            $reply["message"] = "An SQL Error has occured, the password hasn't been changed, sorry.";
            $reply["success"] = false;
            printAndDie();
        }

        $reply["content"] = array();
        $reply["success"] = true;
        $reply["message"] = "Password changed!";
        printAndDie();
    }

?>