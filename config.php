<?php
/*-----------引入檔案區--------------*/
include_once "header.php";

if (!empty($_REQUEST['WebID']) and $isMyWeb) {
    $xoopsOption['template_main'] = 'tad_web_config_b3.html';
} elseif (!$isMyWeb and $MyWebs) {
    redirect_header($_SERVER['PHP_SELF'] . "?WebID={$MyWebs[0]}", 3, _MD_TCW_AUTO_TO_HOME);
} else {
    redirect_header("index.php?WebID={$_GET['WebID']}", 3, _MD_TCW_NOT_OWNER);
}
include_once XOOPS_ROOT_PATH . "/header.php";
/*-----------function區--------------*/

//網站設定
function tad_web_config($WebID)
{
    global $xoopsDB, $xoopsTpl, $MyWebs, $op, $TadUpFiles, $isMyWeb;

    get_jquery(true);
    $xoopsTpl->assign('config', true);
    $configs = get_web_all_config($WebID);

    foreach ($configs as $ConfigName => $ConfigValue) {
        $xoopsTpl->assign($ConfigName, $ConfigValue);
    }

    $Web = get_tad_web($WebID, true);

    //網站設定
    $web_cate = new web_cate(0, "web_cate", "tad_web");
    $web_cate->set_col_md(3, 7);
    //cate_menu($defCateID = "", $mode = "form", $newCate = true, $change_page = false, $show_label = true, $show_tools = false, $show_select = true, $required = false, $default_opt = true)
    $cate_menu = $web_cate->cate_menu($Web['CateID'], 'page', false, false, true, false, true, true, false);

    $xoopsTpl->assign('cate_menu', $cate_menu);

    // die(var_export($Web));
    $WebOwnerUid = intval($Web['WebOwnerUid']);
    $xoopsTpl->assign('Web', $Web);
    $xoopsTpl->assign('WebName', $Web['WebName']);

    $TadUpFiles->set_col("WebOwner", $WebID, 1);
    $teacher_pic = $TadUpFiles->get_pic_file();
    $xoopsTpl->assign('teacher_thumb_pic', $teacher_pic);

    $upform = $TadUpFiles->upform(true, 'upfile', '1', false);
    $xoopsTpl->assign('upform_teacher', $upform);

    //可愛刪除
    if (!file_exists(XOOPS_ROOT_PATH . "/modules/tadtools/sweet_alert.php")) {
        redirect_header("index.php", 3, _MA_NEED_TADTOOLS);
    }
    include_once XOOPS_ROOT_PATH . "/modules/tadtools/sweet_alert.php";
    $sweet_alert      = new sweet_alert();
    $sweet_alert_code = $sweet_alert->render("delete_my_web", "config.php?WebID=$WebID&op=delete_tad_web_chk&delWebID=", 'WebID');
    $xoopsTpl->assign('sweet_delete_action_func_code', $sweet_alert_code);

    //功能設定
    $plugins = get_plugins($WebID, 'edit');
    //die(var_export($plugins));
    $xoopsTpl->assign('plugins', $plugins);

    //背景圖設定
    $bg_path      = XOOPS_ROOT_PATH . "/modules/tad_web/images/bg";
    $bg_user_path = XOOPS_ROOT_PATH . "/uploads/tad_web/{$WebID}/bg";
    mk_dir($bg_user_path);
    mk_dir("{$bg_user_path}/thumbs");
    import_img($bg_path, "bg", $WebID);
    $TadUpFilesBg = TadUpFilesBg($WebID);
    $xoopsTpl->assign('upform_bg', $TadUpFilesBg->upform(false, "bg", null, false));
    $TadUpFilesBg->set_col("bg", $WebID);
    $xoopsTpl->assign('all_bg', $TadUpFilesBg->get_file_for_smarty());

    //標題設定
    $head_path      = XOOPS_ROOT_PATH . "/modules/tad_web/images/head";
    $head_user_path = XOOPS_ROOT_PATH . "/uploads/tad_web/{$WebID}/head";
    mk_dir($head_user_path);
    mk_dir("{$head_user_path}/thumbs");
    import_img($head_path, "head", $WebID);
    $TadUpFilesHead = TadUpFilesHead($WebID);
    $xoopsTpl->assign('upform_head', $TadUpFilesHead->upform(false, "head", null, false));
    $TadUpFilesHead->set_col("head", $WebID);
    $xoopsTpl->assign('all_head', $TadUpFilesHead->get_file_for_smarty());

    //logo設定
    $logo_path      = XOOPS_ROOT_PATH . "/modules/tad_web/images/logo";
    $logo_user_path = XOOPS_ROOT_PATH . "/uploads/tad_web/{$WebID}/logo";
    mk_dir($logo_user_path);
    mk_dir("{$logo_user_path}/thumbs");
    import_img($logo_path, "logo", $WebID);
    $TadUpFilesLogo = TadUpFilesLogo($WebID);
    $xoopsTpl->assign('upform_logo', $TadUpFilesLogo->upform(false, "logo", null, false));
    $TadUpFilesLogo->set_col("logo", $WebID);
    $xoopsTpl->assign('all_logo', $TadUpFilesLogo->get_file_for_smarty());

    //顏色設定
    if (!file_exists(XOOPS_ROOT_PATH . "/modules/tadtools/mColorPicker.php")) {
        redirect_header("index.php", 3, _MA_NEED_TADTOOLS);
    }
    include_once XOOPS_ROOT_PATH . "/modules/tadtools/mColorPicker.php";
    $mColorPicker      = new mColorPicker('.color');
    $mColorPicker_code = $mColorPicker->render();
    $xoopsTpl->assign('mColorPicker_code', $mColorPicker_code);

    //管理員設定
    $web_admin_arr = get_web_roles($WebID, 'admin');
    $web_admins    = !empty($web_admin_arr) ? implode(',', $web_admin_arr) : '';
    $sql           = "select uid,uname,name from " . $xoopsDB->prefix("users") . " order by uname";
    $result        = $xoopsDB->query($sql) or web_error($sql);

    $myts    = MyTextSanitizer::getInstance();
    $user_ok = $user_yet = "";
    while ($all = $xoopsDB->fetchArray($result)) {
        foreach ($all as $k => $v) {
            $$k = $v;
        }
        $name  = $myts->htmlSpecialChars($name);
        $uname = $myts->htmlSpecialChars($uname);
        $name  = empty($name) ? "" : " ({$name})";
        if (!empty($web_admin_arr) and in_array($uid, $web_admin_arr) or $uid == $WebOwnerUid) {
            $user_ok .= "<option value=\"$uid\">{$uid} {$name} {$uname} </option>";
        } else {
            $user_yet .= "<option value=\"$uid\">{$uid} {$name} {$uname} </option>";
        }
    }
    $xoopsTpl->assign('user_ok', $user_ok);
    $xoopsTpl->assign('user_yet', $user_yet);
    $xoopsTpl->assign('web_admins', $web_admins);
    $xoopsTpl->assign('logo_desc', sprintf(_MD_TCW_GOOD_LOGO_SITE, $WebID));

}

