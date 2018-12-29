<?php
$xoopsTpl->assign("op", $op);
$xoopsTpl->assign('WebTitle', $WebTitle);
$xoopsTpl->assign('Web', $Web);
$xoopsTpl->assign('_IS_EZCLASS', _IS_EZCLASS);
// $xoopsTpl->assign('login_from', $_COOKIE['login_from']);

if (isset($LoginWebID)) {
    $xoopsTpl->assign("LoginMemID", $LoginMemID);
    $xoopsTpl->assign("LoginMemName", $LoginMemName);
    $xoopsTpl->assign("LoginMemNickName", $LoginMemNickName);
    $xoopsTpl->assign("LoginWebID", $LoginWebID);
    $LoginCateID = isset($LoginCateID) ? $LoginCateID : '';
    $xoopsTpl->assign("LoginCateID", $LoginCateID);
}

if (isset($LoginParentID)) {
    $xoopsTpl->assign("LoginParentID", $LoginParentID);
    $xoopsTpl->assign("LoginParentName", $LoginParentName);
    $xoopsTpl->assign("LoginParentMemID", $LoginParentMemID);
    $xoopsTpl->assign("LoginWebID", $LoginWebID);
    $LoginCateID = isset($LoginCateID) ? $LoginCateID : '';
    $xoopsTpl->assign("LoginCateID", $LoginCateID);
}

if (!empty($plugin)) {
    $xoopsTpl->assign('plugin', $plugin);
    $xoopsTpl->assign('now_plugin', $plugin_menu_var[$plugin]);
}

$xoopsTpl->assign('menu_var', $menu_var);
$xoopsTpl->assign('isMyWeb', $isMyWeb);
$xoopsTpl->assign('your_version', $xoopsModule->version());
if (!defined('_DISPLAY_MODE')) {
    define('_DISPLAY_MODE', 'page');
}
$xoopsTpl->assign('web_display_mode', _DISPLAY_MODE);

if ($WebID and _DISPLAY_MODE == 'home') {
    $xoopsTpl->assign('xoops_pagetitle', $WebTitle);
    $xoopsTpl->assign('xoops_sitename', $WebName);
    $xoopsTpl->assign('logo_img', XOOPS_URL . "/uploads/tad_web/{$WebID}/header_480.png");
    $xoopsTpl->assign('fb_description', $WebName);
} elseif ($WebID and _DISPLAY_MODE != 'home') {
    $xoopsTpl->assign('xoops_sitename', $WebName);
    $xoopsTpl->assign('logo_img', XOOPS_URL . "/uploads/tad_web/{$WebID}/header_480.png");
} else {
    // $xoopsTpl->assign("toolbar", toolbar_bootstrap($interface_menu));
}

if (file_exists(XOOPS_ROOT_PATH . "/modules/tadtools/FooTable.php")) {
    include_once XOOPS_ROOT_PATH . "/modules/tadtools/FooTable.php";
    $FooTable   = new FooTable();
    $FooTableJS = $FooTable->render();
    $xoopsTpl->assign('FooTableJS', $FooTableJS);
}

//區塊
get_tad_web_blocks($WebID, _DISPLAY_MODE);

//登入區塊及選單
if (!empty($_SESSION['LoginMemID']) or !empty($_SESSION['LoginParentID']) or $xoopsUser) {
    tad_web_my_menu($WebID);
    get_marquee();
} else {
    tad_web_login($WebID);
}

