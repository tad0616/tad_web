<?php

//區塊主函式 (相簿(tad_web_news))
function tad_web_news()
{
    global $xoopsDB;
    require_once XOOPS_ROOT_PATH . '/modules/tad_web/plugins/news/class.php';

    return $block;
}
