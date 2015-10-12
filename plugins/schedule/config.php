<?php
include_once XOOPS_ROOT_PATH . "/modules/tad_web/plugins/schedule/langs/{$xoopsConfig['language']}.php";
$pluginConfig['name']   = _MD_TCW_SCHEDULE;
$pluginConfig['short']  = _MD_TCW_SCHEDULE_SHORT;
$pluginConfig['icon']   = 'fa-table';
$pluginConfig['limit']  = '';
$pluginConfig['cate']   = true;
$pluginConfig['common'] = true;
$pluginConfig['sql']    = array('tad_web_schedule', 'tad_web_schedule_data');
