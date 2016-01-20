CREATE TABLE `tad_web_works` (
  `WorksID` smallint(5) unsigned NOT NULL auto_increment COMMENT '作品主題流水號',
  `CateID` smallint(6) unsigned NOT NULL default 0,
  `WebID` smallint(6) unsigned NOT NULL default 0 COMMENT '所屬班級',
  `WorkName` varchar(255) NOT NULL default '' COMMENT '作品名稱',
  `WorkDesc` text NOT NULL COMMENT '作品說明',
  `uid` mediumint(8) unsigned NOT NULL default 0 COMMENT '建立者',
  `WorksDate` datetime NOT NULL default '0000-00-00 00:00:00' COMMENT '建立日期',
  `WorksCount` smallint(6) unsigned NOT NULL default 0 COMMENT '人氣',
  `WorksKind` varchar(255) NOT NULL default '' COMMENT '上傳方式',
  `WorksEnable` enum('1','0') NOT NULL default '1' COMMENT '是否啟用',
PRIMARY KEY (`WorksID`)
) ENGINE=MyISAM;


CREATE TABLE `tad_web_works_content` (
  `WorksID` smallint(5) unsigned NOT NULL COMMENT '作品主題流水號',
  `MemID` smallint(6) unsigned NOT NULL default 0,
  `MemName` varchar(255) NOT NULL default '' COMMENT '上傳者',
  `WebID` smallint(6) unsigned NOT NULL default 0 COMMENT '所屬班級',
  `WorkDesc` text NOT NULL COMMENT '作品說明',
  `UploadDate` datetime NOT NULL default '0000-00-00 00:00:00' COMMENT '上傳日期',
  `WorkScore` varchar(255) NOT NULL default '' COMMENT '分數',
  `WorkJudgment` text NOT NULL COMMENT '評語',
  `all_files_sn` varchar(255) NOT NULL default '' COMMENT '檔案流水號',
PRIMARY KEY (`WorksID`,`MemID`,`WebID`)
) ENGINE=MyISAM;

