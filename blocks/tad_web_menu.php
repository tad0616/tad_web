<?php
use XoopsModules\Tadtools\Utility;
//區塊主函式 (班級選單(tad_web_menu))
function tad_web_menu($options)
{
    global $xoopsUser, $xoopsDB, $MyWebs, $xoopsConfig;
    include_once XOOPS_ROOT_PATH . '/modules/tad_web/function_block.php';
    $MyWebID = MyWebID(1);
    $DefWebID = isset($_REQUEST['WebID']) ? (int) $_REQUEST['WebID'] : '';
    $block['DefWebID'] = $DefWebID;

    if ($xoopsUser) {
        $uid = $xoopsUser->uid();

        $AllMyWebID = implode("','", $MyWebID);
        if ($MyWebID) {
            $sql = 'select * from ' . $xoopsDB->prefix('tad_web') . " where WebID in ('{$AllMyWebID}') order by WebSort";
            //die($sql);
            $result = $xoopsDB->query($sql) or Utility::web_error($sql, __FILE__, __LINE__);
            //$web_num = $xoopsDB->getRowsNum($result);
            $i = $defalt_used_size = 0;

            $defaltWebID = 0;
            while ($all = $xoopsDB->fetchArray($result)) {
                foreach ($all as $k => $v) {
                    $$k = $v;
                }
                if (!empty($DefWebID) and $WebID == $DefWebID) {
                    $defaltWebID = $WebID;
                    $defaltWebTitle = $WebTitle;
                    $defaltWebName = $WebName;
                    $defalt_used_size = $used_size;
                } elseif (empty($defaltWebID)) {
                    $defaltWebID = $WebID;
                    $defaltWebTitle = $WebTitle;
                    $defaltWebName = $WebName;
                }

                $block['webs'][$i]['title'] = $WebTitle;
                $block['webs'][$i]['WebID'] = $WebID;
                $block['webs'][$i]['name'] = $WebName;
                $block['webs'][$i]['url'] = preg_match('/modules\/tad_web/', $_SERVER['PHP_SELF']) ? $_SERVER['PHP_SELF'] . "?WebID={$WebID}" : XOOPS_URL . "/modules/tad_web/index.php?WebID={$WebID}";

                $i++;
            }

            $block['web_num'] = $i;
            $block['WebTitle'] = $defaltWebTitle;
            $block['back_home'] = empty($defaltWebName) ? _MB_TCW_HOME : sprintf(_MB_TCW_TO_MY_WEB, $defaltWebName);
            $block['defaltWebID'] = $defaltWebID;

            if (!defined('_SHOW_UNABLE')) {
                define('_SHOW_UNABLE', '1');
            }
            $file = XOOPS_ROOT_PATH . "/uploads/tad_web/{$defaltWebID}/menu_var.php";
            if (file_exists($file)) {
                include $file;
                $block['plugins'] = $menu_var;
            }

            $modhandler = xoops_getHandler('module');
            $tad_web_Module = $modhandler->getByDirname('tad_web');
            $config_handler = xoops_getHandler('config');
            $xoopsModuleConfig = $config_handler->getConfigsByCat(0, $tad_web_Module->getVar('mid'));

            $quota = empty($xoopsModuleConfig['user_space_quota']) ? 1 : get_web_config('space_quota', $defaltWebID);
            // $block['quota'] = $quota;
            $block['size'] = size2mb($defalt_used_size);
            $percentage = round($block['size'] / $quota, 2) * 100;
            $block['percentage'] = $percentage;
            $block['quota'] = $quota;
            if ($percentage <= 70) {
                $block['progress_color'] = 'success';
            } elseif ($percentage <= 90) {
                $block['progress_color'] = 'warning';
            } elseif ($percentage > 90) {
                $block['progress_color'] = 'danger';
            }
        }
        //已關閉網站
        $MyClosedWebID = MyWebID('0');
        $AllMyClosedWebID = implode("','", $MyClosedWebID);
        if ($MyClosedWebID) {
            $sql = 'select * from ' . $xoopsDB->prefix('tad_web') . " where WebID in ('{$AllMyClosedWebID}') order by WebSort";
            $result = $xoopsDB->query($sql) or Utility::web_error($sql, __FILE__, __LINE__);
            $i = 0;

            while ($all = $xoopsDB->fetchArray($result)) {
                foreach ($all as $k => $v) {
                    $$k = $v;
                }

                $block['closed_webs'][$i]['title'] = $WebTitle;
                $block['closed_webs'][$i]['WebID'] = $WebID;
                $block['closed_webs'][$i]['name'] = $WebName;
                $block['closed_webs'][$i]['url'] = XOOPS_URL . "/modules/tad_web/config.php?WebID={$WebID}&op=enable_my_web";

                $i++;
            }
        }

        return $block;
    } elseif (!empty($_SESSION['LoginMemID'])) {
        $block['op'] = 'mem';
        $block['LoginMemID'] = $_SESSION['LoginMemID'];
        $block['LoginMemName'] = $_SESSION['LoginMemName'];
        $block['LoginMemNickName'] = $_SESSION['LoginMemNickName'];
        $block['LoginWebID'] = $_SESSION['LoginWebID'];
        $block['say_hi'] = sprintf(_MD_TCW_HI, $_SESSION['LoginMemName']);

        return $block;
    }

    $modhandler = xoops_getHandler('module');
    $config_handler = xoops_getHandler('config');

    $TadLoginXoopsModule = $modhandler->getByDirname('tad_login');
    if ($TadLoginXoopsModule) {
        require XOOPS_ROOT_PATH . '/modules/tad_login/function.php';
        require XOOPS_ROOT_PATH . '/modules/tad_login/oidc.php';
        xoops_loadLanguage('county', 'tad_login');
        xoops_loadLanguage('blocks', 'tad_login');

        $config_handler = xoops_getHandler('config');
        $modConfig = $config_handler->getConfigsByCat(0, $TadLoginXoopsModule->getVar('mid'));

        $auth_method = $modConfig['auth_method'];
        $i = 0;

        foreach ($auth_method as $method) {
            // $method_const = '_' . mb_strtoupper($method);
            // $loginTitle = sprintf(_MB_TCW_OPENID_LOGIN, constant($method_const));

            if ('facebook' === $method) {
                $tlogin[$i]['link'] = facebook_login('return');
            } elseif ('google' === $method) {
                $tlogin[$i]['link'] = google_login('return');
            } else {
                $tlogin[$i]['link'] = XOOPS_URL . "/modules/tad_login/index.php?login&op={$method}";
            }

            $tlogin[$i]['img'] = in_array($method, $oidc_array) ? XOOPS_URL . "/modules/tad_login/images/oidc/{$all_oidc[$method]['tail']}.png" : XOOPS_URL . "/modules/tad_login/images/{$method}{$big}.png";
            // $tlogin[$i]['text'] = in_array($method, $oidc_array) ? constant('_' . mb_strtoupper($all_oidc[$method]['tail'])) . ' OIDC ' . _MB_TADLOGIN_LOGIN : constant('_' . mb_strtoupper($method)) . ' OpenID ' . _MB_TADLOGIN_LOGIN;

            if (in_array($method, $oidc_array)) {
                $tlogin[$i]['text'] = constant('_' . mb_strtoupper($all_oidc[$method]['tail'])) . ' OIDC ' . _MB_TADLOGIN_LOGIN;
            } elseif (in_array($method, $oidc_array2)) {
                $tlogin[$i]['text'] = constant('_' . mb_strtoupper($all_oidc[$method]['tail'])) . _MB_TADLOGIN_LOGIN;
            } else {
                $tlogin[$i]['text'] = constant('_' . mb_strtoupper($method)) . ' OpenID ' . _MB_TADLOGIN_LOGIN;
            }

            $i++;
        }
        //die(var_export($tlogin));
        $block['tlogin'] = $tlogin;
    }

    $block['op'] = 'login';

    return $block;
}

if (!function_exists('size2mb')) {
    function size2mb($size)
    {
        $mb = round($size / (1024 * 1024), 0);

        return $mb;
    }
}
