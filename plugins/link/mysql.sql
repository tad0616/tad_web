CREATE TABLE `tad_web_link` (
  `LinkID` smallint(6) unsigned NOT NULL auto_increment COMMENT '編號',
  `CateID` smallint(6) unsigned NOT NULL default 0,
  `WebID` smallint(6) unsigned NOT NULL default 0 COMMENT '所屬班級',
  `LinkTitle` varchar(255) NOT NULL default '' COMMENT '網站名稱',
  `LinkDesc` text NOT NULL COMMENT '說明',
  `LinkUrl` varchar(255) NOT NULL default '' COMMENT '網站連結',
  `LinkCounter` smallint(6) unsigned NOT NULL default 0 COMMENT '人氣',
  `LinkSort` tinyint(3) unsigned NOT NULL default 0 COMMENT '排序',
  `uid` mediumint(8) unsigned NOT NULL default 0 COMMENT '發布者',
PRIMARY KEY (`LinkID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
