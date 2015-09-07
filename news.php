<?php
/*-----------引入檔案區--------------*/
include_once "header.php";
if (!empty($_GET['WebID'])) {
    $xoopsOption['template_main'] = 'tad_web_news_b3.html';
} else {
    $xoopsOption['template_main'] = set_bootstrap('tad_web_news.html');
}

if (!defined('_NEWS_KIND')) {
    define("_NEWS_KIND", "news");
}

if (!defined('_NEWS_TOCAL_DESC')) {
    define("_NEWS_TOCAL_DESC", _MD_TCW_NEWS_TO_CAL);
}

if (!defined('_SHOW_NEWS_PLACE')) {
    define("_SHOW_NEWS_PLACE", false);
}

if (!defined('_SHOW_NEWS_MASTER')) {
    define("_SHOW_NEWS_MASTER", false);
}

if (!defined('_SHOW_NEWS_URL')) {
    define("_SHOW_NEWS_URL", true);
}

if (!defined('_SHOW_NEWS_TOCAL')) {
    define("_SHOW_NEWS_TOCAL", true);
}

if (!defined('_USE_NEWS_FORM_DATETIME')) {
    define("_USE_NEWS_FORM_DATETIME", false);
}

if (!defined('_USE_FCKEDITOR')) {
    define("_USE_FCKEDITOR", true);
}

if (!defined('_NEWS_NL2BR')) {
    define("_NEWS_NL2BR", false);
}

if (!defined('_SHOW_FULLCALENDAR')) {
    define("_SHOW_FULLCALENDAR", false);
}

if (!defined('_SHOW_FULLCALENDAR_COLNAME')) {
    define("_SHOW_FULLCALENDAR_COLNAME", "NewsDate");
}

if (!defined('_SHOW_NEWS_UPLOAD')) {
    define("_SHOW_NEWS_UPLOAD", true);
}

include_once XOOPS_ROOT_PATH . "/header.php";
/*-----------function區--------------*/

