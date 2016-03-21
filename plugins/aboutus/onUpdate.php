<?php

if (aboutus_onUpdate1_chk()) {
    aboutus_onUpdate1_go();
}

if (aboutus_onUpdate2_chk()) {
    aboutus_onUpdate2_go();
}

if (aboutus_onUpdate3_chk()) {
    aboutus_onUpdate3_go();
}

//修改欄位名稱
function aboutus_onUpdate1_chk()
{
    global $xoopsDB;
    $sql    = "select count(`CateID`) from " . $xoopsDB->prefix("tad_web_link_mems");
    $result = $xoopsDB->query($sql);
    if (empty($result)) {
        return true;
    }

    return false;
}

function aboutus_onUpdate1_go()
{
    global $xoopsDB;
    $sql = "ALTER TABLE " . $xoopsDB->prefix("tad_web_link_mems") . " ADD `CateID` smallint(6) unsigned NOT NULL default 0 AFTER `WebID`";
    $xoopsDB->queryF($sql) or web_error($sql);

    $year = get_seme_year();
    include_once XOOPS_ROOT_PATH . '/modules/tad_web/class/cate.php';

    $sql    = "select WebID,WebTitle from `" . $xoopsDB->prefix("tad_web") . "` group by `WebID`";
    $result = $xoopsDB->queryF($sql) or web_error($sql);
    while (list($WebID, $WebTitle) = $xoopsDB->fetchRow($result)) {
        $web_cate = new web_cate($WebID, "aboutus", "tad_web_link_mems");
        $CateID   = $web_cate->save_tad_web_cate('', sprintf(_MD_TCW_SEME_CATE, $year) . " {$WebTitle}");

        $sql = "update " . $xoopsDB->prefix("tad_web_link_mems") . " set CateID='{$CateID}' where WebID='{$WebID}'";
        $xoopsDB->queryF($sql) or web_error($sql);
    }

    $sql = "ALTER TABLE " . $xoopsDB->prefix("tad_web_link_mems") . " ADD PRIMARY KEY `MemID_CateID` (`MemID`, `CateID`) , DROP INDEX `PRIMARY`";
    $xoopsDB->queryF($sql) or web_error($sql);

    return true;
}

//取得目前的學年
function get_seme_year()
{
    $y = date("Y");
    $m = date("n");
    if ($m >= 8) {
        $year = $y - 1911;
    } elseif ($m >= 2) {
        $year = $y - 1912;
    } else {
        $year = $y - 1912;
    }
    return $year;
}

//修改欄位名稱
function aboutus_onUpdate2_chk()
{
    global $xoopsDB;
    $sql    = "select count(`MemClassOrgan`) from " . $xoopsDB->prefix("tad_web_link_mems");
    $result = $xoopsDB->query($sql);
    if (empty($result)) {
        return true;
    }

    return false;
}

function aboutus_onUpdate2_go()
{
    global $xoopsDB;
    $sql = "ALTER TABLE " . $xoopsDB->prefix("tad_web_link_mems") . " ADD `MemClassOrgan` varchar(255) NOT NULL DEFAULT '' COMMENT '職稱' , ADD `AboutMem` text NOT NULL DEFAULT '' COMMENT '介紹'";
    $xoopsDB->queryF($sql) or web_error($sql);

    $sql = "ALTER TABLE " . $xoopsDB->prefix("tad_web_mems") . " DROP `MemUrl`, DROP `MemClassOrgan`";
    $xoopsDB->queryF($sql) or web_error($sql);

    return true;
}

//新增家長表格
function aboutus_onUpdate3_chk()
{
    global $xoopsDB;
    $sql    = "select count(*) from " . $xoopsDB->prefix("tad_web_mem_parents");
    $result = $xoopsDB->query($sql);
    if (empty($result)) {
        return true;
    }

    return false;
}

function aboutus_onUpdate3_go()
{
    global $xoopsDB;
    $sql = "CREATE TABLE `" . $xoopsDB->prefix("tad_web_mem_parents") . "` (
      `ParentID` mediumint(8) unsigned NOT NULL auto_increment COMMENT 'ParentID',
      `MemID` mediumint(8) unsigned NOT NULL COMMENT 'MemID',
      `Reationship` varchar(255) NOT NULL DEFAULT '' COMMENT '關係',
      `ParentEmail` varchar(255) NOT NULL DEFAULT '' COMMENT 'Email',
      `ParentPasswd` varchar(255) NOT NULL DEFAULT '' COMMENT '密碼',
      `ParentEnable` enum('1','0') NOT NULL DEFAULT '1' COMMENT '啟用狀態',
      `code` varchar(255) NOT NULL DEFAULT '' COMMENT '啟用碼',
      PRIMARY KEY (`ParentID`),
      UNIQUE KEY `MemID_ParentEmail` (`MemID`,`ParentEmail`)
    ) ENGINE=MyISAM";
    $xoopsDB->queryF($sql) or web_error($sql);
}
