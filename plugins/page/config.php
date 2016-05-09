<?php
global $xoopsConfig;
include_once XOOPS_ROOT_PATH . "/modules/tad_web/plugins/page/langs/{$xoopsConfig['language']}.php";
$pluginConfig['name']       = _MD_TCW_PAGE;
$pluginConfig['short']      = _MD_TCW_PAGE_SHORT;
$pluginConfig['icon']       = 'fa-file-text-o';
$pluginConfig['limit']      = '';
$pluginConfig['cate']       = true;
$pluginConfig['cate_table'] = 'tad_web_page';
$pluginConfig['top_table']  = 'tad_web_page';
$pluginConfig['common']     = true;
$pluginConfig['sql']        = array('tad_web_page');
$pluginConfig['setup']      = true;
$pluginConfig['add']        = true;
$pluginConfig['menu']       = true;
$pluginConfig['export']     = false;
$pluginConfig['tag']        = true;
$pluginConfig['top_score']  = 3;
$pluginConfig['assistant']  = true;
