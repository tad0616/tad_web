<?php
include_once "../../mainfile.php";
include_once "function.php";

include_once $GLOBALS['xoops']->path('/modules/system/include/functions.php');
$op           = system_CleanVars($_REQUEST, 'op', '', 'string');
$PositionName = system_CleanVars($_REQUEST, 'PositionName', '', 'string');
$BlockID      = system_CleanVars($_REQUEST, 'BlockID', 0, 'int');
$BlockEnable  = system_CleanVars($_REQUEST, 'BlockEnable', '', 'string');
$order_arr    = system_CleanVars($_REQUEST, 'order_arr', '', 'array');

switch ($op) {
    case 'save_position':

        $sql = "update " . $xoopsDB->prefix("tad_web_blocks") . " set `BlockPosition`='{$PositionName}' where `BlockID`='{$BlockID}'";
        $xoopsDB->queryF($sql) or die("Fail  (" . date("Y-m-d H:i:s") . ")" . $sql);

        echo _MD_TCW_SAVED . " (" . date("Y-m-d H:i:s") . ")";
        break;

    case 'save_sort':

        $sort = 1;
        foreach ($order_arr as $BlockID) {
            $sql = "update " . $xoopsDB->prefix("tad_web_blocks") . " set `BlockSort`='{$sort}' where `BlockID`='{$BlockID}'";
            $xoopsDB->queryF($sql) or die("Fail  (" . date("Y-m-d H:i:s") . ")" . $sql);
            $sort++;
        }
        echo _MD_TCW_SAVED . " (" . date("Y-m-d H:i:s") . ")";
        break;

    case 'save_enable':
        $sql = "update " . $xoopsDB->prefix("tad_web_blocks") . " set `BlockEnable`='{$BlockEnable}' where `BlockID`='{$BlockID}'";
        $xoopsDB->queryF($sql) or die("Fail  (" . date("Y-m-d H:i:s") . ")" . $sql);
        echo _MD_TCW_SAVED . " (" . date("Y-m-d H:i:s") . ")";
        break;
}
