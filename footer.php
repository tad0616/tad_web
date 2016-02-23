<?php
$xoopsTpl->assign("op", $op);
$xoopsTpl->assign('WebTitle', $WebTitle);
$xoopsTpl->assign('Web', $Web);
if (isset($LoginWebID)) {
    $xoopsTpl->assign("LoginMemID", $LoginMemID);
    $xoopsTpl->assign("LoginMemName", $LoginMemName);
    $xoopsTpl->assign("LoginMemNickName", $LoginMemNickName);
    $xoopsTpl->assign("LoginWebID", $LoginWebID);
}

if (!empty($plugin)) {
    $xoopsTpl->assign('plugin', $plugin);
    $xoopsTpl->assign('now_plugin', $menu_var[$plugin]);
}

$xoopsTpl->assign('menu_var', $menu_var);
$xoopsTpl->assign('isMyWeb', $isMyWeb);
$xoopsTpl->assign('bootstrap', $_SESSION['bootstrap']);
$xoopsTpl->assign('your_version', $xoopsModule->version());
if (!defined('_DISPLAY_MODE')) {
    define('_DISPLAY_MODE', 'page');
}
$xoopsTpl->assign('web_display_mode', _DISPLAY_MODE);

if ($WebID and _DISPLAY_MODE == 'home') {
    $xoopsTpl->assign('xoops_pagetitle', $WebTitle);
    $xoopsTpl->assign('xoops_sitename', $WebName);
    $xoopsTpl->assign('logo_img', XOOPS_URL . "/uploads/tad_web/{$WebID}/header.png");
    $xoopsTpl->assign('fb_description', $WebName);
} elseif ($WebID and _DISPLAY_MODE != 'home') {
    $xoopsTpl->assign('xoops_sitename', $WebName);
} else {
    $xoopsTpl->assign("toolbar", toolbar_bootstrap($interface_menu));
}

if (file_exists(XOOPS_ROOT_PATH . "/modules/tadtools/FooTable.php")) {
    include_once XOOPS_ROOT_PATH . "/modules/tadtools/FooTable.php";
    $FooTable   = new FooTable();
    $FooTableJS = $FooTable->render();
    $xoopsTpl->assign('FooTableJS', $FooTableJS);
}

//區塊
if ($WebID and _DISPLAY_MODE == 'no') {
    $sql    = "select * from " . $xoopsDB->prefix("tad_web_blocks") . " where `WebID`='{$WebID}' and `BlockName`='login'";
    $result = $xoopsDB->queryF($sql) or web_error($sql);
    $all    = $xoopsDB->fetchArray($result);
    foreach ($all as $k => $v) {
        $$k = $v;
    }

    $blocks_arr           = $all;
    $config               = json_decode($BlockConfig, true);
    $blocks_arr['config'] = $config;

    if (file_exists("{$dir}{$plugin}/blocks.php")) {
        include_once "{$dir}{$plugin}/blocks.php";
    }

    $blocks_arr['tpl']          = $block_tpl[$BlockName];
    $blocks_arr['BlockContent'] = call_user_func($BlockName, $WebID, $config);
    $blocks_arr['config']       = $config;
    if ($_GET['test'] == '1') {
        die(var_export($blocks_arr));
    }
    $xoopsTpl->assign('block', $blocks_arr);

} else {
    get_tad_web_blocks($WebID, _DISPLAY_MODE);
}

//取得多人網頁的內部區塊(在footer.php執行)
function get_tad_web_blocks($WebID = null, $web_display_mode = '')
{
    global $xoopsTpl, $xoopsDB, $Web;
    $myts            = &MyTextSanitizer::getInstance();
    $block['block1'] = $block['block2'] = $block['block3'] = $block['block4'] = $block['block5'] = $block['block6'] = $block['side'] = array();

    $block_tpl = get_all_blocks('tpl');
    $dir       = XOOPS_ROOT_PATH . "/modules/tad_web/plugins/";

    $andBlockPosition = $web_display_mode == 'home' ? '' : "and `BlockPosition`='side'";

    if ($Web['WebEnable'] != "1") {
        $andForceMenu = "and (`BlockEnable`='1' or `BlockName`='my_menu')";

    } else {
        $andForceMenu = "and `BlockEnable`='1'";
    }
    //取得區塊位置
    $sql = "select * from " . $xoopsDB->prefix("tad_web_blocks") . " where `WebID`='{$WebID}'  $andForceMenu $andBlockPosition order by `BlockPosition`,`BlockSort`";
    // if ($_GET['test'] == '1') {
    //     die($sql);
    // }
    $result = $xoopsDB->queryF($sql) or web_error($sql);

    while ($all = $xoopsDB->fetchArray($result)) {
        foreach ($all as $k => $v) {
            $$k = $v;
        }

        if ($Web['WebEnable'] != "1" and $BlockName == "my_menu") {
            $all['BlockPosition'] = $BlockPosition = "side";

        }
        $blocks_arr           = $all;
        $config               = json_decode($BlockConfig, true);
        $blocks_arr['config'] = $config;

        if ($plugin == "xoops") {
            $blocks_arr['tpl'] = '';
        } elseif ($plugin == "custom" or $plugin == "share") {
            if ($config['content_type'] == "iframe") {
                $blocks_arr['BlockContent'] = "<iframe src=\"{$BlockContent}\" style=\"width: 100%; height: 300px; overflow: auto; border:none;\"></iframe>";
            } elseif ($config['content_type'] == "js") {
                $blocks_arr['BlockContent'] = $BlockContent;

            } else {
                $blocks_arr['BlockContent'] = $myts->displayTarea($BlockContent, 1);
            }
        } else {
            if (file_exists("{$dir}{$plugin}/blocks.php")) {
                include_once "{$dir}{$plugin}/blocks.php";
            }

            $blocks_arr['tpl']          = $block_tpl[$BlockName];
            $blocks_arr['BlockContent'] = call_user_func($BlockName, $WebID, $config);
            $blocks_arr['config']       = $config;
        }
        $block[$BlockPosition][$BlockSort] = $blocks_arr;
    }

    // die(var_export($block['side']));

    $xoopsTpl->assign('center_block1', $block['block1']);
    $xoopsTpl->assign('center_block2', $block['block2']);
    $xoopsTpl->assign('center_block3', $block['block3']);
    $xoopsTpl->assign('center_block4', $block['block4']);
    $xoopsTpl->assign('center_block5', $block['block5']);
    $xoopsTpl->assign('center_block6', $block['block6']);
    $xoopsTpl->assign('side_block', $block['side']);

}
