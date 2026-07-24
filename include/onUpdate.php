<?php

use XoopsModules\Tadtools\Utility;
use XoopsModules\Tad_web\Update;

if (!class_exists('XoopsModules\Tadtools\Utility')) {
    require XOOPS_ROOT_PATH . '/modules/tadtools/preloads/autoloader.php';
}
if (!class_exists('XoopsModules\Tad_web\Update')) {
    include dirname(__DIR__) . '/preloads/autoloader.php';
}
function xoops_module_update_tad_web($module, $old_version)
{
    global $xoopsDB;
    require_once XOOPS_ROOT_PATH . '/modules/tad_web/function.php';
    define('_EZCLASS', 'https://class.tn.edu.tw');
    $is_ezclass = XOOPS_URL == _EZCLASS ? true : false;
    define('_IS_EZCLASS', $is_ezclass);

    Utility::mk_dir(XOOPS_VAR_PATH . "/tad_web");
    Utility::mk_dir(XOOPS_VAR_PATH . "/tad_web/my_webs_data");
    Utility::mk_dir(XOOPS_ROOT_PATH . '/uploads/tad_web');
    //重新產生外掛設定
    get_dir_plugins('force');
    //重新產生區塊設定
    get_dir_blocks('force');

    // 移除menu選單
    Update::drop_menu_plugin();

    if (!_IS_EZCLASS) {
        //修改討論區相關欄位
        Update::chk_tad_web_discuss();

        //整理 tad_web_files_center
        Update::chk_tad_web_files_center();

        //刪除錯誤的重複欄位及樣板檔
        Update::chk_tad_web_block();

        //修改分類名稱欄位名稱
        Update::chk_tad_web_cate();

        //建立相關資料表
        Update::chk_tad_web_tables();

        //建立與更新區塊資料表
        Update::chk_tad_web_blocks();

        //更新 tad_web 資料表
        Update::chk_tad_web();

        //更新 tad_web_power 資料表
        Update::chk_tad_web_power();

        //更新小幫手相關資料表
        Update::chk_tad_web_assistant();
    }

    Update::chk_sql_update();

    if (!_IS_EZCLASS) {
        Update::modify_share_block();
        Update::go_update_var();
        Update::add_log('update');
    }

    Update::chk_plugin_update();
    Update::fiexd_block();
    Update::del_dir_plugins_json();

    return true;
}
