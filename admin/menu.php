<?php
$adminmenu = array();
$icon_dir  = substr(XOOPS_VERSION, 6, 3) == '2.6' ? "" : "images/admin/";

$i                      = 1;
$adminmenu[$i]['title'] = _MI_TAD_ADMIN_HOME;
$adminmenu[$i]['link']  = 'admin/index.php';
$adminmenu[$i]['desc']  = _MI_TAD_ADMIN_HOME_DESC;
$adminmenu[$i]['icon']  = 'images/admin/home.png';

$i++;
$adminmenu[$i]['title'] = _MI_TCW_ADMENU1;
$adminmenu[$i]['link']  = "admin/main.php";
$adminmenu[$i]['desc']  = _MI_TCW_ADMENU1;
$adminmenu[$i]['icon']  = "{$icon_dir}sites.png";

$i++;
$adminmenu[$i]['title'] = _MI_TCW_ADMENU2;
$adminmenu[$i]['link']  = "admin/cate.php";
$adminmenu[$i]['desc']  = _MI_TCW_ADMENU2;
$adminmenu[$i]['icon']  = "{$icon_dir}category.png";

$i++;
$adminmenu[$i]['title'] = _MI_TCW_ADMENU3;
$adminmenu[$i]['link']  = "admin/setup.php";
$adminmenu[$i]['desc']  = _MI_TCW_ADMENU3;
$adminmenu[$i]['icon']  = "{$icon_dir}setup.png";

$i++;
$adminmenu[$i]['title'] = _MI_TCW_ADMENU5;
$adminmenu[$i]['link']  = "admin/schedule.php";
$adminmenu[$i]['desc']  = _MI_TCW_ADMENU5;
$adminmenu[$i]['icon']  = "{$icon_dir}table_edit.png";

$i++;
$adminmenu[$i]['title'] = _MI_TCW_ADMENU4;
$adminmenu[$i]['link']  = "admin/disk.php";
$adminmenu[$i]['desc']  = _MI_TCW_ADMENU4;
$adminmenu[$i]['icon']  = "{$icon_dir}pie.png";

$i++;
$adminmenu[$i]['title'] = _MI_TCW_ADMENU6;
$adminmenu[$i]['link']  = "admin/notice.php";
$adminmenu[$i]['desc']  = _MI_TCW_ADMENU6;
$adminmenu[$i]['icon']  = "{$icon_dir}mail_notice.png";

$i++;
$adminmenu[$i]['title'] = _MI_TAD_ADMIN_ABOUT;
$adminmenu[$i]['link']  = 'admin/about.php';
$adminmenu[$i]['desc']  = _MI_TAD_ADMIN_ABOUT_DESC;
$adminmenu[$i]['icon']  = 'images/admin/about.png';
