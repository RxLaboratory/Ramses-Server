<?php
    class Logger
    {
        private $logsPath = "";
        private $connexionLogFile = "";

        public function __construct()
        {
            // Check if we're logging
            global $enableLogs; 
            global $connexionLogs;
            if (!$enableLogs) return;

            // Prepare folder
            $this->logsPath = "logs/" . date("Y/") . date("m");
            createFolder($this->logsPath, true);

            if (!$connexionLogs) return;

            // Prepare files
            $this->connexionLogFile = $this->logsPath . "/" . date("Y-m-d") . "_connexionLog.csv";
            if (!is_file($this->connexionLogFile)) $this->appendConnexionLog("UUID,ID,Name,Role,Action,Client version,Date");
        }

        public function login()
        {
            // Check if we're logging
            global $enableLogs; 
            global $connexionLogs;
            if (!$enableLogs) return;
            if (!$connexionLogs) return;

            $uuid = $_SESSION["userUuid"];
            $id = $_SESSION["userId"];
            $name = $_SESSION["userRole"];
            $role = $_SESSION["userName"];
            $this->appendConnexionLog("{$uuid},{$id},{$name},{$role},login");
        }

        public function logout($reason)
        {
            // Check if we're logging
            global $enableLogs; 
            global $connexionLogs;
            if (!$enableLogs) return;
            if (!$connexionLogs) return;

            if (isset($_SESSION["userUuid"])) $uuid = $_SESSION["userUuid"];
            else $uuid = "";
            if (isset($_SESSION["userId"])) $id = $_SESSION["userId"];
            else $id = "";
            if (isset($_SESSION["userRole"])) $role = $_SESSION["userRole"];
            else $role = "";
            if (isset($_SESSION["userName"])) $name = $_SESSION["userName"];
            else $name = "";
            $this->appendConnexionLog("{$uuid},{$id},{$name},{$role},{$reason}");
        }

        private function appendConnexionLog($text)
        {
            $date = date("Y/m/d H:i:s");
            if (isset($_SESSION["clientVersion"])) $clientVer = $_SESSION["clientVersion"];
            else $clientVer = "unknown";
            
            $file = fopen($this->connexionLogFile, "a");
            
            fwrite($file, "{$text},{$clientVer},{$date}\n");
            fclose($file);
        }
    }
?>