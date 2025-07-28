<?php

DEFINED('BASEAPP') OR die('403');


if($_SERVER['REQUEST_METHOD'] == 'OPTIONS'){
	header('Access-Control-Allow-Origin: *');
	header('Access-Control-Allow-Headers: Origin,Accept,User-Agent,user-agent,Content-Type,Access-Control-Allow-Headers,lang,Token,token,Client-Tkn,client-tkn,uuid,Uuid');
	header('Access-Control-Allow-Content: *');
	header('Access-Control-Allow-Methods: POST,GET,PATCH,PUT,OPTIONS');
	header("HTTP/1.1 200 OK");
	
	die();
}
header('Content-type: application/json');
header('Access-Control-Allow-Origin: '.$_SERVER['HTTP_ORIGIN']);

header('Access-Control-Allow-Headers: *');
header('Access-Control-Allow-Content: *');
header('Access-Control-Allow-Methods: POST,GET,PATCH,PUT,OPTIONS');
header('Access-Control-Expose-Headers: *');

if(!function_exists('encode_utf8')){
	function encode_utf8($data){
		if(is_array($data) || is_object($data)){
			return array_map('encode_utf8',(array)$data);
		}
		return utf8_encode($data);
	}
}

if(!function_exists('decode_utf8')){
  function decode_utf8($data){
    if(is_array($data) || is_object($data)){
      return array_map('decode_utf8',(array)$data);
    }
    return utf8_decode($data);
  }
}
$server = '159.203.112.151';
$user = 'delivery_mysql';
$pass = '9ueS5LMwJ2DCm4Ax';
$db = 'delivery';


$base_url = 'https://hummdelivery.com/api/';

$conn = mysqli_connect($server,$user,$pass,$db) or die(json_encode(['error'=>'erri']));

$x_token = 'ZDAwMjEzMDFhZjcxOWQyYzY3MjQxMGVmNGMxYzFhZmY';
$z_token = 'ZDAwMjEzMDFhZjcxOWQyYzY3MjQxMGVmNGMxYzFhZmYZz';
$headers = getallheaders();

$tkn_1 = (isset($headers['Token']) ? $headers['Token'] : (isset($headers['token']) ? $headers['token'] : false));
$uuid = (isset($headers['Uuid']) ? $headers['Uuid'] : (isset($headers['uuid']) ? $headers['uuid'] : false));
$clientTkn = (isset($headers['Client-Tkn']) ? $headers['Client-Tkn'] : (isset($headers['client-tkn']) ? $headers['client-tkn'] : false));
$headerTkn = $tkn_1;

if($headerTkn != $x_token && $headerTkn != $z_token){
	die(json_encode(['error'=>'auth']));
}
$requested_url = $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
$array_no_login = [
	'api/login.php',
	'api/get_login_google.php',
	'api/get_login_facebook.php',
	'api/cadastrar_usuario.php',
	'api/estados.php',
	'api/cidades.php',
	'api/',
	'api/index.php',
	'api/updateTransactionStatus.php'
];

$verificar = true;
foreach($array_no_login as $k => $v){

	if(strpos($requested_url,$v) !== false){
		$verificar = false;
	}
}
if($verificar){
	if(!$clientTkn){
		die(json_encode(['error'=>'auth.']));
	}

	$verify = mysqli_query($conn,"SELECT id_dispositivo FROM dispositivos WHERE token = '".$clientTkn."'".($uuid ? " AND uuid = '".$uuid."'" : ""));
	if(mysqli_num_rows($verify) <=0){
		die(json_encode(['error'=>'.auth.']));
	}
}
require dirname(__FILE__).'/funcoes.php';
require dirname(__FILE__).'/IpDetails.class.php';
include dirname(__FILE__)."/vendor/autoload.php";

$browser = new WhichBrowser\Parser($headers);
$IpDetails = new IpDetails(getUserIp());
$IpDetails->scan();
