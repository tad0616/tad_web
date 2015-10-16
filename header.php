<?php
include_once "../../mainfile.php";

//判斷是否對該模組有管理權限
$isAdmin = false;
if ($xoopsUser) {
    if (!$xoopsModule) {
        $modhandler  = &xoops_gethandler('module');
        $xoopsModule = &$modhandler->getByDirname("tad_web");
    }
    $module_id = $xoopsModule->getVar('mid');
    $isAdmin   = $xoopsUser->isAdmin($module_id);
} else {

    $LoginMemID       = isset($_SESSION['LoginMemID']) ? $_SESSION['LoginMemID'] : null;
    $LoginMemName     = isset($_SESSION['LoginMemName']) ? $_SESSION['LoginMemName'] : null;
    $LoginMemNickName = isset($_SESSION['LoginMemNickName']) ? $_SESSION['LoginMemNickName'] : null;
    $LoginWebID       = isset($_SESSION['LoginWebID']) ? $_SESSION['LoginWebID'] : null;

}

//目前觀看的班級
if (!empty($_REQUEST['WebID'])) {
    $WebID = intval($_REQUEST['WebID']);
} else {
    $WebID = '';
}

include_once "function.php";
include_once "class/cate.php";

//圖案
// $TadUpFiles->set_col("WebLogo", $WebID, "1");
// $web_logo = $TadUpFiles->get_pic_file();

//我的班級ID（陣列）
$MyWebs = MyWebID();

//目前瀏覽的是否是我的班級？
$isMyWeb = ($isAdmin) ? true : in_array($WebID, $MyWebs);

if ($WebID) {
    $Web      = getWebInfo($WebID);
    $WebName  = $Web['WebTitle'];
    $WebTitle = $Web['WebTitle'];
    $WebOwner = $Web['WebOwner'];

    if (!file_exists(XOOPS_ROOT_PATH . "/uploads/tad_web/{$WebID}/header.png")) {
        output_head_file($WebID);
    }
} else {
    $Web      = "";
    $WebName  = "";
    $WebTitle = "";
    $WebOwner = "";
}

$i = 0;

if ($WebID) {
    $menu_var[$i]['id']     = $i;
    $menu_var[$i]['title']  = _MD_TCW_CLASS_HOME;
    $menu_var[$i]['url']    = "index.php?WebID={$WebID}";
    $menu_var[$i]['target'] = "_self";
    $menu_var[$i]['icon']   = "fa-home";
    $i++;
}

//模組前台選單
if (!file_exists(XOOPS_ROOT_PATH . "/uploads/tad_web/{$WebID}/menu_var.php")) {
    mk_menu_var_file($WebID);
}
include_once XOOPS_ROOT_PATH . "/uploads/tad_web/{$WebID}/menu_var.php";

$i = sizeof($menu_var);

if ($isMyWeb) {
    $menu_var[$i]['id']     = $i;
    $menu_var[$i]['title']  = _MD_TCW_TOOLS;
    $menu_var[$i]['url']    = "config.php?WebID={$WebID}";
    $menu_var[$i]['target'] = "_self";
    $menu_var[$i]['icon']   = "fa-check-square-o";
    $i++;
}

$menu_var[$i]['id']     = $i;
$menu_var[$i]['title']  = _MD_TCW_HOME;
$menu_var[$i]['url']    = "index.php";
$menu_var[$i]['target'] = "_self";
$menu_var[$i]['icon']   = "fa-share-square-o";
$i++;

if ($isAdmin) {
    $menu_var[$i]['id']     = $i;
    $menu_var[$i]['title']  = _MD_TCW_ADMIN;
    $menu_var[$i]['url']    = "admin/index.php";
    $menu_var[$i]['target'] = "_self";
    $menu_var[$i]['icon']   = "fa-check-square-o";
    $i++;
}
