<?php
/*-----------引入檔案區--------------*/
include_once "header.php";
if (!empty($_GET['WebID'])) {
    $xoopsOption['template_main'] = 'tad_web_video_b3.html';
} else {
    $xoopsOption['template_main'] = set_bootstrap('tad_web_video.html');
}
include_once XOOPS_ROOT_PATH . "/header.php";
/*-----------function區--------------*/

//tad_web_video編輯表單
function tad_web_video_form($VideoID = "")
{
    global $xoopsDB, $xoopsUser, $WebID, $MyWebs, $isMyWeb, $xoopsTpl;

    if (!$isMyWeb and $MyWebs) {
        redirect_header($_SERVER['PHP_SELF'] . "?WebID={$MyWebs[0]}&op=tad_web_video_form", 3, _MD_TCW_AUTO_TO_HOME);
    } elseif (empty($MyWebs)) {
        redirect_header("index.php", 3, _MD_TCW_NOT_OWNER);
    }

    //抓取預設值
    if (!empty($VideoID)) {
        $DBV = get_tad_web_video($VideoID);
    } else {
        $DBV = array();
    }

    //預設值設定

    //設定「VideoID」欄位預設值
    $VideoID = (!isset($DBV['VideoID'])) ? "" : $DBV['VideoID'];

    //設定「VideoName」欄位預設值
    $VideoName = (!isset($DBV['VideoName'])) ? "" : $DBV['VideoName'];

    //設定「VideoDesc」欄位預設值
    $VideoDesc = (!isset($DBV['VideoDesc'])) ? "" : $DBV['VideoDesc'];

    //設定「VideoDate」欄位預設值
    $VideoDate = (!isset($DBV['VideoDate'])) ? "" : $DBV['VideoDate'];

    //設定「VideoPlace」欄位預設值
    $VideoPlace = (!isset($DBV['VideoPlace'])) ? "" : $DBV['VideoPlace'];

    //設定「uid」欄位預設值
    $user_uid = ($xoopsUser) ? $xoopsUser->getVar('uid') : "";
    $uid      = (!isset($DBV['uid'])) ? $user_uid : $DBV['uid'];

    //設定「WebID」欄位預設值
    $WebID = (!isset($DBV['WebID'])) ? $WebID : $DBV['WebID'];

    //設定「VideoCount」欄位預設值
    $VideoCount = (!isset($DBV['VideoCount'])) ? "" : $DBV['VideoCount'];

    //設定「Youtube」欄位預設值
    $Youtube = (!isset($DBV['Youtube'])) ? "" : $DBV['Youtube'];

    $op = (empty($VideoID)) ? "insert_tad_web_video" : "update_tad_web_video";
    //$op="replace_tad_web_video";

    if (!file_exists(TADTOOLS_PATH . "/formValidator.php")) {
        redirect_header("index.php", 3, _MD_NEED_TADTOOLS);
    }
    include_once TADTOOLS_PATH . "/formValidator.php";
    $formValidator      = new formValidator("#myForm", true);
    $formValidator_code = $formValidator->render();

    $xoopsTpl->assign('formValidator_code', $formValidator_code);
    $xoopsTpl->assign('Youtube', $Youtube);
    $xoopsTpl->assign('VideoName', $VideoName);
    $xoopsTpl->assign('VideoDesc', $VideoDesc);
    $xoopsTpl->assign('VideoID', $VideoID);
    $xoopsTpl->assign('WebID', $WebID);
    $xoopsTpl->assign('next_op', $op);
    $xoopsTpl->assign('op', 'tad_web_video_form');

}

