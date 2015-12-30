<?php

if (news_onUpdate1_chk()) {
    news_onUpdate1_go();
}

if (news_onUpdate2_chk()) {
    news_onUpdate2_go();
}

//修改欄位
function news_onUpdate1_chk()
{
    global $xoopsDB;
    $sql    = "select count(NewsPlace) from " . $xoopsDB->prefix("tad_web_news");
    $result = $xoopsDB->query($sql);
    if (!empty($result)) {
        return true;
    }

    return false;
}

function news_onUpdate1_go()
{
    global $xoopsDB;
    $sql = "ALTER TABLE " . $xoopsDB->prefix("tad_web_news") . " CHANGE `NewsContent` `NewsContent` longtext COLLATE 'utf8_general_ci' NOT NULL COMMENT '內容',
    DROP `NewsPlace`,
    DROP `NewsMaster`";
    $xoopsDB->queryF($sql);
    return true;
}

//刪除NewsKind欄位
function news_onUpdate2_chk()
{
    global $xoopsDB;
    $sql    = "select count(NewsKind) from " . $xoopsDB->prefix("tad_web_news");
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
