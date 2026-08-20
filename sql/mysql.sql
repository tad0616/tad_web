CREATE TABLE `tad_web` (
  `WebID` smallint unsigned NOT NULL AUTO_INCREMENT COMMENT '編號',
  `CateID` smallint unsigned NOT NULL DEFAULT '0',
  `WebName` varchar(255) NOT NULL DEFAULT '' COMMENT '名稱',
  `WebSort` smallint unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `WebEnable` enum('1','0') NOT NULL DEFAULT '1' COMMENT '狀態',
  `WebCounter` smallint unsigned NOT NULL DEFAULT '0' COMMENT '人氣',
  `WebOwner` varchar(255) NOT NULL DEFAULT '' COMMENT '擁有者',
  `WebOwnerUid` mediumint unsigned NOT NULL DEFAULT '0' COMMENT '擁有者uid',
  `WebTitle` varchar(255) NOT NULL DEFAULT '' COMMENT '全銜',
  `CreatDate` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `WebYear` year NOT NULL DEFAULT '0000',
  `used_size` int unsigned NOT NULL DEFAULT '0' COMMENT '已使用空間',
  `last_accessed` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '最後被拜訪時間',
  PRIMARY KEY (`WebID`)
) ENGINE=MyISAM;


CREATE TABLE `tad_web_account` (
  `AccountID` smallint unsigned NOT NULL AUTO_INCREMENT COMMENT '帳目編號',
  `CateID` smallint unsigned NOT NULL DEFAULT '0' COMMENT '帳簿編號',
  `WebID` smallint unsigned NOT NULL DEFAULT '0' COMMENT '所屬班級',
  `AccountTitle` varchar(255) NOT NULL DEFAULT '' COMMENT '帳目名稱',
  `AccountDesc` text NOT NULL COMMENT '帳目備註',
  `AccountDate` date NOT NULL COMMENT '帳目日期',
  `AccountIncome` mediumint NOT NULL DEFAULT '0' COMMENT '收入',
  `AccountOutgoings` mediumint NOT NULL DEFAULT '0' COMMENT '支出',
  `uid` mediumint unsigned NOT NULL DEFAULT '0' COMMENT '紀錄者',
  `AccountCount` smallint unsigned NOT NULL DEFAULT '0' COMMENT '人氣',
  PRIMARY KEY (`AccountID`)
) ENGINE=MyISAM;


CREATE TABLE `tad_web_action` (
  `ActionID` smallint unsigned NOT NULL AUTO_INCREMENT COMMENT '活動編號',
  `CateID` smallint unsigned NOT NULL DEFAULT '0',
  `WebID` smallint unsigned NOT NULL DEFAULT '0' COMMENT '所屬班級',
  `ActionName` varchar(255) NOT NULL DEFAULT '' COMMENT '活動名稱',
  `ActionDesc` text NOT NULL COMMENT '活動說明',
  `ActionDate` date NOT NULL COMMENT '活動日期',
  `ActionPlace` varchar(255) NOT NULL DEFAULT '' COMMENT '活動地點',
  `uid` mediumint unsigned NOT NULL DEFAULT '0' COMMENT '發布者',
  `ActionCount` smallint unsigned NOT NULL DEFAULT '0' COMMENT '人氣',
  `gphoto_link` varchar(1000) DEFAULT '' COMMENT 'Google Photo共享相簿',
  PRIMARY KEY (`ActionID`)
) ENGINE=MyISAM;



CREATE TABLE `tad_web_action_gphotos` (
  `ActionID` smallint unsigned NOT NULL DEFAULT '0' COMMENT '相簿編號',
  `image_id` varchar(100) NOT NULL,
  `image_width` smallint unsigned NOT NULL DEFAULT '0' COMMENT '相片寬度',
  `image_height` smallint unsigned NOT NULL DEFAULT '0' COMMENT '相片高度',
  `image_url` varchar(1000) NOT NULL DEFAULT '' COMMENT '相片網址',
  `image_description` text NOT NULL COMMENT '相片說明',
  PRIMARY KEY (`image_id`,`ActionID`)
) ENGINE=MyISAM ;


