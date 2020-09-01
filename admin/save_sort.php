<?php
use Xmf\Request;
require_once __DIR__ . '/header.php';
require_once dirname(__DIR__) . '/function.php';

$op = Request::getString('op');

$sort = 1;

$display_plugins = [];
if ('plugin' === $op) {
    foreach ($_POST['tr'] as $dirname) {
        $sql = 'update ' . $xoopsDB->prefix('tad_web_plugins') . " set `PluginSort`='{$sort}' where `PluginDirname`='{$dirname}' and WebID='{$WebID}'";
        $xoopsDB->queryF($sql) or die(_TAD_SORT_FAIL . ' (' . date('Y-m-d H:i:s') . ')' . $sql);
        $display_plugins[] = $dirname;
        $sort++;
    }
    save_web_config('web_plugin_display_arr', implode(',', $display_plugins), 0);
    mk_menu_var_file(0);
} else {
    foreach ($_POST['tr'] as $WebID) {
        $sql = 'update ' . $xoopsDB->prefix('tad_web') . " set `WebSort`='{$sort}' where `WebID`='{$WebID}'";
        $xoopsDB->queryF($sql) or die(_MA_TCW_UPDATE_FAIL . ' (' . date('Y-m-d H:i:s') . ')' . $sql);
        $sort++;
    }
}

echo _MA_TCW_SAVE_SORT_OK . '(' . date('Y-m-d H:i:s') . ')';
