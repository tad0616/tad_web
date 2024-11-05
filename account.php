<?php
use Xmf\Request;
/*-----------引入檔案區--------------*/
require_once __DIR__ . '/header.php';
$plugin = 'account';
require_once __DIR__ . '/plugin_header.php';
require_once XOOPS_ROOT_PATH . '/header.php';

/*-----------執行動作判斷區----------*/
$op = Request::getString('op');
$AccountID = Request::getInt('AccountID');
$CateID = Request::getInt('CateID');
$fb_account_ids = Request::getInt('fb_account_ids');
$comment_id = Request::getInt('comment_id');
$fb_comment_id = Request::getString('fb_comment_id');
$tag = Request::getString('tag');

common_template($WebID, $web_all_config);

switch ($op) {
    //新增資料
    case 'insert':
        $AccountID = $tad_web_account->insert();
        clear_block_cache($WebID);
        header("location: {$_SERVER['PHP_SELF']}?WebID={$WebID}&AccountID=$AccountID");
        exit;

    //更新資料
    case 'update':
        $AccountID = $tad_web_account->update($AccountID);
        clear_block_cache($WebID);
        header("location: {$_SERVER['PHP_SELF']}?WebID={$WebID}&AccountID=$AccountID");
        exit;

    //輸入表格
    case 'edit_form':
        $tad_web_account->edit_form($AccountID);
        break;
    //刪除資料
    case 'delete':
        $tad_web_account->delete($AccountID);
        clear_block_cache($WebID);
        header("location: {$_SERVER['PHP_SELF']}?WebID={$WebID}");
        exit;

    //預設動作
    default:
        if (empty($AccountID)) {
            $op = 'list_all';
            $tad_web_account->list_all($CateID, null, 'assign', $tag);
        } else {
            $op = 'show_one';
            if (!empty($fb_account_ids) or !empty($fb_comment_id) or !empty($comment_id)) {
                header("location: {$_SERVER['PHP_SELF']}?WebID={$WebID}&AccountID={$AccountID}");
                exit;
            }
            $tad_web_account->show_one($AccountID);
        }
        break;
}

/*-----------秀出結果區--------------*/
require_once __DIR__ . '/footer.php';
require_once XOOPS_ROOT_PATH . '/footer.php';

/*-----------function區--------------*/
