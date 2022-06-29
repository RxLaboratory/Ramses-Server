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

    if (hasArg("ping"))
    {
        $reply["accepted"] = true;
        $reply["query"] = "ping";
        $reply["content"]["version"] = $ramsesVersion;
        
        $ramVersion = strtolower($ramsesVersion);
        $clientVersion = $_SESSION["clientVersion"];
        $_SESSION["sessionKey"] = bin2hex(random_bytes(20));

        if ($_SESSION["clientVersion"] != $ramVersion)
        {
            $reply["content"]["installed"] = true;
            $reply["success"] = true;
            $reply["message"] = "Warning, the version of Ramses you're using ({$clientVersion}) differs with the one of this server ({$ramVersion}).";
            $reply["sessionKey"] = bin2hex(random_bytes(20));
        }
        else if ($installed)
        {
            $reply["content"]["installed"] = true;
            $reply["success"] = true;
            $reply["message"] = "Server ready.";
            $reply["sessionKey"] = bin2hex(random_bytes(20));
        }
        else
        {
            $reply["content"]["installed"] = false;
            $reply["success"] = false;
            $reply["message"] = "The server is not installed!";
            $reply["sessionKey"] = "";
        }
    }
?>