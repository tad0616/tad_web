<?php
use XoopsModules\Tadtools\FancyBox;
use XoopsModules\Tadtools\FooTable;
use XoopsModules\Tadtools\Utility;
use XoopsModules\Tad_login\Tools as TadLoginTools;
use XoopsModules\Tad_web\Power;
use XoopsModules\Tad_web\Tools as TadWebTools;
use XoopsModules\Tad_web\WebCate;

$xoTheme->addStylesheet('modules/tad_web/css/module.css');
$xoTheme->addStylesheet('modules/tadtools/css/xoops.css');

$xoopsTpl->assign('op', $op);
$xoopsTpl->assign('WebTitle', $WebTitle);
$xoopsTpl->assign('Web', $Web);
$xoopsTpl->assign('_IS_EZCLASS', _IS_EZCLASS);

$xoopsTpl->assign('nowTS', time());
$xoopsTpl->assign('today', date('Y-m-d'));

if (isset($LoginWebID)) {
    $xoopsTpl->assign('LoginMemID', $LoginMemID);
    $xoopsTpl->assign('LoginMemName', $LoginMemName);
    $xoopsTpl->assign('LoginMemNickName', $LoginMemNickName);
    $xoopsTpl->assign('LoginWebID', $LoginWebID);
    $LoginCateID = isset($LoginCateID) ? $LoginCateID : '';
    $xoopsTpl->assign('LoginCateID', $LoginCateID);
}

if (isset($LoginParentID)) {
    $xoopsTpl->assign('LoginParentID', $LoginParentID);
    $xoopsTpl->assign('LoginParentName', $LoginParentName);
    $xoopsTpl->assign('LoginParentMemID', $LoginParentMemID);
    $xoopsTpl->assign('LoginWebID', $LoginWebID);
    $LoginCateID = isset($LoginCateID) ? $LoginCateID : '';
    $xoopsTpl->assign('LoginCateID', $LoginCateID);
}

// 當前 當前
if (!empty($plugin)) {
    $xoopsTpl->assign('plugin', $plugin);
    $xoopsTpl->assign('now_plugin', $plugin_menu_var[$plugin]);
}

// 從 menu_var.php 讀出來的檔，較原始
$xoopsTpl->assign('plugin_menu_var', $plugin_menu_var);
// 整理後的檔，會隨著簡化選單而架構有所不同
if (!empty($Web)) {
    $xoopsTpl->assign('menu_var', $menu_var);
}

$xoopsTpl->assign('isMyWeb', $isMyWeb);
$xoopsTpl->assign('your_version', $xoopsModule->version());
if (!defined('_DISPLAY_MODE')) {
    define('_DISPLAY_MODE', 'page');
}
$xoopsTpl->assign('web_display_mode', _DISPLAY_MODE);

if ($WebID and _DISPLAY_MODE === 'home') {
    $xoopsTpl->assign('xoops_pagetitle', $WebTitle);
    $xoopsTpl->assign('xoops_sitename', $WebName);
    $xoopsTpl->assign('logo_img', XOOPS_URL . "/uploads/tad_web/{$WebID}/header_480.png");
    $xoopsTpl->assign('fb_description', $WebName);
} elseif ($WebID and _DISPLAY_MODE !== 'home') {
    $xoopsTpl->assign('xoops_sitename', $WebName);
    $xoopsTpl->assign('logo_img', XOOPS_URL . "/uploads/tad_web/{$WebID}/header_480.png");
}

$FooTable = new FooTable();
$FooTable->render();

//區塊
get_tad_web_blocks($WebID);

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
    $user_kind = '';
    if (!empty($_SESSION['LoginMemID'])) {
        $user_kind = 'mem';
    } elseif (!empty($_SESSION['LoginParentID'])) {
        $user_kind = 'parent';
    } else {
        $user_kind = 'master';
    }

    $tad_web_notice_file = XOOPS_VAR_PATH . "/tad_web/tad_web_notice.json";
    if (file_exists($tad_web_notice_file)) {
        $data_arr = get_json_file($tad_web_notice_file);
    } else {
        $sql = 'SELECT * FROM `' . $xoopsDB->prefix('tad_web_notice') . '` ORDER BY `NoticeDate` DESC LIMIT 0,5';
        $result = Utility::query($sql) or Utility::web_error($sql, __FILE__, __LINE__);

        $data_arr = [];
        while (false !== ($data = $xoopsDB->fetchArray($result))) {
            $NoticeID = $data['NoticeID'];
            $data_arr[$NoticeID] = $data;
            $data_arr[$NoticeID]['NoticeShortDate'] = mb_substr($data['NoticeDate'], 0, 10);
        }
        file_put_contents($tad_web_notice_file, json_encode($data_arr, 256));
    }
    $marquee_arr = [];
    foreach ($data_arr as $NoticeID => $Notice) {
        if (!empty($Notice['NoticeWho'])) {
            if (strpos($Notice['NoticeWho'], $user_kind) !== false) {
                $marquee_arr[$NoticeID] = $Notice;
            }
        } else {
            $marquee_arr[$NoticeID] = $Notice;
        }
    }

    $xoopsTpl->assign('marquee_arr', $marquee_arr);

    if ($marquee_arr) {
        $FancyBox = new FancyBox('.show_notice', '480px', '480px');
        $FancyBox->render(false);
    }
}