//取得通知內容
function get_marquee()
{

    global $xoopsDB, $xoopsTpl;
    if (!empty($_SESSION['LoginMemID'])) {
        $user_kind = 'mem';
    } elseif (!empty($_SESSION['LoginParentID'])) {
        $user_kind = 'parent';
    } else {
        $user_kind = 'master';
    }

    $sql    = "select * from `" . $xoopsDB->prefix("tad_web_notice") . "` where `NoticeWho` like '%{$user_kind}%' or `NoticeWho`='' order by NoticeDate desc limit 0,5";
    $result = $xoopsDB->query($sql)
    or web_error($sql, __FILE__, _LINE__);
    $data_arr = array();
    while ($data = $xoopsDB->fetchArray($result)) {
        $NoticeID                               = $data['NoticeID'];
        $data_arr[$NoticeID]                    = $data;
        $data_arr[$NoticeID]['NoticeShortDate'] = substr($data['NoticeDate'], 0, 10);
    }
    $xoopsTpl->assign('marquee_arr', $data_arr);

    if ($data_arr) {
        if (!file_exists(XOOPS_ROOT_PATH . "/modules/tadtools/fancybox.php")) {
            redirect_header("index.php", 3, _MA_NEED_TADTOOLS);
        }
        include_once XOOPS_ROOT_PATH . "/modules/tadtools/fancybox.php";
        $fancybox = new fancybox('.sho_notice', '480px', '480px');
        $fancybox->render(false);
    }
}

