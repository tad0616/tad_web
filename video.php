<?php
/*-----------引入檔案區--------------*/
include_once "header.php";
$plugin = "video";
include_once "plugin_header.php";
include_once XOOPS_ROOT_PATH . "/header.php";
//$xoopsTpl->assign('plugin', $plugin);
/*-----------function區--------------*/

/*-----------執行動作判斷區----------*/
include_once $GLOBALS['xoops']->path('/modules/system/include/functions.php');
$op      = system_CleanVars($_REQUEST, 'op', '', 'string');
$VideoID = system_CleanVars($_REQUEST, 'VideoID', 0, 'int');
$CateID  = system_CleanVars($_REQUEST, 'CateID', 0, 'int');

common_template($WebID);

switch ($op) {

    //新增資料
    case "insert":
        $VideoID = $tad_web_video->insert();
        header("location: {$_SERVER['PHP_SELF']}?WebID={$WebID}&VideoID=$VideoID");
        exit;
        break;

    //更新資料
    case "update":
        $VideoID = $tad_web_video->update();
        header("location: {$_SERVER['PHP_SELF']}?WebID={$WebID}&VideoID=$VideoID");
        exit;
        break;
    //輸入表格
    case "edit_form":
        $tad_web_video->edit_form($VideoID);
        break;

    //刪除資料
    case "delete":
        $tad_web_video->delete($VideoID);
        header("location: {$_SERVER['PHP_SELF']}?WebID={$WebID}");
        exit;
        break;

    //預設動作
    default:
        if (empty($VideoID)) {
            $op = 'list_all';
            $tad_web_video->list_all($CateID);
        } else {
            $op = 'show_one';
            $tad_web_video->show_one($VideoID);
        }
        break;

}

/*-----------秀出結果區--------------*/
include_once 'footer.php';
include_once XOOPS_ROOT_PATH . '/footer.php';