CREATE TABLE `tad_web_assistant_post` (
  `plugin` varchar(50) NOT NULL COMMENT '所屬外掛',
  `ColName` varchar(100) NOT NULL DEFAULT '' COMMENT '欄位名稱',
  `ColSN` smallint unsigned NOT NULL AUTO_INCREMENT COMMENT '欄位編號',
  `CateID` smallint unsigned NOT NULL COMMENT '編號',
  `AssistantType` varchar(100) NOT NULL DEFAULT '' COMMENT '用戶種類',
  `AssistantID` mediumint unsigned NOT NULL DEFAULT '0' COMMENT '用戶ID',
  PRIMARY KEY (`plugin`,`ColName`,`ColSN`)
) ENGINE=MyISAM;


CREATE TABLE `tad_web_blocks` (
  `BlockID` int unsigned NOT NULL AUTO_INCREMENT COMMENT '區塊流水號',
  `BlockName` varchar(100) NOT NULL COMMENT '區塊名稱',
  `BlockCopy` tinyint NOT NULL COMMENT '區塊份數',
  `BlockTitle` varchar(255) NOT NULL COMMENT '區塊標題',
  `BlockContent` text NOT NULL COMMENT '區塊內容',
  `BlockEnable` enum('1','0') NOT NULL DEFAULT '1' COMMENT '狀態',
  `BlockConfig` text NOT NULL COMMENT '區塊設定值',
  `BlockPosition` varchar(255) NOT NULL COMMENT '區塊位置',
  `BlockSort` smallint unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `WebID` smallint unsigned NOT NULL DEFAULT '0' COMMENT '所屬網站',
  `plugin` varchar(50) NOT NULL COMMENT '所屬外掛',
  `ShareFrom` int unsigned NOT NULL COMMENT '分享自',
  PRIMARY KEY (`BlockID`),
  UNIQUE KEY `BlockName_BlockCopy_WebID_plugin` (`BlockName`,`BlockCopy`,`WebID`,`plugin`)
) ENGINE=MyISAM;


CREATE TABLE `tad_web_calendar` (
  `CalendarID` smallint unsigned NOT NULL AUTO_INCREMENT COMMENT '行程編號',
  `CateID` smallint unsigned NOT NULL DEFAULT '0',
  `WebID` smallint unsigned NOT NULL DEFAULT '0' COMMENT '所屬班級',
  `CalendarName` varchar(255) NOT NULL DEFAULT '' COMMENT '行程名稱',
  `CalendarType` varchar(255) NOT NULL DEFAULT '' COMMENT '行程類型',
  `CalendarDesc` text NOT NULL COMMENT '行程說明',
  `CalendarDate` date NOT NULL COMMENT '行程日期',
  `uid` mediumint unsigned NOT NULL DEFAULT '0' COMMENT '發布者',
  `CalendarCount` smallint unsigned NOT NULL DEFAULT '0' COMMENT '人氣',
  PRIMARY KEY (`CalendarID`)
) ENGINE=MyISAM;


CREATE TABLE `tad_web_cate` (
  `CateID` smallint unsigned NOT NULL AUTO_INCREMENT COMMENT '編號',
  `WebID` smallint unsigned NOT NULL DEFAULT '0' COMMENT '所屬網站',
  `CateName` varchar(255) NOT NULL DEFAULT '' COMMENT '名稱',
  `ColName` varchar(255) NOT NULL DEFAULT '' COMMENT '擁有者',
  `ColSN` mediumint unsigned NOT NULL DEFAULT '0' COMMENT '擁有者uid',
  `CateSort` smallint unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `CateEnable` enum('1','0') NOT NULL DEFAULT '1' COMMENT '狀態',
  `CateCounter` smallint unsigned NOT NULL DEFAULT '0' COMMENT '人氣',
  PRIMARY KEY (`CateID`)
) ENGINE=MyISAM;


CREATE TABLE `tad_web_cate_assistant` (
  `CateID` smallint unsigned NOT NULL COMMENT '編號',
  `AssistantType` varchar(100) NOT NULL DEFAULT '' COMMENT '用戶種類',
  `AssistantID` mediumint unsigned NOT NULL DEFAULT '0' COMMENT '用戶ID',
  `plugin` varchar(50) NOT NULL DEFAULT '',
  PRIMARY KEY (`CateID`,`AssistantType`,`AssistantID`,`plugin`)
) ENGINE=MyISAM;


