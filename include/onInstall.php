<?php
use XoopsModules\Tadtools\Utility;
use XoopsModules\Tad_web\Update;

if (!class_exists('XoopsModules\Tadtools\Utility')) {
    require XOOPS_ROOT_PATH . '/modules/tadtools/preloads/autoloader.php';
}
if (!class_exists('XoopsModules\Tad_web\Update')) {
    include dirname(__DIR__) . '/preloads/autoloader.php';
}

function xoops_module_install_tad_web(&$module)
{

    Utility::mk_dir(XOOPS_VAR_PATH . "/tad_web");
    Utility::mk_dir(XOOPS_VAR_PATH . "/tad_web/my_webs_data");
    Utility::mk_dir(XOOPS_ROOT_PATH . '/uploads/tad_web');
    Update::chk_sql_install();
    Update::add_log('install');

    return true;
}
