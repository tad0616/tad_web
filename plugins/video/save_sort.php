<?php
require_once dirname(dirname(dirname(dirname(__DIR__)))) . '/mainfile.php';
require_once dirname(dirname(__DIR__)) . '/function.php';
header('HTTP/1.1 200 OK');
$xoopsLogger->activated = false;
$sort = 1;
foreach ($_POST['VideoID'] as $VideoID) {
    $sql = 'UPDATE `' . $xoopsDB->prefix('tad_web_video') . '` SET `VideoSort`=? WHERE `VideoID`=?';
    Utility::query($sql, 'ii', [$sort, $VideoID]) or die(_TAD_SORT_FAIL . ' (' . date('Y-m-d H:i:s') . ')' . $sql);
    $sort++;
}

echo _TAD_SORTED . '(' . date('Y-m-d H:i:s') . ')';
