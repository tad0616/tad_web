<?php
//我的選單
function my_menu($WebID, $config = array())
{
    global $xoopsDB, $xoopsUser, $MyWebID, $xoopsModuleConfig;
    include_once XOOPS_ROOT_PATH . '/modules/tad_web/function_block.php';
    if (!$xoopsUser) {
        $block['main_data'] = false;
        return $block;
    }

    $block['main_data'] = true;
    $MyWebID            = MyWebID();
    $DefWebID           = isset($_REQUEST['WebID']) ? intval($_REQUEST['WebID']) : '';
    $block['DefWebID']  = $DefWebID;

    $uid = $xoopsUser->uid();

    $AllMyWebID = implode("','", $MyWebID);

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
    $block['back_home']   = empty($defaltWebName) ? _MD_TCW_HOME : sprintf(_MD_TCW_TO_MY_WEB, $defaltWebName);
    $block['defaltWebID'] = $defaltWebID;

    define('_SHOW_UNABLE', '1');
    $file = XOOPS_ROOT_PATH . "/uploads/tad_web/{$defaltWebID}/menu_var.php";
    if (file_exists($file)) {
        include $file;
        $block['plugins'] = $menu_var;
    }

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

    return $block;
}

//以流水號秀出某筆tad_web_mems資料內容
function login($WebID, $config = array())
{
    global $xoopsUser, $xoopsConfig;
    if ($xoopsUser) {
        $block['main_data'] = false;
        return $block;
    }
    $block['main_data'] = true;

    if (!empty($_SESSION['LoginMemID'])) {
        $block['op']               = 'mem';
        $block['LoginMemID']       = $_SESSION['LoginMemID'];
        $block['LoginMemName']     = $_SESSION['LoginMemName'];
        $block['LoginMemNickName'] = $_SESSION['LoginMemNickName'];
        $block['LoginWebID']       = $_SESSION['LoginWebID'];
        $block['say_hi']           = sprintf(_MB_TCW_HI, $_SESSION['LoginMemName']);

        return $block;
    } else {

        $modhandler     = &xoops_gethandler('module');
        $config_handler = &xoops_gethandler('config');

        $TadLoginXoopsModule = &$modhandler->getByDirname("tad_login");
        if ($TadLoginXoopsModule) {
            include_once XOOPS_ROOT_PATH . "/modules/tad_login/function.php";
            include_once XOOPS_ROOT_PATH . "/modules/tad_login/language/{$xoopsConfig['language']}/county.php";
            $tad_login['facebook'] = facebook_login('return');
            $tad_login['google']   = google_login('return');

            $config_handler = &xoops_gethandler('config');
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

function search($WebID, $config = array())
{
    $block['main_data'] = true;
    return $block;
}

function qrcode($WebID, $config = array())
{
    $block['main_data'] = urlencode("http://" . $_SERVER["SERVER_NAME"] . $_SERVER['REQUEST_URI']);
    return $block;
}

function web_list($WebID, $config = array())
{
    global $xoopsDB;
    $block['DefWebID'] = $DefWebID = $WebID;

    $sql    = "select * from " . $xoopsDB->prefix("tad_web") . " where WebEnable='1' order by CateID,WebSort";
    $result = $xoopsDB->query($sql) or web_error($sql);
    $i      = 0;
    while ($all = $xoopsDB->fetchArray($result)) {
        foreach ($all as $k => $v) {
            $$k = $v;
        }

        $block['webs'][$i]['WebID'] = $WebID;
        $block['webs'][$i]['title'] = $WebTitle;
        $block['webs'][$i]['name']  = $WebName;
        $block['webs'][$i]['url']   = preg_match('/modules\/tad_web/', $_SERVER['PHP_SELF']) ? $_SERVER['PHP_SELF'] . "?WebID={$WebID}" : XOOPS_URL . "/modules/tad_web/index.php?WebID={$WebID}";

        $i++;
    }
    $block['main_data'] = true;
    return $block;
}
