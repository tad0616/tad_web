<?php
use Xmf\Request;
use XoopsModules\Tadtools\Utility;
use XoopsModules\Tad_login\Tools as TadLoginTools;
use XoopsModules\Tad_web\Tools as TadWebTools;

if (!class_exists('XoopsModules\Tadtools\Utility')) {
    require XOOPS_ROOT_PATH . '/modules/tadtools/preloads/autoloader.php';
}

//區塊主函式 (班級選單(tad_web_menu))
function tad_web_menu($options)
{
    global $xoopsUser, $xoopsDB, $xoTheme;
    $MyWebID = TadWebTools::MyWebID(1);
    $DefWebID = Request::getInt('WebID');

    $block['DefWebID'] = $DefWebID;

    if ($xoopsUser) {

        $AllMyWebID = implode(',', $MyWebID);
        if ($MyWebID) {
            $sql = 'SELECT * FROM `' . $xoopsDB->prefix('tad_web') . '` WHERE `WebID` IN (?) ORDER BY `WebSort`';
            $result = Utility::query($sql, 's', [$AllMyWebID]) or Utility::web_error($sql, __FILE__, __LINE__);

            //$web_num = $xoopsDB->getRowsNum($result);
            $i = $defalt_used_size = 0;

            $defaltWebID = 0;
            while (false !== ($all = $xoopsDB->fetchArray($result))) {
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
            $defaltWebID = (isset($_SESSION['tad_web_adm']) and !empty($_GET['WebID'])) ? $_GET['WebID'] : $defaltWebID;

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

            if (!isset($xoopsModuleConfig)) {
                $TadWebModuleConfig = Utility::getXoopsModuleConfig('tad_login');
            } else {
                $TadWebModuleConfig = $xoopsModuleConfig;
            }

            $quota = empty($TadWebModuleConfig['user_space_quota']) ? 1 : TadWebTools::get_web_config('space_quota', $defaltWebID);

            $block['size'] = size2mb($defalt_used_size);
            $size = $quota > 0 ? (int) $block['size'] / (int) $quota : 0;
            $percentage = round($size, 2) * 100;
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
        $MyClosedWebID = TadWebTools::MyWebID('0');
        $AllMyClosedWebID = implode(',', $MyClosedWebID);
        if ($MyClosedWebID) {
            $sql = 'SELECT * FROM `' . $xoopsDB->prefix('tad_web') . '` WHERE `WebID` IN (?) ORDER BY `WebSort`';
            $result = Utility::query($sql, 's', [$AllMyClosedWebID]) or Utility::web_error($sql, __FILE__, __LINE__);
            $i = 0;

            while (false !== ($all = $xoopsDB->fetchArray($result))) {
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

        $xoTheme->addScript('modules/tad_web/class/bootstrap-progressbar/bootstrap-progressbar.js');
        return $block;
    } elseif (!empty($_SESSION['LoginMemID'])) {
        $block['op'] = 'mem';
        $block['LoginMemID'] = $_SESSION['LoginMemID'];
        $block['LoginMemName'] = $_SESSION['LoginMemName'];
        $block['LoginMemNickName'] = $_SESSION['LoginMemNickName'];
        $block['LoginWebID'] = $_SESSION['LoginWebID'];
        $block['say_hi'] = sprintf(_MD_TCW_HI, $_SESSION['LoginMemName']);

        return $block;
    } else {
        $block['op'] = 'login';
        return $block;
    }

    $TadLoginModuleConfig = Utility::getXoopsModuleConfig('tad_login');

    if ($TadLoginModuleConfig) {
        xoops_loadLanguage('county', 'tad_login');
        xoops_loadLanguage('blocks', 'tad_login');

        $auth_method = $TadLoginModuleConfig['auth_method'];
        $i = 0;
        $oidc_array = array_keys(TadLoginTools::$all_oidc);
        $oidc_array2 = array_keys(TadLoginTools::$all_oidc2);
        foreach ($auth_method as $method) {
            // $method_const = '_' . mb_strtoupper($method);
            // $loginTitle = sprintf(_MB_TCW_OPENID_LOGIN, constant($method_const));

            if ('facebook' === $method) {
                $tlogin[$i]['link'] = TadLoginTools::facebook_login('return');
            } elseif ('line' === $method) {
                $tlogin[$i]['link'] = TadLoginTools::line_login('return');
            } elseif ('google' === $method) {
                $tlogin[$i]['link'] = TadLoginTools::google_login('return');
            } else {
                $tlogin[$i]['link'] = XOOPS_URL . "/modules/tad_login/index.php?login&op={$method}";
            }

            $tlogin[$i]['img'] = in_array($method, $oidc_array) ? XOOPS_URL . "/modules/tad_login/images/oidc/" . TadLoginTools::$all_oidc[$method]['tail'] . ".png" : XOOPS_URL . "/modules/tad_login/images/{$method}{$big}.png";

            if (in_array($method, $oidc_array)) {
                $tlogin[$i]['text'] = constant('_' . mb_strtoupper(TadLoginTools::$all_oidc[$method]['tail'])) . ' OIDC ' . _MB_TADLOGIN_LOGIN;
            } elseif (in_array($method, $oidc_array2)) {
                $tlogin[$i]['text'] = constant('_' . mb_strtoupper(TadLoginTools::$all_oidc[$method]['tail'])) . _MB_TADLOGIN_LOGIN;
            } else {
                $tlogin[$i]['text'] = constant('_' . mb_strtoupper($method)) . ' OpenID ' . _MB_TADLOGIN_LOGIN;
            }

            $i++;
        }

        $block['tlogin'] = $tlogin;
    }

    return $block;
}

if (!function_exists('size2mb')) {
    function size2mb($size)
    {
        $mb = round($size / (1024 * 1024), 0);

        return $mb;
    }
}
