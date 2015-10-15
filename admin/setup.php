<?php
/*-----------引入檔案區--------------*/
$xoopsOption['template_main'] = "tad_web_adm_setup.html";
include_once 'header.php';
include_once "../function.php";
/*-----------function區--------------*/
//tad_web_setup編輯表單
function tad_web_setup_form()
{
    global $xoopsDB, $xoopsTpl, $isAdmin;

    $plugins = get_plugins(0, 'edit');
    $xoopsTpl->assign('plugins', $plugins);
    $web_setup_show_arr = '';
    $web_setup_show     = get_web_config('web_setup_show_arr', '0');
    if ($web_setup_show) {
        $web_setup_show_arr = explode(',', $web_setup_show);
    }

    $xoopsTpl->assign('web_setup_show_arr', $web_setup_show_arr);
    get_jquery(true);
}

//新增資料到tad_web_setup中

function save_plugins()
{
    global $xoopsDB;
    $plugins = get_plugins(0);
    //die(var_export($plugins));
    $myts = &MyTextSanitizer::getInstance();

    $i = 1;

    $sql = "delete from " . $xoopsDB->prefix("tad_web_plugins") . " where WebID='0'";
    $xoopsDB->queryF($sql) or web_error($sql);
    $display_plugins = '';
    foreach ($plugins as $plugin) {
        $dirname     = $plugin['dirname'];
        $PluginTitle = $myts->addSlashes($_POST['plugin_name'][$dirname]);

        $sql = "replace into " . $xoopsDB->prefix("tad_web_plugins") . " (`PluginDirname`, `PluginTitle`, `PluginSort`, `PluginEnable`, `WebID`) values('{$dirname}', '{$PluginTitle}', '{$i}', '1', '0')";
        $xoopsDB->queryF($sql) or web_error($sql);

        save_web_config($dirname . '_limit', $_POST['plugin_limit'][$dirname]);
        //save_web_config($dirname . '_display', $_POST['plugin_display'][$dirname]);
        if ($_POST['plugin_display'][$dirname] == '1') {
            $display_plugins[] = $dirname;
        }
        $i++;
    }

    mk_menu_var_file(0);
    save_web_config('web_setup_show_arr', implode(',', $display_plugins));

}

/*-----------執行動作判斷區----------*/
include_once $GLOBALS['xoops']->path('/modules/system/include/functions.php');
$op = system_CleanVars($_REQUEST, 'op', '', 'string');

switch ($op) {
    /*---判斷動作請貼在下方---*/

    //新增資料
    case "save_plugins":
        save_plugins();
        header("location: {$_SERVER['PHP_SELF']}");
        exit;
        break;

    //預設動作
    default:
        tad_web_setup_form();
        break;

        /*---判斷動作請貼在上方---*/
}

/*-----------秀出結果區--------------*/
include_once 'footer.php';
