CREATE TABLE `tad_web` (
  `WebID` smallint(6) unsigned NOT NULL AUTO_INCREMENT COMMENT '編號',
  `CateID` smallint(6) unsigned NOT NULL default 0,
  `WebName` varchar(255) NOT NULL default '' COMMENT '名稱',
  `WebSort` smallint(6) unsigned NOT NULL default 0 COMMENT '排序',
  `WebEnable` enum('1','0') NOT NULL default '1' COMMENT '狀態',
  `WebCounter` smallint(6) unsigned NOT NULL default 0 COMMENT '人氣',
  `WebOwner` varchar(255) NOT NULL default '' COMMENT '擁有者',
  `WebOwnerUid` mediumint(8) unsigned NOT NULL default 0 COMMENT '擁有者uid',
  `WebTitle` varchar(255) NOT NULL default '' COMMENT '全銜',
  `CreatDate` datetime NOT NULL default '0000-00-00 00:00:00',
  `WebYear` year(4) NOT NULL default '0000',
  `used_size` int(10) unsigned NOT NULL default 0 COMMENT '已使用空間',
  `last_accessed` datetime NOT NULL default '0000-00-00 00:00:00' COMMENT '最後被拜訪時間',
  PRIMARY KEY (`WebID`)
) ENGINE=MyISAM;


CREATE TABLE `tad_web_cate` (
  `CateID` smallint(6) unsigned NOT NULL AUTO_INCREMENT COMMENT '編號',
  `WebID` smallint(6) unsigned NOT NULL default 0 COMMENT '所屬網站',
  `CateName` varchar(255) NOT NULL default '' COMMENT '名稱',
  `ColName` varchar(255) NOT NULL default '' COMMENT '擁有者',
  `ColSN` mediumint(8) unsigned NOT NULL default 0 COMMENT '擁有者uid',
  `CateSort` smallint(6) unsigned NOT NULL default 0 COMMENT '排序',
  `CateEnable` enum('1','0') NOT NULL default '1' COMMENT '狀態',
  `CateCounter` smallint(6) unsigned NOT NULL default 0 COMMENT '人氣',
  PRIMARY KEY (`CateID`)
) ENGINE=MyISAM;

CREATE TABLE `tad_web_cate_assistant` (
  `CateID` smallint(6) unsigned NOT NULL COMMENT '編號',
  `AssistantType` varchar(100) NOT NULL default '' COMMENT '用戶種類',
  `AssistantID` mediumint(8) unsigned NOT NULL default 0 COMMENT '用戶ID',
  PRIMARY KEY (`CateID`,`AssistantType`,`AssistantID`)
) ENGINE=MyISAM;

CREATE TABLE `tad_web_assistant_post` (
  `plugin` varchar(100) NOT NULL COMMENT '所屬外掛',
  `ColName` varchar(100) NOT NULL default '' COMMENT '欄位名稱',
  `ColSN` smallint(5) unsigned NOT NULL AUTO_INCREMENT COMMENT '欄位編號',
  `CateID` smallint(6) unsigned NOT NULL COMMENT '編號',
  `AssistantType` varchar(100) NOT NULL default '' COMMENT '用戶種類',
  `AssistantID` mediumint(8) unsigned NOT NULL default 0 COMMENT '用戶ID',
  PRIMARY KEY (`plugin`,`ColName`,`ColSN`)
) ENGINE=MyISAM;

CREATE TABLE `tad_web_config` (
  `ConfigName` VARCHAR(100) NOT NULL default '',
  `ConfigValue` TEXT NOT NULL,
  `ConfigSort` SMALLINT UNSIGNED NOT NULL default 0,
  `CateID` SMALLINT UNSIGNED NOT NULL default 0,
  `WebID` smallint(6) unsigned NOT NULL default 0 COMMENT '所屬網站',
  PRIMARY KEY (`ConfigName`,`WebID`)
) ENGINE = MYISAM ;

CREATE TABLE `tad_web_files_center` (
  `files_sn` int(10) unsigned NOT NULL auto_increment COMMENT '檔案流水號',
  `col_name` varchar(255) NOT NULL default '' COMMENT '欄位名稱',
  `col_sn` smallint(5) unsigned NOT NULL default 0 COMMENT '欄位編號',
  `sort` smallint(5) unsigned NOT NULL default 0 COMMENT '排序',
  `kind` enum('img','file') NOT NULL default 'img' COMMENT '檔案種類',
  `file_name` varchar(255) NOT NULL default '' COMMENT '檔案名稱',
  `file_type` varchar(255) NOT NULL default '' COMMENT '檔案類型',
  `file_size` int(10) unsigned NOT NULL default 0 COMMENT '檔案大小',
  `description` text NOT NULL COMMENT '檔案說明',
  `counter` mediumint(8) unsigned NOT NULL default 0 COMMENT '下載人次',
  `original_filename` varchar(255) NOT NULL COMMENT '檔案名稱',
  `hash_filename` varchar(255) NOT NULL COMMENT '加密檔案名稱',
  `sub_dir` varchar(255) NOT NULL COMMENT '檔案子路徑',
  PRIMARY KEY (`files_sn`),
  UNIQUE KEY `col_name` (`col_name`,`col_sn`,`sort`)
) ENGINE=MyISAM;


