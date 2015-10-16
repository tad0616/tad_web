<?php
include_once XOOPS_ROOT_PATH . "/modules/tad_web/plugins/page/langs/{$xoopsConfig['language']}.php";
$pluginConfig['name']   = _MD_TCW_PAGE;
$pluginConfig['short']  = _MD_TCW_PAGE_SHORT;
$pluginConfig['icon']   = 'fa-file-text-o';
$pluginConfig['limit']  = '';
$pluginConfig['cate']   = true;
$pluginConfig['common'] = false;
$pluginConfig['sql']    = array('tad_web_page');
