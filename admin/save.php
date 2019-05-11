<?php
require_once __DIR__ . '/header.php';

require_once $GLOBALS['xoops']->path('/modules/system/include/functions.php');
$op = system_CleanVars($_REQUEST, 'op', '', 'string');

if ('save_sort' === $op) {
    $sort = 1;
    foreach ($_POST['tr'] as $WebID) {
        $sql = 'update ' . $xoopsDB->prefix('tad_web') . " set `WebSort`='{$sort}' where `WebID`='{$WebID}'";
        $xoopsDB->queryF($sql) or die(_MA_TCW_UPDATE_FAIL . ' (' . date('Y-m-d H:i:s') . ')');
        $sort++;
    }

    echo _MA_TCW_SAVE_SORT_OK . ' (' . date('Y-m-d H:i:s') . ')';
} elseif ('save_teacher' === $op) {
    $WebOwnerUid = $_POST['value'];
    $WebID = $_POST['WebID'];

    //以uid取得使用者名稱
    $uid_name = \XoopsUser::getUnameFromId($WebOwnerUid, 1);
    $uname = \XoopsUser::getUnameFromId($WebOwnerUid, 0);

    $sql = 'update ' . $xoopsDB->prefix('tad_web') . " set `WebOwnerUid` ='{$WebOwnerUid}', `WebOwner`='$uid_name' where `WebID`='{$WebID}'";

    unset($_SESSION['tad_web'][$WebID]);

    $xoopsDB->queryF($sql) or die(_MA_TCW_UPDATE_FAIL . ' (' . date('Y-m-d H:i:s') . ')');
    echo $uid_name . " ($uname)";
}
