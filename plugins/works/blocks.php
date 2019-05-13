<?php
/************** list_work ************
 * @param       $WebID
 * @param array $config
 * @return void
 */
function list_work($WebID, $config = [])
{
    global $xoopsDB, $xoopsTpl, $TadUpFiles;
    if (empty($WebID)) {
        return;
    }
    require_once __DIR__ . '/class.php';

    $tad_web_works = new tad_web_works($WebID);
    $block = $tad_web_works->list_all('', $config['limit'], 'return');

    return $block;
}

/************** random_work ************
 * @param       $WebID
 * @param array $config
 * @return void
 */

function random_work($WebID, $config = [])
{
    global $xoopsDB, $xoopsTpl, $TadUpFiles;
    if (empty($WebID)) {
        return;
    }

    require_once __DIR__ . '/class.php';

    $tad_web_works = new tad_web_works($WebID);
    $block = $tad_web_works->list_all('', 1, 'return', '', '', 'order by rand()', $config['limit']);

    return $block;
}

/************** latest_work ************
 * @param       $WebID
 * @param array $config
 * @return void
 */

function latest_work($WebID, $config = [])
{
    global $xoopsDB, $xoopsTpl, $TadUpFiles;
    if (empty($WebID)) {
        return;
    }
    require_once __DIR__ . '/class.php';

    $tad_web_works = new tad_web_works($WebID);
    $block = $tad_web_works->list_all('', 1, 'return', '', '', 'order by WorksDate desc', $config['limit']);

    return $block;
}
