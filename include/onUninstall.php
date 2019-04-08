<?php
function xoops_module_uninstall_tad_web(&$module)
{
    global $xoopsDB;
    uninstall_sql();
    $date = date("Ymd");
    rename(XOOPS_ROOT_PATH . "/uploads/tad_web", XOOPS_ROOT_PATH . "/uploads/tad_web_bak_{$date}");

    return true;
}

//擷取網站網址、名稱、站長信箱、多人網頁版本、子網站數等資訊以供統計或日後更新通知
function add_log($status)
{
    global $xoopsConfig, $xoopsDB;
    include_once XOOPS_ROOT_PATH . '/modules/tadtools/tad_function.php';
    $modhandler  = xoops_getHandler('module');
    $xoopsModule = $modhandler->getByDirname("tad_web");
    $version     = $xoopsModule->version();
    if ($status == 'install') {
        $web_amount = 0;
    } else {
<<<<<<< HEAD
        $sql        = "SELECT * FROM " . $xoopsDB->prefix("tad_web") . " WHERE `WebEnable`='1' ORDER BY WebSort";
        $result     = $xoopsDB->query($sql) or web_error($sql, __FILE__, __LINE__);
=======
        $sql = "SELECT * FROM " . $xoopsDB->prefix("tad_web") . " WHERE `WebEnable`='1' ORDER BY WebSort";
        $result = $xoopsDB->query($sql) or web_error($sql);
>>>>>>> 826dbd105d48639c01fd80ed38edf4d75ec4d744
        $web_amount = $xoopsDB->getRowsNum($result);
    }
    $sitename      = urlencode($xoopsConfig['sitename']);
    $add_count_url = "http://120.115.2.99/modules/apply/status.php?url=" . XOOPS_URL . "&web_name={$sitename}&version={$version}&web_amount={$web_amount}&email={$xoopsConfig['adminmail']}&status={$status}";
    if (function_exists('curl_init')) {
        $ch      = curl_init();
        $timeout = 5;
        curl_setopt($ch, CURLOPT_URL, $add_count_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        curl_exec($ch);
        curl_close($ch);
    } elseif (function_exists('file_get_contents')) {
        file_get_contents($add_count_url);
    } else {
        $handle = fopen($add_count_url, "rb");
        stream_get_contents($handle);
        fclose($handle);
    }
}

function uninstall_sql()
{
    global $xoopsDB;
    include XOOPS_ROOT_PATH . '/modules/tad_web/function.php';
    $dir_plugins = get_dir_plugins();
    foreach ($dir_plugins as $dirname) {
        include XOOPS_ROOT_PATH . "/modules/tad_web/plugins/{$dirname}/config.php";
        if (!empty($pluginConfig['sql'])) {
            foreach ($pluginConfig['sql'] as $sql_name) {
                $sql = "DROP TABLE " . $xoopsDB->prefix($sql_name);
                $xoopsDB->queryF($sql);
            }
        }
    }
    return true;
}
