<?php
//區塊主函式 (班級選單(tad_webs))
function tad_web_list($options)
{
    global $xoopsDB;

    $DefWebID          = isset($_REQUEST['WebID']) ? intval($_REQUEST['WebID']) : '';
    $block['DefWebID'] = $DefWebID;

    $sql    = "select * from " . $xoopsDB->prefix("tad_web") . " where WebEnable='1' order by CateID,WebSort";
    $result = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'], 3, mysql_error());
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
    if (!empty($DefWebID)) {
        $block['row']  = 'row';
        $block['span'] = 'col-md-';
    } else {
        $block['row']  = $_SESSION['web_bootstrap'] == '3' ? 'row' : 'row-fluid';
        $block['span'] = $_SESSION['web_bootstrap'] == '3' ? 'col-md-' : 'span';
    }
    return $block;
}
