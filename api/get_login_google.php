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
				$email = mysqli_escape_string($conn,$_POST['email']);
				$run_cliente = mysqli_query($conn,"SELECT * FROM clientes WHERE google_login_id = '".$google_login_id."'");
				if(mysqli_num_rows($run_cliente) != 1){
					if(isset($_POST['email']) && filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)){
						$run_cliente_email = mysqli_query($conn,"SELECT * FROM clientes WHERE email = '".$_POST['email']."'");
						if(mysqli_num_rows($run_cliente_email) <= 0){
							$json_return = [
								'error' => 'not_found',
								'msg' => "Não encontrado."
							];
						}else{
							$cliente = mysqli_fetch_assoc($run_cliente_email);
							if($cliente['imagem'] == '' && isset($_POST['imagem']) && $_POST['imagem'] != ''){

								//$imageContent = file_get_contents($_POST['imagem']);

								$contents = file_get_contents($_POST['imagem']);
								$pattern = "/^content-type\s*:\s*(.*)$/i";
								if (($header = array_values(preg_grep($pattern, $http_response_header))) &&
								    (preg_match($pattern, $header[0], $match) !== false))
								{
								    $content_type = $match[1];
								    $ext = strtolower(end(explode('/',$content_type)));
								    if(in_array($ext,['png','jpg','jpeg','gif'])){
								    	$newname = md5(uniqid().date('Y-m-d H:i:s')).".".$ext;
								    	$path = '/api/images/clientes';
								    	$fullpath = $_SERVER['DOCUMENT_ROOT'].$path;
								    	if(!is_dir($fullpath)){
								    		mkdir($fullpath,775,true);
								    	}
										$fullname = $fullpath."/".$newname;
										$OK = file_put_contents($fullname,$contents);
										if($OK !== false){
											mysqli_query($conn,"UPDATE clientes SET imagem = '".$newname."' WHERE id_cliente = '".$cliente['id_cliente']."'");
										}
								    }

								}

								// $newname = md5(uniqid().date('Y-m-d H:i:s')).".png";

								// $path = '/api/images/clientes/TESTES/';
								// $fullname = $_SERVER['DOCUMENT_ROOT'].$path.$newname;
								// $OK = file_put_contents($fullname,$imageContent);
								// var_dump($fullname);
								// var_dump($OK);

							}
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

					$cliente = mysqli_fetch_assoc($run_cliente);
					if($cliente['imagem'] == '' && isset($_POST['imagem']) && $_POST['imagem'] != ''){

						//$imageContent = file_get_contents($_POST['imagem']);

						$contents = file_get_contents($_POST['imagem']);
						$pattern = "/^content-type\s*:\s*(.*)$/i";
						if (($header = array_values(preg_grep($pattern, $http_response_header))) &&
						    (preg_match($pattern, $header[0], $match) !== false))
						{
						    $content_type = $match[1];
						    $ext = strtolower(end(explode('/',$content_type)));
						    if(in_array($ext,['png','jpg','jpeg','gif'])){
						    	$newname = md5(uniqid().date('Y-m-d H:i:s')).".".$ext;
						    	$path = '/api/images/clientes';
						    	$fullpath = $_SERVER['DOCUMENT_ROOT'].$path;
						    	if(!is_dir($fullpath)){
						    		mkdir($fullpath,775,true);
						    	}
								$fullname = $fullpath."/".$newname;
								$OK = file_put_contents($fullname,$contents);
								if($OK !== false){
									mysqli_query($conn,"UPDATE clientes SET imagem = '".$newname."' WHERE id_cliente = '".$cliente['id_cliente']."'");
								}
						    }

						}

						// $newname = md5(uniqid().date('Y-m-d H:i:s')).".png";

						// $path = '/api/images/clientes/TESTES/';
						// $fullname = $_SERVER['DOCUMENT_ROOT'].$path.$newname;
						// $OK = file_put_contents($fullname,$imageContent);
						// var_dump($fullname);
						// var_dump($OK);

					}
					$json_return = [
						'error' => false,
						'msg' => "Encontrado1",
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