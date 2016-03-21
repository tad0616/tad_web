<?php
global $xoopsConfig;
include_once XOOPS_ROOT_PATH . "/modules/tad_web/plugins/system/langs/{$xoopsConfig['language']}.php";
$pluginConfig['name']       = _MD_TCW_SYSTEM;
$pluginConfig['short']      = _MD_TCW_SYSTEM_SHORT;
$pluginConfig['icon']       = 'fa-lock';
$pluginConfig['limit']      = '';
$pluginConfig['cate']       = false;
$pluginConfig['cate_table'] = '';
$pluginConfig['top_table']  = '';
$pluginConfig['common']     = false;
$pluginConfig['sql']        = array();
$pluginConfig['setup']      = false;
$pluginConfig['add']        = false;
$pluginConfig['menu']       = false;
$pluginConfig['export']     = false;
$pluginConfig['tag']        = false;
$pluginConfig['top_score']  = 0;
