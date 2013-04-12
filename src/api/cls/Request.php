<?php
	abstract class Request {
	
		private $db;
		
		protected $result;
		protected $success = false;
		
		protected $requestParams = array();
	
		public function __construct($db){
			$this->db = $db;
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
				$msg = sprintf("Failed: ", $this->required['name']);
			}
			return array($this->success, $msg);
		}
	}