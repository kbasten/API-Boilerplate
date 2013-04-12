<?php
	class ExampleRequest implements Request {
	
		private $result;
		private $succeeded = false;
		
		private $required = array();

		public function parseRequest($params) {
			$required = array(
				array(
					"key" 		=> "name",
					"default"	=> "henk",
					"replace"	=> "/[^a-zA-Z]/"
				)
			);
			
			$this->required = Util::getRequired($params, $required);
			
			global $host, $username, $pass, $dbStats;
			$db = new PDODatabase($host, $username, $pass, $dbStats);
			
			$db->connect();
			$res = $db->query("SELECT SHIZZLE");
			$db = null;
			
			if (!empty($res)){
				$this->succeeded = true;
				
				$this->result = "Naam: " . $this->required['name'];
			} else {
				$this->succeeded = false;
			}
		}
		
		public function getResult(){
			if ($this->succeeded){
				$msg = $this->result;
			} else {
				$msg = sprintf("Failed to get data for '%s'", $this->required['name']);
			}
			return array($this->succeeded, $msg);
		}
	}