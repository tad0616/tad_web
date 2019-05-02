<?php
use XoopsModules\Tadtools\FormValidator;
use XoopsModules\Tadtools\MColorPicker;
use XoopsModules\Tadtools\SweetAlert;
use XoopsModules\Tadtools\Utility;
/*-----------引入檔案區--------------*/
include_once 'header.php';

if ('enable_my_web' === $_REQUEST['op']) {
    $xoopsOption['template_main'] = 'tad_web_config.tpl';
} else {
    if (!empty($_REQUEST['WebID']) and $isMyWeb) {
        $xoopsOption['template_main'] = 'tad_web_config.tpl';
    } elseif (!$isMyWeb and $MyWebs) {
        redirect_header($_SERVER['PHP_SELF'] . "?WebID={$MyWebs[0]}", 3, _MD_TCW_AUTO_TO_HOME);
    } else {
        redirect_header("index.php?WebID={$_GET['WebID']}", 3, _MD_TCW_NOT_OWNER);
    }
}
include_once XOOPS_ROOT_PATH . '/header.php';
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
    $web_cate = new web_cate(0, 'web_cate', 'tad_web');
    $web_cate->set_col_md(3, 9);
    //cate_menu($defCateID = "", $mode = "form", $newCate = true, $change_page = false, $show_label = true, $show_tools = false, $show_select = true, $required = false, $default_opt = true)
    $cate_menu = $web_cate->cate_menu($Web['CateID'], 'page', false, false, true, false, true, true, false);

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
    // $login_method   = '';
    $modhandler = xoops_getHandler('module');
    $config_handler = xoops_getHandler('config');

    $TadLoginXoopsModule = $modhandler->getByDirname('tad_login');
    $login_method = $login_defval = [];
    if ($TadLoginXoopsModule) {
        global $xoopsConfig;
        xoops_loadLanguage('county', 'tad_login');
        xoops_loadLanguage('blocks', 'tad_login');
        require XOOPS_ROOT_PATH . '/modules/tad_login/oidc.php';

        $config_handler = xoops_getHandler('config');
        $modConfig = $config_handler->getConfigsByCat(0, $TadLoginXoopsModule->getVar('mid'));

        $auth_method = $modConfig['auth_method'];
        foreach ($auth_method as $method) {
            $method_const = '_' . mb_strtoupper($method);
            if (in_array($method, $oidc_array)) {
                $loginTitle = constant('_' . mb_strtoupper($all_oidc[$method]['tail'])) . ' OIDC ' . _MB_TADLOGIN_LOGIN;
            } elseif (in_array($method, $oidc_array2)) {
                $loginTitle = constant('_' . mb_strtoupper($all_oidc2[$method]['tail'])) . ' ' . _TADLOGIN_LDAP;
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
    // die(var_export($plugins));
    $xoopsTpl->assign('plugins', $plugins);

    //背景圖設定
    $bg_path = XOOPS_ROOT_PATH . '/modules/tad_web/images/bg';
    $bg_user_path = XOOPS_ROOT_PATH . "/uploads/tad_web/{$WebID}/bg";
    Utility::mk_dir($bg_user_path);
    Utility::mk_dir("{$bg_user_path}/thumbs");
    import_img($bg_path, 'bg', $WebID);
    $TadUpFilesBg = TadUpFilesBg($WebID);
    $xoopsTpl->assign('upform_bg', $TadUpFilesBg->upform(false, 'bg', null, false));
    $TadUpFilesBg->set_col('bg', $WebID);
    $xoopsTpl->assign('all_bg', $TadUpFilesBg->get_file_for_smarty());

    //標題設定
    $head_path = XOOPS_ROOT_PATH . '/modules/tad_web/images/head';
    $head_user_path = XOOPS_ROOT_PATH . "/uploads/tad_web/{$WebID}/head";
    Utility::mk_dir($head_user_path);
    Utility::mk_dir("{$head_user_path}/thumbs");
    import_img($head_path, 'head', $WebID);
    $TadUpFilesHead = TadUpFilesHead($WebID);
    $xoopsTpl->assign('upform_head', $TadUpFilesHead->upform(false, 'head', null, false));
    $TadUpFilesHead->set_col('head', $WebID);
    $xoopsTpl->assign('all_head', $TadUpFilesHead->get_file_for_smarty());

    //logo設定
    $logo_path = XOOPS_ROOT_PATH . '/modules/tad_web/images/logo';
    $logo_user_path = XOOPS_ROOT_PATH . "/uploads/tad_web/{$WebID}/logo";
    Utility::mk_dir($logo_user_path);
    Utility::mk_dir("{$logo_user_path}/thumbs");
    import_img($logo_path, 'logo', $WebID);
    $TadUpFilesLogo = TadUpFilesLogo($WebID);
    $xoopsTpl->assign('upform_logo', $TadUpFilesLogo->upform(false, 'logo', null, false));
    $TadUpFilesLogo->set_col('logo', $WebID);
    $xoopsTpl->assign('all_logo', $TadUpFilesLogo->get_file_for_smarty());

    $MColorPicker = new MColorPicker('.color');
    $MColorPicker->render();

    //管理員設定
    $web_admin_arr = get_web_roles($WebID, 'admin');
    $web_admins = !empty($web_admin_arr) ? implode(',', $web_admin_arr) : '';
    $sql = 'SELECT uid,uname,name FROM ' . $xoopsDB->prefix('users') . ' ORDER BY uname';
    $result = $xoopsDB->query($sql) or Utility::web_error($sql, __FILE__, __LINE__);
    // if($_GET['test']==1){
    // die($web_admins);
    // }
    $myts = \MyTextSanitizer::getInstance();
    $user_ok = $user_yet = '';
    while ($all = $xoopsDB->fetchArray($result)) {
        foreach ($all as $k => $v) {
            $$k = $v;
        }
        $name = $myts->htmlSpecialChars($name);
        $uname = $myts->htmlSpecialChars($uname);
        $name = empty($name) ? '' : " ({$name})";
        if (!empty($web_admin_arr) and in_array($uid, $web_admin_arr)) {
            $user_ok .= "<option value=\"$uid\">{$uid} {$name} {$uname} </option>";
        } else {
            $user_yet .= "<option value=\"$uid\">{$uid} {$name} {$uname} </option>";
        }
    }
    // if ($_GET['test'] == 1) {
    //     die($user_ok);
    // }
    $xoopsTpl->assign('user_ok', $user_ok);
    $xoopsTpl->assign('user_yet', $user_yet);
    $xoopsTpl->assign('web_admins', $web_admins);
    $xoopsTpl->assign('logo_desc', sprintf(_MD_TCW_GOOD_LOGO_SITE, $WebID));
    $xoopsTpl->assign('bg_desc', sprintf(_MD_TCW_GOOD_BG_SITE, $WebID));
}

//更新網頁資訊
function update_tad_web()
{
    global $xoopsDB, $xoopsUser, $WebID;

    $myts = \MyTextSanitizer::getInstance();
    $WebName = $myts->addSlashes($_POST['WebName']);
    $WebOwner = $myts->addSlashes($_POST['WebOwner']);
    $CateID = (int) $_POST['CateID'];

    $sql = 'update ' . $xoopsDB->prefix('tad_web') . " set CateID='{$CateID}', `WebName` = '{$WebName}', `WebOwner` = '{$WebOwner}' where WebID ='{$WebID}'";
    $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);

    unset($_SESSION['tad_web'][$WebID]);

    $TadUpFilesLogo = TadUpFilesLogo($WebID);
    $TadUpFilesLogo->set_col('logo', $WebID, 1);
    $TadUpFilesLogo->del_files();

    mklogoPic($WebID);
    $TadUpFilesLogo->import_one_file(XOOPS_ROOT_PATH . "/uploads/tad_web/{$WebID}/auto_logo/auto_logo.png", null, 1280, 150, null, 'auto_logo.png', false);
    //import_img(XOOPS_ROOT_PATH . "/uploads/tad_web/{$WebID}/auto_logo", "logo", $WebID);
    output_head_file($WebID);
    output_head_file_480($WebID);
}

//移除網站設定
function delete_web_config($ConfigName = '')
{
    global $xoopsDB, $xoopsUser, $WebID, $MyWebs;

    $sql = 'delete from ' . $xoopsDB->prefix('tad_web_config') . " where `ConfigName`='{$ConfigName}' and `WebID`='{$MyWebs}'";
    $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);
    $file = XOOPS_ROOT_PATH . "/uploads/tad_web/{$WebID}/web_config.php";
    unlink($file);
}

