CREATE TABLE `tad_web_works` (
  `WorksID` smallint(5) unsigned NOT NULL auto_increment COMMENT '檔案流水號',
  `CateID` smallint(6) unsigned NOT NULL default 0,
  `WebID` smallint(6) unsigned NOT NULL default 0 COMMENT '所屬班級',
  `WorkName` varchar(255) NOT NULL default '' COMMENT '活動名稱',
  `WorkDesc` text NOT NULL COMMENT '活動說明',
  `uid` mediumint(8) unsigned NOT NULL default 0 COMMENT '上傳者',
  `WorksDate` datetime NOT NULL default '0000-00-00 00:00:00' COMMENT '日期',
  `WorksCount` smallint(6) unsigned NOT NULL default 0 COMMENT '人氣',
PRIMARY KEY (`WorksID`)
) ENGINE=MyISAM;
