CREATE TABLE `tad_web_menu` (
  `MenuID` smallint(6) unsigned NOT NULL auto_increment COMMENT '選項編號',
  `ParentMenuID` smallint(6) unsigned NOT NULL COMMENT '選項編號',
  `WebID` smallint(6) unsigned NOT NULL default 0 COMMENT '所屬班級',
  `MenuTitle` varchar(255) NOT NULL default '' COMMENT '選項名稱',
  `Plugin` varchar(255) NOT NULL COMMENT '對應外掛',
  `CateID` smallint(6) unsigned NOT NULL default 0,
  `ColName` varchar(255) NOT NULL default '' COMMENT '對應欄位',
  `ColSn` smallint(6) unsigned NOT NULL default 0 COMMENT '對應編號',
  `Link` varchar(500) NOT NULL default '' COMMENT '外部連結',
  `Target` enum('_self','_blank') NOT NULL default '_self' COMMENT '目標',
  `Icon` varchar(255) NOT NULL default '' COMMENT '圖示',
  `Color` varchar(255) NOT NULL default '' COMMENT '顏色',
  `BgColor` varchar(255) NOT NULL default '' COMMENT '底色',
  `Status` enum('1','0') NOT NULL default '1' COMMENT '使用狀態',
  `Sort` smallint(6) unsigned NOT NULL default 0 COMMENT '順序',
  `MenuCount` smallint(6) unsigned NOT NULL default 0 COMMENT '人氣',
PRIMARY KEY (`MenuID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
