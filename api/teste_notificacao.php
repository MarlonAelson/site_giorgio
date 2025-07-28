<?php
DEFINE('BASEAPP', 'ok');
use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;
use Kreait\Firebase\Exception\Messaging\InvalidMessage;
use Kreait\Firebase\Messaging\AndroidConfig;


include ("./inc/conexao_mysqli.php");
$titulo = isset($_GET['titulo']) && !empty($_GET['titulo']) ? mysqli_escape_string($con,$_GET['titulo']) : "PRIMEIRA ETAPA DE ADESÃO AOS NOVOS PRODUTOS DO PLANO DE SAÚDE DA ASFARN FOI UM SUCESSO!";
$v  =  isset($_GET['corpo']) && !empty($_GET['corpo']) ? strip_tags(mysqli_escape_string($con,$_GET['corpo'])) : strip_tags('Mais de 85 vidas já ingressaram nos novos produtos do convênio do plano de saúde da ASFARN/UNIMED NATAL, durante os primeiros 15 dias de abril.');
$corpo = strlen($v) > 130 ? mb_substr($v, 0,130)."..." : $v;
$imagem  =  isset($_GET['imagem']) && !empty($_GET['imagem']) ? mysqli_escape_string($con,$_GET['imagem']) : 'http://www.asfarn.com.br/comunicacao/noticias/images/thumb_063ae1a9374dd2f248f0c85de59f8cbd.jpeg';
$payload = ['noticia'=>760];
//gerar_notificacao($titulo,$corpo,$imagem,$payload,1);
$titulo_not = mb_convert_encoding($titulo,'UTF-8');
$corpo_not = mb_convert_encoding($corpo,'UTF-8');

$factory = (new Factory)
	    ->withServiceAccount($_SERVER['DOCUMENT_ROOT']."/api/inc/private/humm-delivery-debug-firebase-adminsdk-rz2sc-010674f919.json");
$messaging = $factory->createMessaging();
//'dYB3nUe-Sz2tUlN-lYNy23:APA91bFKPcs_C6_5v-BYGyy8RUUFNSz_Qd9oNLZSBvx8RTQHqamYVoK87mcIq1XtkAqkj-Yyf7FleESzpZRM_iK0-qDiRTymo9xHWHCqOg50y9QUGzAEKwRNfS8PZqecBgnf00J1ZuN5'
$TKNS = ['cpC1b2jnREH8sB3nguuCyz:APA91bEnNGN2H_hWtt4HEAYvryX6VSVHpbvhNIa2mIpYDwEeu0L4_2USdxqIbL3QABz9stv3bW2GCPmjWblDSnExqtUjNEmxgdIbAsMryvQdePnjxQx9XedVeTxoczSi2LL_FgoRf0zx'];

$notification = Notification::fromArray([
    'title' => $titulo_not,
    'body' => $corpo_not,
    'image' => $imagem,
    'icon' => 'notification_icon',
	'color' => '#1A2843'
]);

$message = CloudMessage::new();
$message = $message->withNotification($notification);
$config = AndroidConfig::fromArray([
	'notification' => [
		'title' => $titulo_not,
	    'body' => $corpo_not,
	    'image' => $imagem,
	    'icon' => 'notification_icon',
		'color' => '#1A2843'
	]
]);
$message = $message->withAndroidConfig($config);
$message = $message->withData($payload);
$result = $messaging->validateRegistrationTokens($TKNS);


if($result['valid']){
	try{
		$response = $messaging->sendMulticast($message, $TKNS);
		//$response = $messaging->validate($message);
		echo 'valid';
		echo "<pre>";
		var_dump($response);
	}catch (Exception $e){
		echo "<pre>";
		var_dump($e->getMessage());
	}
}else{
	echo 'invalids';
}


function gerar_notificacao($titulo,$corpo,$imagem = '',$payload = [],$id_tipo = 1){
	global $con;
	
	$titulo_not = mb_convert_encoding($titulo,'UTF-8');
	$corpo_not = mb_convert_encoding($corpo,'UTF-8');

	$tokens = get_tokens_firebase();
	if($tokens){
		$payload_mysql = json_encode($payload);
		mysqli_query($con,"INSERT INTO notificacoes 
			(titulo,corpo,imagem,payload,id_tipo_notificacao) VALUES 
			('{$titulo}','{$corpo}','{$imagem}','{$payload_mysql}','{$id_tipo}')");
		$id_notificacao = mysqli_insert_id($con);
		$payload['id_notificacao'] = $id_notificacao;
		$payload['imagem'] = $imagem;
		$factory = (new Factory)
	    ->withServiceAccount($_SERVER['DOCUMENT_ROOT']."/api/inc/private/humm-delivery-debug-firebase-adminsdk-rz2sc-010674f919.json");

		$messaging = $factory->createMessaging();
		$TKNS = [];
		foreach ($tokens as $key => $fcm) {
			if(!empty($fcm['token_push'])){
				//$message->addRecipient(new Device($fcm['token_push']));
				$TKNS[] = $fcm['token_push'];
			}
			mysqli_query($con,"INSERT INTO notificacoes_dispositivos (id_dispositivo,id_notificacao) VALUES ('".$fcm['id_dispositivo']."','".$id_notificacao."')");
			
		}
		
		$notification = Notification::fromArray([
		    'title' => $titulo_not,
		    'body' => $corpo_not,
		    'image' => $imagem,
		    'icon' => 'notification_icon',
			'color' => '#1A2843'
		]);
		$message = CloudMessage::new();
		$message = $message->withNotification($notification);
		$config = AndroidConfig::fromArray([
			'notification' => [
				'title' => $titulo_not,
			    'body' => $corpo_not,
			    'image' => $imagem,
			    'icon' => 'notification_icon',
				'color' => '#1A2843'
			]
		]);
		$message = $message->withAndroidConfig($config);
		$message = $message->withData($payload);
		$result = $messaging->validateRegistrationTokens($TKNS);
		if($result['valid']){
			try{
	    		$response = $messaging->sendMulticast($message, $TKNS);
	    		//$response = $messaging->validate($message);
	    		echo 'valid';
	    		echo "<pre>";
	    		var_dump($response);
	    	}catch (Exception $e){
	    		echo "<pre>";
	    		var_dump($e->getMessage());
	    	}
		}else{
			echo 'invalids';
		}
    	
    	
		
	}else{
		echo "sem token";
	}
	echo "<br>".$id_notificacao;
}

function get_tokens_firebase(){
	global $con;

	$run_query = mysqli_query($con,"SELECT id_dispositivo, token_push FROM dispositivos WHERE (dataehora_excluido IS NULL OR dataehora_excluido = '' or dataehora_excluido = '0000-00-00 00:00:00') AND notificacao_noticias = 1") or print mysqli_error($con);

	if(mysqli_num_rows($run_query) > 0){
		$ret = [];
		while($c = mysqli_fetch_assoc($run_query)){
			$ret[] =  $c;
		}
		return $ret;
	}
	return false;
}


?>