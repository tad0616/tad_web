<?php

if (discuss_onUpdate1_chk()) {
    discuss_onUpdate1_go();
}

//修改欄位名稱
function discuss_onUpdate1_chk()
{
    global $xoopsDB;
    $sql = 'SELECT count(`ParentID`) FROM ' . $xoopsDB->prefix('tad_web_discuss');
    $result = $xoopsDB->query($sql);
    if (empty($result)) {
        return true;
    }

    return false;
}

function discuss_onUpdate1_go()
{
    global $xoopsDB;
    $sql = 'ALTER TABLE ' . $xoopsDB->prefix('tad_web_discuss') . " ADD `ParentID` SMALLINT(6) UNSIGNED NOT NULL DEFAULT 0 COMMENT '家長'";
    $xoopsDB->queryF($sql) or web_error($sql, __FILE__, __LINE__);

    return true;
}
