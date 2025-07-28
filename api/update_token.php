<?php
DEFINE('BASEAPP','true');
require $_SERVER['DOCUMENT_ROOT'].'/api/inc/conexao.php';
$path = '/images/clientes/';


if($_SERVER['REQUEST_METHOD'] == 'POST'){
	if(!$headerTkn || $headerTkn != $x_token){
		$json_return = [
			'error' => 'auth'
		];
	}else{
		if(!isset($_POST['id_cliente']) || !isset($_POST['tokenZago'])){
			$json_return = [
				'error' => 'auth',
				'msg'  => 'Parâmetros inválidos'
			];
		}else{
			$id_cliente = preg_replace('/[^0-9]/', '', $_POST['id_cliente']);
			$tknZago = mysqli_real_escape_string($conn,$_POST['tokenZago']);
			$buscar_cliente = mysqli_query($conn,"
				SELECT 
					id_cliente
				FROM clientes WHERE id_cliente = '".$id_cliente."'");

			if(mysqli_num_rows($buscar_cliente) > 0){
				$cliente = mysqli_fetch_array($buscar_cliente,MYSQLI_ASSOC);
				$buscar_token = mysqli_query($conn,"
				SELECT 
					*
				FROM tokens_zago WHERE token = '".$tknZago."'") or error_log('error q token: '.mysqli_error($conn));
				if(mysqli_num_rows($buscar_token)>0){
					mysqli_query($conn,"UPDATE tokens_zago SET ultimo_login = '".date('Y-m-d H:i:s')."' WHERE token = '".$tknZago."'") or error_log('error update token: '.mysqli_error($conn));
				}else{
					mysqli_query($conn,"INSERT INTO tokens_zago (id_cliente,ultimo_login,token) VALUES ('".$cliente['id_cliente']."','".date('Y-m-d H:i:s')."','".$tknZago."')") or error_log('error insert token: '.mysqli_error($conn));
				}

				$json_return = [
					'error' => false,
				];

			}else{
				$json_return = [
					'error' => 'not_found',
					'msg' => 'Cliente não encontrado'
				];
			}
			
		}
		
	}
}else{
	$json_return = [
		'error' => 'request'
	];
}
echo json_encode(array_map('encodeUtf8',$json_return));
?>