//tad_web_news編輯表單
function tad_web_news_form($NewsID = "")
{
    global $xoopsDB, $xoopsUser, $WebID, $MyWebs, $isMyWeb, $xoopsTpl, $TadUpFiles;

    if (!$isMyWeb and $MyWebs) {
        redirect_header($_SERVER['PHP_SELF'] . "?WebID={$MyWebs[0]}&op=tad_web_news_form", 3, _MD_TCW_AUTO_TO_HOME);
    } elseif (empty($MyWebs)) {
        redirect_header("index.php", 3, _MD_TCW_NOT_OWNER);
    }

    $Class = getWebInfo($WebID);

    //抓取預設值
    if (!empty($NewsID)) {
        $DBV = get_tad_web_news($NewsID);
    } else {
        $DBV = array();
    }

    //設定「NewsID」欄位預設值
    $NewsID = (!isset($DBV['NewsID'])) ? "" : $DBV['NewsID'];

    //設定「NewsTitle」欄位預設值
    if (isset($DBV['NewsTitle'])) {
        $NewsTitle = (_NEWS_KIND == "homework") ? $Class['WebTitle'] . date(" Y-m-d ") . _MD_TCW_HOMEWORK : $DBV['NewsTitle'];
    } else {
        $NewsTitle = (_NEWS_KIND == "homework") ? $Class['WebTitle'] . date(" Y-m-d ") . _MD_TCW_HOMEWORK : "";
    }

    //設定「NewsContent」欄位預設值
    if (isset($DBV['NewsContent'])) {
        $NewsContent = $DBV['NewsContent'];
    } else {
        $NewsContent = (_NEWS_KIND == "homework") ? _MD_TCW_HOMEWORK_DEFAULT : "";
    }

    //設定「NewsDate」欄位預設值
    $NewsDate = (!isset($DBV['NewsDate'])) ? date("Y-m-d H:i:s") : $DBV['NewsDate'];

    //設定「toCal」欄位預設值
    if (!isset($DBV['toCal'])) {
        $toCal = (_NEWS_KIND == "homework") ? date("Y-m-d") : "";
    } else {
        $toCal = ($DBV['toCal'] == "0000-00-00 00:00:00") ? "" : $DBV['toCal'];
    }

    //設定「NewsPlace」欄位預設值
    $NewsPlace = (!isset($DBV['NewsPlace'])) ? "" : $DBV['NewsPlace'];

    //設定「NewsMaster」欄位預設值
    $NewsMaster = (!isset($DBV['NewsMaster'])) ? "" : $DBV['NewsMaster'];

    //設定「NewsUrl」欄位預設值
    $NewsUrl = (!isset($DBV['NewsUrl'])) ? "" : $DBV['NewsUrl'];

    //設定「WebID」欄位預設值
    $WebID = (!isset($DBV['WebID'])) ? $WebID : $DBV['WebID'];

    //設定「NewsKind」欄位預設值
    $NewsKind = (!isset($DBV['NewsKind'])) ? "" : $DBV['NewsKind'];

    //設定「NewsCounter」欄位預設值
    $NewsCounter = (!isset($DBV['NewsCounter'])) ? "" : $DBV['NewsCounter'];

    $op = (empty($NewsID)) ? "insert_tad_web_news" : "update_tad_web_news";
    //$op="replace_tad_web_news";

    if (!file_exists(TADTOOLS_PATH . "/formValidator.php")) {
        redirect_header("index.php", 3, _MD_NEED_TADTOOLS);
    }
    include_once TADTOOLS_PATH . "/formValidator.php";
    $formValidator      = new formValidator("#myForm", true);
    $formValidator_code = $formValidator->render();

    if (_USE_FCKEDITOR) {
        include_once XOOPS_ROOT_PATH . "/modules/tadtools/ck.php";
        $ck = new CKEditor("tad_web", "NewsContent", $NewsContent);
        $ck->setHeight(300);
        $editor = $ck->render();
    } else {
        $editor = "<textarea name='NewsContent' id='NewsContent' class='span12'>$NewsContent</textarea>";
    }

    $_SHOW_NEWS_PLACE = $_SHOW_NEWS_MASTER = $_USE_NEWS_FORM_DATETIME = $_SHOW_NEWS_URL = $_SHOW_NEWS_TOCAL = $_SHOW_NEWS_UPLOAD = "";

    $xoopsTpl->assign('formValidator_code', $formValidator_code);
    $xoopsTpl->assign('op', $op);
    $xoopsTpl->assign('NewsID', $NewsID);
    $xoopsTpl->assign('NewsContent_editor', $editor);
    $xoopsTpl->assign('SHOW_NEWS_UPLOAD', _SHOW_NEWS_UPLOAD);
    $xoopsTpl->assign('SHOW_NEWS_TOCAL', _SHOW_NEWS_TOCAL);
    $xoopsTpl->assign('toCal', $toCal);
    $xoopsTpl->assign('USE_NEWS_FORM_DATETIME', _USE_NEWS_FORM_DATETIME);
    $xoopsTpl->assign('NewsDate', $NewsDate);
    $xoopsTpl->assign('SHOW_NEWS_URL', _SHOW_NEWS_URL);
    $xoopsTpl->assign('NewsUrl', $NewsUrl);
    $xoopsTpl->assign('SHOW_NEWS_MASTER', _SHOW_NEWS_MASTER);
    $xoopsTpl->assign('NewsMaster', $NewsMaster);
    $xoopsTpl->assign('SHOW_NEWS_PLACE', _SHOW_NEWS_PLACE);
    $xoopsTpl->assign('NewsPlace', $NewsPlace);
    $xoopsTpl->assign('NewsTitle', $NewsTitle);

    $TadUpFiles->set_col("NewsID", $NewsID);
    $upform = $TadUpFiles->upform();
    $xoopsTpl->assign('upform', $upform);
}

//新增資料到tad_web_news中
function insert_tad_web_news()
{
    global $xoopsDB, $xoopsUser, $TadUpFiles;

    $uid = $xoopsUser->getVar('uid');

    $myts                 = &MyTextSanitizer::getInstance();
    $_POST['NewsTitle']   = $myts->addSlashes($_POST['NewsTitle']);
    $_POST['NewsPlace']   = $myts->addSlashes($_POST['NewsPlace']);
    $_POST['NewsMaster']  = $myts->addSlashes($_POST['NewsMaster']);
    $_POST['NewsUrl']     = $myts->addSlashes($_POST['NewsUrl']);
    $_POST['NewsContent'] = $myts->addSlashes($_POST['NewsContent']);

    $newstime = (_USE_NEWS_FORM_DATETIME) ? $_POST['NewsDate'] : date("Y-m-d H:i:s");

    //if($_POST['NewsKind']=="law")$WebID=0;
    if (empty($_POST['toCal'])) {
        $_POST['toCal'] = "0000-00-00 00:00:00";
    }

    $sql = "insert into " . $xoopsDB->prefix("tad_web_news") . "
	(`NewsTitle` , `NewsContent` , `NewsDate` , `toCal` , `NewsPlace` , `NewsMaster` , `NewsUrl` , `WebID` , `NewsKind` , `NewsCounter` , `uid`)
	values('{$_POST['NewsTitle']}' , '{$_POST['NewsContent']}' , '{$newstime}' , '{$_POST['toCal']}' , '{$_POST['NewsPlace']}' , '{$_POST['NewsMaster']}' , '{$_POST['NewsUrl']}' , '{$_POST['WebID']}' , '{$_POST['NewsKind']}' , '0' , '{$uid}')";
    //die($sql);
    $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'], 3, mysql_error());

    //取得最後新增資料的流水編號
    $NewsID = $xoopsDB->getInsertId();

    $TadUpFiles->set_col("NewsID", $NewsID);
    $TadUpFiles->upload_file('upfile', 640, null, null, null, true);

    return $NewsID;
}

