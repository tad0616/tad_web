<?php
global $xoopsConfig;
include_once XOOPS_ROOT_PATH . "/modules/tad_web/plugins/action/langs/{$xoopsConfig['language']}.php";
$pluginConfig['name']   = _MD_TCW_ACTION;
$pluginConfig['short']  = _MD_TCW_ACTION_SHORT;
$pluginConfig['icon']   = 'fa-picture-o';
$pluginConfig['limit']  = '10';
$pluginConfig['cate']   = true;
$pluginConfig['common'] = true;
$pluginConfig['sql']    = array('tad_web_action');
