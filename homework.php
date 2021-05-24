<?php
use Xmf\Request;
/*-----------引入檔案區--------------*/
require_once __DIR__ . '/header.php';
$plugin = 'homework';
require_once __DIR__ . '/plugin_header.php';
require_once XOOPS_ROOT_PATH . '/header.php';
//$xoopsTpl->assign('plugin', $plugin);
/*-----------function區--------------*/

/*-----------執行動作判斷區----------*/
$op = Request::getString('op');
$HomeworkID = Request::getInt('HomeworkID');
$CateID = Request::getInt('CateID');
$fb_action_ids = Request::getInt('fb_action_ids');
$comment_id = Request::getInt('comment_id');
$fb_comment_id = Request::getString('fb_comment_id');

common_template($WebID, $web_all_config);

switch ($op) {
    //新增資料
    case 'insert':
        $HomeworkID = $tad_web_homework->insert();
        clear_block_cache($WebID);
        header("location: {$_SERVER['PHP_SELF']}?WebID=$WebID&HomeworkID=$HomeworkID");
        exit;

    //更新資料
    case 'update':
        $HomeworkID = $tad_web_homework->update($HomeworkID);
        clear_block_cache($WebID);
        header("location: {$_SERVER['PHP_SELF']}?WebID=$WebID&HomeworkID={$HomeworkID}");
        exit;

    //輸入表格
    case 'edit_form':
        $tad_web_homework->edit_form($HomeworkID);
        break;

    //刪除資料
    case 'delete':
        $tad_web_homework->delete($HomeworkID);
        clear_block_cache($WebID);
        header("location: {$_SERVER['PHP_SELF']}?WebID={$WebID}");
        exit;

    //下載檔案
    case 'tufdl':
        $files_sn = isset($_GET['files_sn']) ? (int) $_GET['files_sn'] : '';
        $TadUpFiles->add_file_counter($files_sn);
        exit;
        break;
    //預設動作
    default:
        if (empty($HomeworkID)) {
            $op = 'list_all';
            $tad_web_homework->list_all($CateID);
        } else {
            $op = 'show_one';
            if (!empty($fb_action_ids) or !empty($fb_comment_id) or !empty($comment_id)) {
                header("location: {$_SERVER['PHP_SELF']}?WebID={$WebID}&HomeworkID={$HomeworkID}");
                exit;
            }
            $tad_web_homework->show_one($HomeworkID);
        }
        break;
}

/*-----------秀出結果區--------------*/
require_once __DIR__ . '/footer.php';
require_once XOOPS_ROOT_PATH . '/footer.php';
