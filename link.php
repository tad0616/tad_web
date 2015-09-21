<?php
/*-----------引入檔案區--------------*/
include_once "header.php";
$web_cate = new web_cate($WebID, "link");
if (!empty($_GET['WebID'])) {
    $xoopsOption['template_main'] = 'tad_web_link_b3.html';
} else {
    $xoopsOption['template_main'] = set_bootstrap('tad_web_link.html');
}
include_once XOOPS_ROOT_PATH . "/header.php";
/*-----------function區--------------*/

//tad_web_link編輯表單
function tad_web_link_form($LinkID = "")
{
    global $xoopsDB, $xoopsUser, $WebID, $MyWebs, $xoopsTpl, $isMyWeb, $web_cate;

    if (!$isMyWeb and $MyWebs) {
        redirect_header($_SERVER['PHP_SELF'] . "?WebID={$MyWebs[0]}&op=tad_web_link_form", 3, _MD_TCW_AUTO_TO_HOME);
    } elseif (empty($MyWebs)) {
        redirect_header("index.php", 3, _MD_TCW_NOT_OWNER);
    }

    //抓取預設值
    if (!empty($LinkID)) {
        $DBV = get_tad_web_link($LinkID);
    } else {
        $DBV = array();
    }

    //預設值設定

    //設定「LinkID」欄位預設值
    $LinkID = (!isset($DBV['LinkID'])) ? "" : $DBV['LinkID'];

    //設定「LinkTitle」欄位預設值
    $LinkTitle = (!isset($DBV['LinkTitle'])) ? "" : $DBV['LinkTitle'];

    //設定「LinkDesc」欄位預設值
    $LinkDesc = (!isset($DBV['LinkDesc'])) ? "" : $DBV['LinkDesc'];

    //設定「LinkUrl」欄位預設值
    $LinkUrl = (!isset($DBV['LinkUrl'])) ? "" : $DBV['LinkUrl'];

    //設定「LinkCounter」欄位預設值
    $LinkCounter = (!isset($DBV['LinkCounter'])) ? "" : $DBV['LinkCounter'];

    //設定「LinkSort」欄位預設值
    $LinkSort = (!isset($DBV['LinkSort'])) ? tad_web_link_max_sort() : $DBV['LinkSort'];

    //設定「WebID」欄位預設值
    $WebID = (!isset($DBV['WebID'])) ? $WebID : $DBV['WebID'];

    //設定「uid」欄位預設值
    $user_uid = ($xoopsUser) ? $xoopsUser->getVar('uid') : "";
    $uid      = (!isset($DBV['uid'])) ? $user_uid : $DBV['uid'];

    //設定「CateID」欄位預設值
    $CateID    = (!isset($DBV['CateID'])) ? "" : $DBV['CateID'];
    $cate_menu = $web_cate->cate_menu($CateID);
    $xoopsTpl->assign('cate_menu', $cate_menu);

    $op = (empty($LinkID)) ? "insert_tad_web_link" : "update_tad_web_link";
    //$op="replace_tad_web_link";

    if (!file_exists(TADTOOLS_PATH . "/formValidator.php")) {
        redirect_header("index.php", 3, _MD_NEED_TADTOOLS);
    }
    include_once TADTOOLS_PATH . "/formValidator.php";
    $formValidator      = new formValidator("#myForm", true);
    $formValidator_code = $formValidator->render();

    $xoopsTpl->assign('formValidator_code', $formValidator_code);
    $xoopsTpl->assign('LinkTitle', $LinkTitle);
    $xoopsTpl->assign('LinkUrl', $LinkUrl);
    $xoopsTpl->assign('LinkDesc', $LinkDesc);
    $xoopsTpl->assign('LinkSort', $LinkSort);
    $xoopsTpl->assign('WebID', $WebID);
    $xoopsTpl->assign('LinkID', $LinkID);
    $xoopsTpl->assign('next_op', $op);
    $xoopsTpl->assign('op', 'tad_web_link_form');

}

//自動取得tad_web_link的最新排序
function tad_web_link_max_sort()
{
    global $xoopsDB;
    $sql        = "select max(`LinkSort`) from " . $xoopsDB->prefix("tad_web_link");
    $result     = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'], 3, mysql_error());
    list($sort) = $xoopsDB->fetchRow($result);
    return ++$sort;
}

//新增資料到tad_web_link中
function insert_tad_web_link()
{
    global $xoopsDB, $xoopsUser, $web_cate;

    //取得使用者編號
    $uid = ($xoopsUser) ? $xoopsUser->getVar('uid') : "";

    $myts               = &MyTextSanitizer::getInstance();
    $_POST['LinkTitle'] = $myts->addSlashes($_POST['LinkTitle']);
    $_POST['LinkDesc']  = $myts->addSlashes($_POST['LinkDesc']);
    $_POST['LinkUrl']   = $myts->addSlashes($_POST['LinkUrl']);

    $_POST['LinkCounter'] = intval($_POST['LinkCounter']);
    $_POST['LinkSort']    = intval($_POST['LinkSort']);

    $CateID = $web_cate->save_tad_web_cate();

    $sql = "insert into " . $xoopsDB->prefix("tad_web_link") . "
      (`CateID`, `LinkTitle` , `LinkDesc` , `LinkUrl` , `LinkCounter` , `LinkSort` , `WebID` , `uid`)
      values('{$CateID}', '{$_POST['LinkTitle']}' , '{$_POST['LinkDesc']}' , '{$_POST['LinkUrl']}' , '{$_POST['LinkCounter']}' , '{$_POST['LinkSort']}' , '{$_POST['WebID']}' , '{$uid}')";
    $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'], 3, mysql_error());

    //取得最後新增資料的流水編號
    $LinkID = $xoopsDB->getInsertId();
    return $LinkID;
}

