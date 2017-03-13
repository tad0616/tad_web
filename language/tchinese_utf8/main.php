<?php
//需加入模組語系
if (!defined('_TAD_NEED_TADTOOLS')) {
    define('_TAD_NEED_TADTOOLS', '需要 tadtools 模組，可至<a href="http://campus-xoops.tn.edu.tw/modules/tad_modules/index.php?module_sn=1" target="_blank">XOOPS輕鬆架</a>下載。');
}
define("_MD_TCW_HOME", "回總覽");
define("_MD_TCW_CLASS_HOME", "首頁");
define("_MD_TCW_ADMIN", "管理");
define("_MD_TCW_CLEAR", "清除");
define("_MD_TCW_TOTAL", "共");
define("_MD_TCW_PEOPLE", "人");
define("_MD_TCW_TOOLS", "設定");
define("_MD_TCW_WEB_TOOLS", "網站設定");
define("_MD_TCW_PLUGIN_TOOLS", "功能設定");
define("_MD_TCW_ABOUT_PLUGIN_TOOLS", "<ul><li>底下項目可以直接拉動排序，拉動完即自動儲存。</li><li>排序僅會影響上方選單及選單區塊各功能項目的呈現順序。</li></ul>");
define("_MD_TCW_HEAD_TOOLS", "標題設定");
define("_MD_TCW_LOGO_TOOLS", "Logo 設定");
define("_MD_TCW_BG_TOOLS", "背景圖設定");
define("_MD_TCW_COLOR_TOOLS", "顏色設定");
define("_MD_TCW_BLOCK_TOOLS", "區塊設定");
define("_MD_TCW_BLOCK_TITLE", "區塊標題");
define("_MD_TCW_BLOCK_SHOW_TITLE", "是否顯示區塊標題？");
define("_MD_TCW_BLOCK_ENABLE", "是否啟用此區塊？");
define("_MD_TCW_DATA_NOT_EXIST", "該資訊不存在");
define("_MD_TCW_WEB_NOT_EXIST", "該網站不存在");
define("_MD_TCW_SAVED", "已儲存");
define("_MD_TCW_DEL_WEB", "刪除此網站");
define("_MD_TCW_DEL_WEB_DESC", "刪除後，會將該網站的所有資料從資料庫移除，並刪除所有上傳的檔案。一旦刪除就無法復原，請慎重執行之。");
define("_MD_TCW_WILL_DEL", "將刪除以下資料：");
define("_MD_TCW_PLUGIN_TOTAL", "資料數");
define("_MD_TCW_DELETE", "確定刪除（不刪就按別的連結離開即可）");

//header.php
define("_MD_TCW_ALL_CLASS", "網站列表");
define("_MD_TCW_MY_CLASS", "關於我們");
define("_MD_TCW_ALL_WEB", "網頁列表");

//common
define("_MD_TCW_TEAMID", "所屬網頁");
define("_MD_TCW_NOT_OWNER", "非網頁擁有者，無法使用此功能。");
define("_MD_TCW_AUTO_TO_HOME", "自動轉至您所屬的網頁，以使用新增功能。");
define("_MD_TCW_NEED_LOGIN", "登入後才能觀看完整內容");
define("_MD_TCW_EMPTY", "尚無資料");
define("_MD_TCW_MORE", "更多");
define("_MD_TADWEB_SELECT_TO_DEL", "編輯檔案：選取欲刪除的檔案：");
define("_MD_TCW_INFO", "%s 於 %s 發布，已有 %s 人次閱讀過");
define("_MD_TCW_MKPIC_ERROR", "無法建立GD圖片");
define("_MD_TCW_EMPTY_TITLE", "無標題");

