<?php
/*-----------引入檔案區--------------*/
include_once "header.php";
if (!empty($_REQUEST['WebID']) and $isMyWeb) {
    $xoopsOption['template_main'] = 'tad_web_block_b3.html';
} elseif (!$isMyWeb and $MyWebs) {
    redirect_header($_SERVER['PHP_SELF'] . "?WebID={$MyWebs[0]}", 3, _MD_TCW_AUTO_TO_HOME);
} else {
    redirect_header("index.php?WebID={$_GET['WebID']}", 3, _MD_TCW_NOT_OWNER);
}

include_once XOOPS_ROOT_PATH . "/header.php";
/*-----------function區--------------*/
function config_block($WebID, $BlockID, $mode = "config")
{
    global $xoopsDB, $xoopsTpl;
    $shareBlockID = $shareBlockCount = $webs = '';
    if ($BlockID) {
        $sql    = "select * from " . $xoopsDB->prefix("tad_web_blocks") . " where `BlockID`='{$BlockID}'";
        $result = $xoopsDB->queryF($sql) or web_error($sql);
        $block  = $xoopsDB->fetchArray($result);

        //查詢否為分享區塊
        $sql    = "select BlockID, WebID from " . $xoopsDB->prefix("tad_web_blocks") . " where `BlockName`='{$block['BlockName']}' and plugin='share'";
        $result = $xoopsDB->queryF($sql) or web_error($sql);

        list($shareBlockID, $shareWebID) = $xoopsDB->fetchRow($result);

        if (!empty($shareBlockID)) {

            $sql = "select b.* from " . $xoopsDB->prefix("tad_web_blocks") . " as a left join " . $xoopsDB->prefix("tad_web") . " as b on a.WebID=b.WebID where a.`BlockName`='{$block['BlockName']}' and a.plugin='custom' and a.BlockEnable='1' and a.BlockPosition!='' and a.BlockPosition!='uninstall'";

            $result          = $xoopsDB->queryF($sql) or web_error($sql);
            $shareBlockCount = 0;
            while ($all = $xoopsDB->fetchArray($result)) {
                $webs[$shareBlockCount] = $all;
                $shareBlockCount++;
            }

        }
    }

    $form = $editor = '';
    if ($mode == "add") {
        include_once XOOPS_ROOT_PATH . "/modules/tadtools/ck.php";
        if (!isset($block)) {
            $block['BlockTitle']   = '';
            $block['BlockID']      = '';
            $block['BlockContent'] = '';
            $config['show_title']  = '1';
        }
        $ck = new CKEditor("tad_web", "BlockContent[html]", $block['BlockContent']);
        $ck->setHeight(250);
        $editor = $ck->render();
    } else {

        $func   = $block['BlockName'];
        $config = json_decode($block['BlockConfig'], true);
        include_once XOOPS_ROOT_PATH . "/modules/tad_web/plugins/{$block['plugin']}/config_blocks.php";
        //die($block['BlockName'] . var_export($config));
        if ($block['plugin'] == 'custom') {
            include_once XOOPS_ROOT_PATH . "/modules/tadtools/ck.php";
            $ck = new CKEditor("tad_web", "BlockContent[html]", $block['BlockContent']);
            $ck->setHeight(250);
            $editor = $ck->render();
        } else {
            $form = array2form($blockConfig[$block['plugin']][$func]['colset'], $config);
        }
        $iframeContent = strip_tags($block['BlockContent']);
    }
    $block['config'] = $config;
    // die($form);
    $xoopsTpl->assign('form', $form);
    $xoopsTpl->assign('editor', $editor);
    $xoopsTpl->assign('block', $block);
    $xoopsTpl->assign('iframeContent', $iframeContent);
    // $xoopsTpl->assign('block_config', $config);
    $xoopsTpl->assign('mode', $mode);
    $xoopsTpl->assign('shareBlockID', $shareBlockID);
    $xoopsTpl->assign('shareWebID', $shareWebID);
    $xoopsTpl->assign('shareBlockCount', sprintf(_MD_TCW_USE_BLOCK_SITE, $shareBlockCount));
    $xoopsTpl->assign('use_share_web', $webs);

    if (!file_exists(XOOPS_ROOT_PATH . "/modules/tadtools/sweet_alert.php")) {
        redirect_header("index.php", 3, _MA_NEED_TADTOOLS);
    }
    include_once XOOPS_ROOT_PATH . "/modules/tadtools/sweet_alert.php";
    $sweet_alert      = new sweet_alert();
    $sweet_alert_code = $sweet_alert->render("delete_block_func", "block.php?WebID={$WebID}&op=delete_block&BlockID=", 'BlockID');
    $xoopsTpl->assign('sweet_delete_block_func_code', $sweet_alert_code);
}