//更新tad_web_link某一筆資料
function update_tad_web_link($LinkID = "")
{
    global $xoopsDB, $xoopsUser, $web_cate;

    //取得使用者編號
    $uid = ($xoopsUser) ? $xoopsUser->getVar('uid') : "";

    $anduid = onlyMine();

    $myts               = &MyTextSanitizer::getInstance();
    $_POST['LinkTitle'] = $myts->addSlashes($_POST['LinkTitle']);
    $_POST['LinkDesc']  = $myts->addSlashes($_POST['LinkDesc']);
    $_POST['LinkUrl']   = $myts->addSlashes($_POST['LinkUrl']);

    $_POST['LinkCounter'] = intval($_POST['LinkCounter']);
    $_POST['LinkSort']    = intval($_POST['LinkSort']);

    $CateID = $web_cate->save_tad_web_cate();
    $sql    = "update " . $xoopsDB->prefix("tad_web_link") . " set
   `CateID` = '{$CateID}' ,
   `LinkTitle` = '{$_POST['LinkTitle']}' ,
   `LinkDesc` = '{$_POST['LinkDesc']}' ,
   `LinkUrl` = '{$_POST['LinkUrl']}' ,
   `LinkCounter` = '{$_POST['LinkCounter']}' ,
   `LinkSort` = '{$_POST['LinkSort']}' ,
   `WebID` = '{$_POST['WebID']}'
    where LinkID='$LinkID' $anduid";
    $xoopsDB->queryF($sql) or redirect_header($_SERVER['PHP_SELF'], 3, mysql_error());
    return $LinkID;
}

//以流水號取得某筆tad_web_link資料
function get_tad_web_link($LinkID = "")
{
    global $xoopsDB;
    if (empty($LinkID)) {
        return;
    }

    $sql    = "select * from " . $xoopsDB->prefix("tad_web_link") . " where LinkID='$LinkID'";
    $result = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'], 3, mysql_error());
    $data   = $xoopsDB->fetchArray($result);
    return $data;
}

//刪除tad_web_link某筆資料資料
function delete_tad_web_link($LinkID = "")
{
    global $xoopsDB;

    $anduid = onlyMine();

    $sql = "delete from " . $xoopsDB->prefix("tad_web_link") . " where LinkID='$LinkID' $anduid";
    $xoopsDB->queryF($sql) or redirect_header($_SERVER['PHP_SELF'], 3, mysql_error());
}

//以流水號秀出某筆tad_web_link資料內容
function show_one_tad_web_link($LinkID = "")
{
    global $xoopsDB;
    if (empty($LinkID)) {
        return;
    } else {
        $LinkID = intval($LinkID);
    }
    $sql           = "select LinkUrl from " . $xoopsDB->prefix("tad_web_link") . " where LinkID='{$LinkID}'";
    $result        = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'], 3, mysql_error());
    list($LinkUrl) = $xoopsDB->fetchRow($result);

    $sql = "update " . $xoopsDB->prefix("tad_web_link") . " set `LinkCounter`=`LinkCounter` +1  where LinkID='{$LinkID}'";
    $xoopsDB->queryF($sql) or redirect_header($_SERVER['PHP_SELF'], 3, mysql_error());
    //echo $sql . "<br>";
    header("location: {$LinkUrl}");
    exit;
}

/*-----------執行動作判斷區----------*/
include_once $GLOBALS['xoops']->path('/modules/system/include/functions.php');
$op     = system_CleanVars($_REQUEST, 'op', '', 'string');
$LinkID = system_CleanVars($_REQUEST, 'LinkID', 0, 'int');
$CateID = system_CleanVars($_REQUEST, 'CateID', 0, 'int');

common_template($WebID);

switch ($op) {
    //替換資料
    case "replace_tad_web_link":
        replace_tad_web_link();
        header("location: {$_SERVER['PHP_SELF']}?WebID={$WebID}");
        exit;
        break;

    //新增資料
    case "insert_tad_web_link":
        $LinkID = insert_tad_web_link();
        header("location: {$_SERVER['PHP_SELF']}?WebID={$WebID}");
        exit;
        break;

    //更新資料
    case "update_tad_web_link":
        update_tad_web_link($LinkID);
        header("location: {$_SERVER['PHP_SELF']}?WebID={$WebID}");
        exit;
        break;
    //輸入表格
    case "tad_web_link_form":
        tad_web_link_form($LinkID);
        break;

    //刪除資料
    case "delete_tad_web_link":
        delete_tad_web_link($LinkID);
        header("location: {$_SERVER['PHP_SELF']}?WebID={$WebID}");
        exit;
        break;

    //預設動作
    default:
        if (empty($LinkID)) {
            list_tad_web_link($WebID, $CateID);
        } else {
            show_one_tad_web_link($LinkID);
        }
        break;

}

/*-----------秀出結果區--------------*/
include_once 'footer.php';
include_once XOOPS_ROOT_PATH . '/footer.php';
