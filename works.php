<?php
/*-----------引入檔案區--------------*/
include_once "header.php";
$plugin = "works";
include_once "plugin_header.php";
include_once XOOPS_ROOT_PATH . "/header.php";
//$xoopsTpl->assign('plugin', $plugin);
/*-----------function區--------------*/

/*-----------執行動作判斷區----------*/
include_once $GLOBALS['xoops']->path('/modules/system/include/functions.php');
$op      = system_CleanVars($_REQUEST, 'op', '', 'string');
$WorksID = system_CleanVars($_REQUEST, 'WorksID', 0, 'int');
$CateID  = system_CleanVars($_REQUEST, 'CateID', 0, 'int');

common_template($WebID);

switch ($op) {

    //新增資料
    case "insert":
        $WorksID = $tad_web_works->insert();
        header("location: {$_SERVER['PHP_SELF']}?WebID={$WebID}&WorksID=$WorksID");
        exit;
        break;

    //更新資料
    case "update":
        $WorksID = $tad_web_works->update($WorksID);
        header("location: {$_SERVER['PHP_SELF']}?WebID={$WebID}&WorksID=$WorksID");
        exit;
        break;
    //輸入表格
    case "edit_form":
        $tad_web_works->edit_form($WorksID);
        break;

    //刪除資料
    case "delete":
        $tad_web_works->delete($WorksID);
        header("location: {$_SERVER['PHP_SELF']}?WebID={$WebID}");
        exit;
        break;

    //下載檔案
    case "tufdl":
        $files_sn = isset($_GET['files_sn']) ? intval($_GET['files_sn']) : "";
        $TadUpFiles->add_file_counter($files_sn);
        exit;
        break;

    //預設動作
    default:
        if (empty($WorksID)) {
            $op = 'list_all';
            $tad_web_works->list_all($CateID);
        } else {
            $op = 'show_one';
            $tad_web_works->show_one($WorksID);
        }
        break;

}

/*-----------秀出結果區--------------*/
include_once 'footer.php';
include_once XOOPS_ROOT_PATH . '/footer.php';
