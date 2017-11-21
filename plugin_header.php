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
    $xoopsOption['template_main'] = 'tad_web_tpl.tpl';
} else {
    $xoopsOption['template_main'] = 'tad_web_unable.tpl';
}

include_once "plugins/{$plugin}/class.php";
$$plugin_name = new $plugin_name($WebID);

function chk_self_web($WebID, $other = null)
{
    global $isMyWeb, $MyWebs;
    if (!$isMyWeb and $MyWebs) {
        redirect_header($_SERVER['PHP_SELF'] . "?op=WebID={$MyWebs[0]}&op=edit_form", 3, _MD_TCW_AUTO_TO_HOME);
    } elseif (!$isMyWeb) {
        if ($other !== null and !$other) {
            redirect_header("index.php?WebID={$WebID}", 3, _MD_TCW_NOT_OWNER);
        } else {
            return true;
        }
        redirect_header("index.php?WebID={$WebID}", 3, _MD_TCW_NOT_OWNER);
    }
}
