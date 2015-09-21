<?php
/*-----------引入檔案區--------------*/
include_once "header.php";
$web_cate = new web_cate($WebID, "action");
if (!empty($_GET['WebID'])) {
    $xoopsOption['template_main'] = 'tad_web_action_b3.html';
} else {
    $xoopsOption['template_main'] = set_bootstrap('tad_web_action.html');
}

include_once XOOPS_ROOT_PATH . "/header.php";
/*-----------function區--------------*/

//tad_web_action編輯表單
function tad_web_action_form($ActionID = "")
{
    global $xoopsDB, $xoopsUser, $WebID, $MyWebs, $isMyWeb, $xoopsTpl, $TadUpFiles, $web_cate;

    if (!$isMyWeb and $MyWebs) {
        redirect_header($_SERVER['PHP_SELF'] . "?op=WebID={$MyWebs[0]}&tad_web_action_form", 3, _MD_TCW_AUTO_TO_HOME);
    } elseif (empty($MyWebs)) {
        redirect_header("index.php", 3, _MD_TCW_NOT_OWNER);
    }

    //抓取預設值
    if (!empty($ActionID)) {
        $DBV = get_tad_web_action($ActionID);
    } else {
        $DBV = array();
    }

    //預設值設定

    //設定「ActionID」欄位預設值
    $ActionID = (!isset($DBV['ActionID'])) ? $ActionID : $DBV['ActionID'];

    //設定「ActionName」欄位預設值
    $ActionName = (!isset($DBV['ActionName'])) ? "" : $DBV['ActionName'];

    //設定「ActionDesc」欄位預設值
    $ActionDesc = (!isset($DBV['ActionDesc'])) ? "" : $DBV['ActionDesc'];

    //設定「ActionDate」欄位預設值
    $ActionDate = (!isset($DBV['ActionDate'])) ? date("Y-m-d") : $DBV['ActionDate'];

    //設定「ActionPlace」欄位預設值
    $ActionPlace = (!isset($DBV['ActionPlace'])) ? "" : $DBV['ActionPlace'];

    //設定「uid」欄位預設值
    $user_uid = ($xoopsUser) ? $xoopsUser->getVar('uid') : "";
    $uid      = (!isset($DBV['uid'])) ? $user_uid : $DBV['uid'];

    //設定「WebID」欄位預設值
    $WebID = (!isset($DBV['WebID'])) ? $WebID : $DBV['WebID'];

    //設定「ActionCount」欄位預設值
    $ActionCount = (!isset($DBV['ActionCount'])) ? "" : $DBV['ActionCount'];

    //設定「CateID」欄位預設值
    $CateID    = (!isset($DBV['CateID'])) ? "" : $DBV['CateID'];
    $cate_menu = $web_cate->cate_menu($CateID);
    $xoopsTpl->assign('cate_menu', $cate_menu);

    $op = (empty($ActionID)) ? "insert_tad_web_action" : "update_tad_web_action";

    if (!file_exists(TADTOOLS_PATH . "/formValidator.php")) {
        redirect_header("index.php", 3, _MD_NEED_TADTOOLS);
    }
    include_once TADTOOLS_PATH . "/formValidator.php";
    $formValidator      = new formValidator("#myForm", true);
    $formValidator_code = $formValidator->render();

    $xoopsTpl->assign('formValidator_code', $formValidator_code);
    $xoopsTpl->assign('ActionName', $ActionName);
    $xoopsTpl->assign('ActionDesc', $ActionDesc);
    $xoopsTpl->assign('ActionDate', $ActionDate);
    $xoopsTpl->assign('ActionPlace', $ActionPlace);
    //$xoopsTpl->assign('list_del_file',upfile::list_del_file("ActionID",$ActionID,true));
    $xoopsTpl->assign('ActionID', $ActionID);
    $xoopsTpl->assign('WebID', $WebID);
    $xoopsTpl->assign('next_op', $op);
    $xoopsTpl->assign('op', 'tad_web_action_form');

    $TadUpFiles->set_col('ActionID', $ActionID); //若 $show_list_del_file ==true 時一定要有
    $upform = $TadUpFiles->upform(true, 'upfile');
    $xoopsTpl->assign('upform', $upform);

}

