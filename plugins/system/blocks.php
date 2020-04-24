<?php
use XoopsModules\Tadtools\ColorBox;
use XoopsModules\Tadtools\Utility;
use XoopsModules\Tad_web\Tags;

function search($WebID, $config = [])
{
    $block['main_data'] = true;

    return $block;
}

function qrcode($WebID, $config = [])
{
    $http = 'http://';
    if (!empty($_SERVER['HTTPS'])) {
        $http = ($_SERVER['HTTPS'] === 'on') ? 'https://' : 'http://';
    }

    if (!empty($_SERVER['HTTP_X_FORWARDED_PROTO'])) {
        $http = $_SERVER['HTTP_X_FORWARDED_PROTO'] . '://';
    }
    $block['main_data'] = urlencode($http . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI']);

    return $block;
}

function web_list($WebID, $config = [])
{
    global $xoopsDB;
    $block['DefWebID'] = $DefWebID = $WebID;

    $sql = 'SELECT * FROM ' . $xoopsDB->prefix('tad_web') . " WHERE WebEnable='1' ORDER BY CateID,WebSort";
    $result = $xoopsDB->query($sql) or Utility::web_error($sql, __FILE__, __LINE__);
    $i = 0;
    while (false !== ($all = $xoopsDB->fetchArray($result))) {
        foreach ($all as $k => $v) {
            $$k = $v;
        }

        $block['webs'][$i]['WebID'] = $WebID;
        $block['webs'][$i]['title'] = $WebTitle;
        $block['webs'][$i]['name'] = $WebName;
        $block['webs'][$i]['url'] = preg_match('/modules\/tad_web/', $_SERVER['PHP_SELF']) ? $_SERVER['PHP_SELF'] . "?WebID={$WebID}" : XOOPS_URL . "/modules/tad_web/index.php?WebID={$WebID}";

        $i++;
    }
    $block['main_data'] = true;

    return $block;
}

//按讚工具
function rrssb($WebID, $config = [])
{
    $block['main_data'] = Utility::push_url();

    return $block;
}

//萌典查生字
function moedict($WebID, $config = [])
{

    $ColorBox = new ColorBox('#get_moedict');
    $ColorBox->render();
    $block['main_data'] = true;

    return $block;
}

//Dr.eye 英文字典
function dreye($WebID, $config = [])
{

    $ColorBox = new ColorBox('#get_dreyedict', '640');
    $ColorBox->render();
    $block['main_data'] = true;

    return $block;
}

//WIKI維基百科查詢
function wiki($WebID, $config = [])
{
    $ColorBox = new ColorBox('#get_wiki');
    $ColorBox->render();
    $block['main_data'] = true;

    return $block;
}

//即時細懸浮微粒預測
function pm25($WebID, $config = [])
{
    $block['main_data'] = true;
    $block['config'] = $config;

    return $block;
}

//空氣品質 (PSI) 預報
function psi($WebID, $config = [])
{
    $block['main_data'] = true;
    $block['config'] = $config;

    return $block;
}

//聊天室
function tlkio($WebID, $config = [])
{
    $block['main_data'] = true;
    $block['config'] = $config;
    $block['WebID'] = $WebID;

    return $block;
}

//倒數計時
function countdown($WebID, $config = [])
{
    $block['main_data'] = true;
    $block['config'] = $config;
    $block['randStr'] = Utility::randStr(4);

    return $block;
}

//相簿崁入
// function flickrit($WebID, $config = array())
// {
//     $block['main_data'] = true;
//     $block['config']    = $config;
//     return $block;
// }

//標籤
function tags($WebID, $config = [])
{
    $tags = new Tags($WebID);
    $tags_arr = $tags->get_tags();
    arsort($tags_arr);
    $block['main_data'] = true;
    $block['config'] = $config;
    $block['tags_arr'] = $tags_arr;

    return $block;
}
