<?php
    // The files required everywhere

    // Global constants
	require_once($__ROOT__ . "/global.php");

    // Config
    require_once($__ROOT__ . "/config/config.php");
	require_once($__ROOT__ . "/config/config_logs.php");

    // Classes
    require_once($__ROOT__ . "/classes/autoloader.php");

    // Common functions

    if (!function_exists('getallheaders')) {
        function getallheaders() {
            $headers = [];
            foreach ($_SERVER as $name => $value) {
                if (substr($name, 0, 5) == 'HTTP_') {
                    $headers[str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($name, 5)))))] = $value;
                }
            }
            return $headers;
        }
    }
?>