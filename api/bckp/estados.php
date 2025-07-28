<?php
DEFINE('BASEAPP','true');
require $_SERVER['DOCUMENT_ROOT'].'/api/inc/conexao.php';

if($_SERVER['REQUEST_METHOD'] == 'GET'){
	if(!$headerTkn || $headerTkn != $x_token){
		$json_return = [
			'error' => 'auth'
		];
	}else{
		$select_estados = mysqli_query($conn,"SELECT * FROM estados");
		while($e = mysqli_fetch_array($select_estados,MYSQLI_ASSOC)){
			$estados[] = $e;
		}
		$json_return = [
			'error' => false,
			'count' => 3,
			'alterado' => date('Y-m-d H:i:s'),
			'data' => $estados
		];
	}
}else{
	$json_return = [
		'error' => 'request'
	];
}
echo json_encode(array_map('encodeUtf8',$json_return));
?>