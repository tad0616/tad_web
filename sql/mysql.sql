CREATE TABLE `tad_web` (
  `WebID` smallint(6) unsigned NOT NULL AUTO_INCREMENT COMMENT '編號',
  `CateID` smallint(6) unsigned NOT NULL default 0,
  `WebName` varchar(255) NOT NULL default '' COMMENT '名稱',
  `WebSort` smallint(6) unsigned NOT NULL default 0 COMMENT '排序',
  `WebEnable` enum('1','0') NOT NULL default '1' COMMENT '狀態',
  `WebCounter` smallint(6) unsigned NOT NULL default 0 COMMENT '人氣',
  `WebOwner` varchar(255) NOT NULL default '' COMMENT '擁有者',
<<<<<<< HEAD
  `WebOwnerUid` mediumint(8) unsigned NOT NULL default 0 COMMENT '擁有者uid',
=======
  `WebOwnerUid` smallint(5) unsigned NOT NULL default 0 COMMENT '擁有者uid',
>>>>>>> 92b005c8e45495e772daa96fb67faed2bcdd7024
  `WebTitle` varchar(255) NOT NULL default '' COMMENT '全銜',
  `CreatDate` datetime NOT NULL default '0000-00-00 00:00:00',
  `WebYear` year(4) NOT NULL default '0000',
  PRIMARY KEY (`WebID`)
) ENGINE=MyISAM;

CREATE TABLE `tad_web_cate` (
  `CateID` smallint(6) unsigned NOT NULL AUTO_INCREMENT COMMENT '編號',
  `WebID` smallint(6) unsigned NOT NULL default 0 COMMENT '所屬班級',
  `CateName` tinyint(3) unsigned NOT NULL default 0 COMMENT '名稱',
  `ColName` varchar(255) NOT NULL default '' COMMENT '擁有者',
<<<<<<< HEAD
  `ColSN` mediumint(8) unsigned NOT NULL default 0 COMMENT '擁有者uid',
=======
  `ColSN` smallint(5) unsigned NOT NULL default 0 COMMENT '擁有者uid',
>>>>>>> 92b005c8e45495e772daa96fb67faed2bcdd7024
  `CateSort` smallint(6) unsigned NOT NULL default 0 COMMENT '排序',
  `CateEnable` enum('1','0') NOT NULL default '1' COMMENT '狀態',
  `CateCounter` smallint(6) unsigned NOT NULL default 0 COMMENT '人氣',
  PRIMARY KEY (`CateID`)
) ENGINE=MyISAM;

CREATE TABLE `tad_web_config` (
  `ConfigName` VARCHAR(100) NOT NULL default '',
  `ConfigValue` TEXT NOT NULL,
  `ConfigSort` SMALLINT UNSIGNED NOT NULL default 0,
  `CateID` SMALLINT UNSIGNED NOT NULL default 0,
  `WebID` smallint(6) unsigned NOT NULL default 0 COMMENT '所屬班級',
  PRIMARY KEY (`ConfigName`,`WebID`)
) ENGINE = MYISAM ;

CREATE TABLE `tad_web_link_mems` (
  `MemID` mediumint(8) unsigned NOT NULL COMMENT 'MemID',
  `WebID` smallint(6) unsigned NOT NULL default 0 COMMENT '所屬班級',
  `MemNum` tinyint(3) unsigned NOT NULL default 0 COMMENT '座號',
  `MemSort` smallint(6) unsigned NOT NULL default 0 COMMENT '排序',
  `MemEnable` enum('1','0') NOT NULL default '1' COMMENT '狀態',
  `top` smallint(6) NOT NULL default 0,
  `left` smallint(6) NOT NULL default 0,
PRIMARY KEY (`MemID`,`WebID`)
) ENGINE=MyISAM;