CREATE TABLE `tad_web_plugins` (
  `PluginDirname` varchar(100) NOT NULL COMMENT '目錄名稱',
  `PluginTitle` varchar(255) NOT NULL COMMENT '外掛名稱',
  `PluginSort` smallint(6) unsigned NOT NULL default 0 COMMENT '排序',
  `PluginEnable` enum('1','0') NOT NULL default '1' COMMENT '狀態',
  `WebID` smallint(6) unsigned NOT NULL default 0 COMMENT '所屬網站',
PRIMARY KEY (`PluginDirname`,`WebID`)
) ENGINE=MyISAM;

CREATE TABLE `tad_web_roles` (
  `uid` mediumint(8) unsigned NOT NULL default 0 COMMENT '使用者',
  `role` varchar(255) NOT NULL COMMENT '角色',
  `term` date  NOT NULL default '0000-00-00' COMMENT '期限',
  `enable` enum('1','0') NOT NULL default '1' COMMENT '狀態',
  `WebID` smallint(6) unsigned NOT NULL default 0 COMMENT '所屬網站',
PRIMARY KEY (`WebID`,`uid`,`role`)
) ENGINE=MyISAM;

CREATE TABLE `tad_web_blocks` (
  `BlockID` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '區塊流水號',
  `BlockName` varchar(100) NOT NULL COMMENT '區塊名稱',
  `BlockCopy` tinyint(3) NOT NULL COMMENT '區塊份數',
  `BlockTitle` varchar(255) NOT NULL COMMENT '區塊標題',
  `BlockContent` text NOT NULL COMMENT '區塊內容',
  `BlockEnable` enum('1','0') NOT NULL default '1' COMMENT '狀態',
  `BlockConfig` text NOT NULL default '' COMMENT '區塊設定值',
  `BlockPosition` varchar(255) NOT NULL COMMENT '區塊位置',
  `BlockSort` smallint(6) unsigned NOT NULL default 0 COMMENT '排序',
  `WebID` smallint(6) unsigned NOT NULL default 0 COMMENT '所屬網站',
  `plugin` varchar(100) NOT NULL COMMENT '所屬外掛',
  `ShareFrom` int(10) unsigned NOT NULL COMMENT '分享自',
  PRIMARY KEY (`BlockID`),
  UNIQUE KEY `BlockName_BlockCopy_WebID_plugin` (`BlockName`,`BlockCopy`,`WebID`,`plugin`)
) ENGINE=MyISAM;


CREATE TABLE `tad_web_plugins_setup` (
  `WebID` smallint(5) unsigned NOT NULL default 0 COMMENT '所屬網站',
  `plugin` varchar(100) NOT NULL default '' COMMENT '所屬外掛',
  `name` varchar(100) NOT NULL default '' COMMENT '設定名稱',
  `type` varchar(255) NOT NULL default '' COMMENT '欄位類型',
  `value` text NOT NULL COMMENT '設定值',
  PRIMARY KEY  (`WebID`,`plugin`,`name`)
)  ENGINE=MyISAM;



CREATE TABLE `tad_web_power` (
  `WebID` smallint(5) unsigned NOT NULL default 0 COMMENT '所屬網站',
  `col_name` varchar(100) NOT NULL default '' COMMENT '權限名稱',
  `col_sn` mediumint(8) unsigned NOT NULL default 0 COMMENT '對應編號',
  `power_name` varchar(100) NOT NULL default '' COMMENT '權限名稱',
  `power_val` varchar(255) NOT NULL COMMENT '權限設定',
  PRIMARY KEY (`col_name`,`col_sn`,`power_name`)
)  ENGINE=MyISAM;



CREATE TABLE `tad_web_tags` (
  `WebID` smallint(5) unsigned NOT NULL  COMMENT '所屬網站',
  `col_name` varchar(100) NOT NULL default '' COMMENT '權限名稱',
  `col_sn` mediumint(8) unsigned NOT NULL default 0 COMMENT '對應編號',
  `tag_name` varchar(100) NOT NULL default '' COMMENT '權限名稱',
  PRIMARY KEY  (`col_name`,`col_sn`,`tag_name`)
)  ENGINE=MyISAM;


CREATE TABLE `tad_web_notice` (
  `NoticeID` smallint(5) unsigned NOT NULL AUTO_INCREMENT COMMENT '通知編號',
  `NoticeTitle` varchar(255) NOT NULL default '' COMMENT '通知標題',
  `NoticeContent` text NOT NULL  COMMENT '通知內容',
  `NoticeWeb` text NOT NULL COMMENT '通知網站',
  `NoticeWho` varchar(255) NOT NULL default '' COMMENT '通知對象',
  `NoticeDate` datetime NOT NULL default '0000-00-00 00:00:00' COMMENT '通知日期',
  PRIMARY KEY  (`NoticeID`)
)  ENGINE=MyISAM;

CREATE TABLE `tad_web_mail_log` (
  `ColName` varchar(100) NOT NULL default '' COMMENT '欄位名稱',
  `ColSN` smallint(5) unsigned NOT NULL AUTO_INCREMENT COMMENT '欄位編號',
  `WebID` smallint(5) unsigned NOT NULL  COMMENT '所屬網站',
  `Mail` varchar(100) NOT NULL default '' COMMENT '信箱',
  `MailDate` datetime NOT NULL default '0000-00-00 00:00:00' COMMENT '寄信日期',
  PRIMARY KEY  (`ColName`,`ColSN`,`WebID`,`Mail`)
)  ENGINE=MyISAM;