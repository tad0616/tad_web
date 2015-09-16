<?php
//區塊主函式 (班級選單(tad_web_menu))
function tad_web_menu($options)
{
    global $xoopsUser, $xoopsDB, $MyWebs;

    if ($xoopsUser) {
        $uid = $xoopsUser->uid();
    } else {
        if (!empty($_GET['WebID'])) {
            $block['row']          = 'row';
            $block['span']         = 'col-md-';
            $block['form_group']   = 'form-group';
            $block['form_control'] = 'form-control';
        } else {
            $block['row']          = $_SESSION['web_bootstrap'] == '3' ? 'row' : 'row-fluid';
            $block['span']         = $_SESSION['web_bootstrap'] == '3' ? 'col-md-' : 'span';
            $block['form_group']   = $_SESSION['web_bootstrap'] == '3' ? 'form-group' : 'control-group';
            $block['form_control'] = $_SESSION['web_bootstrap'] == '3' ? 'form-control' : 'span12';
        }

        $block['op'] = 'login';
        return $block;
    }

    $sql = "select * from " . $xoopsDB->prefix("tad_web") . " where WebOwnerUid='$uid' order by WebSort";
    //die($sql);
    $result = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'], 3, mysql_error());

    $i = 0;

    $oldWebID    = !empty($_GET['WebID']) ? intval($_GET['WebID']) : 0;
    $defaltWebID = 0;

    while ($all = $xoopsDB->fetchArray($result)) {
        foreach ($all as $k => $v) {
            $$k = $v;
        }
        if (empty($defaltWebID) or $defaltWebID == $WebID) {
            $defaltWebID    = $WebID;
            $defaltWebTitle = $WebTitle;
            $defaltWebName  = $WebName;
        }

        $block['webs'][$i]['title'] = $WebTitle;
        $block['webs'][$i]['WebID'] = $WebID;
        $block['webs'][$i]['name']  = $WebName;
        $block['webs'][$i]['url']   = preg_match('/modules\/tad_web/', $_SERVER['PHP_SELF']) ? $_SERVER['PHP_SELF'] . "?WebID={$WebID}" : XOOPS_URL . "/modules/tad_web/index.php?WebID={$WebID}";

        $WebID_Arr[] = $WebID;
        $i++;
    }

    // if (!empty($oldWebID) and !in_array($oldWebID, $WebID_Arr)) {
    //     $block['op'] = "logout";
    //     return $block;
    // }

    $block['WebTitle'] = $defaltWebTitle;

    $block['my_web']       = mkMenuOpt(sprintf(_MB_TCW_TO_MY_WEB, $defaltWebName), "index.php?WebID={$defaltWebID}", "fa-home");
    $block['news_add']     = mkMenuOpt(_MB_TCW_NEWS_ADD, "news.php?WebID={$defaltWebID}&op=tad_web_news_form", "fa-newspaper-o");
    $block['works_add']    = mkMenuOpt(_MB_TCW_WORKS_ADD, "works.php?WebID={$defaltWebID}&op=tad_web_works_form", "fa-paint-brush");
    $block['homework_add'] = mkMenuOpt(_MB_TCW_HOMEWORK_ADD, "homework.php?WebID={$defaltWebID}&op=tad_web_news_form", "fa-pencil-square-o");
    $block['files_add']    = mkMenuOpt(_MB_TCW_FILES_ADD, "files.php?WebID={$defaltWebID}&op=tad_web_files_form", "fa-upload");
    $block['action_add']   = mkMenuOpt(_MB_TCW_ACTION_ADD, "action.php?WebID={$defaltWebID}&op=tad_web_action_form", "fa-camera");
    $block['class_setup']  = mkMenuOpt(_MB_TCW_WEB_SETUP, "aboutus.php?WebID={$defaltWebID}&op=tad_web_adm", "fa-smile-o");
    $block['video_add']    = mkMenuOpt(_MB_TCW_VIDEO_ADD, "video.php?WebID={$defaltWebID}&op=tad_web_video_form", "fa-film");
    $block['link_add']     = mkMenuOpt(_MB_TCW_LINK_ADD, "link.php?WebID={$defaltWebID}&op=tad_web_link_form", "fa-globe");
    $block['logout']       = mkMenuOpt(_MB_TCW_LOGOUT, "/user.php?op=logout", "fa-sign-out");
    $block['web_config']   = mkMenuOpt(_MB_TCW_WEB_CONFIG, "config.php?WebID={$defaltWebID}", "fa-check-square-o ");

    $block['row']  = $_SESSION['web_bootstrap'] == '3' ? 'row' : 'row-fluid';
    $block['span'] = $_SESSION['web_bootstrap'] == '3' ? 'col-md-' : 'span';

    return $block;
}

function mkMenuOpt($title = "", $url = "", $icon = "icon-volume-up")
{
    if (substr($url, 0, 1) == "/") {
        $path = XOOPS_URL . $url;
    } else {
        $path = XOOPS_URL . "/modules/tad_web/{$url}";
    }

    $opt = "
        <a href='{$path}' class='btn'>
            <i class='fa {$icon}'></i>
            $title
        </a>
    ";
    return $opt;
}
