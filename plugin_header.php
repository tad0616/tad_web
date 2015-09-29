<?php
$plugin_name = "tad_web_{$plugin}";

$web_cate = new web_cate($WebID, $plugin, $plugin_name);

include_once "plugins/{$plugin}/class.php";
$$plugin_name = new $plugin_name($WebID);

if (!empty($_GET['WebID'])) {
    $xoopsOption['template_main'] = 'tad_web_tpl_b3.html';
} else {
    $xoopsOption['template_main'] = set_bootstrap('tad_web_tpl.html');
}
