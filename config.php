<?php
use Xmf\Request;
use XoopsModules\Tadtools\EasyResponsiveTabs;
use XoopsModules\Tadtools\FormValidator;
use XoopsModules\Tadtools\MColorPicker;
use XoopsModules\Tadtools\SweetAlert;
use XoopsModules\Tadtools\Tmt;
use XoopsModules\Tadtools\Utility;
use XoopsModules\Tad_login\Tools as TadLoginTools;
use XoopsModules\Tad_web\Tools as TadWebTools;
use XoopsModules\Tad_web\WebCate;
/*-----------引入檔案區--------------*/
require_once __DIR__ . '/header.php';

if ('enable_my_web' === $_REQUEST['op']) {
    $xoopsOption['template_main'] = 'tad_web_config.tpl';
} else {
    if (!empty($WebID) and $isMyWeb) {
        $xoopsOption['template_main'] = 'tad_web_config.tpl';
    } elseif (!$isMyWeb and $MyWebs) {
        $WebID = (int) $MyWebs[0];
        redirect_header($_SERVER['PHP_SELF'] . "?WebID={$WebID}", 3, _MD_TCW_AUTO_TO_HOME);
    } else {
        redirect_header("index.php?WebID={$WebID}", 3, _MD_TCW_NOT_OWNER . '<br>' . __FILE__ . ' : ' . __LINE__);
    }
}
require_once XOOPS_ROOT_PATH . '/header.php';

/*-----------執行動作判斷區----------*/
$op = Request::getString('op');
$WebID = Request::getInt('WebID');
$MemID = Request::getInt('MemID');
$color_setup = Request::getArray('color_setup');
$filename = Request::getString('filename');
$ConfigValue = Request::getArray('ConfigValue');
$head_top = Request::getString('head_top');
$head_left = Request::getString('head_left');
$logo_top = Request::getString('logo_top');
$logo_left = Request::getString('logo_left');
$col_name = Request::getString('col_name');
$col_val = Request::getString('col_val');
$display_blocks = Request::getString('display_blocks');
$other_web_url = Request::getString('other_web_url');
$web_admins = Request::getString('web_admins');
$menu_font_size = Request::getString('menu_font_size', '100%');
$theme_side = Request::getString('theme_side', 'right');
$dirname = Request::getString('dirname');
$delWebID = Request::getInt('delWebID');
$defalut_theme = Request::getString('defalut_theme');
$bg_repeat = Request::getString('bg_repeat');
$bg_attachment = Request::getString('bg_attachment');
$bg_postiton = Request::getString('bg_postiton');
$bg_size = Request::getString('bg_size');
$use_simple_menu = Request::getInt('use_simple_menu');
$login_method = Request::getArray('login_method');

