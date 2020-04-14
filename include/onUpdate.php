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

    Utility::mk_dir(XOOPS_ROOT_PATH . '/uploads/tad_web');
    //重新產生外掛設定
    get_dir_plugins('force');
    //重新產生區塊設定
    get_dir_blocks('force');

    // 移除menu選單
    Update::drop_menu_plugin();

    if (!_IS_EZCLASS) {
        //修改討論區計數欄位名稱
        if (!Update::chk_chk1()) {
            Update::go_update1();
        }

        //修改討論區發布者uid編號
        if (!Update::chk_chk2()) {
            Update::go_update2();
        }
        //修改討論區發布者編號
        if (!Update::chk_chk3()) {
            Update::go_update3();
        }
        //新增討論區發布者姓名欄位
        if (!Update::chk_chk4()) {
            Update::go_update4();
        }
        //新增original_filename欄位
        if (!Update::chk_chk5()) {
            Update::go_update5();
        }
        //將各班檔案收攏到各個子目錄下
        Update::go_update6();
        //刪除錯誤的重複欄位及樣板檔
        Update::chk_tad_web_block();

        //修改分類名稱欄位名稱
        if (Update::chk_chk7()) {
            Update::go_update7();
        }
        //新增外掛表格
        if (Update::chk_chk10()) {
            Update::go_update10();
        }
        //新增角色表格
        if (Update::chk_chk11()) {
            Update::go_update11();
        }
        //新增區塊設定表格
        if (Update::chk_chk12()) {
            Update::go_update12();
        }
        //新增外掛偏好設定表格
        if (Update::chk_chk14()) {
            Update::go_update14();
        }
        //新增已使用空間
        if (Update::chk_chk15()) {
            Update::go_update15();
        }
        //新增權限表格
        if (Update::chk_chk16()) {
            Update::go_update16();
        }
        //新增標籤表格
        if (Update::chk_chk17()) {
            Update::go_update17();
        }
        //修正區塊索引
        if (Update::chk_chk18()) {
            Update::go_update18();
        }
        //刪除分享區塊設訂
        if (Update::chk_chk19()) {
            Update::go_update19();
        }
        //刪除分享區塊設訂
        if (Update::chk_chk19_1()) {
            Update::go_update19_1();
        }
        //修正權限表格索引
        if (Update::chk_chk20()) {
            Update::go_update20();
        }
        //新增通知表格
        if (Update::chk_chk21()) {
            Update::go_update21();
        }
        //新增寄信紀錄表格
        if (Update::chk_chk22()) {
            Update::go_update22();
        }
        //新增小幫手
        if (Update::chk_chk23()) {
            Update::go_update23();
        }
        //新增小幫手權限資料表
        if (Update::chk_chk24()) {
            Update::go_update24();
        }
        //新增檔案欄位
        if (Update::chk_fc_tag()) {
            Update::go_fc_tag();
        }

        //修改小幫手資料表
        if (Update::chk_chk25()) {
            Update::go_update25();
        }
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
