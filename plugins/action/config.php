<?php
global $xoopsConfig;
require_once XOOPS_ROOT_PATH . "/modules/tad_web/plugins/action/langs/{$xoopsConfig['language']}.php";
$pluginConfig['name'] = _MD_TCW_ACTION;
$pluginConfig['short'] = _MD_TCW_ACTION_SHORT;
$pluginConfig['icon'] = 'fa-image';
$pluginConfig['limit'] = '10';
$pluginConfig['cate'] = true;
$pluginConfig['cate_table'] = 'tad_web_action';
$pluginConfig['top_table'] = 'tad_web_action';
$pluginConfig['common'] = true;
$pluginConfig['sql'] = ['tad_web_action'];
$pluginConfig['setup'] = true;
$pluginConfig['add'] = true;
$pluginConfig['menu'] = true;
$pluginConfig['export'] = true;
$pluginConfig['tag'] = true;
$pluginConfig['top_score'] = 3;
$pluginConfig['assistant'] = true;
