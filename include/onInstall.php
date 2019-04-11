<?php

use XoopsModules\Tad_web\Utility;

include dirname(__DIR__) . '/preloads/autoloader.php';

function xoops_module_install_tad_web(&$module)
{
    Utility::mk_dir(XOOPS_ROOT_PATH . "/uploads/tad_web");
    Utility::chk_sql_install();
    Utility::add_log('install');
    return true;
}