//我的選單
function tad_web_my_menu($defaltWebID)
{
    global $xoopsDB, $xoopsTpl, $xoopsUser, $MyWebID, $xoopsModuleConfig, $WebTitle;

    Utility::test($_SESSION, 'session', 'dd');
    //未登入
    if (!$xoopsUser and empty($_SESSION['LoginMemID']) and empty($_SESSION['LoginParentID'])) {
    } else {
        if (!empty($_SESSION['LoginMemID'])) {
            $user_kind = 'mem';
            $user_name = $_SESSION['LoginMemName'];
            $defaltWebID = $_SESSION['LoginWebID'];
            $back_home = empty($WebTitle) ? _MD_TCW_HOME : sprintf(_MD_TCW_TO_MY_WEB, $WebTitle);
            // $add_power = ['discuss'];
            $add_power = [];
            //小幫手
            if (!isset($_SESSION['isAssistant'])) {
                $sql = 'SELECT `CateID`, `plugin` FROM `' . $xoopsDB->prefix('tad_web_cate_assistant') . '` WHERE `AssistantType`=? AND `AssistantID`=?';
                $result = Utility::query($sql, 'si', ['MemID', $_SESSION['LoginMemID']]) or Utility::web_error($sql, __FILE__, __LINE__);
                while (list($CateID, $plugin_dir) = $xoopsDB->fetchRow($result)) {
                    $add_power[] = $plugin_dir;
                    $_SESSION['isAssistant'][$plugin_dir] = $CateID;
                    $_SESSION['AssistantType'][$CateID] = 'MemID';
                    $_SESSION['AssistantID'][$CateID] = $_SESSION['LoginMemID'];
                }
            } else {
                $add_power = array_keys($_SESSION['isAssistant']);
            }
            // die(var_export($add_power));
        } elseif (!empty($_SESSION['LoginParentID'])) {
            $user_kind = 'parent';
            $user_name = $_SESSION['LoginParentName'];
            $defaltWebID = $_SESSION['LoginWebID'];
            $back_home = empty($WebTitle) ? _MD_TCW_HOME : sprintf(_MD_TCW_TO_MY_WEB, $WebTitle);
            $add_power = ['discuss']; //小幫手
            if (!isset($_SESSION['isAssistant'])) {
                $sql = 'SELECT `CateID`, `plugin` FROM `' . $xoopsDB->prefix('tad_web_cate_assistant') . '` WHERE `AssistantType` = ? AND `AssistantID` = ?';
                $result = Utility::query($sql, 'si', ['ParentID', $_SESSION['LoginParentID']]) or Utility::web_error($sql, __FILE__, __LINE__);

                while (list($CateID, $plugin_dir) = $xoopsDB->fetchRow($result)) {
                    $add_power[] = $plugin_dir;
                    $_SESSION['isAssistant'][$plugin_dir] = $CateID;
                    $_SESSION['AssistantType'][$CateID] = 'ParentID';
                    $_SESSION['AssistantID'][$CateID] = $_SESSION['LoginParentID'];
                }
            }
        } else {
            $user_kind = 'xoops';
            $user_name = $xoopsUser->name();
            $add_power = [];
            $MyWebID = TadWebTools::MyWebID('1');

            $showDefWebID = $defaltWebID;
            $uid = $_SESSION['tad_web_adm'] ? get_web_uid($showDefWebID) : $xoopsUser->uid();

            $my_webs_data_file = XOOPS_VAR_PATH . "/tad_web/my_webs_data/$uid.json";

            if ($_SESSION['tad_web_adm']) {
                $MyWebID[$_GET['WebID']] = $_GET['WebID'];
            }

            $AllMyWebID = implode(',', $MyWebID);

            $MyWebID = $_SESSION['tad_web_adm'] ? $_GET['WebID'] : $defaltWebID;

            if ($MyWebID) {
                if (file_exists($my_webs_data_file)) {
                    $webs = get_json_file($my_webs_data_file);
                } else {
                    $sql = 'SELECT * FROM `' . $xoopsDB->prefix('tad_web') . '` WHERE `WebID` IN (?) ORDER BY `WebSort`';
                    $result = Utility::query($sql, 's', [$AllMyWebID]) or Utility::web_error($sql, __FILE__, __LINE__);
                    $i = $defalt_used_size = 0;

                    while (false !== ($all = $xoopsDB->fetchArray($result))) {
                        foreach ($all as $k => $v) {
                            $$k = $v;
                        }

                        $webs[$WebID]['title'] = $WebTitle;
                        $webs[$WebID]['WebID'] = $WebID;
                        $webs[$WebID]['name'] = $WebName;

                        if (_IS_EZCLASS) {
                            $webs[$WebID]['used_size'] = redis_do($WebID, 'get', '', 'used_size');
                        } else {
                            $webs[$WebID]['used_size'] = $used_size;
                        }

                        $webs[$WebID]['url'] = preg_match('/modules\/tad_web/', $_SERVER['PHP_SELF']) ? $_SERVER['PHP_SELF'] . "?WebID={$WebID}" : XOOPS_URL . "/modules/tad_web/index.php?WebID={$WebID}";

                        $i++;
                    }
                    file_put_contents($my_webs_data_file, json_encode($webs, 256));
                }

                if (!empty($showDefWebID)) {
                    $defaltWebID = $showDefWebID;
                }

                $defaltWebID = $_SESSION['tad_web_adm'] ? $_GET['WebID'] : $defaltWebID;

                $defaltWebTitle = $webs[$defaltWebID]['title'];
                $defaltWebName = $webs[$defaltWebID]['name'];
                $defalt_used_size = (int) $webs[$defaltWebID]['used_size'];

                $back_home = empty($defaltWebName) ? _MD_TCW_HOME : sprintf(_MD_TCW_TO_MY_WEB, $defaltWebName);

                if (!defined('_SHOW_UNABLE')) {
                    define('_SHOW_UNABLE', '1');
                }
                $space_quota = TadWebTools::get_web_config('space_quota', $defaltWebID);
                $space_quota = empty($space_quota) ? 500 : $space_quota;
                $quota = (empty($space_quota) or $space_quota == "default") ? $xoopsModuleConfig['user_space_quota'] : $space_quota;

                $size = size2mb($defalt_used_size);

                $percentage = round($size / $quota, 2) * 100;

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
            $xoopsTpl->assign('defalt_used_size', $defalt_used_size);
            $xoopsTpl->assign('percentage', $percentage);
            $xoopsTpl->assign('progress_color', $progress_color);
            $xoopsTpl->assign('webs', $webs);
            $xoopsTpl->assign('adm_defaltWebName', sprintf(_MD_TCW_ADM_DEFALTWEBNAME, $defaltWebName));

            if (isset($_SESSION['isAssistant'])) {
                $xoopsTpl->assign('isAssistant', $_SESSION['isAssistant']);
                $xoopsTpl->assign('AssistantTypeArr', $_SESSION['AssistantType']);
                $xoopsTpl->assign('AssistantIDArr', $_SESSION['AssistantID']);
            }

            //已關閉網站
            $MyClosedWebID = TadWebTools::MyWebID('0');
            $AllMyClosedWebID = implode(',', $MyClosedWebID);
            if ($MyClosedWebID) {
                $sql = 'SELECT * FROM `' . $xoopsDB->prefix('tad_web') . '` WHERE `WebID` IN (?) ORDER BY `WebSort`';
                $result = Utility::query($sql, 's', [$AllMyClosedWebID]) or Utility::web_error($sql, __FILE__, __LINE__);

                $i = 0;

                while (false !== ($all = $xoopsDB->fetchArray($result))) {
                    foreach ($all as $k => $v) {
                        $$k = $v;
                    }

                    $closed_webs[$i]['title'] = $WebTitle;
                    $closed_webs[$i]['WebID'] = $WebID;
                    $closed_webs[$i]['name'] = $WebName;
                    $closed_webs[$i]['url'] = XOOPS_URL . "/modules/tad_web/config.php?WebID={$WebID}&op=enable_my_web";

                    $i++;
                }
            }

            $closed_webs = isset($closed_webs) ? $closed_webs : '';
            $xoopsTpl->assign('closed_webs', $closed_webs);
        }

        if (!empty($defaltWebID)) {
            if (!file_exists(XOOPS_ROOT_PATH . "/uploads/tad_web/{$defaltWebID}/menu_var.php")) {
                mk_menu_var_file($defaltWebID);
            }
            require XOOPS_ROOT_PATH . "/uploads/tad_web/{$defaltWebID}/menu_var.php";
        }
        Utility::test($menu_var, 'menu_var', 'dd');
        Utility::test($add_power, 'add_power', 'dd');
        $xoopsTpl->assign('user_kind', $user_kind);
        $xoopsTpl->assign('say_hi', sprintf(_MD_TCW_HI, $user_name));
        $xoopsTpl->assign('back_home', $back_home);
        $xoopsTpl->assign('defaltWebID', $defaltWebID);
        $xoopsTpl->assign('menu_plugins', $menu_var);
        $xoopsTpl->assign('add_power', $add_power);
        $xoopsTpl->assign('defaltWebName', $defaltWebName);
    }
}

//以流水號秀出某筆tad_web_mems資料內容
function tad_web_login($WebID, $config = [])
{
    global $xoopsUser, $xoopsTpl;

    if ($xoopsUser or !empty($_SESSION['LoginMemID']) or !empty($_SESSION['LoginParentID'])) {
        return;
    }
    $_SESSION['login_from'] = XOOPS_URL . "/modules/tad_web/index.php?WebID=$WebID";

    $login_config = TadWebTools::get_web_config('login_config', $WebID);
    $login_config = empty($login_config) ? [] : explode(';', $login_config);
    $about_setup = get_plugin_setup_values($WebID, 'aboutus');
    $auth_method = get_sys_openid();
    if ($auth_method) {

        xoops_loadLanguage('county', 'tad_login');
        xoops_loadLanguage('blocks', 'tad_login');

        // require_once XOOPS_ROOT_PATH . "/modules/tad_login/language/{$xoopsConfig['language']}/county.php";

        $i = 0;

        $oidc_array = array_keys(TadLoginTools::$all_oidc);
        $oidc_array2 = array_keys(TadLoginTools::$all_oidc2);
        // $login_method_arr = explode(';', $login_method);
        foreach ($auth_method as $method) {
            if (!empty($login_config) and !in_array($method, $login_config)) {
                // die(var_export($login_config));
                continue;
            }

            if ('line' === $method) {
                $tlogin[$i]['link'] = TadLoginTools::line_login('return');
            } elseif ('google' === $method) {
                $tlogin[$i]['link'] = TadLoginTools::google_login('return');
            } else {
                $tlogin[$i]['link'] = XOOPS_URL . "/modules/tad_login/index.php?login&op={$method}";
            }

            if ($oidc_array) {
                $tlogin[$i]['img'] = in_array($method, $oidc_array) ? XOOPS_URL . "/modules/tad_login/images/oidc/" . TadLoginTools::$all_oidc[$method]['tail'] . ".png" : XOOPS_URL . "/modules/tad_login/images/{$method}.png";
            }

            if (is_array($oidc_array) && in_array($method, $oidc_array)) {
                $tlogin[$i]['text'] = constant('_' . mb_strtoupper(TadLoginTools::$all_oidc[$method]['tail'])) . ' OIDC ' . _MB_TADLOGIN_LOGIN;
            } elseif (is_array($oidc_array2) && in_array($method, $oidc_array2)) {
                $tlogin[$i]['text'] = isset(TadLoginTools::$all_oidc[$method]['tail']) ? constant('_' . mb_strtoupper(TadLoginTools::$all_oidc[$method]['tail'])) . _MB_TADLOGIN_LOGIN : '';
            } else {
                $tlogin[$i]['text'] = constant('_' . mb_strtoupper($method)) . ' OpenID ' . _MB_TADLOGIN_LOGIN;
            }
            $i++;
        }

        $openid = '1';
        $xoopsTpl->assign('tlogin', $tlogin);
    } else {
        $openid = '0';
    }
    $xoopsTpl->assign('openid', $openid);

    if ('1' == $about_setup['mem_parents']) {
        $ys = get_seme();
        require_once XOOPS_ROOT_PATH . '/modules/tad_web/class/WebCate.php';
        $WebCate = new WebCate($WebID, 'aboutus', 'tad_web_link_mems');
        // $WebCate->set_default_value(sprintf(_MD_TCW_SEME_CATE, $ys[0]));
        $WebCate->set_default_option_text(sprintf(_MD_TCW_SELECT_SEME, $about_setup['class_title']));
        $WebCate->set_col_md(3, 12);
        $WebCate->set_custom_change_js("$.post('" . XOOPS_URL . "/modules/tad_web/plugins/aboutus/get_mems.php', { op: 'get_parents', WebID: '{$WebID}', CateID: $('#loginCateID').val()}, function(data){
            $('#select_mems').html(data);
            $('#select_mems').show();
        });");
        $WebCate->set_var('menu_id', 'loginCateID');
        $cate_menu = $WebCate->cate_menu('', 'page', false, false, false);
        $xoopsTpl->assign('login_cate_menu', $cate_menu);
        $xoopsTpl->assign('mem_parents', $about_setup['mem_parents']);
    }

    $xoopsTpl->assign('student_title', $about_setup['student_title']);
}

