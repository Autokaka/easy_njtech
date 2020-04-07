<?php

$report = $_POST;

$logFile = fopen("reports/".$report["time"].".json", "w") or die("文件创建失败");
fwrite($logFile, json_encode($report, JSON_UNESCAPED_UNICODE));
fclose($logFile);

?>