//新增資料到tad_web_action中
function insert_tad_web_action()
{
    global $xoopsDB, $xoopsUser, $TadUpFiles, $web_cate;

    //取得使用者編號
    $uid = ($xoopsUser) ? $xoopsUser->getVar('uid') : "";

    $myts                 = &MyTextSanitizer::getInstance();
    $_POST['ActionName']  = $myts->addSlashes($_POST['ActionName']);
    $_POST['ActionDesc']  = $myts->addSlashes($_POST['ActionDesc']);
    $_POST['ActionPlace'] = $myts->addSlashes($_POST['ActionPlace']);

    $_POST['ActionCount'] = intval($_POST['ActionCount']);

    $CateID = $web_cate->save_tad_web_cate();
    $sql    = "insert into " . $xoopsDB->prefix("tad_web_action") . "
	(`CateID`,`ActionName` , `ActionDesc` , `ActionDate` , `ActionPlace` , `uid` , `WebID` , `ActionCount`)
	values('{$CateID}' ,'{$_POST['ActionName']}' , '{$_POST['ActionDesc']}' , '{$_POST['ActionDate']}' , '{$_POST['ActionPlace']}' , '{$uid}' , '{$_POST['WebID']}' , '{$_POST['ActionCount']}')";
    $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'], 3, mysql_error());

    //取得最後新增資料的流水編號
    $ActionID = $xoopsDB->getInsertId();

    $TadUpFiles->set_col('ActionID', $ActionID);
    $TadUpFiles->upload_file('upfile', 800, null, null, null, true);

    return $ActionID;
}

//更新tad_web_action某一筆資料
function update_tad_web_action($ActionID = "")
{
    global $xoopsDB, $xoopsUser, $isAdmin, $MyWebs, $TadUpFiles, $web_cate;

    $myts                 = &MyTextSanitizer::getInstance();
    $_POST['ActionName']  = $myts->addSlashes($_POST['ActionName']);
    $_POST['ActionDesc']  = $myts->addSlashes($_POST['ActionDesc']);
    $_POST['ActionPlace'] = $myts->addSlashes($_POST['ActionPlace']);

    $anduid = onlyMine();

    $_POST['ActionCount'] = intval($_POST['ActionCount']);

    $CateID = $web_cate->save_tad_web_cate();
    $sql    = "update " . $xoopsDB->prefix("tad_web_action") . " set
     `CateID` = '{$CateID}' ,
	 `ActionName` = '{$_POST['ActionName']}' ,
	 `ActionDesc` = '{$_POST['ActionDesc']}' ,
	 `ActionDate` = '{$_POST['ActionDate']}' ,
	 `ActionPlace` = '{$_POST['ActionPlace']}'
	where ActionID='$ActionID' $anduid";
    $xoopsDB->queryF($sql) or redirect_header($_SERVER['PHP_SELF'], 3, mysql_error());

    $TadUpFiles->set_col('ActionID', $ActionID);
    $TadUpFiles->upload_file('upfile', 800, null, null, null, true);

    return $ActionID;
}

//新增tad_web_action計數器
function add_tad_web_action_counter($ActionID = '')
{
    global $xoopsDB;
    $sql = "update " . $xoopsDB->prefix("tad_web_action") . " set `ActionCount`=`ActionCount`+1 where `ActionID`='{$ActionID}'";
    $xoopsDB->queryF($sql) or redirect_header($_SERVER['PHP_SELF'], 3, mysql_error());
}

//以流水號取得某筆tad_web_action資料
function get_tad_web_action($ActionID = "")
{
    global $xoopsDB;
    if (empty($ActionID)) {
        return;
    }

    $sql    = "select * from " . $xoopsDB->prefix("tad_web_action") . " where ActionID='$ActionID'";
    $result = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'], 3, mysql_error());
    $data   = $xoopsDB->fetchArray($result);
    return $data;
}

