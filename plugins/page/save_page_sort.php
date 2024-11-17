<?php
use XoopsModules\Tadtools\Utility;
require_once dirname(dirname(dirname(dirname(__DIR__)))) . '/mainfile.php';
require_once dirname(dirname(__DIR__)) . '/function.php';
$sort = 1;
// $msg  = "";
foreach ($_POST['li'] as $PageID) {
    $sql = 'UPDATE `' . $xoopsDB->prefix('tad_web_page') . '` SET `PageSort`=? WHERE `PageID`=?';
    Utility::query($sql, 'ii', [$sort, $PageID]) or die(_TAD_SORT_FAIL . ' (' . date('Y-m-d H:i:s') . ')' . $sql);
    // $msg .= "$sql<br>";
    $sort++;
}

echo _TAD_SORTED . '(' . date('Y-m-d H:i:s') . ')';
