<?php
	function __autoload($name){
		require_once REQUEST_DIR . $name . ".php";
	}