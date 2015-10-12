<?php
//區塊主函式 (班級選單(tad_web_menu))
function tad_web_menu($options)
{
    global $xoopsUser, $xoopsDB, $MyWebs;

    $DefWebID          = isset($_REQUEST['WebID']) ? intval($_REQUEST['WebID']) : '';
    $block['DefWebID'] = $DefWebID;

    if ($xoopsUser) {
        $uid = $xoopsUser->uid();
    } else {
        if (!empty($DefWebID)) {
            $block['row']          = 'row';
            $block['span']         = 'col-md-';
            $block['form_group']   = 'form-group';
            $block['form_control'] = 'form-control';
            $block['mini']         = 'form-xs';
        } else {
            $block['row']          = $_SESSION['web_bootstrap'] == '3' ? 'row' : 'row-fluid';
            $block['span']         = $_SESSION['web_bootstrap'] == '3' ? 'col-md-' : 'span';
            $block['form_group']   = $_SESSION['web_bootstrap'] == '3' ? 'form-group' : 'control-group';
            $block['form_control'] = $_SESSION['web_bootstrap'] == '3' ? 'form-control' : 'span12';
            $block['mini']         = $_SESSION['web_bootstrap'] == '3' ? 'xs' : 'mini';
        }

        $block['op'] = 'login';
        return $block;
    }

    $sql = "select * from " . $xoopsDB->prefix("tad_web") . " where WebOwnerUid='$uid' order by WebSort";

    $result = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'], 3, mysql_error());
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

    $block['row']  = $_SESSION['web_bootstrap'] == '3' ? 'row' : 'row-fluid';
    $block['span'] = $_SESSION['web_bootstrap'] == '3' ? 'col-md-' : 'span';

    $file = XOOPS_ROOT_PATH . "/uploads/tad_web/{$defaltWebID}/menu_var.php";
    if (file_exists($file)) {
        include $file;
        $block['plugins'] = $menu_var;
    }
    return $block;
}
