<?php
//選項剪影
function list_menu($WebID, $config = [])
{
    global $xoopsDB, $xoopsTpl, $TadUpFiles;
    if (empty($WebID)) {
        return;
    }
    include_once 'class.php';

    $tad_web_menu = new tad_web_menu($WebID);

    $block = $tad_web_menu->list_all('', $config['limit'], 'return');

    return $block;
}