switch ($op) {
    //儲存設定值
    case 'save_config':
        //更新班級資料
        update_tad_web();
        //更新照片
        $TadUpFiles->set_col('WebOwner', $WebID, 1);
        $TadUpFiles->del_files();
        $TadUpFiles->upload_file('upfile', 480, 120, null, null, true);
        //儲存布景
        save_web_config('other_web_url', $other_web_url, $WebID);
        save_web_config('menu_font_size', $menu_font_size, $WebID);
        save_web_config('theme_side', $theme_side, $WebID);
        save_web_config('defalut_theme', $defalut_theme, $WebID);
        save_web_config('use_simple_menu', $use_simple_menu, $WebID);
        //儲存OpenID
        save_web_config('login_config', implode(';', $login_method), $WebID);
        clear_block_cache($WebID);
        header("location: config.php?WebID={$WebID}");
        exit;

    //儲存設定值
    case 'save_all_color':
        foreach ($color_setup as $col_name => $col_val) {
            save_web_config($col_name, $col_val, $WebID);
        }
        clear_block_cache($WebID);
        header("location: {$_SERVER['PHP_SELF']}?WebID={$WebID}");
        exit;

    case 'save_plugins':
        save_plugins($WebID);
        clear_block_cache($WebID);
        header("location: {$_SERVER['PHP_SELF']}?WebID={$WebID}");
        exit;

    case 'upload_head':
        $TadUpFilesHead = TadUpFilesHead($WebID);
        $TadUpFilesHead->set_col('head', $WebID);
        $file_name = $TadUpFilesHead->upload_file('head', null, null, null, '', true);
        if ($file_name) {
            save_web_config('web_head', $file_name, $WebID);
            output_head_file($WebID);
            output_head_file_480($WebID);
        }
        header("location: {$_SERVER['PHP_SELF']}?WebID={$WebID}");
        exit;

    case 'upload_logo':
        $TadUpFilesLogo = TadUpFilesLogo($WebID);
        $TadUpFilesLogo->set_col('logo', $WebID);
        $file_name = $TadUpFilesLogo->upload_file('logo', null, null, null, '', true);
        if ($file_name) {
            save_web_config('web_logo', $file_name, $WebID);
            output_head_file($WebID);
            output_head_file_480($WebID);
        }
        header("location: {$_SERVER['PHP_SELF']}?WebID={$WebID}");
        exit;

    case 'upload_bg':
        $TadUpFilesBg = TadUpFilesBg($WebID);
        $TadUpFilesBg->set_col('bg', $WebID);
        $file_name = $TadUpFilesBg->upload_file('bg', null, null, null, '', true);
        if ($file_name) {
            save_web_config('web_bg', $file_name, $WebID);
        }
        save_web_config('bg_repeat', $bg_repeat, $WebID);
        save_web_config('bg_attachment', $bg_attachment, $WebID);
        save_web_config('bg_postiton', $bg_postiton, $WebID);
        save_web_config('bg_size', $bg_size, $WebID);
        header("location: {$_SERVER['PHP_SELF']}?WebID={$WebID}");
        exit;

    case 'save_adm':
        save_adm($web_admins, $WebID);
        clear_block_cache($WebID);
        header("location: {$_SERVER['PHP_SELF']}?WebID={$WebID}");
        exit;

    case 'reset_logo':
        reset_logo($WebID);
        header("location: {$_SERVER['PHP_SELF']}?WebID={$WebID}");
        exit;

    case 'reset_head':
        reset_head($WebID);
        header("location: {$_SERVER['PHP_SELF']}?WebID={$WebID}");
        exit;

    case 'enabe_plugin':
        enabe_plugin($dirname, $WebID);
        clear_block_cache($WebID);
        header("location: {$dirname}.php?WebID={$WebID}&op=edit_form");
        exit;

    case 'unable_my_web':
        unable_my_web($WebID);
        header("location: config.php?WebID=$WebID");
        exit;

    case 'enable_my_web':
        enable_my_web($WebID);
        header("location: config.php?WebID={$WebID}");
        exit;

    //刪除資料
    case 'delete_tad_web_chk':
        if (empty($delWebID) or !$isMyWeb) {
            redirect_header("index.php?WebID={$WebID}", 3, _MD_TCW_NOT_OWNER . '<br>' . __FILE__ . ' : ' . __LINE__);
        }
        common_template($WebID, $web_all_config);
        delete_tad_web_chk($delWebID);
        break;
    //刪除資料
    case 'delete_tad_web':
        if (empty($WebID) or !$isMyWeb) {
            redirect_header("index.php?WebID={$WebID}", 3, _MD_TCW_NOT_OWNER . '<br>' . __FILE__ . ' : ' . __LINE__);
        }
        delete_tad_web($WebID);
        header('location: index.php');
        exit;

    //恢復顏色預設值
    case 'default_color':
        default_color($WebID);
        header("location: config.php?WebID={$WebID}");
        exit;

    //預設動作
    default:
        if (empty($WebID)) {
            header('location: index.php');
            exit;
        }
        common_template($WebID, $web_all_config);
        tad_web_config($WebID, $web_all_config);

        break;
}

/*-----------秀出結果區--------------*/
require_once __DIR__ . '/footer.php';
require_once XOOPS_ROOT_PATH . '/footer.php';

/*-----------function區--------------*/

