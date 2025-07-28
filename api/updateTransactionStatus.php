<?php
DEFINE('BASEAPP','true');
$pathfile = $_SERVER['DOCUMENT_ROOT']."/api/logs/".date('Y-m-d')."-updateTransactionStatus.log";
require $_SERVER['DOCUMENT_ROOT'].'/api/inc/conexao.php';

if($_SERVER['REQUEST_METHOD'] == 'PUT'){
	if(!$headerTkn || $headerTkn != $z_token){
		$json_return = [
			'error' => 'auth'
		];
	}else{
		$rawInput = file_get_contents('php://input');
		error_log('updateTransactionStatus data received: '.$rawInput,3,$pathfile);
		$_PUT = json_decode($rawInput, true);

		$json_return = [
			'error' => false,
			'sendedData' => $_PUT,
			'log' => $pathfile
		];
	}
}else{
	$json_return = [
		'error' => 'request'
	];
}
echo json_encode(array_map('encodeUtf8',$json_return));
?>