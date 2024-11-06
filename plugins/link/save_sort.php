<?php
use XoopsModules\Tadtools\Utility;
require_once dirname(dirname(dirname(dirname(__DIR__)))) . '/mainfile.php';
require_once dirname(dirname(__DIR__)) . '/function.php';
$xoopsLogger->activated = false;
$sort = 1;
foreach ($_POST['LinkID'] as $LinkID) {
    $sql = 'UPDATE `' . $xoopsDB->prefix('tad_web_link') . '` SET `LinkSort`=? WHERE `LinkID`=?';
    Utility::query($sql, 'ii', [$sort, $LinkID]) or die(_TAD_SORT_FAIL . ' (' . date('Y-m-d H:i:s') . ')' . $sql);
    $sort++;
}

echo _TAD_SORTED . '(' . date('Y-m-d H:i:s') . ')';
