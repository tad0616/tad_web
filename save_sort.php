<?php
use XoopsModules\Tadtools\Utility;
require_once dirname(dirname(__DIR__)) . '/mainfile.php';
header('HTTP/1.1 200 OK');
$xoopsLogger->activated = false;
require_once __DIR__ . '/function.php';
$sort = 1;
$WebID = empty($_GET['WebID']) ? '' : (int) $_GET['WebID'];
$display_plugins = [];
foreach ($_POST['tr'] as $dirname) {
    $sql = 'UPDATE `' . $xoopsDB->prefix('tad_web_plugins') . '` SET `PluginSort`=? WHERE `PluginDirname`=? AND `WebID`=?';
    Utility::query($sql, 'isi', [$sort, $dirname, $WebID]) or die(_TAD_SORT_FAIL . ' (' . date('Y-m-d H:i:s') . ')' . $sql);
    $display_plugins[] = $dirname;
    $sort++;
}
// save_web_config('web_plugin_display_arr', implode(',', $display_plugins), $WebID);
mk_menu_var_file($WebID);
echo _TAD_SORTED . '(' . date('Y-m-d H:i:s') . ')';
