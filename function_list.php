<?php

//所有網站列表
function list_all_tad_webs()
{
    global $xoopsDB, $xoopsUser, $MyWebs, $xoopsModuleConfig, $xoopsTpl, $TadUpFiles;

    $sql    = "select * from " . $xoopsDB->prefix("tad_web") . " where `WebEnable`='1' order by WebSort";
    $result = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'], 3, mysql_error());

    $data = "";
    $i    = 0;
    while ($all = $xoopsDB->fetchArray($result)) {
        //以下會產生這些變數： $WebID , $WebName , $WebSort , $WebEnable , $WebCounter
        foreach ($all as $k => $v) {
            $$k           = $v;
            $data[$i][$k] = $v;
        }

        //$TadUpFiles->set_col("WebOwner", $WebID, 1);
        //$data[$i]['WebOwnerPic'] = $TadUpFiles->get_pic_file();

        $data[$i]['my']    = in_array($WebID, $MyWebs) ? 1 : 0;
        $data[$i]['uname'] = XoopsUser::getUnameFromId($WebOwnerUid, 0);

        $i++;
    }
//exit;
    $n = ceil($i / 4);

    $xoopsTpl->assign('data', $data);
    $xoopsTpl->assign('MyWebs', $MyWebs);
    $xoopsTpl->assign('count', $i);

    $xoopsTpl->assign('tad_web_cate', get_tad_web_cate_all('tad_web'));

}

//最新消息
function list_tad_web_news($WebID = "", $CateID = "", $NewsKind = 'news', $limit = null, $order = 'NewsDate')
{
    global $xoopsDB, $MyWebs, $isAdmin, $xoopsTpl;

    $showWebTitle = (empty($WebID)) ? 1 : 0;
    $NewsKindTag  = strtoupper(substr($NewsKind, 0, 1)) . substr($NewsKind, 1);

    if (empty($WebID)) {
        $whereWebID = "";
    } else {
        $whereWebID = "and a.`WebID`='$WebID'";
    }

    //取得tad_web_cate所有資料陣列
    $web_cate  = new web_cate($WebID, "news", "tad_web_news");
    $cate_menu = $web_cate->cate_menu($CateID, 'page', false, true, false, true);
    $xoopsTpl->assign('cate_menu', $cate_menu);

    if (empty($CateID)) {
        $andCateID = "";
    } else {
        //取得單一分類資料
        $cate = $web_cate->get_tad_web_cate($CateID);
        $xoopsTpl->assign('cate', $cate);
        $andCateID = "and a.`CateID`='$CateID'";
    }

    if (!empty($_REQUEST['key'])) {
        $andKey   = "and (a.`NewsTitle` like '%{$_REQUEST['key']}%' or a.`NewsContent` like '%{$_REQUEST['key']}%' or a.`NewsPlace` like '%{$_REQUEST['key']}%')";
        $andLimit = '';
    } else {
        $andLimit = ($limit > 0) ? "limit 0,$limit" : "";
        $andKey   = '';
    }

    $sql = "select a.* from " . $xoopsDB->prefix("tad_web_news") . " as a left join " . $xoopsDB->prefix("tad_web") . " as b on a.WebID=b.WebID where b.`WebEnable`='1' and a.NewsKind='$NewsKind' $whereWebID $andCateID $andKey order by $order desc $andLimit";
    //die($sql);

    $bar = "";
    if (empty($limit)) {
        //getPageBar($原sql語法, 每頁顯示幾筆資料, 最多顯示幾個頁數選項);
        $PageBar = getPageBar($sql, 20, 10);
        $bar     = $PageBar['bar'];
        $sql     = $PageBar['sql'];
        $total   = $PageBar['total'];
    }

    $result = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'], 3, mysql_error());

    $main_data = "";
    $i         = 0;
    while ($all = $xoopsDB->fetchArray($result)) {
        //以下會產生這些變數： $NewsID , $NewsTitle , $NewsContent , $NewsDate , $toCal , $NewsPlace , $NewsMaster , $NewsUrl , $WebID , $NewsKind , $NewsCounter
        foreach ($all as $k => $v) {
            $$k = $v;
        }
        $Date = substr($$order, 0, 10);
        if (empty($NewsTitle)) {
            $NewsTitle = _MD_TCW_EMPTY_TITLE;
        }

        $Class = getWebInfo($WebID);

        $web_cate->set_WebID($WebID);
        $cate                  = $web_cate->get_tad_web_cate_arr();
        $main_data[$i]['cate'] = $cate[$CateID];

        $main_data[$i]['Date']        = $Date;
        $main_data[$i]['NewsID']      = $NewsID;
        $main_data[$i]['NewsTitle']   = $NewsTitle;
        $main_data[$i]['NewsContent'] = $NewsContent;
        $main_data[$i]['NewsCounter'] = $NewsCounter;
        $main_data[$i]['WebID']       = $WebID;
        $main_data[$i]['WebTitle']    = "<a href='index.php?WebID=$WebID'>{$Class['WebTitle']}</a>";
        $main_data[$i]['NewsKind']    = $NewsKind;
        $i++;
    }

    $more = ($limit > 0) ? "" : "";

    $mode = empty($limit) ? "list" : "limit";

    $xoopsTpl->assign("showWebTitle{$NewsKindTag}", $showWebTitle);
    $xoopsTpl->assign("{$NewsKind}_data", $main_data);
    $xoopsTpl->assign('bar', $bar);
    $xoopsTpl->assign('mode', $mode);
    $xoopsTpl->assign("isMine{$NewsKindTag}", isMine());
    $xoopsTpl->assign("isMyWeb", in_array($WebID, $MyWebs));
    if ($NewsKind == "homework") {
        $xoopsTpl->assign('HomeTitle', _MD_TCW_HOMEWORK);
    } else {
        $xoopsTpl->assign('HomeTitle', _MD_TCW_NEWS);
    }
}

