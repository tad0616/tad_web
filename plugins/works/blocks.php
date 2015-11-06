<?php
function random_work($WebID)
{

    global $xoopsDB, $xoopsTpl, $TadUpFiles;
    if (empty($WebID)) {
        retuen;
    }

    $sql    = "select * from " . $xoopsDB->prefix("tad_web_works") . " where WebID='{$WebID}' order by rand() limit 0,1";
    $result = $xoopsDB->query($sql) or web_error($sql);
    $all    = $xoopsDB->fetchArray($result);

    //以下會產生這些變數： $WorksID , $WorkName , $WorkDesc , $WorksDate , $uid , $WebID , $WorksCount
    foreach ($all as $k => $v) {
        $$k = $v;
    }

    $TadUpFiles->set_col("WorksID", $WorksID);
    $pics = $TadUpFiles->show_files('upfile', true, null, true); //是否縮圖,顯示模式 filename、small,顯示描述,顯示下載次數

    $xoopsTpl->assign('WorkName', $WorkName);
    $xoopsTpl->assign('random_work', $pics);
    $xoopsTpl->assign('WorksID', $WorksID);
    $xoopsTpl->assign('WebID', $WebID);
    $xoopsTpl->assign('func', 'random_work');

}

function latest_work($WebID)
{

    global $xoopsDB, $xoopsTpl, $TadUpFiles;
    if (empty($WebID)) {
        retuen;
    }

    $sql    = "select * from " . $xoopsDB->prefix("tad_web_works") . " where WebID='{$WebID}' order by WorksDate desc limit 0,1";
    $result = $xoopsDB->query($sql) or web_error($sql);
    $all    = $xoopsDB->fetchArray($result);

    //以下會產生這些變數： $WorksID , $WorkName , $WorkDesc , $WorksDate , $uid , $WebID , $WorksCount
    foreach ($all as $k => $v) {
        $$k = $v;
    }

    $TadUpFiles->set_col("WorksID", $WorksID);
    $pics = $TadUpFiles->show_files('upfile', true, null, true); //是否縮圖,顯示模式 filename、small,顯示描述,顯示下載次數

    $xoopsTpl->assign('WorkName', $WorkName);
    $xoopsTpl->assign('latest_work', $pics);
    $xoopsTpl->assign('WorksID', $WorksID);
    $xoopsTpl->assign('WebID', $WebID);
    $xoopsTpl->assign('func', 'latest_work');

}
