<?php
function list_discuss($WebID, $config = [])
{
    global $xoopsDB, $xoopsTpl, $TadUpFiles;
    if (empty($WebID)) {
        retuen;
    }
    include_once 'class.php';

    $tad_web_discuss = new tad_web_discuss($WebID);

    $block = $tad_web_discuss->list_all('', $config['limit'], 'return');

    return $block;
}
