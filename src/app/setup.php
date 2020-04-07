<?php
header('Access-Control-Allow-Origin: *');

require_once __DIR__.'/../../vendor/autoload.php';
use OSS\OssClient;
use OSS\Core\OssException;

$accessKeyId = "<您的阿里云OSS -> accessKeyId>";
$accessKeySecret = "<您的阿里云OSS -> accessKeySecret>";
$endpoint = "<您的阿里云OSS Bucket所在结点 -> endpoint>";
$bucket= "<您的阿里云OSS Bucket名称>";

try {
    $ossClient = new OssClient($accessKeyId, $accessKeySecret, $endpoint);
} catch (OssException $e) {
    print_r($e->getMessage());
}

?>
