<?php
global $xoopsConfig;
require_once XOOPS_ROOT_PATH . "/modules/tad_web/plugins/account/langs/{$xoopsConfig['language']}.php";
$pluginConfig['name'] = _MD_TCW_ACCOUNT;
$pluginConfig['short'] = _MD_TCW_ACCOUNT_SHORT;
$pluginConfig['icon'] = 'fa-calculator';
$pluginConfig['limit'] = '';
$pluginConfig['cate'] = true;
$pluginConfig['cate_table'] = 'tad_web_account';
$pluginConfig['top_table'] = 'tad_web_account';
$pluginConfig['common'] = false;
$pluginConfig['sql'] = ['tad_web_account'];
$pluginConfig['setup'] = true;
$pluginConfig['add'] = true;
$pluginConfig['menu'] = true;
$pluginConfig['export'] = false;
$pluginConfig['tag'] = false;
$pluginConfig['top_score'] = 2;
$pluginConfig['assistant'] = true;
