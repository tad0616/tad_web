<?php
include_once XOOPS_ROOT_PATH . "/modules/tadtools/language/{$xoopsConfig['language']}/modinfo_common.php";

define('_MI_TCW_NAME', 'Tad Web');
define('_MI_TCW_AUTHOR', 'Tad (tad0616@gmail.com)');
define('_MI_TCW_CREDITS', 'Tad');
define('_MI_TCW_DESC', 'XOOPS multi-user web-based system that can be used to as a class on the page, or the user\'s personal page');

define('_MI_TCW_ABOUTUS', 'About Us');
define('_MI_TCW_NEWS', 'News');
define('_MI_TCW_ACTION', 'Events');
define('_MI_TCW_VIDEO', 'Video');
define('_MI_TCW_FILES', 'Download');
define('_MI_TCW_LINK', 'Common website');
define('_MI_TCW_DISCUSS', 'ChatBox');

define('_MI_TCW_ADMENU1', 'Site management');
define('_MI_TCW_ADMENU2', 'Site category');
define('_MI_TCW_ADMENU3', 'Site setup');
define('_MI_TCW_ADMENU4', 'Disk space');
define('_MI_TCW_ADMENU5', 'Schedule template');

define('_MI_TCW_BNAME1', 'Page menu list');
define('_MI_TCW_BDESC1', 'Lists all the individual pages of the menu');

define('_MI_TCW_BNAME2', 'New discussions');
define('_MI_TCW_BDESC2', 'New discussions');

define('_MI_TCW_BNAME3', 'Events');
define('_MI_TCW_BDESC3', 'Events Action');

define('_MI_TCW_BNAME4', 'Menu');
define('_MI_TCW_BDESC4', 'Menu');

define("_MI_TCW_WEB_MODE_TITLE", "Module Title");
define("_MI_TCW_WEB_MODE_DESC", "The title that display in module homepage.");
define("_MI_TCW_WEB_MODE_DEF", "All Webs");

define('_MI_TCW_WEB_DIRNAME', basename(dirname(dirname(__DIR__))));
define('_MI_TCW_WEB_HELP_HEADER', __DIR__ . '/help/helpheader.html');
define('_MI_TCW_WEB_BACK_2_ADMIN', 'Back to Administration of ');

//help
define('_MI_TCW_WEB_HELP_OVERVIEW', 'Overview');

