<?php
DEFINE('BASEAPP','true');

require $_SERVER['DOCUMENT_ROOT'].'/api/inc/conexao.php';

if($_SERVER['REQUEST_METHOD'] == 'GET'){
	if(!$headerTkn || $headerTkn != $x_token){
		$json_return = [
			'error' => 'auth'
		];
	}else{
		$json_return = [
			'error' => false,
			'count' => 3,
			'alterado' => '2020-12-11 11:33:17',
			'data' => [
				[
					'id_slide' => 1,
					'dataehora' => date('Y-m-d H:i:s'),
					//'slide' => 'data:image/'.pathinfo('images/slide1.jpg',PATHINFO_EXTENSION).";base64,".base64_encode(file_get_contents('images/slide1.jpg'))
					'slide' => 'https://'.$_SERVER['HTTP_HOST']."/api/images/slide1.jpg"
				],
				[
					'id_slide' => 2,
					'dataehora' => date('Y-m-d H:i:s'),
					//'slide' => 'data:image/'.pathinfo('images/slide2.jpg',PATHINFO_EXTENSION).";base64,".base64_encode(file_get_contents('images/slide2.jpg'))
					'slide' => 'https://'.$_SERVER['HTTP_HOST']."/api/images/slide2.jpg"
				],
				[
					'id_slide' => 3,
					'dataehora' => date('Y-m-d H:i:s'),
					//'slide' => 'data:image/'.pathinfo('images/slide3.jpg',PATHINFO_EXTENSION).";base64,".base64_encode(file_get_contents('images/slide3.jpg'))
					'slide' => 'https://'.$_SERVER['HTTP_HOST']."/api/images/slide3.jpg"
				]

			]
		];
	}
}else{
	$json_return = [
		'error' => 'request'
	];
}
echo json_encode(array_map('encodeUtf8',$json_return));
?>