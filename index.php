<?php
/*-----------引入檔案區--------------*/
include_once "header.php";
if (!empty($_GET['WebID'])) {
    $xoopsOption['template_main'] = 'tad_web_index_b3.html';
} else {
    $xoopsOption['template_main'] = set_bootstrap('tad_web_index.html');
}
include_once XOOPS_ROOT_PATH . "/header.php";
/*-----------function區--------------*/

//首頁
function ClassHome($WebID = "")
{
    global $xoopsDB, $xoopsUser, $xoopsTpl, $MyWebs;

    $plugins = get_plugins($WebID, 'show', true);
    $xoopsTpl->assign('plugins', $plugins);
    $xoopsTpl->assign('display_mode', 'home');
    //die(var_export($plugins));
    foreach ($plugins as $plugin) {
        $dirname = $plugin['dirname'];
        if ($plugin['db']['display'] != '1') {
            continue;
        }
        include_once "plugins/{$dirname}/class.php";
        $plugin_name  = "tad_web_{$dirname}";
        $$plugin_name = new $plugin_name($WebID);
        $$plugin_name->list_all($CateID, $plugin['db']['limit']);

    }

    $sql = "update " . $xoopsDB->prefix("tad_web") . " set `WebCounter` = `WebCounter` +1	where WebID ='{$WebID}'";
    $xoopsDB->queryF($sql);

    $xoopsTpl->assign('MyWebs', $MyWebs);
}

//取得所有班級
function list_all_class()
{
    global $xoopsTpl;
    $plugins = get_plugins('', 'show');
    $xoopsTpl->assign('plugins', $plugins);
    $xoopsTpl->assign('display_mode', 'index');

    foreach ($plugins as $plugin) {
        $dirname = $plugin['dirname'];
        include_once "plugins/{$dirname}/class.php";
        $plugin_name  = "tad_web_{$dirname}";
        $$plugin_name = new $plugin_name();
        $$plugin_name->list_all($CateID, 5);
    }
}

/*-----------執行動作判斷區----------*/
include_once $GLOBALS['xoops']->path('/modules/system/include/functions.php');
$op    = system_CleanVars($_REQUEST, 'op', '', 'string');
$WebID = system_CleanVars($_REQUEST, 'WebID', 0, 'int');

common_template($WebID);

if (!empty($WebID)) {
    ClassHome($WebID);
    $op = 'ClassHome';
} else {
    list_all_class();
    $op = 'list_all_class';
}

/*-----------秀出結果區--------------*/
include_once 'footer.php';
include_once XOOPS_ROOT_PATH . '/footer.php';
