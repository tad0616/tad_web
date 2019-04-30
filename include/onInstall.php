<?php
use XoopsModules\Tadtools\Utility;
use XoopsModules\Tad_web\Update;

include dirname(__DIR__) . '/preloads/autoloader.php';

function xoops_module_install_tad_web(&$module)
{
    Utility::mk_dir(XOOPS_ROOT_PATH . '/uploads/tad_web');
    Update::chk_sql_install();
    Update::add_log('install');

    return true;
}
