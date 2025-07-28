<?php
DEFINE('BASEAPP','true');
require $_SERVER['DOCUMENT_ROOT'].'/api/inc/conexao.php';

if($_SERVER['REQUEST_METHOD'] == 'GET'){
	if(!$headerTkn || $headerTkn != $x_token){
		$json_return = [
			'error' => 'auth'
		];
	}else{
		if(!isset($_GET['id_pedido']) && !isset($_GET['id_transacao_maquina'])){
			$json_return = [
				'error' => 'params',
				'msg' => 'Parâmetros inválidos'
			];
		}else{
			if(isset($_GET['id_pedido'])){
				$id_pedido = preg_replace('/[^0-9]/', '', $_GET['id_pedido']);
				$where = "p.id_pedido = '".$id_pedido."'";
			}else{
				$id_transacao_maquina = preg_replace('/[^0-9]/', '', $_GET['id_transacao_maquina']);
				$where = "p.id_transacao_maquina = '".$id_transacao_maquina ."'";
			}

			error_log($where);
			$select_pedido = mysqli_query($conn,"
				SELECT 
					p.*, 
					sp.status status_pagamento, 
					IF(
						p.id_status_logistica IS NOT NULL AND p.id_status_logistica != '' AND p.id_status_logistica != 0,
						sl.status,
						'') status_logistica 
				FROM 
					pedidos p 
				INNER JOIN 
					status_pagamento sp ON sp.id_status = p.id_status_pagamento 
				LEFT JOIN 
					status_logistica sl ON sl.id_status = p.id_status_logistica 
				WHERE ".$where." 
				GROUP 
					BY p.id_pedido") or error_log("ERRO GET PEDIDO: ".mysqli_error($conn));
			if(mysqli_num_rows($select_pedido) != 1){
				$json_return = [
					'error' => 'not_found',
					'msg'=> 'não encontrado'
				];
			}else{
				$pedido = mysqli_fetch_array($select_pedido,MYSQLI_ASSOC);
				$select_items = mysqli_query($conn,"SELECT id_produto, produto, quantidade, valor, id_categoria, sub_total FROM pedidos_itens WHERE id_pedido = '".$pedido['id_pedido']."'");
				$items = [];
				if(mysqli_num_rows($select_items) > 0){

					while($it = mysqli_fetch_array($select_items,MYSQLI_ASSOC)){
						$items[] = $it;
					}
				}
				$pedido['items'] = $items;
				$json_return = [
					'error' => false,
					'count' => 1,
					'data' => $pedido
				];
			}
			
		}
		
	}
}else{
	$json_return = [
		'error' => 'request',
		'msg' => 'request'
	];
}
echo json_encode(array_map('encodeUtf8',$json_return));
?>