define("_MD_TCW_WEB_NAME_SETUP", "網站名稱設定");
define("_MD_TCW_FCNCTION_SETUP", "功能設定");
define("_MD_TCW_SELECT_TO_CANCEL", "請將想要隱藏的功能打勾");
define("_MD_TCW_CLICK_TO_CHANG", "點選下圖以切換圖片，並可拖動上方底圖或logo圖，以調整喜歡的位置。");
define("_MD_TCW_GOOD_LOGO_SITE", "<ol><li>可從 <a href='http://www.qt86.com/random.php' target='_blank'>http://www.qt86.com/random.php</a> 線上製作 logo 圖。</li><li>若 logo 圖拖曳到看不見，<a href='config.php?WebID=%s&op=reset_logo'>可點此恢復其預設位置</a>。</li></ol>");
define("_MD_TCW_GOOD_BG_SITE", "<ol><li>可從 <a href='https://pixabay.com/' target='_blank'>https://pixabay.com/</a> 下載可合法使用的精美圖片來作為標題圖。</li><li>若標題圖拖曳到看不見，<a href='config.php?WebID=%s&op=reset_head'>可點此恢復其預設位置</a>。</li></ol>");

define("_MD_TCW_RAND_IMAGE", "回復成隨機背景");
define("_MD_TCW_BG_TOP", "上");
define("_MD_TCW_BG_CENTER", "中");
define("_MD_TCW_BG_BOTTOM", "下");
define("_MD_TCW_LOGIN", "登入");
define("_MD_TCW_HELLO", "您好！");
define("_MD_TCW_EXIT", "離開");

define("_MD_TCW_CONFIG_NONE", "無");
define("_MD_TCW_CONFIG_HEAD", "標題底圖");
define("_MD_TCW_CONFIG_NONE_BG", "僅使用底色");
define("_MD_TCW_CONTAINER_BG_COLOR", "主內容區底色");
define("_MD_TCW_MAIN_BG_COLOR", "網站底色");
define("_MD_TCW_MAIN_DEFAULT_COLOR", "原本顏色");
define("_MD_TCW_MAIN_NAV_COLOR", "導覽列文字顏色");
define("_MD_TCW_MAIN_NAV_TOP_COLOR", "導覽列底色");
define("_MD_TCW_MAIN_NAV_HOVER_COLOR", "滑鼠移過導覽列文字顏色");
define("_MD_TCW_MAIN_NAV_HOVER_BG_COLOR", "滑鼠移過導覽列底色");

define("_MD_TCW_BLOCKS_LIST", "可選用區塊");
define("_MD_TCW_BLOCKS_SELECTED", "已選用區塊");
define("_MD_TCW_BLOCKS_SETUP", "設定區塊");

define("_MD_TCW_NEW_CATE", "建立新分類");
define("_MD_TCW_NEW_SOMETHING", "建立新%s");
define("_MD_TCW_SELECT_CATE", "選擇分類");
define("_MD_TCW_SELECT_PLUGIN_CATE", "選擇%s分類");
define("_MD_TCW_CATE_TOOLS", "分類管理");
define("_MD_TCW_DEL_CATE_MOVE_TO", "刪除分類並將底下資料移至：");
define("_MD_TCW_DEL_CATE_ALL", "刪除分類（含底下所有資料）");
define("_MD_TCW_UNABLE_CATE", "停用此分類");
define("_MD_TCW_ENABLE_CATE", "啟用此分類");
define("_MD_TCW_CATE_NAME", "原始名稱");
define("_MD_TCW_CATE_NEW_NAME", "新名稱或目的地");
define("_MD_TCW_CATE_ACT", "欲執行動作");
define("_MD_TCW_CATE_NONE_OPT", "無動作");
define("_MD_TCW_CATE_NONE", "尚無分類");
define("_MD_TCW_CATE_MODIFY", "修改分類名稱為：");
define("_MD_TCW_CATE_MOVE", "搬移分類底下資料至：");
define("_MD_TCW_DEL_CATE_ALL_ALERT", "我確定要刪除整個指定分類以及底下所有資料。");
define("_MD_TCW_OTHER_CLASS_SETUP", "請連到我另一個班網網址：");
define("_MD_TCW_CATE_POWER", "設定分類權限(開發中)");

