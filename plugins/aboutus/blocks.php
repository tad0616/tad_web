<?php

function list_web_adm($WebID, $config = array())
{

    global $xoopsDB, $xoopsTpl;
    if (empty($WebID)) {
        retuen;
    }

    $sql    = "SELECT `WebOwnerUid` FROM `" . $xoopsDB->prefix("tad_web") . "` WHERE `WebID` = '$WebID'";
    $result = $xoopsDB->query($sql) or web_error($sql);
    while (list($uid) = $xoopsDB->fetchRow($result)) {
        $admin[$uid] = $uid;
    }

    $sql    = "SELECT `uid` FROM `" . $xoopsDB->prefix("tad_web_roles") . "` WHERE `WebID` = '$WebID' and `role` = 'admin'";
    $result = $xoopsDB->query($sql) or web_error($sql);
    while (list($uid) = $xoopsDB->fetchRow($result)) {
        $admin[$uid] = $uid;
    }

    $admin_str = implode("','", $admin);

    $sql    = "SELECT `uid`,`name`,`uname`,`email` FROM `" . $xoopsDB->prefix("users") . "` WHERE `uid` in('{$admin_str}')";
    $result = $xoopsDB->query($sql) or web_error($sql);
    $i      = 0;
    while (list($uid, $name, $uname, $email) = $xoopsDB->fetchRow($result)) {
        $admin_arr[$i]['uid']   = $uid;
        $admin_arr[$i]['name']  = $name;
        $admin_arr[$i]['uname'] = $uname;
        $admin_arr[$i]['email'] = $email;
        $i++;
    }

    $xoopsTpl->assign('admin_arr', $admin_arr);
}