//取得多人網頁的內部區塊(在footer.php執行)
function get_tad_web_blocks($WebID = null)
{
    global $xoopsTpl, $xoopsDB, $Web, $web_all_config;

    $power = new Power($WebID);
    $myts = \MyTextSanitizer::getInstance();
    $block['block1'] = $block['block2'] = $block['block3'] = $block['block4'] = $block['block5'] = $block['block6'] = $block['side'] = [];

    $web_blocks_file = XOOPS_VAR_PATH . "/tad_web/$WebID/web_blocks.json";

    if (!file_exists($web_blocks_file)) {
        $block_tpl = get_all_blocks('tpl');
        $dir = XOOPS_ROOT_PATH . '/modules/tad_web/plugins/';

        // 只列出有啟用的區塊
        $web_plugin_enable_arr = $web_all_config['web_plugin_enable_arr'];

        $andPlugin = $web_plugin_enable_arr ? "AND `plugin` IN ('custom','system','share','" . str_replace(',', "','", $web_plugin_enable_arr) . "')" : '';

        $block_read_power = $power->get_power('read', 'BlockID');

        //取得區塊位置
        $sql = 'SELECT * FROM `' . $xoopsDB->prefix('tad_web_blocks') . '` WHERE `WebID`=? AND `BlockEnable`=? ' . $andPlugin . ' ORDER BY `BlockPosition`, `BlockSort`';
        $result = Utility::query($sql, 'is', [$WebID, '1']) or Utility::web_error($sql, __FILE__, __LINE__);

        while (false !== ($all = $xoopsDB->fetchArray($result))) {
            foreach ($all as $k => $v) {
                $$k = $v;
            }

            //檢查權限（改到樣板去檢查）誰可以看得見區塊
            $all['who_can_read'] = $block_read_power[$BlockID];

            if ('1' != $Web['WebEnable']) {
                $all['BlockPosition'] = $BlockPosition = 'side';
            }
            $blocks_arr = $all;
            $config = json_decode($BlockConfig, true);
            $blocks_arr['config'] = $config;

            if ('xoops' === $plugin) {
                $blocks_arr['tpl'] = '';
            } elseif ('custom' === $plugin or 'share' === $plugin) {
                if ('iframe' === $config['content_type']) {
                    $blocks_arr['BlockContent'] = "<iframe title=\"{$BlockTitle}\" src=\"{$BlockContent}\" style=\"width: 100%; height: 300px; overflow: auto; border:none;\"></iframe>";
                } elseif ('js' === $config['content_type']) {
                    $blocks_arr['BlockContent'] = $BlockContent;
                } else {
                    $blocks_arr['BlockContent'] = $myts->displayTarea($BlockContent, 1, 1, 1, 1, 0);
                }
            } else {
                if (file_exists("{$dir}{$plugin}/blocks.php")) {
                    require_once "{$dir}{$plugin}/blocks.php";
                }

                $blocks_arr['tpl'] = $block_tpl[$BlockName];
                $blocks_arr['BlockContent'] = call_user_func($BlockName, $WebID, $config);
                $blocks_arr['config'] = $config;
            }
            $block[$BlockPosition][$BlockSort] = $blocks_arr;
        }

        file_put_contents($web_blocks_file, json_encode($block, 256));
    } else {
        $block = json_decode(file_get_contents($web_blocks_file), true);
    }

    Utility::test($block, 'block', 'dd');
    $power_cache_file = XOOPS_VAR_PATH . "/tad_web/{$WebID}/web_power.json";
    if (\file_exists($power_cache_file)) {
        $powers = \json_decode(\file_get_contents($power_cache_file), true);
        $xoopsTpl->assign('powers', $powers);
    }

    Utility::test($powers, 'powers', 'dd');

    $xoopsTpl->assign('center_block1', $block['block1']);
    $xoopsTpl->assign('center_block2', $block['block2']);
    $xoopsTpl->assign('center_block3', $block['block3']);
    $xoopsTpl->assign('center_block4', $block['block4']);
    $xoopsTpl->assign('center_block5', $block['block5']);
    $xoopsTpl->assign('center_block6', $block['block6']);
    $xoopsTpl->assign('side_block', $block['side']);
}