function array2form($form_arr = array(), $config = array())
{
    //die(var_export($config));
    if (empty($form_arr)) {
        return;
    }
    $form_code = '';
    foreach ($form_arr as $config_name => $form) {
        $form_code .= '<div class="form-group">';
        $form_code .= '
          <label class="col-md-3 control-label">
            ' . $form['label'] . '
          </label>
          <div class="col-md-9">';
        switch ($form['type']) {
            case 'select':
                $form_code .= '<select name="config[' . $config_name . ']" class="form-control">';
                foreach ($form['options'] as $title => $value) {
                    $selected = $value == $config[$config_name] ? 'selected' : '';
                    $form_code .= '<option value="' . $value . '" ' . $selected . '>' . $title . '</option>';
                }
                $form_code .= '</select>';
                break;

            case 'radio':
                $form_code .= '<input type="radio" name="config[' . $config_name . ']" class="form-control" value="' . $config[$config_name] . '">' . $form['value'] . '';
                break;

            case 'textarea':
                $form_code .= '<textarea name="config[' . $config_name . ']" class="form-control">' . $config[$config_name] . '</textarea>';
                break;

            default:
                $form_code .= '<input type="text" name="config[' . $config_name . ']" class="form-control" value="' . $config[$config_name] . '">';
                break;
        }
        $form_code .= '
          </div>
        </div>';
    }
    return $form_code;
}

function save_block_config($WebID = "", $BlockID = "", $BlockName = "", $BlockTitle = "", $BlockPosition = "", $config = "", $BlockShare = "", $shareBlockID = "")
{
    global $xoopsDB;
    $myts             = MyTextSanitizer::getInstance();
    $BlockTitle       = $myts->addSlashes($BlockTitle);
    $BlockPosition    = $myts->addSlashes($BlockPosition);
    $content_type     = $config['content_type'];
    $BlockContent     = $myts->addSlashes($_POST['BlockContent'][$content_type]);
    $BlockName        = $myts->addSlashes($BlockName);
    $new_block_config = json_encode($config);

    $text_color   = get_web_config('block_pic_text_color', $WebID);
    $border_color = get_web_config('block_pic_border_color', $WebID);
    $text_size    = get_web_config('block_pic_text_size', $WebID);
    $font         = get_web_config('block_pic_font', $WebID);

    //新增的話
    if (empty($BlockID)) {
        $BlockSort = max_blocks_sort($WebID, $BlockPosition);
        $num       = max_custom_block_num($WebID);
        $BlockName = ($BlockShare == '1') ? "share_{$WebID}_{$num}" : "custom_{$WebID}_{$num}";
        $sql       = "insert into `" . $xoopsDB->prefix("tad_web_blocks") . "` (`BlockName`, `BlockCopy`, `BlockTitle`, `BlockContent`, `BlockEnable`, `BlockConfig`, `BlockPosition`, `BlockSort`, `BlockShare`, `WebID`, `plugin`) values('{$BlockName}', '0', '{$BlockTitle}', '{$BlockContent}', '0', '{$new_block_config}', '{$BlockPosition}', '{$BlockSort}', '{$BlockShare}', '{$WebID}', 'custom')";

        $xoopsDB->queryF($sql) or web_error($sql);
        //取得最後新增資料的流水編號
        $BlockID = $xoopsDB->getInsertId();

        //共享區塊
        if ($BlockShare == '1') {
            $sql = "insert into `" . $xoopsDB->prefix("tad_web_blocks") . "` (`BlockName`, `BlockCopy`, `BlockTitle`, `BlockContent`, `BlockEnable`, `BlockConfig`, `BlockPosition`, `BlockSort`, `BlockShare`, `WebID`, `plugin`) values('{$BlockName}', '0', '{$BlockTitle}', '{$BlockContent}', '0', '{$new_block_config}', '', '{$BlockSort}', '1', '{$WebID}', 'share')";
            $xoopsDB->queryF($sql) or web_error($sql);
            //取得最後新增資料的流水編號
            $shareBlockID = $xoopsDB->getInsertId();
        }
    } else {
        //更新區塊
        $sql = "update `" . $xoopsDB->prefix("tad_web_blocks") . "` set `BlockConfig`='{$new_block_config}' , `BlockTitle`='{$BlockTitle}' , `BlockPosition`='{$BlockPosition}' , `BlockContent`='{$BlockContent}'  where `BlockID`='{$BlockID}'";
        $xoopsDB->queryF($sql) or web_error($sql);

        //共享區塊若不再共享（直接刪除之）
        if (!empty($shareBlockID) and $BlockShare != '1') {
            //取得共享區塊資訊
            $sql        = "select * from " . $xoopsDB->prefix("tad_web_blocks") . " where BlockID='{$shareBlockID}'";
            $result     = $xoopsDB->query($sql) or web_error($sql);
            $shareBlock = $xoopsDB->fetchArray($result);

            //刪除共享資訊
            $sql = "delete from `" . $xoopsDB->prefix("tad_web_blocks") . "` where `BlockID`='{$shareBlockID}'";
            $xoopsDB->queryF($sql) or web_error($sql);

            //把已經共享的區塊改名字
            $sql    = "select BlockID,WebID from " . $xoopsDB->prefix("tad_web_blocks") . " where BlockName='{$shareBlock['BlockName']}'";
            $result = $xoopsDB->queryF($sql) or web_error($sql);
            while (list($BlockID, $WebID) = $xoopsDB->fetchRow($result)) {
                $num = max_custom_block_num($WebID);
                $sql = "update `" . $xoopsDB->prefix("tad_web_blocks") . "` set `BlockName`='custom_{$WebID}_{$num}' where `BlockID`='{$BlockID}'";
                $xoopsDB->queryF($sql) or web_error($sql);
            }

            //共享區塊若改為共享
        } elseif (empty($shareBlockID) and $BlockShare == '1') {
            //取得原區塊資訊
            $sql    = "select * from " . $xoopsDB->prefix("tad_web_blocks") . " where BlockID='{$BlockID}'";
            $result = $xoopsDB->query($sql) or web_error($sql);
            $Block  = $xoopsDB->fetchArray($result);

            //新增共享區塊
            $BlockName    = str_replace('custom_', 'share_', $Block['BlockName']);
            $BlockTitle   = $myts->addSlashes($Block['BlockTitle']);
            $BlockContent = $myts->addSlashes($Block['BlockContent']);
            $BlockConfig  = $myts->addSlashes($Block['BlockConfig']);
            $sql          = "insert into `" . $xoopsDB->prefix("tad_web_blocks") . "` (`BlockName`, `BlockCopy`, `BlockTitle`, `BlockContent`, `BlockEnable`, `BlockConfig`, `BlockPosition`, `BlockSort`, `BlockShare`, `WebID`, `plugin`) values('{$BlockName}', '0', '{$BlockTitle}', '{$BlockContent}', '0', '{$BlockConfig}', '', '{$Block['BlockSort']}', '1', '{$Block['WebID']}', 'share')";
            $xoopsDB->queryF($sql) or web_error($sql);

            //更新原有區塊名稱
            $sql = "update `" . $xoopsDB->prefix("tad_web_blocks") . "` set `BlockName`='{$BlockName}' where `BlockID`='{$BlockID}'";
            $xoopsDB->queryF($sql) or web_error($sql);

        }
    }

    mkTitlePic($WebID, "block_{$BlockName}", $BlockTitle, $text_color, $border_color, $text_size, $font);
}

