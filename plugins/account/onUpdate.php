<?php

if (account_onUpdate1_chk()) {
    account_onUpdate1_go();
}

//修改欄位名稱
function account_onUpdate1_chk()
{
    global $xoopsDB;
    $sql    = "SHOW Fields FROM " . $xoopsDB->prefix("tad_web_account") . " where `Field`='AccountIncome' and `Type` = 'smallint(6)'";
    $result = $xoopsDB->query($sql);
    if (!empty($result)) {
        return true;
    }

    return false;
}

function account_onUpdate1_go()
{
    global $xoopsDB;

    $sql = "ALTER TABLE " . $xoopsDB->prefix("tad_web_account") . " CHANGE `AccountIncome` `AccountIncome` mediumint NOT NULL DEFAULT '0' COMMENT '收入' AFTER `AccountDate`,CHANGE `AccountOutgoings` `AccountOutgoings` mediumint NOT NULL DEFAULT '0' COMMENT '支出' AFTER `AccountIncome`";
    $xoopsDB->queryF($sql) or web_error($sql, __FILE__, __LINE__);

    return true;
}
