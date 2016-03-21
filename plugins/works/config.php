<?php
global $xoopsConfig;
include_once XOOPS_ROOT_PATH . "/modules/tad_web/plugins/works/langs/{$xoopsConfig['language']}.php";
$pluginConfig['name']       = _MD_TCW_WORKS;
$pluginConfig['short']      = _MD_TCW_WORKS_SHORT;
$pluginConfig['icon']       = 'fa-paint-brush';
$pluginConfig['limit']      = '5';
$pluginConfig['cate']       = true;
$pluginConfig['cate_table'] = 'tad_web_works';
$pluginConfig['top_table']  = 'tad_web_works';
$pluginConfig['common']     = true;
$pluginConfig['sql']        = array('tad_web_works', 'tad_web_works_content');
$pluginConfig['setup']      = true;
$pluginConfig['add']        = true;
$pluginConfig['menu']       = true;
$pluginConfig['export']     = true;
$pluginConfig['tag']        = true;
$pluginConfig['top_score']  = 3;
