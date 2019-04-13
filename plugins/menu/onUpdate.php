<?php

if (menu_onUpdate1_chk()) {
    menu_onUpdate1_go();
}

//修改欄位
function menu_onUpdate1_chk()
{
    global $xoopsDB;
    $sql = 'SHOW Fields FROM ' . $xoopsDB->prefix('tad_web_menu') . " where `Field`='Plugin' and `Type` = 'text'";
    $result = $xoopsDB->query($sql);
    if (empty($result)) {
        return false;
    }

    return true;
}

function menu_onUpdate1_go()
{
    global $xoopsDB;
    $sql = 'ALTER TABLE ' . $xoopsDB->prefix('tad_web_menu') . " CHANGE `Plugin` `Plugin` varchar(255) COLLATE 'utf8_general_ci' NOT NULL COMMENT '對應外掛' AFTER `MenuTitle`";
    $xoopsDB->queryF($sql);

    return true;
}
