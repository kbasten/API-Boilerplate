<?php
	class Util {
		public static function mapFields($keyMap, $params){
			$fieldArr = explode(",", $params);
			
			$allFields = array();
			foreach ($fieldArr as $field){
				if (!array_key_exists($field, $keyMap)){
					throw new ApiException(sprintf("Unknown field '%s'.", $field), 101);
				}
				$allFields[] = $keyMap[$field]['dbcol'];
			}
			
			$fields = implode($allFields, ",");
			return $fields;
		}
		
		public static function mapAllFields($keyMap){
			$allFields = array();
			foreach ($keyMap as $key => $value){
				$allFields[] = $value['dbcol'];
			}
			$fields = implode(",", $allFields);
			return $fields;
		}
		
		public static function formatFields($keyMap, $input){
			foreach ($input as $dbcol => $value){
				if ($keyMap[$dbcol]['format'] == "bool"){
					$input[$dbcol] = $value == 1;
				} else if ($keyMap[$dbcol]['format'] == "ago"){
					$input[$dbcol] = Util::formatAgo($value);
				} else {
					$input[$dbcol] = sprintf($keyMap[$dbcol]['format'], $input[$dbcol]);
				}
			}
			
			return $input;
		}
		
		public static function getRequired($params, $required){
			$out = array();
			foreach ($required as $r){
				if (!array_key_exists($r['key'], $params)){
					if (isset($r['default'])){
						$out[$r['key']] = $r['default'];
					} else {
						if (!isset($r['optional'])){
							throw new ApiException("Missing required parameter '" . $r['key'] . "'.", 100);
						}
					}
				} else {
					$value = preg_replace($r['replace'], "", $params[$r['key']]);
					if (isset($r['max'])){
						if ($value > $r['max']){
							throw new ApiException("Maximum value for '" . $r['key'] . "' exceeded; maximum is " . $r['max'] . ".", 103);
						}
					}
					if (isset($r['min'])){
						if ($value < $r['min']){
							throw new ApiException("Minimum value for '" . $r['key'] . "' not met; minimum is " . $r['min'] . ".", 104);
						}
					}
					if (isset($r['possible'])){
						if (!in_array($value, $r['possible'])){
							if (isset($r['alias'])){
								$fieldName = $r['alias'];
							} else {
								$fieldName = $r['key'];
							}
							throw new ApiException("Value '" . $value . "' not possible for field '" . $fieldName . "'.", 105);
						}
					}
					
					$out[$r['key']] = $value;
				}
			}
			
			// now check for wrong combinations
			foreach ($required as $r){
				if (isset($out[$r['key']])){
					if (isset($r['combine'])){
						foreach ($params as $k => $v){
							if ($k != "d" && $k != $r['key'] && !in_array($k, $r['combine'])){ // "d" = debug parameter, always allow
								throw new ApiException("Cannot combine fields '" . $r['key'] . "' and '" . $k . "'.", 106);
							}
						}
					}
				}
			}
			
			return $out;
		}
		
		private static function formatAgo($in){
			if ($in == 0){
				return -1;
			} else {
				return time() - $in;
			}
		}
	}