//更新網頁資訊
function update_tad_web()
{
    global $xoopsDB, $xoopsUser, $WebID;

    $myts             = &MyTextSanitizer::getInstance();
    $_POST['WebName'] = $myts->addSlashes($_POST['WebName']);
    $CateID           = intval($_POST['CateID']);

    $sql = "update " . $xoopsDB->prefix("tad_web") . " set CateID='{$CateID}', `WebName` = '{$_POST['WebName']}' where WebID ='{$WebID}'";
    $xoopsDB->queryF($sql) or web_error($sql);

    $TadUpFilesLogo = TadUpFilesLogo($WebID);
    $TadUpFilesLogo->set_col('logo', $WebID, 1);
    $TadUpFilesLogo->del_files();

    mklogoPic($WebID);
    $TadUpFilesLogo->import_one_file(XOOPS_ROOT_PATH . "/uploads/tad_web/{$WebID}/auto_logo/auto_logo.png", null, 1280, 150, null, 'auto_logo.png', false);
    //import_img(XOOPS_ROOT_PATH . "/uploads/tad_web/{$WebID}/auto_logo", "logo", $WebID);
    output_head_file($WebID);
}

//移除網站設定
function delete_web_config($ConfigName = "")
{
    global $xoopsDB, $xoopsUser, $WebID, $MyWebs;

    $sql = "delete from " . $xoopsDB->prefix("tad_web_config") . " where `ConfigName`='{$ConfigName}' and `WebID`='{$MyWebs}'";
    $xoopsDB->queryF($sql) or web_error($sql);

}

