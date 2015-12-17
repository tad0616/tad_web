<?php
$modversion = array();

//---模組基本資訊---//
$modversion['name']        = _MI_TCW_NAME;
$modversion['version']     = 1.65;
$modversion['description'] = _MI_TCW_DESC;
$modversion['author']      = _MI_TCW_AUTHOR;
$modversion['credits']     = _MI_TCW_CREDITS;
$modversion['help']        = 'page=help';
$modversion['license']     = 'GNU GPL 2.0';
$modversion['license_url'] = 'www.gnu.org/licenses/gpl-2.0.html/';
$modversion['image']       = "images/logo_{$xoopsConfig['language']}.png";
$modversion['dirname']     = basename(dirname(__FILE__));

//---模組狀態資訊---//
$modversion['release_date']        = '2015/12/17';
$modversion['module_website_url']  = 'http://tad0616.net/';
$modversion['module_website_name'] = _MI_TAD_WEB;
$modversion['module_status']       = 'release';
$modversion['author_website_url']  = 'http://tad0616.net/';
$modversion['author_website_name'] = _MI_TAD_WEB;
$modversion['min_php']             = 5.3;
$modversion['min_xoops']           = '2.5';

//---paypal資訊---//
$modversion['paypal']                  = array();
$modversion['paypal']['business']      = 'tad0616@gmail.com';
$modversion['paypal']['item_name']     = 'Donation : ' . _MI_TAD_WEB;
$modversion['paypal']['amount']        = 0;
$modversion['paypal']['currency_code'] = 'USD';

//---啟動後台管理界面選單---//
$modversion['system_menu'] = 1;

//---資料表架構---//
$modversion['sqlfile']['mysql'] = "sql/mysql.sql";
$modversion['tables'][]         = "tad_web";
$modversion['tables'][]         = "tad_web_cate";
$modversion['tables'][]         = "tad_web_files_center";
$modversion['tables'][]         = "tad_web_config";
$modversion['tables'][]         = "tad_web_plugins";
$modversion['tables'][]         = "tad_web_roles";
$modversion['tables'][]         = "tad_web_blocks";
$modversion['tables'][]         = "tad_web_plugins_setup";

//---管理介面設定---//
$modversion['hasAdmin']   = 1;
$modversion['adminindex'] = "admin/index.php";
$modversion['adminmenu']  = "admin/menu.php";

$modversion['onInstall']   = "include/onInstall.php";
$modversion['onUpdate']    = "include/onUpdate.php";
$modversion['onUninstall'] = "include/onUninstall.php";

//---使用者主選單設定---//
$modversion['hasMain'] = 1;

// $i                             = 0;
// $modversion['sub'][$i]['name'] = _MI_TCW_ABOUTUS;
// $modversion['sub'][$i]['url']  = "aboutus.php";
// $i++;
// $modversion['sub'][$i]['name'] = _MI_TCW_NEWS;
// $modversion['sub'][$i]['url']  = "news.php";

//---樣板設定---//
$i                                          = 1;
$modversion['templates'][$i]['file']        = 'tad_web_adm_main.html';
$modversion['templates'][$i]['description'] = "tad_web_adm_main.html";

$i++;
$modversion['templates'][$i]['file']        = 'tad_web_adm_main_b3.html';
$modversion['templates'][$i]['description'] = "tad_web_adm_main_b3.html";

$i++;
$modversion['templates'][$i]['file']        = 'tad_web_index.html';
$modversion['templates'][$i]['description'] = 'tad_web_index.html';

$i++;
$modversion['templates'][$i]['file']        = 'tad_web_index_b3.html';
$modversion['templates'][$i]['description'] = 'tad_web_index_b3.html';

$i++;
$modversion['templates'][$i]['file']        = 'tad_web_adm_cate.html';
$modversion['templates'][$i]['description'] = "tad_web_adm_cate.html";

$i++;
$modversion['templates'][$i]['file']        = 'tad_web_adm_cate_b3.html';
$modversion['templates'][$i]['description'] = "tad_web_adm_cate_b3.html";

$i++;
$modversion['templates'][$i]['file']        = 'tad_web_adm_setup.html';
$modversion['templates'][$i]['description'] = "tad_web_adm_setup.html";

$i++;
$modversion['templates'][$i]['file']        = 'tad_web_adm_setup_b3.html';
$modversion['templates'][$i]['description'] = "tad_web_adm_setup_b3.html";

