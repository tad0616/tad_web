<?php
function list_files($WebID, $config = [])
{
    global $xoopsDB, $xoopsTpl, $TadUpFiles;
    if (empty($WebID)) {
        return;
    }
    require_once __DIR__ . '/class.php';

    $tad_web_files = new tad_web_files($WebID);

    $block = $tad_web_files->list_all('', $config['limit'], 'return');

    return $block;
}
