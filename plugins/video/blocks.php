<?php
/************** list_video *************/
function list_video($WebID, $config = array())
{

    global $xoopsDB, $xoopsTpl, $TadUpFiles;
    if (empty($WebID)) {
        retuen;
    }
    include_once "class.php";

    $block         = '';
    $tad_web_video = new tad_web_video($WebID);
    $block         = $tad_web_video->list_all("", $config['limit'], 'return');
    return $block;
}

/************** random_video *************/

function random_video($WebID, $config = array())
{

    global $xoopsDB, $xoopsTpl;
    if (empty($WebID)) {
        retuen;
    }
    $block = '';

    $sql = "select * from " . $xoopsDB->prefix("tad_web_video") . " where WebID='$WebID' order by rand() limit 0,1";
    //die($sql);
    $result = $xoopsDB->queryF($sql) or web_error($sql);
    $all    = $xoopsDB->fetchArray($result);

    //以下會產生這些變數： $VideoID , $VideoName , $VideoDesc , $VideoDate , $VideoPlace , $uid , $WebID , $VideoCount
    if ($all) {
        foreach ($all as $k => $v) {
            $$k = $v;
        }
    }

    if (empty($VideoPlace)) {
        return;
    }

    $url      = "http://www.youtube.com/oembed?url=http%3A%2F%2Fwww.youtube.com%2Fwatch%3Fv%3D{$VideoPlace}&format=json";
    $contents = file_get_contents($url);
    $contents = utf8_encode($contents);

    $results = json_decode($contents, false);
    foreach ($results as $k => $v) {
        $$k = htmlspecialchars($v);
    }

    $rate = round($height / $width, 2);

    if (!file_exists(XOOPS_ROOT_PATH . "/modules/tadtools/jwplayer_new.php")) {
        redirect_header("index.php", 3, _MD_NEED_TADTOOLS);
    }
    include_once XOOPS_ROOT_PATH . "/modules/tadtools/jwplayer_new.php";
    $jw     = new JwPlayer("random_video_{$VideoID}", $Youtube, "http://i3.ytimg.com/vi/{$VideoPlace}/0.jpg", '100%', $rate);
    $player = $jw->render();

    $block['main_data'] = $player;
    $block['VideoID']   = $VideoID;
    $block['VideoName'] = $VideoName;
    return $block;

}

/************** latest_video *************/

function latest_video($WebID, $config = array())
{

    global $xoopsDB, $xoopsTpl;
    if (empty($WebID)) {
        retuen;
    }
    $block = '';
    $sql   = "select * from " . $xoopsDB->prefix("tad_web_video") . " where WebID='$WebID' order by VideoDate desc limit 0,1";
    //die($sql);
    $result = $xoopsDB->queryF($sql) or web_error($sql);
    $all    = $xoopsDB->fetchArray($result);

    //以下會產生這些變數： $VideoID , $VideoName , $VideoDesc , $VideoDate , $VideoPlace , $uid , $WebID , $VideoCount
    if ($all) {
        foreach ($all as $k => $v) {
            $$k = $v;
        }
    }

    if (empty($VideoPlace)) {
        return;
    }

    $url      = "http://www.youtube.com/oembed?url=http%3A%2F%2Fwww.youtube.com%2Fwatch%3Fv%3D{$VideoPlace}&format=json";
    $contents = file_get_contents($url);
    $contents = utf8_encode($contents);

    $results = json_decode($contents, false);
    foreach ($results as $k => $v) {
        $$k = htmlspecialchars($v);
    }

    $rate = round($height / $width, 2);

    if (!file_exists(XOOPS_ROOT_PATH . "/modules/tadtools/jwplayer_new.php")) {
        redirect_header("index.php", 3, _MD_NEED_TADTOOLS);
    }
    include_once XOOPS_ROOT_PATH . "/modules/tadtools/jwplayer_new.php";
    $jw     = new JwPlayer("latest_video_{$VideoID}", $Youtube, "http://i3.ytimg.com/vi/{$VideoPlace}/0.jpg", '100%', $rate);
    $player = $jw->render();

    $block['main_data'] = $player;
    $block['VideoID']   = $VideoID;
    $block['VideoName'] = $VideoName;
    return $block;
}
