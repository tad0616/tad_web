<?php
$modversion = [];

//---模組基本資訊---//
$modversion['name'] = _MI_TCW_NAME;
$modversion['version'] = 1.78;
$modversion['description'] = _MI_TCW_DESC;
$modversion['author'] = _MI_TCW_AUTHOR;
$modversion['credits'] = _MI_TCW_CREDITS;
$modversion['help'] = 'page=help';
$modversion['license'] = 'GNU GPL 2.0';
$modversion['license_url'] = 'www.gnu.org/licenses/gpl-2.0.html/';
$modversion['image'] = "images/logo_{$xoopsConfig['language']}.png";
$modversion['dirname'] = basename(__DIR__);

//---模組狀態資訊---//
$modversion['release_date'] = '2019-05-10';
$modversion['module_website_url'] = 'https://tad0616.net/';
$modversion['module_website_name'] = _MI_TAD_WEB;
$modversion['module_status'] = 'release';
$modversion['author_website_url'] = 'https://tad0616.net/';
$modversion['author_website_name'] = _MI_TAD_WEB;
$modversion['min_php'] = 5.4;
$modversion['min_xoops'] = '2.5';

//---paypal資訊---//
$modversion['paypal'] = [];
$modversion['paypal']['business'] = 'tad0616@gmail.com';
$modversion['paypal']['item_name'] = 'Donation : ' . _MI_TAD_WEB;
$modversion['paypal']['amount'] = 0;
$modversion['paypal']['currency_code'] = 'USD';

//---啟動後台管理界面選單---//
$modversion['system_menu'] = 1;

//---資料表架構---//
$modversion['sqlfile']['mysql'] = 'sql/mysql.sql';
$modversion['tables'][] = 'tad_web';
$modversion['tables'][] = 'tad_web_cate';
$modversion['tables'][] = 'tad_web_files_center';
$modversion['tables'][] = 'tad_web_config';
$modversion['tables'][] = 'tad_web_plugins';
$modversion['tables'][] = 'tad_web_roles';
$modversion['tables'][] = 'tad_web_blocks';
$modversion['tables'][] = 'tad_web_plugins_setup';
$modversion['tables'][] = 'tad_web_power';
$modversion['tables'][] = 'tad_web_tags';
$modversion['tables'][] = 'tad_web_notice';
$modversion['tables'][] = 'tad_web_mail_log';
$modversion['tables'][] = 'tad_web_cate_assistant';
$modversion['tables'][] = 'tad_web_assistant_post';

//---管理介面設定---//
$modversion['hasAdmin'] = 1;
$modversion['adminindex'] = 'admin/index.php';
$modversion['adminmenu'] = 'admin/menu.php';

$modversion['onInstall'] = 'include/onInstall.php';
$modversion['onUpdate'] = 'include/onUpdate.php';
$modversion['onUninstall'] = 'include/onUninstall.php';

//---使用者主選單設定---//
$modversion['hasMain'] = 1;

// $i                             = 0;
// $modversion['sub'][$i]['name'] = _MI_TCW_ABOUTUS;
// $modversion['sub'][$i]['url']  = "aboutus.php";
// $i++;
// $modversion['sub'][$i]['name'] = _MI_TCW_NEWS;
// $modversion['sub'][$i]['url']  = "news.php";

//---樣板設定---//
$i = 1;
$modversion['templates'][$i]['file'] = 'tad_web_adm_main.tpl';
$modversion['templates'][$i]['description'] = 'tad_web_adm_main.tpl';

$i++;
$modversion['templates'][$i]['file'] = 'tad_web_index.tpl';
$modversion['templates'][$i]['description'] = 'tad_web_index.tpl';

$i++;
$modversion['templates'][$i]['file'] = 'tad_web_adm_cate.tpl';
$modversion['templates'][$i]['description'] = 'tad_web_adm_cate.tpl';

$i++;
$modversion['templates'][$i]['file'] = 'tad_web_adm_setup.tpl';
$modversion['templates'][$i]['description'] = 'tad_web_adm_setup.tpl';

$i++;
$modversion['templates'][$i]['file'] = 'tad_web_adm_disk.tpl';
$modversion['templates'][$i]['description'] = 'tad_web_adm_disk.tpl';

$i++;
$modversion['templates'][$i]['file'] = 'tad_web_adm_schedule.tpl';
$modversion['templates'][$i]['description'] = 'tad_web_adm_schedule.tpl';

