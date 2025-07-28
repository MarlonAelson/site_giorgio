<?php
DEFINE('BASEAPP','true');
require $_SERVER['DOCUMENT_ROOT'].'/api/inc/conexao.php';

if($_SERVER['REQUEST_METHOD'] == 'GET'){
	if(!$headerTkn || $headerTkn != $x_token){
		$json_return = [
			'error' => 'auth'
		];
	}else{
		$json_return = [
			'error' => false,
			'data' => [
				'politica' => 'TEXTO DOS TERMOS AQUI'
			]
		];
	}
}else{
	$json_return = [
		'error' => 'request'
	];
}
echo json_encode(array_map('encodeUtf8',$json_return));
?>