<?php
DEFINE('BASEAPP','true');
require $_SERVER['DOCUMENT_ROOT'].'/api/inc/conexao.php';

if($_SERVER['REQUEST_METHOD'] == 'GET'){
	if(!$headerTkn || $headerTkn != $x_token){
		$json_return = [
			'error' => 'auth'
		];
	}else{
		if(!isset($_GET['id_cliente'])){
			$json_return = [
				'error' => 'params',
				'msg' => 'Parâmetros inválidos'
			];
		}else{
			$id_cliente = mysqli_real_escape_string($conn,$_GET['id_cliente']);

			$query = "SELECT tn.tipo_notificacao,n.id_notificacao, n.titulo,n.corpo,n.imagem,n.payload, n.icon FROM notificacoes n INNER JOIN tipos_de_notificacoes tn ON tn.id_notificacao_tipo = n.id_tipo_notificacao WHERE n.id_cliente = '".$id_cliente."'";
			$select_notificacoes = mysqli_query($conn,$query) OR error_log("ERRO GET NOTIFICACAO: ".mysqli_error($conn));
			if(mysqli_num_rows($select_notificacoes) <= 0){
				$json_return = [
					'error' => 'not_found',
					'msg'=> 'não encontrado',
					'err' => mysqli_error($conn)
				];
			}else{
				$notificacoes = [];
				while($h = mysqli_fetch_array($select_notificacoes,MYSQLI_ASSOC)){
					$notificacoes[] = $h;
				}
				$json_return = [
					'error' => false,
					'count' => count($notificacoes),
					'data' => $notificacoes 
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