//網站設定
function tad_web_config($WebID, $configs)
{
    global $xoopsDB, $xoopsTpl, $MyWebs, $op, $TadUpFiles, $isMyWeb;

    Utility::get_jquery(true);
    $xoopsTpl->assign('config', true);
    if (empty($configs)) {
        $configs = get_web_all_config($WebID);
    }

    foreach ($configs as $ConfigName => $ConfigValue) {
        if ('login_config' === $ConfigName) {
            $ConfigValue = explode(';', $ConfigValue);
        }
        $xoopsTpl->assign($ConfigName, $ConfigValue);
    }

    $Web = get_tad_web($WebID, true);

    //網站設定
    $WebCate = new WebCate(0, 'web_cate', 'tad_web');
    $WebCate->set_col_md(4, 8);
    //cate_menu($defCateID = "", $mode = "form", $newCate = true, $change_page = false, $show_label = true, $show_tools = false, $show_select = true, $required = false, $default_opt = true)
    $cate_menu = $WebCate->cate_menu($Web['CateID'], 'page', false, false, true, false, true, true, false);

    $xoopsTpl->assign('cate_menu', $cate_menu);

    // die(var_export($Web));
    $WebOwnerUid = (int) $Web['WebOwnerUid'];
    $xoopsTpl->assign('Web', $Web);
    $xoopsTpl->assign('WebName', $Web['WebName']);
    $xoopsTpl->assign('WebOwner', $Web['WebOwner']);

    $TadUpFiles->set_col('WebOwner', $WebID, 1);
    $teacher_pic = $TadUpFiles->get_pic_file();
    $xoopsTpl->assign('teacher_thumb_pic', $teacher_pic);

    $upform = $TadUpFiles->upform(true, 'upfile', '1', false);
    $xoopsTpl->assign('upform_teacher', $upform);

    $SweetAlert = new SweetAlert();
    $SweetAlert->render('delete_my_web', "config.php?WebID=$WebID&op=delete_tad_web_chk&delWebID=", 'WebID');

    $FormValidator = new FormValidator('.myForm', true);
    $FormValidator->render();

    //登入設定
    $login_method = $login_defval = [];
    $TadLoginModuleConfig = Utility::getXoopsModuleConfig('tad_login');
    if ($TadLoginModuleConfig) {
        xoops_loadLanguage('county', 'tad_login');
        xoops_loadLanguage('blocks', 'tad_login');

        $oidc_array = array_keys(TadLoginTools::$all_oidc);
        $oidc_array2 = array_keys(TadLoginTools::$all_oidc2);

        $auth_method = $TadLoginModuleConfig['auth_method'];
        foreach ($auth_method as $method) {
            if (in_array($method, $oidc_array)) {
                $loginTitle = constant('_' . mb_strtoupper(TadLoginTools::$all_oidc[$method]['tail'])) . ' OIDC ' . _MB_TADLOGIN_LOGIN;
            } elseif (in_array($method, $oidc_array2)) {
                $loginTitle = constant('_' . mb_strtoupper(TadLoginTools::$all_oidc2[$method]['tail'])) . ' ' . _TADLOGIN_LDAP;
            } else {
                $loginTitle = constant('_' . mb_strtoupper($method)) . ' OpenID ' . _MB_TADLOGIN_LOGIN;

            }

            $login_defval[] = $method;
            $login_method[$loginTitle] = $method;
        }

    }

    $xoopsTpl->assign('login_method_count', count($login_defval));
    $xoopsTpl->assign('login_defval', $login_defval);
    $xoopsTpl->assign('login_method', $login_method);

    //功能設定
    $plugins = get_plugins($WebID, 'edit');
    $xoopsTpl->assign('plugins', $plugins);

    //背景圖設定
    $bg_path = XOOPS_ROOT_PATH . '/modules/tad_web/images/bg';
    $bg_user_path = XOOPS_ROOT_PATH . "/uploads/tad_web/{$WebID}/bg";
    Utility::mk_dir($bg_user_path);
    Utility::mk_dir("{$bg_user_path}/thumbs");
    // import_img($bg_path, 'bg', $WebID);
    $TadUpFilesBg = TadUpFilesBg($WebID);
    fixed_img($TadUpFilesBg, $bg_user_path, 'bg', $WebID);
    $xoopsTpl->assign('upform_bg', $TadUpFilesBg->upform(false, 'bg', null, false));
    $TadUpFilesBg->set_col('bg', $WebID);
    $xoopsTpl->assign('all_bg', $TadUpFilesBg->get_file_for_smarty(null, null, null, true));
    $xoopsTpl->assign('all_default_bg', get_default_img($bg_path));

    //標題設定
    $head_path = XOOPS_ROOT_PATH . '/modules/tad_web/images/head';
    $head_user_path = XOOPS_ROOT_PATH . "/uploads/tad_web/{$WebID}/head";
    Utility::mk_dir($head_user_path);
    Utility::mk_dir("{$head_user_path}/thumbs");
    // import_img($head_path, 'head', $WebID);
    $TadUpFilesHead = TadUpFilesHead($WebID);
    fixed_img($TadUpFilesHead, $head_user_path, 'head', $WebID);
    $xoopsTpl->assign('upform_head', $TadUpFilesHead->upform(false, 'head', null, false));
    $TadUpFilesHead->set_col('head', $WebID);
    $xoopsTpl->assign('all_head', $TadUpFilesHead->get_file_for_smarty(null, null, null, true));
    $xoopsTpl->assign('all_default_head', get_default_img($head_path));

    //logo設定
    $logo_user_path = XOOPS_ROOT_PATH . "/uploads/tad_web/{$WebID}/logo";
    Utility::mk_dir($logo_user_path);
    Utility::mk_dir("{$logo_user_path}/thumbs");
    $TadUpFilesLogo = TadUpFilesLogo($WebID);
    $xoopsTpl->assign('upform_logo', $TadUpFilesLogo->upform(false, 'logo', null, false));
    $TadUpFilesLogo->set_col('logo', $WebID);
    $xoopsTpl->assign('all_logo', $TadUpFilesLogo->get_file_for_smarty(null, null, null, true));

    $MColorPicker = new MColorPicker('.color-picker');
    $MColorPicker->render('bootstrap');

    //管理員設定
    $web_admin_arr = get_web_roles($WebID, 'admin');
    $web_admins = !empty($web_admin_arr) ? implode(',', $web_admin_arr) : '';
    $sql = 'SELECT `uid`, `uname`, `name` FROM `' . $xoopsDB->prefix('users') . '` ORDER BY `uname`';
    $result = Utility::query($sql) or Utility::web_error($sql, __FILE__, __LINE__);

    $myts = \MyTextSanitizer::getInstance();
    $user_ok = $user_yet = [];
    while (false !== ($all = $xoopsDB->fetchArray($result))) {
        foreach ($all as $k => $v) {
            $$k = $v;
        }
        $name = $myts->htmlSpecialChars($name);
        $uname = $myts->htmlSpecialChars($uname);
        $name = empty($name) ? '' : " ({$name})";
        if (!empty($web_admin_arr) and in_array($uid, $web_admin_arr)) {
            $user_ok[$uid] = "{$uid} {$name} {$uname}";
        } else {
            $user_yet[$uid] = "{$uid} {$name} {$uname}";
        }
    }

    $new_user_ok = [];
    foreach ($web_admin_arr as $uid) {
        $new_user_ok[$uid] = $user_ok[$uid];
    }
    $hidden_arr['op'] = 'save_adm';
    $hidden_arr['WebID'] = $WebID;
    $tmt_box = Tmt::render('web_admins', $user_yet, $new_user_ok, $hidden_arr, false, true, '15rem', 'adm_repository', 'adm_destination', ',', 'config_ajax.php', ['WebID' => $WebID], '', '<h3>' . _MD_TCW_USER_LIST . '</h3>', '<h3>' . _MD_TCW_USER_SELECTED . '</h3>');
    $xoopsTpl->assign('tmt_box', $tmt_box);

    $xoopsTpl->assign('logo_desc', sprintf(_MD_TCW_GOOD_LOGO_SITE, $WebID));
    $xoopsTpl->assign('bg_desc', sprintf(_MD_TCW_GOOD_BG_SITE, $WebID));

    $EasyResponsiveTabs = new EasyResponsiveTabs('#ConfigTab');
    $EasyResponsiveTabs->render();
}

