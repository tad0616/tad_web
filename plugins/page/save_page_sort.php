<?php
require_once dirname(dirname(dirname(dirname(__DIR__)))) . '/mainfile.php';
require_once dirname(dirname(__DIR__)) . '/function.php';
$sort = 1;
// $msg  = "";
foreach ($_POST['li'] as $PageID) {
    $sql = 'update ' . $xoopsDB->prefix('tad_web_page') . " set `PageSort`='{$sort}' where PageID='{$PageID}'";
    $xoopsDB->queryF($sql) or die(_TAD_SORT_FAIL . ' (' . date('Y-m-d H:i:s') . ')' . $sql);
    // $msg .= "$sql<br>";
    $sort++;
}

echo _TAD_SORTED . '(' . date('Y-m-d H:i:s') . ')';
