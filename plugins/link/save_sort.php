<?php
include_once "../../../../mainfile.php";
include_once "../../function.php";
$sort = 1;
foreach ($_POST['LinkID'] as $LinkID) {
    $sql = "update " . $xoopsDB->prefix("tad_web_link") . " set `LinkSort`='{$sort}' where LinkID='{$LinkID}'";
    $xoopsDB->queryF($sql) or die(_TAD_SORT_FAIL . " (" . date("Y-m-d H:i:s") . ")" . $sql);
    $sort++;
}
echo _TAD_SORTED . "(" . date("Y-m-d H:i:s") . ")";
