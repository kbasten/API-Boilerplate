<?php
	class ExampleRequest extends Request {
		/**
		 * @Override
		 */
		public function __construct(){
			parent::__construct();
			
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

			if (!empty($res)){
				$this->succeeded = true;
				
			} else {
				$this->succeeded = false; 
			}
		}
	}