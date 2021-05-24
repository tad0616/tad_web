<?php
use Xmf\Request;
/*-----------引入檔案區--------------*/
require_once __DIR__ . '/header.php';
$plugin = 'news';
require_once __DIR__ . '/plugin_header.php';
require_once XOOPS_ROOT_PATH . '/header.php';
//$xoopsTpl->assign('plugin', $plugin);
/*-----------function區--------------*/

/*-----------執行動作判斷區----------*/
$op = Request::getString('op');
$NewsID = Request::getInt('NewsID');
$CateID = Request::getInt('CateID');
$fb_action_ids = Request::getInt('fb_action_ids');
$comment_id = Request::getInt('comment_id');
$fb_comment_id = Request::getString('fb_comment_id');
$tag = Request::getInt('tag');

common_template($WebID, $web_all_config);

switch ($op) {
    //新增資料
    case 'insert':
        $NewsID = $tad_web_news->insert();
        clear_block_cache($WebID);
        header("location: {$_SERVER['PHP_SELF']}?WebID=$WebID&NewsID=$NewsID");
        exit;

    //更新資料
    case 'update':
        $NewsID = $tad_web_news->update($NewsID);
        clear_block_cache($WebID);
        header("location: {$_SERVER['PHP_SELF']}?WebID=$WebID&NewsID={$NewsID}");
        exit;

    //輸入表格
    case 'edit_form':
        $tad_web_news->edit_form($NewsID);
        break;

    //刪除資料
    case 'delete':
        $tad_web_news->delete($NewsID);
        clear_block_cache($WebID);
        header("location: {$_SERVER['PHP_SELF']}?WebID={$WebID}");
        exit;

    //下載檔案
    case 'tufdl':
        $files_sn = isset($_GET['files_sn']) ? (int) $_GET['files_sn'] : '';
        $TadUpFiles->add_file_counter($files_sn);
        exit;

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
