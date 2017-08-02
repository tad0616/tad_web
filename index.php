<?php
/*-----------引入檔案區--------------*/
include_once "header.php";
$xoopsOption['template_main'] = 'tad_web_index.tpl';
include_once XOOPS_ROOT_PATH . "/header.php";
/*-----------function區--------------*/

//首頁
function ClassHome($WebID = "")
{
    global $xoopsDB, $xoopsUser, $xoopsTpl, $MyWebs;

    $web = get_tad_web($WebID);

    define('_DISPLAY_MODE', 'home');

    $web_plugin_enable_arr = get_web_config("web_plugin_enable_arr", $WebID);
    if (empty($web_plugin_enable_arr)) {
        $show_arr = get_dir_plugins();
    } else {
        $show_arr = explode(',', $web_plugin_enable_arr);
    }
    $plugin_data_total = 0;
    foreach ($show_arr as $dirname) {
        if (empty($dirname)) {
            continue;
        }
        include_once "plugins/{$dirname}/class.php";
        $plugin_name  = "tad_web_{$dirname}";
        $$plugin_name = new $plugin_name($WebID);
        $plugin_data_total += $$plugin_name->get_total();
    }
    $sql = "update " . $xoopsDB->prefix("tad_web") . " set `WebCounter` = `WebCounter` +1	where WebID ='{$WebID}'";
    $xoopsDB->queryF($sql);

    $xoopsTpl->assign('MyWebs', $MyWebs);
    $xoopsTpl->assign('plugin_data_total', $plugin_data_total);
}

//取得所有班級
function list_all_class()
{
    global $xoopsTpl, $xoopsModuleConfig;

    $xoopsTpl->assign('module_title', $xoopsModuleConfig['module_title']);

    $web_plugin_display_arr = get_web_config("web_plugin_display_arr", 0);
    if (empty($web_plugin_display_arr)) {
        $show_arr = get_dir_plugins();
    } else {
        $show_arr = explode(',', $web_plugin_display_arr);
    }

    $xoopsTpl->assign('show_arr', $show_arr);
    // $xoopsTpl->assign('display_mode', 'index');
    define('_DISPLAY_MODE', 'index');
    $data_count = "";

    foreach ($show_arr as $dirname) {
        if (empty($dirname)) {
            continue;
        }
        include_once "plugins/{$dirname}/class.php";
        $limit                = get_web_config("{$dirname}_limit", 0);
        $plugin_name          = "tad_web_{$dirname}";
        $$plugin_name         = new $plugin_name(0);
        $data_count[$dirname] = $$plugin_name->list_all('', $limit);
    }
    $xoopsTpl->assign('data_count', $data_count);
    $xoopsTpl->assign('data_count_sum', array_sum($data_count));
}

function view_notice($NoticeID = "")
{
    global $xoopsTpl;
    $xoopsTpl->assign('Notice', get_tad_web_notice($NoticeID));
    $xoopsTpl->assign('theme_display_mode', 'blank');
    $xoopsTpl->assign('blank_kind', 'content');

}

/*-----------執行動作判斷區----------*/
include_once $GLOBALS['xoops']->path('/modules/system/include/functions.php');
$op       = system_CleanVars($_REQUEST, 'op', '', 'string');
$WebID    = system_CleanVars($_REQUEST, 'WebID', 0, 'int');
$NoticeID = system_CleanVars($_REQUEST, 'NoticeID', 0, 'int');

common_template($WebID, $web_all_config);

switch ($op) {
    //新增資料
    case "notice":
        view_notice($NoticeID);
        break;

    //預設動作
    default:
        if (!empty($WebID)) {
            ClassHome($WebID);
            $op = 'ClassHome';
        } else {
            list_all_class();
            $op = 'list_all_class';
        }
}
/*-----------秀出結果區--------------*/
include_once 'footer.php';
include_once XOOPS_ROOT_PATH . '/footer.php';