//新增資料到tad_web_video中
function insert_tad_web_video()
{
    global $xoopsDB, $xoopsUser;

    //取得使用者編號
    $uid = ($xoopsUser) ? $xoopsUser->getVar('uid') : "";

    $myts               = &MyTextSanitizer::getInstance();
    $_POST['VideoName'] = $myts->addSlashes($_POST['VideoName']);
    $_POST['VideoDesc'] = $myts->addSlashes($_POST['VideoDesc']);

    $VideoPlace          = tad_web_getYTid($_POST['Youtube']);
    $_POST['VideoCount'] = intval($_POST['VideoCount']);
    $sql                 = "insert into " . $xoopsDB->prefix("tad_web_video") . "
	(`VideoName` , `VideoDesc` , `VideoDate` , `VideoPlace` , `uid` , `WebID` , `VideoCount` , `Youtube`)
	values('{$_POST['VideoName']}' , '{$_POST['VideoDesc']}' , now() , '{$VideoPlace}' , '{$uid}' , '{$_POST['WebID']}' , '{$_POST['VideoCount']}' , '{$_POST['Youtube']}')";
    $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'], 3, mysql_error());

    //取得最後新增資料的流水編號
    $VideoID = $xoopsDB->getInsertId();
    return $VideoID;
}

//抓取 Youtube ID
function tad_web_getYTid($ytURL = "")
{
    if (substr($ytURL, 0, 16) == 'http://youtu.be/') {
        return substr($ytURL, 16);
    } else {
        parse_str(parse_url($ytURL, PHP_URL_QUERY), $params);
        return $params['v'];
    }
}

//更新tad_web_video某一筆資料
function update_tad_web_video($VideoID = "")
{
    global $xoopsDB, $xoopsUser, $isAdmin;

    $myts               = &MyTextSanitizer::getInstance();
    $_POST['VideoName'] = $myts->addSlashes($_POST['VideoName']);
    $_POST['VideoDesc'] = $myts->addSlashes($_POST['VideoDesc']);
    $VideoPlace         = tad_web_getYTid($_POST['Youtube']);

    $anduid = onlyMine();

    $_POST['VideoCount'] = intval($_POST['VideoCount']);
    $sql                 = "update " . $xoopsDB->prefix("tad_web_video") . " set
	 `VideoName` = '{$_POST['VideoName']}' ,
	 `VideoDesc` = '{$_POST['VideoDesc']}' ,
	 `VideoDate` = now() ,
	 `VideoPlace` = '{$VideoPlace}'
	where VideoID='$VideoID' $anduid";
    $xoopsDB->queryF($sql) or redirect_header($_SERVER['PHP_SELF'], 3, mysql_error());
    return $VideoID;
}

//新增tad_web_video計數器
function add_tad_web_video_counter($VideoID = '')
{
    global $xoopsDB;
    $sql = "update " . $xoopsDB->prefix("tad_web_video") . " set `VideoCount`=`VideoCount`+1 where `VideoID`='{$VideoID}'";
    $xoopsDB->queryF($sql) or redirect_header($_SERVER['PHP_SELF'], 3, mysql_error());
}

//以流水號取得某筆tad_web_video資料
function get_tad_web_video($VideoID = "")
{
    global $xoopsDB;
    if (empty($VideoID)) {
        return;
    }

    $sql    = "select * from " . $xoopsDB->prefix("tad_web_video") . " where VideoID='$VideoID'";
    $result = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'], 3, mysql_error());
    $data   = $xoopsDB->fetchArray($result);
    return $data;
}

//刪除tad_web_video某筆資料資料
function delete_tad_web_video($VideoID = "")
{
    global $xoopsDB, $xoopsUser;
    $anduid = onlyMine();
    $sql    = "delete from " . $xoopsDB->prefix("tad_web_video") . " where VideoID='$VideoID' $anduid";
    $xoopsDB->queryF($sql) or redirect_header($_SERVER['PHP_SELF'], 3, mysql_error());
}

