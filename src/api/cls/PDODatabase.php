<?php

    class PDODatabase {
	
		private $host;
		private $user;
		private $pass;
		private $db;
		
		private $connection;
	
        public function __construct($host, $user, $pass, $db) {
            $this->host = $host;
            $this->user = $user;
            $this->pass = $pass;
            $this->db   = $db;
        }

        public function connect() {
            $this->connection = new PDO(sprintf("mysql:host=%s;dbname=%s", $this->host, $this->db), $this->user, $this->pass);
        }

        public function query($query) {
            $result = $this->connection->query($query);
            if (is_bool($result)) {
                return $result;
            } else {
                $array = array();
				$count = 0;
                while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
					foreach ($row as $key => $value){
						$array[$count][$key] = stripslashes($value);
					}
					$count++;
                }
                return $array;
            }
        }
		
		public function disconnect(){
			$this->connection = null;
		}
		
		public function lastId(){
			return mysql_insert_id();
		}
    }