<?php
	class ExampleRequest extends Request {
	
		private $result;
		private $succeeded = false;
		
		private $required = array();
		
		/**
		 * @Override
		 */
		public function __construct($db){
			parent::__construct($db);
			
			$required = array(
				array(
					"key" 		=> "name",
					"default"	=> "henk",
					"replace"	=> "/[^a-zA-Z]/"
				)
			);
			$this->setRequestParams($required);
		}
		
		/**
		 * @Override
		 */
		public function parseRequest($params) {
			
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
	}