define("_MD_TCW_CATE_PLUGIN_ENABLE", "是否啟用");
define("_MD_TCW_CATE_PLUGIN_TITLE", "功能名稱");
define("_MD_TCW_CATE_PLUGIN_NEW_NAME", "自訂名稱");
define("_MD_TCW_CATE_PLUGIN_IN_FRONTPAGE", "在首頁顯示/資料數");
define("_MD_TCW_ADD", "新增");
define("_MD_TCW_ADMIN_SETUP", "共同管理員設定");
define("_MD_TCW_USER_LIST", "所有使用者");
define("_MD_TCW_USER_SELECTED", "已設為共同管理員");
define("_MD_TCW_DEFAULT_ADMIN", "目前網頁預設管理員：");

define("_MD_TCW_THEME_TOOLS", "佈景設定");
define("_MD_TCW_THEME_TOOLS_THEME_SIDE", "側邊欄的位置：");
define("_MD_TCW_THEME_TOOLS_THEME_SIDE_LEFT", "左邊");
define("_MD_TCW_THEME_TOOLS_THEME_SIDE_NONE", "不顯示");
define("_MD_TCW_THEME_TOOLS_THEME_SIDE_RIGHT", "右邊");
define("_MD_TCW_THEME_TOOLS_FONT_SIZE", "選單字型大小：");
define("_MD_TCW_SUN", "日");
define("_MD_TCW_MON", "一");
define("_MD_TCW_TUE", "二");
define("_MD_TCW_WED", "三");
define("_MD_TCW_THU", "四");
define("_MD_TCW_FRI", "五");
define("_MD_TCW_SAT", "六");

$dir = XOOPS_ROOT_PATH . "/modules/tad_web/plugins/";
if (is_dir($dir)) {
    if ($dh = opendir($dir)) {
        while (($file = readdir($dh)) !== false) {
            if (filetype($dir . $file) == "dir") {
                if (substr($file, 0, 1) == '.') {
                    continue;
                }
                include_once XOOPS_ROOT_PATH . "/modules/tad_web/plugins/{$file}/langs/tchinese_utf8.php";
            }
        }
        closedir($dh);
    }
}
define("_MD_TCW_BLOCK_LIMIT", "設定要顯示的資料數量");
define("_MD_TCW_WEBTITLE", "班級名稱");
define("_MD_TCW_WEBNAME", "網站名稱");

define("_MD_TCW_BLOCK_ADD", "新增自由區塊");
define("_MD_TCW_BLOCK_POSITION", "區塊呈現位置");
define("_MD_TCW_BLOCK_CONTENT", "區塊內容種類");
define("_MD_TCW_BLOCK_HTML", "直接輸入內容");
define("_MD_TCW_BLOCK_JS", "貼入JS或崁入語法");
define("_MD_TCW_BLOCK_JS_DESC", "請貼入JS或崁入語法");
define("_MD_TCW_BLOCK_IFRAME", "iframe 內崁網址");
define("_MD_TCW_BLOCK_IFRAME_DESC", "請貼上網址");
define("_MD_TCW_BLOCK_SHARE", "分享區塊");
define("_MD_TCW_BLOCK_SHARE_DESC", "若選是，別人也可以看見並使用此區塊。");
define("_MD_TCW_SIDE_BLOCK", "側邊區塊");
define("_MD_TCW_TOP_CENTER_BLOCK", "上中區塊");
define("_MD_TCW_TOP_LEFT_BLOCK", "上中左區塊");
define("_MD_TCW_TOP_RIGHT_BLOCK", "上中右區塊");
define("_MD_TCW_BOTTOM_CENTER_BLOCK", "下中區塊");
define("_MD_TCW_BOTTOM_LEFT_BLOCK", "下中左區塊");
define("_MD_TCW_BOTTOM_RIGHT_BLOCK", "下中右區塊");
define("_MD_TCW_UNINSTALL_BLOCK", "未安裝區塊");
define("_MD_TCW_NO_SPACE", "您已使用 %s MB的硬碟空間，已達上限 %s MB，無法再新增資料。");
define("_MD_TCW_MENU", "回到選單");
define("_MD_TCW_USE_BLOCK_SITE", "目前有 %s 個網站使用此區塊");
define("_MD_TCW_SIDE_BG_COLOR", "側邊欄底色");

