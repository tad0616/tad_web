<?php
include_once "../../mainfile.php";
include_once "function.php";
include_once $GLOBALS['xoops']->path('/modules/system/include/functions.php');
// die(var_export($_REQUEST) . '<hr>');
$op           = system_CleanVars($_REQUEST, 'op', '', 'string');
$PositionName = system_CleanVars($_REQUEST, 'PositionName', '', 'string');
$BlockID      = system_CleanVars($_REQUEST, 'BlockID', 0, 'int');
$BlockEnable  = system_CleanVars($_REQUEST, 'BlockEnable', '', 'string');
$order_arr    = system_CleanVars($_REQUEST, 'order_arr', '', 'array');
$plugin       = system_CleanVars($_REQUEST, 'plugin', '', 'string');
$WebID        = system_CleanVars($_REQUEST, 'WebID', 0, 'int');

// $status = $op . '-' . $plugin . '-' . $PositionName . '-' . $BlockID . '<hr>';

switch ($op) {
    case 'save_position':
        // $log = "<h3>save_position <small>{$status}</small></h3>";
        if ($plugin == "share" and $PositionName != 'uninstall') {
            //讀出共享區塊內容
            $sql = "select * from " . $xoopsDB->prefix("tad_web_blocks") . " where `BlockID`='{$BlockID}'";
            // $log .= "<div>$sql</div>";
            $result = $xoopsDB->queryF($sql) or web_error($sql);
            $block  = $xoopsDB->fetchArray($result);
            //複製一份給目前網站

            $BlockSort = max_blocks_sort($WebID, $PositionName);
            $sql       = "insert into `" . $xoopsDB->prefix("tad_web_blocks") . "` (`BlockName`, `BlockCopy`, `BlockTitle`, `BlockContent`, `BlockEnable`, `BlockConfig`, `BlockPosition`, `BlockSort`, `BlockShare`, `WebID`, `plugin`) values('{$block['BlockName']}', '0', '{$block['BlockTitle']}', '{$block['BlockContent']}', '1', '{$block['BlockConfig']}', '{$PositionName}', '0', '0', '{$WebID}', 'custom')";
            // $log .= "<div>$sql</div>";
            $xoopsDB->queryF($sql) or web_error($sql);
        } else {

            $sql = "update " . $xoopsDB->prefix("tad_web_blocks") . " set `BlockPosition`='{$PositionName}' where `BlockID`='{$BlockID}'";
            $xoopsDB->queryF($sql) or web_error($sql);
            // $log .= "<div>$sql</div>";
        }

        $sort = 1;
        if ($plugin == "share") {
            $shareBlockID = $BlockID;
            $copyBlockID  = get_share_to_custom_blockid($BlockID, $WebID);
        }
        foreach ($order_arr as $BlockID) {
            if ($BlockID == $shareBlockID) {
                $BlockID = $copyBlockID;
            }
            $sql = "update " . $xoopsDB->prefix("tad_web_blocks") . " set `BlockSort`='{$sort}' where `BlockID`='{$BlockID}'";
            // $log .= "<div>$sql</div>";
            $xoopsDB->queryF($sql) or web_error($sql);
            $sort++;
        }
        // die($log);
        echo _MD_TCW_SAVED . " (" . date("Y-m-d H:i:s") . ")";
        break;

    case 'save_sort':
        // $log = "<h3>save_sort <small>{$status}</small></h3>";

        $sort = 1;
        if ($plugin != "share") {
            foreach ($order_arr as $BlockID) {
                if ($BlockID == $shareBlockID) {
                    $BlockID = $copyBlockID;
                }
                $sql = "update " . $xoopsDB->prefix("tad_web_blocks") . " set `BlockSort`='{$sort}' where `BlockID`='{$BlockID}'";
                // $log .= "<div>$sql</div>";
                $xoopsDB->queryF($sql) or web_error($sql);
                $sort++;
            }
        }
        // die($log);
        echo _MD_TCW_SAVED . " (" . date("Y-m-d H:i:s") . ")";
        break;

    case 'save_enable':
        // $log = "<h3>save_enable <small>{$status}</small></h3>";
        $sql = "update " . $xoopsDB->prefix("tad_web_blocks") . " set `BlockEnable`='{$BlockEnable}' where `BlockID`='{$BlockID}'";
        // $log .= "<div>$sql</div>";
        $xoopsDB->queryF($sql) or web_error($sql);
        // die($log);
        echo _MD_TCW_SAVED . " (" . date("Y-m-d H:i:s") . ")";
        break;
}

//以共享區塊編號取得在某網站的副本編號
function get_share_to_custom_blockid($BlockID, $WebID)
{
    global $xoopsDB;
    $sql             = "select BlockName from " . $xoopsDB->prefix("tad_web_blocks") . " where `BlockID`='{$BlockID}' and `plugin`='share'";
    $result          = $xoopsDB->queryF($sql) or web_error($sql);
    list($BlockName) = $xoopsDB->fetchRow($result);

    $sql           = "select BlockID from " . $xoopsDB->prefix("tad_web_blocks") . " where `BlockName`='{$BlockName}' and `WebID`='{$WebID}' and `plugin`='custom'";
    $result        = $xoopsDB->queryF($sql) or web_error($sql);
    list($BlockID) = $xoopsDB->fetchRow($result);
    return $BlockID;
}
