<?php
//需加入模組語系
define("_TAD_NEED_TADTOOLS", " 需要 tadtools 模組，可至<a href='http://www.tad0616.net/modules/tad_uploader/index.php?of_cat_sn=50' target='_blank'>Tad教材網</a>下載。");

define("_MD_TCW_HOME", "回總覽");
define("_MD_TCW_CLASS_HOME", "首頁");
define("_MD_TCW_ADMIN", "管理");
define("_MD_TCW_ADMIN_MEM", "管理成員");
define("_MD_TCW_CLEAR", "清除");
define("_MD_TCW_TOTAL", "共");
define("_MD_TCW_PEOPLE", "人");
define("_MD_TCW_TOOLS", "網站設定");
define("_MD_TCW_HEAD_TOOLS", "標題設定");
define("_MD_TCW_LOGO_TOOLS", "Logo 設定");
define("_MD_TCW_BG_TOOLS", "背景圖設定");
define("_MD_TCW_COLOR_TOOLS", "顏色設定");
define("_MD_TCW_BLOCK_TOOLS", "區塊設定");

//header.php
define("_MD_TCW_ALL_CLASS", "網站列表");
define("_MD_TCW_MY_CLASS", "關於我們");
define("_MD_TCW_ALL_WEB", "網頁列表");
define("_MD_TCW_ALL_WEB_TITLE", "正式名稱");
define("_MD_TCW_ALL_WEB_NAME", "網頁名稱");
define("_MD_TCW_ALL_WEB_COUNTER", "人氣");
define("_MD_TCW_ALL_WEB_OWNER", "擁有者");

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