//更新網頁資訊
function update_tad_web()
{
    global $xoopsDB, $WebID;

    $WebName = (string) $_POST['WebName'];
    $WebOwner = (string) $_POST['WebOwner'];
    $CateID = (int) $_POST['CateID'];

    $sql = 'UPDATE `' . $xoopsDB->prefix('tad_web') . '` SET `CateID`=?, `WebName`=?, `WebOwner`=? WHERE `WebID`=?';
    Utility::query($sql, 'issi', [$CateID, $WebName, $WebOwner, $WebID]) or Utility::web_error($sql, __FILE__, __LINE__);

    unset($_SESSION['tad_web'][$WebID]);

    $TadUpFilesLogo = TadUpFilesLogo($WebID);
    $TadUpFilesLogo->set_col('logo', $WebID, 1);
    $TadUpFilesLogo->del_files();

    mklogoPic($WebID);
    $TadUpFilesLogo->import_one_file(XOOPS_ROOT_PATH . "/uploads/tad_web/{$WebID}/auto_logo/auto_logo.png", null, 1280, 150, null, 'auto_logo.png', false);

    output_head_file($WebID);
    output_head_file_480($WebID);
}

//儲存外掛
function save_plugins($WebID)
{
    global $xoopsDB;
    $plugins = get_plugins($WebID);

    $sql = 'DELETE FROM `' . $xoopsDB->prefix('tad_web_plugins') . '` WHERE `WebID`=?';
    Utility::query($sql, 'i', [$WebID]) or Utility::web_error($sql, __FILE__, __LINE__);
    foreach ($plugins as $plugin) {
        $dirname = $plugin['dirname'];
        $PluginTitle = (string) $_POST['plugin_name'][$dirname];
        $PluginEnable = ('1' == $_POST['plugin_enable'][$dirname]) ? '1' : '0';

        $sql = 'REPLACE INTO `' . $xoopsDB->prefix('tad_web_plugins') . '` (`PluginDirname`, `PluginTitle`, `PluginSort`, `PluginEnable`, `WebID`) VALUES (?, ?, ?, ?, ?)';
        Utility::query($sql, 'ssisi', [$dirname, $PluginTitle, $plugin['db']['PluginSort'], $PluginEnable, $WebID]) or Utility::web_error($sql, __FILE__, __LINE__);
    }

    mk_menu_var_file($WebID);
}

