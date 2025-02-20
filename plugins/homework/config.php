<?php
global $xoopsConfig;
require_once XOOPS_ROOT_PATH . "/modules/tad_web/plugins/homework/langs/{$xoopsConfig['language']}.php";
$pluginConfig['name'] = _MD_TCW_HOMEWORK;
$pluginConfig['short'] = _MD_TCW_HOMEWORK_SHORT;
$pluginConfig['icon'] = 'fa-pencil';
$pluginConfig['limit'] = '5';
$pluginConfig['cate'] = true;
$pluginConfig['cate_table'] = 'tad_web_homework';
$pluginConfig['top_table'] = 'tad_web_homework';
$pluginConfig['common'] = true;
$pluginConfig['sql'] = ['tad_web_homework', 'tad_web_homework_content'];
$pluginConfig['setup'] = true;
$pluginConfig['add'] = true;
$pluginConfig['menu'] = true;
$pluginConfig['export'] = false;
$pluginConfig['tag'] = false;
$pluginConfig['top_score'] = 3;
$pluginConfig['assistant'] = true;
