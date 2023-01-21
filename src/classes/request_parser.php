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
     * Parses POST and GET requests
     */
    class RequestParser
    {
        static $bodyContent = array();
        static $rawBody = array();
        static $charset = "";
        static $isJson = false;

        /**
         * Gets an argument from the url or the body
         */
        static function getArg($name, $defaultValue = "")
        {
            // It may be in the body
            if (isset(RequestParser::$bodyContent[$name])) $decodedArg = RequestParser::$bodyContent[$name];

            if (!isset($decodedArg)) {
                // Try from URL
                if ( hasArg( $name ) ) $decodedArg = rawurldecode( $_GET[$name] );
            }

            if (!isset($decodedArg)) return $defaultValue;

            if ( is_string($decodedArg) ) return checkForbiddenWords( $decodedArg );
            else return $decodedArg;
        }

        /**
         * Gets an attribute from the body
         */
        static function getAttr($name, $defaultValue = "")
        {
            $attr = "";
            if (isset(RequestParser::$bodyContent[$name])) $attr = RequestParser::$bodyContent[$name];
            if ($attr == "") return $defaultValue;
            return $attr;
        }

        /**
         * Check if the URL has the given arg
         */
        static function hasArg( $name )
        {
            return isset($_GET[$name]);
        }

        static function init()
        {
            $log = new Logger();

            // Parse body content to make it quickly available later
            // Check the content type, accepts application/json or application/x-www-form-urlencoded
            $allHeaders = getallheaders();
            RequestParser::$rawBody = file_get_contents('php://input');
            $log->requestLog($allHeaders, RequestParser::$rawBody);

            if (isset($allHeaders['Content-Type']))
            {
                $cType = $allHeaders['Content-Type'];
                $contentArray = explode(";", $cType);
                foreach( $contentArray as $c)
                {
                    $c = trim($c);
                    if ($c == "") continue;

                    if ($c == "application/json")
                    {
                        RequestParser::$isJson = true;
                        continue;
                    }

                    if ($c == "application/x-www-form-urlencoded")
                    {
                        RequestParser::$isJson = false;
                        continue;
                    }

                    if (StrUtils::startsWith($c, "charset="))
                    {
                        $charsetArray = explode($c, "=");
                        if (count($charsetArray) == 2)
                        {
                            RequestParser::$charset = trim($charsetArray[1]);
                        }
                        continue;
                    }
                }

                // If json, parse it right now
                if (RequestParser::$isJson)
                {
                    RequestParser::$bodyContent = json_decode(RequestParser::$rawBody, true);
                }
                else
                {
                    RequestParser::$bodyContent = $_POST;
                }
            }
        }
    }

?>