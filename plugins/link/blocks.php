<?php
function list_link($WebID)
{

    global $xoopsDB, $xoopsTpl, $TadUpFiles;
    if (empty($WebID)) {
        retuen;
    }
    include_once "class.php";

    $tad_web_link = new tad_web_link($WebID);
    $tad_web_link->list_all();
}
