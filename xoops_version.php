<?php
$modversion = [];
global $xoopsConfig;

//---模組基本資訊---//
$modversion['name'] = _MI_TCW_NAME;
// $modversion['version'] = 1.95;
$modversion['version'] = $_SESSION['xoops_version'] >= 20511 ? '2.0.0-Stable' : '2.0';
$modversion['description'] = _MI_TCW_DESC;
$modversion['author'] = _MI_TCW_AUTHOR;
$modversion['credits'] = _MI_TCW_CREDITS;
$modversion['help'] = 'page=help';
$modversion['license'] = 'GNU GPL 2.0';
$modversion['license_url'] = 'www.gnu.org/licenses/gpl-2.0.html/';
$modversion['image'] = "images/logo_{$xoopsConfig['language']}.png";
$modversion['dirname'] = basename(__DIR__);

//---模組狀態資訊---//
$modversion['release_date'] = '2024-11-18';
$modversion['module_website_url'] = 'https://tad0616.net/';
$modversion['module_website_name'] = _MI_TAD_WEB;
$modversion['module_status'] = 'release';
$modversion['author_website_url'] = 'https://tad0616.net/';
$modversion['author_website_name'] = _MI_TAD_WEB;
$modversion['min_php'] = 5.4;
$modversion['min_xoops'] = '2.5.10';

//---paypal資訊---//
$modversion['paypal'] = [
    'business' => 'tad0616@gmail.com',
    'item_name' => 'Donation : ' . _MI_TAD_WEB,
    'amount' => 0,
    'currency_code' => 'USD',
];

//---啟動後台管理界面選單---//
$modversion['system_menu'] = 1;

//---資料表架構---//
$modversion['sqlfile']['mysql'] = 'sql/mysql.sql';
$modversion['tables'] = [
    'tad_web', 'tad_web_cate', 'tad_web_files_center', 'tad_web_config',
    'tad_web_plugins', 'tad_web_roles', 'tad_web_blocks', 'tad_web_plugins_setup',
    'tad_web_power', 'tad_web_tags', 'tad_web_notice', 'tad_web_mail_log',
    'tad_web_cate_assistant', 'tad_web_assistant_post',
];

//---管理介面設定---//
$modversion['hasAdmin'] = 1;
$modversion['adminindex'] = 'admin/index.php';
$modversion['adminmenu'] = 'admin/menu.php';

$modversion['onInstall'] = 'include/onInstall.php';
$modversion['onUpdate'] = 'include/onUpdate.php';
$modversion['onUninstall'] = 'include/onUninstall.php';

//---使用者主選單設定---//
$modversion['hasMain'] = 1;

//---樣板設定---//
$modversion['templates'] = [
    ['file' => 'tad_web_adm_main.tpl', 'description' => 'tad_web_adm_main.tpl'],
    ['file' => 'tad_web_index.tpl', 'description' => 'tad_web_index.tpl'],
    ['file' => 'tad_web_adm_cate.tpl', 'description' => 'tad_web_adm_cate.tpl'],
    ['file' => 'tad_web_adm_setup.tpl', 'description' => 'tad_web_adm_setup.tpl'],
    ['file' => 'tad_web_adm_disk.tpl', 'description' => 'tad_web_adm_disk.tpl'],
    ['file' => 'tad_web_adm_schedule.tpl', 'description' => 'tad_web_adm_schedule.tpl'],
    ['file' => 'tad_web_adm_notice.tpl', 'description' => 'tad_web_adm_notice.tpl'],
    ['file' => 'tad_web_config.tpl', 'description' => 'tad_web_config.tpl'],
    ['file' => 'tad_web_cate.tpl', 'description' => 'tad_web_cate.tpl'],
    ['file' => 'tad_web_assistant.tpl', 'description' => 'tad_web_assistant.tpl'],
    ['file' => 'tad_web_header.tpl', 'description' => 'tad_web_header.tpl'],
    ['file' => 'tad_web_tpl.tpl', 'description' => 'tad_web_tpl.tpl'],
    ['file' => 'tad_web_block.tpl', 'description' => 'tad_web_block.tpl'],
    ['file' => 'tad_web_block_custom.tpl', 'description' => 'tad_web_block_custom.tpl'],
    ['file' => 'tad_web_unable.tpl', 'description' => 'tad_web_unable.tpl'],
    ['file' => 'tad_web_plugin_setup.tpl', 'description' => 'tad_web_plugin_setup.tpl'],
    ['file' => 'tad_web_search.tpl', 'description' => 'tad_web_search.tpl'],
    ['file' => 'tad_web_tag.tpl', 'description' => 'tad_web_tag.tpl'],
    ['file' => 'tad_web_top.tpl', 'description' => 'tad_web_top.tpl'],
];

