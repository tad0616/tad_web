<?php
global $xoopsConfig;
include_once XOOPS_ROOT_PATH . "/modules/tad_web/plugins/menu/langs/{$xoopsConfig['language']}.php";
$pluginConfig['name']       = _MD_TCW_MENU;
$pluginConfig['short']      = _MD_TCW_MENU_SHORT;
$pluginConfig['icon']       = 'fa-list-alt';
$pluginConfig['limit']      = '';
$pluginConfig['cate']       = true;
$pluginConfig['cate_table'] = 'tad_web_menu';
$pluginConfig['top_table']  = '';
$pluginConfig['common']     = false;
$pluginConfig['sql']        = array('tad_web_menu');
$pluginConfig['setup']      = false;
$pluginConfig['add']        = true;
$pluginConfig['menu']       = true;
$pluginConfig['export']     = false;
$pluginConfig['tag']        = false;
$pluginConfig['top_score']  = 0;
