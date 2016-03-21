<?php
include_once "../../mainfile.php";
//務必要在function.php之前，因為function.php會用到$WebID。
$WebID = isset($_REQUEST['WebID']) ? intval($_REQUEST['WebID']) : '';
include_once "function.php";

$is_ezclass = XOOPS_URL == "http://class.tn.edu.tw" ? true : false;
define('_IS_EZCLASS', $is_ezclass);

//目前觀看的班級
$Web = $WebName = $WebTitle = $WebOwner = $menu_var = $plugin_menu_var = "";

$i = 0;
if (!empty($WebID)) {
    $Web            = getWebInfo($WebID);
    $web_all_config = get_web_all_config($WebID);
    update_last_accessed($WebID);
    if ($Web and $Web['WebEnable'] == '1') {
        $WebName  = $Web['WebName'];
        $WebTitle = $Web['WebTitle'];
        $WebOwner = $Web['WebOwner'];

        if (!file_exists(XOOPS_ROOT_PATH . "/uploads/tad_web/{$WebID}/header.png")) {
            output_head_file($WebID);
        }

        if ($web_all_config['use_simple_menu'] == '1') {
            $simple_menu[$i]['id']     = $i;
            $simple_menu[$i]['title']  = _MD_TCW_CLASS_HOME;
            $simple_menu[$i]['url']    = "index.php?WebID={$WebID}";
            $simple_menu[$i]['target'] = "_self";
            $simple_menu[$i]['icon']   = "fa-home";
            $i++;

            $simple_menu[$i]['id']     = $i;
            $simple_menu[$i]['title']  = _MD_TCW_PLUGIN_MENU;
            $simple_menu[$i]['url']    = "#";
            $simple_menu[$i]['target'] = "_self";
            $simple_menu[$i]['icon']   = "fa-bars";

            //模組前台選單
            if (!file_exists(XOOPS_ROOT_PATH . "/uploads/tad_web/{$WebID}/menu_var.php")) {
                mk_menu_var_file($WebID);
            }
            include_once XOOPS_ROOT_PATH . "/uploads/tad_web/{$WebID}/menu_var.php";
            $plugin_menu_var = $menu_var;

            $menu_var[100]['id']     = $i;
            $menu_var[100]['title']  = _MD_TCW_TAG;
            $menu_var[100]['url']    = "tag.php?WebID={$WebID}";
            $menu_var[100]['target'] = "_self";
            $menu_var[100]['icon']   = "fa-tags";

            $simple_menu[$i]['submenu'] = $menu_var;
            $i++;
            $menu_var = $simple_menu;
        } else {
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
            $plugin_menu_var = $menu_var;

            $menu_var[100]['id']     = $i;
            $menu_var[100]['title']  = _MD_TCW_TAG;
            $menu_var[100]['url']    = "tag.php?WebID={$WebID}";
            $menu_var[100]['target'] = "_self";
            $menu_var[100]['icon']   = "fa-tags";

        }
    } elseif ($Web and $Web['WebEnable'] != '1' and $isMyWeb) {
        $WebName  = "[" . _MD_TCW_UNABLE . "] " . $Web['WebName'];
        $WebTitle = "[" . _MD_TCW_UNABLE . "] " . $Web['WebTitle'];
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
        $plugin_menu_var = $menu_var;
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

    $j            = 0;
    $sub_menu_var = "";

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
    $j++;

    $menu_var[$i]['submenu'] = $sub_menu_var;
    $i++;
}

// $interface_menu[_TAD_TO_MOD] = "index.php";
$menu_var[$i]['id']     = $i;
$menu_var[$i]['title']  = _MD_TCW_LINKTO;
$menu_var[$i]['url']    = "index.php";
$menu_var[$i]['target'] = "_blank";
$menu_var[$i]['icon']   = "fa-share-square-o";

$j            = 0;
$sub_menu_var = "";

$sub_menu_var[$j]['id']     = $j;
$sub_menu_var[$j]['title']  = _MD_TCW_HOME;
$sub_menu_var[$j]['url']    = "index.php";
$sub_menu_var[$j]['target'] = "_blank";
$sub_menu_var[$j]['icon']   = "fa-share-square-o";
$j++;

$sub_menu_var[$j]['id']     = $j;
$sub_menu_var[$j]['title']  = _MD_TCW_LEADERBOARD;
$sub_menu_var[$j]['url']    = "top.php";
$sub_menu_var[$j]['target'] = "_blank";
$sub_menu_var[$j]['icon']   = "fa-bar-chart";
$j++;

$sub_menu_var[$j]['id']     = $j;
$sub_menu_var[$j]['title']  = _MD_TCW_MENU_DISCUSS;
$sub_menu_var[$j]['url']    = "http://class.tn.edu.tw/modules/tad_discuss/discuss.php?BoardID=1";
$sub_menu_var[$j]['target'] = "_blank";
$sub_menu_var[$j]['icon']   = "fa-share-square-o";
$j++;

$sub_menu_var[$j]['id']     = $j;
$sub_menu_var[$j]['title']  = _MD_TCW_MENU_SUGGEST;
$sub_menu_var[$j]['url']    = "http://class.tn.edu.tw/modules/tad_discuss/discuss.php?BoardID=2";
$sub_menu_var[$j]['target'] = "_blank";
$sub_menu_var[$j]['icon']   = "fa-share-square-o";
$j++;

$sub_menu_var[$j]['id']     = $j;
$sub_menu_var[$j]['title']  = _MD_TCW_MENU_BOOKS;
$sub_menu_var[$j]['url']    = "http://class.tn.edu.tw/modules/tad_book3/index.php";
$sub_menu_var[$j]['target'] = "_blank";
$sub_menu_var[$j]['icon']   = "fa-share-square-o";
$j++;

$menu_var[$i]['submenu'] = $sub_menu_var;
$i++;

if ($isAdmin) {
    // $interface_menu[_MD_TADNEWS_TO_ADMIN] = "admin/main.php";
    $menu_var[$i]['id']     = $i;
    $menu_var[$i]['title']  = _MD_TCW_ADMIN;
    $menu_var[$i]['url']    = "admin/index.php";
    $menu_var[$i]['target'] = "_blank";
    $menu_var[$i]['icon']   = "fa-check-square-o";

    $j            = 0;
    $sub_menu_var = "";

    $sub_menu_var[$j]['id']     = $j;
    $sub_menu_var[$j]['title']  = _MD_TCW_ADMINPAGE;
    $sub_menu_var[$j]['url']    = "admin/index.php";
    $sub_menu_var[$j]['target'] = "_blank";
    $sub_menu_var[$j]['icon']   = "fa-check-square-o";
    $j++;

    $sub_menu_var[$j]['id']     = $j;
    $sub_menu_var[$j]['title']  = _MD_TCW_ADMINER;
    $sub_menu_var[$j]['url']    = XOOPS_URL . "/modules/tad_adm/pma.php";
    $sub_menu_var[$j]['target'] = "_blank";
    $sub_menu_var[$j]['icon']   = "fa-check-square-o";
    $j++;

    $menu_var[$i]['submenu'] = $sub_menu_var;
    $i++;

}

// die(var_export($_SESSION));
