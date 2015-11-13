<?php
function list_news($WebID)
{

    global $xoopsDB, $xoopsTpl, $TadUpFiles;
    if (empty($WebID)) {
        retuen;
    }
    include_once "class.php";

    $tad_web_news = new tad_web_news($WebID);
    $tad_web_news->list_all();
}
