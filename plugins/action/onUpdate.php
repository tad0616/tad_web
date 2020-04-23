<?php
use XoopsModules\Tadtools\Utility;

if (action_onUpdate1_chk()) {
    action_onUpdate1_go();
}

if (action_onUpdate2_chk()) {
    action_onUpdate2_go();
}

if (action_onUpdate3_chk()) {
    action_onUpdate3_go();
}

//修改欄位名稱
function action_onUpdate1_chk()
{
    global $xoopsDB;
    $sql = 'SELECT count(`ActionKind`) FROM ' . $xoopsDB->prefix('tad_web_action');
    $result = $xoopsDB->query($sql);
    if (empty($result)) {
        return false;
    }

    return true;
}

function action_onUpdate1_go()
{
    global $xoopsDB;
    $sql = 'ALTER TABLE ' . $xoopsDB->prefix('tad_web_action') . ' DROP `ActionKind`';
    $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);

    return true;
}

//新增Google Photo共享相簿欄位
function action_onUpdate2_chk()
{
    global $xoopsDB;
    $sql = 'SELECT count(`gphoto_link`) FROM ' . $xoopsDB->prefix('tad_web_action');
    $result = $xoopsDB->query($sql);
    if (empty($result)) {
        return true;
    }

    return false;
}

function action_onUpdate2_go()
{
    global $xoopsDB;
    $sql = 'ALTER TABLE ' . $xoopsDB->prefix('tad_web_action') . " ADD `gphoto_link` varchar(1000) default '' COMMENT 'Google Photo共享相簿'";
    $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);
    return true;
}

//新增tad_web_action_gphotos表格
function action_onUpdate3_chk()
{
    global $xoopsDB;
    $sql = 'SELECT count(*) FROM ' . $xoopsDB->prefix('tad_web_action_gphotos');
    $result = $xoopsDB->query($sql);
    if (empty($result)) {
        return true;
    }

    return false;
}

function action_onUpdate3_go()
{
    global $xoopsDB;
    $sql = 'CREATE TABLE `' . $xoopsDB->prefix('tad_web_action_gphotos') . "` (
    `ActionID` smallint(6) unsigned NOT NULL default '0' COMMENT '相簿編號',
    `image_id` varchar(255) NOT NULL default '' COMMENT '相片ID',
    `image_width` smallint(6) unsigned NOT NULL default '0' COMMENT '相片寬度',
    `image_height` smallint(6) unsigned NOT NULL default '0' COMMENT '相片高度',
    `image_url` varchar(1000) NOT NULL default '' COMMENT '相片網址',
    `image_description` text NOT NULL COMMENT '相片說明',
    PRIMARY KEY  (`image_id`)
    ) ENGINE=MyISAM DEFAULT CHARSET=utf8;
    ";
    $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);
}
