<?php
use Xmf\Request;
/*-----------引入檔案區--------------*/
require_once __DIR__ . '/header.php';
$plugin = 'action';
require_once __DIR__ . '/plugin_header.php';
require_once XOOPS_ROOT_PATH . '/header.php';

/*-----------執行動作判斷區----------*/
$op = Request::getString('op');
$ActionID = Request::getInt('ActionID');
$CateID = Request::getInt('CateID');
$fb_action_ids = Request::getInt('fb_action_ids');
$comment_id = Request::getInt('comment_id');
$fb_comment_id = Request::getString('fb_comment_id');
$tag = Request::getString('tag');

common_template($WebID, $web_all_config);

switch ($op) {
    //新增資料
    case 'insert':
        $ActionID = $tad_web_action->insert();
        clear_block_cache($WebID);
        header("location: {$_SERVER['PHP_SELF']}?WebID={$WebID}&ActionID=$ActionID");
        exit;

    //更新資料
    case 'update':
        $ActionID = $tad_web_action->update($ActionID);
        clear_block_cache($WebID);
        header("location: {$_SERVER['PHP_SELF']}?WebID={$WebID}&ActionID=$ActionID");
        exit;

    //輸入表格
    case 'edit_form':
        $tad_web_action->edit_form($ActionID);
        break;

    //刪除資料
    case 'delete':
        $tad_web_action->delete($ActionID);
        clear_block_cache($WebID);
        header("location: {$_SERVER['PHP_SELF']}?WebID={$WebID}");
        exit;

    //重新擷取
    case 're_get':
        $tad_web_action->re_get($ActionID);
        clear_block_cache($WebID);
        header("location: {$_SERVER['PHP_SELF']}?WebID={$WebID}&ActionID={$ActionID}");
        exit;

    //預設動作
    default:
        if (empty($ActionID)) {
            $op = 'list_all';
            $tad_web_action->list_all($CateID, null, 'assign', $tag);
        } else {
            $op = 'show_one';
            if (!empty($fb_action_ids) or !empty($fb_comment_id) or !empty($comment_id)) {
                header("location: {$_SERVER['PHP_SELF']}?WebID={$WebID}&ActionID={$ActionID}");
                exit;
            }
            $tad_web_action->show_one($ActionID);
        }
        break;
}

/*-----------秀出結果區--------------*/
require_once __DIR__ . '/footer.php';
require_once XOOPS_ROOT_PATH . '/footer.php';

/*-----------function區--------------*/