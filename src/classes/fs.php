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
     * Useful methods to handle files and folders
     */
    class FS
    {
        static function createFolder( $path, $recursive=false, $addIndex = true )
        {
            if (is_file($path)) return;
            if (!is_dir($path)) mkdir($path, 0700, $recursive);
            
            if ($addIndex)
            {
                if (substr($path, strlen($path) - 1, 1) != '/') {
                    $path .= '/';
                }
    
                if (!is_file($path . "index.html")) {
                    $file = fopen($path . "index.html", "a");
                    if (!$file) return;
                    fwrite($file, "<h1>Forbidden</h1>");
                    fclose($file);
                }

                if (!is_file($path . ".htaccess")) {
                    $file = fopen($path . ".htaccess", "a");
                    if (!$file) return;
                    fwrite(
                        $file,
                        "Order allow,deny
                        Deny from All

                        # Disable directory browsing
                        Options All -Indexes
                        # Prevent folder listing
                        IndexIgnore *"
                    );
                    fclose($file);
                }    
            }
        }

        /**
         * Recursively deletes a directory
         */
        static function deleteFolder($dirPath) {
            if (! is_dir($dirPath)) {
                throw new InvalidArgumentException("$dirPath must be a directory");
            }
            if (substr($dirPath, strlen($dirPath) - 1, 1) != '/') {
                $dirPath .= '/';
            }
            $files = glob($dirPath . '*', GLOB_MARK);
            foreach ($files as $file) {
                if (is_dir($file)) {
                    FS::deleteFolder($file);
                } else {
                    unlink($file);
                }
            }
            rmdir($dirPath);
        }
    }

?>