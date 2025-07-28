<?php
DEFINE('BASEAPP','true');
require $_SERVER['DOCUMENT_ROOT'].'/api/inc/conexao.php';
require $_SERVER['DOCUMENT_ROOT'].'/api/inc/vendors/wideimage/WideImage.php';

if($_SERVER['REQUEST_METHOD'] == 'POST'){
	$_POST = array_map('decodeUtf8',$_POST);
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
				$enviados = $_POST;
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
				foreach ($enviados as $key => $value) {
					if(!in_array($key,$params_accept)){
						unset($enviados[$key]);
					}
				}

				if(isset($enviados['data_de_nascimento']) && !empty($enviados['data_de_nascimento'])){
					$explode_barra = explode('/',$_POST['data_de_nascimento']);
					if(is_array($explode_barra) && count($explode_barra) == 3){
						$enviados['data_de_nascimento'] = $explode_barra[2].'-'.$explode_barra[1].$explode_barra[0];
					}else{
						$explode_dash = explode('-',$enviados['data_de_nascimento']);
						if(!is_array($explode_dash) || (is_array($explode_dash) && count($explode_dash) != 3)){
							unset($enviados['data_de_nascimento']);
						}
					}
				}
				$run_cliente = mysqli_query($conn,"SELECT * FROM clientes WHERE cpf = '".$cpf."' OR email = '".$enviados['email']."'");
				if(mysqli_num_rows($run_cliente) > 0){
					$json_return = [
						'error' => 'params..',
						'msg' => "Parâmetros inválidos"
					];
				}else{
					if(isset($_POST['imagem']) && $_POST['imagem'] != ''){
						if(strpos($_POST['imagem'], 'base64,') !== false){
							//pegar do base64
							list($type, $data) = explode(';', $_POST['imagem']);
							$type = explode('/',$type);
							if(preg_match('/^data:image\/(\w+);base64,/', $_POST['imagem'], $type)){
				                $data = substr($data, strpos($data, ',') + 1);
                				$type = strtolower($type[1]); // jpg, png, gif
                				$path = '/api/images/clientes/';
	                            $new_file_name = "old_".md5(uniqid(strtotime(date("Y-m-d H:i:s")))).".".$type;

	                            file_put_contents($_SERVER['DOCUMENT_ROOT'].$path.$new_file_name, $data);
	                            if(is_file($_SERVER['DOCUMENT_ROOT'].$path.$new_file_name)){
	                            	$image = WideImage::load($_SERVER['DOCUMENT_ROOT'].$path.$new_file_name);
	                                $new_file_full = str_replace('old_', '', $new_file_name);

	                                list($width, $height) = getimagesize($_SERVER['DOCUMENT_ROOT'].$path.$new_file_name);
	                                $centerx = round($width/2);
	                                $centery = round($height/2);

	                                $cropWidth  = 300;
	                                $cropHeight = 300;

	                                $cropWidthHalf  = round($cropWidth / 2); // could hard-code this but I'm keeping it flexible
	                                $cropHeightHalf = round($cropHeight / 2);

	                                $x1 = max(0, $centerx - $cropWidth);
	                                $y1 = max(0, $centery - $cropHeight);

	                                $x2 = min($width, $cropWidth);
	                                $y2 = min($height,  $cropHeight);
	                                if($width != $height){
	                                    $new_image = $image->crop($x1, $y1, 300,300)->resize(300,300);
	                                }else{
	                                    $new_image = $image->resize(300,300);
	                                }
	                                $new_image->saveToFile($_SERVER['DOCUMENT_ROOT'].$path.$new_file_full);                               

	                                
	                                if(is_file($_SERVER['DOCUMENT_ROOT'].$path.$new_file_name)){
	                                    unlink($_SERVER['DOCUMENT_ROOT'].$path.$new_file_name);
	                                }

	                                $enviados['imagem'] = $new_file_full;
	                            }
				            }
						}elseif(strpos($_POST['imagem'],'https://') !== false){
							//pegar da url
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
										$enviados['imagem'] = $newname;
									}
							    }

							}
						}
					}
					$sql_inserir_pedido = "INSERT INTO clientes (".implode(', ',array_keys($enviados)). ") VALUES ('".implode("','",array_values($enviados))."')";
					$ERRO = 'no error';
					$insert_pedido = mysqli_query($conn,$sql_inserir_pedido) or $ERRO = mysqli_error($conn);
					if($id = mysqli_insert_id($conn)){
						$json_return = [
							'error' => false,
							'msg' => "Cadastrado",
							'data' => [
								'id_cliente' => $id,
								'nome'=>$enviados['nome'],
								'cpf' => $cpf,
								'senha' => $enviados['senha']
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