//---區塊設定---//
$modversion['blocks'] = [
    1 => ['file' => 'tad_web_menu.php', 'name' => _MI_TCW_BNAME2, 'description' => _MI_TCW_BDESC2, 'show_func' => 'tad_web_menu', 'template' => 'tad_web_block_menu.tpl'],
    ['file' => 'tad_web_list.php', 'name' => _MI_TCW_BNAME1, 'description' => _MI_TCW_BDESC1, 'show_func' => 'tad_web_list', 'template' => 'tad_web_block_list.tpl'],
    ['file' => 'tad_web_image.php', 'name' => _MI_TCW_BNAME3, 'description' => _MI_TCW_BDESC3, 'show_func' => 'tad_web_image', 'template' => 'tad_web_block_image.tpl'],
    ['file' => 'tad_web_news.php', 'name' => _MI_TCW_BNAME4, 'description' => _MI_TCW_BDESC4, 'show_func' => 'tad_web_news', 'template' => 'tad_web_block_news.tpl'],
];

$modversion['config'] = [
    ['name' => 'module_title', 'title' => '_MI_TCW_WEB_MODE_TITLE', 'description' => '_MI_TCW_WEB_MODE_DESC', 'formtype' => 'textbox', 'valuetype' => 'text', 'default' => _MI_TCW_WEB_MODE_DEF],
    ['name' => 'schedule_template', 'title' => '_MI_TCW_WEB_SCHEDULE_TEMPLATE', 'description' => '_MI_TCW_WEB_SCHEDULE_TEMPLATE_DESC', 'formtype' => 'textarea', 'valuetype' => 'text', 'default' => _MI_TCW_WEB_SCHEDULE_TEMPLATE_DEF],
    ['name' => 'schedule_subjects', 'title' => '_MI_TCW_WEB_SCHEDULE_SUBJECTS', 'description' => '_MI_TCW_WEB_SCHEDULE_SUBJECTS_DESC', 'formtype' => 'textbox', 'valuetype' => 'text', 'default' => _MI_TCW_WEB_SCHEDULE_SUBJECTS_DEF],
    ['name' => 'aboutus_cols', 'title' => '_MI_TADWEB_ABOUTUS_MODE', 'description' => '_MI_TADWEB_ABOUTUS_MODE_DESC', 'formtype' => 'select_multi', 'valuetype' => 'array', 'default' => ['counter', 'web', 'schedule', 'homework'], 'options' => [_MI_TADWEB_ABOUTUS_MODE_KEY1 => 'counter', _MI_TADWEB_ABOUTUS_MODE_KEY2 => 'web', _MI_TADWEB_ABOUTUS_MODE_KEY3 => 'schedule', _MI_TADWEB_ABOUTUS_MODE_KEY4 => 'homework']],
    ['name' => 'cal_cols', 'title' => '_MI_TADWEB_CAL_COLS', 'description' => '_MI_TADWEB_CAL_COLS_DESC', 'formtype' => 'select_multi', 'valuetype' => 'array', 'default' => ['all', 'web', 'news', 'homework'], 'options' => [_MI_TADWEB_CAL_COLS_KEY1 => 'all', _MI_TADWEB_CAL_COLS_KEY2 => 'web', _MI_TADWEB_CAL_COLS_KEY3 => 'news', _MI_TADWEB_CAL_COLS_KEY4 => 'homework']],
    ['name' => 'user_space_quota', 'title' => '_MI_TADWEB_USER_SPACE_QUOTA', 'description' => '_MI_TADWEB_USER_SPACE_QUOTA_DESC', 'formtype' => 'textbox', 'valuetype' => 'int', 'default' => '500'],
    ['name' => 'list_web_order', 'title' => '_MI_TCW_LIST_WEB_TEXT', 'description' => '_MI_TCW_LIST_WEB_DESC', 'formtype' => 'select', 'valuetype' => 'text', 'default' => 'WebSort', 'options' => [_MI_TCW_LIST_WEB_OPT1 => 'WebSort', _MI_TCW_LIST_WEB_OPT2 => 'WebCounter', _MI_TCW_LIST_WEB_OPT3 => 'WebCounter desc', _MI_TCW_LIST_WEB_OPT4 => 'CreatDate', _MI_TCW_LIST_WEB_OPT5 => 'CreatDate desc']],
];
