<?php
include_once "../../mainfile.php";

//判斷是否對該模組有管理權限
$isAdmin = false;
if ($xoopsUser) {
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
    $WebID             = intval($_REQUEST['WebID']);
    $_SESSION['WebID'] = $WebID;
} else {
    $WebID = $_SESSION['WebID'];
}

include_once "function.php";
include_once "class/cate.php";

if ($WebID) {
    $Web      = getWebInfo($WebID);
    $WebName  = $Web['WebTitle'];
    $WebTitle = $Web['WebTitle'];
    $WebOwner = $Web['WebOwner'];

    if (!file_exists(XOOPS_ROOT_PATH . "/uploads/tad_web/{$WebID}/header.png")) {
        output_head_file();
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

if ($xoopsModuleConfig['web_mode'] == "class") {
    $_MD_TCW_ABOUTUS = empty($WebID) ? _MD_TCW_ALL_CLASS : _MD_TCW_MY_CLASS;
} else {
    $_MD_TCW_ABOUTUS = empty($WebID) ? _MD_TCW_ALL_WEB : _MD_TCW_ABOUTUS;
}

$hide_function = array();
if (!empty($WebID)) {
    $ConfigValue   = get_web_config("hide_function", $WebID);
    $hide_function = explode(';', $ConfigValue);
}

if (!in_array('aboutus', $hide_function)) {
    $menu_var[$i]['id']     = $i;
    $menu_var[$i]['title']  = _MD_TCW_ABOUTUS;
    $menu_var[$i]['url']    = "aboutus.php?WebID={$WebID}";
    $menu_var[$i]['target'] = "_self";
    $menu_var[$i]['icon']   = "fa-smile-o";
    $i++;
}

if (!in_array('news', $hide_function)) {
    $menu_var[$i]['id']     = $i;
    $menu_var[$i]['title']  = _MD_TCW_NEWS;
    $menu_var[$i]['url']    = "news.php?WebID={$WebID}";
    $menu_var[$i]['target'] = "_self";
    $menu_var[$i]['icon']   = "fa-newspaper-o";
    $i++;
}

if ($xoopsModuleConfig['web_mode'] == "class") {
    if (!in_array('homework', $hide_function)) {
        $menu_var[$i]['id']     = $i;
        $menu_var[$i]['title']  = _MD_TCW_HOMEWORK;
        $menu_var[$i]['url']    = "homework.php?WebID={$WebID}";
        $menu_var[$i]['target'] = "_self";
        $menu_var[$i]['icon']   = "fa-pencil-square-o";
        $i++;
    }

}

if (!in_array('works', $hide_function)) {
    $menu_var[$i]['id']     = $i;
    $menu_var[$i]['title']  = _MD_TCW_WORKS;
    $menu_var[$i]['url']    = "works.php?WebID={$WebID}";
    $menu_var[$i]['target'] = "_self";
    $menu_var[$i]['icon']   = "fa-paint-brush";
    $i++;
}

if (!in_array('files', $hide_function)) {
    $menu_var[$i]['id']     = $i;
    $menu_var[$i]['title']  = _MD_TCW_FILES;
    $menu_var[$i]['url']    = "files.php?WebID={$WebID}";
    $menu_var[$i]['target'] = "_self";
    $menu_var[$i]['icon']   = "fa-upload";
    $i++;
}

if (!in_array('action', $hide_function)) {

    $menu_var[$i]['id']     = $i;
    $menu_var[$i]['title']  = _MD_TCW_ACTION;
    $menu_var[$i]['url']    = "action.php?WebID={$WebID}";
    $menu_var[$i]['target'] = "_self";
    $menu_var[$i]['icon']   = "fa-camera";
    $i++;
}

if (!in_array('video', $hide_function)) {
    $menu_var[$i]['id']     = $i;
    $menu_var[$i]['title']  = _MD_TCW_VIDEO;
    $menu_var[$i]['url']    = "video.php?WebID={$WebID}";
    $menu_var[$i]['target'] = "_self";
    $menu_var[$i]['icon']   = "fa-film";
    $i++;
}

if (!in_array('link', $hide_function)) {
    $menu_var[$i]['id']     = $i;
    $menu_var[$i]['title']  = _MD_TCW_LINK;
    $menu_var[$i]['url']    = "link.php?WebID={$WebID}";
    $menu_var[$i]['target'] = "_self";
    $menu_var[$i]['icon']   = "fa-globe";
    $i++;
}

if (!in_array('discuss', $hide_function)) {
    $menu_var[$i]['id']     = $i;
    $menu_var[$i]['title']  = _MD_TCW_DISCUSS;
    $menu_var[$i]['url']    = "discuss.php?WebID={$WebID}";
    $menu_var[$i]['target'] = "_self";
    $menu_var[$i]['icon']   = "fa-comments";
    $i++;
}

if (!in_array('calendar', $hide_function)) {
    $menu_var[$i]['id']     = $i;
    $menu_var[$i]['title']  = _MD_TCW_CALENDAR;
    $menu_var[$i]['url']    = "calendar.php?WebID={$WebID}";
    $menu_var[$i]['target'] = "_self";
    $menu_var[$i]['icon']   = "fa-calendar";
    $i++;
}

//模組前台選單

//圖案
$TadUpFiles->set_col("WebLogo", $WebID, "1");
$web_logo = $TadUpFiles->get_pic_file();

//我的班級ID（陣列）
$MyWebs = MyWebID();
//目前瀏覽的是否是我的班級？
$isMyWeb = ($isAdmin) ? true : in_array($WebID, $MyWebs);

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
