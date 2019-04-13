<?php
/*-----------引入檔案區--------------*/
include_once 'header.php';
$plugin = 'works';
include_once 'plugin_header.php';
include_once XOOPS_ROOT_PATH . '/header.php';
//$xoopsTpl->assign('plugin', $plugin);
/*-----------function區--------------*/

/*-----------執行動作判斷區----------*/
include_once $GLOBALS['xoops']->path('/modules/system/include/functions.php');
$op = system_CleanVars($_REQUEST, 'op', '', 'string');
$WorksID = system_CleanVars($_REQUEST, 'WorksID', 0, 'int');
$CateID = system_CleanVars($_REQUEST, 'CateID', 0, 'int');
$fb_action_ids = system_CleanVars($_REQUEST, 'fb_action_ids', 0, 'int');
$comment_id = system_CleanVars($_REQUEST, 'comment_id', 0, 'int');
$fb_comment_id = system_CleanVars($_REQUEST, 'fb_comment_id', '', 'string');
$WorkScore = system_CleanVars($_REQUEST, 'WorkScore', '', 'array');
$WorkJudgment = system_CleanVars($_REQUEST, 'WorkJudgment', '', 'array');

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
        $files_sn = isset($_GET['files_sn']) ? (int)$_GET['files_sn'] : '';
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
include_once 'footer.php';
include_once XOOPS_ROOT_PATH . '/footer.php';
