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
    $xoopsTpl->assign('isMine', $isMyWeb);
    $xoopsTpl->assign('config', true);
    $configs = get_web_all_config($WebID);

    foreach ($configs as $ConfigName => $ConfigValue) {
        $xoopsTpl->assign($ConfigName, $ConfigValue);
    }

    //網站設定
    $Web = get_tad_web($WebID);
    $xoopsTpl->assign('WebName', $Web['WebName']);

    $TadUpFiles->set_col("WebOwner", $WebID, 1);
    $teacher_pic = $TadUpFiles->get_pic_file();
    $xoopsTpl->assign('teacher_thumb_pic', $teacher_pic);

    $upform = $TadUpFiles->upform(true, 'upfile', '1', false);
    $xoopsTpl->assign('upform_teacher', $upform);

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
    $TadUpFilesBg = TadUpFilesBg();
    $xoopsTpl->assign('upform_bg', $TadUpFilesBg->upform(false, "bg", null, false));
    $TadUpFilesBg->set_col("bg", $WebID);
    $xoopsTpl->assign('all_bg', $TadUpFilesBg->get_file_for_smarty());

    //標題設定
    $head_path      = XOOPS_ROOT_PATH . "/modules/tad_web/images/head";
    $head_user_path = XOOPS_ROOT_PATH . "/uploads/tad_web/{$WebID}/head";
    mk_dir($head_user_path);
    mk_dir("{$head_user_path}/thumbs");
    import_img($head_path, "head", $WebID);
    $TadUpFilesHead = TadUpFilesHead();
    $xoopsTpl->assign('upform_head', $TadUpFilesHead->upform(false, "head", null, false));
    $TadUpFilesHead->set_col("head", $WebID);
    $xoopsTpl->assign('all_head', $TadUpFilesHead->get_file_for_smarty());

    //logo設定
    $logo_path      = XOOPS_ROOT_PATH . "/modules/tad_web/images/logo";
    $logo_user_path = XOOPS_ROOT_PATH . "/uploads/tad_web/{$WebID}/logo";
    mk_dir($logo_user_path);
    mk_dir("{$logo_user_path}/thumbs");
    import_img($logo_path, "logo", $WebID);
    $TadUpFilesLogo = TadUpFilesLogo();
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

    //區塊設定
    //$display_blocks = get_web_config("display_blocks", $WebID);
    if (!empty($configs['display_blocks'])) {
        $display_blocks_arr = explode(',', $display_blocks);
    } else {
        $display_blocks_arr = "";
    }

    $sql    = "select bid,name,title from " . $xoopsDB->prefix("newblocks") . " where dirname='tad_web' order by weight";
    $result = $xoopsDB->query($sql) or web_error($sql);

    $myts     = MyTextSanitizer::getInstance();
    $block_ok = $block_yet = $block_name = "";

    while ($all = $xoopsDB->fetchArray($result)) {
        foreach ($all as $k => $v) {
            $$k = $v;
        }
        $name  = $myts->htmlSpecialChars($name);
        $title = $myts->htmlSpecialChars($title);
        if (!empty($display_blocks)) {
            if (!in_array($bid, $display_blocks_arr)) {
                $block_yet .= "<option value=\"$bid\">{$name}</option>";
            }
            $block_name[$bid] = $name;
        } else {
            $block_ok .= "<option value=\"$bid\">{$name}</option>";
            $blocks[] = $bid;
        }
    }
    if (empty($display_blocks_arr)) {
        $display_blocks = implode(',', $blocks);
    } else {
        foreach ($display_blocks_arr as $bid) {
            $block_ok .= "<option value=\"$bid\">{$block_name[$bid]}</option>";
        }
    }
    $block_content = "
        <script type=\"text/javascript\" src=\"" . XOOPS_URL . "/modules/tad_web/class/tmt_core.js\"></script>
        <script type=\"text/javascript\" src=\"" . XOOPS_URL . "/modules/tad_web/class/tmt_spry_linkedselect.js\"></script>
        <script type=\"text/javascript\">
        function getOptions()
        {

        var values = [];
        var sel = document.getElementById('destination');
        for (var i=0, n=sel.options.length;i<n;i++) {
          if (sel.options[i].value) values.push(sel.options[i].value);
        }
          document.getElementById('display_blocks').value=values.join(',');
          }
        </script>

        <table style='width:auto'>

            <tr>
                <td style='vertical-align:top;'>
                    <h3>" . _MD_TCW_BLOCKS_LIST . "</h3>
                    <select name=\"repository\" id=\"repository\" size=\"12\" multiple=\"multiple\" tmt:linkedselect=\"true\" style='width: 300px;'>
                    $block_yet
                    </select>
                </td>
                <td style='vertical-align:middle'>
                    <img src=\"" . XOOPS_URL . "/modules/tad_web/images/right.png\" onclick=\"tmt.spry.linkedselect.util.moveOptions('repository', 'destination');getOptions();\"><br>
                    <img src=\"" . XOOPS_URL . "/modules/tad_web/images/left.png\" onclick=\"tmt.spry.linkedselect.util.moveOptions('destination' , 'repository');getOptions();\"><br><br>

                    <img src=\"" . XOOPS_URL . "/modules/tad_web/images/up.png\" onclick=\"tmt.spry.linkedselect.util.moveOptionUp('destination');getOptions();\"><br>
                    <img src=\"" . XOOPS_URL . "/modules/tad_web/images/down.png\" onclick=\"tmt.spry.linkedselect.util.moveOptionDown('destination');getOptions();\">
                </td>
                <td style='vertical-align:top;'>
                    <h3>" . _MD_TCW_BLOCKS_SELECTED . "</h3>
                    <select id=\"destination\" size=\"12\" multiple=\"multiple\" tmt:linkedselect=\"true\" style='width: 300px;'>
                    $block_ok
                    </select>
                </td>
            </tr>
            <tr>
                <td colspan=4>
                    <input type='hidden' name='display_blocks' id='display_blocks' value='$display_blocks'>
                </td>
            </tr>
        </table>
    ";
    $xoopsTpl->assign('block_content', $block_content);

}

