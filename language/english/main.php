<?php
//需加入模組語系
define('_TAD_NEED_TADTOOLS', "This module needs TadTools module. You can download TadTools from <a href='http://www.tad0616.net/modules/tad_uploader/index.php?of_cat_sn=50' target='_blank'>Tad's web</a>.");

define('_MD_TCW_HOME', 'List');
define("_MD_TCW_CLASS_HOME", "Home");
define('_MD_TCW_ADMIN', 'Management');
define("_MD_TCW_ADMIN_MEM", "Member management");
define('_MD_TCW_CLEAR', 'Clear');
define('_MD_TCW_TOTAL', 'Total');
define('_MD_TCW_PEOPLE', 'People');
define("_MD_TCW_TOOLS", "Setup");
define("_MD_TCW_PLUGIN_TOOLS", "Plugins");
define("_MD_TCW_HEAD_TOOLS", "Header");
define("_MD_TCW_LOGO_TOOLS", "Logo");
define("_MD_TCW_BG_TOOLS", "Background");
define("_MD_TCW_COLOR_TOOLS", "Color");
define("_MD_TCW_BLOCK_TOOLS", "Blocks");
define("_MD_TCW_DATA_NOT_EXIST", "This information does not exis.");

//header.php
define('_MD_TCW_ALL_CLASS', 'Class List');
define('_MD_TCW_MY_CLASS', 'Class members');
define('_MD_TCW_ALL_WEB', 'Pages list');
define('_MD_TCW_ALL_WEB_TITLE', 'Official Name');
define('_MD_TCW_ALL_WEB_NAME', 'Page Name');
define('_MD_TCW_ALL_WEB_COUNTER', 'Popular');
define('_MD_TCW_ALL_WEB_OWNER', 'Owner');

// Common
define('_MD_TCW_TEAMID', 'Belongs to the page');
define('_MD_TCW_NOT_OWNER', 'Non-page owner, you can not use this function.');
define('_MD_TCW_AUTO_TO_HOME', 'Automatically go to the page to which you belong, to use the new features.');
define('_MD_TCW_NEED_LOGIN', 'Login to view the full content');
define('_MD_TCW_EMPTY', 'No data');
define('_MD_TCW_MORE', 'More');
define('_MD_TADWEB_SELECT_TO_DEL', 'Edit files: Select files to be deleted:');
define('_MD_TCW_INFO', '%s to %s release, viewed by %s people');
define('_MD_TCW_MKPIC_ERROR', 'GD image can not be created');
define('_MD_TCW_EMPTY_TITLE', 'Untitled');

//aboutus.php

define('_MD_TCW_CLASS_SETUP', 'Class title setup');
define('_MD_TCW_FCNCTION_SETUP', 'Function setting');
define('_MD_TCW_SELECT_TO_CANCEL', 'Please select the function you want to hide.');
define('_MD_TCW_CLICK_TO_CHANG', 'Click on a picture to change image. You can drag background image and logo to anywhere.');
define("_MD_TCW_GOOD_LOGO_SITE", "Good tool:<a href='http://www.qt86.com/random.php' target='_blank'>http://www.qt86.com/random.php</a>");
define('_MD_TCW_RAND_IMAGE', 'Select random background');
define('_MD_TCW_BG_TOP', 'Ton');
define('_MD_TCW_BG_CENTER', 'Medium');
define('_MD_TCW_BG_BOTTOM', 'Bottom');
define('_MD_TCW_BG_POSITION', 'Picture Location:');
define('_MD_TCW_MEM_UNAME', 'Student Account');
define('_MD_TCW_MEM_PASSWD', 'Student Password');
define('_MD_TCW_PLEASE_INPUT', 'Enter');
define('_MD_TCW_LOGIN', 'Login');
define('_MD_TCW_HELLO', 'Hello, welcome to use the Forums! Please pay attention to manners!!');
define('_MD_TCW_EXIT', 'Exit');

//calendar.php

define("_MD_TCW_CONFIG_NONE", "None");
define("_MD_TCW_CONFIG_HEAD", "Head background image");
define("_MD_TCW_CONFIG_NONE_BG", "Only background color");
define("_MD_TCW_CONTAINER_BG_COLOR", "Background color");
define("_MD_TCW_MAIN_BG_COLOR", "Main content background color");
define("_MD_TCW_MAIN_DEFAULT_COLOR", "Default color");
define("_MD_TCW_MAIN_NAV_COLOR", "Navbar text color");
define("_MD_TCW_MAIN_NAV_TOP_COLOR", "Navbar background color");
define("_MD_TCW_MAIN_NAV_HOVER_COLOR", "Navbar hover text color ");
define("_MD_TCW_MAIN_NAV_HOVER_BG_COLOR", "Navbar hover background color");

define("_MD_TCW_BLOCKS_LIST", "All blocks");
define("_MD_TCW_BLOCKS_SELECTED", "Enabled blocks");

//action.php

define("_MD_TCW_NEW_CATE", "New Categories");
define("_MD_TCW_SELECT_CATE", "Select Category");
define("_MD_TCW_CATE_TOOLS", "Category Tools");
define("_MD_TCW_DEL_CATE_MOVE_TO", "Delete category and move files to ");
define("_MD_TCW_DEL_CATE_ALL", "Delete category and all files.");
define("_MD_TCW_CATE_NAME", "Category Name");
define("_MD_TCW_CATE_NEW_NAME", "New name or new category");
define("_MD_TCW_CATE_ACT", "Do something...");
define("_MD_TCW_CATE_NONE", "None");
define("_MD_TCW_CATE_MODIFY", "Rename to ");
define("_MD_TCW_DEL_CATE_ALL_ALERT", "I'm sure I want to delete the entire category as well as all the archives.");
define("_MD_TCW_OTHER_CLASS_SETUP", "My Other website url:");

define("_MD_TCW_CATE_PLUGIN_ENABLE", "Enable");
define("_MD_TCW_CATE_PLUGIN_TITLE", "Title");
define("_MD_TCW_CATE_PLUGIN_NEW_NAME", "New Title");
define("_MD_TCW_CATE_PLUGIN_IN_FRONTPAGE", "Display in frontpage? / Display number?");
define("_MD_TCW_ADD", "Add");

$dir = XOOPS_ROOT_PATH . "/modules/tad_web/plugins/";
if (is_dir($dir)) {
    if ($dh = opendir($dir)) {
        while (($file = readdir($dh)) !== false) {
            if (filetype($dir . $file) == "dir") {
                if (substr($file, 0, 1) == '.') {
                    continue;
                }
                include XOOPS_ROOT_PATH . "/modules/tad_web/plugins/{$file}/langs/english.php";
            }
        }
        closedir($dh);
    }
}
