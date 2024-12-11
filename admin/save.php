<?php
use Xmf\Request;
use XoopsModules\Tadtools\Utility;
require_once __DIR__ . '/header.php';
header('HTTP/1.1 200 OK');
$xoopsLogger->activated = false;
$op = Request::getString('op');

if ('save_sort' === $op) {
    $sort = 1;
    foreach ($_POST['tr'] as $WebID) {
        $sql = 'UPDATE `' . $xoopsDB->prefix('tad_web') . '` SET `WebSort`=? WHERE `WebID`=?';
        Utility::query($sql, 'ii', [$sort, $WebID]) or die(_MA_TCW_UPDATE_FAIL . ' (' . date('Y-m-d H:i:s') . ')');
        $sort++;
    }

    echo _MA_TCW_SAVE_SORT_OK . ' (' . date('Y-m-d H:i:s') . ')';
} elseif ('save_teacher' === $op) {
    $WebOwnerUid = (int) $_POST['value'];
    $WebID = (int) $_POST['WebID'];

    //以uid取得使用者名稱
    $uid_name = \XoopsUser::getUnameFromId($WebOwnerUid, 1);
    $uname = \XoopsUser::getUnameFromId($WebOwnerUid, 0);

    $sql = 'UPDATE `' . $xoopsDB->prefix('tad_web') . '` SET `WebOwnerUid` =?, `WebOwner`=? WHERE `WebID`=?';
    Utility::query($sql, 'isi', [$WebOwnerUid, $uid_name, $WebID]) or die(_MA_TCW_UPDATE_FAIL . ' (' . date('Y-m-d H:i:s') . ')');

    unset($_SESSION['tad_web'][$WebID]);

    echo $uid_name . " ($uname)";
}
