<?php

if (files_onUpdate1_chk()) {
    files_onUpdate1_go();
}

//新增檔案連結欄位
function files_onUpdate1_chk()
{
    global $xoopsDB;
    $sql    = "SELECT count(`file_link`) FROM " . $xoopsDB->prefix("tad_web_files");
    $result = $xoopsDB->query($sql);
    if (empty($result)) {
        return true;
    }

    return false;
}

function files_onUpdate1_go()
{
    global $xoopsDB;
    $sql = "ALTER TABLE " . $xoopsDB->prefix("tad_web_files") . " ADD `file_link` VARCHAR(255) NOT NULL DEFAULT '' COMMENT '檔案連結',ADD `file_description` VARCHAR(255) NOT NULL DEFAULT '' COMMENT '檔案說明或檔名' AFTER `file_date`";
    $xoopsDB->queryF($sql) or web_error($sql, __FILE__, __LINE__);

    return true;
}
