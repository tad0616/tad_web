<?php
$plugin_name = "tad_web_{$plugin}";
/****網站設定值****/
$web_all_config = get_web_all_config($WebID);

$show_plugin = true;
if ($WebID) {
    define('_DISPLAY_MODE', 'home_plugin');
    $show_plugin = strrpos($web_all_config['web_plugin_enable_arr'], $plugin) !== false ? true : false;
} else {
    define('_DISPLAY_MODE', 'index_plugin');
}

if ($show_plugin) {
    if (!empty($WebID)) {
        $xoopsOption['template_main'] = 'tad_web_tpl_b3.html';
    } else {
        $xoopsOption['template_main'] = set_bootstrap('tad_web_tpl.html');
    }
} else {
    $xoopsOption['template_main'] = 'tad_web_unable_b3.html';
}

include_once "plugins/{$plugin}/class.php";
$$plugin_name = new $plugin_name($WebID);
