<?php
use XoopsModules\Tadtools\Utility;
$plugin_name = "tad_web_{$plugin}";
/****網站設定值****/
$web_all_config = get_web_all_config($WebID);

$show_plugin = true;
if ($WebID) {
    define('_DISPLAY_MODE', 'home_plugin');
    Utility::test($web_all_config, 'web_all_config', 'dd');
    $show_plugin = false !== mb_strrpos($web_all_config['web_plugin_enable_arr'], $plugin) ? true : false;
} else {
    define('_DISPLAY_MODE', 'index_plugin');
}

Utility::test($show_plugin, 'show_plugin', 'dd');
if ($show_plugin) {
    $GLOBALS['xoopsOption']['template_main'] = 'tad_web_tpl.tpl';
} else {
    $GLOBALS['xoopsOption']['template_main'] = 'tad_web_unable.tpl';
}

require_once "plugins/{$plugin}/class.php";
$$plugin_name = new $plugin_name($WebID);
