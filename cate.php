<?php
use Xmf\Request;
use XoopsModules\Tadtools\FormValidator;
use XoopsModules\Tadtools\Utility;
use XoopsModules\Tad_web\Power;
use XoopsModules\Tad_web\Tools as TadWebTools;
use XoopsModules\Tad_web\WebCate;
/*-----------引入檔案區--------------*/
include_once 'header.php';
$WebID = Request::getInt('WebID');

if (!$isMyWeb) {
    redirect_header("index.php?WebID={$WebID}", 3, _MD_TCW_NOT_OWNER . '<br>' . __FILE__ . ' : ' . __LINE__);
}
if (!empty($WebID)) {
    $xoopsOption['template_main'] = 'tad_web_cate.tpl';
} else {
    header('location: index.php');
    exit;
}
//權限設定
$power = new Power($WebID);
include_once XOOPS_ROOT_PATH . '/header.php';

/*-----------執行動作判斷區----------*/
$op = Request::getString('op');
$ColName = Request::getString('ColName');
$act = Request::getArray('act');
$table = Request::getString('table');

common_template($WebID, $web_all_config);

switch ($op) {
    case 'save_cate':
        save_cate($WebID, $ColName, $act, $table);
        clear_block_cache($WebID);
        header("location:{$_SERVER['PHP_SELF']}?WebID={$WebID}&ColName={$ColName}");
        exit;

    default:
        list_all_cate($WebID, $ColName, $table);
        break;
}

/*-----------秀出結果區--------------*/
include_once 'footer.php';
include_once XOOPS_ROOT_PATH . '/footer.php';

/*-----------function區--------------*/

//分類設定
function list_all_cate($WebID = '', $ColName = '', $table = '')
{
    global $xoopsTpl, $plugin_menu_var, $xoopsDB;
    if (empty($WebID) or empty($ColName)) {
        return;
    }

    $WebCate = new WebCate($WebID, $ColName, $table);
    $WebCate->set_WebID($WebID);
    $cate = $WebCate->get_tad_web_cate_arr(true, null, $ColName);
    // Utility::test($cate, 'cate', 'dd');
    $cate_menu_form = $WebCate->cate_menu($cate['CateID'], 'form', true, false, true, false, false);
    $xoopsTpl->assign('cate_menu_form', $cate_menu_form);

    // Utility::dd($cate);
    /*
    array (
    13 =>
    array (
    'CateID' => '13',
    'WebID' => '1',
    'CateName' => '生活與藝術',
    'ColName' => 'works',
    'ColSN' => '0',
    'CateSort' => '1',
    'CateEnable' => '1',
    'CateCounter' => '0',
    'AssistantType'=> 'MemID',
    'AssistantID'=> '10',
    ),
    21 =>
    array (
    'CateID' => '21',
    'WebID' => '1',
    'CateName' => '作文',
    'ColName' => 'works',
    'ColSN' => '0',
    'CateSort' => '2',
    'CateEnable' => '1',
    'CateCounter' => '0',
    'AssistantType'=> 'MemID',
    'AssistantID'=> '76',
    ),
    )*/
    $default_class = TadWebTools::get_web_config('default_class', $WebID);
    $sql = 'SELECT a.*, b.*, c.`CateName` FROM `' . $xoopsDB->prefix('tad_web_link_mems') . '` AS a LEFT JOIN `' . $xoopsDB->prefix('tad_web_mems') . '` AS b ON a.`MemID`=b.`MemID` LEFT JOIN `' . $xoopsDB->prefix('tad_web_cate') . '` AS c ON a.`CateID`=c.`CateID` WHERE a.`WebID`=? AND a.`MemEnable`=? AND a.`CateID`=?';
    $result = Utility::query($sql, 'isi', [$WebID, '1', $default_class]) or Utility::web_error($sql, __FILE__, __LINE__);
    while ($all = $xoopsDB->fetchArray($result)) {
        $students[] = $all;
    }

    $xoopsTpl->assign('cate_opt_arr', $cate);
    $xoopsTpl->assign('cate_arr', $cate);
    $xoopsTpl->assign('ColName', $ColName);
    $xoopsTpl->assign('WebID', $WebID);
    $xoopsTpl->assign('plugin', $plugin_menu_var[$ColName]);
    $xoopsTpl->assign('students', $students);

    $FormValidator = new FormValidator('#myForm', true);
    $FormValidator->render();
}

//執行分類動作
function save_cate($WebID = '', $ColName = '', $act_arr = [], $table = '')
{
    global $xoopsTpl;
    if (empty($WebID) or empty($ColName)) {
        return;
    }

    $power = new Power($WebID);

    $WebCate = new WebCate($WebID, $ColName, $table);
    $WebCate->set_WebID($WebID);
    //新增分類
    if ($_POST['newCateName']) {
        $WebCate->save_tad_web_cate('', $_POST['newCateName']);
    }

    foreach ($act_arr as $CateID => $act) {
        switch ($act) {
            case 'move':
                $WebCate->move_tad_web_cate($CateID, $_POST['move2'][$CateID]);
                break;
            case 'rename':
                $WebCate->update_tad_web_cate($CateID, $_POST['newName'][$CateID]);
                break;
            case 'delete':
                $WebCate->delete_tad_web_cate($CateID, $_POST['move2'][$CateID]);
                break;
            case 'del_all':
                $WebCate->delete_tad_web_cate($CateID);
                break;
            case 'set_assistant':
                set_assistant($WebID, $CateID, $_POST['MemID'][$CateID], $ColName);
                break;
            case 'enable':
                $WebCate->enable_tad_web_cate($CateID, 1);
                break;
            case 'unable':
                $WebCate->enable_tad_web_cate($CateID, 0);
                break;
            case 'power':
                $power->save_power('CateID', $CateID, 'read', $_POST['power'][$CateID], $ColName);
                break;
        }
    }
}
