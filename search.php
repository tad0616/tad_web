<?php
use Xmf\Request;
use XoopsModules\Tadtools\Utility;
/*-----------引入檔案區--------------*/
require_once __DIR__ . '/header.php';
$GLOBALS['xoopsOption']['template_main'] = 'tad_web_search.tpl';
require_once XOOPS_ROOT_PATH . '/header.php';
/*-----------function區--------------*/

//搜尋
function search_web($WebID = '', $search_keyword = '')
{
    global $xoopsTpl, $plugin_menu_var, $web_all_config;

    $web_plugin_enable_arr = $web_all_config['web_plugin_enable_arr'];

    $web_plugin_arr = explode(',', $web_plugin_enable_arr);
    $keyword_arr = explode(' ', $search_keyword);

    $all_result = [];
    foreach ($web_plugin_arr as $plugin) {
        $plugin_name = $plugin_menu_var[$plugin]['title'];
        if (file_exists(XOOPS_ROOT_PATH . "/modules/tad_web/plugins/{$plugin}/search.php")) {
            require XOOPS_ROOT_PATH . "/modules/tad_web/plugins/{$plugin}/search.php";
            $all_result[$plugin_name] = call_user_func("{$plugin}_search", $WebID, $keyword_arr, '10');
        }
    }
    // Utility::dd($menu_var);
    $xoopsTpl->assign('all_result', $all_result);
    $xoopsTpl->assign('WebID', $WebID);
    $xoopsTpl->assign('search_keyword', $search_keyword);
}

/*-----------執行動作判斷區----------*/
$op = Request::getString('op');
$WebID = Request::getInt('WebID');
$search_keyword = Request::getString('search_keyword');

common_template($WebID, $web_all_config);

switch ($op) {
    //預設動作
    default:
        search_web($WebID, $search_keyword);
        break;
}

/*-----------秀出結果區--------------*/
require_once __DIR__ . '/footer.php';
require_once XOOPS_ROOT_PATH . '/footer.php';
