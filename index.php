<?php
use Xmf\Request;
use XoopsModules\Tadtools\Utility;
use XoopsModules\Tad_web\Tools as TadWebTools;
/*-----------引入檔案區--------------*/
require_once __DIR__ . '/header.php';
$GLOBALS['xoopsOption']['template_main'] = 'tad_web_index.tpl';
require_once XOOPS_ROOT_PATH . '/header.php';
Utility::test($_COOKIE, 'cookie', 'dd');

/*-----------執行動作判斷區----------*/
$op = Request::getString('op');
$WebID = Request::getInt('WebID');
$NoticeID = Request::getInt('NoticeID');

common_template($WebID, $web_all_config);

switch ($op) {

    //重新計算空間
    case 'check_quota':
        check_quota($WebID);
        header("location: index.php?WebID={$WebID}");
        exit;

    //重新產生畫面
    case 'clear_block_cache':
        clear_block_cache($WebID);
        header("location: index.php?WebID={$WebID}");
        exit;

    //新增資料
    case 'notice':
        view_notice($NoticeID);
        break;

    //預設動作
    default:
        if (!empty($WebID)) {
            ClassHome($WebID);
            $op = 'ClassHome';
        } else {
            list_all_class();
            $op = 'list_all_class';
        }
}
/*-----------秀出結果區--------------*/
require_once __DIR__ . '/footer.php';
require_once XOOPS_ROOT_PATH . '/footer.php';

/*-----------function區--------------*/

//首頁
function ClassHome($WebID = '')
{
    global $xoopsDB, $xoopsTpl, $MyWebs, $web_all_config;

    $web = get_tad_web($WebID);

    define('_DISPLAY_MODE', 'home');
    $web_plugin_enable_arr = $web_all_config['web_plugin_enable_arr'];
    if (empty($web_plugin_enable_arr)) {
        $show_arr = get_dir_plugins();
    } else {
        $show_arr = explode(',', $web_plugin_enable_arr);
    }

    foreach ($show_arr as $dirname) {
        if (empty($dirname)) {
            continue;
        }
        if (file_exists("plugins/{$dirname}/class.php")) {
            require_once "plugins/{$dirname}/class.php";
            $plugin_name = "tad_web_{$dirname}";
            $$plugin_name = new $plugin_name($WebID);
        }
    }

    $_SESSION['tad_web'][$WebID]['WebCounter']++;
    if (_IS_EZCLASS) {
        redis_do($WebID, 'set', '', 'WebCounter', $_SESSION['tad_web'][$WebID]['WebCounter']);
    } else {
        $sql = 'UPDATE `' . $xoopsDB->prefix('tad_web') . '` SET `WebCounter` = `WebCounter` +1 WHERE `WebID` = ?';
        Utility::query($sql, 'i', [$WebID]);
    }

    $xoopsTpl->assign('MyWebs', $MyWebs);
}

//取得所有班級
function list_all_class()
{
    global $xoopsTpl, $xoopsModuleConfig;

    $xoopsTpl->assign('module_title', $xoopsModuleConfig['module_title']);

    $web_plugin_display_arr = TadWebTools::get_web_config('web_plugin_display_arr', 0);
    if (empty($web_plugin_display_arr)) {
        $show_arr = get_dir_plugins();
    } else {
        $show_arr = explode(',', $web_plugin_display_arr);
    }

    $xoopsTpl->assign('show_arr', $show_arr);
    // $xoopsTpl->assign('display_mode', 'index');
    define('_DISPLAY_MODE', 'index');
    $data_count = [];

    foreach ($show_arr as $dirname) {
        if (empty($dirname)) {
            continue;
        }
        if (file_exists("plugins/{$dirname}/class.php")) {
            require_once "plugins/{$dirname}/class.php";
            $limit = TadWebTools::get_web_config("{$dirname}_limit", 0);
            $plugin_name = "tad_web_{$dirname}";
            $$plugin_name = new $plugin_name(0);
            $data_count[$dirname] = $$plugin_name->list_all('', $limit);
        }
    }
    $xoopsTpl->assign('data_count', $data_count);
    $xoopsTpl->assign('data_count_sum', array_sum($data_count));
}

function view_notice($NoticeID = '')
{
    global $xoopsTpl;
    $xoopsTpl->assign('Notice', get_tad_web_notice($NoticeID));
    $xoopsTpl->assign('theme_display_mode', 'blank');
    $xoopsTpl->assign('blank_kind', 'content');
}