define('_MI_TCW_WEB_SCHEDULE_TEMPLATE', 'Schedule template');
define('_MI_TCW_WEB_SCHEDULE_TEMPLATE_DESC', 'Don\'t edit it! Please edit it from "<a href="' . XOOPS_URL . '/modules/tad_web/admin/schedule.php</a>">Schedule template"');
define('_MI_TCW_WEB_SCHEDULE_TEMPLATE_DEF', '<table class="table table-bordered schedule_table">
    <tbody>
        <tr>
            <td class="schedule_head"><strong>Time</strong></td>
            <td class="schedule_head"><strong>Section</strong></td>
            <td class="schedule_head"><strong>Mon</strong></td>
            <td class="schedule_head"><strong>Tue</strong></td>
            <td class="schedule_head"><strong>Wed</strong></td>
            <td class="schedule_head"><strong>Thur</strong></td>
            <td class="schedule_head"><strong>Fri</strong></td>
            <td class="schedule_head"><strong>Sat</strong></td>
        </tr>
        <tr>
            <td class="schedule_time">08:00~08:40</td>
            <td class="schedule_section">&nbsp;</td>
            <td class="schedule_cell">Teacher Time</td>
            <td class="schedule_cell">Teacher Time</td>
            <td class="schedule_cell">Teacher Time</td>
            <td class="schedule_cell">Teacher Time</td>
            <td class="schedule_cell">Teacher Time</td>
            <td class="schedule_cell">Teacher Time</td>
        </tr>
        <tr>
            <td class="schedule_time">08:40~09:20</td>
            <td class="schedule_section">1</td>
            <td class="schedule_cell">{1-1}</td>
            <td class="schedule_cell">{2-1}</td>
            <td class="schedule_cell">{3-1}</td>
            <td class="schedule_cell">{4-1}</td>
            <td class="schedule_cell">{5-1}</td>
            <td class="schedule_cell">{6-1}</td>
        </tr>
        <tr>
            <td class="schedule_time">09:30~10:10</td>
            <td class="schedule_section">2</td>
            <td class="schedule_cell">{1-2}</td>
            <td class="schedule_cell">{2-2}</td>
            <td class="schedule_cell">{3-2}</td>
            <td class="schedule_cell">{4-2}</td>
            <td class="schedule_cell">{5-2}</td>
            <td class="schedule_cell">{6-2}</td>
        </tr>
        <tr>
            <td class="schedule_time">10:30~11:10</td>
            <td class="schedule_section">3</td>
            <td class="schedule_cell">{1-3}</td>
            <td class="schedule_cell">{2-3}</td>
            <td class="schedule_cell">{3-3}</td>
            <td class="schedule_cell">{4-3}</td>
            <td class="schedule_cell">{5-3}</td>
            <td class="schedule_cell">{6-3}</td>
        </tr>
        <tr>
            <td class="schedule_time">11:20~12:00</td>
            <td class="schedule_section">4</td>
            <td class="schedule_cell">{1-4}</td>
            <td class="schedule_cell">{2-4}</td>
            <td class="schedule_cell">{3-4}</td>
            <td class="schedule_cell">{4-4}</td>
            <td class="schedule_cell">{5-4}</td>
            <td class="schedule_cell">{6-4}</td>
        </tr>
        <tr>
            <td class="schedule_time">12:00~12:40</td>
            <td class="schedule_section">&nbsp;</td>
            <td class="schedule_note" colspan="6" rowspan="1">Lunch Time</td>
        </tr>
        <tr>
            <td class="schedule_time">12:40~13:30</td>
            <td class="schedule_section">&nbsp;</td>
            <td class="schedule_note" colspan="6" rowspan="1">Rest Time</td>
        </tr>
        <tr>
            <td class="schedule_time">13:40~14:20</td>
            <td class="schedule_section">5</td>
            <td class="schedule_cell">{1-5}</td>
            <td class="schedule_cell">{2-5}</td>
            <td class="schedule_cell">{3-5}</td>
            <td class="schedule_cell">{4-5}</td>
            <td class="schedule_cell">{5-5}</td>
            <td class="schedule_cell">&nbsp;</td>
        </tr>
        <tr>
            <td class="schedule_time">14:30~15:10</td>
            <td class="schedule_section">6</td>
            <td class="schedule_cell">{1-6}</td>
            <td class="schedule_cell">{2-6}</td>
            <td class="schedule_cell">{3-6}</td>
            <td class="schedule_cell">{4-6}</td>
            <td class="schedule_cell">{5-6}</td>
            <td class="schedule_cell">&nbsp;</td>
        </tr>
        <tr>
            <td class="schedule_time">15:20~16:00</td>
            <td class="schedule_section">7</td>
            <td class="schedule_cell">{1-7}</td>
            <td class="schedule_cell">{2-7}</td>
            <td class="schedule_cell">{3-7}</td>
            <td class="schedule_cell">{4-7}</td>
            <td class="schedule_cell">{5-7}</td>
            <td class="schedule_cell">&nbsp;</td>
        </tr>
        <tr>
            <td class="schedule_time">&nbsp;</td>
            <td class="schedule_section">8</td>
            <td class="schedule_cell">{1-8}</td>
            <td class="schedule_cell">{2-8}</td>
            <td class="schedule_cell">{3-8}</td>
            <td class="schedule_cell">{4-8}</td>
            <td class="schedule_cell">{5-8}</td>
            <td class="schedule_cell">&nbsp;</td>
        </tr>
    </tbody>
</table>');

define('_MI_TCW_WEB_SCHEDULE_SUBJECTS', 'Schedule subjects');
define('_MI_TCW_WEB_SCHEDULE_SUBJECTS_DESC', 'Don\'t edit it! Please edit it from "<a href="' . XOOPS_URL . '/modules/tad_web/admin/schedule.php</a>">Schedule template"');
define('_MI_TCW_WEB_SCHEDULE_SUBJECTS_DEF', 'Writing;Reading;Math;Science;Art;Music');
define('_MI_TADWEB_ABOUTUS_MODE', 'List all web mode');
define('_MI_TADWEB_ABOUTUS_MODE_DESC', 'Please select fields to appear in the site list.');
define('_MI_TADWEB_ABOUTUS_MODE_KEY1', 'Counter');
define('_MI_TADWEB_ABOUTUS_MODE_KEY2', 'Web Title');
define('_MI_TADWEB_ABOUTUS_MODE_KEY3', 'Schedule');
define('_MI_TADWEB_ABOUTUS_MODE_KEY4', 'Homework');

define('_MI_TADWEB_CAL_COLS', 'Calendar display');
define('_MI_TADWEB_CAL_COLS_DESC', 'Setting events to be displayed on the home page calendar.');
define('_MI_TADWEB_CAL_COLS_KEY1', 'Web Events');
define('_MI_TADWEB_CAL_COLS_KEY2', 'Sub-web Events');
define('_MI_TADWEB_CAL_COLS_KEY3', 'News Events');
define('_MI_TADWEB_CAL_COLS_KEY4', 'Homework');