//自動取得tad_web_blocks的最新排序
function max_custom_block_num($WebID)
{
    global $xoopsDB;
    $sql         = "select count(*) from " . $xoopsDB->prefix("tad_web_blocks") . " where WebID='$WebID' and plugin='custom'";
    $result      = $xoopsDB->query($sql) or web_error($sql);
    list($count) = $xoopsDB->fetchRow($result);
    return ++$count;
}

//產生區塊標題
function mk_block_pic($WebID = "", $block_pic = array(), $use_block_pic = '')
{
    global $xoopsDB;
    foreach ($block_pic as $item => $val) {
        save_web_config($item, $val, $WebID);
    }
    save_web_config('use_block_pic', $use_block_pic, $WebID);
    $sql    = "select BlockID,BlockName,BlockTitle from " . $xoopsDB->prefix("tad_web_blocks") . " where `WebID`='{$WebID}'";
    $result = $xoopsDB->queryF($sql) or web_error($sql);

    while (list($BlockID, $BlockName, $BlockTitle) = $xoopsDB->fetchRow($result)) {
        $filename = "block_{$BlockName}";
        mkTitlePic($WebID, $filename, $BlockTitle, $block_pic['block_pic_text_color'], $block_pic['block_pic_border_color'], $block_pic['block_pic_text_size'], $block_pic['block_pic_font']);
    }
}

