CREATE TABLE `tad_web_homework` (
  `HomeworkID` smallint(6) unsigned NOT NULL auto_increment COMMENT '編號',
  `CateID` smallint(6) unsigned NOT NULL default 0,
  `WebID` smallint(6) unsigned NOT NULL default 0 COMMENT '所屬班級',
  `HomeworkTitle` varchar(255) NOT NULL default '' COMMENT '標題',
  `HomeworkContent` text NOT NULL COMMENT '內容',
  `HomeworkDate` datetime NOT NULL COMMENT '發布日期',
  `toCal` date NOT NULL COMMENT '加到行事曆',
  `HomeworkCounter` smallint(6) unsigned NOT NULL default 0 COMMENT '人氣',
  `uid` mediumint(8) unsigned NOT NULL default 0 COMMENT '發布者',
  `HomeworkPostDate` datetime NOT NULL COMMENT '顯示日期',
PRIMARY KEY (`HomeworkID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE `tad_web_homework_content` (
  `HomeworkID` smallint(6) unsigned NOT NULL COMMENT '編號',
  `HomeworkCol` varchar(100) NOT NULL default '' COMMENT '欄位',
  `Content` text NOT NULL COMMENT '內容',
  `WebID` smallint(6) unsigned NOT NULL default 0 COMMENT '所屬班級',
PRIMARY KEY (`HomeworkID`,`HomeworkCol`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
