<?php
	// error_reporting(0);
	
	require_once "config.php";
	require_once "functions.php";
	
	$startTime = microtime(true);
	
	$url = strtok(substr($_SERVER['REQUEST_URI'], 1), "?");
	
	$urlParts = explode("/", $url);
	
	for ($i = 0; $i < count($urlParts); $i++){
		$urlParts[$i] = preg_replace("/[^a-zA-Z0-9]/", "", $urlParts[$i]);
	}
	
	$className = ucfirst($urlParts[0]);
	
	/*
	if ($className == ""){
		header("Location: http://www.scrollsguide.com/404");
		exit;
	}
	*/
	
	$pathToModule = sprintf(REQUEST_DIR . "%s.php", $className);
	
	$result = array(false);
	$msg = "";
	$execTime = 0;
	
	try {
		try {
			require_once "cls/ApiException.php";
			if (file_exists($pathToModule)){
				require_once "cls/PDODatabase.php";
				require_once "cls/Util.php";
				require_once "cls/Request.php";
				require_once $pathToModule;
				
				$r = new $className;
				
				$params = array();
				
				for ($i = 0; $i < count($urlParts); $i++){
					$params['url_' . $i] = $urlParts[$i];
				}
				
				foreach ($_GET as $key => $p){
					$params[$key] = urldecode($p);
				}
				
				$r->parseRequest($params);
				
				$result = $r->getResult();
				
				if (!isset($result[2]) || $result[2]){
					$out = array(
						"msg"	=> $result[0] ? "success" : "fail",
						"data"	=> $result[1]
					);
					
					$execTime = (int)((microtime(true) - $startTime) * 1000);
					if (isset($_GET['d'])){
						$out['time'] = $execTime;
					}					
					echo json_encode($out, JSON_NUMERIC_CHECK);
				}
			} else {
				$msg = "No such method '$className'.";
				throw new ApiException($msg, 102);
			}
		} catch (PDOException $e){
			$msg = "Database exception." . $e->getMessage();
			if (DEBUG){
				echo $msg;
			} else {
				throw new ApiException("Database exception.", 105);
			}
		}
	} catch (ApiException $e){
		$msg = $e;
		echo $msg;
	}
	
	$pdo = new PDO(sprintf("mysql:host=%s;dbname=%s", $host, $dbApiName), $username, $pass);
	$sth = $pdo->prepare("INSERT INTO requests (ip, time, request, msg, success, exectime)
				VALUES (?, UNIX_TIMESTAMP(), ?, ?, ?, ?)");
	$sth->bindValue(1, $_SERVER['REMOTE_ADDR'], PDO::PARAM_STR);
	$sth->bindValue(2, $_SERVER['REQUEST_URI'], PDO::PARAM_STR);
	$sth->bindValue(3, $msg, PDO::PARAM_STR);
	$sth->bindValue(4, $result[0] ? 1 : 0, PDO::PARAM_INT);
	$sth->bindValue(5, $execTime, PDO::PARAM_INT);
	$sth->execute();
	$pdo = null;