<?php
/************** list_work *************/
function list_work($WebID, $config = array())
{

    global $xoopsDB, $xoopsTpl, $TadUpFiles;
    if (empty($WebID)) {
        retuen;
    }
    include_once "class.php";

    $tad_web_works = new tad_web_works($WebID);
    $block         = $tad_web_works->list_all("", $config['limit'], 'return');
    return $block;
}

/************** random_work *************/

function random_work($WebID, $config = array())
{

    global $xoopsDB, $xoopsTpl, $TadUpFiles;
    if (empty($WebID)) {
        retuen;
    }

    include_once "class.php";

    $tad_web_works = new tad_web_works($WebID);
    $block         = $tad_web_works->list_all("", 1, 'return', '', 'order by rand()', $config['limit']);
    return $block;
}

/************** latest_work *************/

function latest_work($WebID, $config = array())
{

    global $xoopsDB, $xoopsTpl, $TadUpFiles;
    if (empty($WebID)) {
        retuen;
    }
    include_once "class.php";

    $tad_web_works = new tad_web_works($WebID);
    $block         = $tad_web_works->list_all("", 1, 'return', '', 'order by WorksDate desc', $config['limit']);
    return $block;
}
