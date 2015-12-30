<?php

if (page_onUpdate1_chk()) {
    page_onUpdate1_go();
}

//修改欄位
function page_onUpdate1_chk()
{
    global $xoopsDB;
    $sql    = "select * from " . $xoopsDB->prefix("tad_web_page");
    $result = $xoopsDB->query($sql);
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
    $xoopsDB->queryF($sql);
    return true;
}
