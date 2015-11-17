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

function config_list_work($WebID, $config = array())
{

    global $xoopsDB, $xoopsTpl, $TadUpFiles;
    if (empty($WebID)) {
        retuen;
    }
    include_once "class.php";

    $tad_web_works = new tad_web_works($WebID);
    $tad_web_works->list_all("", $config['limit'], 'return');
}

/************** random_work *************/

function random_work($WebID, $config = array())
{

    global $xoopsDB, $xoopsTpl, $TadUpFiles;
    if (empty($WebID)) {
        retuen;
    }
    $block  = '';
    $sql    = "select * from " . $xoopsDB->prefix("tad_web_works") . " where WebID='{$WebID}' order by rand() limit 0,1";
    $result = $xoopsDB->query($sql) or web_error($sql);
    $all    = $xoopsDB->fetchArray($result);

    //以下會產生這些變數： $WorksID , $WorkName , $WorkDesc , $WorksDate , $uid , $WebID , $WorksCount
    if ($all) {
        foreach ($all as $k => $v) {
            $$k = $v;
        }
        $TadUpFiles->set_col("WorksID", $WorksID);
        $pics = $TadUpFiles->show_files('upfile', true, null, true); //是否縮圖,顯示模式 filename、small,顯示描述,顯示下載次數

        $block['random_work'] = $pics;
        $block['WorksID']     = $WorksID;
        $block['WorkName']    = $WorkName;
    }
    return $block;
}

/************** latest_work *************/

function latest_work($WebID, $config = array())
{

    global $xoopsDB, $xoopsTpl, $TadUpFiles;
    if (empty($WebID)) {
        retuen;
    }
    $block = '';

    $sql    = "select * from " . $xoopsDB->prefix("tad_web_works") . " where WebID='{$WebID}' order by WorksDate desc limit 0,1";
    $result = $xoopsDB->query($sql) or web_error($sql);
    $all    = $xoopsDB->fetchArray($result);

    //以下會產生這些變數： $WorksID , $WorkName , $WorkDesc , $WorksDate , $uid , $WebID , $WorksCount
    if ($all) {
        foreach ($all as $k => $v) {
            $$k = $v;
        }
        $TadUpFiles->set_col("WorksID", $WorksID);
        $pics = $TadUpFiles->show_files('upfile', true, null, true); //是否縮圖,顯示模式 filename、small,顯示描述,顯示下載次數

        $block['latest_work'] = $pics;
        $block['WorksID']     = $WorksID;
        $block['WorkName']    = $WorkName;
    }
    return $block;
}
