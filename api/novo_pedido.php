<?php
DEFINE('BASEAPP','true');
require $_SERVER['DOCUMENT_ROOT'].'/api/inc/conexao.php';

if($_SERVER['REQUEST_METHOD'] == 'POST'){
	if(!$headerTkn || $headerTkn != $x_token){
		$json_return = [
			'error' => 'auth'
		];
	}else{
		if(!isset($_POST['items']) || !isset($_POST['cartao']) || !isset($_POST['cpf']) || !isset($_POST['valor']) || !isset($_POST['id_maquina']) || !isset($_POST['id_status_pagamento']) || !isset($_POST['sub_total'])){
			$json_return = [
				'error' => 'params',
				'msg'  => 'Parâmetros inválidos'
			];
		}else{
			$cpf = preg_replace('/[^0-9]/', '', $_POST['cpf']);
			if(strlen($cpf) != 11){
				$json_return = [
					'error' => 'params.',
					'msg' => "Parâmetros inválidos"
				];
			}else{
				$run_cliente = mysqli_query($conn,"SELECT * FROM clientes WHERE cpf = '".$cpf."'");
				if(mysqli_num_rows($run_cliente) != 1){
					$json_return = [
						'error' => 'prms',
						'msg' => "Parâmetros inválidos"
					];
				}else{
					$cliente = mysqli_fetch_array($run_cliente,MYSQLI_ASSOC);
					$dados = [
						'id_status_pagamento' => $_POST['id_status_pagamento'],
						'total' => str_replace(',','.',str_replace('.','',$_POST['valor'])),
						'sub_total' => str_replace(',','.',str_replace('.','',$_POST['sub_total'])),
						'id_maquina' => $_POST['id_maquina'],
						'id_cliente' => $cliente['id_cliente'],
						'cartao' => $_POST['cartao'],
						'qrcode' => $_POST['qrcode'] ? mysqli_real_escape_string($conn,$_POST['qrcode']) : ''
					];
					if(isset($_POST['desconto'])){
						$dados['desconto'] = $_POST['desconto'];
					}
					if(isset($_POST['entrega']) && $_POST['entrega']){
						$dados['retirada'] = 1;
					}else{
						$dados['retirada'] = 0;
					}

					$sql_inserir_pedido = "INSERT INTO pedidos (".implode(', ',array_keys($dados)). ") VALUES ('".implode("','",array_values($dados))."')";
					error_log($sql_inserir_pedido);
					$run_novo_pedido = mysqli_query($conn,$sql_inserir_pedido) or error_log(mysqli_error($conn));
					$id_pedido = mysqli_insert_id($conn);
					if($id_pedido){


						$sql_inserir_historico_pedido = "INSERT INTO historico_status_pagamento (id_pedido,id_status_pagamento) VALUES ('".$id_pedido."','".$dados['id_status_pagamento']."')";
						$run_historico_pedido = mysqli_query($conn,$sql_inserir_historico_pedido);

						$sql_inserir_historico_logistica = "INSERT INTO historico_status_logistica (id_pedido,id_status_pagamento) VALUES ('".$id_pedido."','1')";
						$run_historico_logistica = mysqli_query($conn,$sql_inserir_historico_logistica);


						error_log(json_encode($_POST['items']));
						foreach ($_POST['items'] as $key => $value) {
							$price = str_replace('.', '', $value['product_price']);
							$price = str_replace(',', '.', $price);
							if(strpos($value['subTotal'],',') !== false){
								$subPrice = str_replace('.', '', $value['subTotal']);
								$subPrice = str_replace(',', '.', $price);
							}else{
								$subPrice = $value['subTotal'];
							}
							
							$arr = [
								'id_produto' => $value['product_id'],
								'id_categoria' => $value['category_id'],
								'produto' => $value['product_name'],
								'valor' => $price,
								'quantidade'=> $value['quantidade'],
								'sub_total' => $subPrice,
								'id_pedido' => $id_pedido
							];

							$sql_inserir_item = "INSERT INTO pedidos_itens (".implode(', ',array_keys($arr)). ") VALUES ('".implode("','",array_values($arr))."')";
							error_log('subTotal: '.$subPrice);
							error_log('sql_itens: '.$sql_inserir_item);
							mysqli_query($conn,$sql_inserir_item) or error_log("error sql item: ".mysqli_error($conn));
						}
						$json_return = [
							'error' => false,
							'id_pedido' => $id_pedido,
							'msg' => "pedido realizado"
						];
					}else{
						$json_return = [
							'error' => 'not_created',
							'msg' => "pedido não realizado",
							'mysqli_error' => mysqli_error($conn)
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