$i++;
$modversion['templates'][$i]['file'] = 'tad_web_adm_notice.tpl';
$modversion['templates'][$i]['description'] = 'tad_web_adm_notice.tpl';

//tad_web_config.tpl 不需要

$i++;
$modversion['templates'][$i]['file'] = 'tad_web_config.tpl';
$modversion['templates'][$i]['description'] = 'tad_web_config.tpl';

//tad_web_cate.tpl 不需要
$i++;
$modversion['templates'][$i]['file'] = 'tad_web_cate.tpl';
$modversion['templates'][$i]['description'] = 'tad_web_cate.tpl';

//tad_web_assistant.tpl 不需要
$i++;
$modversion['templates'][$i]['file'] = 'tad_web_assistant.tpl';
$modversion['templates'][$i]['description'] = 'tad_web_assistant.tpl';

//tad_web_header.tpl 不需要
$i++;
$modversion['templates'][$i]['file'] = 'tad_web_header.tpl';
$modversion['templates'][$i]['description'] = 'tad_web_header.tpl';

$i++;
$modversion['templates'][$i]['file'] = 'tad_web_tpl.tpl';
$modversion['templates'][$i]['description'] = 'tad_web_tpl.tpl';
//tad_web_block.tpl 不需要
$i++;
$modversion['templates'][$i]['file'] = 'tad_web_block.tpl';
$modversion['templates'][$i]['description'] = 'tad_web_block.tpl';

$i++;
$modversion['templates'][$i]['file'] = 'tad_web_block_custom.tpl';
$modversion['templates'][$i]['description'] = 'tad_web_block_custom.tpl';

$i++;
$modversion['templates'][$i]['file'] = 'tad_web_unable.tpl';
$modversion['templates'][$i]['description'] = 'tad_web_unable.tpl';

$i++;
$modversion['templates'][$i]['file'] = 'tad_web_plugin_setup.tpl';
$modversion['templates'][$i]['description'] = 'tad_web_plugin_setup.tpl';

$i++;
$modversion['templates'][$i]['file'] = 'tad_web_search.tpl';
$modversion['templates'][$i]['description'] = 'tad_web_search.tpl';

$i++;
$modversion['templates'][$i]['file'] = 'tad_web_tag.tpl';
$modversion['templates'][$i]['description'] = 'tad_web_tag.tpl';

$i++;
$modversion['templates'][$i]['file'] = 'tad_web_top.tpl';
$modversion['templates'][$i]['description'] = 'tad_web_top.tpl';

//---區塊設定---//
$i = 1;
$modversion['blocks'][$i]['file'] = 'tad_web_menu.php';
$modversion['blocks'][$i]['name'] = _MI_TCW_BNAME4;
$modversion['blocks'][$i]['description'] = _MI_TCW_BDESC4;
$modversion['blocks'][$i]['show_func'] = 'tad_web_menu';
$modversion['blocks'][$i]['template'] = 'tad_web_block_menu.tpl';

$i++;
$modversion['blocks'][$i]['file'] = 'tad_web_list.php';
$modversion['blocks'][$i]['name'] = _MI_TCW_BNAME1;
$modversion['blocks'][$i]['description'] = _MI_TCW_BDESC1;
$modversion['blocks'][$i]['show_func'] = 'tad_web_list';
$modversion['blocks'][$i]['template'] = 'tad_web_block_list.tpl';

$i++;
$modversion['blocks'][$i]['file'] = 'tad_web_image.php';
$modversion['blocks'][$i]['name'] = _MI_TCW_BNAME3;
$modversion['blocks'][$i]['description'] = _MI_TCW_BDESC3;
$modversion['blocks'][$i]['show_func'] = 'tad_web_image';
$modversion['blocks'][$i]['template'] = 'tad_web_block_image.tpl';

$i++;
$modversion['blocks'][$i]['file'] = 'tad_web_news.php';
$modversion['blocks'][$i]['name'] = _MI_TCW_BNAME4;
$modversion['blocks'][$i]['description'] = _MI_TCW_BDESC4;
$modversion['blocks'][$i]['show_func'] = 'tad_web_news';
$modversion['blocks'][$i]['template'] = 'tad_web_block_news.tpl';

//---偏好設定---//
$i = 0;
$modversion['config'][$i]['name'] = 'module_title';
$modversion['config'][$i]['title'] = '_MI_TCW_WEB_MODE_TITLE';
$modversion['config'][$i]['description'] = '_MI_TCW_WEB_MODE_DESC';
$modversion['config'][$i]['formtype'] = 'textbox';
$modversion['config'][$i]['valuetype'] = 'text';
$modversion['config'][$i]['default'] = _MI_TCW_WEB_MODE_DEF;

