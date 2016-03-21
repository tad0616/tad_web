<?php

if (page_onUpdate1_chk()) {
    page_onUpdate1_go();
}

if (page_onUpdate2_chk()) {
    page_onUpdate2_go();
}

//修改欄位
function page_onUpdate1_chk()
{
    global $xoopsDB;
    $sql    = "select * from " . $xoopsDB->prefix("tad_web_page");
    $result = $xoopsDB->query($sql) or web_error($sql);
    $len    = mysql_field_len($result, 4);
    if ($len == "196605") {
        return true;
    }

    return false;
}

function page_onUpdate1_go()
{
    global $xoopsDB;
    $sql = "ALTER TABLE " . $xoopsDB->prefix("tad_web_page") . " CHANGE `PageContent` `PageContent` longtext COLLATE 'utf8_general_ci' NOT NULL COMMENT '文章內容'";
    $xoopsDB->queryF($sql) or web_error($sql);
    return true;
}

//修改排序欄位
function page_onUpdate2_chk()
{
    global $xoopsDB;
    $sql    = "SHOW Fields FROM " . $xoopsDB->prefix("tad_web_page") . " where `Field`='PageSort' and `Type` like 'smallint%'";
    $result = $xoopsDB->query($sql) or web_error($sql);
    $all    = $xoopsDB->fetchRow($result);
    // die(var_export($all));
    if ($all === false) {
        // die('yes');
        return true;
    }

    // die('no');
    return false;
}

function page_onUpdate2_go()
{
    global $xoopsDB;
    $sql = "ALTER TABLE " . $xoopsDB->prefix("tad_web_page") . " CHANGE `PageSort` `PageSort` smallint NOT NULL DEFAULT 0 COMMENT '排序'";
    // die($sql);
    $xoopsDB->queryF($sql) or web_error($sql);
    return true;
}
