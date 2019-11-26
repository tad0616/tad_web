<?php
use XoopsModules\Tadtools\FancyBox;
use XoopsModules\Tadtools\TadUpFiles;
use XoopsModules\Tadtools\Utility;

/*-----------引入檔案區--------------*/
require_once __DIR__ . '/header.php';
if (!empty($_REQUEST['WebID']) and $isMyWeb) {
    $GLOBALS['xoopsOption']['template_main'] = 'tad_web_plugin_setup.tpl';
} else {
    redirect_header("index.php?WebID={$_GET['WebID']}", 3, _MD_TCW_NOT_OWNER .'<br>' . __FILE__ . ' : ' . __LINE__);
}

require_once XOOPS_ROOT_PATH . '/header.php';
/*-----------function區--------------*/

//外掛設定功能
function plugin_setup($WebID, $plugin)
{
    global $xoopsTpl, $isMyWeb, $MyWebs, $xoopsUser, $xoopsConfig;

    if (!$isMyWeb and $MyWebs) {
        redirect_header($_SERVER['PHP_SELF'] . "?op=WebID={$MyWebs[0]}&op=setup", 3, _MD_TCW_AUTO_TO_HOME);
    } elseif (!$xoopsUser or empty($WebID) or empty($MyWebs)) {
        redirect_header("index.php?WebID={$WebID}", 3, _MD_TCW_NOT_OWNER .'<br>' . __FILE__ . ' : ' . __LINE__);
    }

    $myts = \MyTextSanitizer::getInstance();
    $pluginSetup = [];
    $setup_file = XOOPS_ROOT_PATH . "/modules/tad_web/plugins/{$plugin}/setup.php";
    if (file_exists($setup_file)) {
        require XOOPS_ROOT_PATH . "/modules/tad_web/plugins/{$plugin}/langs/{$xoopsConfig['language']}.php";
        require $setup_file;
    }
    $setup_db_values = get_plugin_setup_values($WebID, $plugin);
    // if (isset($_GET['test'])) {
    //     die(var_export($setup_db_values));
    // }
    $TadUpFiles_plugin_setup = TadUpFiles_plugin_setup($WebID, $plugin);
    foreach ($plugin_setup as $k => $setup) {
        $value = $setup_db_values[$setup['name']];
        // if (isset($_GET['test']) and $setup['name'] == 'mem_column') {
        //     die(var_export($value));
        // }

        $pluginSetup[$k]['name'] = $setup['name'];
        $pluginSetup[$k]['text'] = $setup['text'];
        $pluginSetup[$k]['desc'] = $setup['desc'];
        $pluginSetup[$k]['type'] = $setup['type'];
        $pluginSetup[$k]['value'] = $value;
        $pluginSetup[$k]['default'] = $setup['default'];
        $pluginSetup[$k]['options'] = $setup['options'];

        if ('file' === $setup['type']) {
            import_img($setup['default'], "{$plugin}_{$setup['name']}", $WebID, '');
            $TadUpFiles_plugin_setup->set_col("{$plugin}_{$setup['name']}", $WebID);
            $pluginSetup[$k]['form'] = $TadUpFiles_plugin_setup->upform(false, "{$plugin}_{$setup['name']}", null, false);
            $pluginSetup[$k]['list'] = $TadUpFiles_plugin_setup->get_file_for_smarty();
        } elseif ('checkbox' === $setup['type']) {
            if (is_array($pluginSetup[$k]['value'])) {
                $pluginSetup[$k]['value'] = $value;
            } else {
                $pluginSetup[$k]['value'] = explode(',', $pluginSetup[$k]['value']);
            }
        }
    }
    $xoopsTpl->assign('plugin_setup', $pluginSetup);
    $xoopsTpl->assign('plugin', $plugin);
    $xoopsTpl->assign('WebID', $WebID);
    // if (isset($_GET['test'])) {
    //     die(var_export(get_db_plugin($WebID, $plugin)));
    // }

    $xoopsTpl->assign('plugin_arr', get_db_plugin($WebID, $plugin));
}

//儲存額外設定值
function save_plugin_setup($WebID = '', $plugin = '')
{
    global $xoopsDB, $xoopsConfig;

    if (file_exists(XOOPS_ROOT_PATH . "/modules/tad_web/plugins/{$plugin}/setup.php")) {
        require XOOPS_ROOT_PATH . "/modules/tad_web/plugins/{$plugin}/langs/{$xoopsConfig['language']}.php";
        require XOOPS_ROOT_PATH . "/modules/tad_web/plugins/{$plugin}/setup.php";

        $myts = \MyTextSanitizer::getInstance();
        foreach ($plugin_setup as $k => $setup) {
            $name = $setup['name'];
            if ('checkbox' === $setup['type']) {
                $value = isset($_POST[$name]) ? $myts->addSlashes(implode(',', $_POST[$name])) : implode(',', $setup['default']);
            } else {
                $value = isset($_POST[$name]) ? $myts->addSlashes($_POST[$name]) : $setup['default'];
            }

            $sql = 'replace into ' . $xoopsDB->prefix('tad_web_plugins_setup') . " (`WebID`, `plugin`, `name`, `type`, `value`) values($WebID, '{$plugin}','{$setup['name']}' , '{$setup['type']}' , '{$value}')";
            $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);
        }
    }
}

function TadUpFiles_plugin_setup($WebID, $plugin)
{
    global $xoopsConfig;

    $TadUpFiles_plugin_setup = new TadUpFiles('tad_web', "/{$WebID}/{$plugin}", null, '', '/thumbs');

    $TadUpFiles_plugin_setup->set_thumb('100px', '60px', '#000', 'center center', 'no-repeat', 'contain');

    return $TadUpFiles_plugin_setup;
}

//該外掛區塊設定
function plugin_block_setup($WebID, $plugin)
{
    global $xoopsTpl, $BlockPositionTitle;
    // $config_blocks_file = XOOPS_ROOT_PATH . "/modules/tad_web/plugins/{$plugin}/config_blocks.php";
    // if (file_exists($config_blocks_file)) {
    //     require_once XOOPS_ROOT_PATH . "/modules/tad_web/plugins/{$plugin}/langs/{$xoopsConfig['language']}.php";
    //     include $config_blocks_file;

    //     $xoopsTpl->assign('plugin_blocks', $blockConfig[$plugin]);
    // }
    $web_install_blocks = get_web_blocks($WebID, $plugin, null);
    $xoopsTpl->assign('web_install_blocks', $web_install_blocks);
    $xoopsTpl->assign('BlockPositionTitle', $BlockPositionTitle);

    $FancyBox = new FancyBox('.edit_block', '640px');
    $FancyBox->render(false);
}
/*-----------執行動作判斷區----------*/
require_once $GLOBALS['xoops']->path('/modules/system/include/functions.php');
$op = system_CleanVars($_REQUEST, 'op', '', 'string');
$WebID = system_CleanVars($_REQUEST, 'WebID', 0, 'int');
$plugin = system_CleanVars($_REQUEST, 'plugin', '', 'string');

common_template($WebID, $web_all_config);

switch ($op) {
    //新增資料
    case 'save_plugin_setup':
        save_plugin_setup($WebID, $plugin);
        header("location: {$plugin}.php?WebID={$WebID}");
        exit;
        break;
    //預設動作
    default:
        plugin_setup($WebID, $plugin);
        plugin_block_setup($WebID, $plugin);

        break;
}

/*-----------秀出結果區--------------*/
require_once __DIR__ . '/footer.php';
require_once XOOPS_ROOT_PATH . '/footer.php';