function block_setup($WebID = "")
{
    global $xoopsDB, $xoopsTpl;

    //顏色設定
    if (!file_exists(XOOPS_ROOT_PATH . "/modules/tadtools/mColorPicker.php")) {
        redirect_header("index.php", 3, _MA_NEED_TADTOOLS);
    }
    include_once XOOPS_ROOT_PATH . "/modules/tadtools/mColorPicker.php";
    $mColorPicker      = new mColorPicker('.color');
    $mColorPicker_code = $mColorPicker->render();

    $text_color   = get_web_config('block_pic_text_color', $WebID);
    $border_color = get_web_config('block_pic_border_color', $WebID);
    $text_size    = get_web_config('block_pic_text_size', $WebID);
    $font         = get_web_config('block_pic_font', $WebID);

    $block_pic_text_color   = empty($text_color) ? "#ABBF6B" : $text_color;
    $block_pic_border_color = empty($border_color) ? "#ffffff" : $border_color;
    $block_pic_text_size    = empty($text_size) ? "18" : $text_size;
    $block_pic_font         = empty($font) ? "DroidSansFallback.ttf" : $font;

    $xoopsTpl->assign('mColorPicker_code', $mColorPicker_code);
    $xoopsTpl->assign('block_pic_text_color', $block_pic_text_color);
    $xoopsTpl->assign('block_pic_border_color', $block_pic_border_color);
    $xoopsTpl->assign('block_pic_text_size', $block_pic_text_size);
    $xoopsTpl->assign('block_pic_font', $block_pic_font);
}

//刪除區塊
function delete_block($BlockID, $WebID)
{
    global $xoopsDB, $MyWebs, $isAdmin;
    if (!$isAdmin and !in_array($WebID, $MyWebs)) {
        return;
    }
    $sql = "delete from " . $xoopsDB->prefix("tad_web_blocks") . " where BlockID='{$BlockID}'";
    $xoopsDB->queryF($sql) or web_error($sql);
}

/*-----------執行動作判斷區----------*/
include_once $GLOBALS['xoops']->path('/modules/system/include/functions.php');
$op            = system_CleanVars($_REQUEST, 'op', '', 'string');
$WebID         = system_CleanVars($_REQUEST, 'WebID', 0, 'int');
$BlockID       = system_CleanVars($_REQUEST, 'BlockID', 0, 'int');
$shareBlockID  = system_CleanVars($_REQUEST, 'shareBlockID', 0, 'int');
$config        = system_CleanVars($_REQUEST, 'config', '', 'array');
$BlockTitle    = system_CleanVars($_REQUEST, 'BlockTitle', '', 'string');
$BlockName     = system_CleanVars($_REQUEST, 'BlockName', '', 'string');
$BlockPosition = system_CleanVars($_REQUEST, 'BlockPosition', '', 'string');
$BlockShare    = system_CleanVars($_REQUEST, 'BlockShare', '', 'string');
$block_pic     = system_CleanVars($_REQUEST, 'block_pic', '', 'array');
$use_block_pic = system_CleanVars($_REQUEST, 'use_block_pic', '', 'string');

common_template($WebID);

switch ($op) {

    //新增資料
    case "save_block_config":
        save_block_config($WebID, $BlockID, $BlockName, $BlockTitle, $BlockPosition, $config, $BlockShare, $shareBlockID);
        header("location: block.php?WebID={$WebID}");
        exit;
        break;

    case "config":
        config_block($WebID, $BlockID);
        break;

    case "add_block":
        config_block($WebID, $BlockID, "add");
        break;

    case "mk_block_pic":
        mk_block_pic($WebID, $block_pic, $use_block_pic);
        header("location: block.php?WebID={$WebID}");
        break;
    case "delete_block":
        delete_block($BlockID, $WebID);
        header("location: block.php?WebID={$WebID}");
        break;
    //預設動作
    default:
        //die(var_export(get_all_blocks('limit')));
        block_setup($WebID);
        $xoopsTpl->assign('block1', get_position_blocks($WebID, 'block1'));
        $xoopsTpl->assign('block2', get_position_blocks($WebID, 'block2'));
        $xoopsTpl->assign('block3', get_position_blocks($WebID, 'block3'));
        $xoopsTpl->assign('block4', get_position_blocks($WebID, 'block4'));
        $xoopsTpl->assign('block5', get_position_blocks($WebID, 'block5'));
        $xoopsTpl->assign('block6', get_position_blocks($WebID, 'block6'));
        $xoopsTpl->assign('side', get_position_blocks($WebID, 'side'));
        $xoopsTpl->assign('uninstall', get_position_blocks($WebID, 'uninstall'));
        break;

}

/*-----------秀出結果區--------------*/
include_once 'footer.php';
include_once XOOPS_ROOT_PATH . '/footer.php';
