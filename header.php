<?php
use Xmf\Request;
use XoopsModules\Tadtools\Utility;
require_once dirname(dirname(__DIR__)) . '/mainfile.php';
include_once 'preloads/autoloader.php';
//務必要在function.php之前，因為function.php會用到$WebID。

$WebID = Request::getInt('WebID');

require_once __DIR__ . '/function.php';
require_once __DIR__ . '/interface.php';

//目前觀看的班級
$WebName = $WebTitle = $WebOwner = $WebOwnerUid = '';
$Web = $menu_var = $plugin_menu_var = $web_all_config = [];

$i = 0;
if (!empty($WebID)) {
    $Web = get_tad_web($WebID);

    if ($Web) {
        $web_all_config = get_web_all_config($WebID);
        update_last_accessed($WebID);
    } else {
        header('location:index.php');
        exit;
    }

    if ($Web and '1' == $Web['WebEnable']) {
        $WebName = $Web['WebName'];
        $WebTitle = $Web['WebTitle'];
        $WebOwner = $Web['WebOwner'];
        $WebOwnerUid = $Web['WebOwnerUid'];

        if (!file_exists(XOOPS_ROOT_PATH . "/uploads/tad_web/{$WebID}/header.png")) {
            output_head_file($WebID);
            output_head_file_480($WebID);
        }

        if (isset($web_all_config['use_simple_menu']) and '1' == $web_all_config['use_simple_menu']) {
            $simple_menu[$i]['id'] = $i;
            $simple_menu[$i]['title'] = _MD_TCW_CLASS_HOME;
            $simple_menu[$i]['url'] = "index.php?WebID={$WebID}";
            $simple_menu[$i]['target'] = '_self';
            $simple_menu[$i]['icon'] = 'fa-home';
            $i++;

            $simple_menu[$i]['id'] = $i;
            $simple_menu[$i]['title'] = _MD_TCW_PLUGIN_MENU;
            $simple_menu[$i]['url'] = '#';
            $simple_menu[$i]['target'] = '_self';
            $simple_menu[$i]['icon'] = 'fa-bars';

            //模組前台選單
            if (!file_exists(XOOPS_ROOT_PATH . "/uploads/tad_web/{$WebID}/menu_var.php")) {
                mk_menu_var_file($WebID);
            }
            require_once XOOPS_ROOT_PATH . "/uploads/tad_web/{$WebID}/menu_var.php";
            $plugin_menu_var = $menu_var;

            $simple_menu[$i]['submenu'] = $menu_var;
            $i++;
            $menu_var = $simple_menu;

        } else {
            $menu_var[$i]['id'] = $i;
            $menu_var[$i]['title'] = _MD_TCW_CLASS_HOME;
            $menu_var[$i]['url'] = "index.php?WebID={$WebID}";
            $menu_var[$i]['target'] = '_self';
            $menu_var[$i]['icon'] = 'fa-home';
            $i++;

            //模組前台選單
            if (!file_exists(XOOPS_ROOT_PATH . "/uploads/tad_web/{$WebID}/menu_var.php")) {
                mk_menu_var_file($WebID);
            }
            require_once XOOPS_ROOT_PATH . "/uploads/tad_web/{$WebID}/menu_var.php";
            $plugin_menu_var = $menu_var;
        }
    } elseif ($Web and '1' != $Web['WebEnable'] and $isMyWeb) {
        $WebName = '[' . _MD_TCW_UNABLE . '] ' . $Web['WebName'];
        $WebTitle = '[' . _MD_TCW_UNABLE . '] ' . $Web['WebTitle'];
        $WebOwner = $Web['WebOwner'];

        if (!file_exists(XOOPS_ROOT_PATH . "/uploads/tad_web/{$WebID}/header.png")) {
            output_head_file($WebID);
            output_head_file_480($WebID);
        }

        $menu_var[$i]['id'] = $i;
        $menu_var[$i]['title'] = _MD_TCW_CLASS_HOME;
        $menu_var[$i]['url'] = "index.php?WebID={$WebID}";
        $menu_var[$i]['target'] = '_self';
        $menu_var[$i]['icon'] = 'fa-home';
        $i++;

        //模組前台選單
        if (!file_exists(XOOPS_ROOT_PATH . "/uploads/tad_web/{$WebID}/menu_var.php")) {
            mk_menu_var_file($WebID);
        }
        require_once XOOPS_ROOT_PATH . "/uploads/tad_web/{$WebID}/menu_var.php";
        $plugin_menu_var = $menu_var;
    } else {
        define('_DISPLAY_MODE', 'no');
    }
}
$i = count($menu_var);