//儲存外掛
function save_plugins($WebID)
{
    global $xoopsDB;
    $plugins = get_plugins($WebID);
    $myts = \MyTextSanitizer::getInstance();

    $sql = 'delete from ' . $xoopsDB->prefix('tad_web_plugins') . " where WebID='{$WebID}'";
    $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);
    foreach ($plugins as $plugin) {
        $dirname = $plugin['dirname'];
        $PluginTitle = $myts->addSlashes($_POST['plugin_name'][$dirname]);
        $PluginEnable = ('1' == $_POST['plugin_enable'][$dirname]) ? '1' : '0';

        $sql = 'replace into ' . $xoopsDB->prefix('tad_web_plugins') . " (`PluginDirname`, `PluginTitle`, `PluginSort`, `PluginEnable`, `WebID`) values('{$dirname}', '{$PluginTitle}', '{$plugin['db']['PluginSort']}', '{$PluginEnable}', '{$WebID}')";
        $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);

        // $sql = "update " . $xoopsDB->prefix("tad_web_blocks") . " set BlockEnable='$PluginEnable' where `WebID`='{$WebID}' and `plugin`='{$dirname}'";
        // $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);
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

    $sql = 'delete from ' . $xoopsDB->prefix('tad_web_roles') . " where `role`='admin' and `WebID`='$WebID' ";
    $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);

    if ($web_admins) {
        $web_admin_arr = explode(',', $web_admins);
        foreach ($web_admin_arr as $uid) {
            $sql = 'insert into ' . $xoopsDB->prefix('tad_web_roles') . " (`uid`, `role`, `term`, `enable`, `WebID`) values('{$uid}', 'admin', '0000-00-00', '1', '{$WebID}')";
            $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);
        }
    }
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

    $myts = \MyTextSanitizer::getInstance();
    $dirname = $myts->addSlashes($dirname);

    $sql = 'update ' . $xoopsDB->prefix('tad_web_plugins') . " set
   `PluginEnable` = '1'
    where WebID ='{$WebID}' and `PluginDirname`='{$dirname}'";
    $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);

    $sql = 'update ' . $xoopsDB->prefix('tad_web_blocks') . " set
   `BlockEnable` = '1'
    where WebID ='{$WebID}' and `plugin`='{$dirname}'";
    $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);

    mk_menu_var_file($WebID);
}

