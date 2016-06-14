<?php
//區塊主函式 (班級選單(tad_web_menu))
function tad_web_menu($options)
{
    global $xoopsUser, $xoopsDB, $MyWebs, $xoopsConfig;
    include_once XOOPS_ROOT_PATH . '/modules/tad_web/function_block.php';
    $MyWebID           = MyWebID(1);
    $DefWebID          = isset($_REQUEST['WebID']) ? intval($_REQUEST['WebID']) : '';
    $block['DefWebID'] = $DefWebID;

    if ($xoopsUser) {
        $uid = $xoopsUser->uid();

        $AllMyWebID = implode("','", $MyWebID);
        if ($MyWebID) {
            $sql = "select * from " . $xoopsDB->prefix("tad_web") . " where WebID in ('{$AllMyWebID}') order by WebSort";
            //die($sql);
            $result = $xoopsDB->query($sql) or web_error($sql);
            //$web_num = $xoopsDB->getRowsNum($result);
            $i = 0;

            $defaltWebID = 0;
            while ($all = $xoopsDB->fetchArray($result)) {
                foreach ($all as $k => $v) {
                    $$k = $v;
                }
                if (!empty($DefWebID) and $WebID == $DefWebID) {
                    $defaltWebID    = $WebID;
                    $defaltWebTitle = $WebTitle;
                    $defaltWebName  = $WebName;
                } elseif (empty($defaltWebID)) {
                    $defaltWebID    = $WebID;
                    $defaltWebTitle = $WebTitle;
                    $defaltWebName  = $WebName;
                }

                $block['webs'][$i]['title'] = $WebTitle;
                $block['webs'][$i]['WebID'] = $WebID;
                $block['webs'][$i]['name']  = $WebName;
                $block['webs'][$i]['url']   = preg_match('/modules\/tad_web/', $_SERVER['PHP_SELF']) ? $_SERVER['PHP_SELF'] . "?WebID={$WebID}" : XOOPS_URL . "/modules/tad_web/index.php?WebID={$WebID}";

                $i++;
            }

            $block['web_num']     = $i;
            $block['WebTitle']    = $defaltWebTitle;
            $block['back_home']   = empty($defaltWebName) ? _MB_TCW_HOME : sprintf(_MB_TCW_TO_MY_WEB, $defaltWebName);
            $block['defaltWebID'] = $defaltWebID;

            if (!defined('_SHOW_UNABLE')) {
                define('_SHOW_UNABLE', '1');
            }
            $file = XOOPS_ROOT_PATH . "/uploads/tad_web/{$defaltWebID}/menu_var.php";
            if (file_exists($file)) {
                include $file;
                $block['plugins'] = $menu_var;
            }

            $modhandler        = xoops_gethandler('module');
            $xoopsModule       = &$modhandler->getByDirname("tad_web");
            $config_handler    = xoops_gethandler('config');
            $xoopsModuleConfig = &$config_handler->getConfigsByCat(0, $xoopsModule->getVar('mid'));

            $quota = empty($xoopsModuleConfig['user_space_quota']) ? 1 : intval($xoopsModuleConfig['user_space_quota']);
            $size  = get_web_config("used_size", $defaltWebID);

            $percentage     = round($size / $quota, 2) * 100;
            $block['quota'] = $percentage;
            if ($percentage <= 70) {
                $block['progress_color'] = 'success';
            } elseif ($percentage <= 90) {
                $block['progress_color'] = 'warning';
            } elseif ($percentage > 90) {
                $block['progress_color'] = 'danger';
            }
        }
        //已關閉網站
        $MyClosedWebID    = MyWebID('0');
        $AllMyClosedWebID = implode("','", $MyClosedWebID);
        if ($MyClosedWebID) {
            $sql    = "select * from " . $xoopsDB->prefix("tad_web") . " where WebID in ('{$AllMyClosedWebID}') order by WebSort";
            $result = $xoopsDB->query($sql) or web_error($sql);
            $i      = 0;

            while ($all = $xoopsDB->fetchArray($result)) {
                foreach ($all as $k => $v) {
                    $$k = $v;
                }

                $block['closed_webs'][$i]['title'] = $WebTitle;
                $block['closed_webs'][$i]['WebID'] = $WebID;
                $block['closed_webs'][$i]['name']  = $WebName;
                $block['closed_webs'][$i]['url']   = XOOPS_URL . "/modules/tad_web/config.php?WebID={$WebID}&op=enable_my_web";

                $i++;
            }
        }
        return $block;
    } elseif (!empty($_SESSION['LoginMemID'])) {
        $block['op']               = 'mem';
        $block['LoginMemID']       = $_SESSION['LoginMemID'];
        $block['LoginMemName']     = $_SESSION['LoginMemName'];
        $block['LoginMemNickName'] = $_SESSION['LoginMemNickName'];
        $block['LoginWebID']       = $_SESSION['LoginWebID'];
        $block['say_hi']           = sprintf(_MD_TCW_HI, $_SESSION['LoginMemName']);

        return $block;
    } else {

        $modhandler     = xoops_gethandler('module');
        $config_handler = xoops_gethandler('config');

        $TadLoginXoopsModule = &$modhandler->getByDirname("tad_login");
        if ($TadLoginXoopsModule) {
            include_once XOOPS_ROOT_PATH . "/modules/tad_login/function.php";
            include_once XOOPS_ROOT_PATH . "/modules/tad_login/language/{$xoopsConfig['language']}/county.php";
            $tad_login['facebook'] = facebook_login('return');
            $tad_login['google']   = google_login('return');

            $config_handler = xoops_gethandler('config');
            $modConfig      = &$config_handler->getConfigsByCat(0, $TadLoginXoopsModule->getVar('mid'));

            $auth_method = $modConfig['auth_method'];
            $i           = 0;

            foreach ($auth_method as $method) {
                $method_const = "_" . strtoupper($method);
                $loginTitle   = sprintf(_MB_TCW_OPENID_LOGIN, constant($method_const));

                if ($method == "facebook") {
                    $tlogin[$i]['link'] = $tad_login['facebook'];
                } elseif ($method == "google") {
                    $tlogin[$i]['link'] = $tad_login['google'];
                } else {
                    $tlogin[$i]['link'] = XOOPS_URL . "/modules/tad_login/index.php?login&op={$method}";
                }
                $tlogin[$i]['img']  = XOOPS_URL . "/modules/tad_login/images/{$method}.png";
                $tlogin[$i]['text'] = $loginTitle;

                $i++;
            }
            //die(var_export($tlogin));
            $block['tlogin'] = $tlogin;
        }

        $block['op'] = 'login';
        return $block;
    }

}
