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
    $sql    = "SELECT count(`WorksKind`) FROM " . $xoopsDB->prefix("tad_web_works");
    $result = $xoopsDB->query($sql);
    if (empty($result)) {
        return true;
    }

    return false;
}

function works_onUpdate1_go()
{
    global $xoopsDB;
    $sql = "ALTER TABLE " . $xoopsDB->prefix("tad_web_works") . " ADD `WorksKind` VARCHAR(255) NOT NULL DEFAULT '' COMMENT '上傳方式',
    ADD `WorksEnable` ENUM('1','0') NOT NULL DEFAULT '1' COMMENT '是否啟用'";
    $xoopsDB->queryF($sql);
    return true;
}

//新增作品分享表格
function works_onUpdate2_chk()
{
    global $xoopsDB;
    $sql    = "SELECT count(*) FROM " . $xoopsDB->prefix("tad_web_works_content");
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
      `WorksID` SMALLINT(5) UNSIGNED NOT NULL COMMENT '作品主題流水號',
      `MemID` SMALLINT(6) UNSIGNED NOT NULL DEFAULT 0,
      `MemName` VARCHAR(255) NOT NULL DEFAULT '' COMMENT '上傳者',
      `WebID` SMALLINT(6) UNSIGNED NOT NULL DEFAULT 0 COMMENT '所屬班級',
      `WorkDesc` TEXT NOT NULL COMMENT '作品說明',
      `UploadDate` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '上傳日期',
      `WorkScore` VARCHAR(255) NOT NULL DEFAULT '' COMMENT '分數',
      `WorkJudgment` TEXT NOT NULL COMMENT '評語',
      `all_files_sn` VARCHAR(255) NOT NULL DEFAULT '' COMMENT '檔案流水號',
    PRIMARY KEY (`WorksID`,`MemID`,`WebID`)
    ) ENGINE=MyISAM";
    $xoopsDB->queryF($sql);
}
