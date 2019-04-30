<?php

use XoopsModules\Tad_web\Update;

function xoops_module_uninstall_tad_web(&$module)
{
    global $xoopsDB;
    Update::uninstall_sql();
    $date = date('Ymd');
    rename(XOOPS_ROOT_PATH . '/uploads/tad_web', XOOPS_ROOT_PATH . "/uploads/tad_web_bak_{$date}");

    return true;
}
