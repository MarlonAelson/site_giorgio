<?php 
$server = '159.203.112.151';
$user = 'delivery_mysql';
$pass = '9ueS5LMwJ2DCm4Ax';
$db = 'delivery';


$base_url = 'https://hummdelivery.com/api/';
$conn = mysqli_connect($server,$user,$pass,$db) or die(json_encode(['error'=>'erri']));
require dirname(__FILE__).'/funcoes.php';
require dirname(__FILE__).'/IpDetails.class.php';
include dirname(__FILE__)."/vendor/autoload.php";

$browser = new WhichBrowser\Parser($headers);
$IpDetails = new IpDetails(getUserIp());
$IpDetails->scan();