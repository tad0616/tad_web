<?php
/*-----------引入檔案區--------------*/
require_once __DIR__ . '/header.php';
$plugin = 'account';
require_once __DIR__ . '/plugin_header.php';
require_once XOOPS_ROOT_PATH . '/header.php';
//$xoopsTpl->assign('plugin', $plugin);
/*-----------function區--------------*/

/*-----------執行動作判斷區----------*/
require_once $GLOBALS['xoops']->path('/modules/system/include/functions.php');
$op = system_CleanVars($_REQUEST, 'op', '', 'string');
$AccountID = system_CleanVars($_REQUEST, 'AccountID', 0, 'int');
$CateID = system_CleanVars($_REQUEST, 'CateID', 0, 'int');
$fb_account_ids = system_CleanVars($_REQUEST, 'fb_account_ids', 0, 'int');
$comment_id = system_CleanVars($_REQUEST, 'comment_id', 0, 'int');
$fb_comment_id = system_CleanVars($_REQUEST, 'fb_comment_id', '', 'string');
$tag = system_CleanVars($_REQUEST, 'tag', '', 'string');

common_template($WebID, $web_all_config);

switch ($op) {
    //新增資料
    case 'insert':
        $AccountID = $tad_web_account->insert();
        header("location: {$_SERVER['PHP_SELF']}?WebID={$WebID}&AccountID=$AccountID");
        exit;
        break;
    //更新資料
    case 'update':
        $AccountID = $tad_web_account->update($AccountID);
        header("location: {$_SERVER['PHP_SELF']}?WebID={$WebID}&AccountID=$AccountID");
        exit;
        break;
    //輸入表格
    case 'edit_form':
        $tad_web_account->edit_form($AccountID);
        break;
    //刪除資料
    case 'delete':
        $tad_web_account->delete($AccountID);
        header("location: {$_SERVER['PHP_SELF']}?WebID={$WebID}");
        exit;
        break;
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
