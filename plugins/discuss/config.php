<?php
global $xoopsConfig;
require_once XOOPS_ROOT_PATH . "/modules/tad_web/plugins/discuss/langs/{$xoopsConfig['language']}.php";
$pluginConfig['name'] = _MD_TCW_DISCUSS;
$pluginConfig['short'] = _MD_TCW_DISCUSS_SHORT;
$pluginConfig['icon'] = 'fa-comments';
$pluginConfig['limit'] = '5';
$pluginConfig['cate'] = true;
$pluginConfig['cate_table'] = 'tad_web_discuss';
$pluginConfig['top_table'] = 'tad_web_discuss';
$pluginConfig['common'] = true;
$pluginConfig['sql'] = ['tad_web_discuss'];
$pluginConfig['setup'] = false;
$pluginConfig['add'] = true;
$pluginConfig['menu'] = true;
$pluginConfig['export'] = true;
$pluginConfig['tag'] = true;
$pluginConfig['top_score'] = 1;
$pluginConfig['assistant'] = false;
