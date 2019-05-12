<?php

//區塊主函式 (相簿(tad_web_news))
function tad_web_news()
{
    global $xoopsDB;
    require_once __DIR__ . '/plugin/news/class.php';

    return $block;
}