//檔案下載
function list_tad_web_files($WebID = "", $CateID = "", $limit = "")
{
    global $xoopsDB, $xoopsUser, $isAdmin, $xoopsTpl;

    $showWebTitle = (empty($WebID)) ? 1 : 0;
    //$enable_link=(!$xoopsUser and _ONLY_USER)?false:true;
    $enable_link = true;

    //所有文件種類名稱
    $kindname = getAllCateName('file', $WebID);

    $andWebID = (empty($WebID)) ? "" : "and a.WebID='$WebID'";

    //取得tad_web_cate所有資料陣列
    $web_cate  = new web_cate($WebID, "files", "tad_web_files");
    $cate_menu = $web_cate->cate_menu($CateID, 'page', false, true, false, true);
    $xoopsTpl->assign('cate_menu', $cate_menu);

    if (empty($CateID)) {
        $andCateID = "";
    } else {
        //取得單一分類資料
        $cate = $web_cate->get_tad_web_cate($CateID);
        $xoopsTpl->assign('cate', $cate);
        $andCateID = "and a.`CateID`='$CateID'";
    }
    $andLimit = ($limit > 0) ? "limit 0,$limit" : "";

    $data = $title = "";

    $sql = "select a.* , b.* from " . $xoopsDB->prefix("tad_web_files") . " as a join " . $xoopsDB->prefix("tad_web_files_center") . " as b on a.fsn=b.col_sn  left join " . $xoopsDB->prefix("tad_web") . " as c on a.WebID=c.WebID where c.`WebEnable`='1' and b.col_name='fsn' $andWebID $andCateID order by a.file_date desc $andLimit";

    if (empty($limit)) {
        //getPageBar($原sql語法, 每頁顯示幾筆資料, 最多顯示幾個頁數選項);
        $PageBar = getPageBar($sql, 20, 10);
        $bar     = $PageBar['bar'];
        $sql     = $PageBar['sql'];
        $total   = $PageBar['total'];
    } else {
        $bar = "";
    }

    $result = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'], 3, mysql_error());

    $all_data = "";

    $i = 0;
    while ($all = $xoopsDB->fetchArray($result)) {

        //以下會產生這些變數： $fsn , $uid , $CateID , $file_date  , $WebID
        foreach ($all as $k => $v) {
            $$k = $v;
        }

        //僅管理員和作者可以編輯
        $fun = (isMine($uid)) ? "" : "<td></td>";

        $uid_name = XoopsUser::getUnameFromId($uid, 1);

        $Class     = getWebInfo($WebID);
        $file_date = substr($file_date, 0, 10);

        $showurl = ($enable_link) ? "<a href='" . XOOPS_URL . "/modules/tad_web/files.php?WebID={$WebID}&op=tufdl&files_sn=$files_sn' class='iconize'>{$description}</a>" : $description;

        $web_cate->set_WebID($WebID);
        $cate                 = $web_cate->get_tad_web_cate_arr();
        $all_data[$i]['cate'] = $cate[$CateID];

        $all_data[$i]['showurl']  = $showurl;
        $all_data[$i]['uid_name'] = $uid_name;
        $all_data[$i]['fsn']      = $fsn;
        $all_data[$i]['WebID']    = $WebID;
        $all_data[$i]['WebTitle'] = "<a href='index.php?WebID=$WebID'>{$Class['WebTitle']}</a>";
        $i++;
    }

    $xoopsTpl->assign('bar', $bar);
    $xoopsTpl->assign('isMineFiles', isMine());
    $xoopsTpl->assign('showWebTitleFiles', $showWebTitle);
    $xoopsTpl->assign('file_data', $all_data);
}

