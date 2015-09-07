<?php
include_once 'header.php';
include_once "../function.php";
$sort = 1;
foreach ($_POST['tr'] as $WebID) {
    $sql = "update " . $xoopsDB->prefix("tad_web") . " set `WebSort`='{$sort}' where `WebID`='{$WebID}'";
    $xoopsDB->queryF($sql) or die(_MA_TCW_UPDATE_FAIL . " (" . date("Y-m-d H:i:s") . ")" . $sql);
    $sort++;
}

echo _MA_TCW_SAVE_SORT_OK . "(" . date("Y-m-d H:i:s") . ")";
