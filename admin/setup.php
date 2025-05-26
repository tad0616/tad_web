<?php
use Xmf\Request;
use XoopsModules\Tadtools\Utility;
use XoopsModules\Tad_web\Tools as TadWebTools;
/*-----------引入檔案區--------------*/
$GLOBALS['xoopsOption']['template_main'] = 'tad_web_adm_setup.tpl';
require_once __DIR__ . '/header.php';
require_once dirname(__DIR__) . '/function.php';

/*-----------執行動作判斷區----------*/
$op = Request::getString('op');

switch ($op) {

    //新增資料
    case 'save_plugins':
        save_plugins();
        header("location: {$_SERVER['PHP_SELF']}");
        exit;

    //預設動作
    default:
        tad_web_setup_form();
        break;

}

/*-----------秀出結果區--------------*/
require_once __DIR__ . '/footer.php';

/*-----------function區--------------*/
//tad_web_setup編輯表單
function tad_web_setup_form()
{
    global $xoopsTpl;

    $plugins = get_plugins(0, 'edit');

    foreach ($plugins as $i => $plugin) {
        $plugins[$i]['limit'] = TadWebTools::get_web_config($plugin['dirname'] . '_limit', '0');
    }
    // die(var_export($plugins));
    $xoopsTpl->assign('plugins', $plugins);
    $web_plugin_display_arr = [];
    $web_setup_show         = TadWebTools::get_web_config('web_plugin_display_arr', '0');
    if ($web_setup_show) {
        $web_plugin_display_arr = explode(',', $web_setup_show);
    }

    $xoopsTpl->assign('web_plugin_display_arr', $web_plugin_display_arr);
    Utility::get_jquery(true);
}

//新增資料到tad_web_setup中

function save_plugins()
{
    global $xoopsDB;
    $plugins = get_plugins(0);

    $i = 1;

    $sql = 'DELETE FROM `' . $xoopsDB->prefix('tad_web_plugins') . '` WHERE `WebID`=0';
    Utility::query($sql) or Utility::web_error($sql, __FILE__, __LINE__);

    $display_plugins = [];
    foreach ($plugins as $plugin) {
        $dirname     = $plugin['dirname'];
        $PluginTitle = (string) $_POST['plugin_name'][$dirname];

        $sql = 'REPLACE INTO `' . $xoopsDB->prefix('tad_web_plugins') . '` (`PluginDirname`, `PluginTitle`, `PluginSort`, `PluginEnable`, `WebID`) VALUES (?, ?, ?, ?, ?)';
        Utility::query($sql, 'ssisi', [$dirname, $PluginTitle, $i, (int) $_POST['plugin_display'][$dirname], 0]) or Utility::web_error($sql, __FILE__, __LINE__);

        if ('none' !== $_POST['plugin_limit'][$dirname]) {
            save_web_config($dirname . '_limit', $_POST['plugin_limit'][$dirname], 0);
        }

        if ('1' == $_POST['plugin_display'][$dirname]) {
            $display_plugins[] = $dirname;
        }
        mkTitleImg(0, $dirname, $PluginTitle);
        $i++;
    }

    save_web_config('web_plugin_display_arr', implode(',', $display_plugins), 0);
    mk_menu_var_file(0);
}
