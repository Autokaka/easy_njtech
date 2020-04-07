<?php

require_once(__DIR__.'/../njtech.php');
require_once(__DIR__.'/../crypto.php');
$configs = file_get_contents("php://input");

// 数据解密
$crypto = Crypto::getInstance();
$configs = $crypto->decrypt($configs);
$configs = json_decode($configs, true);

// 测试接口
$configs = [
	'username' => '1405170120',
	'password' => 'Skylucas244013',
];

// 执行查询
$client = new Njtech($configs);
echo($client->score());

?>