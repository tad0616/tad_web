<?php
/*-----------引入檔案區--------------*/
include_once "header.php";
if (!empty($_GET['WebID'])) {
    $xoopsOption['template_main'] = 'tad_web_config_b3.html';
} else {
    $xoopsOption['template_main'] = set_bootstrap('tad_web_config.html');
}
if (!$isMyWeb) {
    redirect_header("index.php?WebID={$_GET['WebID']}", 3, _MD_TCW_NOT_OWNER);
}
include_once XOOPS_ROOT_PATH . "/header.php";
/*-----------function區--------------*/

//網站設定
function tad_web_config($WebID)
{
    global $xoopsDB, $xoopsTpl, $MyWebs, $op, $TadUpFiles;

    $bg_path      = XOOPS_ROOT_PATH . "/modules/tad_web/images/bg";
    $bg_user_path = XOOPS_ROOT_PATH . "/uploads/tad_web/{$WebID}/bg";
    mk_dir($bg_user_path);
    mk_dir("{$bg_user_path}/thumbs");
    import_img($bg_path, "bg", $WebID);
    $TadUpFilesBg = TadUpFilesBg();
    $xoopsTpl->assign('upform_bg', $TadUpFilesBg->upform(false, "bg", null, false));
    $TadUpFilesBg->set_col("bg", $WebID);
    $xoopsTpl->assign('all_bg', $TadUpFilesBg->get_file_for_smarty());

    $head_path      = XOOPS_ROOT_PATH . "/modules/tad_web/images/head";
    $head_user_path = XOOPS_ROOT_PATH . "/uploads/tad_web/{$WebID}/head";
    mk_dir($head_user_path);
    mk_dir("{$head_user_path}/thumbs");
    import_img($head_path, "head", $WebID);
    $TadUpFilesHead = TadUpFilesHead();
    $xoopsTpl->assign('upform_head', $TadUpFilesHead->upform(false, "head", null, false));
    $TadUpFilesHead->set_col("head", $WebID);
    $xoopsTpl->assign('all_head', $TadUpFilesHead->get_file_for_smarty());

    $logo_path      = XOOPS_ROOT_PATH . "/modules/tad_web/images/logo";
    $logo_user_path = XOOPS_ROOT_PATH . "/uploads/tad_web/{$WebID}/logo";
    mk_dir($logo_user_path);
    mk_dir("{$logo_user_path}/thumbs");
    import_img($logo_path, "logo", $WebID);
    $TadUpFilesLogo = TadUpFilesLogo();
    $xoopsTpl->assign('upform_logo', $TadUpFilesLogo->upform(false, "logo", null, false));
    $TadUpFilesLogo->set_col("logo", $WebID);
    $xoopsTpl->assign('all_logo', $TadUpFilesLogo->get_file_for_smarty());

    $xoopsTpl->assign('config', true);
    get_jquery(true);

    $TadUpFiles->set_col("WebOwner", $WebID, 1);
    $teacher_pic = $TadUpFiles->get_pic_file('thumb');
    $xoopsTpl->assign('teacher_thumb_pic', $teacher_pic);

    $upform = $TadUpFiles->upform(true, 'upfile', '1', false);
    $xoopsTpl->assign('upform', $upform);

    $ConfigValue   = get_web_config("hide_function", $WebID);
    $hide_function = explode(';', $ConfigValue);

    $mod_name['aboutus']  = _MD_TCW_ABOUTUS;
    $mod_name['news']     = _MD_TCW_NEWS;
    $mod_name['works']    = _MD_TCW_WORKS;
    $mod_name['homework'] = _MD_TCW_HOMEWORK;
    $mod_name['files']    = _MD_TCW_FILES;
    $mod_name['action']   = _MD_TCW_ACTION;
    $mod_name['video']    = _MD_TCW_VIDEO;
    $mod_name['link']     = _MD_TCW_LINK;
    $mod_name['discuss']  = _MD_TCW_DISCUSS;
    $mod_name['calendar'] = _MD_TCW_CALENDAR;

    $all_functions = "";

    $inline = $_SESSION['bootstrap'] == '3' ? '-inline' : ' inline';

    foreach ($mod_name as $function_name => $function_text) {
        $checked = in_array($function_name, $hide_function) ? "checked" : "";
        $all_functions .= "
        <label class='checkbox{$inline}'>
          <input name='ConfigValue[]' type='checkbox' value='{$function_name}' $checked>{$function_text}
        </label>";
    }

    $Web = get_tad_web($WebID);

    $xoopsTpl->assign('all_functions', $all_functions);
    $xoopsTpl->assign('op', 'tad_web_config');
    $xoopsTpl->assign('isMine', isMine());
    $xoopsTpl->assign('WebName', $Web['WebName']);

    $TadUpFiles->set_col("WebOwner", $WebID);
    $list_del_file = $TadUpFiles->list_del_file();

    $xoopsTpl->assign('list_del_file', $list_del_file);

    if (!file_exists(XOOPS_ROOT_PATH . "/modules/tadtools/mColorPicker.php")) {
        redirect_header("index.php", 3, _MA_NEED_TADTOOLS);
    }
    include_once XOOPS_ROOT_PATH . "/modules/tadtools/mColorPicker.php";
    $mColorPicker      = new mColorPicker('.color');
    $mColorPicker_code = $mColorPicker->render();
    $xoopsTpl->assign('mColorPicker_code', $mColorPicker_code);

    //區塊設定

    $display_blocks = get_web_config("display_blocks", $WebID);
    if (!empty($display_blocks)) {
        $display_blocks_arr = explode(',', $display_blocks);
    } else {
        $display_blocks_arr = "";
    }

    $sql    = "select bid,name,title from " . $xoopsDB->prefix("newblocks") . " where dirname='tad_web' order by weight";
    $result = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'], 3, mysql_error());

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
    $xoopsDB->queryF($sql) or redirect_header($_SERVER['PHP_SELF'], 3, mysql_error());

    $TadUpFilesLogo = TadUpFilesLogo();
    $TadUpFilesLogo->set_col('logo', $WebID, 1);
    $TadUpFilesLogo->del_files();

    mklogoPic($WebID);
    $TadUpFilesLogo->import_one_file(XOOPS_ROOT_PATH . "/uploads/tad_web/{$WebID}/auto_logo/auto_logo.png", null, 1280, 150, null, 'auto_logo.png', false);
    //import_img(XOOPS_ROOT_PATH . "/uploads/tad_web/{$WebID}/auto_logo", "logo", $WebID);
    output_head_file();
}

