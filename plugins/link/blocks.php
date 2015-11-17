<?php
function list_link($WebID, $config = array())
{

    global $xoopsDB, $xoopsTpl, $TadUpFiles;
    if (empty($WebID)) {
        retuen;
    }
    include_once "class.php";

    $tad_web_link = new tad_web_link($WebID);

    $block = $tad_web_link->list_all("", $config['limit'], 'return');
    return $block;
}
