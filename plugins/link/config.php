<?php
global $xoopsConfig;
include_once XOOPS_ROOT_PATH . "/modules/tad_web/plugins/link/langs/{$xoopsConfig['language']}.php";
$pluginConfig['name']   = _MD_TCW_LINK;
$pluginConfig['short']  = _MD_TCW_LINK_SHORT;
$pluginConfig['icon']   = 'fa-globe';
$pluginConfig['limit']  = '5';
$pluginConfig['cate']   = true;
$pluginConfig['common'] = true;
$pluginConfig['sql']    = array('tad_web_link');