define("_MD_TCW_CENTER_TEXT_COLOR", "主內容區文字顏色");
define("_MD_TCW_CENTER_LINK_COLOR", "主內容區連結顏色");
define("_MD_TCW_CENTER_HOVER_COLOR", "主內容區移至連結顏色");
define("_MD_TCW_SIDE_TEXT_COLOR", "側邊欄文字顏色");
define("_MD_TCW_SIDE_LINK_COLOR", "側邊欄連結顏色");
define("_MD_TCW_SIDE_HOVER_COLOR", "側邊欄移至連結顏色");
define("_MD_TCW_CENTER_HEADER_COLOR", "主內容區標題顏色");
define("_MD_TCW_CENTER_BORDER_COLOR", "主內容區標題邊框顏色");
define("_MD_TCW_SIDE_HEADER_COLOR", "側邊欄標題顏色");
define("_MD_TCW_SIDE_BORDER_COLOR", "側邊欄標題邊框顏色");
define("_MD_TCW_DEFAULT_COLOR", "恢復成系統預設值");
define("_MD_TCW_BLOCK_TITLE_PIC", "將區塊標題轉為圖片");
define("_MD_TCW_BLOCK_PIC_COLOR", "圖片文字顏色");
define("_MD_TCW_BLOCK_PIC_BORDER_COLOR", "圖片文字邊框顏色");
define("_MD_TCW_BLOCK_PIC_SIZE", "圖片文字大小");
define("_MD_TCW_BLOCK_PIC_FONT", "圖片文字字型");
define("_MD_TCW_BLOCK_PIC_FONT1", "【嵐】萌系一號");
define("_MD_TCW_BLOCK_PIC_FONT2", "（黑體）");
define("_MD_TCW_BLOCK_TITLE_USE_PIC", "使用區塊標題圖片取代文字");
define("_MD_TCW_SEARCH_RESULT", "搜尋結果");
define("_MD_TCW_READ_MORE", "（繼續閱讀...）");
define("_MD_TCW_PRINT", "友善列印");
define("_MD_TCW_THEME_TOOLS_DEFAULT_THEME", "套用版型");
define("_MD_TCW_CLOSE_WEB", "關閉此網站");
define("_MD_TCW_CLOSE_WEB_DESC", "關閉網站後，在網站列表中就看不到網站，直接輸入網址也會呈現網站關閉的訊息，唯有登入後，直接從選單才能再次啟動之。");
define("_MD_TCW_OPEN_WEB", "啟動此網站");
define("_MD_TCW_OPEN_WEB_DESC", "您的網站目前關閉中，要重新啟動之嗎？");
define("_MD_TCW_SETUP", "設定");

