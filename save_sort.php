<?php
include_once "../../mainfile.php";
include_once "function.php";
$sort            = 1;
$WebID           = empty($_GET['WebID']) ? "" : intval($_GET['WebID']);
$display_plugins = "";
foreach ($_POST['tr'] as $dirname) {
    $sql = "update " . $xoopsDB->prefix("tad_web_plugins") . " set `PluginSort`='{$sort}' where `PluginDirname`='{$dirname}' and WebID='{$WebID}'";
    $xoopsDB->queryF($sql) or die(_TAD_SORT_FAIL . " (" . date("Y-m-d H:i:s") . ")" . $sql);
    $display_plugins[] = $dirname;
    $sort++;
}
save_web_config('web_plugin_display_arr', implode(',', $display_plugins), $WebID);
mk_menu_var_file($WebID);
echo _TAD_SORTED . "(" . date("Y-m-d H:i:s") . ")";
