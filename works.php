<?php
use Xmf\Request;
/*-----------引入檔案區--------------*/
require_once __DIR__ . '/header.php';
$plugin = 'works';
require_once __DIR__ . '/plugin_header.php';
require_once XOOPS_ROOT_PATH . '/header.php';
//$xoopsTpl->assign('plugin', $plugin);
/*-----------function區--------------*/

/*-----------執行動作判斷區----------*/
$op = Request::getString('op');
$WorksID = Request::getInt('WorksID');
$CateID = Request::getInt('CateID');
$fb_action_ids = Request::getInt('fb_action_ids');
$comment_id = Request::getInt('comment_id');
$fb_comment_id = Request::getString('fb_comment_id');
$WorkScore = Request::getArray('WorkScore');
$WorkJudgment = Request::getArray('WorkJudgment');

common_template($WebID, $web_all_config);

switch ($op) {
    //新增資料
    case 'insert':
        $WorksID = $tad_web_works->insert();
        header("location: {$_SERVER['PHP_SELF']}?WebID={$WebID}&WorksID=$WorksID");
        exit;
        break;
    //更新資料
    case 'update':
        $WorksID = $tad_web_works->update($WorksID);
        header("location: {$_SERVER['PHP_SELF']}?WebID={$WebID}&WorksID=$WorksID");
        exit;
        break;
    //交作業
    case 'mem_upload':
        $WorksID = $tad_web_works->mem_upload($WorksID);
        header("location: {$_SERVER['PHP_SELF']}?WebID={$WebID}&WorksID=$WorksID");
        exit;
        break;
    //輸入表格
    case 'edit_form':
        $tad_web_works->edit_form($WorksID);
        break;
    //評分
    case 'score_form':
        $tad_web_works->score_form($WorksID);
        break;
    //儲存評分
    case 'save_score':
        $tad_web_works->save_score($WorksID, $WorkScore, $WorkJudgment);
        header("location: {$_SERVER['PHP_SELF']}?WebID={$WebID}&WorksID=$WorksID");
        exit;
        break;
    //刪除資料
    case 'delete':
        $tad_web_works->delete($WorksID);
        header("location: {$_SERVER['PHP_SELF']}?WebID={$WebID}");
        exit;
        break;
    //下載檔案
    case 'tufdl':
        $files_sn = isset($_GET['files_sn']) ? (int) $_GET['files_sn'] : '';
        $TadUpFiles->add_file_counter($files_sn);
        exit;
        break;
    //預設動作
    default:
        if (empty($WorksID)) {
            $op = 'list_all';
            $tad_web_works->list_all($CateID);
        } else {
            $op = 'show_one';
            if (!empty($fb_action_ids) or !empty($fb_comment_id) or !empty($comment_id)) {
                header("location: {$_SERVER['PHP_SELF']}?WebID={$WebID}&WorksID={$WorksID}");
                exit;
            }
            $tad_web_works->show_one($WorksID);
        }
        break;
}

/*-----------秀出結果區--------------*/
require_once __DIR__ . '/footer.php';
require_once XOOPS_ROOT_PATH . '/footer.php';
