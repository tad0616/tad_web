<?php
/*-----------引入檔案區--------------*/
include_once "header.php";
$plugin = "page";
include_once "plugin_header.php";
include_once XOOPS_ROOT_PATH . "/header.php";
//$xoopsTpl->assign('plugin', $plugin);
/*-----------function區--------------*/

/*-----------執行動作判斷區----------*/
include_once $GLOBALS['xoops']->path('/modules/system/include/functions.php');
$op     = system_CleanVars($_REQUEST, 'op', '', 'string');
$PageID = system_CleanVars($_REQUEST, 'PageID', 0, 'int');
$CateID = system_CleanVars($_REQUEST, 'CateID', 0, 'int');

common_template($WebID);

switch ($op) {

    //新增資料
    case "insert":
        $PageID = $tad_web_page->insert();
        header("location: {$_SERVER['PHP_SELF']}?WebID={$WebID}&PageID=$PageID");
        exit;
        break;

    //更新資料
    case "update":
        $PageID = $tad_web_page->update($PageID);
        header("location: {$_SERVER['PHP_SELF']}?WebID={$WebID}&PageID=$PageID");
        exit;
        break;

    //輸入表格
    case "edit_form":
        $tad_web_page->edit_form($PageID);
        break;

    //刪除資料
    case "delete":
        $tad_web_page->delete($PageID);
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
        if (empty($PageID)) {
            $op = 'list_all';
            $tad_web_page->list_all($CateID);
        } else {
            $op = 'show_one';
            $tad_web_page->show_one($PageID);
        }
        break;

}

/*-----------秀出結果區--------------*/
include_once 'footer.php';
include_once XOOPS_ROOT_PATH . '/footer.php';