//更新網頁資訊
function update_tad_web()
{
    global $xoopsDB, $xoopsUser, $WebID;

    $myts             = &MyTextSanitizer::getInstance();
    $_POST['WebName'] = $myts->addSlashes($_POST['WebName']);

    $sql = "update " . $xoopsDB->prefix("tad_web") . " set
   `WebName` = '{$_POST['WebName']}'
    where WebID ='{$WebID}'";
    $xoopsDB->queryF($sql) or web_error($sql);

    $TadUpFilesLogo = TadUpFilesLogo();
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
    //echo var_export($plugins);
    $myts = &MyTextSanitizer::getInstance();
    $i    = 1;

    $sql = "delete from " . $xoopsDB->prefix("tad_web_plugins") . " where WebID='{$WebID}'";
    $xoopsDB->queryF($sql) or web_error($sql);
    $enable_plugins = $display_plugins = '';
    foreach ($plugins as $plugin) {
        $dirname      = $plugin['dirname'];
        $PluginTitle  = $myts->addSlashes($_POST['plugin_name'][$dirname]);
        $PluginEnable = ($_POST['plugin_enable'][$dirname] == '1') ? '1' : '0';

        $sql = "replace into " . $xoopsDB->prefix("tad_web_plugins") . " (`PluginDirname`, `PluginTitle`, `PluginSort`, `PluginEnable`, `WebID`) values('{$dirname}', '{$PluginTitle}', '{$i}', '{$PluginEnable}', '{$WebID}')";
        $xoopsDB->queryF($sql) or web_error($sql);

        save_web_config($dirname . '_limit', $_POST['plugin_limit'][$dirname]);
        //save_web_config($dirname . '_display', $_POST['plugin_display'][$dirname]);
        if ($PluginEnable == '1') {
            $enable_plugins[] = $dirname;
            if ($_POST['plugin_display'][$dirname] == '1') {
                $display_plugins[] = $dirname;
            }
        }
        $i++;
    }
    save_web_config('web_plugin_enable_arr', implode(',', $enable_plugins));
    save_web_config('web_setup_show_arr', implode(',', $display_plugins));
    mk_menu_var_file($WebID);

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

switch ($op) {

    //儲存設定值
    case "save_all_color":
        foreach ($color_setup as $col_name => $col_val) {
            save_web_config($col_name, $col_val);
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
        $TadUpFilesHead = TadUpFilesHead();
        $TadUpFilesHead->set_col('head', $WebID);
        $TadUpFilesHead->upload_file('head', null, null, null, "", true);
        header("location: {$_SERVER['PHP_SELF']}?WebID={$WebID}");
        exit;
        break;

    case "upload_logo":
        $TadUpFilesLogo = TadUpFilesLogo();
        $TadUpFilesLogo->set_col('logo', $WebID);
        $TadUpFilesLogo->upload_file('logo', null, null, null, "", true);
        header("location: {$_SERVER['PHP_SELF']}?WebID={$WebID}");
        exit;
        break;

    case "upload_bg":
        $TadUpFilesBg = TadUpFilesBg();
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

    case "save_block":
        save_web_config("display_blocks", $display_blocks);
        header("location: {$_SERVER['PHP_SELF']}?WebID={$WebID}");
        exit;
        break;

    case "save_other_web_url":
        save_web_config("other_web_url", $other_web_url);
        header("location: {$_SERVER['PHP_SELF']}?WebID={$WebID}");
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
