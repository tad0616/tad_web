<?php
include_once '../../tadtools/language/' . $xoopsConfig['language'] . '/admin_common.php';

//main.php
define("_MA_TCW_TEAMID", "編號");
define("_MA_TCW_TEAMNAME", "網站名稱");
define("_MA_TCW_TEAMSORT", "排序");
define("_MA_TCW_TEAMENABLE", "狀態");
define("_MA_TCW_TEAMCOUNTER", "人氣");
define("_MA_TCW_MEM_AMOUNT", "成員人數");
define("_MA_TCW_TEAMLEADER", "擁有者");
define("_MA_TCW_TEAMTITLE", "正式名稱");
define("_MA_TCW_MAIN_TITLE", "個人網頁管理");
define("_MA_TCW_GROUP_NAME", "XOOPS個人網頁群組");
define("_MA_TCW_GROUP_DESC", "請勿修改群組名稱，否則會造成模組運作不正確。");
define("_MA_TCW_CREATE_BY_USER", "批次新增");
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
define("_MA_TCW_ORDER_BY_TEAMTITLE", "依正式名稱排序");

//save_sort.php save.php
define("_MA_TCW_UPDATE_FAIL", "更新失敗！");
define("_MA_TCW_SAVE_SORT_OK", "排序完成！ ");
define("_MA_TCW_NEED_TAD_WEB_THEME", "<ul><li style='line-height:2;'>本模組需要搭配 <a href='http://120.115.2.90/modules/tad_modules/index.php?module_sn=77' target='_blank'>for_tad_web_theme 佈景</a>。</li><li style='line-height:2;'>該佈景只需要解壓縮放到 themes 目錄底下即可，無須至偏好設定選用。</li><li style='line-height:2;'>亦可<a href='" . XOOPS_URL . "/modules/tad_adm/admin/main.php'>從站長工具箱直接安裝</a></li></ul>");

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