$i++;
$modversion['templates'][$i]['file']        = 'tad_web_adm_disk.html';
$modversion['templates'][$i]['description'] = "tad_web_adm_disk.html";

$i++;
$modversion['templates'][$i]['file']        = 'tad_web_adm_disk_b3.html';
$modversion['templates'][$i]['description'] = "tad_web_adm_disk_b3.html";

$i++;
$modversion['templates'][$i]['file']        = 'tad_web_adm_schedule.html';
$modversion['templates'][$i]['description'] = "tad_web_adm_schedule.html";

$i++;
$modversion['templates'][$i]['file']        = 'tad_web_adm_schedule_b3.html';
$modversion['templates'][$i]['description'] = "tad_web_adm_schedule_b3.html";

//tad_web_config.html 不需要

$i++;
$modversion['templates'][$i]['file']        = 'tad_web_config_b3.html';
$modversion['templates'][$i]['description'] = 'tad_web_config_b3.html';

//tad_web_cate.html 不需要
$i++;
$modversion['templates'][$i]['file']        = 'tad_web_cate_b3.html';
$modversion['templates'][$i]['description'] = 'tad_web_cate_b3.html';

//tad_web_header.html 不需要
$i++;
$modversion['templates'][$i]['file']        = 'tad_web_header_b3.html';
$modversion['templates'][$i]['description'] = 'tad_web_header_b3.html';

$i++;
$modversion['templates'][$i]['file']        = 'tad_web_tpl.html';
$modversion['templates'][$i]['description'] = 'tad_web_tpl.html';

$i++;
$modversion['templates'][$i]['file']        = 'tad_web_tpl_b3.html';
$modversion['templates'][$i]['description'] = 'tad_web_tpl_b3.html';
//tad_web_block.html 不需要
$i++;
$modversion['templates'][$i]['file']        = 'tad_web_block_b3.html';
$modversion['templates'][$i]['description'] = 'tad_web_block_b3.html';

$i++;
$modversion['templates'][$i]['file']        = 'tad_web_block_custom_b3.html';
$modversion['templates'][$i]['description'] = 'tad_web_block_custom_b3.html';

$i++;
$modversion['templates'][$i]['file']        = 'tad_web_unable_b3.html';
$modversion['templates'][$i]['description'] = 'tad_web_unable_b3.html';

$i++;
$modversion['templates'][$i]['file']        = 'tad_web_plugin_setup_b3.html';
$modversion['templates'][$i]['description'] = 'tad_web_plugin_setup_b3.html';

//---區塊設定---//
$i                                       = 1;
$modversion['blocks'][$i]['file']        = "tad_web_menu.php";
$modversion['blocks'][$i]['name']        = _MI_TCW_BNAME4;
$modversion['blocks'][$i]['description'] = _MI_TCW_BDESC4;
$modversion['blocks'][$i]['show_func']   = "tad_web_menu";
$modversion['blocks'][$i]['template']    = "tad_web_block_menu.html";

$i++;
$modversion['blocks'][$i]['file']        = "tad_web_list.php";
$modversion['blocks'][$i]['name']        = _MI_TCW_BNAME1;
$modversion['blocks'][$i]['description'] = _MI_TCW_BDESC1;
$modversion['blocks'][$i]['show_func']   = "tad_web_list";
$modversion['blocks'][$i]['template']    = "tad_web_block_list.html";

$i++;
$modversion['blocks'][$i]['file']        = "tad_web_image.php";
$modversion['blocks'][$i]['name']        = _MI_TCW_BNAME3;
$modversion['blocks'][$i]['description'] = _MI_TCW_BDESC3;
$modversion['blocks'][$i]['show_func']   = "tad_web_image";
$modversion['blocks'][$i]['template']    = "tad_web_block_image.html";

/*
$i++;
$modversion['blocks'][$i]['file'] = "tad_web_discuss.php";
$modversion['blocks'][$i]['name'] = _MI_TCW_BNAME2;
$modversion['blocks'][$i]['description'] = _MI_TCW_BDESC2;
$modversion['blocks'][$i]['show_func'] = "tad_web_discuss";
$modversion['blocks'][$i]['template'] = "tad_web_block_discuss.html";
 */

//---偏好設定---//
$i                                       = 0;
$modversion['config'][$i]['name']        = 'module_title';
$modversion['config'][$i]['title']       = '_MI_TCW_WEB_MODE_TITLE';
$modversion['config'][$i]['description'] = '_MI_TCW_WEB_MODE_DESC';
$modversion['config'][$i]['formtype']    = 'textbox';
$modversion['config'][$i]['valuetype']   = 'text';
$modversion['config'][$i]['default']     = _MI_TCW_WEB_MODE_DEF;

