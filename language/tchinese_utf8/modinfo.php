<?php
xoops_loadLanguage('modinfo_common', 'tadtools');
define('_MI_TCW_NAME', '多人網頁系統');
define('_MI_TCW_AUTHOR', 'tad(tad0616@gmail.com)');
define('_MI_TCW_CREDITS', 'tad');
define('_MI_TCW_DESC', 'XOOPS多人網頁系統，可用來當作班級網頁，或者使用者的個人網頁');

define('_MI_TCW_ABOUTUS', '關於我們');
define('_MI_TCW_NEWS', '最新消息');
define('_MI_TCW_ACTION', '活動剪影');
define('_MI_TCW_VIDEO', '影片');
define('_MI_TCW_FILES', '下載');
define('_MI_TCW_LINK', '常用網站');
define('_MI_TCW_DISCUSS', '留言簿');

define('_MI_TCW_ADMENU1', '網站管理');
define('_MI_TCW_ADMENU2', '網站分類');
define('_MI_TCW_ADMENU3', '首頁設定');
define('_MI_TCW_ADMENU4', '使用空間');
define('_MI_TCW_ADMENU5', '課表樣板');
define('_MI_TCW_ADMENU6', '全域通知');

define('_MI_TCW_BNAME1', '網頁列表選單');
define('_MI_TCW_BDESC1', '列出所有個人網頁的選單');

define('_MI_TCW_BNAME2', '最新討論');
define('_MI_TCW_BDESC2', '最新討論');

define('_MI_TCW_BNAME3', '活動剪影');
define('_MI_TCW_BDESC3', '活動剪影');

define('_MI_TCW_BNAME4', '選單');
define('_MI_TCW_BDESC4', '選單');

define('_MI_TCW_WEB_MODE_TITLE', '模組首頁標題');
define('_MI_TCW_WEB_MODE_DESC', '此模組首頁欲呈現的標題');
define('_MI_TCW_WEB_MODE_DEF', '班級網頁一覽');

define('_MI_TCW_WEB_DIRNAME', basename(dirname(dirname(__DIR__))));
define('_MI_TCW_WEB_HELP_HEADER', __DIR__ . '/help/helpheader.tpl');
define('_MI_TCW_WEB_BACK_2_ADMIN', '管理');

//help
define('_MI_TCW_WEB_HELP_OVERVIEW', '概要');

