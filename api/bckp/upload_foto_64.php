<?php
DEFINE('BASEAPP','true');
require $_SERVER['DOCUMENT_ROOT'].'/api/inc/conexao.php';
require $_SERVER['DOCUMENT_ROOT'].'/api/inc/vendor/wideimage/WideImage.php';

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    if(!$headerTkn || $headerTkn != $x_token){
        $json_return = [
            'error' => 'auth'
        ];
    }else{
        if(
            !isset($_POST['id_cliente']) || empty($_POST['id_cliente']) || 
            !isset($_POST['imagem']) || empty($_POST['imagem'])
            
        ){
            $json_return = [
                'error' => 'params',
                'msg'  => 'Parâmetros inválidos'
            ];
        }else{
            list($type, $data) = explode(';', $_POST['imagem']);
            // list(, $data)      = explode(',', $data);
            // $data = base64_decode($data);
            
            // $data = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $data));
            $type = explode('/',$type);
            // var_dump($type);
            // var_dump(preg_match('/^data:image\/(\w+);base64,/', $_POST['imagem'], $type));

            if(!preg_match('/^data:image\/(\w+);base64,/', $_POST['imagem'], $type)){
                $json_return = [
                    'error' => 'params',
                    'msg'  => 'Parâmetros inválidos.'
                ];
            }else{

                $data = substr($data, strpos($data, ',') + 1);
                $type = strtolower($type[1]); // jpg, png, gif

                if (!in_array($type, [ 'jpg', 'jpeg', 'gif', 'png' ])) {
                    $json_return = [
                        'error' => 'image',
                        'msg'  => 'sem permissão'
                    ];
                }else{
                    $data = str_replace( ' ', '+', $data );
                    $data = base64_decode($data);

                    if ($data === false) {
                        $json_return = [
                            'error' => 'image',
                            'msg'  => 'invalido'
                        ];
                    }else{
                        $protocol=$_SERVER['PROTOCOL'] = isset($_SERVER['HTTPS']) && !empty($_SERVER['HTTPS']) ? 'https' : 'http';
                        $cpf = preg_replace('/[^0-9]/', '', $_POST['id_cliente']);
                        $run_cliente = mysqli_query($conn,"SELECT * FROM clientes WHERE cpf = '".$cpf."'") or error_log('error mysql uploadfoto cliente: '.mysqli_error($conn));
                        if(mysqli_num_rows($run_cliente) > 0){
                            $cliente = mysqli_fetch_array($run_cliente,MYSQLI_ASSOC);

                            $path = '/api/images/clientes/';
                            $new_file_name = "old_".md5($cliente['id_cliente'].uniqid(strtotime(date("Y-m-d H:i:s")))).".{$type}";

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
                                
                                $update = mysqli_query($conn,"UPDATE clientes SET imagem = '".$new_file_full."' WHERE id_cliente = '".$cliente['id_cliente']."'");
                                if($update){
                                    if($cliente['imagem'] != ''){
                                        if(is_file($_SERVER['DOCUMENT_ROOT'].$path.$cliente['imagem'])){
                                            unlink($_SERVER['DOCUMENT_ROOT'].$path.$cliente['imagem']);
                                        }
                                    }
                                    $json_return = [
                                        'error' => false,
                                        'msg'  => 'Imagem atualizada',
                                        'image' => $protocol.'://'.$_SERVER['HTTP_HOST'].$path.$new_file_full,
                                        'w' => $x2,
                                        'h' => $y2
                                    ];
                                }else{
                                    $json_return = [
                                        'error' => 'update',
                                        'msg'  => 'erro ao atualizar imagem'
                                    ];
                                }
                                
                            }else{
                                $json_return = [
                                    'error' => 'image',
                                    'msg'  => 'Imagem não salva'
                                ];
                            }
                        }
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