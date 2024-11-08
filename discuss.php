<?php
use Xmf\Request;
/*-----------引入檔案區--------------*/
require_once __DIR__ . '/header.php';
$plugin = 'discuss';
require_once __DIR__ . '/plugin_header.php';
require_once XOOPS_ROOT_PATH . '/header.php';

/*-----------執行動作判斷區----------*/
$op = Request::getString('op');
$DiscussID = Request::getInt('DiscussID');
$WebID = Request::getInt('WebID', $LoginWebID);
$CateID = Request::getInt('CateID');
$DefDiscussID = Request::getInt('DefDiscussID');

common_template($WebID, $web_all_config);

switch ($op) {
    //新增資料
    case 'insert':
        $DiscussID = $tad_web_discuss->insert();
        clear_block_cache($WebID);
        header("location: {$_SERVER['PHP_SELF']}?WebID=$WebID&DiscussID=$DiscussID");
        exit;

    //更新資料
    case 'update':
        $DiscussID = $tad_web_discuss->update($DiscussID);
        clear_block_cache($WebID);
        header("location: {$_SERVER['PHP_SELF']}?WebID={$WebID}&DiscussID=$DiscussID");
        exit;

    //輸入表格
    case 'edit_form':
        $tad_web_discuss->edit_form($DiscussID);
        break;

    //刪除資料
    case 'delete':
        $tad_web_discuss->delete($DiscussID);
        clear_block_cache($WebID);
        header("location: {$_SERVER['PHP_SELF']}?WebID={$WebID}&DiscussID=$DefDiscussID");
        exit;

    //下載檔案
    case 'tufdl':
        $files_sn = isset($_GET['files_sn']) ? (int) $_GET['files_sn'] : '';
        $TadUpFiles->add_file_counter($files_sn);
        exit;

    //預設動作
    default:
        if (empty($DiscussID)) {
            $op = 'list_all';
            $tad_web_discuss->list_all($CateID);
        } else {
            $op = 'show_one';
            $tad_web_discuss->show_one($DiscussID);
        }
        break;
}

/*-----------秀出結果區--------------*/
$xoTheme->addStylesheet('modules/tad_web/plugins/discuss/bubble.css');
require_once __DIR__ . '/footer.php';
require_once XOOPS_ROOT_PATH . '/footer.php';

/*-----------function區--------------*/