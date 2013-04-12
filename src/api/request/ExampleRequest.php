<?php
	class ExampleRequest extends Request {
		/**
		 * @Override
		 */
		public function __construct(){
			$required = array(
				array(
					"key" 		=> "name",
					"default"	=> "henk",
					"replace"	=> "/[^a-zA-Z]/"
				)
			);
			
			parent::__construct($required);
		}
		
		/**
		 * @Override
		 */
		public function parseRequest($params) {
			Util::getRequired($params, $this->requestParams);
			
			$this->success = true;
			$this->result    = "aww yeah";
			
			if (true){
				$this->success= true;
				
			} else {
				$this->success = false; 
			}
		}
		
		public function getResult(){
			if ($this->success){
				$msg = $this->result;
			} else {
				$msg = sprintf("Failed: %s!", __CLASS__);
			}
			return array($this->success, $msg);
		}
	}