<?php
DEFINE('BASEAPP','true');
require $_SERVER['DOCUMENT_ROOT'].'/api/inc/conexao.php';
if($_SERVER['REQUEST_METHOD'] == 'POST'){
	if(!$headerTkn || $headerTkn != $x_token){
		$json_return = [
			'error' => 'auth'
		];
	}else{
		if(!isset($_POST['id_pedido'])){
			$json_return = [
				'error' => 'params',
				'msg'  => 'Parâmetros inválidos'
			];
		}else{
			$conn = mysqli_connect('localhost','jvmtecno_hummdelivery','Nq#AViblTD99','jvmtecno_hummdelivery') or die(mysqli_error($conn));
			$id_pedido = preg_replace('/[^0-9]/', '', $_POST['id_pedido']);
			if(isset($_POST['id_status_pagamento']) && !empty($_POST['id_status_pagamento'])){
				$id_status_pagamento = preg_replace('/[^0-9]/', '', $_POST['id_status_pagamento']);
			}
			if(isset($_POST['id_status_maquina']) && !empty($_POST['id_status_maquina'])){
				$id_status_maquina = preg_replace('/[^0-9]/', '', $_POST['id_status_maquina']);
			}

			error_log('id_pedido: "'.$id_pedido.'"');

			if(!is_numeric($id_pedido)){
				$json_return = [
					'error' => 'params.',
					'msg' => "Parâmetros inválidos"
				];
			}else{
				$run_pedido = mysqli_query($conn,"SELECT * FROM pedidos WHERE id_pedido = '".$id_pedido."'");
				if(mysqli_num_rows($run_pedido) != 1){
					$json_return = [
						'error' => 'prms',
						'msg' => "Parâmetros inválidos"
					];
				}else{
					$pedido = mysqli_fetch_array($run_pedido,MYSQLI_ASSOC);
					$dados = [];
					if(isset($_POST['id_status_maquina']) && !empty($_POST['id_status_maquina'])){
						$dados['id_status_maquina'] = $id_status_maquina;
					}
					if(isset($_POST['id_status_pagamento']) && !empty($_POST['id_status_pagamento'])){
						$dados['id_status_pagamento'] = $id_status_pagamento;
					}
					if(isset($_POST['id_transacao_maquina']) && !empty($_POST['id_transacao_maquina'])){
						$dados['id_transacao_maquina'] = $_POST['id_transacao_maquina'];
					}
					if(isset($_POST['id_transacao_pagarme']) && !empty($_POST['id_transacao_pagarme'])){
						$dados['id_transacao_pagarme'] = $_POST['id_transacao_pagarme'];
					}
					if(isset($_POST['id_status_logistica']) && !empty($_POST['id_status_logistica'])){
						$dados['id_status_logistica'] = $_POST['id_status_logistica'];
					}

					if(count($dados) > 0){
						$string = '';
						$itt = 0;
						foreach ($dados as $key => $value) {
							$itt++;
							$string .= $key." = '".$value."'";
							if($itt != count($dados)){
								$string .= ", ";
							}
						}
						$sql_atualizar_pedido = "UPDATE pedidos SET ".$string." WHERE id_pedido = '".$id_pedido."'";
						error_log($sql_atualizar_pedido);
						$run_upd_pedido = mysqli_query($conn,$sql_atualizar_pedido) or error_log(mysqli_error($conn));
						if($run_upd_pedido){
							if(isset($dados['id_status_pagamento'])){
								$sql_inserir_historico_pedido = "INSERT INTO historico_status_pagamento (id_pedido,id_status_pagamento) VALUES ('".$id_pedido."','".$dados['id_status_pagamento']."')";
								$run_historico_pedido = mysqli_query($conn,$sql_inserir_historico_pedido) or error_log('run insert historico pagamento: '.$sql_inserir_historico_pedido);
							}
							if(isset($dados['id_status_logistica'])){
								$sql_inserir_historico_logistica = "INSERT INTO historico_status_logistica (id_pedido,id_status_logistica) VALUES ('".$id_pedido."','".$dados['id_status_logistica']."')";
								$run_historico_logistica = mysqli_query($conn,$sql_inserir_historico_logistica) or error_log('run insert historico logistica: '.$sql_inserir_historico_logistica);
							}
							$json_return = [
								'error' => false,
								'id_pedido' => $id_pedido,
								'msg' => "pedido atualizado"
							];
						}else{
							$json_return = [
								'error' => 'not_updated',
								'msg' => "pedido não atualizado"
							];
						}
					}else{
						$json_return = [
							'error'=> 'paramss',
							'msg' => 'Parâmetros inválidos'
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