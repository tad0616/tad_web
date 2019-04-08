<?php
include_once "../../mainfile.php";
include_once "function.php";
include_once $GLOBALS['xoops']->path('/modules/system/include/functions.php');
$op            = system_CleanVars($_REQUEST, 'op', '', 'string');
$plugin        = system_CleanVars($_REQUEST, 'plugin', '', 'string');
$WebID         = system_CleanVars($_REQUEST, 'WebID', 0, 'int');
$default_class = system_CleanVars($_REQUEST, 'default_class', 0, 'int');

switch ($op) {
    case 'get_cate_options':
        get_cate_options($WebID, $plugin);
        break;

    case 'get_default_class_mems':
        get_default_class_mems($WebID, $default_class);
        break;

}

function get_cate_options($WebID = "", $plugin = "")
{
    global $xoopsDB;
    $sql    = "select CateID, CateName from " . $xoopsDB->prefix("tad_web_cate") . " where `ColName` = '{$plugin}' AND `CateEnable` = '1' AND `WebID` = '{$WebID}' order by CateSort";
    $result = $xoopsDB->query($sql) or web_error($sql, __FILE__, __LINE__);
    $option = "";
    while (list($CateID, $CateName) = $xoopsDB->fetchRow($result)) {
        $option .= "<option value='{$CateID}'>{$CateName}</option>";
    }

    if (empty($option)) {
        $options = "<option value=''>無任何分類，無法設定小幫手</option>";
    } else {
        $options = "<option value=''>" . _MD_TCW_SELECT_CATE . "</option>{$option}";
    }
    die($options);
}

function get_default_class_mems($WebID = "", $default_class = "")
{
    global $xoopsDB;
    $sql     = "select a.MemID, a.MemNum ,b.MemName from " . $xoopsDB->prefix("tad_web_link_mems") . " as a left join " . $xoopsDB->prefix("tad_web_mems") . " as b on a.MemID=b.MemID where a.`CateID` = '{$default_class}'  order by a.MemNum";
    $result  = $xoopsDB->query($sql) or web_error($sql, __FILE__, __LINE__);
    $options = "<option value=''>" . _MD_TCW_CATE_SET_ASSISTANT . "</option>";
    while (list($MemID, $MemNum, $MemName) = $xoopsDB->fetchRow($result)) {
        $options .= "<option value='{$MemID}'>{$MemNum} {$MemName}</option>";
    }
    die($options);
}