//活動剪影
function list_tad_web_action($WebID = "", $CateID = "", $limit = null)
{
    global $xoopsDB, $xoopsTpl, $TadUpFiles;

    $showWebTitle = (empty($WebID)) ? 1 : 0;
    $andWebID     = (empty($WebID)) ? "" : "and a.WebID='$WebID'";

    //取得tad_web_cate所有資料陣列
    $web_cate  = new web_cate($WebID, "action", "tad_web_action");
    $cate_menu = $web_cate->cate_menu($CateID, 'page', false, true, false, true);
    $xoopsTpl->assign('cate_menu', $cate_menu);

    if (empty($CateID)) {
        $andCateID = "";
    } else {
        //取得單一分類資料
        $cate = $web_cate->get_tad_web_cate($CateID);
        $xoopsTpl->assign('cate', $cate);
        $andCateID = "and a.`CateID`='$CateID'";
    }
    $andLimit = (empty($limit)) ? "" : "limit 0 , $limit";
    $sql      = "select a.* from " . $xoopsDB->prefix("tad_web_action") . " as a left join " . $xoopsDB->prefix("tad_web") . " as b on a.WebID=b.WebID where b.`WebEnable`='1' $andWebID $andCateID order by a.ActionDate desc $andLimit";

    if (empty($limit)) {
        //getPageBar($原sql語法, 每頁顯示幾筆資料, 最多顯示幾個頁數選項);
        $PageBar = getPageBar($sql, 20, 10);
        $bar     = $PageBar['bar'];
        $sql     = $PageBar['sql'];
        $total   = $PageBar['total'];
    } else {
        $bar = "";
    }

    $result = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'], 3, mysql_error());

    $main_data = "";
    $i         = 0;
    while ($all = $xoopsDB->fetchArray($result)) {
        //以下會產生這些變數： $ActionID , $ActionName , $ActionDesc , $ActionDate , $ActionPlace , $uid , $WebID , $ActionCount
        foreach ($all as $k => $v) {
            $$k = $v;
        }

        $Class = getWebInfo($WebID);

        $web_cate->set_WebID($WebID);
        $cate                  = $web_cate->get_tad_web_cate_arr();
        $main_data[$i]['cate'] = $cate[$CateID];

        $main_data[$i]['ActionDate']  = $ActionDate;
        $main_data[$i]['ActionID']    = $ActionID;
        $main_data[$i]['WebID']       = $WebID;
        $main_data[$i]['ActionName']  = $ActionName;
        $main_data[$i]['ActionPlace'] = $ActionPlace;
        $main_data[$i]['ActionCount'] = $ActionCount;
        $main_data[$i]['WebTitle']    = "<a href='index.php?WebID=$WebID'>{$Class['WebTitle']}</a>";

        $subdir = isset($WebID) ? "/{$WebID}" : "";
        $TadUpFiles->set_dir('subdir', $subdir);
        $TadUpFiles->set_col("ActionID", $ActionID);
        $ActionPic = $TadUpFiles->get_pic_file('thumb');
        //die(var_export($ActionPic));
        $main_data[$i]['ActionPic'] = $ActionPic;
        $i++;
    }

    $xoopsTpl->assign('action_data', $main_data);

    $xoopsTpl->assign('bar', $bar);
    $xoopsTpl->assign('isMineAction', isMine());
    $xoopsTpl->assign('showWebTitleAction', $showWebTitle);

}

