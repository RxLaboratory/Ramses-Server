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

    /**
     * Useful methods to handle strings
     */
    class JSON
    {
        // Useful functions to handle cache
        static function loadFile($filePath)
        {
            $file = fopen($filePath, "r");
            if ($file)
            {
                $dataStr = fread($file, filesize($filePath));
                fclose($file);
                return json_decode($dataStr, true);
            }
            else return array();
        }

        static function saveFile($filePath, $data)
        {
            $cacheStr = json_encode($data);
            $file = fopen($filePath, "w");
            fwrite($file, $cacheStr);
            fclose($file);
        }
    }

?>