<?php
function list_action($WebID)
{

    global $xoopsDB, $xoopsTpl, $TadUpFiles;
    if (empty($WebID)) {
        retuen;
    }
    include_once "class.php";

    $tad_web_action = new tad_web_action($WebID);
    $tad_web_action->list_all();
}
