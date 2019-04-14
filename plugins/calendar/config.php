<?php
global $xoopsConfig;
include_once XOOPS_ROOT_PATH . "/modules/tad_web/plugins/calendar/langs/{$xoopsConfig['language']}.php";
$pluginConfig['name'] = _MD_TCW_CALENDAR;
$pluginConfig['short'] = _MD_TCW_CALENDAR_SHORT;
$pluginConfig['icon'] = 'fa-calendar';
$pluginConfig['limit'] = '';
$pluginConfig['cate'] = false;
$pluginConfig['cate_table'] = '';
$pluginConfig['top_table'] = 'tad_web_calendar';
$pluginConfig['common'] = true;
$pluginConfig['sql'] = ['tad_web_calendar'];
$pluginConfig['setup'] = true;
$pluginConfig['add'] = true;
$pluginConfig['menu'] = true;
$pluginConfig['export'] = false;
$pluginConfig['tag'] = false;
$pluginConfig['top_score'] = 2;
$pluginConfig['assistant'] = false;
