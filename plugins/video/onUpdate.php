<?php

if (video_onUpdate1_chk()) {
    video_onUpdate1_go();
}

//新增檔案連結欄位
function video_onUpdate1_chk()
{
    global $xoopsDB;
    $sql    = "select count(`VideoSort`) from " . $xoopsDB->prefix("tad_web_video");
    $result = $xoopsDB->query($sql);
    if (empty($result)) {
        return true;
    }

    return false;
}

function video_onUpdate1_go()
{
    global $xoopsDB;
    $sql = "ALTER TABLE " . $xoopsDB->prefix("tad_web_video") . " ADD `VideoSort` smallint(6) unsigned NOT NULL default 0 COMMENT '影片排序'";
    $xoopsDB->queryF($sql) or web_error($sql);

    return true;
}
