<?php
use Xmf\Request;
use XoopsModules\Tadtools\CkEditor;
use XoopsModules\Tadtools\Utility;
/*-----------引入檔案區--------------*/
$xoopsOption['template_main'] = 'tad_web_adm_schedule.tpl';
require_once __DIR__ . '/header.php';
require_once dirname(__DIR__) . '/function.php';
require_once dirname(__DIR__) . '/class/WebCate.php';
/*-----------function區--------------*/

function schedule_template()
{
    global $xoopsDB, $xoopsTpl, $xoopsModuleConfig;

    Utility::mk_dir(XOOPS_ROOT_PATH . '/uploads/tad_web/schedule');
    Utility::mk_dir(XOOPS_ROOT_PATH . '/uploads/tad_web/schedule/image');
    Utility::mk_dir(XOOPS_ROOT_PATH . '/uploads/tad_web/schedule/file');
    $schedule_template = $xoopsModuleConfig['schedule_template'];

    $CkEditor = new CkEditor('tad_web/schedule', 'schedule_template', $schedule_template);
    $CkEditor->setHeight(300);
    $CkEditor->setContentCss(XOOPS_URL . '/modules/tad_web/plugins/schedule/schedule.css');
    $schedule_template = $CkEditor->render();
    $xoopsTpl->assign('schedule_template', $schedule_template);
    $xoopsTpl->assign('schedule_subjects', $xoopsModuleConfig['schedule_subjects']);
}

function save_schedule_template()
{
    global $xoopsModule, $xoopsDB;
    $conf_modid = $xoopsModule->getVar('mid');

    $conf_value = $xoopsDB->escape($_POST['schedule_template']);

    $sql = 'update ' . $xoopsDB->prefix('config') . " set conf_value='$conf_value' where conf_modid='$conf_modid' and conf_name='schedule_template'";
    $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);
}

function save_schedule_subjects()
{
    global $xoopsModule, $xoopsDB;
    $conf_modid = $xoopsModule->getVar('mid');

    $conf_value = $xoopsDB->escape($_POST['schedule_subjects']);

    $sql = 'update ' . $xoopsDB->prefix('config') . " set conf_value='$conf_value' where conf_modid='$conf_modid' and conf_name='schedule_subjects'";
    $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);
}
/*-----------執行動作判斷區----------*/
$op = Request::getString('op');
$WebID = Request::getInt('WebID');
$CateID = Request::getInt('CateID');

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
require_once __DIR__ . '/footer.php';
