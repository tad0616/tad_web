<?php

if (news_onUpdate1_chk()) {
    news_onUpdate1_go();
}

if (news_onUpdate2_chk()) {
    news_onUpdate2_go();
}

if (news_onUpdate3_chk()) {
    news_onUpdate3_go();
}

//修改欄位
function news_onUpdate1_chk()
{
    global $xoopsDB;
    $sql    = "SELECT count(NewsPlace) FROM " . $xoopsDB->prefix("tad_web_news");
    $result = $xoopsDB->query($sql);
    if (!empty($result)) {
        return true;
    }

    return false;
}

function news_onUpdate1_go()
{
    global $xoopsDB;
    $sql = "ALTER TABLE " . $xoopsDB->prefix("tad_web_news") . " CHANGE `NewsContent` `NewsContent` LONGTEXT COLLATE 'utf8_general_ci' NOT NULL COMMENT '內容',
    DROP `NewsPlace`,
    DROP `NewsMaster`";
    $xoopsDB->queryF($sql);
    return true;
}

//刪除NewsKind欄位
function news_onUpdate2_chk()
{
    global $xoopsDB;
    $sql    = "SELECT count(NewsKind) FROM " . $xoopsDB->prefix("tad_web_news");
    $result = $xoopsDB->query($sql);
    if (!empty($result)) {
        return true;
    }

    return false;
}

function news_onUpdate2_go()
{
    global $xoopsDB;
    $sql = "ALTER TABLE " . $xoopsDB->prefix("tad_web_news") . " DROP `NewsKind`";
    $xoopsDB->queryF($sql);
    return true;
}

//新增狀態欄位
function news_onUpdate3_chk()
{
    global $xoopsDB;
    $sql    = "SELECT count(NewsEnable) FROM " . $xoopsDB->prefix("tad_web_news");
    $result = $xoopsDB->query($sql);
    if (empty($result)) {
        return true;
    }

    return false;
}

function news_onUpdate3_go()
{
    global $xoopsDB;
    $sql = "ALTER TABLE " . $xoopsDB->prefix("tad_web_news") . " ADD `NewsEnable` ENUM('1','0')  NOT NULL DEFAULT '1' COMMENT '狀態' AFTER `NewsCounter`";
    $xoopsDB->queryF($sql);
    return true;
}