//移除網站設定
function delete_web_config($ConfigName = "")
{
    global $xoopsDB, $xoopsUser, $WebID, $MyWebs;

    $sql = "delete from " . $xoopsDB->prefix("tad_web_config") . " where `ConfigName`='{$ConfigName}' and `WebID`='{$MyWebs}'";
    $xoopsDB->queryF($sql) or redirect_header($_SERVER['PHP_SELF'], 3, mysql_error());

}

/*-----------執行動作判斷區----------*/
include_once $GLOBALS['xoops']->path('/modules/system/include/functions.php');
$op             = system_CleanVars($_REQUEST, 'op', '', 'string');
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

common_template($WebID);

switch ($op) {
    //儲存設定值
    case "save_color":
        save_web_config($col_name, $col_val);
        header("location: {$_SERVER['PHP_SELF']}?WebID={$WebID}");
        exit;
        break;

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

    //標題設定
    case "save_head":
        save_web_config("web_head", $filename);
        output_head_file();
        break;

    case "save_hide_function":
        $ConfigValue = implode(';', $ConfigValue);
        save_web_config("hide_function", $ConfigValue);
        header("location: {$_SERVER['PHP_SELF']}?WebID={$WebID}");
        exit;
        break;

    case "save_head_bg":
        save_web_config("head_top", $head_top);
        save_web_config("head_left", $head_left);
        output_head_file();
        break;

    case "upload_head":
        $TadUpFilesHead = TadUpFilesHead();
        $TadUpFilesHead->set_col('head', $WebID);
        $TadUpFilesHead->upload_file('head', null, null, null, "", true);
        header("location: {$_SERVER['PHP_SELF']}?WebID={$WebID}");
        exit;
        break;

    //logo設定
    case "save_logo":
        save_web_config("logo_top", $logo_top);
        save_web_config("logo_left", $logo_left);
        output_head_file();
        break;

    case "save_logo_pic":
        save_web_config("web_logo", $filename);
        output_head_file();
        break;

    case "upload_logo":
        $TadUpFilesLogo = TadUpFilesLogo();
        $TadUpFilesLogo->set_col('logo', $WebID);
        $TadUpFilesLogo->upload_file('logo', null, null, null, "", true);
        header("location: {$_SERVER['PHP_SELF']}?WebID={$WebID}");
        exit;
        break;

    //標題設定
    case "save_bg":
        save_web_config("web_bg", $filename);
        output_head_file();
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
        $TadUpFiles->upload_file('upfile', 1024, 480, null, null, true);
        header("location: {$_SERVER['PHP_SELF']}?WebID={$WebID}");
        exit;
        break;

    case "save_block":
        save_web_config("display_blocks", $display_blocks);
        header("location: {$_SERVER['PHP_SELF']}?WebID={$WebID}");
        break;

    //預設動作
    default:
        if (empty($WebID)) {
            header("location: index.php");
            exit;
        } else {
            tad_web_config($WebID);
        }
        break;

}

/*-----------秀出結果區--------------*/
include_once '/footer.php';
include_once XOOPS_ROOT_PATH . '/footer.php';