CREATE TABLE `tad_web_config` (
  `ConfigName` varchar(100) NOT NULL DEFAULT '',
  `ConfigValue` text NOT NULL,
  `ConfigSort` smallint unsigned NOT NULL DEFAULT '0',
  `CateID` smallint unsigned NOT NULL DEFAULT '0',
  `WebID` smallint unsigned NOT NULL DEFAULT '0' COMMENT '所屬網站',
  PRIMARY KEY (`ConfigName`,`WebID`)
) ENGINE=MyISAM ;


CREATE TABLE `tad_web_discuss` (
  `DiscussID` smallint unsigned NOT NULL AUTO_INCREMENT COMMENT '編號',
  `ReDiscussID` smallint unsigned NOT NULL DEFAULT '0' COMMENT '回覆編號',
  `CateID` smallint unsigned NOT NULL DEFAULT '0',
  `WebID` smallint unsigned NOT NULL DEFAULT '0' COMMENT '所屬班級',
  `uid` mediumint unsigned NOT NULL DEFAULT '0' COMMENT 'uid',
  `MemID` smallint unsigned NOT NULL DEFAULT '0' COMMENT '學生',
  `ParentID` smallint unsigned NOT NULL DEFAULT '0' COMMENT '家長',
  `MemName` varchar(255) NOT NULL DEFAULT '' COMMENT '發布者姓名',
  `DiscussTitle` varchar(255) NOT NULL DEFAULT '' COMMENT '標題',
  `DiscussContent` text NOT NULL COMMENT '內容',
  `DiscussDate` datetime NOT NULL COMMENT '發布時間',
  `LastTime` datetime NOT NULL COMMENT '最後發表時間',
  `DiscussCounter` smallint unsigned NOT NULL DEFAULT '0' COMMENT '人氣',
  PRIMARY KEY (`DiscussID`)
) ENGINE=MyISAM;


CREATE TABLE `tad_web_files` (
  `fsn` smallint unsigned NOT NULL AUTO_INCREMENT COMMENT '檔案流水號',
  `CateID` smallint unsigned NOT NULL DEFAULT '0',
  `WebID` smallint unsigned NOT NULL DEFAULT '0' COMMENT '所屬班級',
  `uid` mediumint unsigned NOT NULL DEFAULT '0' COMMENT '上傳者',
  `file_date` datetime NOT NULL COMMENT '日期',
  `file_link` varchar(255) NOT NULL DEFAULT '' COMMENT '檔案連結',
  `file_description` varchar(255) NOT NULL DEFAULT '' COMMENT '檔案說明或檔名',
  PRIMARY KEY (`fsn`)
) ENGINE=MyISAM;


