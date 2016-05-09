<?php
/*-----------引入檔案區--------------*/
include_once "header.php";
include_once $GLOBALS['xoops']->path('/modules/system/include/functions.php');
$WebID = system_CleanVars($_REQUEST, 'WebID', 0, 'int');

if (!$isMyWeb) {
    redirect_header("index.php?WebID={$WebID}", 3, _MD_TCW_NOT_OWNER);
}
if (!empty($WebID)) {
    $xoopsOption['template_main'] = 'tad_web_cate_b3.html';
} else {
    header("location: index.php");
    exit;
}
include_once XOOPS_ROOT_PATH . "/header.php";
/*-----------function區--------------*/

//分類設定
function list_all_cate($WebID = "", $ColName = "", $table = "")
{
    global $xoopsTpl, $plugin_menu_var, $xoopsDB;
    if (empty($WebID) or empty($ColName)) {
        return;
    }

    $web_cate = new web_cate($WebID, $ColName, $table);
    $web_cate->set_WebID($WebID);
    $cate = $web_cate->get_tad_web_cate_arr();

    $cate_menu_form = $web_cate->cate_menu($CateID, "form", true, false, true, false, false);
    $xoopsTpl->assign('cate_menu_form', $cate_menu_form);

    // die(var_export($cate));
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
    $default_class = get_web_config('default_class', $WebID);
    $sql           = "select a.*,b.*,c.CateName from " . $xoopsDB->prefix("tad_web_link_mems") . " as a
    left join " . $xoopsDB->prefix("tad_web_mems") . " as b on a.MemID=b.MemID
    left join " . $xoopsDB->prefix("tad_web_cate") . " as c on a.CateID=c.CateID
    where a.WebID ='{$WebID}' and a.MemEnable='1' and a.CateID='{$default_class}'";
    $result = $xoopsDB->query($sql) or web_error($sql);
    while ($all = $xoopsDB->fetchArray($result)) {
        $students[] = $all;
    }

    $xoopsTpl->assign('cate_opt_arr', $cate);
    $xoopsTpl->assign('cate_arr', $cate);
    $xoopsTpl->assign('ColName', $ColName);
    $xoopsTpl->assign('WebID', $WebID);
    $xoopsTpl->assign('plugin', $plugin_menu_var[$ColName]);
    $xoopsTpl->assign('students', $students);
    // die(var_export($menu_var[$ColName]));

    if (!file_exists(TADTOOLS_PATH . "/formValidator.php")) {
        redirect_header("index.php", 3, _MD_NEED_TADTOOLS);
    }
    include_once TADTOOLS_PATH . "/formValidator.php";
    $formValidator      = new formValidator("#myForm", true);
    $formValidator_code = $formValidator->render();

}

//執行分類動作
function save_cate($WebID = "", $ColName = "", $act_arr = array(), $table = "")
{
    global $xoopsTpl;
    if (empty($WebID) or empty($ColName)) {
        return;
    }

    $web_cate = new web_cate($WebID, $ColName, $table);
    $web_cate->set_WebID($WebID);
    //新增分類
    if ($_POST['newCateName']) {
        $web_cate->save_tad_web_cate('', $_POST['newCateName']);
    }

    foreach ($act_arr as $CateID => $act) {

        switch ($act) {

            case "move":
                $web_cate->move_tad_web_cate($CateID, $_POST['move2'][$CateID]);
                break;
            case "rename":
                $web_cate->update_tad_web_cate($CateID, $_POST['newName'][$CateID]);
                break;
            case "delete":
                $web_cate->delete_tad_web_cate($CateID, $_POST['move2'][$CateID]);
                break;
            case "del_all":
                $web_cate->delete_tad_web_cate($CateID);
                break;
            case "set_assistant":
                set_assistant($CateID, $_POST['MemID'][$CateID]);
                break;

        }
    }
}

/*-----------執行動作判斷區----------*/
$op      = system_CleanVars($_REQUEST, 'op', '', 'string');
$ColName = system_CleanVars($_REQUEST, 'ColName', '', 'string');
$act     = system_CleanVars($_REQUEST, 'act', '', 'array');
$table   = system_CleanVars($_REQUEST, 'table', '', 'string');

common_template($WebID, $web_all_config);

switch ($op) {

    case "save_cate":
        save_cate($WebID, $ColName, $act, $table);
        header("location:{$_SERVER['PHP_SELF']}?WebID={$WebID}&ColName={$ColName}");
        exit;
        break;

    default:
        list_all_cate($WebID, $ColName, $table);
        break;
}

/*-----------秀出結果區--------------*/
include_once 'footer.php';
include_once XOOPS_ROOT_PATH . '/footer.php';
