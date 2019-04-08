<?php
/*-----------引入檔案區--------------*/
include_once "header.php";
include_once $GLOBALS['xoops']->path('/modules/system/include/functions.php');
$WebID = system_CleanVars($_REQUEST, 'WebID', 0, 'int');

if (!$isMyWeb) {
    redirect_header("index.php?WebID={$WebID}", 3, _MD_TCW_NOT_OWNER);
}
if (!empty($WebID)) {
    $xoopsOption['template_main'] = 'tad_web_assistant.tpl';
} else {
    header("location: index.php");
    exit;
}
include_once XOOPS_ROOT_PATH . "/header.php";
/*-----------function區--------------*/

//分類設定
function list_all_assistant($WebID = "", $plugin = "")
{
    global $xoopsTpl, $plugin_menu_var, $xoopsDB;

    $all_assistant = array();
    $sql           = "select a.*,b.* from `" . $xoopsDB->prefix("tad_web_cate_assistant") . "` as a
    left join `" . $xoopsDB->prefix("tad_web_cate") . "` as b on a.CateID=b.CateID
    where b.`WebID` = '{$WebID}' ";
    $result = $xoopsDB->query($sql) or web_error($sql, __FILE__, __LINE__);
    $i      = 0;
    while ($data = $xoopsDB->fetchArray($result)) {
        foreach ($data as $k => $v) {
            $$k = $v;
        }

        $all_assistant[$i]           = $data;
        $all_assistant[$i]['plugin'] = $plugin_menu_var[$ColName];
        if ($AssistantType == "MemID") {
            $all_assistant[$i]['mem'] = get_tad_web_mems($AssistantID);
        } elseif ($AssistantType == "ParentID") {
            $all_assistant[$i]['mem'] = get_tad_web_parent($AssistantID);
        }
        $i++;
    }

    // die(var_export($plugin_menu_var));

    $xoopsTpl->assign('all_assistant', $all_assistant);
    $xoopsTpl->assign('plugin', $plugin);
    $xoopsTpl->assign('plugin_menu_var', $plugin_menu_var);
    $default_class = get_web_config('default_class', $WebID);

    $sql     = "select a.MemID, a.MemNum ,b.MemName from " . $xoopsDB->prefix("tad_web_link_mems") . " as a left join " . $xoopsDB->prefix("tad_web_mems") . " as b on a.MemID=b.MemID where a.`CateID` = '{$default_class}'  order by a.MemNum";
    $result  = $xoopsDB->query($sql) or web_error($sql, __FILE__, __LINE__);
    $AllMems = array();
    while ($mem = $xoopsDB->fetchArray($result)) {
        $AllMems[] = $mem;
    }
    $xoopsTpl->assign('default_class', $default_class);
    $xoopsTpl->assign('AllMems', $AllMems);

    if (!file_exists(TADTOOLS_PATH . "/formValidator.php")) {
        redirect_header("index.php", 3, _MD_NEED_TADTOOLS);
    }
    include_once TADTOOLS_PATH . "/formValidator.php";
    $formValidator      = new formValidator("#myForm", true);
    $formValidator_code = $formValidator->render();

    if (!file_exists(XOOPS_ROOT_PATH . "/modules/tadtools/sweet_alert.php")) {
        redirect_header("index.php", 3, _MA_NEED_TADTOOLS);
    }
    include_once XOOPS_ROOT_PATH . "/modules/tadtools/sweet_alert.php";
    $sweet_alert = new sweet_alert();
    $sweet_alert->render("delete_assistant_func", "assistant.php?WebID={$WebID}&op=del_assistant&plugin={$plugin}&CateID=", 'CateID');
}

function del_assistant($CateID = "")
{
    global $xoopsDB, $isMyWeb;

    if (!$isMyWeb) {
        redirect_header("{$_SERVER['PHP_SELF']}?WebID=$WebID", 3, _MD_TCW_NOT_OWNER);
    }

    $sql = "delete from " . $xoopsDB->prefix("tad_web_cate_assistant") . " where CateID='$CateID'";
    $xoopsDB->queryF($sql) or web_error($sql, __FILE__, __LINE__);

}

/*-----------執行動作判斷區----------*/
$op     = system_CleanVars($_REQUEST, 'op', '', 'string');
$plugin = system_CleanVars($_REQUEST, 'plugin', '', 'string');
$CateID = system_CleanVars($_REQUEST, 'CateID', '', 'string');
$MemID  = system_CleanVars($_REQUEST, 'MemID', '', 'string');

common_template($WebID, $web_all_config);

switch ($op) {

    case "del_assistant":
        del_assistant($CateID);
        header("location:{$_SERVER['PHP_SELF']}?WebID={$WebID}&plugin={$plugin}");
        exit;
        break;

    case "save_assistant":
        set_assistant($CateID, $MemID);
        header("location:{$_SERVER['PHP_SELF']}?WebID={$WebID}&plugin={$plugin}");
        exit;
        break;

    default:
        list_all_assistant($WebID, $plugin);
        break;
}

/*-----------秀出結果區--------------*/
include_once 'footer.php';
include_once XOOPS_ROOT_PATH . '/footer.php';
