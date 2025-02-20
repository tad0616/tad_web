<?php
use XoopsModules\Tadtools\Utility;

/************** list_video ************
 * @param       $WebID
 * @param array $config
 * @return string|void
 */
function list_video($WebID, $config = [])
{
    global $xoopsDB, $xoopsTpl, $TadUpFiles;
    if (empty($WebID)) {
        return;
    }
    require_once __DIR__ . '/class.php';

    $block = [];
    $tad_web_video = new tad_web_video($WebID);
    $block = $tad_web_video->list_all('', $config['limit'], 'return', '', $config['mode']);
    return $block;
}

/************** random_video ************
 * @param       $WebID
 * @param array $config
 * @return array|void
 */

function random_video($WebID, $config = [])
{
    global $xoopsDB;
    if (empty($WebID)) {
        return;
    }
    $block = [];

    $sql = 'SELECT * FROM `' . $xoopsDB->prefix('tad_web_video') . '` WHERE `WebID`=? ORDER BY RAND() LIMIT 0,1';
    $result = Utility::query($sql, 'i', [$WebID]) or Utility::web_error($sql, __FILE__, __LINE__);
    $all = $xoopsDB->fetchArray($result);

    //以下會產生這些變數： $VideoID , $VideoName , $VideoDesc , $VideoDate , $VideoPlace , $uid , $WebID , $VideoCount
    if ($all) {
        foreach ($all as $k => $v) {
            $$k = $v;
        }
    }

    if (empty($VideoPlace)) {
        return;
    }

    $block['main_data'] = "<div class='embed-responsive embed-responsive-16by9 ratio ratio-16x9'><iframe title='random_video' class='embed-responsive-item' src='https://www.youtube.com/embed/{$VideoPlace}?feature=oembed' frameborder='0' allowfullscreen></iframe></div>";
    $block['VideoID'] = $VideoID;
    $block['VideoName'] = $VideoName;

    return $block;
}

/************** latest_video ************
 * @param       $WebID
 * @param array $config
 * @return array|void
 */

function latest_video($WebID, $config = [])
{
    global $xoopsDB;
    if (empty($WebID)) {
        return;
    }
    $block = [];
    $sql = 'SELECT * FROM `' . $xoopsDB->prefix('tad_web_video') . '` WHERE `WebID`=? ORDER BY `VideoDate` DESC, `VideoID` DESC LIMIT 0,1';
    $result = Utility::query($sql, 'i', [$WebID]) or Utility::web_error($sql, __FILE__, __LINE__);
    $all = $xoopsDB->fetchArray($result);

    //以下會產生這些變數： $VideoID , $VideoName , $VideoDesc , $VideoDate , $VideoPlace , $uid , $WebID , $VideoCount
    if ($all) {
        foreach ($all as $k => $v) {
            $$k = $v;
        }
    }

    if (empty($VideoPlace)) {
        return;
    }

    $block['main_data'] = "<div class='embed-responsive embed-responsive-16by9 ratio ratio-4x3'><iframe title='latest_video' class='embed-responsive-item' src='https://www.youtube.com/embed/{$VideoPlace}?feature=oembed' frameborder='0' allowfullscreen></iframe></div>";
    $block['VideoID'] = $VideoID;
    $block['VideoName'] = $VideoName;

    return $block;
}