//關閉網站
function unable_my_web($WebID)
{
    global $xoopsDB, $isMyWeb;
    if (empty($WebID) or !$isMyWeb) {
        redirect_header("index.php?WebID={$WebID}", 3, _MD_TCW_NOT_OWNER);
    }

    $sql = 'update ' . $xoopsDB->prefix('tad_web') . " set `WebEnable` = '0' where WebID ='{$WebID}'";
    $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);
    $_SESSION['tad_web'][$WebID]['WebEnable'] = 0;
}

//啟動網站
function enable_my_web($WebID)
{
    global $xoopsDB, $isAdmin;
    $MyWebs = MyWebID('0');
    $isMyWeb = ($isAdmin) ? true : in_array($WebID, $MyWebs);
    if (empty($WebID) or !$isMyWeb) {
        redirect_header("index.php?WebID={$WebID}", 3, _MD_TCW_NOT_OWNER);
    }

    $sql = 'update ' . $xoopsDB->prefix('tad_web') . " set `WebEnable` = '1' where WebID ='{$WebID}'";
    $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);
    $_SESSION['tad_web'][$WebID]['WebEnable'] = 1;
}

//恢復顏色預設值
function default_color($WebID = '')
{
    global $xoopsDB, $isMyWeb;
    if (empty($WebID) or !$isMyWeb) {
        redirect_header("index.php?WebID={$WebID}", 3, _MD_TCW_NOT_OWNER);
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
        $sql = 'delete from ' . $xoopsDB->prefix('tad_web_config') . " where WebID ='{$WebID}' and ConfigName='{$ConfigName}'";
        $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);
    }
    $file = XOOPS_ROOT_PATH . "/uploads/tad_web/{$WebID}/web_config.php";
    unlink($file);
}

