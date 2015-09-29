<?php
$xoopsTpl->assign("op", $op);
$xoopsTpl->assign('WebTitle', $WebTitle);
$xoopsTpl->assign("LoginMemID", $LoginMemID);
$xoopsTpl->assign("LoginMemName", $LoginMemName);
$xoopsTpl->assign("LoginMemNickName", $LoginMemNickName);
$xoopsTpl->assign("LoginWebID", $LoginWebID);

if (!empty($plugin)) {
    $xoopsTpl->assign('plugin', $plugin);
}
