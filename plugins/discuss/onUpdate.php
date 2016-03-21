<?php

if (discuss_onUpdate1_chk()) {
    discuss_onUpdate1_go();
}

//修改欄位名稱
function discuss_onUpdate1_chk()
{
    global $xoopsDB;
    $sql    = "select count(`ParentID`) from " . $xoopsDB->prefix("tad_web_discuss");
    $result = $xoopsDB->query($sql);
    if (empty($result)) {
        return true;
    }

    return false;
}

function discuss_onUpdate1_go()
{
    global $xoopsDB;
    $sql = "ALTER TABLE " . $xoopsDB->prefix("tad_web_discuss") . " ADD `ParentID` smallint(6) unsigned NOT NULL default 0 COMMENT '家長'";
    $xoopsDB->queryF($sql) or web_error($sql);

    return true;
}
