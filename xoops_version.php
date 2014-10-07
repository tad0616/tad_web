<?php
$modversion = array();

//---模組基本資訊---//
$modversion['name'] = _MI_TCW_NAME;
$modversion['version'] = 1.00;
$modversion['description'] = _MI_TCW_DESC;
$modversion['author'] = _MI_TCW_AUTHOR;
$modversion['credits'] = _MI_TCW_CREDITS;
$modversion['help'] = 'page=help';
$modversion['license'] = 'GNU GPL 2.0';
$modversion['license_url'] = 'www.gnu.org/licenses/gpl-2.0.html/';
$modversion['image'] = "images/logo_{$xoopsConfig['language']}.png";
$modversion['dirname'] = basename(dirname(__FILE__));

//---模組狀態資訊---//
$modversion['release_date'] = '2014/01/27';
$modversion['module_website_url'] = 'http://tad0616.net/';
$modversion['module_website_name'] = _MI_TAD_WEB;
$modversion['module_status'] = 'rc4';
$modversion['author_website_url'] = 'http://tad0616.net/';
$modversion['author_website_name'] = _MI_TAD_WEB;
$modversion['min_php']=5.2;
$modversion['min_xoops']='2.5';

//---paypal資訊---//
$modversion ['paypal'] = array();
$modversion ['paypal']['business'] = 'tad0616@gmail.com';
$modversion ['paypal']['item_name'] = 'Donation : ' . _MI_TAD_WEB;
$modversion ['paypal']['amount'] = 0;
$modversion ['paypal']['currency_code'] = 'USD';

//---啟動後台管理界面選單---//
$modversion['system_menu'] = 1;

//---資料表架構---//
$modversion['sqlfile']['mysql'] = "sql/mysql.sql";
$modversion['tables'][] = "tad_web";
$modversion['tables'][] = "tad_web_cate";
$modversion['tables'][] = "tad_web_mems";
$modversion['tables'][] = "tad_web_link_mems";
$modversion['tables'][] = "tad_web_link";
$modversion['tables'][] = "tad_web_news";
$modversion['tables'][] = "tad_web_action";
$modversion['tables'][] = "tad_web_discuss";
$modversion['tables'][] = "tad_web_files_center";
$modversion['tables'][] = "tad_web_files";
$modversion['tables'][] = "tad_web_video";
$modversion['tables'][] = "tad_web_config";

//---管理介面設定---//
$modversion['hasAdmin'] = 1;
$modversion['adminindex'] = "admin/index.php";
$modversion['adminmenu'] = "admin/menu.php";

$modversion['onInstall'] = "include/onInstall.php";
$modversion['onUpdate'] = "include/onUpdate.php";
$modversion['onUninstall'] = "include/onUninstall.php";

//---使用者主選單設定---//
$modversion['hasMain'] = 1;

$i=0;
$modversion['sub'][$i]['name'] =_MI_TCW_ABOUTUS;
$modversion['sub'][$i]['url'] = "aboutus.php";
$i++;
$modversion['sub'][$i]['name'] =_MI_TCW_NEWS;
$modversion['sub'][$i]['url'] = "news.php";
$i++;
$modversion['sub'][$i]['name'] =_MI_TCW_ACTION;
$modversion['sub'][$i]['url'] = "action.php";
$i++;
$modversion['sub'][$i]['name'] =_MI_TCW_VIDEO;
$modversion['sub'][$i]['url'] = "video.php";
$i++;
$modversion['sub'][$i]['name'] =_MI_TCW_FILES;
$modversion['sub'][$i]['url'] = "files.php";
$i++;
$modversion['sub'][$i]['name'] =_MI_TCW_LINK;
$modversion['sub'][$i]['url'] = "link.php";
$i++;
$modversion['sub'][$i]['name'] =_MI_TCW_DISCUSS;
$modversion['sub'][$i]['url'] = "discuss.php";


//---樣板設定---//
$i=1;
$modversion['templates'][$i]['file'] = 'tad_web_adm_main.html';
$modversion['templates'][$i]['description'] = "tad_web_adm_main.html";

$i++;
$modversion['templates'][$i]['file'] = 'tad_web_index_tpl.html';
$modversion['templates'][$i]['description'] = 'tad_web_index_tpl.html';

$i++;
$modversion['templates'][$i]['file'] = 'tad_web_header.html';
$modversion['templates'][$i]['description'] = 'tad_web_header.html';


$i++;
$modversion['templates'][$i]['file'] = 'tad_web_aboutus_tpl.html';
$modversion['templates'][$i]['description'] = 'tad_web_aboutus_tpl.html';

$i++;
$modversion['templates'][$i]['file'] = 'tad_web_news_tpl.html';
$modversion['templates'][$i]['description'] = 'tad_web_news_tpl.html';
$i++;
$modversion['templates'][$i]['file'] = 'tad_web_common_news.html';
$modversion['templates'][$i]['description'] = 'tad_web_common_news.html';


