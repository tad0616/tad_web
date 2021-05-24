<?php
use Xmf\Request;
use XoopsModules\Tadtools\FormValidator;
use XoopsModules\Tadtools\SweetAlert;
use XoopsModules\Tadtools\Utility;
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
/*-----------function區--------------*/

//分類設定
function list_all_assistant($WebID = '', $plugin = '')
{
    global $xoopsTpl, $plugin_menu_var, $xoopsDB;

    $all_assistant = [];
    $sql = 'select a.*,b.* from `' . $xoopsDB->prefix('tad_web_cate_assistant') . '` as a
    left join `' . $xoopsDB->prefix('tad_web_cate') . "` as b on a.CateID=b.CateID
    where b.`WebID` = '{$WebID}' ";
    $result = $xoopsDB->query($sql) or Utility::web_error($sql, __FILE__, __LINE__);
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
    $default_class = get_web_config('default_class', $WebID);

    $sql = 'select a.MemID, a.MemNum ,b.MemName from ' . $xoopsDB->prefix('tad_web_link_mems') . ' as a left join ' . $xoopsDB->prefix('tad_web_mems') . " as b on a.MemID=b.MemID where a.`CateID` = '{$default_class}'  order by a.MemNum";
    $result = $xoopsDB->query($sql) or Utility::web_error($sql, __FILE__, __LINE__);
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

    $sql = 'delete from ' . $xoopsDB->prefix('tad_web_cate_assistant') . " where CateID='$CateID' and plugin='{$plugin}'";
    $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);
}

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
