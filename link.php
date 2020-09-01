<?php
use Xmf\Request;
/*-----------引入檔案區--------------*/
require_once __DIR__ . '/header.php';
$plugin = 'link';
require_once __DIR__ . '/plugin_header.php';
require_once XOOPS_ROOT_PATH . '/header.php';
//$xoopsTpl->assign('plugin', $plugin);
/*-----------function區--------------*/

/*-----------執行動作判斷區----------*/

$op = Request::getString('op');
$LinkID = Request::getInt('LinkID');
$CateID = Request::getInt('CateID');
$WebID = Request::getInt('WebID');

common_template($WebID, $web_all_config);

switch ($op) {
    //新增資料
    case 'insert':
        $LinkID = $tad_web_link->insert();
        header("location: {$_SERVER['PHP_SELF']}?WebID={$WebID}");
        exit;

    //更新資料
    case 'update':
        $tad_web_link->update($LinkID);
        header("location: {$_SERVER['PHP_SELF']}?WebID={$WebID}");
        exit;

    //輸入表格
    case 'edit_form':
        $tad_web_link->edit_form($LinkID);
        break;

    //刪除資料
    case 'delete':
        $tad_web_link->delete($LinkID);
        header("location: {$_SERVER['PHP_SELF']}?WebID={$WebID}");
        exit;

    //預設動作
    default:
        if (empty($LinkID)) {
            $op = 'list_all';
            $tad_web_link->list_all($CateID);
        } else {
            $op = 'show_one';
            $tad_web_link->show_one($LinkID);
        }
        break;
}

/*-----------秀出結果區--------------*/
require_once __DIR__ . '/footer.php';
require_once XOOPS_ROOT_PATH . '/footer.php';
