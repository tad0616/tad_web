<?php
use Xmf\Request;
use XoopsModules\Tadtools\FormValidator;
use XoopsModules\Tadtools\SweetAlert;
use XoopsModules\Tadtools\Utility;
use XoopsModules\Tad_web\Tools as TadWebTools;
/*-----------引入檔案區--------------*/
require_once __DIR__ . '/header.php';

$WebID = Request::getInt('WebID');

if (!$isMyWeb) {
    redirect_header("index.php?WebID={$WebID}", 3, _MD_TCW_NOT_OWNER . '<br>' . __FILE__ . ' : ' . __LINE__);
}
if (!empty($WebID)) {
    $xoopsOption['template_main'] = 'tad_web_assistant.tpl';
} else {
    header('location: index.php');
    exit;
}
require_once XOOPS_ROOT_PATH . '/header.php';

/*-----------執行動作判斷區----------*/
$op = Request::getString('op');
$plugin = Request::getString('plugin');
$CateID = Request::getInt('CateID');
$MemID = Request::getInt('MemID');

common_template($WebID, $web_all_config);

switch ($op) {
    case 'del_assistant':
        del_assistant($CateID, $plugin);
        header("location:{$_SERVER['PHP_SELF']}?WebID={$WebID}&plugin={$plugin}");
        exit;

    case 'save_assistant':
        set_assistant($WebID, $CateID, $MemID, $plugin);
        header("location:{$_SERVER['PHP_SELF']}?WebID={$WebID}&plugin={$plugin}");
        exit;

    default:
        list_all_assistant($WebID, $plugin);
        break;
}

/*-----------秀出結果區--------------*/
require_once __DIR__ . '/footer.php';
require_once XOOPS_ROOT_PATH . '/footer.php';

/*-----------function區--------------*/

//分類設定
function list_all_assistant($WebID = '', $plugin = '')
{
    global $xoopsTpl, $plugin_menu_var, $xoopsDB;

    $all_assistant = [];
    $sql = 'SELECT a.*, b.* FROM `' . $xoopsDB->prefix('tad_web_cate_assistant') . '` AS a LEFT JOIN `' . $xoopsDB->prefix('tad_web_cate') . '` AS b ON a.`CateID` = b.`CateID` WHERE b.`WebID` = ?';
    $result = Utility::query($sql, 'i', [$WebID]) or Utility::web_error($sql, __FILE__, __LINE__);

    $i = 0;
    while (false !== ($data = $xoopsDB->fetchArray($result))) {
        foreach ($data as $k => $v) {
            $$k = $v;
        }

        $all_assistant[$i] = $data;
        $all_assistant[$i]['plugin'] = $plugin_menu_var[$plugin];
        if ('MemID' === $AssistantType) {
            $all_assistant[$i]['mem'] = get_tad_web_mems($AssistantID);
        } elseif ('ParentID' === $AssistantType) {
            $all_assistant[$i]['mem'] = get_tad_web_parent($AssistantID);
        }
        $i++;
    }

    $xoopsTpl->assign('all_assistant', $all_assistant);
    $xoopsTpl->assign('plugin', $plugin);
    $default_class = TadWebTools::get_web_config('default_class', $WebID);

    $sql = 'SELECT a.`MemID`, a.`MemNum`, b.`MemName` FROM `' . $xoopsDB->prefix('tad_web_link_mems') . '` as a LEFT JOIN `' . $xoopsDB->prefix('tad_web_mems') . '` as b ON a.`MemID`=b.`MemID` WHERE a.`CateID` =? ORDER BY a.`MemNum`';
    $result = Utility::query($sql, 'i', [$default_class]) or Utility::web_error($sql, __FILE__, __LINE__);

    $AllMems = [];
    while (false !== ($mem = $xoopsDB->fetchArray($result))) {
        $AllMems[] = $mem;
    }
    $xoopsTpl->assign('default_class', $default_class);
    $xoopsTpl->assign('AllMems', $AllMems);

    $FormValidator = new FormValidator('#myForm', true);
    $FormValidator->render();

    $SweetAlert = new SweetAlert();
    $SweetAlert->render('delete_assistant_func', "assistant.php?WebID={$WebID}&op=del_assistant&plugin={$plugin}&CateID=", 'CateID');
}

function del_assistant($CateID = '', $plugin = '')
{
    global $xoopsDB, $isMyWeb;

    if (!$isMyWeb) {
        redirect_header("{$_SERVER['PHP_SELF']}?WebID=$WebID", 3, _MD_TCW_NOT_OWNER . '<br>' . __FILE__ . ' : ' . __LINE__);
    }

    $sql = 'DELETE FROM `' . $xoopsDB->prefix('tad_web_cate_assistant') . '` WHERE `CateID`=? AND `plugin`=?';
    Utility::query($sql, 'is', [$CateID, $plugin]) or Utility::web_error($sql, __FILE__, __LINE__);
}
