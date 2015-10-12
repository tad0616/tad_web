CREATE TABLE `tad_web_schedule` (
  `ScheduleID` smallint(6) unsigned NOT NULL auto_increment COMMENT '課表編號',
  `CateID` smallint(6) unsigned NOT NULL default 0,
  `WebID` smallint(6) unsigned NOT NULL default 0 COMMENT '所屬班級',
  `ScheduleName` varchar(255) NOT NULL default '' COMMENT '課表名稱',
  `ScheduleDesc` text NOT NULL COMMENT '課表說明',
  `uid` mediumint(8) unsigned NOT NULL default 0 COMMENT '發布者',
  `ScheduleCount` smallint(6) unsigned NOT NULL default 0 COMMENT '人氣',
PRIMARY KEY (`ScheduleID`)
) ENGINE=MyISAM;


CREATE TABLE `tad_web_schedule_data` (
  `SDID` mediumint(8) unsigned NOT NULL auto_increment COMMENT '課表編號',
  `ScheduleID` smallint(6) unsigned NOT NULL  COMMENT '課表編號',
  `SDWeek` tinyint(3) unsigned NOT NULL default 0 COMMENT '星期幾',
  `SDSort` tinyint(3) unsigned NOT NULL default 0 COMMENT '第幾節',
  `Subject` varchar(255) NOT NULL default '' COMMENT '科目',
  `Teacher` varchar(255) NOT NULL default '' COMMENT '教師',
  `color` varchar(255)  NOT NULL default '' COMMENT '文字顏色',
  `bg_color` varchar(255)  NOT NULL default '' COMMENT '背景顏色',
PRIMARY KEY (`SDID`)
) ENGINE=MyISAM;