define('_MI_TCW_WEB_SCHEDULE_TEMPLATE', '功課表樣板');
define('_MI_TCW_WEB_SCHEDULE_TEMPLATE_DESC', '勿動，請由後台「<a href="' . XOOPS_URL . '/modules/tad_web/admin/schedule.php">課表樣板</a>」修改之');
define('_MI_TCW_WEB_SCHEDULE_TEMPLATE_DEF', '<table class="table table-bordered schedule_table">
    <tbody>
        <tr>
            <td class="schedule_head" id="Tim"><strong>時間</strong></td>
            <td class="schedule_head" id="Sct"><strong>節</strong></td>
            <td class="schedule_head" id="Mon"><strong>一</strong></td>
            <td class="schedule_head" id="Tue"><strong>二</strong></td>
            <td class="schedule_head" id="Wed"><strong>三</strong></td>
            <td class="schedule_head" id="Thu"><strong>四</strong></td>
            <td class="schedule_head" id="Fri"><strong>五</strong></td>
            <td class="schedule_head" id="Sat"><strong>六</strong></td>
        </tr>
        <tr>
            <td headers="Tim" class="schedule_time">08:00~08:40</td>
            <td headers="Sct" class="schedule_section">&nbsp;</td>
            <td headers="Mon" class="schedule_cell">導師時間</td>
            <td headers="Tue" class="schedule_cell">導師時間</td>
            <td headers="Wed" class="schedule_cell">導師時間</td>
            <td headers="Thu" class="schedule_cell">導師時間</td>
            <td headers="Fri" class="schedule_cell">導師時間</td>
            <td headers="Sat" class="schedule_cell">導師時間</td>
        </tr>
        <tr>
            <td headers="Tim" class="schedule_time">08:40~09:20</td>
            <td headers="Sct" class="schedule_section">一</td>
            <td headers="Mon" class="schedule_cell">{1-1}</td>
            <td headers="Tue" class="schedule_cell">{2-1}</td>
            <td headers="Wed" class="schedule_cell">{3-1}</td>
            <td headers="Thu" class="schedule_cell">{4-1}</td>
            <td headers="Fri" class="schedule_cell">{5-1}</td>
            <td headers="Sat" class="schedule_cell">{6-1}</td>
        </tr>
        <tr>
            <td headers="Tim" class="schedule_time">09:30~10:10</td>
            <td headers="Sct" class="schedule_section">二</td>
            <td headers="Mon" class="schedule_cell">{1-2}</td>
            <td headers="Tue" class="schedule_cell">{2-2}</td>
            <td headers="Wed" class="schedule_cell">{3-2}</td>
            <td headers="Thu" class="schedule_cell">{4-2}</td>
            <td headers="Fri" class="schedule_cell">{5-2}</td>
            <td headers="Sat" class="schedule_cell">{6-2}</td>
        </tr>
        <tr>
            <td headers="Tim" class="schedule_time">10:30~11:10</td>
            <td headers="Sct" class="schedule_section">三</td>
            <td headers="Mon" class="schedule_cell">{1-3}</td>
            <td headers="Tue" class="schedule_cell">{2-3}</td>
            <td headers="Wed" class="schedule_cell">{3-3}</td>
            <td headers="Thu" class="schedule_cell">{4-3}</td>
            <td headers="Fri" class="schedule_cell">{5-3}</td>
            <td headers="Sat" class="schedule_cell">{6-3}</td>
        </tr>
        <tr>
            <td headers="Tim" class="schedule_time">11:20~12:00</td>
            <td headers="Sct" class="schedule_section">四</td>
            <td headers="Mon" class="schedule_cell">{1-4}</td>
            <td headers="Tue" class="schedule_cell">{2-4}</td>
            <td headers="Wed" class="schedule_cell">{3-4}</td>
            <td headers="Thu" class="schedule_cell">{4-4}</td>
            <td headers="Fri" class="schedule_cell">{5-4}</td>
            <td headers="Sat" class="schedule_cell">{6-4}</td>
        </tr>
        <tr>
            <td headers="Tim" class="schedule_time">12:00~12:40</td>
            <td headers="Sct" class="schedule_section">&nbsp;</td>
            <td id="LunchTime" class="schedule_note" colspan="6" rowspan="1">午餐時間</td>
        </tr>
        <tr>
            <td headers="Tim" class="schedule_time">12:40~13:30</td>
            <td headers="Sct" class="schedule_section">&nbsp;</td>
            <td id="RestTime" class="schedule_note" colspan="6" rowspan="1">午休時間</td>
        </tr>
        <tr>
            <td headers="Tim" class="schedule_time">13:40~14:20</td>
            <td headers="Sct" class="schedule_section">五</td>
            <td headers="Mon" class="schedule_cell">{1-5}</td>
            <td headers="Tue" class="schedule_cell">{2-5}</td>
            <td headers="Wed" class="schedule_cell">{3-5}</td>
            <td headers="Thu" class="schedule_cell">{4-5}</td>
            <td headers="Fri" class="schedule_cell">{5-5}</td>
            <td headers="Sat" class="schedule_cell">&nbsp;</td>
        </tr>
        <tr>
            <td headers="Tim" class="schedule_time">14:30~15:10</td>
            <td headers="Sct" class="schedule_section">六</td>
            <td headers="Mon" class="schedule_cell">{1-6}</td>
            <td headers="Tue" class="schedule_cell">{2-6}</td>
            <td headers="Wed" class="schedule_cell">{3-6}</td>
            <td headers="Thu" class="schedule_cell">{4-6}</td>
            <td headers="Fri" class="schedule_cell">{5-6}</td>
            <td headers="Sat" class="schedule_cell">&nbsp;</td>
        </tr>
        <tr>
            <td headers="Tim" class="schedule_time">15:20~16:00</td>
            <td headers="Sct" class="schedule_section">七</td>
            <td headers="Mon" class="schedule_cell">{1-7}</td>
            <td headers="Tue" class="schedule_cell">{2-7}</td>
            <td headers="Wed" class="schedule_cell">{3-7}</td>
            <td headers="Thu" class="schedule_cell">{4-7}</td>
            <td headers="Fri" class="schedule_cell">{5-7}</td>
            <td headers="Sat" class="schedule_cell">&nbsp;</td>
        </tr>
        <tr>
            <td headers="Tim" class="schedule_time">&nbsp;</td>
            <td headers="Sct" class="schedule_section">八</td>
            <td headers="Mon" class="schedule_cell">{1-8}</td>
            <td headers="Tue" class="schedule_cell">{2-8}</td>
            <td headers="Wed" class="schedule_cell">{3-8}</td>
            <td headers="Thu" class="schedule_cell">{4-8}</td>
            <td headers="Fri" class="schedule_cell">{5-8}</td>
            <td headers="Sat" class="schedule_cell">&nbsp;</td>
        </tr>
    </tbody>
</table>');

define('_MI_TCW_WEB_SCHEDULE_SUBJECTS', '功課表科目設定');
define('_MI_TCW_WEB_SCHEDULE_SUBJECTS_DESC', '勿動，請由後台「<a href="' . XOOPS_URL . '/modules/tad_web/admin/schedule.php">課表樣板</a>」修改之');
define('_MI_TCW_WEB_SCHEDULE_SUBJECTS_DEF', '國語;數學;社會;自然;音樂;體育;美勞;團體活動;輔導活動;鄉土教學;道德與健康');
define('_MI_TADWEB_ABOUTUS_MODE', '總覽網站列表呈現模式');
define('_MI_TADWEB_ABOUTUS_MODE_DESC', '設定總覽網站列表呈現模式');
define('_MI_TADWEB_ABOUTUS_MODE_KEY1', '人氣');
define('_MI_TADWEB_ABOUTUS_MODE_KEY2', '網頁名稱');
define('_MI_TADWEB_ABOUTUS_MODE_KEY3', '功課表');
define('_MI_TADWEB_ABOUTUS_MODE_KEY4', '聯絡簿');

define('_MI_TADWEB_CAL_COLS', '首頁行事曆顯示內容');
define('_MI_TADWEB_CAL_COLS_DESC', '設定首頁行事曆欲顯示的內容');
define('_MI_TADWEB_CAL_COLS_KEY1', '母站事件');
define('_MI_TADWEB_CAL_COLS_KEY2', '子站事件');
define('_MI_TADWEB_CAL_COLS_KEY3', '新聞事件');
define('_MI_TADWEB_CAL_COLS_KEY4', '聯絡簿');

define('_MI_TADWEB_USER_SPACE_QUOTA', '子網站可使用硬碟空間上限');
define('_MI_TADWEB_USER_SPACE_QUOTA_DESC', '單位為MB，填入數字即可');
define('_MI_TCW_LIST_WEB_TEXT', '網站列表排序依據');
define('_MI_TCW_LIST_WEB_DESC', '總覽頁網站列表的排序依據');
define('_MI_TCW_LIST_WEB_OPT1', '按照指定順序');
define('_MI_TCW_LIST_WEB_OPT2', '按照人氣由小到大');
define('_MI_TCW_LIST_WEB_OPT3', '按照人氣由大到小');
define('_MI_TCW_LIST_WEB_OPT4', '按照建站日期由舊到新');
define('_MI_TCW_LIST_WEB_OPT5', '按照建站日期由新到舊');
