<?php
require_once dirname(dirname(dirname(dirname(__DIR__)))) . '/mainfile.php';
require_once dirname(dirname(__DIR__)) . '/function.php';
$sort = 1;
foreach ($_POST['VideoID'] as $VideoID) {
    $sql = 'update ' . $xoopsDB->prefix('tad_web_video') . " set `VideoSort`='{$sort}' where VideoID='{$VideoID}'";
    $xoopsDB->queryF($sql) or die(_TAD_SORT_FAIL . ' (' . date('Y-m-d H:i:s') . ')' . $sql);
    $sort++;
}
header('HTTP/1.1 200 OK');
echo _TAD_SORTED . '(' . date('Y-m-d H:i:s') . ')';
