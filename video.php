<?php
use Xmf\Request;
/*-----------引入檔案區--------------*/
require_once __DIR__ . '/header.php';
$plugin = 'video';
require_once __DIR__ . '/plugin_header.php';
require_once XOOPS_ROOT_PATH . '/header.php';
//$xoopsTpl->assign('plugin', $plugin);
/*-----------function區--------------*/

/*-----------執行動作判斷區----------*/
$op = Request::getString('op');
$VideoID = Request::getInt('VideoID');
$CateID = Request::getInt('CateID');
$fb_action_ids = Request::getInt('fb_action_ids');
$comment_id = Request::getInt('comment_id');
$fb_comment_id = Request::getString('fb_comment_id');

common_template($WebID, $web_all_config);

switch ($op) {
    //新增資料
    case 'insert':
        $VideoID = $tad_web_video->insert();
        header("location: {$_SERVER['PHP_SELF']}?WebID={$WebID}&VideoID=$VideoID");
        exit;
        break;
    //更新資料
    case 'update':
        $VideoID = $tad_web_video->update($VideoID);
        header("location: {$_SERVER['PHP_SELF']}?WebID={$WebID}&VideoID=$VideoID");
        exit;
        break;
    //輸入表格
    case 'edit_form':
        $tad_web_video->edit_form($VideoID);
        break;
    //刪除資料
    case 'delete':
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
            if (!empty($fb_action_ids) or !empty($fb_comment_id) or !empty($comment_id)) {
                header("location: {$_SERVER['PHP_SELF']}?WebID={$WebID}&VideoID={$VideoID}");
                exit;
            }
            $tad_web_video->show_one($VideoID);
        }
        break;
}

/*-----------秀出結果區--------------*/
require_once __DIR__ . '/footer.php';
require_once XOOPS_ROOT_PATH . '/footer.php';