//儲存使用者
function save_adm($web_admins, $WebID)
{
    global $xoopsDB;
    if (empty($WebID)) {
        return;
    }

    $sql = 'DELETE FROM `' . $xoopsDB->prefix('tad_web_roles') . '` WHERE `role`=? AND `WebID`=?';
    Utility::query($sql, 'si', ['admin', $WebID]) or Utility::web_error($sql, __FILE__, __LINE__);

    if ($web_admins) {
        $web_admin_arr = explode(',', $web_admins);
        foreach ($web_admin_arr as $uid) {
            $sql = 'INSERT INTO `' . $xoopsDB->prefix('tad_web_roles') . '` (`uid`, `role`, `term`, `enable`, `WebID`) VALUES (?, ?, ?, ?, ?)';
            Utility::query($sql, 'isssi', [$uid, 'admin', '0000-00-00', '1', $WebID]) or Utility::web_error($sql, __FILE__, __LINE__);
        }
    }
    clear_my_webs_data($WebID);
}

//重置logo圖位置
function reset_logo($WebID)
{
    global $xoopsDB;
    if (empty($WebID)) {
        return;
    }

    save_web_config('logo_left', '41.7', $WebID);
    save_web_config('logo_top', '53.8', $WebID);
    output_head_file($WebID);
    output_head_file_480($WebID);
}

