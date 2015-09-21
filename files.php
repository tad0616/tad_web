<?php
/*-----------引入檔案區--------------*/
include_once "header.php";
$web_cate = new web_cate($WebID, "files", "tad_web_files");
if (!empty($_GET['WebID'])) {
    $xoopsOption['template_main'] = 'tad_web_file_b3.html';
} else {
    $xoopsOption['template_main'] = set_bootstrap('tad_web_file.html');
}

define("_ONLY_USER", false);
//if(!$xoopsUser and _ONLY_USER) redirect_header("index.php",3, "登入後才能使用此功能。");
include_once XOOPS_ROOT_PATH . "/header.php";
/*-----------function區--------------*/

//tad_web_files編輯表單
function tad_web_files_form($fsn = "", $WebID = "")
{
    global $xoopsDB, $xoopsUser, $xoopsModuleConfig, $isAdmin, $MyWebs, $xoopsTpl, $isMyWeb, $TadUpFiles, $web_cate;

    if (!$isMyWeb and $MyWebs) {
        redirect_header($_SERVER['PHP_SELF'] . "?WebID={$MyWebs[0]}&op=tad_web_files_form", 3, _MD_TCW_AUTO_TO_HOME);
    } elseif (empty($MyWebs)) {
        redirect_header("index.php", 3, _MD_TCW_NOT_OWNER);
    }

    //抓取預設值
    if (!empty($fsn)) {
        $DBV = get_tad_web_files($fsn);
    } else {
        $DBV = array();
    }

    //預設值設定

    //設定「fsn」欄位預設值
    $fsn = (!isset($DBV['fsn'])) ? "" : $DBV['fsn'];

    //設定「uid」欄位預設值
    $user_uid = ($xoopsUser) ? $xoopsUser->getVar('uid') : "";

    $uid = (!isset($DBV['uid'])) ? $user_uid : $DBV['uid'];

    //設定「CateID」欄位預設值
    $CateID = (!isset($DBV['CateID'])) ? "" : $DBV['CateID'];

    //設定「file_date」欄位預設值
    $file_date = (!isset($DBV['file_date'])) ? date("Y-m-d H:i:s") : $DBV['file_date'];

    //設定「WebID」欄位預設值
    $WebID = (!isset($DBV['WebID'])) ? $WebID : $DBV['WebID'];

    //設定「CateID」欄位預設值
    $CateID    = (!isset($DBV['CateID'])) ? "" : $DBV['CateID'];
    $cate_menu = $web_cate->cate_menu($CateID);
    $xoopsTpl->assign('cate_menu', $cate_menu);

    $op = (empty($fsn)) ? "insert_tad_web_files" : "update_tad_web_files";

    $xoopsTpl->assign('WebID', $WebID);
    $xoopsTpl->assign('fsn', $fsn);

    $xoopsTpl->assign('next_op', $op);
    $xoopsTpl->assign('op', 'tad_web_files_form');

    $TadUpFiles->set_col("fsn", $fsn);
    $upform = $TadUpFiles->upform();
    $xoopsTpl->assign('upform', $upform);
}

//新增資料到tad_web_files中
function insert_tad_web_files()
{
    global $xoopsDB, $xoopsUser, $TadUpFiles, $web_cate;

    //取得使用者編號
    $uid = ($xoopsUser) ? $xoopsUser->getVar('uid') : "";

    $myts = &MyTextSanitizer::getInstance();

    $_POST['WebID'] = intval($_POST['WebID']);

    $CateID = $web_cate->save_tad_web_cate($_POST['CateID'], $_POST['newCateName']);

    $sql = "insert into " . $xoopsDB->prefix("tad_web_files") . "
  (`uid` , `CateID` , `file_date`  , `WebID`)
  values('{$uid}' , '{$CateID}' , now()  , '{$_POST['WebID']}')";

    $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'], 3, mysql_error());

    //取得最後新增資料的流水編號
    $fsn = $xoopsDB->getInsertId();

    $TadUpFiles->set_col('fsn', $fsn);
    $TadUpFiles->upload_file('upfile', 640, null, null, null, true);
    return $fsn;
}

//更新tad_web_files某一筆資料
function update_tad_web_files($fsn = "")
{
    global $xoopsDB, $xoopsUser, $TadUpFiles, $web_cate;

    $myts = &MyTextSanitizer::getInstance();

    $anduid = onlyMine();

    $_POST['CateID'] = intval($_POST['CateID']);
    $_POST['WebID']  = intval($_POST['WebID']);

    $CateID = $web_cate->save_tad_web_cate($_POST['CateID'], $_POST['newCateName']);
    $sql    = "update " . $xoopsDB->prefix("tad_web_files") . " set
   `CateID` = '{$CateID}' ,
   `file_date` = now() ,
   `WebID` = '{$_POST['WebID']}'
    where fsn='$fsn' $anduid";
    $xoopsDB->queryF($sql) or redirect_header($_SERVER['PHP_SELF'], 3, mysql_error());

    $TadUpFiles->set_col('fsn', $fsn);
    $TadUpFiles->upload_file('upfile', 640, null, null, null, true);
    return $fsn;
}

//以流水號取得某筆tad_web_files資料
function get_tad_web_files($fsn = "")
{
    global $xoopsDB;
    if (empty($fsn)) {
        return;
    }

    $sql    = "select * from " . $xoopsDB->prefix("tad_web_files") . " where fsn='$fsn'";
    $result = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'], 3, mysql_error());
    $data   = $xoopsDB->fetchArray($result);
    return $data;
}

//刪除tad_web_files某筆資料資料
function delete_tad_web_files($fsn = "")
{
    global $xoopsDB, $xoopsUser, $TadUpFiles;
    $anduid = onlyMine();
    $sql    = "delete from " . $xoopsDB->prefix("tad_web_files") . " where fsn='$fsn' $anduid";
    $xoopsDB->queryF($sql) or redirect_header($_SERVER['PHP_SELF'], 3, mysql_error());
    $TadUpFiles->set_col("fsn", $fsn);
    $TadUpFiles->del_files();
}

/*-----------執行動作判斷區----------*/
include_once $GLOBALS['xoops']->path('/modules/system/include/functions.php');
$op       = system_CleanVars($_REQUEST, 'op', '', 'string');
$files_sn = system_CleanVars($_REQUEST, 'files_sn', 0, 'int');
$fsn      = system_CleanVars($_REQUEST, 'fsn', 0, 'int');
$CateID   = system_CleanVars($_REQUEST, 'CateID', 0, 'int');

common_template($WebID);

switch ($op) {

    //新增資料
    case "insert_tad_web_files":
        $fsn = insert_tad_web_files();
        header("location: {$_SERVER['PHP_SELF']}?WebID={$WebID}");
        exit;
        break;

    //更新資料
    case "update_tad_web_files":
        update_tad_web_files($fsn);
        header("location: {$_SERVER['PHP_SELF']}?WebID={$WebID}");
        exit;
        break;

    //輸入表格
    case "tad_web_files_form":
        tad_web_files_form($fsn, $WebID);
        break;

    //刪除資料
    case "delete_tad_web_files":
        delete_tad_web_files($fsn);
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
        if (empty($fsn)) {
            list_tad_web_files($WebID, $CateID);
        }
        break;

}

/*-----------秀出結果區--------------*/
include_once 'footer.php';
include_once XOOPS_ROOT_PATH . '/footer.php';