$i++;
$modversion['config'][$i]['name']        = 'schedule_template';
$modversion['config'][$i]['title']       = '_MI_TCW_WEB_SCHEDULE_TEMPLATE';
$modversion['config'][$i]['description'] = '_MI_TCW_WEB_SCHEDULE_TEMPLATE_DESC';
$modversion['config'][$i]['formtype']    = 'textarea';
$modversion['config'][$i]['valuetype']   = 'text';
$modversion['config'][$i]['default']     = _MI_TCW_WEB_SCHEDULE_TEMPLATE_DEF;

$i++;
$modversion['config'][$i]['name']        = 'schedule_subjects';
$modversion['config'][$i]['title']       = '_MI_TCW_WEB_SCHEDULE_SUBJECTS';
$modversion['config'][$i]['description'] = '_MI_TCW_WEB_SCHEDULE_SUBJECTS_DESC';
$modversion['config'][$i]['formtype']    = 'textbox';
$modversion['config'][$i]['valuetype']   = 'text';
$modversion['config'][$i]['default']     = _MI_TCW_WEB_SCHEDULE_SUBJECTS_DEF;

$i++;
$modversion['config'][$i]['name']        = 'aboutus_cols';
$modversion['config'][$i]['title']       = '_MI_TADWEB_ABOUTUS_MODE';
$modversion['config'][$i]['description'] = '_MI_TADWEB_ABOUTUS_MODE_DESC';
$modversion['config'][$i]['formtype']    = 'select_multi';
$modversion['config'][$i]['valuetype']   = 'array';
$modversion['config'][$i]['default']     = array('counter', 'web', 'schedule', 'homework');
$modversion['config'][$i]['options']     = array(_MI_TADWEB_ABOUTUS_MODE_KEY1 => 'counter', _MI_TADWEB_ABOUTUS_MODE_KEY2 => 'web', _MI_TADWEB_ABOUTUS_MODE_KEY3 => 'schedule', _MI_TADWEB_ABOUTUS_MODE_KEY4 => 'homework');

$i++;
$modversion['config'][$i]['name']        = 'cal_cols';
$modversion['config'][$i]['title']       = '_MI_TADWEB_CAL_COLS';
$modversion['config'][$i]['description'] = '_MI_TADWEB_CAL_COLS_DESC';
$modversion['config'][$i]['formtype']    = 'select_multi';
$modversion['config'][$i]['valuetype']   = 'array';
$modversion['config'][$i]['default']     = array('all', 'web', 'news', 'homework');
$modversion['config'][$i]['options']     = array(_MI_TADWEB_CAL_COLS_KEY1 => 'all', _MI_TADWEB_CAL_COLS_KEY2 => 'web', _MI_TADWEB_CAL_COLS_KEY3 => 'news', _MI_TADWEB_CAL_COLS_KEY4 => 'homework');

$i++;
$modversion['config'][$i]['name']        = 'user_space_quota';
$modversion['config'][$i]['title']       = '_MI_TADWEB_USER_SPACE_QUOTA';
$modversion['config'][$i]['description'] = '_MI_TADWEB_USER_SPACE_QUOTA_DESC';
$modversion['config'][$i]['formtype']    = 'textbox';
$modversion['config'][$i]['valuetype']   = 'int';
$modversion['config'][$i]['default']     = '500';

$i++;
$modversion['config'][$i]['name']        = 'list_web_order';
$modversion['config'][$i]['title']       = '_MI_TCW_LIST_WEB_TEXT';
$modversion['config'][$i]['description'] = '_MI_TCW_LIST_WEB_DESC';
$modversion['config'][$i]['formtype']    = 'select';
$modversion['config'][$i]['valuetype']   = 'text';
$modversion['config'][$i]['default']     = 'WebSort';
$modversion['config'][$i]['options']     = array(_MI_TCW_LIST_WEB_OPT1 => 'WebSort', _MI_TCW_LIST_WEB_OPT2 => 'WebCounter', _MI_TCW_LIST_WEB_OPT3 => 'WebCounter desc', _MI_TCW_LIST_WEB_OPT4 => 'CreatDate', _MI_TCW_LIST_WEB_OPT5 => 'CreatDate desc');
