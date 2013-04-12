<?php
	abstract class Request {
	
		protected $db;
		protected $result;
		protected $requestParams = array();
	
		public function __construct($request_params){
			$this->requestParams = $request_params;
			$this->db = new PDO(sprintf("mysql:host=%s;dbname=%s", DB_HOST, DB_NAME), DB_USER, DB_PASS);
		}
		
		public abstract function parseRequest($params);
		public abstract function getResult();
	}