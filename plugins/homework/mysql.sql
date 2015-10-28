CREATE TABLE `tad_web_homework` (
  `HomeworkID` smallint(6) unsigned NOT NULL auto_increment COMMENT '編號',
  `CateID` smallint(6) unsigned NOT NULL default 0,
  `WebID` smallint(6) unsigned NOT NULL default 0 COMMENT '所屬班級',
  `HomeworkTitle` varchar(255) NOT NULL default '' COMMENT '標題',
  `HomeworkContent` text NOT NULL COMMENT '內容',
  `HomeworkDate` datetime NOT NULL default '0000-00-00 00:00:00' COMMENT '發布日期',
  `toCal` date NOT NULL default '0000-00-00' COMMENT '加到行事曆',
  `HomeworkCounter` smallint(6) unsigned NOT NULL default 0 COMMENT '人氣',
  `uid` mediumint(8) unsigned NOT NULL default 0 COMMENT '發布者',
  `HomeworkPostDate` datetime NOT NULL default '0000-00-00 00:00:00' COMMENT '顯示日期',
PRIMARY KEY (`HomeworkID`)
) ENGINE=MyISAM;