//刪除tad_web_action某筆資料資料
function delete_tad_web_action($ActionID = "")
{
    global $xoopsDB, $xoopsUser;
    $anduid = onlyMine();
    $sql    = "delete from " . $xoopsDB->prefix("tad_web_action") . " where ActionID='$ActionID' $anduid";
    $xoopsDB->queryF($sql) or redirect_header($_SERVER['PHP_SELF'], 3, mysql_error());
}

//以流水號秀出某筆tad_web_action資料內容
function show_one_tad_web_action($ActionID = "")
{
    global $xoopsDB, $xoopsTpl, $TadUpFiles, $web_cate;
    if (empty($ActionID)) {
        return;
    } else {
        $ActionID = intval($ActionID);
    }

    add_tad_web_action_counter($ActionID);

    $sql    = "select * from " . $xoopsDB->prefix("tad_web_action") . " where ActionID='{$ActionID}'";
    $result = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'], 3, mysql_error());
    $all    = $xoopsDB->fetchArray($result);

    //以下會產生這些變數： $ActionID , $ActionName , $ActionDesc , $ActionDate , $ActionPlace , $uid , $WebID , $ActionCount
    foreach ($all as $k => $v) {
        $$k = $v;
    }

    $TadUpFiles->set_col("ActionID", $ActionID);
    $pics = $TadUpFiles->show_files('upfile'); //是否縮圖,顯示模式 filename、small,顯示描述,顯示下載次數

    $uid_name = XoopsUser::getUnameFromId($uid, 1);

    $xoopsTpl->assign('isMineAction', isMine());
    $xoopsTpl->assign('ActionName', $ActionName);
    $xoopsTpl->assign('ActionDate', $ActionDate);
    $xoopsTpl->assign('ActionPlace', $ActionPlace);
    $xoopsTpl->assign('ActionDesc', nl2br($ActionDesc));
    $xoopsTpl->assign('uid_name', $uid_name);
    $xoopsTpl->assign('ActionCount', $ActionCount);
    $xoopsTpl->assign('pics', $pics);
    $xoopsTpl->assign('op', 'show_one_tad_web_action');
    $xoopsTpl->assign('ActionID', $ActionID);
    $xoopsTpl->assign('ActionInfo', sprintf(_MD_TCW_INFO, $uid_name, $ActionDate, $ActionCount));

    //取得單一分類資料
    $cate = $web_cate->get_tad_web_cate($CateID);
    $xoopsTpl->assign('cate', $cate);
}

/*-----------執行動作判斷區----------*/
include_once $GLOBALS['xoops']->path('/modules/system/include/functions.php');
$op       = system_CleanVars($_REQUEST, 'op', '', 'string');
$ActionID = system_CleanVars($_REQUEST, 'ActionID', 0, 'int');
$CateID   = system_CleanVars($_REQUEST, 'CateID', 0, 'int');

common_template($WebID);

switch ($op) {

    //新增資料
    case "insert_tad_web_action":
        $ActionID = insert_tad_web_action();
        header("location: {$_SERVER['PHP_SELF']}?WebID={$WebID}&ActionID=$ActionID");
        exit;
        break;

    //更新資料
    case "update_tad_web_action":
        update_tad_web_action($ActionID);
        header("location: {$_SERVER['PHP_SELF']}?WebID={$WebID}&ActionID=$ActionID");
        exit;
        break;
    //輸入表格
    case "tad_web_action_form":
        tad_web_action_form($ActionID);
        break;

    //刪除資料
    case "delete_tad_web_action":
        delete_tad_web_action($ActionID);
        header("location: {$_SERVER['PHP_SELF']}?WebID={$WebID}");
        exit;
        break;

    //預設動作
    default:
        if (empty($ActionID)) {
            list_tad_web_action($WebID, $CateID);
        } else {
            show_one_tad_web_action($ActionID);
        }
        break;

}

/*-----------秀出結果區--------------*/
include_once 'footer.php';
include_once XOOPS_ROOT_PATH . '/footer.php';
