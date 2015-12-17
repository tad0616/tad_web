<?php
/*-----------引入檔案區--------------*/
include_once "header.php";
if (!empty($_REQUEST['WebID']) and $isMyWeb) {
    $xoopsOption['template_main'] = 'tad_web_plugin_setup_b3.html';
} elseif (!$isMyWeb and $MyWebs) {
    redirect_header($_SERVER['PHP_SELF'] . "?WebID={$MyWebs[0]}", 3, _MD_TCW_AUTO_TO_HOME);
} else {
    redirect_header("index.php?WebID={$_GET['WebID']}", 3, _MD_TCW_NOT_OWNER);
}

include_once XOOPS_ROOT_PATH . "/header.php";
/*-----------function區--------------*/

//外掛設定功能
function plugin_setup($WebID, $plugin)
{
    global $xoopsTpl, $isMyWeb, $MyWebs, $xoopsUser, $xoopsConfig;

    if (!$isMyWeb and $MyWebs) {
        redirect_header($_SERVER['PHP_SELF'] . "?op=WebID={$MyWebs[0]}&op=setup", 3, _MD_TCW_AUTO_TO_HOME);
    } elseif (!$xoopsUser or empty($WebID) or empty($MyWebs)) {
        redirect_header("index.php", 3, _MD_TCW_NOT_OWNER);
    }

    $myts        = &MyTextSanitizer::getInstance();
    $pluginSetup = '';
    $setup_file  = XOOPS_ROOT_PATH . "/modules/tad_web/plugins/{$plugin}/setup.php";
    if (file_exists($setup_file)) {
        require XOOPS_ROOT_PATH . "/modules/tad_web/plugins/{$plugin}/langs/{$xoopsConfig['language']}.php";
        require $setup_file;
    }
    $setup_db_values         = get_plugin_setup_values($WebID);
    $TadUpFiles_plugin_setup = TadUpFiles_plugin_setup($WebID, $plugin);
    foreach ($plugin_setup as $k => $setup) {
        $value = isset($setup_db_values[$setup['name']]) ? $myts->htmlSpecialChars($setup_db_values[$setup['name']]) : $setup['default'];

        $pluginSetup[$k]['name']    = $setup['name'];
        $pluginSetup[$k]['text']    = $setup['text'];
        $pluginSetup[$k]['desc']    = $setup['desc'];
        $pluginSetup[$k]['type']    = $setup['type'];
        $pluginSetup[$k]['value']   = $value;
        $pluginSetup[$k]['default'] = $setup['default'];
        $pluginSetup[$k]['options'] = $setup['options'];

        if ($setup['type'] == "file") {
            import_img($setup['default'], "{$plugin}_{$setup['name']}", $WebID, "");
            $TadUpFiles_plugin_setup->set_col("{$plugin}_{$setup['name']}", $WebID);
            $pluginSetup[$k]['form'] = $TadUpFiles_plugin_setup->upform(false, "{$plugin}_{$setup['name']}", null, false);
            $pluginSetup[$k]['list'] = $TadUpFiles_plugin_setup->get_file_for_smarty();
        }
    }
    $xoopsTpl->assign('plugin_setup', $pluginSetup);
    $xoopsTpl->assign('plugin', $plugin);
    $xoopsTpl->assign('WebID', $WebID);
}

//儲存額外設定值
function save_plugin_setup($WebID = "", $plugin = "")
{
    global $xoopsDB, $xoopsConfig;

    //額外佈景設定
    if (file_exists(XOOPS_ROOT_PATH . "/modules/tad_web/plugins/{$plugin}/setup.php")) {
        require XOOPS_ROOT_PATH . "/modules/tad_web/plugins/{$plugin}/langs/{$xoopsConfig['language']}.php";
        require XOOPS_ROOT_PATH . "/modules/tad_web/plugins/{$plugin}/setup.php";

        $myts = &MyTextSanitizer::getInstance();
        foreach ($plugin_setup as $k => $setup) {
            $name  = $setup['name'];
            $value = isset($_POST[$name]) ? $myts->addSlashes($_POST[$name]) : $setup['default'];

            $sql = "replace into " . $xoopsDB->prefix("tad_web_plugins_setup") . " (`WebID`, `plugin`, `name`, `type`, `value`) values($WebID, '{$plugin}','{$setup['name']}' , '{$setup['type']}' , '{$value}')";
            $xoopsDB->queryF($sql) or web_error($sql);
        }
    }
}

function TadUpFiles_plugin_setup($WebID, $plugin)
{
    global $xoopsConfig;
    include_once XOOPS_ROOT_PATH . "/modules/tadtools/TadUpFiles.php";
    $TadUpFiles_plugin_setup = new TadUpFiles("tad_web", "/{$WebID}/{$plugin}", null, "", "/thumbs");
    $TadUpFiles_plugin_setup->set_thumb("100px", "60px", "#000", "center center", "no-repeat", "contain");
    return $TadUpFiles_plugin_setup;
}
/*-----------執行動作判斷區----------*/
include_once $GLOBALS['xoops']->path('/modules/system/include/functions.php');
$op     = system_CleanVars($_REQUEST, 'op', '', 'string');
$WebID  = system_CleanVars($_REQUEST, 'WebID', 0, 'int');
$plugin = system_CleanVars($_REQUEST, 'plugin', '', 'string');

common_template($WebID);

switch ($op) {

    //新增資料
    case "save_plugin_setup":
        save_plugin_setup($WebID, $plugin);
        header("location: {$plugin}.php?WebID={$WebID}");
        exit;
        break;

    //預設動作
    default:
        plugin_setup($WebID, $plugin);
        break;

}

/*-----------秀出結果區--------------*/
include_once 'footer.php';
include_once XOOPS_ROOT_PATH . '/footer.php';
