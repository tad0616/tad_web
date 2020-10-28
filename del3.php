<?php
// $ip = '120.115.2.85';
$ip = '172.16.10.185';
$mysqli = new mysqli($ip, 'root', 'x2hx-2ki3', "class");
if ($mysqli->connect_error) {
    $result = "連不上<br>{$mysqli->connect_error}";
} else {
    $result = 'ok';
}
// $mysqli->set_charset("utf8");
die('<!DOCTYPE html>
        <html lang="zh-tw">
        <head>
        <meta charset="UTF-8">
        <title>Document</title>
        </head>
        <body>
        <p>' . $result . '</p>
        </body>
        </html>');
