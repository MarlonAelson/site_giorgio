<?php
DEFINE('BASEAPP','true');
require $_SERVER['DOCUMENT_ROOT'].'/api/inc/conexao.php';

if($_SERVER['REQUEST_METHOD'] == 'POST'){
	if(!$headerTkn || $headerTkn != $x_token){
		$json_return = [
			'error' => 'auth'
		];
	}else{
		if(!isset($_POST['google_login_id'])){
			$json_return = [
				'error' => 'params',
				'msg'  => 'Parâmetros inválidos'
			];
		}else{
			$google_login_id = preg_replace('/[^0-9]/', '', $_POST['google_login_id']);
			if(strlen($google_login_id) <= 0){
				$json_return = [
					'error' => 'params.',
					'msg' => "Parâmetros inválidos"
				];
			}else{
				$run_cliente = mysqli_query($conn,"SELECT * FROM clientes WHERE google_login_id = '".$google_login_id."'");
				if(mysqli_num_rows($run_cliente) != 1){
					if(isset($_POST['email']) && filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)){
						$run_cliente_email = mysqli_query($conn,"SELECT * FROM cliente WHERE email = '".$_POST['email']."'");
						if(mysqli_num_rows($run_cliente) != 1){
							$json_return = [
								'error' => 'not_found',
								'msg' => "Não encontrado"
							];
						}else{
							$cliente = mysql_fetch_array($run_cliente_email,MYSQLI_ASSOC);
							mysqli_query($conn,"UPDATE clientes SET google_login_id = '".$google_login_id."' WHERE id_cliente = '".$cliente['id_cliente']."'");
							$json_return = [
								'error' => false,
								'msg' => "Encontrado",
								'data' => [
									'id_cliente' => $cliente['id_cliente'],
									'google_login_id' => $google_login_id,
									'facebook_login_id' => $cliente['facebook_login_id'],
									'nome' => $cliente['nome'],
									'cpf' => $cliente['cpf'],
									'email' => $cliente['email'],
									'senha' => $cliente['senha']
								]
							];
						}
					}else{
						$json_return = [
							'error' => 'not_found',
							'msg' => "Não encontrado"
						];
					}
					
				}else{
					$cliente = mysqli_fetch_array($run_cliente,MYSQLI_ASSOC);
					$json_return = [
						'error' => false,
						'msg' => "Encontrado",
						'data' => [
							'id_cliente' => $cliente['id_cliente'],
							'google_login_id' => $google_login_id,
							'facebook_login_id' => $cliente['facebook_login_id'],
							'nome' => $cliente['nome'],
							'cpf' => $cliente['cpf'],
							'email' => $cliente['email'],
							'senha' => $cliente['senha']
						]
					];
				}
				
			}
			
		}
		
	}
}else{
	$json_return = [
		'error' => 'request',
		'msg'  => 'error'
	];
}
echo json_encode(array_map('encodeUtf8',$json_return));
?>