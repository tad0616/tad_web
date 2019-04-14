<?php

if (action_onUpdate1_chk()) {
    action_onUpdate1_go();
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
    $xoopsDB->queryF($sql) or web_error($sql, __FILE__, __LINE__);

    return true;
}
