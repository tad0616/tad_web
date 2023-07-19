<?php
xoops_loadLanguage('main', 'tadtools');
if (!defined('_TAD_NEED_TADTOOLS')) {
    define('_TAD_NEED_TADTOOLS', 'This module needs TadTools module. You can download TadTools from <a href="https://campus-xoops.tn.edu.tw/modules/tad_modules/index.php?module_sn=1" target="_blank">XOOPS EasyGO</a>.');
}
define('_MD_TCW_HOME', 'List');
define('_MD_TCW_CLASS_HOME', 'Home');
define('_MD_TCW_ADMIN', 'Management');
define('_MD_TCW_CLEAR', 'Clear');
define('_MD_TCW_TOTAL', 'Total');
define('_MD_TCW_PEOPLE', 'People');
define('_MD_TCW_TOOLS', 'Setup');
define('_MD_TCW_WEB_TOOLS', 'Web');
define('_MD_TCW_PLUGIN_TOOLS', 'Plugins');
define('_MD_TCW_ABOUT_PLUGIN_TOOLS', '<ul><li>You can directly drag the items are sorted, pulling finished automatically saved.</li><li>Sorting only affects the order of presentation over the menu and menu items of the functional blocks.</li></ul>');
define('_MD_TCW_HEAD_TOOLS', 'Header');
define('_MD_TCW_LOGO_TOOLS', 'Logo');
define('_MD_TCW_BG_TOOLS', 'Background');
define('_MD_TCW_COLOR_TOOLS', 'Color');
define('_MD_TCW_BLOCK_TOOLS', 'Blocks');
define('_MD_TCW_BLOCK_TITLE', 'Block title');
define('_MD_TCW_BLOCK_SHOW_TITLE', 'Does display block title?');
define('_MD_TCW_BLOCK_ENABLE', 'Does enable this block?');
define('_MD_TCW_DATA_NOT_EXIST', 'This information does not exist.');
define('_MD_TCW_WEB_NOT_EXIST', 'The webdite does not exist.');
define('_MD_TCW_SAVED', 'Saved');
define('_MD_TCW_DEL_WEB', 'Remove this site');
define('_MD_TCW_DEL_WEB_DESC', 'After removal, all the information the site will be removed from the database, and delete all uploaded files.
Once you delete can not be restored, please carefully execute it.');
define('_MD_TCW_WILL_DEL', 'It will delete the following information:');
define('_MD_TCW_PLUGIN_TOTAL', 'Total');
define('_MD_TCW_DELETE', 'Yes! I want to delete it! ');

//header.php
define('_MD_TCW_ALL_CLASS', 'Class List');
define('_MD_TCW_MY_CLASS', 'Class members');
define('_MD_TCW_ALL_WEB', 'Pages list');

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

define('_MD_TCW_WEB_NAME_SETUP', 'Web Name');
define('_MD_TCW_FCNCTION_SETUP', 'Function setting');
define('_MD_TCW_SELECT_TO_CANCEL', 'Please select the function you want to hide.');
define('_MD_TCW_CLICK_TO_CHANG', 'Click on a picture to change image. You can drag background image and logo to anywhere.');
define('_MD_TCW_GOOD_LOGO_SITE', "<ol><li>Good tool:<a href='https://www.canva.com' target='_blank'>https://www.canva.com</a></li><li>If logo was disappear , <a href='config.php?WebID=%s&op=reset_logo'>click here to reset it</a>.</li></ol>");
define('_MD_TCW_GOOD_BG_SITE', "<ol><li>Good photos: <a href='https://pixabay.com/' target='_blank'>https://pixabay.com/</a></li><li>If head background was disappear , <a href='config.php?WebID=%s&op=reset_head'>click here to reset it</a>.</li></ol>");
define('_MD_TCW_RAND_IMAGE', 'Select random background');
define('_MD_TCW_BG_TOP', 'Ton');
define('_MD_TCW_BG_CENTER', 'Medium');
define('_MD_TCW_BG_BOTTOM', 'Bottom');
//define('_MD_TCW_LOGIN', 'Login');
define('_MD_TCW_HELLO', 'Hello!');
define('_MD_TCW_EXIT', 'Exit');

//calendar.php

define('_MD_TCW_CONFIG_NONE', 'None');
define('_MD_TCW_CONFIG_HEAD', 'Head background image');
define('_MD_TCW_CONFIG_NONE_BG', 'Only background color');
define('_MD_TCW_CONTAINER_BG_COLOR', 'Background color');
define('_MD_TCW_MAIN_BG_COLOR', 'Main content background color');
define('_MD_TCW_MAIN_DEFAULT_COLOR', 'Default color');
define('_MD_TCW_MAIN_NAV_COLOR', 'Navbar text color');
define('_MD_TCW_MAIN_NAV_TOP_COLOR', 'Navbar background color');
define('_MD_TCW_MAIN_NAV_HOVER_COLOR', 'Navbar hover text color ');
define('_MD_TCW_MAIN_NAV_HOVER_BG_COLOR', 'Navbar hover background color');

define('_MD_TCW_BLOCKS_LIST', 'All blocks');
define('_MD_TCW_BLOCKS_SELECTED', 'Enabled blocks');
define('_MD_TCW_BLOCKS_SETUP', 'Setup block');

//action.php

define('_MD_TCW_NEW_CATE', 'New Categories');
define('_MD_TCW_NEW_SOMETHING', 'New %s');
define('_MD_TCW_SELECT_CATE', 'Select Category');
define('_MD_TCW_SELECT_PLUGIN_CATE', 'Select %s Category');
define('_MD_TCW_CATE_TOOLS', 'Category Tools');
define('_MD_TCW_DEL_CATE_MOVE_TO', 'Delete category and move files to ');
define('_MD_TCW_DEL_CATE_ALL', 'Delete category and all files.');
define('_MD_TCW_UNABLE_CATE', 'Unable category');
define('_MD_TCW_ENABLE_CATE', 'Enable category');
define('_MD_TCW_CATE_NAME', 'Category Name');
define('_MD_TCW_CATE_NEW_NAME', 'New name or new category');
define('_MD_TCW_CATE_ACT', 'Do something...');
define('_MD_TCW_CATE_NONE_OPT', 'None');
define('_MD_TCW_CATE_NONE', 'No any category');
define('_MD_TCW_CATE_MODIFY', 'Rename to ');
define('_MD_TCW_DEL_CATE_ALL_ALERT', "I'm sure I want to delete the entire category as well as all the archives.");
define('_MD_TCW_OTHER_CLASS_SETUP', 'My Other website url:');
define('_MD_TCW_CATE_POWER', 'Category permission');

define('_MD_TCW_CATE_PLUGIN_ENABLE', 'Enable');
define('_MD_TCW_CATE_PLUGIN_TITLE', 'Title');
define('_MD_TCW_CATE_PLUGIN_NEW_NAME', 'New Title');
define('_MD_TCW_CATE_PLUGIN_IN_FRONTPAGE', 'Display in frontpage? / Display number?');
define('_MD_TCW_ADD', 'Add');
define('_MD_TCW_ADMIN_SETUP', 'Co-administrator settings');
define('_MD_TCW_USER_LIST', 'Users list');
define('_MD_TCW_USER_SELECTED', 'Co-administrator');
define('_MD_TCW_DEFAULT_ADMIN', 'Current Default Administrator:');

define('_MD_TCW_THEME_TOOLS', 'Theme Set');
define('_MD_TCW_THEME_TOOLS_THEME_SIDE', 'Side Position:');
define('_MD_TCW_THEME_TOOLS_THEME_SIDE_LEFT', 'Left');
define('_MD_TCW_THEME_TOOLS_THEME_SIDE_NONE', 'None');
define('_MD_TCW_THEME_TOOLS_THEME_SIDE_RIGHT', 'Right');
define('_MD_TCW_THEME_TOOLS_FONT_SIZE', 'Menu Font Size:');
define('_MD_TCW_SUN', 'Sun');
define('_MD_TCW_MON', 'Mon');
define('_MD_TCW_TUE', 'Tue');
define('_MD_TCW_WED', 'Wed');
define('_MD_TCW_THU', 'Thu');
define('_MD_TCW_FRI', 'Fri');
define('_MD_TCW_SAT', 'Sat');

$dir = XOOPS_ROOT_PATH . '/modules/tad_web/plugins/';
if (is_dir($dir)) {
    if ($dh = opendir($dir)) {
        while (false !== ($file = readdir($dh))) {
            if ('dir' === filetype($dir . $file)) {
                if ('.' === mb_substr($file, 0, 1)) {
                    continue;
                }
                require_once XOOPS_ROOT_PATH . "/modules/tad_web/plugins/{$file}/langs/english.php";
            }
        }
        closedir($dh);
    }
}

define('_MD_TCW_BLOCK_LIMIT', 'Setting the number of data to be displayed');
define('_MD_TCW_WEBTITLE', 'Class Name');
define('_MD_TCW_WEBNAME', 'Web Name');

define('_MD_TCW_BLOCK_ADD', 'Add New Block');
define('_MD_TCW_BLOCK_POSITION', 'Block Position');
define('_MD_TCW_BLOCK_CONTENT', 'Block Content Type');
define('_MD_TCW_BLOCK_HTML', 'Block Content');
define('_MD_TCW_BLOCK_JS', 'JS Code or Embed Code');
define('_MD_TCW_BLOCK_JS_DESC', 'JS Code or Embed Code');
define('_MD_TCW_BLOCK_IFRAME', 'iframe');
define('_MD_TCW_BLOCK_IFRAME_DESC', 'iframe URL');
define('_MD_TCW_BLOCK_SHARE', 'Share Block');
define('_MD_TCW_BLOCK_SHARE_DESC', 'If selected, the others can see and use this block.');
define('_MD_TCW_SIDE_BLOCK', 'Side blocks');
define('_MD_TCW_TOP_CENTER_BLOCK', 'Top center blocks');
define('_MD_TCW_TOP_LEFT_BLOCK', 'Top left blocks');
define('_MD_TCW_TOP_RIGHT_BLOCK', 'Top right blocks');
define('_MD_TCW_BOTTOM_CENTER_BLOCK', 'Bottom center blocks');
define('_MD_TCW_BOTTOM_LEFT_BLOCK', 'Bottom left blocks');
define('_MD_TCW_BOTTOM_RIGHT_BLOCK', 'Bottom right blocks');
define('_MD_TCW_UNINSTALL_BLOCK', 'Uninstall blocks');
define('_MD_TCW_NO_SPACE', 'You (%s) have used %s MB of hard disk space has reached the upper limit of  %s MB, can not add data.');
define('_MD_TCW_TO_MENU', 'Menu');
define('_MD_TCW_USE_BLOCK_SITE', 'Currently there are %s sites use this block');
define('_MD_TCW_SIDE_BG_COLOR', 'Side background color');

define('_MD_TCW_CENTER_TEXT_COLOR', 'Main text color');
define('_MD_TCW_CENTER_LINK_COLOR', 'Main link color');
define('_MD_TCW_CENTER_HOVER_COLOR', 'Main hover color');
define('_MD_TCW_SIDE_TEXT_COLOR', 'Side text color');
define('_MD_TCW_SIDE_LINK_COLOR', 'Side link color');
define('_MD_TCW_SIDE_HOVER_COLOR', 'Side hover color');
define('_MD_TCW_DEFAULT_COLOR', 'Revert to the system default');
define('_MD_TCW_BLOCK_TITLE_PIC', 'The block title into picture');
define('_MD_TCW_BLOCK_PIC_COLOR', 'Font color');
define('_MD_TCW_BLOCK_PIC_BORDER_COLOR', 'Border color');
define('_MD_TCW_BLOCK_PIC_SIZE', 'Font size');
define('_MD_TCW_BLOCK_PIC_FONT', 'Font family');
define('_MD_TCW_BLOCK_PIC_FONT1', 'Cute font');
define('_MD_TCW_BLOCK_PIC_FONT2', '');
define('_MD_TCW_BLOCK_TITLE_USE_PIC', 'Use the block image replace text');
define('_MD_TCW_SEARCH_RESULT', 'Search Result');
define('_MD_TCW_READ_MORE', '(more...)');
define('_MD_TCW_PRINT', 'Print');
define('_MD_TCW_THEME_TOOLS_DEFAULT_THEME', 'Default Theme');
define('_MD_TCW_CLOSE_WEB', 'Close Web');
define('_MD_TCW_CLOSE_WEB_DESC', 'After closing the site, the site list will not see the site, even if connected to the site will display a message that has been closed, only after login, from the menu to start it again.');
define('_MD_TCW_OPEN_WEB', 'Enable Web');
define('_MD_TCW_OPEN_WEB_DESC', 'Your site is currently closed, and you want to enable it?');
define('_MD_TCW_SETUP', 'Setup ');

define('_MD_TCW_BG_REPEAT', 'background-repeat');
define('_MD_TCW_BG_REPEAT_NORMAL', 'repeat');
define('_MD_TCW_BG_REPEAT_X', 'repeat_x');
define('_MD_TCW_BG_REPEAT_Y', 'repeat_y');
define('_MD_TCW_BG_NO_REPEAT', 'no_repeat');
define('_MD_TCW_BG_ATTACHMENT', 'background-attachment');
define('_MD_TCW_BG_ATTACHMENT_SCROLL', 'scroll');
define('_MD_TCW_BG_ATTACHMENT_FIXED', 'fixed');
define('_MD_TCW_BG_POSITION', 'background-postiton');
define('_MD_TCW_BG_POSITION_LT', 'left top');
define('_MD_TCW_BG_POSITION_RT', 'right top');
define('_MD_TCW_BG_POSITION_LB', 'left bottom');
define('_MD_TCW_BG_POSITION_RB', 'right bottom');
define('_MD_TCW_BG_POSITION_CC', 'center center');
define('_MD_TCW_BG_POSITION_CT', 'center top');
define('_MD_TCW_BG_POSITION_CB', 'center bottom');
define('_MD_TCW_BG_SIZE', 'background-size');
define('_MD_TCW_BG_SIZE_NONE', 'none');
define('_MD_TCW_BG_SIZE_COVER', 'cover');
define('_MD_TCW_BG_SIZE_CONTAIN', 'contain');
define('_MD_TCW_MENU_BOOKS', 'Manual');
define('_MD_TCW_MENU_DISCUSS', 'Discuss');
define('_MD_TCW_MENU_SUGGEST', 'Suggest');
define('_MD_TCW_LINKTO', 'Link');
define('_MD_TCW_ADMINER', 'Adminer');
define('_MD_TCW_ADMINPAGE', 'Admin Page');

define('_MD_TCW_USE_FB_COMMENT_TEXT', 'Do you want to use FaceBook Comments box?');
define('_MD_TCW_USE_FB_COMMENT_DESC', '"Yes" to display a FaceBook Comments box in a single page.');

define('_MD_TCW_POWER_FOR', 'Who can read?');
define('_MD_TCW_POWER_FOR_ALL', 'All users');
define('_MD_TCW_POWER_FOR_USERS', 'All login users');
define('_MD_TCW_POWER_FOR_WEB_USERS', 'Only my web users');
define('_MD_TCW_POWER_FOR_WEB_ADMIN', 'Only my web admin');
define('_MD_TCW_NOW_READ_POWER', 'No permission to read this content');
define('_MD_TCW_TAGS', 'Tags');
define('_MD_TCW_TAG', 'Tag');
define('_MD_TCW_TAGS_LIST', 'All Tags');
define('_MD_TCW_INPUT_TAGS', 'Please enter new tags, if more than one tag with a lowercase comma "," separated');
define('_MD_TCW_UNABLE', 'Unabale');

define('_MD_TCW_PLUGIN_MENU', 'Main Menu');
define('_MD_TCW_USER_SIMPLE_MENU', 'Simplified menu');

define('_MD_TCW_KEYWORD_TO_SELECT_USER', 'Enter a keyword to filter user');
define('_MD_TCW_SELETC_USER', 'Filter');
define('_MD_TCW_LEADERBOARD', 'Leaderboard');
define('_MD_TCW_LEADERBOARD_RANK', 'Rank');
define('_MD_TCW_WEB_COUNTER', 'Web Counter');
define('_MD_TCW_WEB_BLOCK', 'Blocks');
define('_MD_TCW_HI', 'Hi %s!');
define('_MD_TCW_WEB_CLOSE_MENU', 'Close Menu');
define('_MD_TCW_WEB_OPENID_SETUP', 'OpenID Setup');

define('_MD_TCW_CATE_ASSISTANT', 'Assistant');
define('_MD_TCW_CATE_SET_ASSISTANT', 'Setup an assistant');

define('_MD_TCW_CATE_ENABLED', 'Enable');
define('_MD_TCW_CATE_UNABLED', 'Unable');
define('_MD_TCW_CATE_DATA_AMOUNT1', '( ');
define('_MD_TCW_CATE_DATA_AMOUNT2', ' )');
define('_MD_TCW_BLOCK_EMPTY', 'Empty Block');
define('_MD_TCW_BLOCK_DEMO', 'Block Demo');

define('_MD_TCW_UPDATE_MY_NAME', 'Admin Name');

define('_MD_TCW_CATE_NOTE', 'If there is a <i class="fa fa-link"></i> icon, and it is a <span style="color: #a0062c;">purplish</span> category, Will be linked to the class, if the class is hidden in the future, the content under this category will also be hidden.');

define('_MD_TCW_USED_SPACE', 'Used space:');
define('_MD_TCW_RE_GENERATE_SCREEN', 'Re-generate screen');
define('_MD_TCW_ADM_DEFALTWEBNAME', '(Because you are an administrator, the menu at the bottom is "%s")');
