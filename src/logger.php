<?php
    require_once($__ROOT__."/config/config_logs.php");

    class Logger
    {
        private $logsPath = "";
        private $connexionLogFile = "";
        private $debugLogFile = "";
        private $requestPath = "";
        private $levels = Array (
            'DATA' => 0,
            'DEBUG' => 1,
            'INFO' => 2,
            'WARNING' => 3,
            'CRITICAL' => 4,
            'FATAL' => 5
        );

        public function __construct()
        {
            // Check if we're logging
            global $enableLogs; 
            global $connexionLogs;
            global $requestLogs;
            global $debugLogs;
            if (!$enableLogs) return;

            // Prepare folder
            $this->logsPath = "logs/";
            createFolder($this->logsPath, true);
            // Create folder for current month too
            createFolder($this->logsPath . date("Y/m/") , true);

            if (!$connexionLogs && !$requestLogs && !$debugLogs) return;

            // Prepare files
            $this->connexionLogFile = $this->logsPath . date("Y/m/") . date("Y-m-d") . "_connexionLog.csv";
            if (!is_file($this->connexionLogFile)) $this->appendConnexionLog("UUID,ID,Name,Role,Action,Client version,Date");

            $this->debugLogFile = $this->logsPath . date("Y/m/") . date("Y-m-d") . "_debugLog.csv";
            $this->initDebugLog();

            // Delete old logs
            $this->cleanLogs();
        }

        public function login($uuid, $role, $id, $name)
        {
            // Check if we're logging
            global $enableLogs; 
            global $connexionLogs;
            if (!$enableLogs) return;
            if (!$connexionLogs) return;

            $this->appendConnexionLog("{$uuid},{$id},{$name},{$role},login");
        }

        public function logout($uuid, $reason)
        {
            // Check if we're logging
            global $enableLogs; 
            global $connexionLogs;
            if (!$enableLogs) return;
            if (!$connexionLogs) return;

            $this->appendConnexionLog("{$uuid},,,,{$reason}");
        }

        public function debugLog($message, $level="INFO")
        {
            // Check if we're logging
            global $enableLogs; 
            global $debugLogs;
            global $reply;
            global $logLevel;

            $date = date("Y/m/d H:i:s");

            // Add the log to the reply
            if ($this->levels[$level] >= $this->levels[$logLevel])
            {
                $reply["debug"][] = Array (
                    "date" => $date,
                    "level" => $level,
                    "message" => $message
                );
            }

            if (!$enableLogs) return;
            if (!$debugLogs) return;
            
            $this->appendDebugLog("{$date},{$level},{$message}");
        }

        public function requestLog($headers, $body)
        {
            // Check if we're logging
            global $enableLogs; 
            global $requestLogs;
            if (!$enableLogs) return;
            if (!$requestLogs) return;

            $date = date("Y-m-d H:i:s");

            $this->requestPath = "logs/" . date("Y/m/") . "/request_{$date}";
            createFolder($this->requestPath, true);

            $headerFile = $this->requestPath . "/request-header.txt";
            $bodyFile = $this->requestPath . "/request-body.json";
            $metaFile = $this->requestPath . "/request-meta.txt";

            $ip = $_SESSION['IPaddress'];

            if (isset($_SESSION["clientVersion"])) $clientVer = $_SESSION["clientVersion"];
            else $clientVer = "unknown";

            $headerStr = "";
            foreach ($headers as $key => $header)
            {
                $headerStr = $headerStr . "{$key}: {$header}\n";
            }

            $file = fopen($headerFile, "a");
            if (!$file) return;
            if (!flock($file, LOCK_EX))
                return;
            fwrite($file, $headerStr);
            flock($file, LOCK_UN);
            fclose($file);

            $file = fopen($bodyFile, "a");
            if (!$file) return;
            if (!flock($file, LOCK_EX))
                return;
            fwrite($file, $body);
            flock($file, LOCK_UN);
            fclose($file);

            $file = fopen($metaFile, "a");
            if (!$file) return;
            if (!flock($file, LOCK_EX))
                return;
            fwrite($file, "IP: {$ip}\nClient: {$clientVer}");
            flock($file, LOCK_UN);
            fclose($file);
        }

        private function appendConnexionLog($text, $appendDateAndVersion = true)
        {
            $date = date("Y/m/d H:i:s");
            if (isset($_SESSION["clientVersion"])) $clientVer = $_SESSION["clientVersion"];
            else $clientVer = "unknown";
            
            $file = fopen($this->connexionLogFile, "a");

            if (!$file) return;

            // Lock exclusive
            if (!flock($file, LOCK_EX))
                return;
            
            if ($appendDateAndVersion) fwrite($file, "{$text},{$clientVer},{$date}\n");
            else fwrite($file, "{$text}\n");

            // Unlock
            flock($file, LOCK_UN);

            fclose($file);
        }

        private function initDebugLog()
        {
            if (is_file($this->debugLogFile)) return;
            $this->appendDebugLog("Date, Level, Message");
        }

        private function appendDebugLog($text)
        {
            $file = fopen($this->debugLogFile, "a");
            if (!$file) return;
            // Lock exclusive
            if (!flock($file, LOCK_EX))
                return;
            fwrite($file, "{$text}\n");
            flock($file, LOCK_UN);
            fclose($file);
        }

        private function cleanLogs()
        {
            global $logsExpiration;
            // convert timeout in seconds
            $timeout = ($logsExpiration+1) * 86400;
            // get limit
            $timeLimit = strtotime("now") - $timeout;
            $yearLimit = date("Y", $timeLimit);
            $monthLimit = date("m", $timeLimit);
            $dayLimit = date("d", $timeLimit);

            // Year
            $yearFolders = glob($this->logsPath . "*/", GLOB_MARK);
            foreach( $yearFolders as $yearFolder)
            {
                if (basename($yearFolder) < $yearLimit) deleteDir($yearFolder);
                else break;
            }
            // Month and day
            $currentYearFolder = $this->logsPath . $yearLimit . "/";
            if (is_dir($currentYearFolder))
            {
                $monthFolders = glob($currentYearFolder . "*/", GLOB_MARK);
                foreach( $monthFolders as $monthFolder)
                {
                    if (basename($monthFolder) < $monthLimit) deleteDir($monthFolder);
                    else break;
                }

                // Day
                $currentMonthFolder = $currentYearFolder . $monthLimit . "/";
                if (is_dir($currentMonthFolder))
                {
                    $dayFiles = glob($currentMonthFolder . "*");
                    foreach( $dayFiles as $dayFile)
                    {
                        $mtime = filemtime($dayFile);
                        if ($mtime < $timeLimit) {
                            unlink($dayFile);
                        }
                    }
                }
            }
            
        }
    }
?>