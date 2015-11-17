<?php
function list_action($WebID, $config = array())
{

    global $xoopsDB, $xoopsTpl, $TadUpFiles;
    if (empty($WebID)) {
        retuen;
    }
    include_once "class.php";

    $tad_web_action = new tad_web_action($WebID);

    $block = $tad_web_action->list_all("", $config['limit'], 'return');
    return $block;
}
