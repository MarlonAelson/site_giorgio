<?php
DEFINE('BASEAPP','true');
require $_SERVER['DOCUMENT_ROOT'].'/api/inc/conexao.php';

if($_SERVER['REQUEST_METHOD'] == 'POST'){
	if(!$headerTkn || $headerTkn != $x_token){
		$json_return = [
			'error' => 'auth'
		];
	}else{
		if(!isset($_POST['nome']) || !isset($_POST['cpf']) || !in_array(strlen($_POST['cpf']),[11,14]) || !isset($_POST['senha']) || !isset($_POST['email']) || !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)){
			$json_return = [
				'error' => 'params',
				'msg'  => 'Parâmetros inválidos'
			];
		}else{

			$cpf = preg_replace('/[^0-9]/', '', $_POST['cpf']);
			
			if(!is_numeric($cpf)){
				$json_return = [
					'error' => 'params.',
					'msg' => "Parâmetros inválidos"
				];
			}else{
				$params_accept = [
					'nome',
					'senha',
					'email',
					'google_login_id',
					'facebook_login_id',
					'cpf',
					'telefone',
					'apple_login_id',
					'data_de_nascimento',
				];
				foreach ($_POST as $key => $value) {
					if(!in_array($key,$params_accept)){
						unset($_POST[$key]);
					}
				}

				if(isset($_POST['data_de_nascimento']) && !empty($_POST['data_de_nascimento'])){
					$explode_barra = explode('/',$_POST['data_de_nascimento']);
					if(is_array($explode_barra) && count($explode_barra) == 3){
						$_POST['data_de_nascimento'] = $explode_barra[2].'-'.$explode_barra[1].$explode_barra[0];
					}else{
						$explode_dash = explode('-',$_POST['data_de_nascimento']);
						if(!is_array($explode_dash) || (is_array($explode_dash) && count($explode_dash) != 3)){
							unset($_POST['data_de_nascimento']);
						}
					}
				}
				$run_cliente = mysqli_query($conn,"SELECT * FROM clientes WHERE cpf = '".$cpf."' OR email = '".$_POST['email']."'");
				if(mysqli_num_rows($run_cliente) > 0){
					$json_return = [
						'error' => 'params..',
						'msg' => "Parâmetros inválidos"
					];
				}else{
					$sql_inserir_pedido = "INSERT INTO clientes (".implode(', ',array_keys($_POST)). ") VALUES ('".implode("','",array_values($_POST))."')";
					$ERRO = 'no error';
					$insert_pedido = mysqli_query($conn,$sql_inserir_pedido) or $ERRO = mysqli_error($conn);
					if($id = mysqli_insert_id($conn)){
						$json_return = [
							'error' => false,
							'msg' => "Cadastrado",
							'data' => [
								'id_cliente' => $id,
								'nome'=>$_POST['nome'],
								'cpf' => $cpf,
								'senha' => $_POST['senha']
							]
						];
					}else{
						$json_return = [
							'error' => 'error_cad',
							'msg' => "Erro ao cadastrar: ".$ERRO,
						];
					}
				}
				
				
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