CREATE TABLE `tad_web_files_center` (
  `files_sn` int unsigned NOT NULL AUTO_INCREMENT COMMENT '檔案流水號',
  `col_name` varchar(50) NOT NULL DEFAULT '' COMMENT '欄位名稱',
  `col_sn` smallint unsigned NOT NULL DEFAULT '0' COMMENT '欄位編號',
  `sort` smallint unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `kind` enum('img','file') NOT NULL DEFAULT 'img' COMMENT '檔案種類',
  `file_name` varchar(255) NOT NULL DEFAULT '' COMMENT '檔案名稱',
  `file_type` varchar(255) NOT NULL DEFAULT '' COMMENT '檔案類型',
  `file_size` int unsigned NOT NULL DEFAULT '0' COMMENT '檔案大小',
  `description` text NOT NULL COMMENT '檔案說明',
  `counter` mediumint unsigned NOT NULL DEFAULT '0' COMMENT '下載人次',
  `original_filename` varchar(255) NOT NULL COMMENT '檔案名稱',
  `hash_filename` varchar(255) NOT NULL COMMENT '加密檔案名稱',
  `sub_dir` varchar(255) NOT NULL COMMENT '檔案子路徑',
  `upload_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '上傳時間',
  `uid` mediumint unsigned NOT NULL DEFAULT '0' COMMENT '上傳者',
  `tag` varchar(255) NOT NULL DEFAULT '' COMMENT '註記',
  PRIMARY KEY (`files_sn`),
  UNIQUE KEY `col_name` (`col_name`,`col_sn`,`sort`)
) ENGINE=MyISAM;


CREATE TABLE `tad_web_homework` (
  `HomeworkID` smallint unsigned NOT NULL AUTO_INCREMENT COMMENT '編號',
  `CateID` smallint unsigned NOT NULL DEFAULT '0',
  `WebID` smallint unsigned NOT NULL DEFAULT '0' COMMENT '所屬班級',
  `HomeworkTitle` varchar(255) NOT NULL DEFAULT '' COMMENT '標題',
  `HomeworkContent` text NOT NULL COMMENT '內容',
  `HomeworkDate` datetime NOT NULL COMMENT '發布日期',
  `toCal` date NOT NULL COMMENT '加到行事曆',
  `HomeworkCounter` smallint unsigned NOT NULL DEFAULT '0' COMMENT '人氣',
  `uid` mediumint unsigned NOT NULL DEFAULT '0' COMMENT '發布者',
  `HomeworkPostDate` datetime NOT NULL COMMENT '顯示日期',
  PRIMARY KEY (`HomeworkID`)
) ENGINE=MyISAM;


CREATE TABLE `tad_web_homework_content` (
  `HomeworkID` smallint unsigned NOT NULL COMMENT '編號',
  `HomeworkCol` varchar(100) NOT NULL DEFAULT '' COMMENT '欄位',
  `Content` text NOT NULL COMMENT '內容',
  `WebID` smallint unsigned NOT NULL DEFAULT '0' COMMENT '所屬班級',
  PRIMARY KEY (`HomeworkID`,`HomeworkCol`)
) ENGINE=MyISAM;


CREATE TABLE `tad_web_link` (
  `LinkID` smallint unsigned NOT NULL AUTO_INCREMENT COMMENT '編號',
  `CateID` smallint unsigned NOT NULL DEFAULT '0',
  `WebID` smallint unsigned NOT NULL DEFAULT '0' COMMENT '所屬班級',
  `LinkTitle` varchar(255) NOT NULL DEFAULT '' COMMENT '網站名稱',
  `LinkDesc` text NOT NULL COMMENT '說明',
  `LinkUrl` varchar(255) NOT NULL DEFAULT '' COMMENT '網站連結',
  `LinkCounter` smallint unsigned NOT NULL DEFAULT '0' COMMENT '人氣',
  `LinkSort` tinyint unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `uid` mediumint unsigned NOT NULL DEFAULT '0' COMMENT '發布者',
  PRIMARY KEY (`LinkID`)
) ENGINE=MyISAM;


CREATE TABLE `tad_web_link_mems` (
  `MemID` mediumint unsigned NOT NULL DEFAULT '0' COMMENT 'MemID',
  `WebID` smallint unsigned NOT NULL DEFAULT '0' COMMENT '所屬網站',
  `CateID` smallint unsigned NOT NULL DEFAULT '0' COMMENT '所屬班級',
  `MemNum` tinyint unsigned NOT NULL DEFAULT '0' COMMENT '座號',
  `MemSort` smallint unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `MemEnable` enum('1','0') NOT NULL DEFAULT '1' COMMENT '狀態',
  `MemClassOrgan` varchar(255) NOT NULL DEFAULT '' COMMENT '職稱',
  `AboutMem` text NOT NULL COMMENT '介紹',
  `top` smallint NOT NULL DEFAULT '0',
  `left` smallint NOT NULL DEFAULT '0',
  PRIMARY KEY (`MemID`,`CateID`)
) ENGINE=MyISAM;


CREATE TABLE `tad_web_mail_log` (
  `ColName` varchar(100) NOT NULL DEFAULT '' COMMENT '欄位名稱',
  `ColSN` smallint unsigned NOT NULL AUTO_INCREMENT COMMENT '欄位編號',
  `WebID` smallint unsigned NOT NULL COMMENT '所屬網站',
  `Mail` varchar(100) NOT NULL DEFAULT '' COMMENT '信箱',
  `MailDate` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '寄信日期',
  PRIMARY KEY (`ColName`,`ColSN`,`WebID`,`Mail`)
) ENGINE=MyISAM;


CREATE TABLE `tad_web_mem_parents` (
  `ParentID` mediumint unsigned NOT NULL AUTO_INCREMENT COMMENT 'ParentID',
  `MemID` mediumint unsigned NOT NULL COMMENT 'MemID',
  `Reationship` varchar(255) NOT NULL DEFAULT '' COMMENT '關係',
  `ParentEmail` varchar(100) NOT NULL,
  `ParentPasswd` varchar(255) NOT NULL DEFAULT '' COMMENT '密碼',
  `ParentEnable` enum('1','0') NOT NULL DEFAULT '1' COMMENT '啟用狀態',
  `code` varchar(255) NOT NULL DEFAULT '' COMMENT '啟用碼',
  PRIMARY KEY (`ParentID`),
  UNIQUE KEY `MemID_ParentEmail` (`MemID`,`ParentEmail`)
) ENGINE=MyISAM ;


CREATE TABLE `tad_web_mems` (
  `MemID` mediumint unsigned NOT NULL AUTO_INCREMENT COMMENT 'MemID',
  `MemName` varchar(255) NOT NULL DEFAULT '' COMMENT '學生姓名',
  `MemNickName` varchar(255) NOT NULL DEFAULT '' COMMENT '學生暱稱',
  `MemSex` enum('1','0') NOT NULL DEFAULT '1' COMMENT '性別',
  `MemUnicode` varchar(255) NOT NULL DEFAULT '' COMMENT '學號',
  `MemBirthday` date NOT NULL COMMENT '生日',
  `MemExpertises` varchar(255) NOT NULL DEFAULT '' COMMENT '專長',
  `uid` mediumint unsigned NOT NULL DEFAULT '0' COMMENT 'uid',
  `MemUname` varchar(255) NOT NULL DEFAULT '' COMMENT '帳號',
  `MemPasswd` varchar(255) NOT NULL DEFAULT '' COMMENT '密碼',
  PRIMARY KEY (`MemID`)
) ENGINE=MyISAM;


CREATE TABLE `tad_web_news` (
  `NewsID` smallint unsigned NOT NULL AUTO_INCREMENT COMMENT '編號',
  `CateID` smallint unsigned NOT NULL DEFAULT '0',
  `WebID` smallint unsigned NOT NULL DEFAULT '0' COMMENT '所屬班級',
  `NewsTitle` varchar(255) NOT NULL DEFAULT '' COMMENT '標題',
  `NewsContent` longtext NOT NULL COMMENT '內容',
  `NewsDate` datetime NOT NULL COMMENT '發布日期',
  `toCal` datetime NOT NULL COMMENT '加到行事曆',
  `NewsUrl` varchar(255) NOT NULL DEFAULT '' COMMENT '相關連結',
  `NewsCounter` smallint unsigned NOT NULL DEFAULT '0' COMMENT '人氣',
  `NewsEnable` enum('1','0') NOT NULL DEFAULT '1' COMMENT '狀態',
  `uid` mediumint unsigned NOT NULL DEFAULT '0' COMMENT '發布者',
  PRIMARY KEY (`NewsID`)
) ENGINE=MyISAM;


CREATE TABLE `tad_web_notice` (
  `NoticeID` smallint unsigned NOT NULL AUTO_INCREMENT COMMENT '通知編號',
  `NoticeTitle` varchar(255) NOT NULL DEFAULT '' COMMENT '通知標題',
  `NoticeContent` text NOT NULL COMMENT '通知內容',
  `NoticeWeb` text NOT NULL COMMENT '通知網站',
  `NoticeWho` varchar(255) NOT NULL DEFAULT '' COMMENT '通知對象',
  `NoticeDate` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '通知日期',
  PRIMARY KEY (`NoticeID`)
) ENGINE=MyISAM;


CREATE TABLE `tad_web_page` (
  `PageID` smallint unsigned NOT NULL AUTO_INCREMENT COMMENT '文章編號',
  `CateID` smallint unsigned NOT NULL DEFAULT '0',
  `WebID` smallint unsigned NOT NULL DEFAULT '0' COMMENT '所屬班級',
  `PageTitle` varchar(255) NOT NULL DEFAULT '' COMMENT '文章標題',
  `PageContent` longtext NOT NULL COMMENT '文章內容',
  `PageDate` datetime NOT NULL COMMENT '發布日期',
  `uid` mediumint unsigned NOT NULL DEFAULT '0' COMMENT '發布者',
  `PageCount` smallint unsigned NOT NULL DEFAULT '0' COMMENT '人氣',
  `PageSort` smallint unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `PageCSS` text NOT NULL COMMENT '文章樣式',
  PRIMARY KEY (`PageID`)
) ENGINE=MyISAM;


CREATE TABLE `tad_web_plugins` (
  `PluginDirname` varchar(100) NOT NULL COMMENT '目錄名稱',
  `PluginTitle` varchar(255) NOT NULL COMMENT '外掛名稱',
  `PluginSort` smallint unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `PluginEnable` enum('1','0') NOT NULL DEFAULT '1' COMMENT '狀態',
  `WebID` smallint unsigned NOT NULL DEFAULT '0' COMMENT '所屬網站',
  PRIMARY KEY (`PluginDirname`,`WebID`)
) ENGINE=MyISAM;


CREATE TABLE `tad_web_plugins_setup` (
  `WebID` smallint unsigned NOT NULL DEFAULT '0' COMMENT '所屬網站',
  `plugin` varchar(50) NOT NULL DEFAULT '' COMMENT '所屬外掛',
  `name` varchar(100) NOT NULL DEFAULT '' COMMENT '設定名稱',
  `type` varchar(255) NOT NULL DEFAULT '' COMMENT '欄位類型',
  `value` text NOT NULL COMMENT '設定值',
  PRIMARY KEY (`WebID`,`plugin`,`name`)
) ENGINE=MyISAM;


CREATE TABLE `tad_web_power` (
  `WebID` smallint unsigned NOT NULL DEFAULT '0' COMMENT '所屬網站',
  `col_name` varchar(50) NOT NULL DEFAULT '' COMMENT '權限名稱',
  `col_sn` mediumint unsigned NOT NULL DEFAULT '0' COMMENT '對應編號',
  `power_name` varchar(50) NOT NULL DEFAULT '' COMMENT '權限名稱',
  `power_val` varchar(255) NOT NULL COMMENT '權限設定',
  `plugin` varchar(50) NOT NULL COMMENT '針對外掛',
  PRIMARY KEY (`col_name`,`col_sn`,`power_name`,`plugin`)
) ENGINE=MyISAM;


CREATE TABLE `tad_web_roles` (
  `uid` mediumint unsigned NOT NULL DEFAULT '0' COMMENT '使用者',
  `role` varchar(100) NOT NULL COMMENT '角色',
  `term` date NOT NULL COMMENT '期限',
  `enable` enum('1','0') NOT NULL DEFAULT '1' COMMENT '狀態',
  `WebID` smallint unsigned NOT NULL DEFAULT '0' COMMENT '所屬網站',
  PRIMARY KEY (`WebID`,`uid`,`role`)
) ENGINE=MyISAM;


CREATE TABLE `tad_web_schedule` (
  `ScheduleID` smallint unsigned NOT NULL AUTO_INCREMENT COMMENT '課表編號',
  `CateID` smallint unsigned NOT NULL DEFAULT '0',
  `WebID` smallint unsigned NOT NULL DEFAULT '0' COMMENT '所屬班級',
  `ScheduleName` varchar(255) NOT NULL DEFAULT '' COMMENT '課表名稱',
  `ScheduleDisplay` enum('0','1') NOT NULL DEFAULT '0' COMMENT '預設課表',
  `uid` mediumint unsigned NOT NULL DEFAULT '0' COMMENT '發布者',
  `ScheduleCount` smallint unsigned NOT NULL DEFAULT '0' COMMENT '人氣',
  `ScheduleTime` datetime NOT NULL COMMENT '發布日期',
  PRIMARY KEY (`ScheduleID`)
) ENGINE=MyISAM;


CREATE TABLE `tad_web_schedule_data` (
  `ScheduleID` smallint unsigned NOT NULL COMMENT '課表編號',
  `SDWeek` tinyint unsigned NOT NULL DEFAULT '0' COMMENT '星期幾',
  `SDSort` tinyint unsigned NOT NULL DEFAULT '0' COMMENT '第幾節',
  `Subject` varchar(255) NOT NULL DEFAULT '' COMMENT '科目',
  `Teacher` varchar(255) NOT NULL DEFAULT '' COMMENT '教師',
  `Link` varchar(1000) NOT NULL DEFAULT '' COMMENT '連結',
  `color` varchar(255) NOT NULL DEFAULT '' COMMENT '文字顏色',
  `bg_color` varchar(255) NOT NULL DEFAULT '' COMMENT '背景顏色',
  PRIMARY KEY (`ScheduleID`,`SDWeek`,`SDSort`)
) ENGINE=MyISAM;


CREATE TABLE `tad_web_tags` (
  `WebID` smallint unsigned NOT NULL COMMENT '所屬網站',
  `col_name` varchar(50) NOT NULL DEFAULT '' COMMENT '權限名稱',
  `col_sn` mediumint unsigned NOT NULL DEFAULT '0' COMMENT '對應編號',
  `tag_name` varchar(50) NOT NULL DEFAULT '' COMMENT '權限名稱',
  PRIMARY KEY (`col_name`,`col_sn`,`tag_name`)
) ENGINE=MyISAM;


CREATE TABLE `tad_web_video` (
  `VideoID` smallint unsigned NOT NULL AUTO_INCREMENT COMMENT '影片編號',
  `CateID` smallint unsigned NOT NULL DEFAULT '0',
  `WebID` smallint unsigned NOT NULL DEFAULT '0' COMMENT '所屬班級',
  `VideoName` varchar(255) NOT NULL DEFAULT '' COMMENT '影片名稱',
  `VideoDesc` text NOT NULL COMMENT '影片說明',
  `VideoDate` date NOT NULL COMMENT '影片日期',
  `VideoPlace` varchar(255) NOT NULL DEFAULT '' COMMENT '影片地點',
  `uid` mediumint unsigned NOT NULL DEFAULT '0' COMMENT '發布者',
  `VideoCount` smallint unsigned NOT NULL DEFAULT '0' COMMENT '人氣',
  `Youtube` varchar(255) NOT NULL DEFAULT '' COMMENT 'Youtube 位址',
  `VideoSort` smallint unsigned NOT NULL DEFAULT '0' COMMENT '影片排序',
  PRIMARY KEY (`VideoID`)
) ENGINE=MyISAM;


CREATE TABLE `tad_web_works` (
  `WorksID` smallint unsigned NOT NULL AUTO_INCREMENT COMMENT '作品主題流水號',
  `CateID` smallint unsigned NOT NULL DEFAULT '0',
  `WebID` smallint unsigned NOT NULL DEFAULT '0' COMMENT '所屬班級',
  `WorkName` varchar(255) NOT NULL DEFAULT '' COMMENT '作品名稱',
  `WorkDesc` text NOT NULL COMMENT '作品說明',
  `uid` mediumint unsigned NOT NULL DEFAULT '0' COMMENT '建立者',
  `WorksDate` datetime NOT NULL COMMENT '建立日期',
  `WorksCount` smallint unsigned NOT NULL DEFAULT '0' COMMENT '人氣',
  `WorksKind` varchar(255) NOT NULL DEFAULT '' COMMENT '上傳方式',
  `WorksEnable` enum('1','0') NOT NULL DEFAULT '1' COMMENT '是否啟用',
  PRIMARY KEY (`WorksID`)
) ENGINE=MyISAM;


CREATE TABLE `tad_web_works_content` (
  `WorksID` smallint unsigned NOT NULL COMMENT '作品主題流水號',
  `MemID` smallint unsigned NOT NULL DEFAULT '0',
  `MemName` varchar(255) NOT NULL DEFAULT '' COMMENT '上傳者',
  `WebID` smallint unsigned NOT NULL DEFAULT '0' COMMENT '所屬班級',
  `WorkDesc` text NOT NULL COMMENT '作品說明',
  `UploadDate` datetime NOT NULL COMMENT '上傳日期',
  `WorkScore` varchar(255) NOT NULL DEFAULT '' COMMENT '分數',
  `WorkJudgment` text NOT NULL COMMENT '評語',
  `all_files_sn` varchar(255) NOT NULL DEFAULT '' COMMENT '檔案流水號',
  PRIMARY KEY (`WorksID`,`MemID`,`WebID`)
) ENGINE=MyISAM;
