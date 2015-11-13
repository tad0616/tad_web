<?php
function list_homework($WebID)
{

    global $xoopsDB, $xoopsTpl, $TadUpFiles;
    if (empty($WebID)) {
        retuen;
    }
    include_once "class.php";

    $tad_web_homework = new tad_web_homework($WebID);
    $tad_web_homework->list_all();
}
