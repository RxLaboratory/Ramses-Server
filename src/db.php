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
			$db = new PDO( 'sqlite:' . __DIR__ . '/ram_db' );
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

		// Removes a row from a table
		public function remove( $table, $uuid )
		{
			global $tablePrefix;

			// Check if it's NEW
			$q = "SELECT `shortName` FROM {$tablePrefix}{$table} WHERE `uuid`= :uuid ;";

			$this->prepare($q);
			$this->bindStr( "uuid", $uuid, true);
			$this->execute();

			$item = $this->fetch();
			$sName = '';
			if ($item) $sName = $item['shortName'];

			$this->close();

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

			$result = array();
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

		// Bind a Ramses name
		public function bindName( $name )
		{
			if ( validateName( $name ) && $this->ok ) $this->query->bindValue(':name', $name, PDO::PARAM_STR );
			else $this->ok = false;
		}

		// Bind a Ramses short name (ID)
		public function bindShortName( $shortName )
		{
			if ( validateShortName( $shortName ) && $this->ok ) $this->query->bindValue(':shortName', $shortName, PDO::PARAM_STR );
			else $this->ok = false;
		}

		// Bind an email
		public function bindEmail( $email )
		{
			//DISABLE CHECK FOR NOW
			$this->query->bindValue(':email', $email, PDO::PARAM_STR );
			return;

			if ( validateEmail( $email ) && $this->ok ) $this->query->bindValue(':email', $email, PDO::PARAM_STR );
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
		public function bindInt( $key, $int )
		{
			global $reply;

			if ( !$this->ok ) return;

			if ($int == "")
			{
				$reply["message"] = "Invalid request, missing value (int): '{$key}'";
            	$reply["success"] = false;
				$this->ok = false;
				return;
			}

			$this->query->bindValue( ":{$key}", $int, PDO::PARAM_INT );
		}

		// Request
		public function execute( $successMessage = "", $debug = false )
		{
			if ( !$this->ok ) return;
			$this->ok = sqlRequest( $this->query, $successMessage, $debug );
		}

		// Close cursor
		public function close()
		{
			if (isset( $this->query )) $this->query->closeCursor();
		}

		public function fetch()
		{
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
	}
?>