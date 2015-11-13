<?php
function list_calendar($WebID)
{

    global $xoopsDB, $xoopsTpl, $TadUpFiles;
    if (empty($WebID)) {
        retuen;
    }
    include_once "class.php";

    $tad_web_calendar = new tad_web_calendar($WebID);
    $tad_web_calendar->list_all();
}
