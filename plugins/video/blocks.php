<?php
//get_random_video($WebID);

function get_random_video($WebID)
{

    global $xoopsDB, $xoopsTpl;
    if (empty($WebID)) {
        retuen;
    }

    $sql = "select * from " . $xoopsDB->prefix("tad_web_video") . " where WebID='$WebID' order by rand() limit 0,1";
    //die($sql);
    $result = $xoopsDB->queryF($sql) or web_error($sql);
    $all    = $xoopsDB->fetchArray($result);

    //以下會產生這些變數： $VideoID , $VideoName , $VideoDesc , $VideoDate , $VideoPlace , $uid , $WebID , $VideoCount
    foreach ($all as $k => $v) {
        $$k = $v;
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
    $jw     = new JwPlayer("block_video{$VideoID}", $Youtube, "http://i3.ytimg.com/vi/{$VideoPlace}/0.jpg", '100%', $rate);
    $player = $jw->render();

    // $xoopsTpl->assign('isMineVideo', $isMyWeb);
    $xoopsTpl->assign('VideoName', $VideoName);
    // $xoopsTpl->assign('VideoDate', $VideoDate);
    // $xoopsTpl->assign('VideoPlace', $VideoPlace);
    // $xoopsTpl->assign('VideoDesc', nl2br($VideoDesc));
    // $xoopsTpl->assign('uid_name', $uid_name);
    // $xoopsTpl->assign('VideoCountInfo', sprintf(_MD_TCW_VIDEOCOUNTINFO, $VideoCount));
    $xoopsTpl->assign('block_player', $player);
    $xoopsTpl->assign('VideoID', $VideoID);
    $xoopsTpl->assign('WebID', $WebID);
    // $xoopsTpl->assign('VideoInfo', sprintf(_MD_TCW_INFO, $uid_name, $VideoDate, $VideoCount));

    // return $player;
}
