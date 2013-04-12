<?php
	abstract class GuardedRequest extends Request{
		protected $user;
		
		public function __construct() {
			parent::__construct();
			
			require_once "cls/BasicUser.php";
			require_once "example/User.php";
			
			
			$this->user = User::factory();
			
			if(!$this->user){
				throw new ApiException("No user logged in!", 108);
			}
		}
	}