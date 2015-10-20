<?php
/*-----------引入檔案區--------------*/
include_once "header.php";
$plugin = "calendar";
include_once "plugin_header.php";
include_once XOOPS_ROOT_PATH . "/header.php";
//$xoopsTpl->assign('plugin', $plugin);
/*-----------function區--------------*/

/*-----------執行動作判斷區----------*/
include_once $GLOBALS['xoops']->path('/modules/system/include/functions.php');
$op         = system_CleanVars($_REQUEST, 'op', '', 'string');
$CalendarID = system_CleanVars($_REQUEST, 'CalendarID', 0, 'int');
$CateID     = system_CleanVars($_REQUEST, 'CateID', 0, 'int');

common_template($WebID);

switch ($op) {

    //新增資料
    case "insert":
        $CalendarID = $tad_web_calendar->insert();
        header("location: {$_SERVER['PHP_SELF']}?WebID={$WebID}&CalendarID=$CalendarID");
        exit;
        break;

    //更新資料
    case "update":
        $CalendarID = $tad_web_calendar->update($CalendarID);
        header("location: {$_SERVER['PHP_SELF']}?WebID={$WebID}&CalendarID=$CalendarID");
        exit;
        break;

    //輸入表格
    case "edit_form":
        $tad_web_calendar->edit_form($CalendarID);
        break;

    //刪除資料
    case "delete":
        $tad_web_calendar->delete($CalendarID);
        header("location: {$_SERVER['PHP_SELF']}?WebID={$WebID}");
        exit;
        break;

    //預設動作
    default:
        if (empty($CalendarID)) {
            $op = 'list_all';
            $tad_web_calendar->list_all($CateID);
        } else {
            $op = 'show_one';
            $tad_web_calendar->show_one($CalendarID);
        }
        break;

}
/*-----------秀出結果區--------------*/
include_once 'footer.php';
include_once XOOPS_ROOT_PATH . '/footer.php';
