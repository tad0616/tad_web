<?php
function list_homework($WebID, $config = [])
{
    global $xoopsDB, $xoopsTpl, $TadUpFiles;
    if (empty($WebID)) {
        return;
    }
    require_once __DIR__ . '/class.php';
    $tad_web_homework = new tad_web_homework($WebID);

    $block = $tad_web_homework->list_all('', $config['limit'], 'return');

    return $block;
}
