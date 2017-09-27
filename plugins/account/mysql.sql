CREATE TABLE `tad_web_account` (
  `AccountID` smallint(6) unsigned NOT NULL auto_increment COMMENT '帳目編號',
  `CateID` smallint(6) unsigned NOT NULL default 0 COMMENT '帳簿編號',
  `WebID` smallint(6) unsigned NOT NULL default 0 COMMENT '所屬班級',
  `AccountTitle` varchar(255) NOT NULL default '' COMMENT '帳目名稱',
  `AccountDesc` text NOT NULL COMMENT '帳目備註',
  `AccountDate` date NOT NULL default '0000-00-00' COMMENT '帳目日期',
  `AccountIncome` mediumint(8) NOT NULL default 0 COMMENT '收入',
  `AccountOutgoings` mediumint(8) NOT NULL default 0 COMMENT '支出',
  `uid` mediumint(8) unsigned NOT NULL default 0 COMMENT '紀錄者',
  `AccountCount` smallint(6) unsigned NOT NULL default 0 COMMENT '人氣',
PRIMARY KEY (`AccountID`)
) ENGINE=MyISAM;
