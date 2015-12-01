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
function config_block($WebID, $BlockID)
{
    global $xoopsDB, $xoopsTpl;
    $sql = "select * from " . $xoopsDB->prefix("tad_web_blocks") . " where `BlockID`='{$BlockID}'";
    //die($sql);
    $result = $xoopsDB->queryF($sql) or web_error($sql);
    $block  = $xoopsDB->fetchArray($result);
    $func   = $block['BlockName'];
    $config = json_decode($block['BlockConfig'], true);
    include_once XOOPS_ROOT_PATH . "/modules/tad_web/plugins/{$block['plugin']}/config_blocks.php";
    //die($block['BlockName'] . var_export($config));
    $form = array2form($blockConfig[$block['plugin']][$func]['colset'], $config);
    $xoopsTpl->assign('form', $form);
    $xoopsTpl->assign('block', $block);
    $xoopsTpl->assign('config', $config);

    //die(var_export($blocksArr[$func]['config']));
}

function array2form($form_arr = array(), $config = array())
{
    //die(var_export($config));
    if (empty($form_arr)) {
        return;
    }
    $form_code = '<div class="form-group">';
    foreach ($form_arr as $config_name => $form) {
        $form_code .= '
          <label class="col-md-4 control-label">
            ' . $form['label'] . '
          </label>
          <div class="col-md-8">';
        switch ($form['type']) {
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
    }
    $form_code .= '
      </div>
    </div>';
    return $form_code;
}

/*-----------執行動作判斷區----------*/
include_once $GLOBALS['xoops']->path('/modules/system/include/functions.php');
$op         = system_CleanVars($_REQUEST, 'op', '', 'string');
$WebID      = system_CleanVars($_REQUEST, 'WebID', 0, 'int');
$BlockID    = system_CleanVars($_REQUEST, 'BlockID', 0, 'int');
$config     = system_CleanVars($_REQUEST, 'config', '', 'array');
$BlockTitle = system_CleanVars($_REQUEST, 'BlockTitle', '', 'string');

common_template($WebID);

switch ($op) {

    //新增資料
    case "save_block_config":
        $myts             = MyTextSanitizer::getInstance();
        $BlockTitle       = $myts->htmlSpecialChars($BlockTitle);
        $new_block_config = json_encode($config);

        $sql = "update `" . $xoopsDB->prefix("tad_web_blocks") . "` set `BlockConfig`='{$new_block_config}' , `BlockTitle`='{$BlockTitle}' where `BlockID`='{$BlockID}'";
        $xoopsDB->queryF($sql) or web_error($sql);
        header("location: index.php?WebID={$WebID}");
        exit;
        break;

    case "config":
        config_block($WebID, $BlockID);
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
