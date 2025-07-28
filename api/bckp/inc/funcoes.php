<?php
DEFINED('BASEAPP') OR die('403');
function encodeUtf8($val){
	if(is_array($val) || is_object($val)){
		return array_map('encodeUtf8',(array)$val);
	}
	return utf8_encode($val);
}