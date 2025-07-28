<?php

DEFINED('BASEAPP') OR die('403');

$server = '159.203.112.151';
$user = 'delivery_mysql';
$pass = '9ueS5LMwJ2DCm4Ax';
$db = 'delivery';

$base_url = 'https://delivery-api.velty.com.br/api/';

$conn = mysqli_connect($server,$user,$pass,$db) or die(mysqli_error($conn));

$x_token = 'ZDAwMjEzMDFhZjcxOWQyYzY3MjQxMGVmNGMxYzFhZmY';
$headers = getallheaders();
header('Content-type: application/json');
header('Access-Control-Allow-Origin:    *');
header('Access-Control-Allow-Headers:   *');
header('Access-Control-Allow-Content:   *');
header('Access-Control-Allow-Methods:   *');
header('Access-Control-Expose-Headers:  *');

$headerTkn = (isset($headers['Token']) ? $headers['Token'] : false);

include dirname(__FILE__).'/funcoes.php';