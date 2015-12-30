<?php
global $xoopsConfig;
include_once XOOPS_ROOT_PATH . "/modules/tad_web/plugins/files/langs/{$xoopsConfig['language']}.php";
$pluginConfig['name']       = _MD_TCW_FILES;
$pluginConfig['short']      = _MD_TCW_FILES_SHORT;
$pluginConfig['icon']       = 'fa-upload';
$pluginConfig['limit']      = '5';
$pluginConfig['cate']       = true;
$pluginConfig['cate_table'] = 'tad_web_files';
$pluginConfig['common']     = true;
$pluginConfig['sql']        = array('tad_web_files');
$pluginConfig['setup']      = false;
$pluginConfig['add']        = true;
$pluginConfig['menu']       = true;
