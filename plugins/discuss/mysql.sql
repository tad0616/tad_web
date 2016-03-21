CREATE TABLE `tad_web_discuss` (
  `DiscussID` smallint(6) unsigned NOT NULL auto_increment COMMENT '編號',
  `ReDiscussID` smallint(6) unsigned NOT NULL default 0 COMMENT '回覆編號',
  `CateID` smallint(6) unsigned NOT NULL default 0,
  `WebID` smallint(6) unsigned NOT NULL default 0 COMMENT '所屬班級',
  `uid` mediumint(8) unsigned NOT NULL default 0 COMMENT 'uid',
  `MemID` smallint(6) unsigned NOT NULL default 0 COMMENT '學生',
  `ParentID` smallint(6) unsigned NOT NULL default 0 COMMENT '家長',
  `MemName` varchar(255) NOT NULL default '' COMMENT '發布者姓名',
  `DiscussTitle` varchar(255) NOT NULL default '' COMMENT '標題',
  `DiscussContent` text NOT NULL COMMENT '內容',
  `DiscussDate` datetime NOT NULL default '0000-00-00 00:00:00' COMMENT '發布時間',
  `LastTime` datetime NOT NULL default '0000-00-00 00:00:00' COMMENT '最後發表時間',
  `DiscussCounter` smallint(6) unsigned NOT NULL default 0 COMMENT '人氣',
PRIMARY KEY (`DiscussID`)
) ENGINE=MyISAM;
