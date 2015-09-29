<?php
/*-----------引入檔案區--------------*/
include_once "header.php";
$plugin = "action";
include_once "plugin_header.php";
include_once XOOPS_ROOT_PATH . "/header.php";
//$xoopsTpl->assign('plugin', $plugin);
/*-----------function區--------------*/

/*-----------執行動作判斷區----------*/
include_once $GLOBALS['xoops']->path('/modules/system/include/functions.php');
$op       = system_CleanVars($_REQUEST, 'op', '', 'string');
$ActionID = system_CleanVars($_REQUEST, 'ActionID', 0, 'int');
$CateID   = system_CleanVars($_REQUEST, 'CateID', 0, 'int');

common_template($WebID);

switch ($op) {

    //新增資料
    case "insert":
        $ActionID = $tad_web_action->insert();
        header("location: {$_SERVER['PHP_SELF']}?WebID={$WebID}&ActionID=$ActionID");
        exit;
        break;

    //更新資料
    case "update":
        $ActionID = $tad_web_action->update();
        header("location: {$_SERVER['PHP_SELF']}?WebID={$WebID}&ActionID=$ActionID");
        exit;
        break;

    //輸入表格
    case "edit_form":
        $tad_web_action->edit_form($ActionID);
        break;

    //刪除資料
    case "delete":
        $tad_web_action->delete($ActionID);
        header("location: {$_SERVER['PHP_SELF']}?WebID={$WebID}");
        exit;
        break;

    //預設動作
    default:
        if (empty($ActionID)) {
            $op = 'list_all';
            $tad_web_action->list_all($CateID);
        } else {
            $op = 'show_one';
            $tad_web_action->show_one($ActionID);
        }
        break;

}

/*-----------秀出結果區--------------*/
include_once 'footer.php';
include_once XOOPS_ROOT_PATH . '/footer.php';