//作品分享
function list_tad_web_works($WebID = "", $CateID = "", $limit = null)
{
    global $xoopsDB, $xoopsTpl;

    $showWebTitle = (empty($WebID)) ? 1 : 0;
    $andWebID     = (empty($WebID)) ? "" : "and a.WebID='$WebID'";

    //取得tad_web_cate所有資料陣列
    $web_cate  = new web_cate($WebID, "works", "tad_web_works");
    $cate_menu = $web_cate->cate_menu($CateID, 'page', false, true, false, true);
    $xoopsTpl->assign('cate_menu', $cate_menu);

    if (empty($CateID)) {
        $andCateID = "";
    } else {
        //取得單一分類資料
        $cate = $web_cate->get_tad_web_cate($CateID);
        $xoopsTpl->assign('cate', $cate);
        $andCateID = "and a.`CateID`='$CateID'";
    }
    $andLimit = (empty($limit)) ? "" : "limit 0 , $limit";
    $sql      = "select a.* from " . $xoopsDB->prefix("tad_web_works") . " as a left join " . $xoopsDB->prefix("tad_web") . " as b on a.WebID=b.WebID where b.`WebEnable`='1' $andWebID $andCateID order by a.WorksDate desc $andLimit";

    if (empty($limit)) {
        //getPageBar($原sql語法, 每頁顯示幾筆資料, 最多顯示幾個頁數選項);
        $PageBar = getPageBar($sql, 20, 10);
        $bar     = $PageBar['bar'];
        $sql     = $PageBar['sql'];
        $total   = $PageBar['total'];
    } else {
        $bar = "";
    }

    $result = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'], 3, mysql_error());

    $main_data = "";
    $i         = 0;
    while ($all = $xoopsDB->fetchArray($result)) {
        //以下會產生這些變數： $ActionID , $ActionName , $ActionDesc , $ActionDate , $ActionPlace , $uid , $WebID , $ActionCount
        foreach ($all as $k => $v) {
            $$k = $v;
        }

        $Class = getWebInfo($WebID);

        $web_cate->set_WebID($WebID);
        $cate                  = $web_cate->get_tad_web_cate_arr();
        $main_data[$i]['cate'] = $cate[$CateID];

        $main_data[$i]['WorksDate']  = substr($WorksDate, 0, 10);
        $main_data[$i]['WorksID']    = $WorksID;
        $main_data[$i]['WebID']      = $WebID;
        $main_data[$i]['WorkName']   = $WorkName;
        $main_data[$i]['WorksCount'] = $WorksCount;
        $main_data[$i]['WebTitle']   = "<a href='index.php?WebID=$WebID'>{$Class['WebTitle']}</a>";
        $i++;
    }

    $xoopsTpl->assign('works_data', $main_data);

    $xoopsTpl->assign('bar', $bar);
    $xoopsTpl->assign('isMineWorks', isMine());
    $xoopsTpl->assign('showWebTitleWorks', $showWebTitle);

}
//好站連結
function list_tad_web_link($WebID = "", $CateID = "", $limit = "")
{
    global $xoopsDB, $xoopsTpl;

    $showWebTitle = (empty($WebID)) ? 1 : 0;

    $andWebID = (empty($WebID)) ? "" : "and a.WebID='$WebID'";

    //取得tad_web_cate所有資料陣列
    $web_cate  = new web_cate($WebID, "link", "tad_web_link");
    $cate_menu = $web_cate->cate_menu($CateID, 'page', false, true, false, true);
    $xoopsTpl->assign('cate_menu', $cate_menu);

    if (empty($CateID)) {
        $andCateID = "";
    } else {
        //取得單一分類資料
        $cate = $web_cate->get_tad_web_cate($CateID);
        $xoopsTpl->assign('cate', $cate);
        $andCateID = "and a.`CateID`='$CateID'";
    }
    $andLimit = ($limit > 0) ? "limit 0,$limit" : "";

    $sql = "select a.* from " . $xoopsDB->prefix("tad_web_link") . " as a left join " . $xoopsDB->prefix("tad_web") . " as b on a.WebID=b.WebID where b.`WebEnable`='1' $andWebID $andCateID order by a.LinkID desc $andLimit";

    if (empty($limit)) {
        //getPageBar($原sql語法, 每頁顯示幾筆資料, 最多顯示幾個頁數選項);
        $PageBar = getPageBar($sql, 20, 10);
        $bar     = $PageBar['bar'];
        $sql     = $PageBar['sql'];
        $total   = $PageBar['total'];
    } else {
        $bar = "";
    }

    $result = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'], 3, mysql_error());

    $main_data = "";
    $i         = 0;

    while ($all = $xoopsDB->fetchArray($result)) {
        //以下會產生這些變數： $LinkID , $LinkTitle , $LinkDesc , $LinkUrl , $LinkCounter , $LinkSort , $WebID , $uid
        foreach ($all as $k => $v) {
            $$k = $v;
        }

        $Class = getWebInfo($WebID);

        $LinkDesc = nl2br(xoops_substr(strip_tags($LinkDesc), 0, 150));

        $web_cate->set_WebID($WebID);
        $cate                  = $web_cate->get_tad_web_cate_arr();
        $main_data[$i]['cate'] = $cate[$CateID];

        $main_data[$i]['LinkUrl']     = $LinkUrl;
        $main_data[$i]['LinkTitle']   = $LinkTitle;
        $main_data[$i]['LinkDesc']    = $LinkDesc;
        $main_data[$i]['LinkCounter'] = $LinkCounter;
        $main_data[$i]['LinkID']      = $LinkID;
        $main_data[$i]['WebID']       = $WebID;
        $main_data[$i]['WebTitle']    = "<a href='index.php?WebID=$WebID'>{$Class['WebTitle']}</a>";
        $i++;
    }

    $xoopsTpl->assign('link_data', $main_data);

    $xoopsTpl->assign('bar', $bar);
    $xoopsTpl->assign('isMineLink', isMine());
    $xoopsTpl->assign('showWebTitleLink', $showWebTitle);
}

