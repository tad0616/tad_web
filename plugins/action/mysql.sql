CREATE TABLE `tad_web_action` (
  `ActionID` smallint(6) unsigned NOT NULL auto_increment COMMENT '活動編號',
  `CateID` smallint(6) unsigned NOT NULL default 0,
  `WebID` smallint(6) unsigned NOT NULL default 0 COMMENT '所屬班級',
  `ActionName` varchar(255) NOT NULL default '' COMMENT '活動名稱',
  `ActionDesc` text NOT NULL COMMENT '活動說明',
  `ActionDate` date NOT NULL COMMENT '活動日期',
  `ActionPlace` varchar(255) NOT NULL default '' COMMENT '活動地點',
  `uid` mediumint(8) unsigned NOT NULL default 0 COMMENT '發布者',
  `ActionCount` smallint(6) unsigned NOT NULL default 0 COMMENT '人氣',
PRIMARY KEY (`ActionID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
