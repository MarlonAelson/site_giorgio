<?php
DEFINE('BASEAPP','true');
require $_SERVER['DOCUMENT_ROOT'].'/api/inc/conexao.php';



if($_SERVER['REQUEST_METHOD'] == 'GET'){
	if(!$headerTkn || $headerTkn != $x_token){
		$json_return = [
			'error' => 'auth'
		];
	}else{
		// Takes raw data from the request
		$json = file_get_contents('php://input');

		// Converts it into a PHP object
		$data = $_GET;

		$select_cidadess = mysqli_query($conn,"SELECT c.* FROM cidades c INNER JOIN estados e ON e.id_estado = c.id_estado WHERE e.uf = '".mysqli_real_escape_string($conn,$data['uf'])."' OR e.id_estado = '".preg_replace('/[^0-9]/', '', $data['uf'])."'");
		while($e = mysqli_fetch_array($select_cidadess,MYSQLI_ASSOC)){
			$cidades[] = $e;
		}
		$json_return = [
			'error' => false,
			'count' => 3,
			'alterado' => date('Y-m-d H:i:s'),
			'data' => $cidades
		];
	}
}else{
	$json_return = [
		'error' => 'request'
	];
}
echo json_encode(array_map('encodeUtf8',$json_return));
?>