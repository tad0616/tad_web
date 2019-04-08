<?php
/*-----------引入檔案區--------------*/
include_once "header.php";
$xoopsOption['template_main'] = 'tad_web_search.tpl';
include_once XOOPS_ROOT_PATH . "/header.php";
/*-----------function區--------------*/

//搜尋
function search_web($WebID = "", $search_keyword = "")
{
    global $xoopsTpl, $menu_var;

    $web_plugin_enable_arr = get_web_config('web_plugin_enable_arr', $WebID);
    $web_plugin_arr        = explode(',', $web_plugin_enable_arr);
    $keyword_arr           = explode(' ', $search_keyword);

    // die(var_export($menu_var));
    foreach ($web_plugin_arr as $plugin) {
        $plugin_name = $menu_var[$plugin]['title'];
        if (file_exists(XOOPS_ROOT_PATH . "/modules/tad_web/plugins/{$plugin}/search.php")) {
            include_once XOOPS_ROOT_PATH . "/modules/tad_web/plugins/{$plugin}/search.php";
            $result[$plugin_name] = call_user_func("{$plugin}_search", $WebID, $keyword_arr, '10');
        }
    }

    $xoopsTpl->assign('result', $result);
    $xoopsTpl->assign('WebID', $WebID);
    $xoopsTpl->assign('search_keyword', $search_keyword);
}

/*-----------執行動作判斷區----------*/
include_once $GLOBALS['xoops']->path('/modules/system/include/functions.php');
$op             = system_CleanVars($_REQUEST, 'op', '', 'string');
$WebID          = system_CleanVars($_REQUEST, 'WebID', 0, 'int');
$search_keyword = system_CleanVars($_REQUEST, 'search_keyword', '', 'string');

common_template($WebID, $web_all_config);

switch ($op) {

    //預設動作
    default:
        search_web($WebID, $search_keyword);
        break;

}

/*-----------秀出結果區--------------*/
include_once 'footer.php';
include_once XOOPS_ROOT_PATH . '/footer.php';