CREATE TABLE `tad_web_mems` (
  `MemID` mediumint(8) unsigned NOT NULL auto_increment COMMENT 'MemID',
  `MemName` varchar(255) NOT NULL DEFAULT '' COMMENT '學生姓名',
  `MemNickName` varchar(255) NOT NULL DEFAULT '' COMMENT '學生暱稱',
  `MemSex` enum('1','0') NOT NULL DEFAULT '1' COMMENT '性別',
  `MemUnicode` varchar(255) NOT NULL DEFAULT '' COMMENT '學號',
  `MemBirthday` date NOT NULL DEFAULT '0000-00-00' COMMENT '生日',
  `MemUrl` varchar(255) NOT NULL DEFAULT '' COMMENT '網址',
  `MemClassOrgan` varchar(255) NOT NULL DEFAULT '' COMMENT '職稱',
  `MemExpertises` varchar(255) NOT NULL DEFAULT '' COMMENT '專長',
<<<<<<< HEAD
  `uid` mediumint(8) unsigned NOT NULL default 0 COMMENT 'uid',
=======
  `uid` smallint(6) unsigned NOT NULL default 0 COMMENT 'uid',
>>>>>>> 92b005c8e45495e772daa96fb67faed2bcdd7024
  `MemUname` varchar(255) NOT NULL DEFAULT '' COMMENT '帳號',
  `MemPasswd` varchar(255) NOT NULL DEFAULT '' COMMENT '密碼',
  PRIMARY KEY `uid` (`MemID`)
) ENGINE=MyISAM;


CREATE TABLE `tad_web_link` (
  `LinkID` smallint(6) unsigned NOT NULL auto_increment COMMENT '編號',
  `CateID` smallint(6) unsigned NOT NULL default 0,
  `WebID` smallint(6) unsigned NOT NULL default 0 COMMENT '所屬班級',
  `LinkTitle` varchar(255) NOT NULL default '' COMMENT '網站名稱',
  `LinkDesc` text NOT NULL COMMENT '說明',
  `LinkUrl` varchar(255) NOT NULL default '' COMMENT '網站連結',
  `LinkCounter` smallint(6) unsigned NOT NULL default 0 COMMENT '人氣',
  `LinkSort` tinyint(3) unsigned NOT NULL default 0 COMMENT '排序',
<<<<<<< HEAD
  `uid` mediumint(8) unsigned NOT NULL default 0 COMMENT '發布者',
=======
  `uid` smallint(6) unsigned NOT NULL default 0 COMMENT '發布者',
>>>>>>> 92b005c8e45495e772daa96fb67faed2bcdd7024
PRIMARY KEY (`LinkID`)
) ENGINE=MyISAM;


CREATE TABLE `tad_web_news` (
  `NewsID` smallint(6) unsigned NOT NULL auto_increment COMMENT '編號',
  `CateID` smallint(6) unsigned NOT NULL default 0,
  `WebID` smallint(6) unsigned NOT NULL default 0 COMMENT '所屬班級',
  `NewsTitle` varchar(255) NOT NULL default '' COMMENT '標題',
  `NewsContent` text NOT NULL COMMENT '內容',
  `NewsDate` datetime NOT NULL default '0000-00-00 00:00:00' COMMENT '發布日期',
  `toCal` datetime NOT NULL default '0000-00-00 00:00:00' COMMENT '加到行事曆',
  `NewsPlace` varchar(255) NOT NULL default '' COMMENT '地點',
  `NewsMaster` varchar(255) NOT NULL default '' COMMENT '主持人',
  `NewsUrl` varchar(255) NOT NULL default '' COMMENT '相關連結',
  `NewsCounter` smallint(6) unsigned NOT NULL default 0 COMMENT '人氣',
  `NewsKind` varchar(255) NOT NULL default '' COMMENT '文章種類',
<<<<<<< HEAD
  `uid` mediumint(8) unsigned NOT NULL default 0 COMMENT '發布者',
=======
  `uid` smallint(6) unsigned NOT NULL default 0 COMMENT '發布者',
>>>>>>> 92b005c8e45495e772daa96fb67faed2bcdd7024
PRIMARY KEY (`NewsID`)
) ENGINE=MyISAM;