//我的選單
function tad_web_my_menu($WebID)
{
    global $xoopsDB, $xoopsTpl, $xoopsUser, $MyWebID, $xoopsModuleConfig, $WebTitle, $isAdmin;
    include_once XOOPS_ROOT_PATH . '/modules/tad_web/function_block.php';
    //未登入
    if (!$xoopsUser and empty($_SESSION['LoginMemID']) and empty($_SESSION['LoginParentID'])) {

    } else {

        if (!empty($_SESSION['LoginMemID'])) {
            $user_kind   = 'mem';
            $user_name   = $_SESSION['LoginMemName'];
            $defaltWebID = $_SESSION['LoginWebID'];
            $back_home   = empty($WebTitle) ? _MD_TCW_HOME : sprintf(_MD_TCW_TO_MY_WEB, $WebTitle);
            $defaltWebID = $_SESSION['LoginWebID'];
            $add_power   = array('discuss');
            //小幫手
            $sql    = "select a.`CateID`,b.ColName from `" . $xoopsDB->prefix('tad_web_cate_assistant') . "` as a join `" . $xoopsDB->prefix('tad_web_cate') . "` as b on a.`CateID`=b.`CateID` where a.`AssistantType`='MemID' and a.`AssistantID`='{$_SESSION['LoginMemID']}'";
            $result = $xoopsDB->queryF($sql) or web_error($sql, __FILE__, _LINE__);
            while (list($CateID, $plugin_dir) = $xoopsDB->fetchRow($result)) {
                $add_power[]                          = $plugin_dir;
                $_SESSION['isAssistant'][$plugin_dir] = $CateID;
                $_SESSION['AssistantType'][$CateID]   = 'MemID';
                $_SESSION['AssistantID'][$CateID]     = $_SESSION['LoginMemID'];
            }
            // die(var_export($add_power));
        } elseif (!empty($_SESSION['LoginParentID'])) {
            $user_kind   = 'parent';
            $user_name   = $_SESSION['LoginParentName'];
            $defaltWebID = $_SESSION['LoginWebID'];
            $back_home   = empty($WebTitle) ? _MD_TCW_HOME : sprintf(_MD_TCW_TO_MY_WEB, $WebTitle);
            $defaltWebID = $_SESSION['LoginWebID'];
            $add_power   = array('discuss'); //小幫手
            $sql         = "select a.`CateID`,b.ColName from `" . $xoopsDB->prefix('tad_web_cate_assistant') . "` as a join `" . $xoopsDB->prefix('tad_web_cate') . "` as b on a.`CateID`=b.`CateID` where a.`AssistantType`='ParentID' and a.`AssistantID`='{$_SESSION['LoginParentID']}'";
            $result      = $xoopsDB->queryF($sql) or web_error($sql, __FILE__, _LINE__);
            while (list($CateID, $plugin_dir) = $xoopsDB->fetchRow($result)) {
                $add_power[]                          = $plugin_dir;
                $_SESSION['isAssistant'][$plugin_dir] = $CateID;
                $_SESSION['AssistantType'][$CateID]   = 'ParentID';
                $_SESSION['AssistantID'][$CateID]     = $_SESSION['LoginParentID'];
            }
        } else {
            $user_kind = 'xoops';
            $user_name = $xoopsUser->name();
            $add_power = array();
            $MyWebID   = MyWebID('1');
            $DefWebID  = isset($_REQUEST['WebID']) ? intval($_REQUEST['WebID']) : '';

            $uid = $xoopsUser->uid();

            $AllMyWebID = implode("','", $MyWebID);

            if ($MyWebID) {
                $sql = "select * from " . $xoopsDB->prefix("tad_web") . " where WebID in ('{$AllMyWebID}') order by WebSort";
                //die($sql);
                $result = $xoopsDB->query($sql) or web_error($sql, __FILE__, _LINE__);
                //$web_num = $xoopsDB->getRowsNum($result);
                $i = $defalt_used_size = 0;

                $defaltWebID = 0;
                while ($all = $xoopsDB->fetchArray($result)) {
                    foreach ($all as $k => $v) {
                        $$k = $v;
                    }
                    if (!empty($DefWebID) and $WebID == $DefWebID) {
                        $defaltWebID      = $WebID;
                        $defaltWebTitle   = $WebTitle;
                        $defaltWebName    = $WebName;
                        $defalt_used_size = $used_size;
                    } elseif (empty($defaltWebID)) {
                        $defaltWebID    = $WebID;
                        $defaltWebTitle = $WebTitle;
                        $defaltWebName  = $WebName;
                    }

                    $webs[$i]['title'] = $WebTitle;
                    $webs[$i]['WebID'] = $WebID;
                    $webs[$i]['name']  = $WebName;
                    $webs[$i]['url']   = preg_match('/modules\/tad_web/', $_SERVER['PHP_SELF']) ? $_SERVER['PHP_SELF'] . "?WebID={$WebID}" : XOOPS_URL . "/modules/tad_web/index.php?WebID={$WebID}";

                    $i++;
                }

                $web_num   = $i;
                $back_home = empty($defaltWebName) ? _MD_TCW_HOME : sprintf(_MD_TCW_TO_MY_WEB, $defaltWebName);

                if (!defined('_SHOW_UNABLE')) {
                    define('_SHOW_UNABLE', '1');
                }
                $space_quota = get_web_config("space_quota", $defaltWebID);
                $space_quota = empty($space_quota) ? 500 : $space_quota;
                $quota       = empty($space_quota) ? $xoopsModuleConfig['user_space_quota'] : $space_quota;

                $size       = size2mb($defalt_used_size);
                $percentage = round($size / $quota, 2) * 100;
                // $size        = get_web_config("used_size", $defaltWebID);
                // if ($isAdmin) {
                //     die("defalt_used_size={$defalt_used_size}, size={$size}, quota={$quota}, percentage={$percentage},");
                // }
                if ($percentage <= 70) {
                    $progress_color = 'success';
                } elseif ($percentage <= 90) {
                    $progress_color = 'warning';
                } elseif ($percentage > 90) {
                    $progress_color = 'danger';
                }
            }

            $xoopsTpl->assign('size', $size);
            $xoopsTpl->assign('quota', $quota);
            $xoopsTpl->assign('percentage', $percentage);
            $xoopsTpl->assign('progress_color', $progress_color);
            $xoopsTpl->assign('webs', $webs);
            $xoopsTpl->assign('web_num', $web_num);

            if (isset($_SESSION['isAssistant'])) {
                $xoopsTpl->assign('isAssistant', $_SESSION['isAssistant']);
                $xoopsTpl->assign('AssistantTypeArr', $_SESSION['AssistantType']);
                $xoopsTpl->assign('AssistantIDArr', $_SESSION['AssistantID']);
            }

            //已關閉網站
            $MyClosedWebID    = MyWebID('0');
            $AllMyClosedWebID = implode("','", $MyClosedWebID);
            if ($MyClosedWebID) {
                $sql    = "select * from " . $xoopsDB->prefix("tad_web") . " where WebID in ('{$AllMyClosedWebID}') order by WebSort";
                $result = $xoopsDB->query($sql) or web_error($sql, __FILE__, _LINE__);
                $i      = 0;

                while ($all = $xoopsDB->fetchArray($result)) {
                    foreach ($all as $k => $v) {
                        $$k = $v;
                    }

                    $closed_webs[$i]['title'] = $WebTitle;
                    $closed_webs[$i]['WebID'] = $WebID;
                    $closed_webs[$i]['name']  = $WebName;
                    $closed_webs[$i]['url']   = XOOPS_URL . "/modules/tad_web/config.php?WebID={$WebID}&op=enable_my_web";

                    $i++;
                }
            }

            $closed_webs = isset($closed_webs) ? $closed_webs : '';
            $xoopsTpl->assign('closed_webs', $closed_webs);
        }

        if (!file_exists(XOOPS_ROOT_PATH . "/uploads/tad_web/{$defaltWebID}/menu_var.php")) {
            mk_menu_var_file($defaltWebID);
        }
        include XOOPS_ROOT_PATH . "/uploads/tad_web/{$defaltWebID}/menu_var.php";

        $xoopsTpl->assign('user_kind', $user_kind);
        $xoopsTpl->assign('say_hi', sprintf(_MD_TCW_HI, $user_name));
        $xoopsTpl->assign('back_home', $back_home);
        $xoopsTpl->assign('defaltWebID', $defaltWebID);
        // die(var_export($menu_var));
        $xoopsTpl->assign('menu_plugins', $menu_var);
        $xoopsTpl->assign('add_power', $add_power);
    }
}

