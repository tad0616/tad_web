<?php

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

//按讚工具
function rrssb($WebID, $config = array())
{
    // $block['main_data'] = urlencode("http://" . $_SERVER["SERVER_NAME"] . $_SERVER['REQUEST_URI']);
    $block['main_data'] = push_url();
    return $block;
}

//萌典查生字
function moedict($WebID, $config = array())
{
    if (!file_exists(XOOPS_ROOT_PATH . "/modules/tadtools/colorbox.php")) {
        redirect_header("index.php", 3, _MA_NEED_TADTOOLS);
    }
    include_once XOOPS_ROOT_PATH . "/modules/tadtools/colorbox.php";
    $colorbox = new colorbox('#get_moedict');
    $colorbox->render();
    $block['main_data'] = true;
    return $block;
}

//Dr.eye 英文字典
function dreye($WebID, $config = array())
{
    if (!file_exists(XOOPS_ROOT_PATH . "/modules/tadtools/colorbox.php")) {
        redirect_header("index.php", 3, _MA_NEED_TADTOOLS);
    }
    include_once XOOPS_ROOT_PATH . "/modules/tadtools/colorbox.php";
    $colorbox = new colorbox('#get_dreyedict', '640');
    $colorbox->render();
    $block['main_data'] = true;
    return $block;
}

//WIKI維基百科查詢
function wiki($WebID, $config = array())
{
    if (!file_exists(XOOPS_ROOT_PATH . "/modules/tadtools/colorbox.php")) {
        redirect_header("index.php", 3, _MA_NEED_TADTOOLS);
    }
    include_once XOOPS_ROOT_PATH . "/modules/tadtools/colorbox.php";
    $colorbox = new colorbox('#get_wiki');
    $colorbox->render();
    $block['main_data'] = true;
    return $block;
}

//即時細懸浮微粒預測
function pm25($WebID, $config = array())
{
    $block['main_data'] = true;
    return $block;
}

//空氣品質 (PSI) 預報
function psi($WebID, $config = array())
{
    $block['main_data'] = true;
    return $block;
}

//聊天室
function tlkio($WebID, $config = array())
{

    $block['main_data'] = true;
    $block['config']    = $config;
    $block['WebID']     = $WebID;
    return $block;
}

//倒數計時
function countdown($WebID, $config = array())
{
    $block['main_data'] = true;
    $block['config']    = $config;
    return $block;
}

//相簿崁入
function flickrit($WebID, $config = array())
{
    $block['main_data'] = true;
    $block['config']    = $config;
    return $block;
}

//標籤
function tags($WebID, $config = array())
{
    $tags     = new tags($WebID);
    $tags_arr = $tags->get_tags();
    arsort($tags_arr);
    $block['main_data'] = true;
    $block['config']    = $config;
    $block['tags_arr']  = $tags_arr;
    return $block;
}