//更新tad_web_news某一筆資料
function update_tad_web_news($NewsID = "")
{
    global $xoopsDB, $xoopsUser, $TadUpFiles;

    $myts                 = &MyTextSanitizer::getInstance();
    $_POST['NewsTitle']   = $myts->addSlashes($_POST['NewsTitle']);
    $_POST['NewsPlace']   = $myts->addSlashes($_POST['NewsPlace']);
    $_POST['NewsMaster']  = $myts->addSlashes($_POST['NewsMaster']);
    $_POST['NewsUrl']     = $myts->addSlashes($_POST['NewsUrl']);
    $_POST['NewsContent'] = $myts->addSlashes($_POST['NewsContent']);

    $newstime = (_USE_NEWS_FORM_DATETIME) ? $_POST['NewsDate'] : date("Y-m-d H:i:s");

    $anduid = onlyMine();

    if ($_POST['NewsKind'] == "law") {
        $WebID = 0;
    }

    if (empty($_POST['toCal'])) {
        $_POST['toCal'] = "0000-00-00 00:00:00";
    }

    $sql = "update " . $xoopsDB->prefix("tad_web_news") . " set
	 `NewsTitle` = '{$_POST['NewsTitle']}' ,
	 `NewsContent` = '{$_POST['NewsContent']}' ,
	 `NewsDate` = '{$newstime}' ,
	 `toCal` = '{$_POST['toCal']}' ,
	 `NewsPlace` = '{$_POST['NewsPlace']}' ,
	 `NewsMaster` = '{$_POST['NewsMaster']}' ,
	 `NewsUrl` = '{$_POST['NewsUrl']}'
	where NewsID='$NewsID' $anduid";
    $xoopsDB->queryF($sql) or redirect_header($_SERVER['PHP_SELF'], 3, mysql_error());

    $TadUpFiles->set_col("NewsID", $NewsID);
    $TadUpFiles->upload_file('upfile', 640, null, null, null, true);

    return $NewsID;
}

//以流水號秀出某筆tad_web_news資料內容
function show_one_tad_web_news($NewsID = "", $show_place = false, $nl2br = false)
{
    global $xoopsDB, $WebID, $isAdmin, $xoopsTpl, $TadUpFiles;
    if (empty($NewsID)) {
        return;
    } else {
        $NewsID = intval($NewsID);
    }
    add_tad_web_news_counter($NewsID);

    $sql    = "select * from " . $xoopsDB->prefix("tad_web_news") . " where NewsID='{$NewsID}'";
    $result = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'], 3, mysql_error());
    $all    = $xoopsDB->fetchArray($result);

    //以下會產生這些變數： $NewsID , $NewsTitle , $NewsContent , $NewsDate , $toCal , $NewsPlace , $NewsMaster , $NewsUrl , $WebID , $NewsKind , $NewsCounter ,$uid
    foreach ($all as $k => $v) {
        $$k = $v;
    }

    if (empty($uid)) {
        redirect_header('index.php', 3, _MD_TCW_NEWS_NOT_EXIST);
    }

    $uid_name = XoopsUser::getUnameFromId($uid, 1);

    $NewsUrlTxt = empty($NewsUrl) ? "" : "<div>" . _MD_TCW_NEWSURL . _TAD_FOR . "<a href='$NewsUrl' target='_blank'>$NewsUrl</a></div>";

    $ShowCal = ($toCal == "0000-00-00 00:00:00") ? "" : _MD_TCW_NEWSDATE . _TAD_FOR . $toCal;

    $NewsContent = ($nl2br) ? nl2br($NewsContent) : $NewsContent;

    $learn_info = "";
    if ($show_place) {
        $showNewsMaster = empty($NewsMaster) ? "" : _MD_TCW_NEWS_TEACHER . _TAD_FOR . "<u>{$NewsMaster}</u>";
        $learn_info     = "<table><tr><td>" . _MD_TCW_NEWSPLACE . _TAD_FOR . "<u>{$NewsPlace}</u></td><td>$showNewsMaster</td></tr></table>";
    }

    $TadUpFiles->set_col("NewsID", $NewsID);
    $NewsFiles = $TadUpFiles->show_files('upfile', true, null, true);

    $xoopsTpl->assign('isMine', isMine());
    $xoopsTpl->assign('mode', 'one_news');
    $xoopsTpl->assign('NewsTitle', $NewsTitle);
    $xoopsTpl->assign('NewsUrlTxt', $NewsUrlTxt);
    $xoopsTpl->assign('NewsContent', $NewsContent);
    $xoopsTpl->assign('ShowCal', $ShowCal);
    $xoopsTpl->assign('learn_info', $learn_info);
    $xoopsTpl->assign('uid_name', $uid_name);
    $xoopsTpl->assign('NewsDate', $NewsDate);
    $xoopsTpl->assign('NewsCounter', $NewsCounter);
    $xoopsTpl->assign('NewsFiles', $NewsFiles);
    $xoopsTpl->assign('NewsID', $NewsID);
    $xoopsTpl->assign('NewsInfo', sprintf(_MD_TCW_INFO, $uid_name, $NewsDate, $NewsCounter));

}

