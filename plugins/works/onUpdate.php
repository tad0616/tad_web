<?php

if (works_onUpdate1_chk()) {
    works_onUpdate1_go();
}

if (works_onUpdate2_chk()) {
    works_onUpdate2_go();
}

//修改欄位
function works_onUpdate1_chk()
{
    global $xoopsDB;
    $sql    = "select count(`WorksKind`) from " . $xoopsDB->prefix("tad_web_works");
    $result = $xoopsDB->query($sql);
    if (empty($result)) {
        return true;
    }

    return false;
}

function works_onUpdate1_go()
{
    global $xoopsDB;
    $sql = "ALTER TABLE " . $xoopsDB->prefix("tad_web_works") . " ADD `WorksKind` varchar(255) NOT NULL default '' COMMENT '上傳方式',
    ADD `WorksEnable` enum('1','0') NOT NULL default '1' COMMENT '是否啟用'";
    $xoopsDB->queryF($sql);
    return true;
}

//新增作品分享表格
function works_onUpdate2_chk()
{
    global $xoopsDB;
    $sql    = "select count(*) from " . $xoopsDB->prefix("tad_web_works_content");
    $result = $xoopsDB->query($sql);
    if (empty($result)) {
        return true;
    }

    return false;
}

function works_onUpdate2_go()
{
    global $xoopsDB;
    $sql = "CREATE TABLE `" . $xoopsDB->prefix("tad_web_works_content") . "` (
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
    ) ENGINE=MyISAM";
    $xoopsDB->queryF($sql);
}
