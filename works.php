<?php
/*-----------引入檔案區--------------*/
include_once "header.php";
$web_cate = new web_cate($WebID, "works", "tad_web_works");
if (!empty($_GET['WebID'])) {
    $xoopsOption['template_main'] = 'tad_web_works_b3.html';
} else {
    $xoopsOption['template_main'] = set_bootstrap('tad_web_works.html');
}
include_once XOOPS_ROOT_PATH . "/header.php";
/*-----------function區--------------*/

//tad_web_works編輯表單
function tad_web_works_form($WorksID = "")
{
    global $xoopsDB, $xoopsUser, $WebID, $MyWebs, $isMyWeb, $xoopsTpl, $TadUpFiles, $web_cate;

    if (!$isMyWeb and $MyWebs) {
        redirect_header($_SERVER['PHP_SELF'] . "?op=WebID={$MyWebs[0]}&tad_web_works_form", 3, _MD_TCW_AUTO_TO_HOME);
    } elseif (empty($MyWebs)) {
        redirect_header("index.php", 3, _MD_TCW_NOT_OWNER);
    }

    //抓取預設值
    if (!empty($WorksID)) {
        $DBV = get_tad_web_works($WorksID);
    } else {
        $DBV = array();
    }

    //預設值設定

    //設定「WorksID」欄位預設值
    $WorksID = (!isset($DBV['WorksID'])) ? $WorksID : $DBV['WorksID'];

    //設定「WorkName」欄位預設值
    $WorkName = (!isset($DBV['WorkName'])) ? "" : $DBV['WorkName'];

    //設定「WorkDesc」欄位預設值
    $WorkDesc = (!isset($DBV['WorkDesc'])) ? "" : $DBV['WorkDesc'];

    //設定「WorksDate」欄位預設值
    $WorksDate = (!isset($DBV['WorksDate'])) ? date("Y-m-d") : $DBV['WorksDate'];

    //設定「uid」欄位預設值
    $user_uid = ($xoopsUser) ? $xoopsUser->getVar('uid') : "";
    $uid      = (!isset($DBV['uid'])) ? $user_uid : $DBV['uid'];

    //設定「WebID」欄位預設值
    $WebID = (!isset($DBV['WebID'])) ? $WebID : $DBV['WebID'];

    //設定「CateID」欄位預設值
    $CateID = (!isset($DBV['CateID'])) ? '' : $DBV['CateID'];

    //設定「WorksCount」欄位預設值
    $WorksCount = (!isset($DBV['WorksCount'])) ? "" : $DBV['WorksCount'];

    //設定「CateID」欄位預設值
    $CateID    = (!isset($DBV['CateID'])) ? "" : $DBV['CateID'];
    $cate_menu = $web_cate->cate_menu($CateID);
    $xoopsTpl->assign('cate_menu', $cate_menu);

    $op = (empty($WorksID)) ? "insert_tad_web_works" : "update_tad_web_works";

    if (!file_exists(TADTOOLS_PATH . "/formValidator.php")) {
        redirect_header("index.php", 3, _MD_NEED_TADTOOLS);
    }
    include_once TADTOOLS_PATH . "/formValidator.php";
    $formValidator      = new formValidator("#myForm", true);
    $formValidator_code = $formValidator->render();

    $xoopsTpl->assign('formValidator_code', $formValidator_code);
    $xoopsTpl->assign('WorkName', $WorkName);
    $xoopsTpl->assign('WorkDesc', $WorkDesc);
    $xoopsTpl->assign('WorksDate', $WorksDate);
    //$xoopsTpl->assign('list_del_file',upfile::list_del_file("WorksID",$WorksID,true));
    $xoopsTpl->assign('WorksID', $WorksID);
    $xoopsTpl->assign('WebID', $WebID);
    $xoopsTpl->assign('CateID', $CateID);
    $xoopsTpl->assign('next_op', $op);
    $xoopsTpl->assign('op', 'tad_web_works_form');

    $TadUpFiles->set_col('WorksID', $WorksID); //若 $show_list_del_file ==true 時一定要有
    $upform = $TadUpFiles->upform(true, 'upfile');
    $xoopsTpl->assign('upform', $upform);

}

//新增資料到tad_web_works中
function insert_tad_web_works()
{
    global $xoopsDB, $xoopsUser, $TadUpFiles, $web_cate;

    //取得使用者編號
    $uid = ($xoopsUser) ? $xoopsUser->getVar('uid') : "";

    $myts              = &MyTextSanitizer::getInstance();
    $_POST['WorkName'] = $myts->addSlashes($_POST['WorkName']);
    $_POST['WorkDesc'] = $myts->addSlashes($_POST['WorkDesc']);

    $CateID = $web_cate->save_tad_web_cate($_POST['CateID'], $_POST['newCateName']);

    $sql = "insert into " . $xoopsDB->prefix("tad_web_works") . "
    (`CateID`,`WorkName` , `WorkDesc` , `WorksDate` ,  `uid` , `WebID` , `WorksCount`)
    values('{$CateID}' , '{$_POST['WorkName']}' , '{$_POST['WorkDesc']}' , '{$_POST['WorksDate']}' , '{$uid}' , '{$_POST['WebID']}' , '0')";
    $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'], 3, mysql_error());

    //取得最後新增資料的流水編號
    $WorksID = $xoopsDB->getInsertId();

    $TadUpFiles->set_col('WorksID', $WorksID);
    $TadUpFiles->upload_file('upfile', 800, null, null, null, true);

    return $WorksID;
}

