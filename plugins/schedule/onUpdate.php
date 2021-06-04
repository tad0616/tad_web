<?php
if (!schedule_onUpdate1_chk()) {
    schedule_onUpdate1_go();
}

//新增連結欄位
function schedule_onUpdate1_chk()
{
    global $xoopsDB;

    $sql = "desc `" . $xoopsDB->prefix('tad_web_schedule_data') . "` `Link`";
    $result = $xoopsDB->queryF($sql);
    return $xoopsDB->getRowsNum($result);
}

function schedule_onUpdate1_go()
{
    global $xoopsDB;
    $sql = 'ALTER TABLE ' . $xoopsDB->prefix('tad_web_schedule_data') . " ADD `Link` varchar(1000) NOT NULL default '' COMMENT '連結' AFTER `Teacher`";
    $xoopsDB->queryF($sql);

    return true;
}
