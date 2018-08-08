CREATE TABLE `tad_web_calendar` (
  `CalendarID` smallint(6) unsigned NOT NULL auto_increment COMMENT '行程編號',
  `CateID` smallint(6) unsigned NOT NULL default 0,
  `WebID` smallint(6) unsigned NOT NULL default 0 COMMENT '所屬班級',
  `CalendarName` varchar(255) NOT NULL default '' COMMENT '行程名稱',
  `CalendarType` varchar(255) NOT NULL default '' COMMENT '行程類型',
  `CalendarDesc` text NOT NULL COMMENT '行程說明',
  `CalendarDate` date NOT NULL COMMENT '行程日期',
  `uid` mediumint(8) unsigned NOT NULL default 0 COMMENT '發布者',
  `CalendarCount` smallint(6) unsigned NOT NULL default 0 COMMENT '人氣',
PRIMARY KEY (`CalendarID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
