<?php
function list_schedule($WebID)
{

    global $xoopsDB, $xoopsTpl, $TadUpFiles;
    if (empty($WebID)) {
        retuen;
    }
    include_once "class.php";

    $tad_web_schedule = new tad_web_schedule($WebID);
    $tad_web_schedule->list_all();
}
