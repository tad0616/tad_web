<?php
/*-----------引入檔案區--------------*/
require_once __DIR__ . '/header.php';
$plugin = 'news';
require_once __DIR__ . '/plugin_header.php';
require_once XOOPS_ROOT_PATH . '/header.php';
//$xoopsTpl->assign('plugin', $plugin);
/*-----------function區--------------*/

/*-----------執行動作判斷區----------*/
require_once $GLOBALS['xoops']->path('/modules/system/include/functions.php');
$op = system_CleanVars($_REQUEST, 'op', '', 'string');
$NewsID = system_CleanVars($_REQUEST, 'NewsID', 0, 'int');
$CateID = system_CleanVars($_REQUEST, 'CateID', 0, 'int');
$fb_action_ids = system_CleanVars($_REQUEST, 'fb_action_ids', 0, 'int');
$comment_id = system_CleanVars($_REQUEST, 'comment_id', 0, 'int');
$fb_comment_id = system_CleanVars($_REQUEST, 'fb_comment_id', '', 'string');
$tag = system_CleanVars($_REQUEST, 'tag', '', 'string');

common_template($WebID, $web_all_config);

switch ($op) {
    //新增資料
    case 'insert':
        $NewsID = $tad_web_news->insert();
        header("location: {$_SERVER['PHP_SELF']}?WebID=$WebID&NewsID=$NewsID");
        exit;
        break;
    //更新資料
    case 'update':
        $NewsID = $tad_web_news->update($NewsID);
        header("location: {$_SERVER['PHP_SELF']}?WebID=$WebID&NewsID={$NewsID}");
        exit;
        break;
    //輸入表格
    case 'edit_form':
        $tad_web_news->edit_form($NewsID);
        break;
    //刪除資料
    case 'delete':
        $tad_web_news->delete($NewsID);
        header("location: {$_SERVER['PHP_SELF']}?WebID={$WebID}");
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
        if (empty($NewsID)) {
            $op = 'list_all';
            $tad_web_news->list_all($CateID, null, 'assign', $tag);
        } else {
            $op = 'show_one';
            if (!empty($fb_action_ids) or !empty($fb_comment_id) or !empty($comment_id)) {
                header("location: {$_SERVER['PHP_SELF']}?WebID={$WebID}&NewsID={$NewsID}");
                exit;
            }
            $tad_web_news->show_one($NewsID);
        }
        break;
}

/*-----------秀出結果區--------------*/
require_once __DIR__ . '/footer.php';
require_once XOOPS_ROOT_PATH . '/footer.php';
