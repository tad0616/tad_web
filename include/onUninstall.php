<?php

use XoopsModules\Tad_web\Utility;

function xoops_module_uninstall_tad_web(&$module)
{
    global $xoopsDB;
    Utility::uninstall_sql();
    $date = date('Ymd');
    rename(XOOPS_ROOT_PATH . '/uploads/tad_web', XOOPS_ROOT_PATH . "/uploads/tad_web_bak_{$date}");

    return true;
}
