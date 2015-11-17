<?php
/*-----------引入檔案區--------------*/
include_once "header.php";
$xoopsOption['template_main'] = 'tad_web_block_b3.html';

include_once XOOPS_ROOT_PATH . "/header.php";
/*-----------function區--------------*/

/*-----------執行動作判斷區----------*/
include_once $GLOBALS['xoops']->path('/modules/system/include/functions.php');
$op    = system_CleanVars($_REQUEST, 'op', '', 'string');
$WebID = system_CleanVars($_REQUEST, 'WebID', 0, 'int');

common_template($WebID);

switch ($op) {

    //新增資料
    case "insert":
        $ScheduleID = $tad_web_schedule->insert();
        header("location: {$_SERVER['PHP_SELF']}?WebID={$WebID}&op=edit_form&ScheduleID=$ScheduleID");
        exit;
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
