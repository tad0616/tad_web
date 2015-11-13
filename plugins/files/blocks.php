<?php
function list_files($WebID)
{

    global $xoopsDB, $xoopsTpl, $TadUpFiles;
    if (empty($WebID)) {
        retuen;
    }
    include_once "class.php";

    $tad_web_files = new tad_web_files($WebID);
    $tad_web_files->list_all();
}
