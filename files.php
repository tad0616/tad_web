<?php
use Xmf\Request;
/*-----------引入檔案區--------------*/
require_once __DIR__ . '/header.php';
$plugin = 'files';
require_once __DIR__ . '/plugin_header.php';
require_once XOOPS_ROOT_PATH . '/header.php';

/*-----------執行動作判斷區----------*/
$op = Request::getString('op');
$files_sn = Request::getInt('files_sn');
$fsn = Request::getInt('fsn');
$CateID = Request::getInt('CateID');

common_template($WebID, $web_all_config);

switch ($op) {
    //新增資料
    case 'insert':
        $fsn = $tad_web_files->insert();
        clear_block_cache($WebID);
        header("location: {$_SERVER['PHP_SELF']}?WebID={$WebID}");
        exit;

    //更新資料
    case 'update':
        $fsn = $tad_web_files->update($fsn);
        clear_block_cache($WebID);
        header("location: {$_SERVER['PHP_SELF']}?WebID={$WebID}");
        exit;

    //輸入表格
    case 'edit_form':
        $tad_web_files->edit_form($fsn, $WebID);
        break;

    //刪除資料
    case 'delete':
        $tad_web_files->delete($fsn, $files_sn);
        clear_block_cache($WebID);
        header("location: {$_SERVER['PHP_SELF']}?WebID={$WebID}");
        exit;

    //下載檔案
    case 'tufdl':
        $TadUpFiles->add_file_counter($files_sn);
        exit;

    //預設動作
    default:
        if (empty($fsn)) {
            $op = 'list_all';
            $tad_web_files->list_all($CateID);
        }
        break;
}

/*-----------秀出結果區--------------*/
require_once __DIR__ . '/footer.php';
require_once XOOPS_ROOT_PATH . '/footer.php';

/*-----------function區--------------*/