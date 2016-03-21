CREATE TABLE `tad_web_page` (
  `PageID` smallint(6) unsigned NOT NULL auto_increment COMMENT '文章編號',
  `CateID` smallint(6) unsigned NOT NULL default 0,
  `WebID` smallint(6) unsigned NOT NULL default 0 COMMENT '所屬班級',
  `PageTitle` varchar(255) NOT NULL default '' COMMENT '文章標題',
  `PageContent` longtext NOT NULL COMMENT '文章內容',
  `PageDate` datetime NOT NULL default '0000-00-00 00:00:00' COMMENT '發布日期',
  `uid` mediumint(8) unsigned NOT NULL default 0 COMMENT '發布者',
  `PageCount` smallint(6) unsigned NOT NULL default 0 COMMENT '人氣',
  `PageSort` smallint(6) unsigned NOT NULL default 0 COMMENT '排序',
PRIMARY KEY (`PageID`)
) ENGINE=MyISAM;
