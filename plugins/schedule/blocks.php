<?php
function list_schedule($WebID, $config = [])
{
    global $xoopsDB, $xoopsTpl, $TadUpFiles;
    if (empty($WebID)) {
        return;
    }
    include_once 'class.php';

    $tad_web_schedule = new tad_web_schedule($WebID);
    $limit = isset($config['limit']) ? $config['limit'] : '';
    $block = $tad_web_schedule->list_all('', $limit, 'return');

    return $block;
}
