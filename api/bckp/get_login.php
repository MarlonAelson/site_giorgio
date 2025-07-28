<?php
DEFINE('BASEAPP','true');
require $_SERVER['DOCUMENT_ROOT'].'/api/inc/conexao.php';

$path = '/api/images/clientes/';


if($_SERVER['REQUEST_METHOD'] == 'GET'){
	if(!$headerTkn || $headerTkn != $x_token){
		$json_return = [
			'error' => 'auth'
		];
	}else{
		if(!isset($_GET['tokenZago']) && !isset($_GET['id_cliente'])){
			$json_return = [
				'error' => 'auth',
				'msg'  => 'Parâmetros inválidos'
			];
		}else{

			if(isset($_GET['tokenZago'])){
				$tknZago = mysqli_real_escape_string($conn,$_GET['tokenZago']);
				$buscar_cliente = mysqli_query($conn,"
					SELECT 
						c.id_cliente, nome, cpf, email, senha, telefone, imagem, data_de_nascimento, facebook_login_id, google_login_id, apple_login_id
					FROM clientes c INNER JOIN tokens_zago tz ON tz.id_cliente = c.id_cliente WHERE token = '".$tknZago."'");
			}else{
				$id_cliente = preg_replace('/[^0-9]/', '', $_GET['id_cliente']);
				$buscar_cliente = mysqli_query($conn,"
				SELECT 
					id_cliente, nome, cpf, email, senha, telefone, imagem, data_de_nascimento, facebook_login_id, google_login_id, apple_login_id
				FROM clientes WHERE id_cliente = '".$id_cliente."'");
			}
			

			if(mysqli_num_rows($buscar_cliente) > 0){
				$cliente = mysqli_fetch_array($buscar_cliente,MYSQLI_ASSOC);

				if($cliente['imagem'] != ''){

					if(is_file($_SERVER['DOCUMENT_ROOT'].$path.$cliente['imagem'])){
						$cliente['imagem'] = 'data:image/'.pathinfo($_SERVER['DOCUMENT_ROOT'].$path.$cliente['imagem'],PATHINFO_EXTENSION).";base64,".base64_encode(file_get_contents($_SERVER['DOCUMENT_ROOT'].$path.$cliente['imagem']));
					}else{
						$cliente['imagem'] = '';
					}
				}
				$json_return = [
					'error' => false,
					'data' => $cliente
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