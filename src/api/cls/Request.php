<?php
	abstract class Request {
	
		private $db;
		
		protected $result;
		protected $success = false;
		
		protected $requestParams = array();
	
		public function __construct(){
			$this->db = new PDO(sprintf("mysql:host=%s;dbname=%s", DB_HOST, DB_NAME), DB_USER, DB_PASS);
		}
	
		protected function getDB(){
			return $this->db;
		}
		
		protected function setRequestParams($p){
			$this->requestParams = $p;
		}
		
		public abstract function parseRequest($params);
		
		public function getResult(){
			if ($this->success){
				$msg = $this->result;
			} else {
				$msg = sprintf("Failed: ", $this->requestParams['name']);
			}
			return array($this->success, $msg);
		}
	}