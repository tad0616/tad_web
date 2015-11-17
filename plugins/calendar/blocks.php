<?php
function list_calendar($WebID, $config = array())
{

    global $xoopsDB, $xoopsTpl, $TadUpFiles;
    if (empty($WebID)) {
        retuen;
    }
    include_once "class.php";

    $tad_web_calendar = new tad_web_calendar($WebID);

    $block = $tad_web_calendar->list_all("", $config['limit'], 'return');
    return $block;
}