//新增tad_web_news計數器
function add_tad_web_news_counter($NewsID = '')
{
    global $xoopsDB;
    $sql = "update " . $xoopsDB->prefix("tad_web_news") . " set `NewsCounter`=`NewsCounter`+1 where `NewsID`='{$NewsID}'";
    $xoopsDB->queryF($sql) or redirect_header($_SERVER['PHP_SELF'], 3, mysql_error());
}

//刪除tad_web_news某筆資料資料
function delete_tad_web_news($NewsID = "")
{
    global $xoopsDB, $xoopsUser, $TadUpFiles;
    $anduid = onlyMine();
    $sql    = "delete from " . $xoopsDB->prefix("tad_web_news") . " where NewsID='$NewsID' $anduid";
    $xoopsDB->queryF($sql) or redirect_header($_SERVER['PHP_SELF'], 3, mysql_error());
    $TadUpFiles->set_col("NewsID", $NewsID);
    $TadUpFiles->del_files();
}

//以流水號取得某筆tad_web_news資料
function get_tad_web_news($NewsID = "")
{
    global $xoopsDB;
    if (empty($NewsID)) {
        return;
    }

    $sql    = "select * from " . $xoopsDB->prefix("tad_web_news") . " where NewsID='$NewsID'";
    $result = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'], 3, mysql_error());
    $data   = $xoopsDB->fetchArray($result);
    return $data;
}

/*-----------執行動作判斷區----------*/
include_once $GLOBALS['xoops']->path('/modules/system/include/functions.php');
$op     = system_CleanVars($_REQUEST, 'op', '', 'string');
$NewsID = system_CleanVars($_REQUEST, 'NewsID', 0, 'int');

$xoopsTpl->assign("news_kind", _NEWS_KIND);

common_template($WebID);

switch ($op) {
    //替換資料
    case "replace_tad_web_news":
        replace_tad_web_news();
        header("location: {$_SERVER['PHP_SELF']}?WebID={$WebID}");
        exit;
        break;

    //新增資料
    case "insert_tad_web_news":
        $NewsID = insert_tad_web_news();
        header("location: {$_SERVER['PHP_SELF']}?WebID=$WebID&NewsID=$NewsID");
        exit;
        break;

    //更新資料
    case "update_tad_web_news":
        update_tad_web_news($NewsID);
        header("location: {$_SERVER['PHP_SELF']}?WebID=$WebID&NewsID={$NewsID}");
        exit;
        break;

    //輸入表格
    case "tad_web_news_form":
        tad_web_news_form($NewsID);
        break;

    //刪除資料
    case "delete_tad_web_news":
        delete_tad_web_news($NewsID);
        header("location: {$_SERVER['PHP_SELF']}?WebID={$WebID}");
        exit;
        break;

    //下載檔案
    case "tufdl":
        $files_sn = isset($_GET['files_sn']) ? intval($_GET['files_sn']) : "";
        $TadUpFiles->add_file_counter($files_sn);
        exit;
        break;

    //預設動作
    default:
        if (empty($NewsID)) {
            list_tad_web_news($WebID, _NEWS_KIND, null);

            if (_SHOW_FULLCALENDAR) {
                $xoopsTpl->assign('show_fullcalendar', true);
            }
        } else {
            show_one_tad_web_news($NewsID, _SHOW_NEWS_PLACE, _NEWS_NL2BR);
        }
        break;

}

/*-----------秀出結果區--------------*/
include_once XOOPS_ROOT_PATH . '/footer.php';
