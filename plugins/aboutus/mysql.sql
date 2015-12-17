CREATE TABLE `tad_web_link_mems` (
  `MemID` mediumint(8) unsigned NOT NULL default 0 COMMENT 'MemID',
  `WebID` smallint(6) unsigned NOT NULL default 0 COMMENT '所屬網站',
  `CateID` smallint(6) unsigned NOT NULL default 0 COMMENT '所屬班級',
  `MemNum` tinyint(3) unsigned NOT NULL default 0 COMMENT '座號',
  `MemSort` smallint(6) unsigned NOT NULL default 0 COMMENT '排序',
  `MemEnable` enum('1','0') NOT NULL default '1' COMMENT '狀態',
  `top` smallint(6) NOT NULL default 0,
  `left` smallint(6) NOT NULL default 0,
PRIMARY KEY (`MemID`,`CateID`)
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
  `uid` mediumint(8) unsigned NOT NULL default 0 COMMENT 'uid',
  `MemUname` varchar(255) NOT NULL DEFAULT '' COMMENT '帳號',
  `MemPasswd` varchar(255) NOT NULL DEFAULT '' COMMENT '密碼',
  PRIMARY KEY `uid` (`MemID`)
) ENGINE=MyISAM;