//aboutus.php
define("_MD_TCW_ABOUTUS", "關於我們");
define("_MD_TCW_STUDENT_SETUP", "學生設定");
define("_MD_TCW_ABOUTUS_SHORT", "成員");
define("_MD_TCW_UID", "成員");
define("_MD_TCW_MEMSCHOOLORGAN", "學校職稱");
define("_MD_TCW_MEMEXPERTISES", "專長");
define("_MD_TCW_MEMURL", "個人網頁");
define("_MD_TCW_MEMTEAMORGAN", "團隊職稱");
define("_MD_TCW_MEMORDER", "排序");
define("_MD_TCW_MEMENABLE", "狀態");
define("_MD_TCW_UPLOAD_MY_PHOTO", "導師照片");
define("_MD_TCW_UPLOAD_MEM_PHOTO", "照片");
define("_MD_TCW_COMMING_SOON", "尚在整理中...");
define("_MD_TCW_CLASS_WEB_NAME", "班網名稱");
define("_MD_TCW_IMPORT", "匯入");
define("_MD_TCW_ADD_MEM", "我要手動新增學生");
define("_MD_TCW_MEM_NAME", "姓名");
define("_MD_TCW_MEM_NICKNAME", "暱稱");
define("_MD_TCW_MEM_BIRTHDAY", "生日");
define("_MD_TCW_MEM_SEX", "性別");
define("_MD_TCW_MEM_UNICODE", "學號");
define("_MD_TCW_MEM_NUM", "座號");
define("_MD_TCW_MEM_STATUS", "狀態");
define("_MD_TCW_MEM_SAVE_OK", "座位儲存完畢！");
define("_MD_TCW_AGAIN", "再抽一次...");
define("_MD_TCW_GOT_YOU", "就是你了！");
define("_MD_TCW_NUMBER", "號！");
define("_MD_TCW_SOMEBODY", "同學");
define("_MD_TCW_DEMO_NAME", "吳弘凱");
define("_MD_TCW_BOY", "男");
define("_MD_TCW_GIRL", "女");
define("_MD_TCW_IMPORT_OK", "匯入完成");
define("_MD_TCW_MEM_ENABLE", "在班上");
define("_MD_TCW_MEM_UNABLE", "不在班上");
define("_MD_TCW_OWNER_NAME", "老師姓名");
define("_MD_TCW_MEM_AMOUNT", "班級人數");
define("_MD_TCW_GET_SOMEONE", "抽幾個幸運兒吧！");
define("_MD_TCW_IMPORT_PREVIEW", "匯入結果預覽");
define("_MD_TCW_IMPORT_DESCRIPT", "
<ol>
  <li>若結果正確，直接按下方「匯入」按鈕即可。</li>
  <li>若有少部份錯誤，請線上修改。</li>
  <li>若有大量不正確，請重新修正Excel檔然後重新匯入。</li>
  <li>若有欄位是空白的，表示該欄位未填。</li>
  <li>若有下拉選單是空白，表示Excel檔中所輸入的值和規定的選項不符。</li>
</ol>");
define("_MD_TCW_IMPORT_EXCEL", "匯入學生資料Excel檔");
define("_MD_TCW_SELECT_TO_EDIT", "點選學生姓名可編輯該學生資料");
define("_MD_TCW_IMPORT_LINK", "
<ul>
  <li><a href='http://120.115.2.90/modules/student_data/' target='_blank'>臺南市老師專用取得學生資料匯入檔</a></li>
  <li><a href='import.xls'>學生資料Excel匯入檔範例</a></li>
  <li style='color:red;'>僅支援 xls 格式（csv、xlsx 均不支援）</li>
</ul>");

define("_MD_TCW_CLASS_SETUP", "班級名稱設定");
define("_MD_TCW_FCNCTION_SETUP", "功能設定");
define("_MD_TCW_SELECT_TO_CANCEL", "請將想要隱藏的功能打勾");
define("_MD_TCW_CLICK_TO_CHANG", "點選下圖以切換圖片，並可拖動上方底圖或logo圖，以調整喜歡的位置。");
define("_MD_TCW_GOOD_LOGO_SITE", "可從 <a href='http://www.qt86.com/random.php' target='_blank'>http://www.qt86.com/random.php</a> 線上製作 logo 圖。");

define("_MD_TCW_RAND_IMAGE", "回復成隨機背景");
define("_MD_TCW_BG_TOP", "上");
define("_MD_TCW_BG_CENTER", "中");
define("_MD_TCW_BG_BOTTOM", "下");
define("_MD_TCW_BG_POSITION", "圖片位置：");
define("_MD_TCW_MEM_UNAME", "學生帳號");
define("_MD_TCW_MEM_PASSWD", "學生密碼");
define("_MD_TCW_PLEASE_INPUT", "請輸入");
define("_MD_TCW_LOGIN", "登入");
define("_MD_TCW_HELLO", "您好！歡迎使用討論區，請注意禮貌喔！");
define("_MD_TCW_EXIT", "離開");

//news.php
define("_MD_TCW_NEWS", "最新消息");
define("_MD_TCW_NEWS_SHORT", "消息");
define("_MD_TCW_NEWSID", "編號");
define("_MD_TCW_NEWSTITLE", "消息標題");
define("_MD_TCW_NEWSCONTENT", "消息內容");
define("_MD_TCW_NEWSDATE", "發布日期");
define("_MD_TCW_TOCAL", "加到行事曆");
define("_MD_TCW_NEWSPLACE", "地點");
define("_MD_TCW_NEWSMASTER", "主持人");
define("_MD_TCW_NEWSURL", "相關連結");
define("_MD_TCW_NEWSKIND", "文章種類");
define("_MD_TCW_NEWSCOUNTER", "人氣");
define("_MD_TCW_NEWS_ADD", "發布消息");
define("_MD_TCW_NEWS_TO_CAL", "填上日期，即可將此消息加到行事曆中");
define("_MD_TCW_NEWS_NOT_EXIST", "該消息不存在");
define("_MD_TCW_NEWS_TEACHER", "講師");
define("_MD_TCW_NEWS_FILES", "相關附件");
define("_MD_TCW_NEWS_TODAY", "今日");
define("_MD_TCW_NEWS_PREV_MONTH", "上個月");
define("_MD_TCW_NEWS_NEXT_MONTH", "下個月");

//homework.php
define("_MD_TCW_HOMEWORK", "聯絡簿");
define("_MD_TCW_HOMEWORK_SHORT", "內容");
define("_MD_TCW_HOMEWORK_ADD", "寫聯絡簿");
define("_MD_TCW_HOMEWORK_DEFAULT", "
  <table class='table'>
  <tbody>
    <tr>
      <td><img alt='今日作業' src='" . XOOPS_URL . "/modules/tad_web/images/today_homework.png' /></td>
      <td><img alt='明日準備事項' src='" . XOOPS_URL . "/modules/tad_web/images/bring.png' /></td>
      <td><img alt='老師的叮嚀' src='" . XOOPS_URL . "/modules/tad_web/images/teacher_say.png' /></td>
    </tr>
    <tr>
      <td>
      <ol>
        <li>&nbsp;</li>
      </ol>
      </td>
      <td>
      <ol>
        <li>&nbsp;</li>
      </ol>
      </td>
      <td>&nbsp;</td>
    </tr>
  </tbody>
</table>");

//action.php
define("_MD_TCW_ACTION", "活動剪影");
define("_MD_TCW_ACTION_ADD", "上傳圖檔");
define("_MD_TCW_ACTION_SHORT", "活動");
define("_MD_TCW_ACTIONID", "活動編號");
define("_MD_TCW_ACTIONNAME", "活動名稱");
define("_MD_TCW_ACTIONDESC", "活動說明");
define("_MD_TCW_ACTIONDATE", "活動日期");
define("_MD_TCW_ACTIONPLACE", "活動地點");
define("_MD_TCW_ACTIONUID", "發布者");
define("_MD_TCW_ACTIONCOUNT", "人氣");
define("_MD_TCW_ACTION_UPLOAD", "上傳圖檔");

//discuss.php
define("_MD_TCW_DISCUSS", "留言簿");
define("_MD_TCW_DISCUSS_SHORT", "留言");
define("_MD_TCW_DISCUSSID", "編號");
define("_MD_TCW_REDISCUSSID", "回覆編號");
define("_MD_TCW_DISCUSS_UID", "發布者");
define("_MD_TCW_DISCUSSTITLE", "討論主題");
define("_MD_TCW_DISCUSSCONTENT", "討論內容");
define("_MD_TCW_DISCUSSDATE", "發布時間");
define("_MD_TCW_LASTTIME", "最後更新");
define("_MD_TCW_DISCUSSCOUNTER", "人氣");
define("_MD_TCW_LOGIN_TO_POST", "要先登入才能發表");
define("_MD_TCW_DISCUSS_SUBMIT", "送出討論");
define("_MD_TCW_DISCUSS_TO_REPLY", "我要回覆");
define("_MD_TCW_DISCUSS_REPLY", "回覆");
define("_MD_TCW_DISCUSS_ADD", "我要留言");

//files.php
define("_MD_TCW_FILES", "檔案下載");
define("_MD_TCW_FILES_ADD", "上傳檔案");
define("_MD_TCW_FILES_SHORT", "檔案");
define("_MD_TCW_FSN", "檔案流水號");
define("_MD_TCW_FILES_UID", "上傳者");
define("_MD_TCW_FILE_KIND", "分類");
define("_MD_TCW_FILE_DATE", "日期");
define("_MD_TCW_FILENAME", "檔名");
define("_MD_TCW_FILES_UPLOAD", "上傳檔案");

//link.php
define("_MD_TCW_LINK", "常用網站");
define("_MD_TCW_LINK_ADD", "新增連結");
define("_MD_TCW_LINK_SHORT", "網站");
define("_MD_TCW_LINKURL", "網站連結");
define("_MD_TCW_LINKID", "編號");
define("_MD_TCW_LINKTITLE", "網站名稱");
define("_MD_TCW_LINKDESC", "說明");
define("_MD_TCW_LINKSORT", "排序");
define("_MD_TCW_LINKUID", "發布者");
define("_MD_TCW_LINKCOUNTER", "人氣");
define("_MD_TCW_LINK_AUTO_GET", "自動抓取");

//video.php
define("_MD_TCW_VIDEO", "線上影片");
define("_MD_TCW_VIDEO_ADD", "發布影片");
define("_MD_TCW_VIDEO_SHORT", "影片");
define("_MD_TCW_VIDEOID", "影片編號");
define("_MD_TCW_VIDEONAME", "影片名稱");
define("_MD_TCW_VIDEODESC", "影片說明");
define("_MD_TCW_VIDEODATE", "發布日期");
define("_MD_TCW_VIDEOPLACE", "縮圖位置");
define("_MD_TCW_VIDEOUID", "發布者");
define("_MD_TCW_VIDEOCOUNT", "人氣");
define("_MD_TCW_VIDEOYOUTUBE", "Youtube 網址");
define("_MD_TCW_VIDEOCOUNTINFO", "共 %s 人觀看過");

//calendar.php
define("_MD_TCW_CALENDAR", "行事曆");
define("_MD_TCW_CALENDAR_TODAY", "今天");
define("_MD_TCW_CALENDAR_PREV_MONTH", "上個月");
define("_MD_TCW_CALENDAR_NEXT_MONTH", "下個月");

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

//action.php
define("_MD_TCW_WORKS", "作品分享");
define("_MD_TCW_WORKS_ADD", "上傳作品");
define("_MD_TCW_WORKS_SHORT", "作品");
define("_MD_TCW_WORKS_ID", "作品編號");
define("_MD_TCW_WORKS_DESC", "主題說明");
define("_MD_TCW_WORKS_DATE", "上傳日期");
define("_MD_TCW_WORKS_NAME", "作品主題");
define("_MD_TCW_WORKS_UPLOAD", "上傳作品");
define("_MD_TCW_WORKS_COUNT", "人氣");

define("_MD_TCW_NEW_CATE", "建立新分類");
define("_MD_TCW_SELECT_CATE", "選擇分類");
