<?php
use Xmf\Request;
use XoopsModules\Tadtools\Utility;
require_once __DIR__ . '/header.php';
require_once dirname(__DIR__) . '/function.php';
header('HTTP/1.1 200 OK');
$xoopsLogger->activated = false;
$op = Request::getString('op');

$sort = 1;

$display_plugins = [];
if ('plugin' === $op) {
    foreach ($_POST['tr'] as $dirname) {
        $sql = 'UPDATE `' . $xoopsDB->prefix('tad_web_plugins') . '` SET `PluginSort`=? WHERE `PluginDirname`=? AND `WebID`=?';
        Utility::query($sql, 'isi', [$sort, $dirname, $WebID]) or die(_TAD_SORT_FAIL . ' (' . date('Y-m-d H:i:s') . ')' . $sql);

        $display_plugins[] = $dirname;
        $sort++;
    }
    save_web_config('web_plugin_display_arr', implode(',', $display_plugins), 0);
    mk_menu_var_file(0);
} else {
    foreach ($_POST['tr'] as $WebID) {
        $sql = 'UPDATE `' . $xoopsDB->prefix('tad_web') . '` SET `WebSort`=? WHERE `WebID`=?';
        Utility::query($sql, 'ii', [$sort, $WebID]) or die(_MA_TCW_UPDATE_FAIL . ' (' . date('Y-m-d H:i:s') . ')' . $sql);
        $sort++;
    }
}

echo _MA_TCW_SAVE_SORT_OK . '(' . date('Y-m-d H:i:s') . ')';
