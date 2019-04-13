<?php

use XoopsModules\Tad_web\Utility;

function xoops_module_update_tad_web($module, $old_version)
{
    global $xoopsDB;
    include_once XOOPS_ROOT_PATH . '/modules/tad_web/function.php';
    define('_EZCLASS', 'https://class.tn.edu.tw');
    $is_ezclass = XOOPS_URL == _EZCLASS ? true : false;
    define('_IS_EZCLASS', $is_ezclass);

    Utility::mk_dir(XOOPS_ROOT_PATH . '/uploads/tad_web');
    //重新產生外掛設定
    get_dir_plugins('force');
    //重新產生區塊設定
    get_dir_blocks('force');

    if (!_IS_EZCLASS) {
        //修改討論區計數欄位名稱
        if (!Utility::chk_chk1()) {
            Utility::go_update1();
        }

        //修改討論區發布者uid編號
        if (!Utility::chk_chk2()) {
            Utility::go_update2();
        }
        //修改討論區發布者編號
        if (!Utility::chk_chk3()) {
            Utility::go_update3();
        }
        //新增討論區發布者姓名欄位
        if (!Utility::chk_chk4()) {
            Utility::go_update4();
        }
        //新增original_filename欄位
        if (!Utility::chk_chk5()) {
            Utility::go_update5();
        }
        //將各班檔案收攏到各個子目錄下
        Utility::go_update6();
        //刪除錯誤的重複欄位及樣板檔
        Utility::chk_tad_web_block();

        //修改分類名稱欄位名稱
        if (Utility::chk_chk7()) {
            Utility::go_update7();
        }
        //新增外掛表格
        if (Utility::chk_chk10()) {
            Utility::go_update10();
        }
        //新增角色表格
        if (Utility::chk_chk11()) {
            Utility::go_update11();
        }
        //新增區塊設定表格
        if (Utility::chk_chk12()) {
            Utility::go_update12();
        }
        //新增外掛偏好設定表格
        if (Utility::chk_chk14()) {
            Utility::go_update14();
        }
        //新增已使用空間
        if (Utility::chk_chk15()) {
            Utility::go_update15();
        }
        //新增權限表格
        if (Utility::chk_chk16()) {
            Utility::go_update16();
        }
        //新增標籤表格
        if (Utility::chk_chk17()) {
            Utility::go_update17();
        }
        //修正區塊索引
        if (Utility::chk_chk18()) {
            Utility::go_update18();
        }
        //刪除分享區塊設訂
        if (Utility::chk_chk19()) {
            Utility::go_update19();
        }
        //刪除分享區塊設訂
        if (Utility::chk_chk19_1()) {
            Utility::go_update19_1();
        }
        //修正權限表格索引
        if (Utility::chk_chk20()) {
            Utility::go_update20();
        }
        //新增通知表格
        if (Utility::chk_chk21()) {
            Utility::go_update21();
        }
        //新增寄信紀錄表格
        if (Utility::chk_chk22()) {
            Utility::go_update22();
        }
        //新增小幫手
        if (Utility::chk_chk23()) {
            Utility::go_update23();
        }
        //新增小幫手權限資料表
        if (Utility::chk_chk24()) {
            Utility::go_update24();
        }
        //新增檔案欄位
        if (Utility::chk_fc_tag()) {
            Utility::go_fc_tag();
        }
    }

    Utility::chk_sql_update();

    if (!_IS_EZCLASS) {
        Utility::modify_share_block();
        Utility::go_update_var();
        Utility::add_log('update');
    }

    Utility::chk_plugin_update();
    Utility::fiexd_block();

    return true;
}
