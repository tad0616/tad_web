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
    $sql = 'SELECT count(`CateID`) FROM ' . $xoopsDB->prefix('tad_web_link_mems');
    $result = $xoopsDB->query($sql);
    if (empty($result)) {
        return true;
    }

    return false;
}

function aboutus_onUpdate1_go()
{
    global $xoopsDB;
    $sql = 'ALTER TABLE ' . $xoopsDB->prefix('tad_web_link_mems') . ' ADD `CateID` SMALLINT(6) UNSIGNED NOT NULL DEFAULT 0 AFTER `WebID`';
    $xoopsDB->queryF($sql) or web_error($sql, __FILE__, __LINE__);

    $year = get_seme_year();
    require_once XOOPS_ROOT_PATH . '/modules/tad_web/class/cate.php';

    $sql = 'SELECT WebID,WebTitle FROM `' . $xoopsDB->prefix('tad_web') . '` GROUP BY `WebID`';
    $result = $xoopsDB->queryF($sql) or web_error($sql, __FILE__, __LINE__);
    while (list($WebID, $WebTitle) = $xoopsDB->fetchRow($result)) {
        $web_cate = new web_cate($WebID, 'aboutus', 'tad_web_link_mems');
        $CateID = $web_cate->save_tad_web_cate('', sprintf(_MD_TCW_SEME_CATE, $year) . " {$WebTitle}");

        $sql = 'update ' . $xoopsDB->prefix('tad_web_link_mems') . " set CateID='{$CateID}' where WebID='{$WebID}'";
        $xoopsDB->queryF($sql) or web_error($sql, __FILE__, __LINE__);
    }

    $sql = 'ALTER TABLE ' . $xoopsDB->prefix('tad_web_link_mems') . ' ADD PRIMARY KEY `MemID_CateID` (`MemID`, `CateID`) , DROP INDEX `PRIMARY`';
    $xoopsDB->queryF($sql) or web_error($sql, __FILE__, __LINE__);

    return true;
}

//取得目前的學年
function get_seme_year()
{
    $y = date('Y');
    $m = date('n');
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
    $sql = 'SELECT count(`MemClassOrgan`) FROM ' . $xoopsDB->prefix('tad_web_link_mems');
    $result = $xoopsDB->query($sql);
    if (empty($result)) {
        return true;
    }

    return false;
}

function aboutus_onUpdate2_go()
{
    global $xoopsDB;
    $sql = 'ALTER TABLE ' . $xoopsDB->prefix('tad_web_link_mems') . " ADD `MemClassOrgan` VARCHAR(255) NOT NULL DEFAULT '' COMMENT '職稱' , ADD `AboutMem` TEXT NOT NULL COMMENT '介紹'";
    $xoopsDB->queryF($sql) or web_error($sql, __FILE__, __LINE__);

    $sql = 'ALTER TABLE ' . $xoopsDB->prefix('tad_web_mems') . ' DROP `MemUrl`, DROP `MemClassOrgan`';
    $xoopsDB->queryF($sql) or web_error($sql, __FILE__, __LINE__);

    return true;
}

//新增家長表格
function aboutus_onUpdate3_chk()
{
    global $xoopsDB;
    $sql = 'SELECT count(*) FROM ' . $xoopsDB->prefix('tad_web_mem_parents');
    $result = $xoopsDB->query($sql);
    if (empty($result)) {
        return true;
    }

    return false;
}

function aboutus_onUpdate3_go()
{
    global $xoopsDB;
    $sql = 'CREATE TABLE `' . $xoopsDB->prefix('tad_web_mem_parents') . "` (
      `ParentID` MEDIUMINT(8) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ParentID',
      `MemID` MEDIUMINT(8) UNSIGNED NOT NULL COMMENT 'MemID',
      `Reationship` VARCHAR(255) NOT NULL DEFAULT '' COMMENT '關係',
      `ParentEmail` VARCHAR(255) NOT NULL DEFAULT '' COMMENT 'Email',
      `ParentPasswd` VARCHAR(255) NOT NULL DEFAULT '' COMMENT '密碼',
      `ParentEnable` ENUM('1','0') NOT NULL DEFAULT '1' COMMENT '啟用狀態',
      `code` VARCHAR(255) NOT NULL DEFAULT '' COMMENT '啟用碼',
      PRIMARY KEY (`ParentID`),
      UNIQUE KEY `MemID_ParentEmail` (`MemID`,`ParentEmail`)
    ) ENGINE=MyISAM";
    $xoopsDB->queryF($sql) or web_error($sql, __FILE__, __LINE__);
}
