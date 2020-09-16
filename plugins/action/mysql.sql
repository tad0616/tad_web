CREATE TABLE `tad_web_action` (
  `ActionID` smallint(6) unsigned NOT NULL auto_increment COMMENT '活動編號',
  `CateID` smallint(6) unsigned NOT NULL default 0,
  `WebID` smallint(6) unsigned NOT NULL default 0 COMMENT '所屬班級',
  `ActionName` varchar(255) NOT NULL default '' COMMENT '活動名稱',
  `ActionDesc` text NOT NULL COMMENT '活動說明',
  `ActionDate` date NOT NULL COMMENT '活動日期',
  `ActionPlace` varchar(255) NOT NULL default '' COMMENT '活動地點',
  `uid` mediumint(8) unsigned NOT NULL default 0 COMMENT '發布者',
  `ActionCount` smallint(6) unsigned NOT NULL default 0 COMMENT '人氣',
  `gphoto_link` varchar(1000) default '' COMMENT 'Google Photo共享相簿',
PRIMARY KEY (`ActionID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


CREATE TABLE `tad_web_action_gphotos` (
  `ActionID` smallint(6) unsigned NOT NULL default '0' COMMENT '相簿編號',
  `image_id` varchar(255) NOT NULL default '' COMMENT '相片ID',
  `image_width` smallint(6) unsigned NOT NULL default '0' COMMENT '相片寬度',
  `image_height` smallint(6) unsigned NOT NULL default '0' COMMENT '相片高度',
  `image_url` varchar(1000) NOT NULL default '' COMMENT '相片網址',
PRIMARY KEY `image_id_ActionID` (`image_id`, `ActionID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
