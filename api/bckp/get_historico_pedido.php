<?php
DEFINE('BASEAPP','true');
require $_SERVER['DOCUMENT_ROOT'].'/api/inc/conexao.php';

if($_SERVER['REQUEST_METHOD'] == 'GET'){
	if(!$headerTkn || $headerTkn != $x_token){
		$json_return = [
			'error' => 'auth'
		];
	}else{
		if(!isset($_GET['id_pedido'])){
			$json_return = [
				'error' => 'params',
				'msg' => 'Parâmetros inválidos'
			];
		}else{

			$id_pedido = preg_replace('/[^0-9]/', '', $_GET['id_pedido']);

			$query = "
				(SELECT 
					s.id_status id_status,
					DATE_FORMAT(hsp.dataehora, '%d/%m/%Y às %H:%i:%s') dataehora,
					s.status,
					IF(s.id_status,1,0) tipo_status
				FROM
					historico_status_pagamento hsp
				INNER JOIN
					status_pagamento s ON s.id_status = hsp.id_status_pagamento
				INNER JOIN 
					pedidos p ON p.id_pedido = hsp.id_pedido
				WHERE
					hsp.id_pedido = '{$id_pedido}')
				UNION ALL
				(SELECT 
					s.id_status id_status,
					DATE_FORMAT(hsl.dataehora, '%d/%m/%Y às %H:%i:%s') dataehora,
					s.status,
					IF(s.id_status,2,0) tipo_status
				FROM
					historico_status_logistica hsl
				INNER JOIN
					status_logistica s ON s.id_status = hsl.id_status_logistica
				INNER JOIN 
					pedidos p ON p.id_pedido = hsl.id_pedido
				WHERE
					hsl.id_pedido = '{$id_pedido}')
				ORDER BY 
					dataehora DESC, id_status 
			";

			error_log('query historico : '.$query);
			$select_historico = mysqli_query($conn,$query) or error_log("ERRO GET PEDIDO: ".mysqli_error($conn));
			if(mysqli_num_rows($select_historico) <= 0){
				$json_return = [
					'error' => 'not_found',
					'msg'=> 'não encontrado'
				];
			}else{
				while($h = mysqli_fetch_array($select_historico,MYSQLI_ASSOC)){
					$historico[] = $h;
				}
				$json_return = [
					'error' => false,
					'count' => count($historico),
					'data' => $historico
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