//以流水號秀出某筆tad_web_mems資料內容
function tad_web_login($WebID, $config = array())
{
    global $xoopsUser, $xoopsConfig, $xoopsTpl;

    if ($xoopsUser or !empty($_SESSION['LoginMemID']) or !empty($_SESSION['LoginParentID'])) {
        return;
    }
    // $http = 'http://';
    // if (!empty($_SERVER['HTTPS'])) {
    //     $http = ($_SERVER['HTTPS'] == 'on') ? 'https://' : 'http://';
    // }

    // $domain     = ($_SERVER['HTTP_HOST'] != 'localhost') ? $_SERVER['HTTP_HOST'] : false;
    // $login_from = $http . $_SERVER["HTTP_HOST"] . $_SERVER['REQUEST_URI'];
    // setcookie("login_from", $login_from, '/', $domain, false);
    // $_SESSION['login_from'] = $login_from;

    $login_config = get_web_config('login_config', $WebID);
    $login_config = empty($login_config) ? array() : explode(';', $login_config);
    // die(var_export($login_config));
    $about_setup = get_plugin_setup_values($WebID, "aboutus");
    if ($_GET['test'] == '1') {
        die(var_export($about_setup));
    }

    $auth_method = get_sys_openid();
    if ($auth_method) {

        $i = 0;

        // $login_method_arr = explode(';', $login_method);
        foreach ($auth_method as $method) {
            if (!empty($login_config) and !in_array($method, $login_config)) {
                // die(var_export($login_config));
                continue;
            }
            $method_const = "_" . strtoupper($method);
            $loginTitle   = sprintf(_MD_TCW_OPENID_LOGIN, constant($method_const));

            if ($method == "facebook") {
                $tlogin[$i]['link'] = facebook_login('return');

            } elseif ($method == "google") {
                $tlogin[$i]['link'] = google_login('return');

            } else {
                $tlogin[$i]['link'] = XOOPS_URL . "/modules/tad_login/index.php?login&op={$method}";
            }
            $tlogin[$i]['img']  = XOOPS_URL . "/modules/tad_login/images/{$method}.png";
            $tlogin[$i]['text'] = $loginTitle;

            $i++;
        }

        // if ($_GET['test'] == '1') {
        //     die(var_export($auth_method));
        // }

        $openid = '1';
        $xoopsTpl->assign('tlogin', $tlogin);
    } else {
        $openid = '0';
    }
    $xoopsTpl->assign('openid', $openid);

    if ($about_setup['mem_parents'] == '1') {

        $ys = get_seme();
        include_once XOOPS_ROOT_PATH . "/modules/tad_web/class/cate.php";
        $web_cate = new web_cate($WebID, "aboutus", "tad_web_link_mems");
        // $web_cate->set_default_value(sprintf(_MD_TCW_SEME_CATE, $ys[0]));
        $web_cate->set_default_option_text(sprintf(_MD_TCW_SELECT_SEME, $about_setup['class_title']));
        $web_cate->set_col_md(3, 12);
        $web_cate->set_custom_change_js("$.post('" . XOOPS_URL . "/modules/tad_web/plugins/aboutus/get_mems.php', { op: 'get_parents', WebID: '{$WebID}', CateID: $('#loginCateID').val()}, function(data){
                      $('#select_mems').html(data);
                      $('#select_mems').show();
                  });");
        $web_cate->set_var('menu_id', 'loginCateID');
        $cate_menu = $web_cate->cate_menu('', 'page', false, false, false);
        $xoopsTpl->assign('login_cate_menu', $cate_menu);
        $xoopsTpl->assign('mem_parents', $about_setup['mem_parents']);
    }

    $xoopsTpl->assign('student_title', $about_setup['student_title']);
}

