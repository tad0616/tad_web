<?php
function list_discuss($WebID)
{

    global $xoopsDB, $xoopsTpl, $TadUpFiles;
    if (empty($WebID)) {
        retuen;
    }
    include_once "class.php";

    $tad_web_discuss = new tad_web_discuss($WebID);
    $tad_web_discuss->list_all();
}
