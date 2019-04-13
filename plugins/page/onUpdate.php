<?php

if (page_onUpdate1_chk()) {
    page_onUpdate1_go();
}

if (page_onUpdate2_chk()) {
    page_onUpdate2_go();
}

if (page_onUpdate3_chk()) {
    page_onUpdate3_go();
}

//修改欄位
function page_onUpdate1_chk()
{
    global $xoopsDB;
    $sql = "SELECT DATA_TYPE FROM INFORMATION_SCHEMA.COLUMNS WHERE table_name = '" . $xoopsDB->prefix('tad_web_page') . "' AND COLUMN_NAME = 'PageContent'";
    $result = $xoopsDB->query($sql);
    list($type) = $xoopsDB->fetchRow($result);
    if ('text' == $type) {
        return true;
    }

    return false;
}

function page_onUpdate1_go()
{
    global $xoopsDB;
    $sql = 'ALTER TABLE ' . $xoopsDB->prefix('tad_web_page') . " CHANGE `PageContent` `PageContent` LONGTEXT COLLATE 'utf8_general_ci' NOT NULL COMMENT '文章內容'";
    $xoopsDB->queryF($sql) or web_error($sql, __FILE__, __LINE__);

    return true;
}

//修改排序欄位
function page_onUpdate2_chk()
{
    global $xoopsDB;
    $sql = 'SHOW Fields FROM ' . $xoopsDB->prefix('tad_web_page') . " where `Field`='PageSort' and `Type` like 'smallint%'";
    $result = $xoopsDB->query($sql) or web_error($sql, __FILE__, __LINE__);
    $all = $xoopsDB->fetchRow($result);
    // die(var_export($all));
    if (false === $all) {
        // die('yes');
        return true;
    }

    // die('no');
    return false;
}

function page_onUpdate2_go()
{
    global $xoopsDB;
    $sql = 'ALTER TABLE ' . $xoopsDB->prefix('tad_web_page') . " CHANGE `PageSort` `PageSort` SMALLINT NOT NULL DEFAULT 0 COMMENT '排序'";
    // die($sql);
    $xoopsDB->queryF($sql) or web_error($sql, __FILE__, __LINE__);

    return true;
}

//新增樣式欄位
function page_onUpdate3_chk()
{
    global $xoopsDB;
    $sql = 'SELECT count(PageCSS) FROM ' . $xoopsDB->prefix('tad_web_page');
    $result = $xoopsDB->query($sql);
    if (empty($result)) {
        return true;
    }

    return false;
}

function page_onUpdate3_go()
{
    global $xoopsDB;
    $sql = 'ALTER TABLE ' . $xoopsDB->prefix('tad_web_page') . " ADD `PageCSS` TEXT NOT NULL COMMENT '文章樣式'";
    $xoopsDB->queryF($sql);

    return true;
}
