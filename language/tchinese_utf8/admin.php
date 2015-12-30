<?php
include_once '../../tadtools/language/' . $xoopsConfig['language'] . '/admin_common.php';

//main.php
define("_MA_TCW_TEAMID", "編號");
define("_MA_TCW_TEAMNAME", "網站名稱");
define("_MA_TCW_TEAM", "網站");
define("_MA_TCW_TEAMSORT", "排序");
define("_MA_TCW_TEAMENABLE", "狀態");
define("_MA_TCW_TEAMCOUNTER", "人氣");
define("_MA_TCW_MEM_AMOUNT", "成員人數");
define("_MA_TCW_TEAMLEADER", "擁有者");
define("_MA_TCW_TEAMTITLE", "班級名稱");
define("_MA_TCW_MAIN_TITLE", "個人網頁管理");
define("_MA_TCW_GROUP_NAME", "XOOPS個人網頁群組");
define("_MA_TCW_GROUP_DESC", "請勿修改群組名稱，否則會造成模組運作不正確。");
define("_MA_TCW_CREATE_BY_USER", "快速大量新增使用者網站");
define("_MA_TCW_ALL_USER_NO", "尚未有自己網站的使用者");
define("_MA_TCW_ALL_USER_YES", "允許有自己網站的使用者");
define("_MA_TCW_SOMEBODY_WEB", "%s的專用網頁");
define("_MA_TCW_WILL_DEL", "將刪除以下資料：");
define("_MA_TCW_DEL_MEM", "成員資料");
define("_MA_TCW_DEL_LINK", "好站連結資料");
define("_MA_TCW_DEL_NEWS", "最新消息資料");
define("_MA_TCW_DEL_ACTION", "活動剪影資料");
define("_MA_TCW_DEL_FILES", "檔案下載資料");
define("_MA_TCW_DEL_VIDEOS", "線上影片資料");
define("_MA_TCW_DEL_DISCUSS", "留言討論資料");
define("_MA_TCW_DELETE", "確定刪除（不刪就按別的連結離開即可）");
define("_MA_TCW_UPLOAD_OWNER_PIC", "上傳首頁圖檔");
define("_MA_TCW_ORDER_BY_TEAMTITLE", "依班級名稱排序");

//save_sort.php save.php
define("_MA_TCW_UPDATE_FAIL", "更新失敗！");
define("_MA_TCW_SAVE_SORT_OK", "排序完成！ ");
define("_MA_TCW_NEED_TAD_WEB_THEME", "<ul><li style='line-height:2;'>本模組需要搭配 <a href='http://120.115.2.90/modules/tad_modules/index.php?module_sn=77' target='_blank'>for_tad_web_theme 佈景</a>。</li><li style='line-height:2;'>該佈景只需要解壓縮放到 themes 目錄底下即可，無須至偏好設定選用。</li><li style='line-height:2;'>亦可<a href='" . XOOPS_URL . "/modules/tad_adm/admin/main.php'>從站長工具箱直接安裝</a></li></ul>");

define('_MA_TCW_NEED_IMAGECREATETURECOLOR', 'PHP不支援 imagecreatetruecolor() 函數');
define('_MA_TCW_NEED_IMAGECREATETURECOLOR_CONTENT', "請參考以下連結以安裝之：<a href='http://120.115.2.90/modules/tad_book3/page.php?tbdsn=216' target='_blank'>http://120.115.2.90/modules/tad_book3/page.php?tbdsn=216</a>");
define('_MA_TCW_NEED_THEME', '缺少 for_tad_web_theme 佈景');
define('_MA_TCW_NEED_THEME_CONTENT', "<a href='http://120.115.2.90/modules/tad_modules/index.php?module_sn=77' target='_blank'>for_tad_web_theme</a> 佈景只需要解壓縮放到 themes 目錄底下即可，無須至偏好設定選用。亦可<a href='" . XOOPS_URL . "/modules/tad_adm/admin/main.php'>從站長工具箱直接安裝</a>");
define('_MA_TCW_NEED_TADTOOLS', 'TadTools版本需要 2.7.4 以上');
define('_MA_TCW_NEED_TADTOOLS_CONTENT', "請<a href='" . XOOPS_URL . "/modules/tad_adm/admin/main.php'>從站長工具箱</a>更新 TadTools 至最新版。");

define('_MA_TADWEB_CATEID', '編號');
define('_MA_TADWEB_WEBID', '所屬班級');
define('_MA_TADWEB_CATENAME', '分類名稱');
define('_MA_TADWEB_COLNAME', '對應欄位名稱');
define('_MA_TADWEB_COLSN', '對應欄位值');
define('_MA_TADWEB_CATESORT', '排序');
define('_MA_TADWEB_CATEENABLE', '狀態');
define('_MA_TADWEB_CATECOUNTER', '人氣');
define('_MA_TCW_SELECT_CATE', '不分類');
define('_MA_TCW_DISK_TOTAL_SPACE_STATUS', '網站使用空間一覽');
define('_MA_TCW_DISK_TOTAL_SPACE', '網站已使用空間');
define('_MA_TCW_DISK_PATH', '個人網站存放目錄');
define('_MA_TCW_DISK_SPACE_QUOTA', '每站可使用硬碟空間上限：');
define('_MA_TCW_DISK_SPACE_TOTAL', '，全部已使用：');
define('_MA_TCW_DISK_AVAILABLE_SPACE', '，主機可用空間：');

define('_MA_TCW_WEB_SCHEDULE_TEMPLATE', '設定功課表樣板');
define('_MA_TCW_WEB_SCHEDULE_TEMPLATE_DESC', '
  <p>{週-節} 屆時在前台會轉變為可讓老師輸入的欄位。</p>
  <p>可根據 {週-節} 規則，自行新增標籤，不需要的也可以移除，例如第八節（移除列）或星期六（移除欄）。</p>
  <p>週日~週六，分別為0~6。如星期日第一節為 {0-1}，星期六第五節為 {6-5}。</p>
  <p>除了 {週-節} 標籤不要編輯以外，其他都能自行修改。</p>
  <p>若欲調整顏色，可自行修改 ' . XOOPS_ROOT_PATH . '/modules/tad_web/plugins/schedule/schedule.css 檔案內容</p>');

define('_MA_TCW_WEB_SCHEDULE_SUBJECT', '設定科目（供老師選用）');
define('_MA_TCW_WEB_SCHEDULE_SUBJECT_DESC', '國民小學教學科目：(11科)<br>
國語;數學;社會;自然;音樂;體育;美勞;團體活動;輔導活動;鄉土教學;道德與健康<br>
國民中學教學科目：(21科) <br>
國文;英語;數學;歷史;地理;生物;理化;地球科學;健康教育;認識臺灣;公民與道德;家政與生活科技;電腦;體育;音樂;美術;童軍教育;鄉土藝術活動;輔導活動;團體活動;選修科目');

define('_MA_TCW_CO_ADMIN', '共同管理員');

define('_MA_TADWEB_PLUGIN_TITLE', '外掛名稱');
define('_MA_TADWEB_PLUGIN_TOTAL', '外掛資料數');
define('_MA_TCW_LAST_ACCESSED', '最後存取時間');
