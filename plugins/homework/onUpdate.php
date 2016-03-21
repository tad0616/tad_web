<?php

if (homework_onUpdate1_chk()) {
    homework_onUpdate1_go();
}

//修正聯絡簿日期
homework_onUpdate2_go();

//修改聯絡簿計數欄位名稱
function homework_onUpdate1_chk()
{
    global $xoopsDB;
    $sql    = "select count(`HomeworkPostDate`) from " . $xoopsDB->prefix("tad_web_homework");
    $result = $xoopsDB->query($sql);
    if (empty($result)) {
        return true;
    }

    return false;
}

function homework_onUpdate1_go()
{
    global $xoopsDB;
    $sql = "ALTER TABLE " . $xoopsDB->prefix("tad_web_homework") . " CHANGE `toCal` `toCal` date NOT NULL default '0000-00-00' COMMENT '加到行事曆', ADD `HomeworkPostDate` datetime NOT NULL default '0000-00-00 00:00:00' COMMENT '顯示日期' AFTER `uid`";
    $xoopsDB->queryF($sql);
    $sql = "update " . $xoopsDB->prefix("tad_web_homework") . " set `HomeworkPostDate` = `HomeworkDate`";
    $xoopsDB->queryF($sql);
    return true;
}

//修正聯絡簿日期
function homework_onUpdate2_go()
{
    global $xoopsDB;
    $sql = "update `" . $xoopsDB->prefix("tad_web_homework") . "` set `HomeworkPostDate`=`HomeworkDate`
    where right(`HomeworkPostDate`,6)!=':00:00' and `HomeworkPostDate`!=`HomeworkDate`";
    // $sql = "update " . $xoopsDB->prefix("tad_web_homework") . " set `HomeworkPostDate`=concat(`toCal`,' ',right(`HomeworkPostDate`,8)) where `toCal`!=left(`HomeworkPostDate`,10)";
    $xoopsDB->queryF($sql);
    return true;
}