if (!empty($WebID) and $isMyWeb) {
    $menu_var[$i]['id'] = $i;
    $menu_var[$i]['title'] = _MD_TCW_TOOLS;
    $menu_var[$i]['url'] = "config.php?WebID={$WebID}";
    $menu_var[$i]['target'] = '_self';
    $menu_var[$i]['icon'] = 'fa-check-square';

    $j = 0;
    $sub_menu_var = [];

    $sub_menu_var[$j]['id'] = $j;
    $sub_menu_var[$j]['title'] = _MD_TCW_WEB_TOOLS;
    $sub_menu_var[$j]['url'] = "config.php?WebID={$WebID}";
    $sub_menu_var[$j]['target'] = '_self';
    $sub_menu_var[$j]['icon'] = 'fa-check-square';
    $j++;

    $sub_menu_var[$j]['id'] = $j;
    $sub_menu_var[$j]['title'] = _MD_TCW_BLOCK_TOOLS;
    $sub_menu_var[$j]['url'] = "block.php?WebID={$WebID}";
    $sub_menu_var[$j]['target'] = '_self';
    $sub_menu_var[$j]['icon'] = 'fa-check-square';
    $j++;

    $menu_var[$i]['submenu'] = $sub_menu_var;
    $i++;
}

if ($is_ezclass) {
    $menu_var[$i]['id'] = $i;
    $menu_var[$i]['title'] = _MD_TCW_LINKTO;
    $menu_var[$i]['url'] = 'index.php';
    $menu_var[$i]['target'] = '_blank';
    $menu_var[$i]['icon'] = 'fa-share-from-square';

    $j = 0;
    $sub_menu_var = [];

    $sub_menu_var[$j]['id'] = $j;
    $sub_menu_var[$j]['title'] = _MD_TCW_HOME;
    $sub_menu_var[$j]['url'] = $is_ezclass ? XOOPS_URL : 'index.php';
    $sub_menu_var[$j]['target'] = '_blank';
    $sub_menu_var[$j]['icon'] = 'fa-share-from-square';
    $j++;

    $sub_menu_var[$j]['id'] = $j;
    $sub_menu_var[$j]['title'] = _MD_TCW_LEADERBOARD;
    $sub_menu_var[$j]['url'] = 'top.php';
    $sub_menu_var[$j]['target'] = '_blank';
    $sub_menu_var[$j]['icon'] = 'fa-chart-column';
    $j++;

    $sub_menu_var[$j]['id'] = $j;
    $sub_menu_var[$j]['title'] = _MD_TCW_MENU_DISCUSS;
    $sub_menu_var[$j]['url'] = _EZCLASS . '/modules/tad_discuss/discuss.php?BoardID=1';
    $sub_menu_var[$j]['target'] = '_blank';
    $sub_menu_var[$j]['icon'] = 'fa-share-from-square';
    $j++;

    $sub_menu_var[$j]['id'] = $j;
    $sub_menu_var[$j]['title'] = _MD_TCW_MENU_SUGGEST;
    $sub_menu_var[$j]['url'] = _EZCLASS . '/modules/tad_discuss/discuss.php?BoardID=2';
    $sub_menu_var[$j]['target'] = '_blank';
    $sub_menu_var[$j]['icon'] = 'fa-share-from-square';
    $j++;

    $sub_menu_var[$j]['id'] = $j;
    $sub_menu_var[$j]['title'] = _MD_TCW_MENU_BOOKS;
    $sub_menu_var[$j]['url'] = _EZCLASS . '/modules/tad_book3/index.php';
    $sub_menu_var[$j]['target'] = '_blank';
    $sub_menu_var[$j]['icon'] = 'fa-share-from-square';
    $j++;

    $menu_var[$i]['submenu'] = $sub_menu_var;
    $i++;
}

if ($_SESSION['tad_web_adm']) {
    $menu_var[$i]['id'] = $i;
    $menu_var[$i]['title'] = _MD_TCW_ADMIN;
    $menu_var[$i]['url'] = 'admin/index.php';
    $menu_var[$i]['target'] = '_blank';
    $menu_var[$i]['icon'] = 'fa-check-square';

    $j = 0;
    $sub_menu_var = [];

    $sub_menu_var[$j]['id'] = $j;
    $sub_menu_var[$j]['title'] = _MD_TCW_ADMINPAGE;
    $sub_menu_var[$j]['url'] = 'admin/index.php';
    $sub_menu_var[$j]['target'] = '_blank';
    $sub_menu_var[$j]['icon'] = 'fa-check-square';
    $j++;

    $sub_menu_var[$j]['id'] = $j;
    $sub_menu_var[$j]['title'] = _MD_TCW_ADMINER;
    $sub_menu_var[$j]['url'] = XOOPS_URL . '/modules/tad_adm/pma.php';
    $sub_menu_var[$j]['target'] = '_blank';
    $sub_menu_var[$j]['icon'] = 'fa-check-square';
    $j++;

    $menu_var[$i]['submenu'] = $sub_menu_var;
    $i++;
}
