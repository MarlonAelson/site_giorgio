<?php
DEFINED('BASEAPP') OR die('403');
function encodeUtf8($val){
	if(is_array($val) || is_object($val)){
		return array_map('encodeUtf8',(array)$val);
	}
	return utf8_encode($val);
}

if(!function_exists('decodeUtf8')){
  function decodeUtf8($data){
    if(is_array($data) || is_object($data)){
      return array_map('decodeUtf8',(array)$data);
    }
    return utf8_decode($data);
  }
}

if(!function_exists('getUserIP')){
  function getUserIP(){
    $ipaddress = '';
    if (isset($_SERVER['HTTP_CLIENT_IP']))
        $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
    else if(isset($_SERVER['HTTP_X_FORWARDED_FOR']))
        $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
    else if(isset($_SERVER['HTTP_X_FORWARDED']))
        $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
    else if(isset($_SERVER['HTTP_X_CLUSTER_CLIENT_IP']))
        $ipaddress = $_SERVER['HTTP_X_CLUSTER_CLIENT_IP'];
    else if(isset($_SERVER['HTTP_FORWARDED_FOR']))
        $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
    else if(isset($_SERVER['HTTP_FORWARDED']))
        $ipaddress = $_SERVER['HTTP_FORWARDED'];
    else if(isset($_SERVER['REMOTE_ADDR']))
        $ipaddress = $_SERVER['REMOTE_ADDR'];
    else
        $ipaddress = 'UNKNOWN';
    return $ipaddress;
  }
}

if (!function_exists('getallheaders'))
{
    function getallheaders()
    {
           $headers = [];
       foreach ($_SERVER as $name => $value)
       {
           if (substr($name, 0, 5) == 'HTTP_')
           {
               $headers[str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($name, 5)))))] = $value;
           }
       }
       return $headers;
    }
}

if(!function_exists('validar_telefone')){
  function validar_telefone($num){
    $clean = preg_replace('/[^0-9]/', '', $num);
    if(strlen($clean) < 10 || strlen($clean) > 11){
      return false;
    }

    $noblanks = str_replace(' ','',$num);
    $pattern = '/^\\([0-9]{2}\\)((3[0-9]{3}-[0-9]{4})|(9[0-9]{4}-[0-9]{4})|([0-9]{4}-[0-9]{4}))$/';
    if(preg_match($pattern, $noblanks)){
      return true;
    }
    return false;
  }
}

if(!function_exists('custom_log')){
  function custom_log($message,$destination){
    if(is_array($message) || is_object($message)){
      try{
        $new_message = json_encode((array)$message,JSON_PRETTY_PRINT);
      }catch(Exception $e){
        $new_message = json_encode((array)array_map('encode_utf8',$message),JSON_PRETTY_PRINT);
      }

      $message = $new_message;
    }
    error_log(date('Y-m-d H:i:s')." --> ".$message."\n",3,$destination);
  }
}

if(!function_exists('gerar_string_aleatoria')){
  function gerar_string_aleatoria($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ_@!?-';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
  }
}

if(!function_exists('log_and_die')){
  function log_and_die($error){
    $PATH_LOG = $_SERVER['DOCUMENT_ROOT']."/api/private/erros_die_logs";
    if(!is_dir($PATH_LOG)){
      mkdir($PATH_LOG,0775,true);
    }
    $PATH_LOG = $PATH_LOG."/error-".date('Y-m-d').".log";
    custom_log('ERROR_AND_DIE_LOG: '.$error,$PATH_LOG);
    die(json_encode(['error'=>'die','return'=>$error]));
  }
}