<?php
function list_news($WebID, $config = array())
{

    global $xoopsDB;
    if (empty($WebID)) {
        retuen;
    }
    include_once "class.php";

    $tad_web_news = new tad_web_news($WebID);

    $block = $tad_web_news->list_all("", $config['limit'], 'return');

    return $block;
}

function news_rss($WebID, $config = array())
{

    global $xoopsDB;

    $block['main_data'] = true;
    $block['WebID']     = $WebID;
    $block['config']    = $config;

    return $block;
}