//取得多人網頁的內部區塊(在footer.php執行)
function get_tad_web_blocks($WebID = null, $web_display_mode = '')
{
    global $xoopsTpl, $xoopsDB, $Web, $isAdmin;

    $power           = new power($WebID);
    $myts            = MyTextSanitizer::getInstance();
    $block['block1'] = $block['block2'] = $block['block3'] = $block['block4'] = $block['block5'] = $block['block6'] = $block['side'] = array();

    $block_tpl = get_all_blocks('tpl');
    $dir       = XOOPS_ROOT_PATH . "/modules/tad_web/plugins/";

    $andBlockPosition = $web_display_mode == 'home' ? '' : "and `BlockPosition`='side'";
    // die(var_export($Web));
    if (isset($Web['WebEnable']) and $Web['WebEnable'] != "1") {
        $andForceMenu = "and (`BlockEnable`='1' or `BlockName`='my_menu')";
    } else {
        $andForceMenu = "and `BlockEnable`='1'";
    }
    // die($andForceMenu);
    //取得區塊位置
    $sql = "select * from " . $xoopsDB->prefix("tad_web_blocks") . " where `WebID`='{$WebID}'  $andForceMenu $andBlockPosition order by `BlockPosition`,`BlockSort`";
    // if ($isAdmin) {
    //     die($sql);
    // }
    $result = $xoopsDB->queryF($sql) or web_error($sql, __FILE__, _LINE__);

    while ($all = $xoopsDB->fetchArray($result)) {
        foreach ($all as $k => $v) {
            $$k = $v;
        }

        //檢查權限
        $have_power = $power->check_power("read", "BlockID", $BlockID);
        if (!$have_power) {
            continue;
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
                $blocks_arr['BlockContent'] = "<iframe title=\"{$BlockTitle}\" src=\"{$BlockContent}\" style=\"width: 100%; height: 300px; overflow: auto; border:none;\"></iframe>";
            } elseif ($config['content_type'] == "js") {
                $blocks_arr['BlockContent'] = $BlockContent;

            } else {
                $blocks_arr['BlockContent'] = $myts->displayTarea($BlockContent, 1);
            }
        } else {
            if (file_exists("{$dir}{$plugin}/blocks.php")) {
                // if ($isAdmin) {
                //     echo "{$dir}{$plugin}/blocks.php<br>";
                // }
                include_once "{$dir}{$plugin}/blocks.php";
            }

            $blocks_arr['tpl']          = $block_tpl[$BlockName];
            $blocks_arr['BlockContent'] = call_user_func($BlockName, $WebID, $config);
            $blocks_arr['config']       = $config;
        }
        $block[$BlockPosition][$BlockSort] = $blocks_arr;
    }
    // if ($_GET['test'] == '1') {
    //     die(var_export($block));
    // }
    // die(var_export($block['side']));

    // if ($isAdmin) {
    //     exit;
    // }
    $xoopsTpl->assign('center_block1', $block['block1']);
    $xoopsTpl->assign('center_block2', $block['block2']);
    $xoopsTpl->assign('center_block3', $block['block3']);
    $xoopsTpl->assign('center_block4', $block['block4']);
    $xoopsTpl->assign('center_block5', $block['block5']);
    $xoopsTpl->assign('center_block6', $block['block6']);
    $xoopsTpl->assign('side_block', $block['side']);

}
