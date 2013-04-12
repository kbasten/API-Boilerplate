<?php
	interface Request {
		public function parseRequest($params);
		public function getResult();
	}