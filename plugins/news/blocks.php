<?php
function list_news($WebID, $config = [])
{
    global $xoopsDB;
    if (empty($WebID)) {
        return;
    }
    include_once 'class.php';

    $tad_web_news = new tad_web_news($WebID);

    $block = $tad_web_news->list_all('', $config['limit'], 'return');

    return $block;
}

function news_rss($WebID, $config = [])
{
    global $xoopsDB;

    $block['main_data'] = true;
    $block['WebID'] = $WebID;
    $block['config'] = $config;

    return $block;
}