//重置標題圖位置
function reset_head($WebID)
{
    global $xoopsDB;
    if (empty($WebID)) {
        return;
    }

    save_web_config('head_left', '0', $WebID);
    save_web_config('head_top', '-371', $WebID);
    output_head_file($WebID);
    output_head_file_480($WebID);
}

function enabe_plugin($dirname = '', $WebID = '')
{
    global $xoopsDB;

    $sql = 'UPDATE `' . $xoopsDB->prefix('tad_web_plugins') . '` SET `PluginEnable` = ? WHERE `WebID` = ? AND `PluginDirname` = ?';
    Utility::query($sql, 'sis', ['1', $WebID, $dirname]) or Utility::web_error($sql, __FILE__, __LINE__);

    $sql = 'UPDATE `' . $xoopsDB->prefix('tad_web_blocks') . '` SET `BlockEnable` = ? WHERE `WebID` = ? AND `plugin` = ?';
    Utility::query($sql, 'sis', ['1', $WebID, $dirname]) or Utility::web_error($sql, __FILE__, __LINE__);

    mk_menu_var_file($WebID);
}

//關閉網站
function unable_my_web($WebID)
{
    global $xoopsDB, $isMyWeb;
    if (empty($WebID) or !$isMyWeb) {
        redirect_header("index.php?WebID={$WebID}", 3, _MD_TCW_NOT_OWNER . '<br>' . __FILE__ . ' : ' . __LINE__);
    }

    $sql = 'UPDATE `' . $xoopsDB->prefix('tad_web') . '` SET `WebEnable` = ? WHERE `WebID` = ?';
    Utility::query($sql, 'si', ['0', $WebID]) or Utility::web_error($sql, __FILE__, __LINE__);
    $_SESSION['tad_web'][$WebID]['WebEnable'] = 0;
}

//啟動網站
function enable_my_web($WebID)
{
    global $xoopsDB;
    $MyWebs = TadWebTools::MyWebID('0');
    $isMyWeb = ($_SESSION['tad_web_adm']) ? true : in_array($WebID, $MyWebs);
    if (empty($WebID) or !$isMyWeb) {
        redirect_header("index.php?WebID={$WebID}", 3, _MD_TCW_NOT_OWNER . '<br>' . __FILE__ . ' : ' . __LINE__);
    }

    $sql = 'UPDATE `' . $xoopsDB->prefix('tad_web') . '` SET `WebEnable` = ? WHERE `WebID` = ?';
    Utility::query($sql, 'si', ['1', $WebID]) or Utility::web_error($sql, __FILE__, __LINE__);

    $_SESSION['tad_web'][$WebID]['WebEnable'] = 1;
}

//恢復顏色預設值
function default_color($WebID = '')
{
    global $xoopsDB, $isMyWeb;
    if (empty($WebID) or !$isMyWeb) {
        redirect_header("index.php?WebID={$WebID}", 3, _MD_TCW_NOT_OWNER . '<br>' . __FILE__ . ' : ' . __LINE__);
    }
    $del_item = [
        'bg_color',
        'container_bg_color',
        'side_bg_color',
        'center_text_color',
        'center_link_color',
        'center_hover_color',
        'center_header_color',
        'center_border_color',
        'side_text_color',
        'side_link_color',
        'side_hover_color',
        'side_header_color',
        'side_border_color',
        'navbar_bg_top',
        'navbar_color',
        'navbar_hover',
        'navbar_color_hover',
    ];
    foreach ($del_item as $ConfigName) {
        $sql = 'DELETE FROM `' . $xoopsDB->prefix('tad_web_config') . '` WHERE `WebID` =? AND `ConfigName` =?';
        Utility::query($sql, 'is', [$WebID, $ConfigName]) or Utility::web_error($sql, __FILE__, __LINE__);
    }
    $file = XOOPS_ROOT_PATH . "/uploads/tad_web/{$WebID}/web_config.php";
    unlink($file);
    clear_tad_web_config($WebID);
}
