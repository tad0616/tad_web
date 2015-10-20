<?php
global $xoopsConfig;
include_once XOOPS_ROOT_PATH . "/modules/tad_web/plugins/homework/langs/{$xoopsConfig['language']}.php";
$pluginConfig['name']   = _MD_TCW_HOMEWORK;
$pluginConfig['short']  = _MD_TCW_HOMEWORK_SHORT;
$pluginConfig['icon']   = 'fa-pencil-square-o';
$pluginConfig['limit']  = '5';
$pluginConfig['cate']   = true;
$pluginConfig['common'] = true;
$pluginConfig['sql']    = array('tad_web_homework');
