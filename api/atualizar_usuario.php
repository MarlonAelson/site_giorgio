<?php
DEFINE('BASEAPP','true');
require $_SERVER['DOCUMENT_ROOT'].'/api/inc/conexao.php';

if($_SERVER['REQUEST_METHOD'] == 'POST'){
	if(!$headerTkn || $headerTkn != $x_token){
		$json_return = [
			'error' => 'auth'
		];
	}else{
		if(!isset($_POST['id_cliente']) || !isset($_POST['dados']) || !is_array($_POST['dados']) || count($_POST['dados']) <= 0){
			$json_return = [
				'error' => 'params',
				'msg'  => 'Parâmetros inválidos',
				'dados' => $_POST['dados']
			];
		}else{
			$id_cliente = preg_replace('/[^0-9]/', '', $_POST['id_cliente']);
			
			if(!is_numeric($id_cliente)){
				$json_return = [
					'error' => 'params.',
					'msg' => "Parâmetros inválidos"
				];
			}else{
				$dados = $_POST['dados'];
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
					'senha'
				];
				foreach ($dados as $key => $value) {
					if(
						!in_array($key,$params_accept) || 
						($key == 'email' && !filter_var($value, FILTER_VALIDATE_EMAIL)) ||
						($key == 'senha' && ($value == '' || strlen($value) != 32) )
					){
						unset($_POST[$key]);
					}else{
						$dados[$key] = mysqli_real_escape_string($conn,utf8_decode($value));
					}
				}
				if(isset($dados['data_de_nascimento']) && !empty($dados['data_de_nascimento'])){
					$explode_barra = explode('/',$dados['data_de_nascimento']);
					if(is_array($explode_barra) && count($explode_barra) == 3){
						$dados['data_de_nascimento'] = $explode_barra[2].'-'.$explode_barra[1].$explode_barra[0];
					}else{
						$explode_dash = explode('-',$dados['data_de_nascimento']);
						if(!is_array($explode_dash) || (is_array($explode_dash) && count($explode_dash) != 3)){
							unset($dados['data_de_nascimento']);
						}
					}
				}
				$num = 0;
				if(isset($dados['cpf'])){
					$sql_cliente = "SELECT * FROM clientes WHERE cpf = '".$dados['cpf']."' and id_cliente <> '".$id_cliente."'";
					$run_cliente = mysqli_query($conn,$sql_cliente) or error_log('run_cliente1: '.mysqli_error($conn));
					$num += mysqli_num_rows($run_cliente);
				}
				if(isset($dados['email'])){
					$sql_cliente = "SELECT * FROM clientes WHERE email = '".$dados['email']."' and id_cliente <> '".$id_cliente."'";
					$run_cliente = mysqli_query($conn,$sql_cliente) or error_log('run_cliente2: '.mysqli_error($conn));
					$num += mysqli_num_rows($run_cliente);
				}
				
				if($num > 0){
					$json_return = [
						'error' => 'params..',
						'msg' => "Parâmetros inválidos",
					];
				}else{
					$cliente = mysqli_fetch_array($run_cliente);

					foreach ($dados as $key => $value) {
						$itt++;
						$string .= $key." = '".$value."'";
						if($itt != count($dados)){
							$string .= ", ";
						}
					}
					$sql_update_cliente = "UPDATE clientes SET ".$string." WHERE id_cliente = '".$id_cliente."'";
					$ERRO = 'no error';
					$update_cliente = mysqli_query($conn,$sql_update_cliente) or $ERRO = mysqli_error($conn);
					if($update_cliente){
						$json_return = [
							'error' => false,
							'msg' => "Atualizado"
						];
					}else{
						$json_return = [
							'error' => 'error_upd',
							'msg' => "Erro ao atualizar: ".$ERRO,
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