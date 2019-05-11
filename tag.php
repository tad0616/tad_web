<?php
/*-----------引入檔案區--------------*/
require_once __DIR__ . '/header.php';
$GLOBALS['xoopsOption']['template_main'] = 'tad_web_tag.tpl';
require_once XOOPS_ROOT_PATH . '/header.php';
/*-----------function區--------------*/

//搜尋
function search_tag($WebID = '', $tag = '')
{
    global $xoopsTpl;

    define('_DISPLAY_MODE', 'index');
    $web_plugin_enable_arr = get_web_config('web_plugin_enable_arr', 0);
    if (empty($web_plugin_enable_arr)) {
        $enable_arr = get_dir_plugins();
    } else {
        $enable_arr = explode(',', $web_plugin_enable_arr);
    }

    $data_count = [];

    $plugin_data_total = 0;
    foreach ($enable_arr as $dirname) {
        if (empty($dirname)) {
            continue;
        }
        $pluginConfig = [];
        require_once "plugins/{$dirname}/config.php";
        if (true !== $pluginConfig['tag']) {
            continue;
        }
        require_once "plugins/{$dirname}/class.php";
        $plugin_name = "tad_web_{$dirname}";
        $$plugin_name = new $plugin_name($WebID);
        $data_count[$dirname] = $$plugin_name->list_all('', 50, 'assign', $tag);
        $plugin_data_total += $$plugin_name->get_total();
        $show_arr[] = $dirname;
    }
    $xoopsTpl->assign('data_count', $data_count);
    $xoopsTpl->assign('tag', $tag);
    $xoopsTpl->assign('show_arr', $show_arr);
    $xoopsTpl->assign('plugin_data_total', $plugin_data_total);
}

//標籤
function list_tags($WebID)
{
    global $xoopsTpl;
    // require_once "class/tags.php";
    $tags = new  \XoopsModules\Tad_web\Tags($WebID);
    $tags_arr = $tags->get_tags();
    arsort($tags_arr);
    $xoopsTpl->assign('tags_arr', $tags_arr);
}

/*-----------執行動作判斷區----------*/
require_once $GLOBALS['xoops']->path('/modules/system/include/functions.php');
$op = system_CleanVars($_REQUEST, 'op', '', 'string');
$WebID = system_CleanVars($_REQUEST, 'WebID', 0, 'int');
$tag = system_CleanVars($_REQUEST, 'tag', '', 'string');

common_template($WebID, $web_all_config);

switch ($op) {
    //預設動作
    default:
        if ($tag) {
            search_tag($WebID, $tag);
        } else {
            list_tags($WebID);
        }

        break;
}

/*-----------秀出結果區--------------*/
require_once __DIR__ . '/footer.php';
require_once XOOPS_ROOT_PATH . '/footer.php';
