<?php
/*-----------引入檔案區--------------*/
$xoopsOption['template_main'] = "tad_web_adm_setup.html";
include_once 'header.php';
include_once "../function.php";
/*-----------function區--------------*/
//tad_web_setup編輯表單
function tad_web_setup_form()
{
    global $xoopsDB, $xoopsTpl, $isAdmin;

    $plugins = get_plugins();
    die(var_export($plugins));
    $web_setup_show_arr = get_web_config("web_setup_show_arr", 0);
    $web_setup_hide_arr = get_web_config("web_setup_hide_arr", 0);

    $i = 0;

    if (empty($web_setup_show_arr)) {
        $repository = $plugin_arr = '';
        foreach ($plugins as $plugin) {
            $destination[$i]['dirname'] = $plugin['dirname'];
            $destination[$i]['title']   = $plugin['config']['name'];
            $plugin_arr[]               = $plugin['dirname'];
            // $repository[$i]['dirname'] = $plugin['dirname'];
            // $repository[$i]['title']   = $plugin['config']['name'];
            $i++;
        }
        $web_setup_show_arr = implode($plugin_arr);
    } else {
        $plugin_arr = explode(',', $web_setup_show_arr);

    }

    $xoopsTpl->assign('web_setup_show_arr', $web_setup_show_arr);
    $xoopsTpl->assign('web_setup_hide_arr', $web_setup_hide_arr);
    $xoopsTpl->assign('destination', $destination);
    $xoopsTpl->assign('repository', $repository);
}

//新增資料到tad_web_setup中
function save_tad_web_setup()
{
    save_web_config("web_setup_show_arr", $web_setup_show_arr);
    save_web_config("web_setup_hide_arr", $web_setup_hide_arr);
    return;
}

/*-----------執行動作判斷區----------*/
$op = (!isset($_REQUEST['op'])) ? "" : $_REQUEST['op'];

switch ($op) {
    /*---判斷動作請貼在下方---*/

    //新增資料
    case "save_tad_web_setup":
        save_tad_web_setup();
        header("location: {$_SERVER['PHP_SELF']}");
        exit;
        break;

    //預設動作
    default:
        tad_web_setup_form();
        break;

        /*---判斷動作請貼在上方---*/
}

/*-----------秀出結果區--------------*/
include_once 'footer.php';
