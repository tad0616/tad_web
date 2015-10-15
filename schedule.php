<?php
/*-----------引入檔案區--------------*/
include_once "header.php";
$plugin = "schedule";
include_once "plugin_header.php";
include_once XOOPS_ROOT_PATH . "/header.php";
//$xoopsTpl->assign('plugin', $plugin);
/*-----------function區--------------*/

/*-----------執行動作判斷區----------*/
include_once $GLOBALS['xoops']->path('/modules/system/include/functions.php');
$op         = system_CleanVars($_REQUEST, 'op', '', 'string');
$ScheduleID = system_CleanVars($_REQUEST, 'ScheduleID', 0, 'int');
$CateID     = system_CleanVars($_REQUEST, 'CateID', 0, 'int');

common_template($WebID);

switch ($op) {

    //新增資料
    case "insert":
        $ScheduleID = $tad_web_schedule->insert();
        header("location: {$_SERVER['PHP_SELF']}?WebID={$WebID}&op=edit_form&ScheduleID=$ScheduleID");
        exit;
        break;

    //更新資料
    case "update":
        $ScheduleID = $tad_web_schedule->update($ScheduleID);
        header("location: {$_SERVER['PHP_SELF']}?WebID={$WebID}&ScheduleID=$ScheduleID");
        exit;
        break;

    //輸入表格
    case "edit_form":
        $tad_web_schedule->edit_form($ScheduleID);
        break;

    //刪除資料
    case "delete":
        $tad_web_schedule->delete($ScheduleID);
        header("location: {$_SERVER['PHP_SELF']}?WebID={$WebID}");
        exit;
        break;

    case "setup_subject":
        $tad_web_schedule->setup_subject($ScheduleID);
        break;

    case "save_subject":
        $tad_web_schedule->save_subject($ScheduleID);
        header("location: {$_SERVER['PHP_SELF']}?WebID={$WebID}&op=edit_form&ScheduleID={$ScheduleID}");
        exit;

    //預設動作
    default:
        if (empty($ScheduleID)) {
            $op = 'list_all';
            $tad_web_schedule->list_all($CateID);
        } else {
            $op = 'show_one';
            $tad_web_schedule->show_one($ScheduleID);
        }
        break;

}

/*-----------秀出結果區--------------*/
include_once 'footer.php';
include_once XOOPS_ROOT_PATH . '/footer.php';