define("_MD_TCW_BG_REPEAT", "背景重複");
define("_MD_TCW_BG_REPEAT_NORMAL", "一般重複");
define("_MD_TCW_BG_REPEAT_X", "僅橫向重複");
define("_MD_TCW_BG_REPEAT_Y", "僅垂直重複");
define("_MD_TCW_BG_NO_REPEAT", "不重複");
define("_MD_TCW_BG_ATTACHMENT", "背景模式");
define("_MD_TCW_BG_ATTACHMENT_SCROLL", "隨畫面捲動");
define("_MD_TCW_BG_ATTACHMENT_FIXED", "固定不捲動");
define("_MD_TCW_BG_POSITION", "背景位置");
define("_MD_TCW_BG_POSITION_LT", "左上");
define("_MD_TCW_BG_POSITION_RT", "右上");
define("_MD_TCW_BG_POSITION_LB", "左下");
define("_MD_TCW_BG_POSITION_RB", "右下");
define("_MD_TCW_BG_POSITION_CC", "正中");
define("_MD_TCW_BG_POSITION_CT", "上中");
define("_MD_TCW_BG_POSITION_CB", "下中");
define("_MD_TCW_BG_SIZE", "背景尺寸");
define("_MD_TCW_BG_SIZE_NONE", "原尺寸");
define("_MD_TCW_BG_SIZE_COVER", "背景圖放大至畫面的大小");
define("_MD_TCW_BG_SIZE_CONTAIN", "背景圖縮小至畫面的大小");
define('_MD_TCW_MENU_BOOKS', '操作教學');
define('_MD_TCW_MENU_DISCUSS', '發問討論區');
define('_MD_TCW_MENU_SUGGEST', '功能許願池');
define('_MD_TCW_LINKTO', '連結');
define('_MD_TCW_ADMINER', '資料庫管理');
define('_MD_TCW_ADMINPAGE', '後台管理');

define('_MD_TCW_USE_FB_COMMENT_TEXT', '是否使用 FaceBook 留言框？');
define('_MD_TCW_USE_FB_COMMENT_DESC', '若是選「是」即可在單一資料的頁面下呈現FaceBook留言框互動工具');

define('_MD_TCW_POWER_FOR', '可閱讀對象');
define('_MD_TCW_POWER_FOR_ALL', '全部開放');
define('_MD_TCW_POWER_FOR_USERS', '僅登入者（含其他子網站）');
define('_MD_TCW_POWER_FOR_WEB_USERS', '僅本網站成員');
define('_MD_TCW_POWER_FOR_WEB_ADMIN', '僅本網站管理員');
define('_MD_TCW_NOW_READ_POWER', '沒有閱讀此內容的權限');
define('_MD_TCW_TAGS', '標籤設定');
define('_MD_TCW_TAG', '標籤');
define('_MD_TCW_TAGS_LIST', '所有標籤');
define('_MD_TCW_INPUT_TAGS', '請輸入新標籤，若有多個標籤請用小寫逗號「,」隔開');
define('_MD_TCW_UNABLE', '關閉中');

define('_MD_TCW_PLUGIN_MENU', '功能選單');
define('_MD_TCW_USER_SIMPLE_MENU', '簡化選單');

define('_MD_TCW_KEYWORD_TO_SELECT_USER', '輸入關鍵字以篩選');
define('_MD_TCW_SELETC_USER', '篩選');
define('_MD_TCW_LEADERBOARD', '排行榜');
define('_MD_TCW_LEADERBOARD_RANK', '綜合排名');
define('_MD_TCW_WEB_COUNTER', '網站人氣');
define('_MD_TCW_WEB_BLOCK', '區塊應用');
define("_MD_TCW_HI", "嗨！%s 您好！");
define('_MD_TCW_WEB_CLOSE_MENU', '關閉選單');
define('_MD_TCW_WEB_OPENID_SETUP', 'OpenID 登入設定');
define('_MD_TCW_CATE_ASSISTANT', '小幫手');
define('_MD_TCW_CATE_SET_ASSISTANT', '設定小幫手');

define('_MD_TCW_CATE_ENABLED', '啟用中');
define('_MD_TCW_CATE_UNABLED', '關閉中');
define('_MD_TCW_CATE_DATA_AMOUNT1', '（目前有 ');
define('_MD_TCW_CATE_DATA_AMOUNT2', ' 筆資料）');
define('_MD_TCW_BLOCK_EMPTY', '目前無內容，無法展示');
define('_MD_TCW_BLOCK_TITLE', '區塊標題');
define('_MD_TCW_BLOCK_DEMO', '區塊預覽');

define('_MD_TCW_UPDATE_MY_NAME', '管理者姓名');