$i++;
$modversion['templates'][$i]['file'] = 'tad_web_file_tpl.html';
$modversion['templates'][$i]['description'] = 'tad_web_file_tpl.html';
$i++;
$modversion['templates'][$i]['file'] = 'tad_web_common_files.html';
$modversion['templates'][$i]['description'] = 'tad_web_common_files.html';

$i++;
$modversion['templates'][$i]['file'] = 'tad_web_action_tpl.html';
$modversion['templates'][$i]['description'] = 'tad_web_action_tpl.html';
$i++;
$modversion['templates'][$i]['file'] = 'tad_web_common_action.html';
$modversion['templates'][$i]['description'] = 'tad_web_common_action.html';

$i++;
$modversion['templates'][$i]['file'] = 'tad_web_discuss_tpl.html';
$modversion['templates'][$i]['description'] = 'tad_web_discuss_tpl.html';
$i++;
$modversion['templates'][$i]['file'] = 'tad_web_common_discuss.html';
$modversion['templates'][$i]['description'] = 'tad_web_common_discuss.html';

$i++;
$modversion['templates'][$i]['file'] = 'tad_web_link_tpl.html';
$modversion['templates'][$i]['description'] = 'tad_web_link_tpl.html';
$i++;
$modversion['templates'][$i]['file'] = 'tad_web_common_link.html';
$modversion['templates'][$i]['description'] = 'tad_web_common_link.html';

$i++;
$modversion['templates'][$i]['file'] = 'tad_web_video_tpl.html';
$modversion['templates'][$i]['description'] = 'tad_web_video_tpl.html';
$i++;
$modversion['templates'][$i]['file'] = 'tad_web_common_video.html';
$modversion['templates'][$i]['description'] = 'tad_web_common_video.html';

$i++;
$modversion['templates'][$i]['file'] = 'tad_web_calendar_tpl.html';
$modversion['templates'][$i]['description'] = 'tad_web_calendar_tpl.html';

$i++;
$modversion['templates'][$i]['file'] = 'tad_web_common_homework.html';
$modversion['templates'][$i]['description'] = 'tad_web_common_homework.html';

$i++;
$modversion['templates'][$i]['file'] = 'tad_web_common_aboutus.html';
$modversion['templates'][$i]['description'] = 'tad_web_common_aboutus.html';

$i++;
$modversion['templates'][$i]['file'] = 'tad_web_adm.html';
$modversion['templates'][$i]['description'] = 'tad_web_adm.html';

$i++;
$modversion['templates'][$i]['file'] = 'tad_web_config.html';
$modversion['templates'][$i]['description'] = 'tad_web_config.html';

$i++;
$modversion['templates'][$i]['file'] = 'tad_web_discuss_login_tpl.html';
$modversion['templates'][$i]['description'] = 'tad_web_discuss_login_tpl.html';




//---區塊設定---//
$i=1;
$modversion['blocks'][$i]['file'] = "tad_web_menu.php";
$modversion['blocks'][$i]['name'] = _MI_TCW_BNAME4;
$modversion['blocks'][$i]['description'] = _MI_TCW_BDESC4;
$modversion['blocks'][$i]['show_func'] = "tad_web_menu";
$modversion['blocks'][$i]['template'] = "tad_web_menu.html";

$i++;
$modversion['blocks'][$i]['file'] = "tad_web_list.php";
$modversion['blocks'][$i]['name'] = _MI_TCW_BNAME1;
$modversion['blocks'][$i]['description'] = _MI_TCW_BDESC1;
$modversion['blocks'][$i]['show_func'] = "tad_web_list";
$modversion['blocks'][$i]['template'] = "tad_web_list.html";

$i++;
$modversion['blocks'][$i]['file'] = "tad_web_image.php";
$modversion['blocks'][$i]['name'] = _MI_TCW_BNAME3;
$modversion['blocks'][$i]['description'] = _MI_TCW_BDESC3;
$modversion['blocks'][$i]['show_func'] = "tad_web_image";
$modversion['blocks'][$i]['template'] = "tad_web_image.html";
/*
$i++;
$modversion['blocks'][$i]['file'] = "tad_web_discuss.php";
$modversion['blocks'][$i]['name'] = _MI_TCW_BNAME2;
$modversion['blocks'][$i]['description'] = _MI_TCW_BDESC2;
$modversion['blocks'][$i]['show_func'] = "tad_web_discuss";
$modversion['blocks'][$i]['template'] = "tad_web_discuss.html";
*/



//---偏好設定---//
$modversion['config'][0]['name']	= 'web_mode';
$modversion['config'][0]['title']	= '_MI_TCW_WEB_MODE';
$modversion['config'][0]['description']	= '_MI_TCW_WEB_MODE_DESC';
$modversion['config'][0]['formtype']	= 'select';
$modversion['config'][0]['valuetype']	= 'text';
$modversion['config'][0]['default'] = "class";
$modversion['config'][0]['options']	= array('_MI_TCW_WEB_MODE_OPT1' => 'class','_MI_TCW_WEB_MODE_OPT2' => 'person');



?>