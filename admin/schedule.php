<?php
/*-----------引入檔案區--------------*/
$xoopsOption['template_main'] = 'tad_web_adm_schedule.tpl';
include_once 'header.php';
include_once '../function.php';
include_once '../class/cate.php';
/*-----------function區--------------*/

function schedule_template()
{
    global $xoopsDB, $xoopsTpl, $xoopsModuleConfig;

    include_once XOOPS_ROOT_PATH . '/modules/tadtools/ck.php';
    mk_dir(XOOPS_ROOT_PATH . '/uploads/tad_web/schedule');
    mk_dir(XOOPS_ROOT_PATH . '/uploads/tad_web/schedule/image');
    mk_dir(XOOPS_ROOT_PATH . '/uploads/tad_web/schedule/file');
    $schedule_template = $xoopsModuleConfig['schedule_template'];

    $ck = new CKEditor('tad_web/schedule', 'schedule_template', $schedule_template);
    $ck->setHeight(300);
    $ck->setContentCss(XOOPS_URL . '/modules/tad_web/plugins/schedule/schedule.css');
    $editor = $ck->render();
    $xoopsTpl->assign('schedule_template', $editor);
    $xoopsTpl->assign('schedule_subjects', $xoopsModuleConfig['schedule_subjects']);
}

function save_schedule_template()
{
    global $xoopsModule, $xoopsDB;
    $conf_modid = $xoopsModule->getVar('mid');

    $myts = MyTextSanitizer::getInstance();
    $conf_value = $myts->addSlashes($_POST['schedule_template']);

    $sql = 'update ' . $xoopsDB->prefix('config') . " set conf_value='$conf_value' where conf_modid='$conf_modid' and conf_name='schedule_template'";
    $xoopsDB->queryF($sql) or web_error($sql, __FILE__, __LINE__);
}

function save_schedule_subjects()
{
    global $xoopsModule, $xoopsDB;
    $conf_modid = $xoopsModule->getVar('mid');

    $myts = MyTextSanitizer::getInstance();
    $conf_value = $myts->addSlashes($_POST['schedule_subjects']);

    $sql = 'update ' . $xoopsDB->prefix('config') . " set conf_value='$conf_value' where conf_modid='$conf_modid' and conf_name='schedule_subjects'";
    $xoopsDB->queryF($sql) or web_error($sql, __FILE__, __LINE__);
}
/*-----------執行動作判斷區----------*/
include_once $GLOBALS['xoops']->path('/modules/system/include/functions.php');
$op = system_CleanVars($_REQUEST, 'op', '', 'string');
$WebID = system_CleanVars($_REQUEST, 'WebID', 0, 'int');
$CateID = system_CleanVars($_REQUEST, 'CateID', 0, 'int');

$xoopsTpl->assign('op', $op);

switch ($op) {
    /*---判斷動作請貼在下方---*/

    case 'save_schedule_subjects':
        save_schedule_subjects();
        header("location: {$_SERVER['PHP_SELF']}");
        exit;
        break;
    case 'save_schedule_template':
        save_schedule_template();
        header("location: {$_SERVER['PHP_SELF']}");
        exit;
        break;
    //預設動作
    default:
        schedule_template();
        break;
        /*---判斷動作請貼在上方---*/
}

/*-----------秀出結果區--------------*/
include_once 'footer.php';