$i++;
$modversion['config'][$i]['name'] = 'schedule_template';
$modversion['config'][$i]['title'] = '_MI_TCW_WEB_SCHEDULE_TEMPLATE';
$modversion['config'][$i]['description'] = '_MI_TCW_WEB_SCHEDULE_TEMPLATE_DESC';
$modversion['config'][$i]['formtype'] = 'textarea';
$modversion['config'][$i]['valuetype'] = 'text';
$modversion['config'][$i]['default'] = _MI_TCW_WEB_SCHEDULE_TEMPLATE_DEF;

$i++;
$modversion['config'][$i]['name'] = 'schedule_subjects';
$modversion['config'][$i]['title'] = '_MI_TCW_WEB_SCHEDULE_SUBJECTS';
$modversion['config'][$i]['description'] = '_MI_TCW_WEB_SCHEDULE_SUBJECTS_DESC';
$modversion['config'][$i]['formtype'] = 'textbox';
$modversion['config'][$i]['valuetype'] = 'text';
$modversion['config'][$i]['default'] = _MI_TCW_WEB_SCHEDULE_SUBJECTS_DEF;

$i++;
$modversion['config'][$i]['name'] = 'aboutus_cols';
$modversion['config'][$i]['title'] = '_MI_TADWEB_ABOUTUS_MODE';
$modversion['config'][$i]['description'] = '_MI_TADWEB_ABOUTUS_MODE_DESC';
$modversion['config'][$i]['formtype'] = 'select_multi';
$modversion['config'][$i]['valuetype'] = 'array';
$modversion['config'][$i]['default'] = ['counter', 'web', 'schedule', 'homework'];
$modversion['config'][$i]['options'] = [_MI_TADWEB_ABOUTUS_MODE_KEY1 => 'counter', _MI_TADWEB_ABOUTUS_MODE_KEY2 => 'web', _MI_TADWEB_ABOUTUS_MODE_KEY3 => 'schedule', _MI_TADWEB_ABOUTUS_MODE_KEY4 => 'homework'];

$i++;
$modversion['config'][$i]['name'] = 'cal_cols';
$modversion['config'][$i]['title'] = '_MI_TADWEB_CAL_COLS';
$modversion['config'][$i]['description'] = '_MI_TADWEB_CAL_COLS_DESC';
$modversion['config'][$i]['formtype'] = 'select_multi';
$modversion['config'][$i]['valuetype'] = 'array';
$modversion['config'][$i]['default'] = ['all', 'web', 'news', 'homework'];
$modversion['config'][$i]['options'] = [_MI_TADWEB_CAL_COLS_KEY1 => 'all', _MI_TADWEB_CAL_COLS_KEY2 => 'web', _MI_TADWEB_CAL_COLS_KEY3 => 'news', _MI_TADWEB_CAL_COLS_KEY4 => 'homework'];

$i++;
$modversion['config'][$i]['name'] = 'user_space_quota';
$modversion['config'][$i]['title'] = '_MI_TADWEB_USER_SPACE_QUOTA';
$modversion['config'][$i]['description'] = '_MI_TADWEB_USER_SPACE_QUOTA_DESC';
$modversion['config'][$i]['formtype'] = 'textbox';
$modversion['config'][$i]['valuetype'] = 'int';
$modversion['config'][$i]['default'] = '500';

$i++;
$modversion['config'][$i]['name'] = 'list_web_order';
$modversion['config'][$i]['title'] = '_MI_TCW_LIST_WEB_TEXT';
$modversion['config'][$i]['description'] = '_MI_TCW_LIST_WEB_DESC';
$modversion['config'][$i]['formtype'] = 'select';
$modversion['config'][$i]['valuetype'] = 'text';
$modversion['config'][$i]['default'] = 'WebSort';
$modversion['config'][$i]['options'] = [_MI_TCW_LIST_WEB_OPT1 => 'WebSort', _MI_TCW_LIST_WEB_OPT2 => 'WebCounter', _MI_TCW_LIST_WEB_OPT3 => 'WebCounter desc', _MI_TCW_LIST_WEB_OPT4 => 'CreatDate', _MI_TCW_LIST_WEB_OPT5 => 'CreatDate desc'];
