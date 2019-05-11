<?php
xoops_loadLanguage('admin_common', 'tadtools');

//main.php
define('_MA_TCW_TEAMID', 'Number');
define('_MA_TCW_TEAMNAME', 'Site name');
define('_MA_TCW_TEAM', 'Site');
define('_MA_TCW_TEAMSORT', 'Sorting');
define('_MA_TCW_TEAMENABLE', 'State');
define('_MA_TCW_TEAMCOUNTER', 'Popular');
define('_MA_TCW_MEM_AMOUNT', 'Number of members');
define('_MA_TCW_TEAMLEADER', 'Owner');
define('_MA_TCW_TEAMTITLE', 'Class name');
define('_MA_TCW_MAIN_TITLE', 'Personal Web Manager');
define('_MA_TCW_GROUP_NAME', 'XOOPS personal page groups');
define('_MA_TCW_GROUP_DESC', 'Do not change the group name, otherwise the module will not function correctly.');
define('_MA_TCW_CREATE_BY_USER', 'Batch creat user website');
define('_MA_TCW_ALL_USER_NO', 'Yet have its own website users');
define('_MA_TCW_ALL_USER_YES', 'Users have their own website');
define('_MA_TCW_SOMEBODY_WEB', '%s dedicated website');
define('_MA_TCW_WILL_DEL', 'It will delete the following information:');
define('_MA_TCW_DEL_MEM', 'Member information');
define('_MA_TCW_DEL_LINK', 'Link information');
define('_MA_TCW_DEL_NEWS', 'Update');
define('_MA_TCW_DEL_ACTION', 'Activities silhouette Materials');
define('_MA_TCW_DEL_FILES', 'File Download');
define('_MA_TCW_DEL_VIDEOS', 'Online video data');
define('_MA_TCW_DEL_DISCUSS', 'Message Discussion Materials');
define('_MA_TCW_DELETE', 'Yes! I want to delete it! ');
define('_MA_TCW_UPLOAD_OWNER_PIC', 'Upload page image file');
define('_MA_TCW_ORDER_BY_TEAMTITLE', 'Sort by team name');

//save_sort.php save.php
define('_MA_TCW_UPDATE_FAIL', 'Update Failed!');
define('_MA_TCW_SAVE_SORT_OK', 'The sort is complete!');
define('_MA_TCW_NEED_TAD_WEB_THEME', "<ul><li style='line-height:2;'>This module need <a href='http://120.115.2.90/modules/tad_modules/index.php?module_sn=77' target='_blank'>for_tad_web_theme</a>。</li><li style='line-height:2;'>Just need to download it and unzip it to themes folder.</li><li style='line-height:2;'>You can install from <a href='" . XOOPS_URL . "/modules/tad_adm/admin/main.php'>Tad Adm module</a></li></ul>");

define('_MA_TCW_NEED_IMAGECREATETURECOLOR', 'Need imagecreatetruecolor()');
define('_MA_TCW_NEED_IMAGECREATETURECOLOR_CONTENT', "
Please install imagecreatetruecolor for PHP:<a href='http://120.115.2.90/modules/tad_book3/page.php?tbdsn=216' target='_blank'>http://120.115.2.90/modules/tad_book3/page.php?tbdsn=216</a>");
define('_MA_TCW_NEED_THEME', 'Missing for_tad_web theme');
define('_MA_TCW_NEED_THEME_CONTENT', "Download and unzip <a href='http://120.115.2.90/modules/tad_modules/index.php?module_sn=77' target='_blank'>for_tad_web_theme</a> to themes folder. You can also install it directly from the <a href='" . XOOPS_URL . "/modules/tad_adm/admin/main.php'>Tad_adm</a>");
define('_MA_TCW_NEED_TADTOOLS', 'TadTools version must > 2.7.4');
define('_MA_TCW_NEED_TADTOOLS_CONTENT', "Please update tadtools form <a href='" . XOOPS_URL . "/modules/tad_adm/admin/main.php'>Tad_adm</a>.");

define('_MA_TADWEB_CATEID', 'Category ID');
define('_MA_TADWEB_WEBID', 'Web ID');
define('_MA_TADWEB_CATENAME', 'Category Name');
define('_MA_TADWEB_COLNAME', 'Column Value');
define('_MA_TADWEB_COLSN', 'Column Value');
define('_MA_TADWEB_CATESORT', 'Category Sort');
define('_MA_TADWEB_CATEENABLE', 'Category Status');
define('_MA_TADWEB_CATECOUNTER', 'Category Counter');
define('_MA_TCW_SELECT_CATE', 'All Category');
define('_MA_TCW_DISK_TOTAL_SPACE_STATUS', 'Disk total space');
define('_MA_TCW_DISK_TOTAL_SPACE', 'Disk total space');
define('_MA_TCW_DISK_PATH', 'WebSite Dir');
define('_MA_TCW_DISK_SPACE_QUOTA', 'Space quota:');
define('_MA_TCW_DISK_SPACE_TOTAL', ', Server used space:');
define('_MA_TCW_DISK_AVAILABLE_SPACE', ', Available space');

define('_MA_TCW_WEB_SCHEDULE_TEMPLATE', 'Edit schedule table template');
define('_MA_TCW_WEB_SCHEDULE_TEMPLATE_DESC', '
  <p> {weeks-section} will allow the teacher entered into the fields. </ p>
  <p> According to {weeks-section} rule, self-add tags, you can remove the unwanted (eg Section VIII). </ p>
  <p> Sunday ~ Saturday was 0-6. As the first section of Sunday {0-1}, Section V of Saturday is {6-5}. </ p>
  <p> Don\'t edit {weeks-section} tags , the other can be changed. </ p>
  <p> You can modify ' . XOOPS_ROOT_PATH . ' /modules/tad_web/plugins/schedule/schedule.css to change the color. </ p>');

define('_MA_TCW_WEB_SCHEDULE_SUBJECT', 'Set subjects (for teachers optional)');
define('_MA_TCW_WEB_SCHEDULE_SUBJECT_DESC', 'E.g:Writing;Reading;Math;Science;Art;Music');

define('_MA_TCW_CO_ADMIN', 'Co-administrator');

define('_MA_TADWEB_PLUGIN_TITLE', 'Plugin Title');
define('_MA_TADWEB_PLUGIN_TOTAL', 'Total');
define('_MA_TCW_LAST_ACCESSED', 'Last access');

define('_MA_TCW_WEB_NOTICE', 'Notice');
define('_MA_TADWEB_NOTICEWHO_DEF', 'Web Master');
define('_MA_TADWEB_NOTICEID', 'Notice ID');
define('_MA_TADWEB_NOTICETITLE', 'Notice TITLE');
define('_MA_TADWEB_NOTICECONTENT', 'Notice Content');
define('_MA_TADWEB_NOTICEWEB', 'Notice Webs');
define('_MA_TADWEB_NOTICEWHO', 'Notice whom');
define('_MA_TADWEB_NOTICEWHO_DEF0', 'Web Master');
define('_MA_TADWEB_NOTICEWHO_DEF1', 'Web Members');
define('_MA_TADWEB_NOTICEWHO_DEF2', 'Member Parents');
define('_MA_TADWEB_NOTICEDATE', 'Notice Date');
