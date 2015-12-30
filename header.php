<?php
include_once "../../mainfile.php";
//勿必要在function.php之前，因為function.php會用到$WebID。
$WebID = isset($_REQUEST['WebID']) ? intval($_REQUEST['WebID']) : '';
include_once "function.php";
include_once "class/cate.php";

$is_ezclass = XOOPS_URL == "http://class.tn.edu.tw" ? true : false;
define(_IS_EZCLASS, $is_ezclass);

//判斷是否對該模組有管理權限
$isAdmin    = false;
$LoginMemID = $LoginMemName = $LoginMemNickName = $LoginWebID = '';
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
$Web     = $WebName     = $WebTitle     = $WebOwner     = $menu_var     = "";
$MyWebs  = array();
$isMyWeb = false;
$i       = 0;

if ($xoopsUser) {
    //我的班級ID（陣列）
    $MyWebs = MyWebID();
}

if (!empty($WebID)) {
    $Web = getWebInfo($WebID);
    update_last_accessed($WebID);
    if ($Web) {
        $WebName  = $Web['WebName'];
        $WebTitle = $Web['WebTitle'];
        $WebOwner = $Web['WebOwner'];

        if (!file_exists(XOOPS_ROOT_PATH . "/uploads/tad_web/{$WebID}/header.png")) {
            output_head_file($WebID);
        }

        $menu_var[$i]['id']     = $i;
        $menu_var[$i]['title']  = _MD_TCW_CLASS_HOME;
        $menu_var[$i]['url']    = "index.php?WebID={$WebID}";
        $menu_var[$i]['target'] = "_self";
        $menu_var[$i]['icon']   = "fa-home";
        $i++;

        //模組前台選單
        if (!file_exists(XOOPS_ROOT_PATH . "/uploads/tad_web/{$WebID}/menu_var.php")) {
            mk_menu_var_file($WebID);
        }
        include_once XOOPS_ROOT_PATH . "/uploads/tad_web/{$WebID}/menu_var.php";

        if ($xoopsUser) {
            //目前瀏覽的是否是我的班級？
            $isMyWeb = ($isAdmin) ? true : in_array($WebID, $MyWebs);
        }
    } else {
        define('_DISPLAY_MODE', 'no');
    }
}

$i = sizeof($menu_var);

if ($isMyWeb) {
    $menu_var[$i]['id']     = $i;
    $menu_var[$i]['title']  = _MD_TCW_TOOLS;
    $menu_var[$i]['url']    = "config.php?WebID={$WebID}";
    $menu_var[$i]['target'] = "_self";
    $menu_var[$i]['icon']   = "fa-check-square-o";

    $j                          = 0;
    $sub_menu_var[$j]['id']     = $j;
    $sub_menu_var[$j]['title']  = _MD_TCW_WEB_TOOLS;
    $sub_menu_var[$j]['url']    = "config.php?WebID={$WebID}";
    $sub_menu_var[$j]['target'] = "_self";
    $sub_menu_var[$j]['icon']   = "fa-check-square-o";
    $j++;
    $sub_menu_var[$j]['id']     = $j;
    $sub_menu_var[$j]['title']  = _MD_TCW_BLOCK_TOOLS;
    $sub_menu_var[$j]['url']    = "block.php?WebID={$WebID}";
    $sub_menu_var[$j]['target'] = "_self";
    $sub_menu_var[$j]['icon']   = "fa-check-square-o";

    $menu_var[$i]['submenu'] = $sub_menu_var;
    $i++;
}

$interface_menu[_TAD_TO_MOD] = "index.php";
$menu_var[$i]['id']          = $i;
$menu_var[$i]['title']       = _MD_TCW_HOME;
$menu_var[$i]['url']         = "index.php";
$menu_var[$i]['target']      = "_self";
$menu_var[$i]['icon']        = "fa-share-square-o";
$i++;

if ($isAdmin) {
    $interface_menu[_MD_TADNEWS_TO_ADMIN] = "admin/main.php";
    $menu_var[$i]['id']                   = $i;
    $menu_var[$i]['title']                = _MD_TCW_ADMIN;
    $menu_var[$i]['url']                  = "admin/index.php";
    $menu_var[$i]['target']               = "_self";
    $menu_var[$i]['icon']                 = "fa-check-square-o";
    $i++;

}

// die(var_export($_SESSION));