//更新tad_web_works某一筆資料
function update_tad_web_works($WorksID = "")
{
    global $xoopsDB, $xoopsUser, $isAdmin, $MyWebs, $TadUpFiles, $web_cate;

    $myts              = &MyTextSanitizer::getInstance();
    $_POST['WorkName'] = $myts->addSlashes($_POST['WorkName']);
    $_POST['WorkDesc'] = $myts->addSlashes($_POST['WorkDesc']);

    $anduid = onlyMine();

    $_POST['WorksCount'] = intval($_POST['WorksCount']);

    $CateID = $web_cate->save_tad_web_cate($_POST['CateID'], $_POST['newCateName']);
    $sql    = "update " . $xoopsDB->prefix("tad_web_works") . " set
     `CateID` = '{$CateID}' ,
     `WorkName` = '{$_POST['WorkName']}' ,
     `WorkDesc` = '{$_POST['WorkDesc']}' ,
     `WorksDate` = '{$_POST['WorksDate']}'
    where WorksID='$WorksID' $anduid";
    $xoopsDB->queryF($sql) or redirect_header($_SERVER['PHP_SELF'], 3, mysql_error());

    $TadUpFiles->set_col('WorksID', $WorksID);
    $TadUpFiles->upload_file('upfile', 800, null, null, null, true);

    return $WorksID;
}

//新增tad_web_works計數器
function add_tad_web_works_counter($WorksID = '')
{
    global $xoopsDB;
    $sql = "update " . $xoopsDB->prefix("tad_web_works") . " set `WorksCount`=`WorksCount`+1 where `WorksID`='{$WorksID}'";
    $xoopsDB->queryF($sql) or redirect_header($_SERVER['PHP_SELF'], 3, mysql_error());
}

//以流水號取得某筆tad_web_works資料
function get_tad_web_works($WorksID = "")
{
    global $xoopsDB;
    if (empty($WorksID)) {
        return;
    }

    $sql    = "select * from " . $xoopsDB->prefix("tad_web_works") . " where WorksID='$WorksID'";
    $result = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'], 3, mysql_error());
    $data   = $xoopsDB->fetchArray($result);
    return $data;
}

//刪除tad_web_works某筆資料資料
function delete_tad_web_works($WorksID = "")
{
    global $xoopsDB, $xoopsUser;
    $anduid = onlyMine();
    $sql    = "delete from " . $xoopsDB->prefix("tad_web_works") . " where WorksID='$WorksID' $anduid";
    $xoopsDB->queryF($sql) or redirect_header($_SERVER['PHP_SELF'], 3, mysql_error());
}

//以流水號秀出某筆tad_web_works資料內容
function show_one_tad_web_works($WorksID = "")
{
    global $xoopsDB, $xoopsTpl, $TadUpFiles, $web_cate;
    if (empty($WorksID)) {
        return;
    } else {
        $WorksID = intval($WorksID);
    }

    add_tad_web_works_counter($WorksID);

    $sql    = "select * from " . $xoopsDB->prefix("tad_web_works") . " where WorksID='{$WorksID}'";
    $result = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'], 3, mysql_error());
    $all    = $xoopsDB->fetchArray($result);

    //以下會產生這些變數： $WorksID , $WorkName , $WorkDesc , $WorksDate , $uid , $WebID , $WorksCount
    foreach ($all as $k => $v) {
        $$k = $v;
    }

    $TadUpFiles->set_col("WorksID", $WorksID);
    $pics = $TadUpFiles->show_files('upfile', true, null, true); //是否縮圖,顯示模式 filename、small,顯示描述,顯示下載次數

    $uid_name  = XoopsUser::getUnameFromId($uid, 1);
    $WorksDate = str_replace(' 00:00:00', '', $WorksDate);
    $xoopsTpl->assign('isMineWorks', isMine());
    $xoopsTpl->assign('WorkName', $WorkName);
    $xoopsTpl->assign('WorksDate', $WorksDate);
    $xoopsTpl->assign('WorkDesc', nl2br($WorkDesc));
    $xoopsTpl->assign('uid_name', $uid_name);
    $xoopsTpl->assign('WorksCount', $WorksCount);
    $xoopsTpl->assign('pics', $pics);
    $xoopsTpl->assign('op', 'show_one_tad_web_works');
    $xoopsTpl->assign('WorksID', $WorksID);
    $xoopsTpl->assign('ActionInfo', sprintf(_MD_TCW_INFO, $uid_name, $WorksDate, $WorksCount));

    //取得單一分類資料
    $cate = $web_cate->get_tad_web_cate($CateID);
    $xoopsTpl->assign('cate', $cate);
}

/*-----------執行動作判斷區----------*/
include_once $GLOBALS['xoops']->path('/modules/system/include/functions.php');
$op      = system_CleanVars($_REQUEST, 'op', '', 'string');
$WorksID = system_CleanVars($_REQUEST, 'WorksID', 0, 'int');
$CateID  = system_CleanVars($_REQUEST, 'CateID', 0, 'int');

common_template($WebID);

switch ($op) {

    //新增資料
    case "insert_tad_web_works":
        $WorksID = insert_tad_web_works();
        header("location: {$_SERVER['PHP_SELF']}?WebID={$WebID}&WorksID=$WorksID");
        exit;
        break;

    //更新資料
    case "update_tad_web_works":
        update_tad_web_works($WorksID);
        header("location: {$_SERVER['PHP_SELF']}?WebID={$WebID}&WorksID=$WorksID");
        exit;
        break;
    //輸入表格
    case "tad_web_works_form":
        tad_web_works_form($WorksID);
        break;

    //刪除資料
    case "delete_tad_web_works":
        delete_tad_web_works($WorksID);
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
        if (empty($WorksID)) {
            list_tad_web_works($WebID, $CateID);
        } else {
            show_one_tad_web_works($WorksID);
        }
        break;

}

/*-----------秀出結果區--------------*/
include_once 'footer.php';
include_once XOOPS_ROOT_PATH . '/footer.php';
