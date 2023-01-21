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
    class StrUtils
    {
        static function endsWith( $string, $substring ) {
            $length = strlen( $substring );
            if( !$length ) {
                return true;
            }
            return substr( $string, -$length ) === $substring;
        }

        /**
         * Tests if a string starts with a substring
         */
        static function startsWith( $string, $substring ) {
            $length = strlen( $substring );
            return substr( $string, 0, $length ) === $substring;
        }
    }

?>