//影片
function list_tad_web_video($WebID = "", $CateID = "", $limit = "")
{
    global $xoopsDB, $xoopsTpl;

    $showWebTitle = (empty($WebID)) ? 1 : 0;

    $andWebID = (empty($WebID)) ? "" : "and a.WebID='$WebID'";

    //取得tad_web_cate所有資料陣列
    $web_cate  = new web_cate($WebID, "video", "tad_web_video");
    $cate_menu = $web_cate->cate_menu($CateID, 'page', false, true, false, true);
    $xoopsTpl->assign('cate_menu', $cate_menu);

    if (empty($CateID)) {
        $andCateID = "";
    } else {
        //取得單一分類資料
        $cate = $web_cate->get_tad_web_cate($CateID);
        $xoopsTpl->assign('cate', $cate);
        $andCateID = "and a.`CateID`='$CateID'";
    }
    $andLimit = ($limit > 0) ? "limit 0,$limit" : "";
    $sql      = "select a.* from " . $xoopsDB->prefix("tad_web_video") . " as a left join " . $xoopsDB->prefix("tad_web") . " as b on a.WebID=b.WebID where b.`WebEnable`='1' $andWebID $andCateID order by a.VideoDate desc , a.VideoID desc $andLimit";

    if (empty($limit)) {
        //getPageBar($原sql語法, 每頁顯示幾筆資料, 最多顯示幾個頁數選項);
        $PageBar = getPageBar($sql, 20, 10);
        $bar     = $PageBar['bar'];
        $sql     = $PageBar['sql'];
        $total   = $PageBar['total'];
    } else {
        $bar = "";
    }

    $result = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'], 3, mysql_error());

    $main_data = "";
    $i         = 0;

    while ($all = $xoopsDB->fetchArray($result)) {
        //以下會產生這些變數： $VideoID , $VideoName , $VideoDesc , $VideoDate , $VideoPlace , $uid , $WebID , $VideoCount
        foreach ($all as $k => $v) {
            $$k = $v;
        }

        $Class = getWebInfo($WebID);

        $web_cate->set_WebID($WebID);
        $cate                  = $web_cate->get_tad_web_cate_arr();
        $main_data[$i]['cate'] = $cate[$CateID];

        $main_data[$i]['VideoID']    = $VideoID;
        $main_data[$i]['VideoPlace'] = $VideoPlace;
        $main_data[$i]['WebID']      = $WebID;
        $main_data[$i]['VideoDate']  = $VideoDate;
        $main_data[$i]['VideoName']  = $VideoName;
        $main_data[$i]['VideoCount'] = $VideoCount;
        $main_data[$i]['WebTitle']   = "<a href='index.php?WebID=$WebID'>{$Class['WebTitle']}</a>";
        $i++;
    }

    $xoopsTpl->assign('video_data', $main_data);

    $xoopsTpl->assign('bar', $bar);
    $xoopsTpl->assign('isMineVideo', isMine());
    $xoopsTpl->assign('showWebTitleVideo', $showWebTitle);
}

