<?php
function list_link($WebID, $config = [])
{
    global $xoopsDB, $xoopsTpl, $TadUpFiles;
    if (empty($WebID)) {
        return;
    }
    include_once 'class.php';

    $tad_web_link = new tad_web_link($WebID);

    $limit = isset($config['limit']) ? $config['limit'] : '';
    $hide_link = isset($config['hide_link']) ? $config['hide_link'] : '';
    $hide_desc = isset($config['hide_desc']) ? $config['hide_desc'] : '';

    $block = $tad_web_link->list_all('', $limit, 'return', '', $hide_link, $hide_desc);

    return $block;
}
