<?php
	
	/*
		Rainbox Asset Manager
		Database access
	*/

	// sqlMode may not be set if the config file is from an old version
	if (!isset($sqlMode)) $sqlMode = 'mysql';

	try
	{
		if ( $sqlMode == 'mysql' )
		{
			$options = array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'");
			$db = new PDO('mysql:host=' . $sqlHost . ';port=' . $sqlPort . ';dbname=' . $sqlDBName . ';charset=utf8', $sqlUser, $sqlpassword,$options);
		}
			
		else if ( $sqlMode == 'sqlite' )
		{
			$db = new PDO( 'sqlite:' . __DIR__ . '/ramses_data' );
			$db->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
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

		// Insert/Replace a new row in a table
		public function insert( $table, $keys )
		{
			global $tablePrefix;

			$qKeys = array();
			$qVals = array();

			foreach( $keys as $key )
			{
				array_push( $qKeys, '`' . $key . '`');
				array_push( $qVals, ':' . $key);
			}

			array_push( $qKeys, '`latestUpdate`');
			array_push( $qKeys, '`removed`');
			array_push( $qVals, ':udpateTime');
			array_push( $qVals, ':removed');

			$q = "REPLACE INTO {$tablePrefix}{$table} (" . join(",",$qKeys) . ") VALUES (" . join(",",$qVals) . ");";

			$this->prepare($q);
			$this->bindStr('udpateTime', dateTimeStr() );
			$this->bindInt('removed', '0' );
		}

		// Get an id from a uuid
		public function id( $table, $uuid )
		{
			global $tablePrefix, $db;

			$q = "SELECT {$tablePrefix}{$table}.`id`
				FROM {$tablePrefix}{$table}
				WHERE `uuid` = :uuid;";

			$this->prepare($q);

			$this->bindStr( "uuid", $uuid, true);
			$this->execute();
			if ($i = $this->fetch())
			{
				$this->close();
				return $i['id'];
			}
			$this->close();
			return "";
		}

		public function uuid( $table, $id, $includeRemoved = false )
		{
			global $tablePrefix, $db;

			if ($id == "") return "";

			$q = "SELECT {$tablePrefix}{$table}.`uuid`
				FROM {$tablePrefix}{$table}
				WHERE `id` = :id";

			if (!$includeRemoved) $q = $q . " AND `removed`= 0";
			$q = $q . ";";

			$this->prepare($q);

			$this->bindInt( "id", $id, true);
			$this->execute();
			if ($i = $this->fetch())
			{
				$this->close();
				return $i['uuid'];
			}
			$this->close();
			return "";
		}

		// Removes a row from a table
		public function remove( $table, $uuid, $deleteNEW = true )
		{
			global $tablePrefix;

			$sName = '';

			if ($deleteNEW)
			{
				$q = "SELECT `shortName` FROM {$tablePrefix}{$table} WHERE `uuid`= :uuid ;";

				$this->prepare($q);
				$this->bindStr( "uuid", $uuid, true);
				$this->execute();

				$item = $this->fetch();
				if ($item) $sName = $item['shortName'];

				$this->close();
			}
			
			// DELETE
			if ($sName == "NEW")
			{
				$q = "DELETE FROM {$tablePrefix}{$table} WHERE uuid= :uuid ;";
				
				$this->prepare($q);
				$this->bindStr('uuid', $uuid, true );
				$this->execute( "'NEW' item deleted." );
				$this->close();
			}
			// SET REMOVED
			else
			{
				$q = "UPDATE {$tablePrefix}{$table} SET removed = 1, latestUpdate = :udpateTime WHERE uuid= :uuid ;";
				
				$this->prepare($q);
				$this->bindStr('uuid', $uuid, true );
				$this->bindStr('udpateTime', dateTimeStr() );
				$this->execute( "Item '{$sName}' removed from {$table}." );
				$this->close();
			}
		}

		// Updates a row in a table
		public function update( $table, $keys, $uuid )
		{
			global $tablePrefix;

			$qKeys = array();

			foreach( $keys as $key )
			{
				array_push( $qKeys, '`' . $key . '`= :' . $key);
			}
			array_push($qKeys, '`latestUpdate`= :updateTime');

			$q = "UPDATE {$tablePrefix}{$table} SET " . join(",",$qKeys) . " WHERE `uuid`= :uuid;";

			$this->prepare($q);
			$this->bindStr('uuid',  $uuid );
			$this->bindStr('updateTime', dateTimeStr() );
		}

		// Gets values from a single row
		public function get( $table, $keys, $uuid )
		{
			global $tablePrefix;

			$qKeys = array();
			foreach( $keys as $key )
			{
				array_push( $qKeys, '`' . $key . '`');
			}

			$q = "SELECT " . join(',',$qKeys) . " FROM {$tablePrefix}{$table} WHERE `uuid`= :uuid;";

			$this->prepare($q);
			$this->bindStr('uuid',  $uuid );

			$result = Array();
			$this->execute();
			if ($r = $this->fetch())
			{
				foreach($keys as $key)
				{
					$result[$key] = $r[$key];
				}
			}
			$this->close();
			return $result;
		}

		public function getAll( $table, $keys, $orderkeys = array(), $includeRemoved = false )
		{
			global $tablePrefix;

			array_push($keys, 'removed');
			array_push($keys, 'latestUpdate');

			$qKeys = array();
			foreach( $keys as $key )
			{
				array_push( $qKeys, '`' . $key . '`');
			}

			$q = "SELECT " . join(',',$qKeys) . " FROM {$tablePrefix}{$table}";
			if(!$includeRemoved) $q = $q . " WHERE `removed`= 0";
			if (count($orderkeys) > 0)
			{
				$q = $q . " ORDER BY ";
				$qOrderKeys = array();
				foreach( $orderkeys as $key )
				{
					array_push( $qOrderKeys, '`' . $key . '`');
				}
				$q = $q . join(",",$qOrderKeys);
			}
			$q = $q . ";";

			$this->prepare($q);

			$result = Array();
			$this->execute();
			while($r = $this->fetch())
			{
				$i = Array();
				foreach( $keys as $key )
				{
					if ($key == 'removed') $i[$key] = (int)$r[$key];
					$i[$key] = $r[$key];
				}
				$result[] = $i;
			}
			$this->close();
			return $result;
		}

		// Bind a Ramses name
		public function bindName( $name )
		{
			if ( $this->validateName( $name ) && $this->ok ) $this->query->bindValue(':name', $name, PDO::PARAM_STR );
			else $this->ok = false;
		}

		// Bind a Ramses short name (ID)
		public function bindShortName( $shortName )
		{
			if ( $this->validateShortName( $shortName ) && $this->ok ) $this->query->bindValue(':shortName', $shortName, PDO::PARAM_STR );
			else $this->ok = false;
		}

		// Bind an email
		public function bindEmail( $email )
		{
			//DISABLE CHECK FOR NOW
			$this->query->bindValue(':email', $email, PDO::PARAM_STR );
			return;

			if ( $this->validateEmail( $email ) && $this->ok ) $this->query->bindValue(':email', $email, PDO::PARAM_STR );
			else $this->ok = false;
		}

		// Bind a string
		public function bindStr( $key, $str, $mandatory = false )
		{
			global $reply;

			if ( !$this->ok ) return;

			if ($str == "" && $mandatory)
			{
				$reply["message"] = "Invalid request, missing value (string): '{$key}'";
            	$reply["success"] = false;
				$this->ok = false;
				return;
			}

			$this->query->bindValue( ":{$key}", $str, PDO::PARAM_STR );
		}

		// Bind an int
		public function bindInt( $key, $int, $mandatory = false )
		{
			global $reply;

			if ( !$this->ok ) return;

			if ($int == "" && $mandatory)
			{
				$reply["message"] = "Invalid request, missing value (int): '{$key}'";
            	$reply["success"] = false;
				$this->ok = false;
				return;
			}
			else if ($int == "")
			{
				$this->query->bindValue( ":{$key}", null, PDO::PARAM_STR );
			}
			else 
			{
				$this->query->bindValue( ":{$key}", $int, PDO::PARAM_INT );
			}
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
				$reply["message"] = "Database query failed. Here's the error\n\n" . $rep->errorInfo()[2];
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
			global $db;

			$this->query = $db->prepare($qstr);
			if ($this->query) $this->ok = true;
			else
			{
				global $reply;
				$reply["message"] = "Could not prepare the Database query.";
        		$reply["success"] = false;
				$this->ok = false;
			}
		}

		private function validateName( $name )
		{
			global $reply;
	
			if (preg_match( "/^[ a-zA-Z0-9+-]{1,256}$/i", $name ))
				return true;
	
			$reply["message"] = "Wrong name, sorry: names must have less than 256 characters and contain only one of these characters: [ A-Z, 0-9, +, - ] (and spaces).
				The name was: \"{$name}\"";
			$reply["success"] = false;
		}
	
		private function validateEmail( $email )
		{
			global $reply;
	
			// accept empty emails
			if ( $email == '' ) return true;
	
			if ( preg_match( "/^[a-z0-9!#$%&'*+\/=?^_`{|}~-]+(?:\\.[a-z0-9!#$%&'*+\/=?^_`{|}~-]+)*@(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\\.)+[a-z0-9](?:[a-z0-9-]*[a-z0-9])?$/i", $email ) )
				return true;
	
			$reply["message"] = "Wrong email, sorry.";
			$reply["success"] = false;
		}
	
		private function validateShortName( $shortName )
		{
			global $reply;
			
			if ( preg_match( "/^[a-zA-Z0-9+-]{1,10}$/i", $shortName ) )
				return true;
	
			$reply["message"] = "Wrong ID, sorry: IDs must have less than 10 characters and contain only one of these characters: [ A-Z, 0-9, +, - ].";
			$reply["success"] = false;
		}
	}
?>