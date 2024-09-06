<?php
    // If this file is called directly, abort.
    if (!defined('RAMROOT')) die;

	require_once(RAMROOT."/config/config.php");
	
	/*
		Rainbox Asset Manager
		Database access
	*/

	// sqlMode may not be set if the config file is from an old version
	if (!isset($sqlMode)) $sqlMode = 'sqlite';

	// Security, chmod the data file
	if (is_file(RAMROOT."/data/ramses_data")) chmod(RAMROOT."/data/ramses_data", 0600);

	// In SQLite, no more than a 1000 rows at once (let's set 900)
	if ($sqlMode == "sqlite" && $SQLMaxRowPerRequest > 900) $SQLMaxRowPerRequest = 900; 

	try
	{
		if ( $sqlMode == 'mysql' || $sqlMode == 'mariadb')
		{
			$options = array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'");
			$db = new PDO('mysql:host=' . $sqlHost . ';port=' . $sqlPort . ';dbname=' . $sqlDBName . ';charset=utf8', $sqlUser, $sqlpassword, $options);
		}
			
		else if ( $sqlMode == 'sqlite' )
		{
			$db = new PDO( 'sqlite:' .RAMROOT."/data/ramses_data" );
			$db->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
			// activate use of foreign key constraints
			$db->exec( 'PRAGMA foreign_keys = ON;' );
		}
		else
		{
			die ( "Sorry, unknown database mode: " . $sqlMode );
		}
	}
	catch (Exception $e)
	{
		echo ("Oops, something went wrong with the database. Here's the error: <br />");
		die('Error: ' . $e->getMessage());
	}

	// A Wrapper class for the DB interface and queries
	class DBQuery
	{
		private $query;
		private $ok = false;
		private $closed = true;
		private $errorInfo = "";
		private $start_time = 0; // used to time methods

		// === Status ===

		public function isOK()
		{
			return $this->ok;
		}

		public function errorInfo()
		{
			return $this->errorInfo;
		}


		// === High level methods ===

		/**
		 * Mark a bunch of items as removed
		 * @param string $table The name of the table
		 * @param array $uuids The uuids to be marked as removed
		 */
		public function remove($table, $uuids) {

			global $SQLMaxRowPerRequest, $tablePrefix, $log;

			$this->start_timer();

			// Remove entries in the table
			$remove = 0;
			$removeCount = count($uuids);

			$log->debugLog("Removing {$removeCount} items from $table.", "DEBUG");

			$keys = [];
			$vals = [];
			$condition = [];

			$modified = gmdate("Y-m-d H:i:s", time()); 

			while ($remove < $removeCount)
			{
				for ($i = 0; $i < $SQLMaxRowPerRequest; $i++)
				{
					if ($remove == $removeCount) break;

					$key = "uuid$remove";
					$condition[] =  "`uuid` = :$key ";
					$keys[] = $key;
					$vals[] = $uuids[$remove];
					$remove++;
				}
	
				$condition = join(" OR ", $condition);
				$this->prepare("UPDATE `{$tablePrefix}$table` SET `modified` = '$modified', `removed` = 1 WHERE {$condition} ;");
				$this->bindStrings($keys, $vals);
				$this->execute();
				$this->close();
			}

			$elapsed = $this->elapsed();
			$log->debugLog("Rmoved {$removeCount} items in $table in $elapsed ms", "DEBUG");
		}

		/**
		 * Create new items or update existing items in a table
		 * @param string $table The name of the table
		 * @param array $data The list of data to be created
		 * @param array $uuids The uuids. If the array is empty, will generate new uuids.
		 */
		public function createOrUpdate($table, $data, $uuids = array() ) {

			global $SQLMaxRowPerRequest, $tablePrefix, $sqlMode, $log;

			$this->start_timer();

			$update = 0;
			$updateCount = count($data);

			$log->debugLog("Updating {$updateCount} items from $table.", "DEBUG");

			$modified = gmdate("Y-m-d H:i:s", time());

            while ($update < $updateCount)
            {
                // The values to be added to the request
                $vals = [];
                $keys = [];

                // The array of values for building the request
                $valuesStr = [];

                for ($i = 0; $i < $SQLMaxRowPerRequest; $i++)
                {
                    // Got all
                    if ($update == $updateCount) break;

					$uuidkey = "uuid$update";
                    $keys[] = $uuidkey;
                    $vals[] = $uuids[$update] ?? uuid();

					$datakey = "data$update";
                    $keys[] = $datakey;
                    $vals[] = json_encode( $data[$update] );

                    $valuesStr[] = "( :$uuidkey, :$datakey, '$modified' )";

                    $update++;
                }

                $valuesStr = implode(", ", $valuesStr);

                if ($sqlMode == 'sqlite') 
                    $qStr = "INSERT INTO `{$tablePrefix}$table` (`uuid`, `data`, `modified`)
							VALUES {$valuesStr}
                            ON CONFLICT(uuid) DO UPDATE SET 
                            `data` = excluded.data, `modified` = excluded.modified ;";
                else if ($sqlMode == 'mysql')
                    $qStr = "INSERT INTO `{$tablePrefix}$table`  (`uuid`, `data`, `modified`)
							VALUES {$valuesStr}
							AS new 
                            ON DUPLICATE KEY UPDATE `data` = new.data, `modified` = new.modified ;";
                else
                    $qStr = "INSERT INTO `{$tablePrefix}$table`  (`uuid`, `data`, `modified`)
							VALUES {$valuesStr}
                        	ON DUPLICATE KEY UPDATE `data` = VALUES(`data`), `modified` = VALUES(`modified`) ;";

                $this->prepare($qStr);
                $this->bindStrings($keys, $vals);
                $this->execute();
                $this->close();
            }

			$elapsed = $this->elapsed();
			$log->debugLog("Updated {$updateCount} items in $table in $elapsed ms", "DEBUG");
		}

		public function createUsers($users) {
			global $SQLMaxRowPerRequest, $tablePrefix, $sqlMode, $log;

			$this->start_timer();

			$update = 0;
			$updateCount = count($users);

			$log->debugLog("Updating {$updateCount} items from RamUser.", "DEBUG");

			$modified = gmdate("Y-m-d H:i:s", time());

			while ($update < $updateCount)
            {
                // The values to be added to the request
                $vals = [];
                $keys = [];

                // The array of values for building the request
                $valuesStr = [];

                for ($i = 0; $i < $SQLMaxRowPerRequest; $i++)
                {
                    // Got all
                    if ($update == $updateCount) break;

					$user = $users[$update];

					$uuidkey = "uuid$update";
                    $keys[] = $uuidkey;
					$userUuid = $user['uuid'] ?? uuid();
                    $vals[] = $userUuid;

					$usernamekey = "username$update";
                    $keys[] = $usernamekey;
                    $vals[] = $user['username'];

					$passwordkey = "password$update";
                    $keys[] = $passwordkey;
					// Password must be encrypted
                    $vals[] = hashPassword($user['password'], $userUuid);

					$emailkey = "email$update";
                    $keys[] = $emailkey;
					// Email must be encrypted
                    $vals[] = encrypt($user['email']);

					$datakey = "data$update";
                    $keys[] = $datakey;
					// Data must be encrypted
                    $vals[] = encrypt($user['data']);

                    $valuesStr[] = "( :$uuidkey, :$usernamekey, :$passwordkey, :$emailkey, :$datakey, '$modified' )";

                    $update++;
                }

                $valuesStr = implode(", ", $valuesStr);

                if ($sqlMode == 'sqlite') 
                    $qStr = "INSERT INTO `{$tablePrefix}RamUser` (`uuid`, `userName`, `password`, `email`, `data`, `modified`)
							VALUES {$valuesStr}
                            ON CONFLICT(uuid) DO UPDATE SET 
                            `data` = excluded.data, `modified` = excluded.modified ;";
                else if ($sqlMode == 'mysql')
                    $qStr = "INSERT INTO `{$tablePrefix}RamUser` (`uuid`, `userName`, `password`, `email`, `data`, `modified`)
							VALUES {$valuesStr}
							AS new 
                            ON DUPLICATE KEY UPDATE `data` = new.data, `modified` = new.modified ;";
                else
                    $qStr = "INSERT INTO `{$tablePrefix}RamUser` (`uuid`, `userName`, `password`, `email`, `data`, `modified`)
							VALUES {$valuesStr}
                        	ON DUPLICATE KEY UPDATE `data` = VALUES(`data`), `modified` = VALUES(`modified`) ;";

                $this->prepare($qStr);
                $this->bindStrings($keys, $vals);
                $this->execute();
                $this->close();
            }
		}

		public function createProject($uuid, $data) {
			global $tablePrefix, $sqlMode;

			if ($uuid == "") $uuid = uuid();
			$modified = gmdate("Y-m-d H:i:s", time());

			$qstr = "INSERT INTO `{$tablePrefix}RamProject` (`uuid`, `data`, `modified`, `removed`) 
					 VALUES ( :projectUuid, :projectData, :modified, 0 )";

			if ($sqlMode == 'sqlite') $qstr .= " ON CONFLICT(uuid) DO UPDATE SET ";
			else if ($sqlMode == 'mysql') $qstr .= " AS excluded ON DUPLICATE KEY UPDATE ";
			else $qstr .= " ON DUPLICATE KEY UPDATE ";

			if ($sqlMode == 'mariadb') $qstr .= "`data` = VALUES(`data`), `modified` = VALUES(`modified`), `removed` = 0 ;";
			else $qstr .= "`data` = excluded.data, `modified` = excluded.modified, `removed` = 0 ;";

			$this->prepare($qstr);
			$this->bindStr('projectUuid', $uuid);
			$this->bindStr('projectData', $data);
			$this->bindStr('modified', $modified);
			$this->execute();
			$this->close();

			if ($this->ok)
				return $uuid;
			
			return "";
		}

		public function getProjectId($projectUuid) {
			global $tablePrefix;

			$this->prepare("SELECT `id` FROM `{$tablePrefix}RamProject` WHERE `uuid` = :projectUuid;");
			$this->bindStr( "projectUuid", $projectUuid );
			$this->execute();
			$projectId = (int)$this->fetch()["id"] ?? -1;
			$this->close();
			return $projectId;
		}

		public function getUserIds($uuids) {
			global $tablePrefix, $SQLMaxRowPerRequest, $log;

			$update = 0;
			$updateCount = count($uuids);

			$ids = array();

			while ($update < $updateCount)
            {
				// The values to be added to the request
				$vals = [];
				$keys = [];
				// The array of values for building the request
                $valuesStr = [];

				for ($i = 0; $i < $SQLMaxRowPerRequest; $i++)
                {
                    // Got all
                    if ($update == $updateCount) break;

					$key = "uuid$update";
                    $keys[] = $key;
                    $vals[] = $uuids[$update];

                    $valuesStr[] = "`uuid` = :$key";

                    $update++;
                }

				$valuesStr = implode(" OR ", $valuesStr);

				$qStr = "SELECT `id` FROM `{$tablePrefix}RamUser` WHERE $valuesStr ;";
                $this->prepare($qStr);
                $this->bindStrings($keys, $vals);
                $this->execute();
				while($r = $this->fetch()) {
					$id = (int)$r['id'] ?? -1;
					if ($id >= 0)
						$ids[] = $id;
				}
                $this->close();
			}

			$elapsed = $this->elapsed();
			$log->debugLog("Got {$updateCount} user ids $elapsed ms", "DEBUG");

			return $ids;
		}

		public function assignUser($userId, $projectId) {
			$userIds = array();
			$userIds[] = $userId;
			$this->assignUsers($userIds, $projectId);
		}

		public function assignUsers( $userIds, $projectId ) {
			global $SQLMaxRowPerRequest, $tablePrefix, $sqlMode, $log;

			$update = 0;
			$updateCount = count($userIds);
			$table = "ServerProjectUser";

			$log->debugLog("Assigning users to project. There are $updateCount assignments.", "DEBUG");

            while ($update < $updateCount)
            {
                // The values to be added to the request
                $vals = [];
                $keys = [];

                // The array of values for building the request
                $valuesStr = [];

                for ($i = 0; $i < $SQLMaxRowPerRequest; $i++)
                {
                    // Got all
                    if ($update == $updateCount) break;

					$useridkey = "userid$update";
                    $keys[] = $useridkey;
                    $vals[] = $userIds[$update];

					$projectidkey = "projectid$update";
                    $keys[] = $projectidkey;
                    $vals[] = $projectId;

                    $valuesStr[] = "( :$useridkey, :$projectidkey )";

                    $update++;
                }

                $valuesStr = implode(", ", $valuesStr);

                if ($sqlMode == 'sqlite') 
                    $qStr = "INSERT INTO `{$tablePrefix}$table` (`user_id`, `project_id`)
							VALUES {$valuesStr}
                            ON CONFLICT(uuid) DO UPDATE SET 
                            `user_id` = `user_id`;";
                else if ($sqlMode == 'mysql')
                    $qStr = "INSERT INTO `{$tablePrefix}$table` (`user_id`, `project_id`)
							VALUES {$valuesStr}
                            ON DUPLICATE KEY UPDATE `user_id` = `user_id` ;";
                else
                    $qStr = "INSERT INTO `{$tablePrefix}$table`  (`user_id`, `project_id`)
							VALUES {$valuesStr}
                        	ON DUPLICATE KEY UPDATE `user_id` = `user_id` ;";

                $this->prepare($qStr);
                $this->bindInts($keys, $vals);
                $this->execute();
                $this->close();
            }

			$elapsed = $this->elapsed();
			$log->debugLog("Updated {$updateCount} items in $table in $elapsed ms", "DEBUG");
		}

		public function unassignUser( $userId, $projectId) {
			$userIds = array();
			$userIds[] = $userId;
			$this->unassignUsers($userIds, $projectId);
		}

		public function unassignUsers( $userIds, $projectId) {
			global $SQLMaxRowPerRequest, $tablePrefix, $log;

			$update = 0;
			$updateCount = count($userIds);
			$table = "ServerProjectUser";

			$log->debugLog("Unassigning users from project. There are $updateCount assignments.", "DEBUG");

            while ($update < $updateCount)
            {
                // The values to be added to the request
                $vals = [];
                $keys = [];

                // The array of values for building the request
                $valuesStr = [];

                for ($i = 0; $i < $SQLMaxRowPerRequest; $i++)
                {
                    // Got all
                    if ($update == $updateCount) break;

					$useridkey = "userid$update";
                    $keys[] = $useridkey;
                    $vals[] = $userIds[$update];

					$projectidkey = "projectid$update";
                    $keys[] = $projectidkey;
                    $vals[] = $projectId;

                    $valuesStr[] = "( `user_id` = :$useridkey AND `project_id` = :$projectidkey )";

                    $update++;
                }

                $valuesStr = implode(" OR ", $valuesStr);
				$qStr = "DELETE FROM `{$tablePrefix}$table` WHERE $valuesStr ;";
               
                $this->prepare($qStr);
                $this->bindInts($keys, $vals);
                $this->execute();
                $this->close();
            }

			$elapsed = $this->elapsed();
			$log->debugLog("Updated {$updateCount} items in $table in $elapsed ms", "DEBUG");
		}

		/**
		 * Get all items from a table
		 * @param string $table The name of the table
		 * @param boolean $includeRemoved Whether to include removed items
		 * @return array The items by uuid, as an associative array with uuid, data (decoded as an associative array), modified (as a string), removed (as a bool)
		 */
		public function get($table, $includeRemoved = false, $includeId = false) {

			global $tablePrefix, $log;

			$this->start_timer();

			$qStr = "SELECT `id`, `uuid`, `data`, `modified`, `removed`
					FROM {$tablePrefix}$table ";

			if (!$includeRemoved)
				$qStr .= "WHERE `removed` = 0 ";
			
			$qStr .= ";";

			$items = array();

			$this->prepare($qStr);
			$this->execute();
			while($r = $this->fetch()) {
				
				$uuid = $r["uuid"];
				if ($uuid == "")
					continue;

				$dataStr = $r["data"];
				if ($dataStr == "")
					continue;

				$item = array();
				if ($includeId)
					$item["id"] = (int)$r["id"];
				
				if ($table == "RamUser")
					$item["data"] = decrypt($dataStr);
				else
					$item["data"] = $dataStr;

				$item["modified"] = $r["modified"];
				$item["removed"] = (int)$r["removed"] == 1;
				
				$items[$uuid] = $item;
			}

			$this->close();

			$count = count($items);
			$elapsed = $this->elapsed();
			$log->debugLog("Retrieved {$count} items from $table in $elapsed ms", "DEBUG");

			return $items;
		}

		// === Low level methods ===

		// Bind a string
		public function bindStr( $key, $str, $mandatory = false )
		{
			global $reply, $log;

			if ( !$this->ok ) return;

			$log->debugLog("Binding string \"$str\" to :$key", "DEBUG");

			if ($str == "" && $mandatory)
			{
				$reply["message"] = "Invalid request, missing value (string): '{$key}'";
            	$reply["success"] = false;
				$log->debugLog("Missing string query parameter: $key", "WARNING");
				$this->ok = false;
				return;
			}

			$this->query->bindValue( ":{$key}", $str, PDO::PARAM_STR );
		}

		// Bind many strings
		public function bindStrings( $keys, $strings )
		{
			if (count($keys) != count($strings))
				throw "The key and string count doesn't match";

			for ($i = 0; $i < count($keys); $i++)
			{
				$this->bindStr( $keys[$i], $strings[$i]);
			}
		}

		// Bind an int
		public function bindInt( $key, $int, $mandatory = false )
		{
			global $reply, $log;

			if ( !$this->ok ) return;

			$log->debugLog("Binding int \"$int\" to :$key", "DEBUG");

			if ($int === "" && $mandatory)
			{
				$reply["message"] = "Invalid request, missing value (int): '{$key}'";
            	$reply["success"] = false;
				$log->debugLog("Missing int query parameter: $key", "WARNING");
				$this->ok = false;
				return;
			}
			else if ($int === "")
			{
				$this->bindNull( $key );
			}
			else 
			{
				$this->query->bindValue( ":{$key}", $int, PDO::PARAM_INT );
			}
		}

		public function bindInts( $keys, $ints )
		{
			if (count($keys) != count($ints))
				throw "The key and int count doesn't match";

			for ($i = 0; $i < count($keys); $i++)
			{
				$this->bindInt( $keys[$i], $ints[$i]);
			}
		}

		public function bindNull( $key )
		{
			$this->query->bindValue( ":{$key}", null, PDO::PARAM_STR );
		}

		// Bind a float (rounds it)
		public function bindFloat( $key, $float, $precision=6, $mandatory = false)
		{
			global $reply;

			if ( !$this->ok ) return;

			if ($float == "" && $mandatory)
			{
				$reply["message"] = "Invalid request, missing value (float): '{$key}'";
            	$reply["success"] = false;
				$this->ok = false;
				return;
			}
			else if ($float == "")
			{
				$this->query->bindValue( ":{$key}", null, PDO::PARAM_STR );
			}
			else 
			{
				$float = (float)$float;
				$float = round($float, $precision);
				$this->query->bindValue( ":{$key}", $float, PDO::PARAM_STR );
			}

		}

		// Request
		public function execute( $successMessage = "", $debug = false )
		{
			global $reply;

			if (!$this->closed) $this->close();
			if ( !$this->ok ) return;

			// dump params if debug
			if ($debug) $this->query->debugDumpParams();
			// execute
			$this->ok = $this->query->execute();
			$this->closed = false;

			// update reply
			if (!$this->ok)
			{
				$this->errorInfo = $this->query->errorInfo();
				$reply["message"] = "Warning! Database query failed. Here's the error\n\n" . $this->query->errorInfo()[2];
				$reply["success"] = false;
			}
			else if ($successMessage != "")
			{
				$reply["message"] = $successMessage;
				$reply["success"] = true;
			}
		}

		// Close cursor
		public function close()
		{
			if (isset( $this->query )) $this->query->closeCursor();
			$this->closed = true;
		}

		public function fetch()
		{
			$this->closed = false;

			if ( !$this->ok ) return false;
			return $this->query->fetch();
		}

		public function prepare( $qstr )
		{
			global $db, $log;

			$this->query = $db->prepare($qstr);
			if ($this->query) $this->ok = true;
			else
			{
				global $reply;
				$reply["message"] = "Could not prepare the Database query.";
        		$reply["success"] = false;
				$this->ok = false;
				$log->debugLog( "Could not prepare the Database query:\n$qstr", "WARNING" );
				printAndDie();
			}

			$log->debugLog("Preparing SQL request:\n$qstr", "DEBUG");
		}

		public function vacuum()
		{
			global $sqlMode, $db;
			if ( $sqlMode != 'sqlite' ) return;
			$query = $db->prepare("VACUUM");
			$query->execute();
			$query->closeCursor();
		}

		// === PRIVATE ===

		private function start_timer() {
			$this->start_time = hrtime(true);
		}

		private function elapsed() {
			$end_time = hrtime(true);
			$elapsed = ($end_time-$this->start_time) / 1000000; // nanonsecs to millisecs
			return (int)$elapsed;
		}
	}
