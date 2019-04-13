<?php

if (homework_onUpdate1_chk()) {
    homework_onUpdate1_go();
}
if (homework_onUpdate2_chk()) {
    homework_onUpdate2_go();
}

//修改聯絡簿計數欄位名稱
function homework_onUpdate1_chk()
{
    global $xoopsDB;
    $sql = 'SELECT count(`HomeworkPostDate`) FROM ' . $xoopsDB->prefix('tad_web_homework');
    $result = $xoopsDB->query($sql);
    if (empty($result)) {
        return true;
    }

    return false;
}

function homework_onUpdate1_go()
{
    global $xoopsDB;
    $sql = 'ALTER TABLE ' . $xoopsDB->prefix('tad_web_homework') . " CHANGE `toCal` `toCal` DATE NOT NULL DEFAULT '0000-00-00' COMMENT '加到行事曆', ADD `HomeworkPostDate` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '顯示日期' AFTER `uid`";
    $xoopsDB->queryF($sql);
    $sql = 'update ' . $xoopsDB->prefix('tad_web_homework') . ' set `HomeworkPostDate` = `HomeworkDate`';
    $xoopsDB->queryF($sql);

    return true;
}

//新增家長表格
function homework_onUpdate2_chk()
{
    global $xoopsDB;
    $sql = 'SELECT count(*) FROM ' . $xoopsDB->prefix('tad_web_homework_content');
    $result = $xoopsDB->query($sql);
    if (empty($result)) {
        return true;
    }

    return false;
}

function homework_onUpdate2_go()
{
    global $xoopsDB;
    $sql = 'CREATE TABLE `' . $xoopsDB->prefix('tad_web_homework_content') . "` (
      `HomeworkID` SMALLINT(6) UNSIGNED NOT NULL COMMENT '編號',
      `HomeworkCol` VARCHAR(100) NOT NULL DEFAULT '' COMMENT '欄位',
      `Content` TEXT NOT NULL COMMENT '內容',
      `WebID` SMALLINT(6) UNSIGNED NOT NULL DEFAULT 0 COMMENT '所屬班級',
      PRIMARY KEY (`HomeworkID`,`HomeworkCol`)
    ) ENGINE=MyISAM";
    $xoopsDB->queryF($sql) or web_error($sql, __FILE__, __LINE__);
}
