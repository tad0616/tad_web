<?php
global $xoopsConfig;
include_once XOOPS_ROOT_PATH . "/modules/tad_web/plugins/calendar/langs/{$xoopsConfig['language']}.php";
$pluginConfig['name']   = _MD_TCW_CALENDAR;
$pluginConfig['short']  = _MD_TCW_CALENDAR_SHORT;
$pluginConfig['icon']   = 'fa-calendar';
$pluginConfig['limit']  = '';
$pluginConfig['cate']   = true;
$pluginConfig['common'] = false;
$pluginConfig['sql']    = array('tad_web_calendar');