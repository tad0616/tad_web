<?php
$xoopsTpl->assign("op", $op);
$xoopsTpl->assign('WebTitle', $WebTitle);
if (isset($LoginWebID)) {
    $xoopsTpl->assign("LoginMemID", $LoginMemID);
    $xoopsTpl->assign("LoginMemName", $LoginMemName);
    $xoopsTpl->assign("LoginMemNickName", $LoginMemNickName);
    $xoopsTpl->assign("LoginWebID", $LoginWebID);
}
if (!empty($plugin)) {
    $xoopsTpl->assign('plugin', $plugin);
}

$xoopsTpl->assign('menu_var', $menu_var);
$xoopsTpl->assign('bootstrap', $_SESSION['bootstrap']);
