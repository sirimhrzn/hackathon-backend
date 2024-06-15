<?php
$vendor_id = $argv[1];
$vendor_store = $argv[2];

$nginxConfPath = "/home/sirimhrzn/nginx/lh.conf";

$serverConfig = file_get_contents(__DIR__ . "/nginx_template");
$serverConfig = str_replace("{{vendor_id}}",$vendor_id,$serverConfig);
$serverConfig = str_replace("{{vendor_store}}",$vendor_store,$serverConfig);
file_put_contents($nginxConfPath, $serverConfig, FILE_APPEND | LOCK_EX);
exec("docker exec -it sp-nginx nginx -s reload");


