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
    $shareBlockID = '';
    if ($BlockID) {
        $sql    = "select * from " . $xoopsDB->prefix("tad_web_blocks") . " where `BlockID`='{$BlockID}'";
        $result = $xoopsDB->queryF($sql) or web_error($sql);
        $block  = $xoopsDB->fetchArray($result);

        $sql    = "select BlockID, WebID from " . $xoopsDB->prefix("tad_web_blocks") . " where `BlockName`='{$block['BlockName']}' and plugin='share'";
        $result = $xoopsDB->queryF($sql) or web_error($sql);

        list($shareBlockID, $shareWebID) = $xoopsDB->fetchRow($result);
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
    $xoopsTpl->assign('config', $config);
    $xoopsTpl->assign('mode', $mode);
    $xoopsTpl->assign('shareBlockID', $shareBlockID);
    $xoopsTpl->assign('shareWebID', $shareWebID);

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

function save_block_config($WebID = "", $BlockID = "", $BlockTitle = "", $BlockPosition = "", $config = "", $BlockShare = "")
{
    global $xoopsDB;
    $myts             = MyTextSanitizer::getInstance();
    $BlockTitle       = $myts->addSlashes($BlockTitle);
    $BlockPosition    = $myts->addSlashes($BlockPosition);
    $content_type     = $config['content_type'];
    $BlockContent     = $myts->addSlashes($_POST['BlockContent'][$content_type]);
    $new_block_config = json_encode($config);

    if (empty($BlockID)) {
        $BlockSort = max_blocks_sort($WebID, $BlockPosition);
        $num       = max_custom_block_num($WebID);
        $sql       = "insert into `" . $xoopsDB->prefix("tad_web_blocks") . "` (`BlockName`, `BlockCopy`, `BlockTitle`, `BlockContent`, `BlockEnable`, `BlockConfig`, `BlockPosition`, `BlockSort`, `BlockShare`, `WebID`, `plugin`) values('custom_block_{$num}', '0', '{$BlockTitle}', '{$BlockContent}', '0', '{$new_block_config}', '{$BlockPosition}', '{$BlockSort}', '{$BlockShare}', '{$WebID}', 'custom')";
    } else {
        $sql = "update `" . $xoopsDB->prefix("tad_web_blocks") . "` set `BlockConfig`='{$new_block_config}' , `BlockTitle`='{$BlockTitle}' , `BlockPosition`='{$BlockPosition}' , `BlockContent`='{$BlockContent}'  where `BlockID`='{$BlockID}'";
    }
    $xoopsDB->queryF($sql) or web_error($sql);

    //共享區塊
    if ($BlockShare == '1') {
        $BlockName = empty($_POST['BlockName']) ? "custom_block_{$num}" : $_POST['BlockName'];
        $sql       = "insert into `" . $xoopsDB->prefix("tad_web_blocks") . "` (`BlockName`, `BlockCopy`, `BlockTitle`, `BlockContent`, `BlockEnable`, `BlockConfig`, `BlockPosition`, `BlockSort`, `BlockShare`, `WebID`, `plugin`) values('{$BlockName}', '0', '{$BlockTitle}', '{$BlockContent}', '0', '{$new_block_config}', '', '{$BlockSort}', '1', '{$WebID}', 'share')";
        $xoopsDB->queryF($sql) or web_error($sql);
    }
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

/*-----------執行動作判斷區----------*/
include_once $GLOBALS['xoops']->path('/modules/system/include/functions.php');
$op            = system_CleanVars($_REQUEST, 'op', '', 'string');
$WebID         = system_CleanVars($_REQUEST, 'WebID', 0, 'int');
$BlockID       = system_CleanVars($_REQUEST, 'BlockID', 0, 'int');
$config        = system_CleanVars($_REQUEST, 'config', '', 'array');
$BlockTitle    = system_CleanVars($_REQUEST, 'BlockTitle', '', 'string');
$BlockPosition = system_CleanVars($_REQUEST, 'BlockPosition', '', 'string');
$BlockShare    = system_CleanVars($_REQUEST, 'BlockShare', '', 'string');

common_template($WebID);

switch ($op) {

    //新增資料
    case "save_block_config":
        save_block_config($WebID, $BlockID, $BlockTitle, $BlockPosition, $config, $BlockShare);
        header("location: block.php?WebID={$WebID}");
        exit;
        break;

    case "config":
        config_block($WebID, $BlockID);
        break;

    case "add_block":
        config_block($WebID, $BlockID, "add");
        break;

    //預設動作
    default:
        //die(var_export(get_all_blocks('limit')));

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
