<?php

require_once __DIR__ . '/setup.php';

// 老版本强制更新
if (!empty(file_get_contents("php://input"))) {
	$apk = "releases/app-release.apk";
	$changelog = "releases/changelog.md";
	$timeout = 3600;
	$signedUrl = $ossClient->signUrl($bucket, $apk, $timeout, "GET");

	try {
	    $changelog = $ossClient->getObject($bucket, $changelog);
	} catch (OssException $e) {
	    echo packMsg(false, $e->getMessage());
	    return;
	}
	
	$arr = array(
		"android_url" => $signedUrl,
		"changelog" => $changelog
	);
	echo packMsg(true, $arr);
	return;
}

// 新的更新方案
$info = $_POST;

function packMsg($statusCode = false, $message = '') {
	return json_encode([
		'statusCode' => $statusCode,
		'message' => $message,
	], JSON_UNESCAPED_UNICODE);
}

try {
    $output = $ossClient->getObject($bucket, "releases/output.json");
    $output = json_decode($output);
    $latestInfo = $output["0"]->apkData;
} catch (OssException $e) {
    echo packMsg(false, $e->getMessage());
    return;
}

// 比对版本
if ($info["versionName"] == $latestInfo->versionName && 
intval($info["versionCode"]) < intval($latestInfo->versionCode)) {
	$needUpdate = true;
} else {
	$verName = explode('.', $info["versionName"]);
	$latestVerName = explode('.', $latestInfo->versionName);
	$needUpdate = false;
	for ($i = 0; $i < sizeof($verName); $i++) {
		if (intval($verName[$i]) < intval($latestVerName[$i])) {
			$needUpdate = true;
			break;
		}
	}
}

// 比对结果处理
if (!$needUpdate) {
	echo packMsg(false, "应用已是最新版本");
} else {
	$apk = "releases/app-release.apk";
	$changelog = "releases/changelog.md";
	$timeout = 3600;
	$signedUrl = $ossClient->signUrl($bucket, $apk, $timeout, "GET");

	try {
	    $changelog = $ossClient->getObject($bucket, $changelog);
	} catch (OssException $e) {
	    echo packMsg(false, $e->getMessage());
	    return;
	}
	
	$arr = array(
		"android_url" => $signedUrl,
		"changelog" => $changelog
	);
	echo packMsg(true, $arr);
}

?>