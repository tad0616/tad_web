<?php
/*-----------引入檔案區--------------*/
include_once 'header.php';
$plugin = 'discuss';
include_once 'plugin_header.php';
include_once XOOPS_ROOT_PATH . '/header.php';
//$xoopsTpl->assign('plugin', $plugin);
/*-----------function區--------------*/

/*-----------執行動作判斷區----------*/
include_once $GLOBALS['xoops']->path('/modules/system/include/functions.php');
$op = system_CleanVars($_REQUEST, 'op', '', 'string');
$DiscussID = system_CleanVars($_REQUEST, 'DiscussID', 0, 'int');
$WebID = system_CleanVars($_REQUEST, 'WebID', $LoginWebID, 'int');
$CateID = system_CleanVars($_REQUEST, 'CateID', 0, 'int');
$DefDiscussID = system_CleanVars($_REQUEST, 'DefDiscussID', 0, 'int');

common_template($WebID, $web_all_config);

switch ($op) {
    //新增資料
    case 'insert':
        $DiscussID = $tad_web_discuss->insert();
        header("location: {$_SERVER['PHP_SELF']}?WebID=$WebID&DiscussID=$DiscussID");
        exit;
        break;
    //更新資料
    case 'update':
        $DiscussID = $tad_web_discuss->update($DiscussID);
        header("location: {$_SERVER['PHP_SELF']}?WebID={$WebID}&DiscussID=$DiscussID");
        exit;
        break;
    //輸入表格
    case 'edit_form':
        $tad_web_discuss->edit_form($DiscussID);
        break;
    //刪除資料
    case 'delete':
        $tad_web_discuss->delete($DiscussID);
        header("location: {$_SERVER['PHP_SELF']}?WebID={$WebID}&DiscussID=$DefDiscussID");
        exit;
        break;
    //下載檔案
    case 'tufdl':
        $files_sn = isset($_GET['files_sn']) ? (int)$_GET['files_sn'] : '';
        $TadUpFiles->add_file_counter($files_sn);
        exit;
        break;
    //預設動作
    default:
        if (empty($DiscussID)) {
            $op = 'list_all';
            $tad_web_discuss->list_all($CateID);
        } else {
            $op = 'show_one';
            $tad_web_discuss->show_one($DiscussID);
        }
        break;
}

/*-----------秀出結果區--------------*/
include_once 'footer.php';
include_once XOOPS_ROOT_PATH . '/footer.php';