function save_plugins($WebID)
{
    global $xoopsDB;
    $plugins = get_plugins($WebID);
    $myts    = &MyTextSanitizer::getInstance();

    $sql = "delete from " . $xoopsDB->prefix("tad_web_plugins") . " where WebID='{$WebID}'";
    $xoopsDB->queryF($sql) or web_error($sql);
    foreach ($plugins as $plugin) {
        $dirname      = $plugin['dirname'];
        $PluginTitle  = $myts->addSlashes($_POST['plugin_name'][$dirname]);
        $PluginEnable = ($_POST['plugin_enable'][$dirname] == '1') ? '1' : '0';

        $sql = "replace into " . $xoopsDB->prefix("tad_web_plugins") . " (`PluginDirname`, `PluginTitle`, `PluginSort`, `PluginEnable`, `WebID`) values('{$dirname}', '{$PluginTitle}', '{$plugin['db']['PluginSort']}', '{$PluginEnable}', '{$WebID}')";
        $xoopsDB->queryF($sql) or web_error($sql);

        $sql = "update " . $xoopsDB->prefix("tad_web_blocks") . " set BlockEnable='$PluginEnable' where `WebID`='{$WebID}' and `plugin`='{$dirname}'";
        $xoopsDB->queryF($sql) or web_error($sql);
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

    $sql = "delete from " . $xoopsDB->prefix("tad_web_roles") . " where `role`='admin' and `WebID`='$WebID' ";
    $xoopsDB->queryF($sql) or web_error($sql);

    if ($web_admins) {
        $web_admin_arr = explode(',', $web_admins);
        foreach ($web_admin_arr as $uid) {
            $sql = "insert into " . $xoopsDB->prefix("tad_web_roles") . " (`uid`, `role`, `term`, `enable`, `WebID`) values('{$uid}', 'admin', '0000-00-00', '1', '{$WebID}')";
            $xoopsDB->queryF($sql) or web_error($sql);
        }
    }

}

//儲存使用者
function reset_logo($WebID)
{
    global $xoopsDB;
    if (empty($WebID)) {
        return;
    }

    save_web_config('logo_left', '41.7', $WebID);
    save_web_config('logo_top', '53.8', $WebID);
    output_head_file($WebID);
}

function enabe_plugin($dirname = "", $WebID = "")
{
    global $xoopsDB;

    $myts    = &MyTextSanitizer::getInstance();
    $dirname = $myts->addSlashes($dirname);

    $sql = "update " . $xoopsDB->prefix("tad_web_plugins") . " set
   `PluginEnable` = '1'
    where WebID ='{$WebID}' and `PluginDirname`='{$dirname}'";
    $xoopsDB->queryF($sql) or web_error($sql);

    $sql = "update " . $xoopsDB->prefix("tad_web_blocks") . " set
   `BlockEnable` = '1'
    where WebID ='{$WebID}' and `plugin`='{$dirname}'";
    $xoopsDB->queryF($sql) or web_error($sql);

    mk_menu_var_file($WebID);
}

//刪除網站
// function delete_my_web($WebID)
// {
//     global $xoopsDB, $isMyWeb;
//     if (empty($WebID) or !$isMyWeb) {
//         redirect_header("index.php?WebID={$WebID}", 3, _MD_TCW_NOT_OWNER);
//     }

//     $sql = "update " . $xoopsDB->prefix("tad_web") . " set `WebEnable` = '0' where WebID ='{$WebID}'";
//     $xoopsDB->queryF($sql) or web_error($sql);
// }

//關閉網站
function unable_my_web($WebID)
{
    global $xoopsDB, $isMyWeb;
    if (empty($WebID) or !$isMyWeb) {
        redirect_header("index.php?WebID={$WebID}", 3, _MD_TCW_NOT_OWNER);
    }

    $sql = "update " . $xoopsDB->prefix("tad_web") . " set `WebEnable` = '0' where WebID ='{$WebID}'";
    $xoopsDB->queryF($sql) or web_error($sql);
}

/*-----------執行動作判斷區----------*/
include_once $GLOBALS['xoops']->path('/modules/system/include/functions.php');
$op             = system_CleanVars($_REQUEST, 'op', '', 'string');
$WebID          = system_CleanVars($_REQUEST, 'WebID', 0, 'int');
$MemID          = system_CleanVars($_REQUEST, 'MemID', 0, 'int');
$color_setup    = system_CleanVars($_REQUEST, 'color_setup', '', 'array');
$filename       = system_CleanVars($_REQUEST, 'filename', '', 'string');
$ConfigValue    = system_CleanVars($_REQUEST, 'ConfigValue', '', 'array');
$head_top       = system_CleanVars($_REQUEST, 'head_top', '', 'string');
$head_left      = system_CleanVars($_REQUEST, 'head_left', '', 'string');
$logo_top       = system_CleanVars($_REQUEST, 'logo_top', '', 'string');
$logo_left      = system_CleanVars($_REQUEST, 'logo_left', '', 'string');
$col_name       = system_CleanVars($_REQUEST, 'col_name', '', 'string');
$col_val        = system_CleanVars($_REQUEST, 'col_val', '', 'string');
$display_blocks = system_CleanVars($_REQUEST, 'display_blocks', '', 'string');
$other_web_url  = system_CleanVars($_REQUEST, 'other_web_url', '', 'string');
$web_admins     = system_CleanVars($_REQUEST, 'web_admins', '', 'string');
$menu_font_size = system_CleanVars($_REQUEST, 'menu_font_size', 12, 'int');
$theme_side     = system_CleanVars($_REQUEST, 'theme_side', 'right', 'string');
$dirname        = system_CleanVars($_REQUEST, 'dirname', '', 'string');
$delWebID       = system_CleanVars($_REQUEST, 'delWebID', 0, 'int');

switch ($op) {

    //儲存設定值
    case "save_all_color":
        foreach ($color_setup as $col_name => $col_val) {
            save_web_config($col_name, $col_val, $WebID);
        }
        header("location: {$_SERVER['PHP_SELF']}?WebID={$WebID}");
        exit;
        break;

    //更新班級資料
    case "update_tad_web":
        update_tad_web();
        header("location: {$_SERVER['PHP_SELF']}?WebID={$WebID}");
        exit;
        break;

    case "save_plugins":
        save_plugins($WebID);
        header("location: {$_SERVER['PHP_SELF']}?WebID={$WebID}");
        exit;
        break;

    case "upload_head":
        $TadUpFilesHead = TadUpFilesHead($WebID);
        $TadUpFilesHead->set_col('head', $WebID);
        $TadUpFilesHead->upload_file('head', null, null, null, "", true);
        header("location: {$_SERVER['PHP_SELF']}?WebID={$WebID}");
        exit;
        break;

    case "upload_logo":
        $TadUpFilesLogo = TadUpFilesLogo($WebID);
        $TadUpFilesLogo->set_col('logo', $WebID);
        $TadUpFilesLogo->upload_file('logo', null, null, null, "", true);
        header("location: {$_SERVER['PHP_SELF']}?WebID={$WebID}");
        exit;
        break;

    case "upload_bg":
        $TadUpFilesBg = TadUpFilesBg($WebID);
        $TadUpFilesBg->set_col('bg', $WebID);
        $TadUpFilesBg->upload_file('bg', null, null, null, "", true);
        header("location: {$_SERVER['PHP_SELF']}?WebID={$WebID}");
        exit;
        break;

    //更新照片
    case "update_photo":
        $TadUpFiles->set_col("WebOwner", $WebID, 1);
        $TadUpFiles->del_files();
        $TadUpFiles->upload_file('upfile', 480, 120, null, null, true);
        header("location: {$_SERVER['PHP_SELF']}?WebID={$WebID}");
        exit;
        break;

    case "save_other_web_url":
        save_web_config("other_web_url", $other_web_url, $WebID);
        header("location: {$_SERVER['PHP_SELF']}?WebID={$WebID}");
        exit;
        break;

    case "save_adm":
        save_adm($web_admins, $WebID);
        header("location: {$_SERVER['PHP_SELF']}?WebID={$WebID}");
        exit;
        break;

    case "reset_logo":
        reset_logo($WebID);
        header("location: {$_SERVER['PHP_SELF']}?WebID={$WebID}");
        exit;
        break;

    case "save_theme_config":
        save_web_config('menu_font_size', $menu_font_size, $WebID);
        save_web_config('theme_side', $theme_side, $WebID);
        header("location: {$_SERVER['PHP_SELF']}?WebID={$WebID}");
        exit;
        break;

    case "enabe_plugin":
        enabe_plugin($dirname, $WebID);
        header("location: {$dirname}.php?WebID={$WebID}&op=edit_form");
        exit;
        break;

    case "unable_my_web":
        unable_my_web($WebID);
        header("location: index.php");
        exit;
        break;

    //刪除資料
    case "delete_tad_web_chk":
        if (empty($delWebID) or !$isMyWeb) {
            redirect_header("index.php", 3, _MD_TCW_NOT_OWNER);
        }
        common_template($WebID);
        delete_tad_web_chk($delWebID);
        break;

    //刪除資料
    case "delete_tad_web":
        if (empty($WebID) or !$isMyWeb) {
            redirect_header("index.php", 3, _MD_TCW_NOT_OWNER);
        }
        delete_tad_web($WebID);
        header("location: index.php");
        exit;
        break;

    //預設動作
    default:
        if (empty($WebID)) {
            header("location: index.php");
            exit;
        } else {
            common_template($WebID);
            tad_web_config($WebID);
        }
        break;

}

/*-----------秀出結果區--------------*/
include_once 'footer.php';
include_once XOOPS_ROOT_PATH . '/footer.php';