/*-----------執行動作判斷區----------*/
include_once $GLOBALS['xoops']->path('/modules/system/include/functions.php');
$op = system_CleanVars($_REQUEST, 'op', '', 'string');
$WebID = system_CleanVars($_REQUEST, 'WebID', 0, 'int');
$MemID = system_CleanVars($_REQUEST, 'MemID', 0, 'int');
$color_setup = system_CleanVars($_REQUEST, 'color_setup', '', 'array');
$filename = system_CleanVars($_REQUEST, 'filename', '', 'string');
$ConfigValue = system_CleanVars($_REQUEST, 'ConfigValue', '', 'array');
$head_top = system_CleanVars($_REQUEST, 'head_top', '', 'string');
$head_left = system_CleanVars($_REQUEST, 'head_left', '', 'string');
$logo_top = system_CleanVars($_REQUEST, 'logo_top', '', 'string');
$logo_left = system_CleanVars($_REQUEST, 'logo_left', '', 'string');
$col_name = system_CleanVars($_REQUEST, 'col_name', '', 'string');
$col_val = system_CleanVars($_REQUEST, 'col_val', '', 'string');
$display_blocks = system_CleanVars($_REQUEST, 'display_blocks', '', 'string');
$other_web_url = system_CleanVars($_REQUEST, 'other_web_url', '', 'string');
$web_admins = system_CleanVars($_REQUEST, 'web_admins', '', 'string');
$menu_font_size = system_CleanVars($_REQUEST, 'menu_font_size', 12, 'int');
$theme_side = system_CleanVars($_REQUEST, 'theme_side', 'right', 'string');
$dirname = system_CleanVars($_REQUEST, 'dirname', '', 'string');
$delWebID = system_CleanVars($_REQUEST, 'delWebID', 0, 'int');
$defalut_theme = system_CleanVars($_REQUEST, 'defalut_theme', '', 'string');
$bg_repeat = system_CleanVars($_REQUEST, 'bg_repeat', '', 'string');
$bg_attachment = system_CleanVars($_REQUEST, 'bg_attachment', '', 'string');
$bg_postiton = system_CleanVars($_REQUEST, 'bg_postiton', '', 'string');
$bg_size = system_CleanVars($_REQUEST, 'bg_size', '', 'string');
$use_simple_menu = system_CleanVars($_REQUEST, 'use_simple_menu', 0, 'int');
$login_method = system_CleanVars($_REQUEST, 'login_method', '', 'array');

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
        header("location: config.php?WebID={$WebID}");
        exit;

    //儲存設定值
    case 'save_all_color':
        foreach ($color_setup as $col_name => $col_val) {
            save_web_config($col_name, $col_val, $WebID);
        }
        header("location: {$_SERVER['PHP_SELF']}?WebID={$WebID}");
        exit;

    case 'save_plugins':
        save_plugins($WebID);
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
            redirect_header("index.php?WebID={$WebID}", 3, _MD_TCW_NOT_OWNER);
        }
        common_template($WebID, $web_all_config);
        delete_tad_web_chk($delWebID);
        break;
    //刪除資料
    case 'delete_tad_web':
        if (empty($WebID) or !$isMyWeb) {
            redirect_header("index.php?WebID={$WebID}", 3, _MD_TCW_NOT_OWNER);
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
include_once 'footer.php';
include_once XOOPS_ROOT_PATH . '/footer.php';