//列出所有tad_web_discuss資料
function list_tad_web_discuss($WebID = "", $CateID = "", $limit = null)
{
    global $xoopsDB, $xoopsUser, $xoopsTpl;

    if (!$xoopsUser and empty($_SESSION['LoginMemID'])) {
        $xoopsTpl->assign('mode', 'need_login');
    } else {

        $showWebTitle = (empty($WebID)) ? 1 : 0;

        $andWebID = (empty($WebID)) ? "" : "and a.WebID='$WebID'";

        //取得tad_web_cate所有資料陣列
        $web_cate  = new web_cate($WebID, "discuss", "tad_web_discuss");
        $cate_menu = $web_cate->cate_menu($CateID, 'page', false, true, false, true);
        $xoopsTpl->assign('cate_menu', $cate_menu);

        if (empty($CateID)) {
            $andCateID = "";
        } else {
            //取得單一分類資料
            $cate = $web_cate->get_tad_web_cate($CateID);
            $xoopsTpl->assign('cate', $cate);
            $andCateID = "and a.`CateID`='$CateID'";
        }
        $andLimit = ($limit > 0) ? "limit 0,$limit" : "";
        $sql      = "select a.* from " . $xoopsDB->prefix("tad_web_discuss") . " as a left join " . $xoopsDB->prefix("tad_web") . " as b on a.WebID=b.WebID where b.`WebEnable`='1' and a.ReDiscussID='0' $andWebID $andCateID order by a.LastTime desc $andLimit";

        if (empty($limit)) {
            //getPageBar($原sql語法, 每頁顯示幾筆資料, 最多顯示幾個頁數選項);
            $PageBar = getPageBar($sql, 20, 10);
            $bar     = $PageBar['bar'];
            $sql     = $PageBar['sql'];
            $total   = $PageBar['total'];
        } else {
            $bar = "";
        }

        $result = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'], 3, mysql_error());

        $main_data = "";
        $i         = 0;

        while ($all = $xoopsDB->fetchArray($result)) {
            //`DiscussID`, `ReDiscussID`, `CateID`, `WebID`, `MemID`, `MemName`, `DiscussTitle`, `DiscussContent`, `DiscussDate`, `LastTime`, `DiscussCounter`
            foreach ($all as $k => $v) {
                $$k = $v;
            }

            $renum       = get_re_num($DiscussID);
            $show_re_num = empty($renum) ? "" : " ({$renum}) ";

            $LastTime = substr($LastTime, 0, 10);
            $Class    = getWebInfo($WebID);

            $web_cate->set_WebID($WebID);
            $cate                  = $web_cate->get_tad_web_cate_arr();
            $main_data[$i]['cate'] = $cate[$CateID];

            $main_data[$i]['DiscussID']      = $DiscussID;
            $main_data[$i]['DiscussTitle']   = $DiscussTitle;
            $main_data[$i]['show_re_num']    = $show_re_num;
            $main_data[$i]['LastTime']       = $LastTime;
            $main_data[$i]['MemName']        = $MemName;
            $main_data[$i]['DiscussCounter'] = $DiscussCounter;
            $main_data[$i]['WebID']          = $WebID;
            $main_data[$i]['WebTitle']       = "<a href='index.php?WebID=$WebID'>{$Class['WebTitle']}</a>";
            $i++;
        }

        $xoopsTpl->assign('discuss_data', $main_data);

        $xoopsTpl->assign('bar', $bar);
        $xoopsTpl->assign('isMineDiscuss', isMine());
        $xoopsTpl->assign('showWebTitleDiscuss', $showWebTitle);
        //$xoopsTpl->assign('WebID', $main_data);
    }
}
