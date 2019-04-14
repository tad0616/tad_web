<?php
global $xoopsConfig;
require_once XOOPS_ROOT_PATH . "/modules/tad_web/plugins/schedule/langs/{$xoopsConfig['language']}.php";
$pluginConfig['name'] = _MD_TCW_SCHEDULE;
$pluginConfig['short'] = _MD_TCW_SCHEDULE_SHORT;
$pluginConfig['icon'] = 'fa-table';
$pluginConfig['limit'] = '';
$pluginConfig['cate'] = true;
$pluginConfig['cate_table'] = 'tad_web_schedule';
$pluginConfig['top_table'] = '';
$pluginConfig['common'] = true;
$pluginConfig['sql'] = ['tad_web_schedule', 'tad_web_schedule_data'];
$pluginConfig['setup'] = false;
$pluginConfig['add'] = true;
$pluginConfig['menu'] = true;
$pluginConfig['export'] = false;
$pluginConfig['tag'] = false;
$pluginConfig['top_score'] = 0;
$pluginConfig['assistant'] = true;