CREATE TABLE `tad_web_action` (
  `ActionID` smallint(6) unsigned NOT NULL auto_increment COMMENT '活動編號',
  `CateID` smallint(6) unsigned NOT NULL default 0,
  `WebID` smallint(6) unsigned NOT NULL default 0 COMMENT '所屬班級',
  `ActionName` varchar(255) NOT NULL default '' COMMENT '活動名稱',
  `ActionDesc` text NOT NULL COMMENT '活動說明',
  `ActionDate` date NOT NULL default '0000-00-00' COMMENT '活動日期',
  `ActionPlace` varchar(255) NOT NULL default '' COMMENT '活動地點',
<<<<<<< HEAD
  `uid` mediumint(8) unsigned NOT NULL default 0 COMMENT '發布者',
=======
  `uid` smallint(6) unsigned NOT NULL default 0 COMMENT '發布者',
>>>>>>> 92b005c8e45495e772daa96fb67faed2bcdd7024
  `ActionCount` smallint(6) unsigned NOT NULL default 0 COMMENT '人氣',
PRIMARY KEY (`ActionID`)
) ENGINE=MyISAM;

CREATE TABLE `tad_web_discuss` (
  `DiscussID` smallint(6) unsigned NOT NULL auto_increment COMMENT '編號',
  `ReDiscussID` smallint(6) unsigned NOT NULL default 0 COMMENT '回覆編號',
  `CateID` smallint(6) unsigned NOT NULL default 0,
  `WebID` smallint(6) unsigned NOT NULL default 0 COMMENT '所屬班級',
<<<<<<< HEAD
  `uid` mediumint(8) unsigned NOT NULL default 0 COMMENT 'uid',
=======
  `uid` smallint(6) unsigned NOT NULL default 0 COMMENT 'uid',
>>>>>>> 92b005c8e45495e772daa96fb67faed2bcdd7024
  `MemID` smallint(6) unsigned NOT NULL default 0 COMMENT '發布者',
  `MemName` varchar(255) NOT NULL default '' COMMENT '發布者姓名',
  `DiscussTitle` varchar(255) NOT NULL default '' COMMENT '標題',
  `DiscussContent` text NOT NULL COMMENT '內容',
  `DiscussDate` datetime NOT NULL default '0000-00-00 00:00:00' COMMENT '發布時間',
  `LastTime` datetime NOT NULL default '0000-00-00 00:00:00' COMMENT '最後發表時間',
  `DiscussCounter` smallint(6) unsigned NOT NULL default 0 COMMENT '人氣',
PRIMARY KEY (`DiscussID`)
) ENGINE=MyISAM;


CREATE TABLE `tad_web_files` (
  `fsn` smallint(5) unsigned NOT NULL auto_increment COMMENT '檔案流水號',
  `CateID` smallint(6) unsigned NOT NULL default 0,
  `WebID` smallint(6) unsigned NOT NULL default 0 COMMENT '所屬班級',
<<<<<<< HEAD
  `uid` mediumint(8) unsigned NOT NULL default 0 COMMENT '上傳者',
=======
  `uid` smallint(5) unsigned NOT NULL default 0 COMMENT '上傳者',
>>>>>>> 92b005c8e45495e772daa96fb67faed2bcdd7024
  `file_date` datetime NOT NULL default '0000-00-00 00:00:00' COMMENT '日期',
PRIMARY KEY (`fsn`)
) ENGINE=MyISAM;


CREATE TABLE `tad_web_files_center` (
  `files_sn` smallint(5) unsigned NOT NULL auto_increment COMMENT '檔案流水號',
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


CREATE TABLE `tad_web_video` (
  `VideoID` smallint(6) unsigned NOT NULL AUTO_INCREMENT COMMENT '影片編號',
  `CateID` smallint(6) unsigned NOT NULL default 0,
  `WebID` smallint(6) unsigned NOT NULL default 0 COMMENT '所屬班級',
  `VideoName` varchar(255) NOT NULL default '' COMMENT '影片名稱',
  `VideoDesc` text NOT NULL COMMENT '影片說明',
  `VideoDate` date NOT NULL default '0000-00-00' COMMENT '影片日期',
  `VideoPlace` varchar(255) NOT NULL default '' COMMENT '影片地點',
<<<<<<< HEAD
  `uid` mediumint(8) unsigned NOT NULL default 0 COMMENT '發布者',
=======
  `uid` smallint(6) unsigned NOT NULL default 0 COMMENT '發布者',
>>>>>>> 92b005c8e45495e772daa96fb67faed2bcdd7024
  `VideoCount` smallint(6) unsigned NOT NULL default 0 COMMENT '人氣',
  `Youtube` varchar(255) NOT NULL default '' COMMENT 'Youtube 位址',
  PRIMARY KEY (`VideoID`)
) ENGINE=MyISAM;

