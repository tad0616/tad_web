CREATE TABLE `tad_web_video` (
  `VideoID` smallint(6) unsigned NOT NULL AUTO_INCREMENT COMMENT '影片編號',
  `CateID` smallint(6) unsigned NOT NULL default 0,
  `WebID` smallint(6) unsigned NOT NULL default 0 COMMENT '所屬班級',
  `VideoName` varchar(255) NOT NULL default '' COMMENT '影片名稱',
  `VideoDesc` text NOT NULL COMMENT '影片說明',
  `VideoDate` date NOT NULL default '0000-00-00' COMMENT '影片日期',
  `VideoPlace` varchar(255) NOT NULL default '' COMMENT '影片地點',
  `uid` mediumint(8) unsigned NOT NULL default 0 COMMENT '發布者',
  `VideoCount` smallint(6) unsigned NOT NULL default 0 COMMENT '人氣',
  `Youtube` varchar(255) NOT NULL default '' COMMENT 'Youtube 位址',
  PRIMARY KEY (`VideoID`)
) ENGINE=MyISAM;
