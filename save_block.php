<?php
include_once "../../mainfile.php";
include_once "function.php";

include_once $GLOBALS['xoops']->path('/modules/system/include/functions.php');
$op           = system_CleanVars($_REQUEST, 'op', '', 'string');
$WebID        = system_CleanVars($_REQUEST, 'WebID', 0, 'int');
$PositionName = system_CleanVars($_REQUEST, 'PositionName', '', 'string');
$BlockName    = system_CleanVars($_REQUEST, 'BlockName', '', 'string');
$BlockEnable  = system_CleanVars($_REQUEST, 'BlockEnable', '', 'string');
$order_arr    = system_CleanVars($_REQUEST, 'order_arr', '', 'array');

switch ($op) {
    case 'save_position':

        $sql = "update " . $xoopsDB->prefix("tad_web_blocks") . " set `BlockPosition`='{$PositionName}' where `BlockName`='{$BlockName}' and WebID='{$WebID}'";
        $xoopsDB->queryF($sql) or die("Fail  (" . date("Y-m-d H:i:s") . ")" . $sql);

        echo "Saved (" . date("Y-m-d H:i:s") . ") {$sql}";
        break;

    case 'save_sort':

        $sort = 1;
        foreach ($order_arr as $BlockName) {
            $sql = "update " . $xoopsDB->prefix("tad_web_blocks") . " set `BlockSort`='{$sort}' where `BlockName`='{$BlockName}' and WebID='{$WebID}'";
            $xoopsDB->queryF($sql) or die("Fail  (" . date("Y-m-d H:i:s") . ")" . $sql);
            $sort++;
        }
        echo "Saved (" . date("Y-m-d H:i:s") . ") {$sql}";
        break;

    case 'save_enable':
        $sql = "update " . $xoopsDB->prefix("tad_web_blocks") . " set `BlockEnable`='{$BlockEnable}' where `BlockName`='{$BlockName}' and WebID='{$WebID}'";
        $xoopsDB->queryF($sql) or die("Fail  (" . date("Y-m-d H:i:s") . ")" . $sql);
        echo "Saved (" . date("Y-m-d H:i:s") . ") {$sql}";
        break;
}
