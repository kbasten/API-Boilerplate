<?php
class BasicUser{
	private $ip;
	
	protected function __construct(){
		$this->ip = $_SERVER['REMOTE_ADDR'];
	}
	
	protected function __destruct(){
		session_start();
		$_SESSION['user'] = serialize($this);
	}
	
	public static function factory(){
		session_start();
		if(isset($_SESSION['user'])){
			return unserialize($_SESSION['user']);
		}else{
			return false;
		}
	}
}