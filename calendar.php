<?php
use Xmf\Request;
/*-----------引入檔案區--------------*/
require_once __DIR__ . '/header.php';
$plugin = 'calendar';
require_once __DIR__ . '/plugin_header.php';
require_once XOOPS_ROOT_PATH . '/header.php';
//$xoopsTpl->assign('plugin', $plugin);
/*-----------function區--------------*/

/*-----------執行動作判斷區----------*/
$op = Request::getString('op');
$CalendarID = Request::getInt('CalendarID');
$CateID = Request::getInt('CateID');
$fb_action_ids = Request::getInt('fb_action_ids');
$comment_id = Request::getInt('comment_id');
$fb_comment_id = Request::getString('fb_comment_id');

common_template($WebID, $web_all_config);

switch ($op) {
    //新增資料
    case 'insert':
        $CalendarID = $tad_web_calendar->insert();
        clear_block_cache($WebID);
        header("location: {$_SERVER['PHP_SELF']}?WebID={$WebID}&CalendarID=$CalendarID");
        exit;

    //更新資料
    case 'update':
        $CalendarID = $tad_web_calendar->update($CalendarID);
        clear_block_cache($WebID);
        header("location: {$_SERVER['PHP_SELF']}?WebID={$WebID}&CalendarID=$CalendarID");
        exit;

    //輸入表格
    case 'edit_form':
        $tad_web_calendar->edit_form($CalendarID);
        break;
    //刪除資料
    case 'delete':
        $tad_web_calendar->delete($CalendarID);
        clear_block_cache($WebID);
        header("location: {$_SERVER['PHP_SELF']}?WebID={$WebID}");
        exit;

    //預設動作
    default:
        if (empty($CalendarID)) {
            $op = 'list_all';
            $tad_web_calendar->list_all($CateID);
        } else {
            $op = 'show_one';
            if (!empty($fb_action_ids) or !empty($fb_comment_id) or !empty($comment_id)) {
                header("location: {$_SERVER['PHP_SELF']}?WebID={$WebID}&CalendarID={$CalendarID}");
                exit;
            }
            $tad_web_calendar->show_one($CalendarID);
        }
        break;
}
/*-----------秀出結果區--------------*/
require_once __DIR__ . '/footer.php';
require_once XOOPS_ROOT_PATH . '/footer.php';