//以流水號秀出某筆tad_web_video資料內容
function show_one_tad_web_video($VideoID = "")
{
    global $xoopsDB, $xoopsTpl;
    if (empty($VideoID)) {
        return;
    } else {
        $VideoID = intval($VideoID);
    }

    add_tad_web_video_counter($VideoID);

    $sql    = "select * from " . $xoopsDB->prefix("tad_web_video") . " where VideoID='{$VideoID}'";
    $result = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'], 3, mysql_error());
    $all    = $xoopsDB->fetchArray($result);

    //以下會產生這些變數： $VideoID , $VideoName , $VideoDesc , $VideoDate , $VideoPlace , $uid , $WebID , $VideoCount
    foreach ($all as $k => $v) {
        $$k = $v;
    }

    $url      = "http://www.youtube.com/oembed?url=http%3A%2F%2Fwww.youtube.com%2Fwatch%3Fv%3D{$VideoPlace}&format=json";
    $contents = file_get_contents($url);
    $contents = utf8_encode($contents);
//echo "$contents";
    $results = json_decode($contents, false);
    foreach ($results as $k => $v) {
        $$k = htmlspecialchars($v);
        //echo "$k = $v<br>";
    }

    $rate = round($height / $width, 2);
    //die("$rate=$height/$width");

    if (!file_exists(XOOPS_ROOT_PATH . "/modules/tadtools/jwplayer_new.php")) {
        redirect_header("index.php", 3, _MD_NEED_TADTOOLS);
    }
    include_once XOOPS_ROOT_PATH . "/modules/tadtools/jwplayer_new.php";
    $jw     = new JwPlayer("video{$VideoID}", $Youtube, "http://i3.ytimg.com/vi/{$VideoPlace}/0.jpg", '100%', $rate);
    $player = $jw->render();

    $uid_name = XoopsUser::getUnameFromId($uid, 1);

    $xoopsTpl->assign('isMineVideo', isMine());
    $xoopsTpl->assign('VideoName', $VideoName);
    $xoopsTpl->assign('VideoDate', $VideoDate);
    $xoopsTpl->assign('VideoPlace', $VideoPlace);
    $xoopsTpl->assign('VideoDesc', nl2br($VideoDesc));
    $xoopsTpl->assign('uid_name', $uid_name);
    $xoopsTpl->assign('VideoCountInfo', sprintf(_MD_TCW_VIDEOCOUNTINFO, $VideoCount));
    $xoopsTpl->assign('player', $player);
    $xoopsTpl->assign('op', 'show_one_tad_web_video');
    $xoopsTpl->assign('VideoID', $VideoID);
    $xoopsTpl->assign('VideoInfo', sprintf(_MD_TCW_INFO, $uid_name, $VideoDate, $VideoCount));
}

/*-----------執行動作判斷區----------*/
include_once $GLOBALS['xoops']->path('/modules/system/include/functions.php');
$op      = system_CleanVars($_REQUEST, 'op', '', 'string');
$VideoID = system_CleanVars($_REQUEST, 'VideoID', 0, 'int');

common_template($WebID);

switch ($op) {
    //替換資料
    case "replace_tad_web_video":
        replace_tad_web_video();
        header("location: {$_SERVER['PHP_SELF']}?WebID={$WebID}");
        exit;
        break;

    //新增資料
    case "insert_tad_web_video":
        $VideoID = insert_tad_web_video();
        header("location: {$_SERVER['PHP_SELF']}?WebID={$WebID}&VideoID=$VideoID");
        exit;
        break;

    //更新資料
    case "update_tad_web_video":
        update_tad_web_video($VideoID);
        header("location: {$_SERVER['PHP_SELF']}?WebID={$WebID}");
        exit;
        break;
    //輸入表格
    case "tad_web_video_form":
        tad_web_video_form($VideoID);
        break;

    //刪除資料
    case "delete_tad_web_video":
        delete_tad_web_video($VideoID);
        header("location: {$_SERVER['PHP_SELF']}?WebID={$WebID}");
        exit;
        break;

    //預設動作
    default:
        if (empty($VideoID)) {
            list_tad_web_video($WebID);
            //$main.=tad_web_video_form($VideoID);
        } else {
            show_one_tad_web_video($VideoID);
        }
        break;

}

/*-----------秀出結果區--------------*/
include_once XOOPS_ROOT_PATH . '/footer.php';
