<?php
/*-----------引入檔案區--------------*/
include_once "header.php";
$web_cate = new web_cate($WebID, "discuss", "tad_web_discuss");
if (!empty($_GET['WebID'])) {
    $xoopsOption['template_main'] = 'tad_web_discuss_b3.html';
} else {
    $xoopsOption['template_main'] = set_bootstrap('tad_web_discuss.html');
}
include_once XOOPS_ROOT_PATH . "/header.php";

/*-----------function區--------------*/

//tad_web_discuss編輯表單
function tad_web_discuss_form($DiscussID = "")
{
    global $xoopsDB, $xoopsUser, $WebID, $isAdmin, $xoopsTpl, $web_cate, $isMyWeb;

    if (!$isAdmin and !$isMyWeb and empty($_SESSION['LoginMemID'])) {
        //die('isMyWeb:' . $isMyWeb);
        redirect_header("index.php", 3, _MD_TCW_LOGIN_TO_POST);
    }

    //抓取預設值
    if (!empty($DiscussID)) {
        $DBV = get_tad_web_discuss($DiscussID);
    } else {
        $DBV = array();
    }

    //預設值設定

    if ($isMyWeb) {

        //設定「uid」欄位預設值
        $uid = (!isset($DBV['uid'])) ? $xoopsUser->uid() : $DBV['uid'];

        //設定「MemID」欄位預設值
        $MemID = (!isset($DBV['MemID'])) ? 0 : $DBV['MemID'];

        //設定「LoginMemName」欄位預設值
        $MemName = (!isset($DBV['MemName'])) ? $xoopsUser->name() : $DBV['MemName'];

        //設定「WebID」欄位預設值
        $WebID = (!isset($DBV['WebID'])) ? $WebID : $DBV['WebID'];
    } else {

        //設定「uid」欄位預設值
        $uid = (!isset($DBV['uid'])) ? 0 : $DBV['uid'];

        //設定「MemID」欄位預設值
        $MemID = (!isset($DBV['MemID'])) ? $LoginMemID : $DBV['MemID'];

        //設定「LoginMemName」欄位預設值
        $MemName = (!isset($DBV['MemName'])) ? $LoginMemName : $DBV['MemName'];

        //設定「WebID」欄位預設值
        $WebID = (!isset($DBV['WebID'])) ? $_SESSION['LoginWebID'] : $DBV['WebID'];
    }

    //設定「DiscussID」欄位預設值
    $DiscussID = (!isset($DBV['DiscussID'])) ? "" : $DBV['DiscussID'];

    //設定「ReDiscussID」欄位預設值
    $ReDiscussID = (!isset($DBV['ReDiscussID'])) ? "" : $DBV['ReDiscussID'];

    //設定「DiscussTitle」欄位預設值
    $DiscussTitle = (!isset($DBV['DiscussTitle'])) ? "" : $DBV['DiscussTitle'];

    //設定「DiscussContent」欄位預設值
    $DiscussContent = (!isset($DBV['DiscussContent'])) ? "" : $DBV['DiscussContent'];

    //設定「DiscussDate」欄位預設值
    $DiscussDate = (!isset($DBV['DiscussDate'])) ? date("Y-m-d H:i:s") : $DBV['DiscussDate'];

    //設定「LastTime」欄位預設值
    $LastTime = (!isset($DBV['LastTime'])) ? date("Y-m-d H:i:s") : $DBV['LastTime'];

    //設定「DiscussCounter」欄位預設值
    $DiscussCounter = (!isset($DBV['DiscussCounter'])) ? "" : $DBV['DiscussCounter'];

    //設定「CateID」欄位預設值
    $CateID = (!isset($DBV['CateID'])) ? "" : $DBV['CateID'];

    $new_cate  = empty($_SESSION['LoginMemID']) ? true : false;
    $cate_menu = $web_cate->cate_menu($CateID, 'form', $new_cate);
    $xoopsTpl->assign('cate_menu', $cate_menu);

    $op = (empty($DiscussID)) ? "insert_tad_web_discuss" : "update_tad_web_discuss";
    //$op="replace_tad_web_discuss";

    if (!file_exists(TADTOOLS_PATH . "/formValidator.php")) {
        redirect_header("index.php", 3, _MD_NEED_TADTOOLS);
    }
    include_once TADTOOLS_PATH . "/formValidator.php";
    $formValidator      = new formValidator("#myForm", true);
    $formValidator_code = $formValidator->render();

    $xoopsTpl->assign('formValidator_code', $formValidator_code);
    $xoopsTpl->assign('DiscussTitle', $DiscussTitle);
    $xoopsTpl->assign('DiscussContent', $DiscussContent);
    $xoopsTpl->assign('WebID', $WebID);
    $xoopsTpl->assign('DiscussID', $DiscussID);
    $xoopsTpl->assign('ReDiscussID', $ReDiscussID);
    $xoopsTpl->assign('next_op', $op);
    $xoopsTpl->assign('op', 'tad_web_discuss_form');
    $xoopsTpl->assign('isMyWeb', $isMyWeb);

}

//新增資料到tad_web_discuss中
function insert_tad_web_discuss()
{
    global $xoopsDB, $xoopsUser, $WebID, $isMyWeb, $isAdmin, $web_cate;

    if (empty($_SESSION['LoginMemID']) and !$isMyWeb and $isAdmin) {
        redirect_header("index.php", 3, _MD_TCW_LOGIN_TO_POST);
    }

    $myts                    = MyTextSanitizer::getInstance();
    $_POST['DiscussTitle']   = $myts->addSlashes($_POST['DiscussTitle']);
    $_POST['DiscussContent'] = $myts->addSlashes($_POST['DiscussContent']);

    if ($isMyWeb) {
        $uid     = $xoopsUser->uid();
        $MemID   = 0;
        $MemName = $xoopsUser->name();
    } else {
        $uid     = 0;
        $MemID   = $_SESSION['LoginMemID'];
        $MemName = $_SESSION['LoginMemName'];
        $WebID   = $_SESSION['LoginWebID'];
    }

    $_POST['ReDiscussID'] = intval($_POST['ReDiscussID']);

    $CateID = $web_cate->save_tad_web_cate($_POST['CateID'], $_POST['newCateName']);
    $sql    = "insert into " . $xoopsDB->prefix("tad_web_discuss") . " 	(`CateID`,`ReDiscussID` , `uid` , `MemID`, `MemName` , `DiscussTitle` , `DiscussContent` , `DiscussDate` , `WebID` , `LastTime` , `DiscussCounter`)
	values('{$CateID}'  ,'{$_POST['ReDiscussID']}'  , '{$uid}' , '{$MemID}', '{$MemName}' , '{$_POST['DiscussTitle']}' , '{$_POST['DiscussContent']}' , now() , '{$WebID}' , now() , 0)";
    $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'], 3, mysql_error());

    //取得最後新增資料的流水編號
    $DiscussID = $xoopsDB->getInsertId();

    if (!empty($_POST['ReDiscussID'])) {
        $sql = "update " . $xoopsDB->prefix("tad_web_discuss") . " set `LastTime` = now()
  	where `DiscussID` = '{$_POST['ReDiscussID']}' or `ReDiscussID` = '{$_POST['ReDiscussID']}'";
        $xoopsDB->queryF($sql) or redirect_header($_SERVER['PHP_SELF'], 3, mysql_error());
    }

    if (!empty($_POST['ReDiscussID'])) {
        return $_POST['ReDiscussID'];
    }

    return $DiscussID;
}

//更新tad_web_discuss某一筆資料
function update_tad_web_discuss($DiscussID = "")
{
    global $xoopsDB, $xoopsUser, $isAdmin, $WebID, $isMyWeb, $web_cate;

    if ($isMyWeb) {
        $uid     = $xoopsUser->uid();
        $MemID   = 0;
        $MemName = $xoopsUser->name();
        $anduid  = ($isAdmin) ? "" : "and `WebID`='{$WebID}'";
    } else {

        $uid     = 0;
        $MemID   = $_SESSION['LoginMemID'];
        $MemName = $_SESSION['LoginMemName'];
        $WebID   = $_SESSION['LoginWebID'];
        $anduid  = "and `MemID`='{$MemID}'";
    }

    $myts                    = MyTextSanitizer::getInstance();
    $_POST['DiscussTitle']   = $myts->addSlashes($_POST['DiscussTitle']);
    $_POST['DiscussContent'] = $myts->addSlashes($_POST['DiscussContent']);

    $_POST['ReDiscussID'] = intval($_POST['ReDiscussID']);

    $CateID = $web_cate->save_tad_web_cate($_POST['CateID'], $_POST['newCateName']);
    $sql    = "update " . $xoopsDB->prefix("tad_web_discuss") . " set
     `CateID` = '{$CateID}' ,
	 `ReDiscussID` = '{$_POST['ReDiscussID']}' ,
	 `DiscussTitle` = '{$_POST['DiscussTitle']}' ,
	 `DiscussContent` = '{$_POST['DiscussContent']}' ,
	 `LastTime` = now()
	where DiscussID='{$DiscussID}' {$anduid}";
    $xoopsDB->queryF($sql) or redirect_header($_SERVER['PHP_SELF'], 3, mysql_error());

    return $DiscussID;
}

//以流水號取得某筆tad_web_discuss資料
function get_tad_web_discuss($DiscussID = "")
{
    global $xoopsDB;
    if (empty($DiscussID)) {
        return;
    }

    $sql    = "select * from " . $xoopsDB->prefix("tad_web_discuss") . " where DiscussID='$DiscussID'";
    $result = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'], 3, mysql_error());
    $data   = $xoopsDB->fetchArray($result);
    return $data;
}

//刪除tad_web_discuss某筆資料資料
function delete_tad_web_discuss($DiscussID = "")
{
    global $xoopsDB, $xoopsUser, $isAdmin, $WebID, $isMyWeb, $isMyWeb;

    if ($isMyWeb) {
        // $uid     = $xoopsUser->uid();
        // $MemID   = 0;
        // $MemName = $xoopsUser->name();
        // $WebID   = $WebID;
        $anduid = ($isAdmin) ? "" : "and `WebID`='{$WebID}'";
    } else {

        // $uid     = 0;
        // $MemID   = $_SESSION['LoginMemID'];
        // $MemName = $_SESSION['LoginMemName'];
        // $WebID   = $_SESSION['LoginWebID'];
        $anduid = "and `MemID`='{$MemID}'";
    }

    $sql = "delete from " . $xoopsDB->prefix("tad_web_discuss") . " where DiscussID='$DiscussID' $anduid";
    $xoopsDB->queryF($sql) or redirect_header($_SERVER['PHP_SELF'], 3, mysql_error());

    $sql = "delete from " . $xoopsDB->prefix("tad_web_discuss") . " where ReDiscussID='$DiscussID' $anduid";
    $xoopsDB->queryF($sql) or redirect_header($_SERVER['PHP_SELF'], 3, mysql_error());
}

//以流水號秀出某筆tad_web_discuss資料內容
function show_one_tad_web_discuss($DiscussID = "")
{
    global $xoopsDB, $xoopsUser, $isAdmin, $xoopsTpl, $web_cate;
    if (empty($DiscussID)) {
        return;
    } else {
        $DiscussID = intval($DiscussID);
    }

    add_tad_web_discuss_counter($DiscussID);

    $sql    = "select * from " . $xoopsDB->prefix("tad_web_discuss") . " where DiscussID='{$DiscussID}'";
    $result = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'], 3, mysql_error());
    $all    = $xoopsDB->fetchArray($result);

    //以下會產生這些變數： $DiscussID , $ReDiscussID , $uid , $DiscussTitle , $DiscussContent , $DiscussDate , $WebID , $LastTime , $DiscussCounter
    foreach ($all as $k => $v) {
        $$k = $v;
    }

    //$fun=(isMine($uid))?"
    //<a href='{$_SERVER['PHP_SELF']}?op=tad_web_discuss_form&DiscussID=$DiscussID' class='link_button_r'>"._TAD_EDIT."</a>
    //<a href=\"javascript:delete_tad_web_discuss_func($DiscussID);\" class='link_button_r'>"._TAD_DEL."</a>
    //":"";

    $xoopsTpl->assign('op', 'show_one_tad_web_discuss');
    $xoopsTpl->assign('isMineDiscuss', isMineDiscuss($MemID, $WebID));
    $xoopsTpl->assign('DiscussTitle', $DiscussTitle);
    $xoopsTpl->assign('MemID', $MemID);
    $xoopsTpl->assign('DiscussContent', nl2br($DiscussContent));
    $xoopsTpl->assign('DiscussDate', $DiscussDate);
    $xoopsTpl->assign('LastTime', $LastTime);
    $xoopsTpl->assign('MemName', $MemName);
    $xoopsTpl->assign('WebID', $WebID);
    $xoopsTpl->assign('DiscussCounter', $DiscussCounter);
    $xoopsTpl->assign('DiscussID', $DiscussID);
    $xoopsTpl->assign('DiscussInfo', sprintf(_MD_TCW_INFO, $MemName, $DiscussDate, $DiscussCounter));
    $xoopsTpl->assign('re', get_re($DiscussID));
    $xoopsTpl->assign('LoginMemID', $_SESSION['LoginMemID']);

    //取得單一分類資料
    $cate = $web_cate->get_tad_web_cate($CateID);
    $xoopsTpl->assign('cate', $cate);
}

//是否有管理權（或由自己發布的），判斷是否要秀出管理工具
function isMineDiscuss($DiscussMemID = null, $DiscussWebID = null)
{
    global $isMyWeb, $isAdmin, $xoopsUser;

    if (!empty($DiscussMemID) and $_SESSION['LoginMemID'] == $DiscussMemID) {
        return true;

    } elseif (!empty($DiscussWebID) and $isMyWeb) {
        return true;
    } elseif ($isAdmin) {
        return true;
    }

    return false;
}

//回覆的留言
function get_re($DiscussID = "")
{
    global $xoopsDB, $isMyWeb, $TadUpFiles;
    if (empty($DiscussID)) {
        return;
    }

    $sql    = "select * from " . $xoopsDB->prefix("tad_web_discuss") . " where ReDiscussID='$DiscussID' order by DiscussDate";
    $result = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'], 3, mysql_error());

    $re_data = "";

    while ($all = $xoopsDB->fetchArray($result)) {
        //以下會產生這些變數： $DiscussID , $ReDiscussID , $MemID , $DiscussTitle , $DiscussContent , $DiscussDate , $WebID , $LastTime , $DiscussCounter
        foreach ($all as $k => $v) {
            $$k = $v;
        }

        if (!empty($uid)) {
            $TadUpFiles->set_col("WebOwner", $WebID, "1");
            $pic = $TadUpFiles->get_pic_file("thumb");
            if (empty($pic)) {
                $pic = "images/nobody.png";
            }

        } else {
            $TadUpFiles->set_col("MemID", $MemID, "1");
            $pic = $TadUpFiles->get_pic_file("thumb");
            $M   = get_tad_web_mems($MemID);
            if (empty($pic)) {
                $pic = ($M['MemSex'] == '1') ? XOOPS_URL . "/modules/tad_web/images/boy.gif" : XOOPS_URL . "/modules/tad_web/images/girl.gif";
            }

        }

        $fun = (isMineDiscuss($MemID)) ? "<div style='font-size:12px;'>
  	<a href='{$_SERVER['PHP_SELF']}?WebID=$WebID&op=tad_web_discuss_form&DiscussID=$DiscussID'>" . _TAD_EDIT . "</a> | <a href=\"javascript:delete_tad_web_discuss_func($DiscussID);\">" . _TAD_DEL . "</a>
  	</div>" : "";

        $DiscussContent = nl2br($DiscussContent);
        $DiscussContent = bubble($DiscussContent);
        $re_data .= "<tr><td style='line-height:180%;'>
    $DiscussContent
    <img src='$pic' alt='{$MemName}" . _MD_TCW_DISCUSS_REPLY . "' style='margin:0px 15px 0px 30px;float:left;' class='img-rounded img-polaroid'>
    <div style='line-height:1.5em;'>
      <div>{$MemName}</div><div style='font-size:12px;'>$DiscussDate</div>{$fun}
    </div>
    <div style='clean:both;'></div>
    </td></tr>";
    }

    $re = "";
    if (!empty($re_data)) {
        $re = "
  	<table>
  	$re_data
  	</table>
    ";
    }
    return $re;
}

function bubble($content = "")
{
    $main = "<div class='xsnazzy'>
  <b class='xb1'></b><b class='xb2'></b><b class='xb3'></b><b class='xb4'></b><b class='xb5'></b><b class='xb6'></b><b class='xb7'></b>
  <div class='xboxcontent'>
  <p>$content</p>
  </div>
  <b class='xb7'></b><b class='xb6'></b><b class='xb5'></b><b class='xb4'></b><b class='xb3'></b><b class='xb2'></b><b class='xb1'></b>
  <em></em><span></span>
  </div>";
    return $main;
}

//新增tad_web_discuss計數器
function add_tad_web_discuss_counter($DiscussID = '')
{
    global $xoopsDB;
    $sql = "update " . $xoopsDB->prefix("tad_web_discuss") . " set `DiscussCounter`=`DiscussCounter`+1 where `DiscussID`='{$DiscussID}'";
    $xoopsDB->queryF($sql) or redirect_header($_SERVER['PHP_SELF'], 3, mysql_error());
}

//登入
function mem_login($MemUname = "", $MemPasswd = "")
{
    global $xoopsDB, $xoopsUser;
    if (empty($MemUname) or empty($MemPasswd)) {
        return false;
    }

    $sql                                         = "select a.`MemID` , a.`MemName` , a.`MemNickName` , b.`WebID` from " . $xoopsDB->prefix("tad_web_mems") . " as a left join " . $xoopsDB->prefix("tad_web_link_mems") . " as b on a.`MemID`=b.`MemID` where a.`MemUname`='$MemUname' and a.`MemPasswd`='$MemPasswd' and b.`MemEnable`='1'";
    $result                                      = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'], 3, mysql_error());
    list($MemID, $MemName, $MemNickName, $WebID) = $xoopsDB->fetchRow($result);

    if (!empty($MemID)) {
        $_SESSION['LoginMemID']       = $MemID;
        $_SESSION['LoginMemName']     = $MemName;
        $_SESSION['LoginMemNickName'] = $MemNickName;
        $_SESSION['LoginWebID']       = $WebID;
    }
    return true;
}

/*-----------執行動作判斷區----------*/
include_once $GLOBALS['xoops']->path('/modules/system/include/functions.php');
$op        = system_CleanVars($_REQUEST, 'op', '', 'string');
$DiscussID = system_CleanVars($_REQUEST, 'DiscussID', 0, 'int');
$WebID     = system_CleanVars($_REQUEST, 'WebID', $LoginWebID, 'int');
$CateID    = system_CleanVars($_REQUEST, 'CateID', 0, 'int');

common_template($WebID);

switch ($op) {
    //替換資料
    case "replace_tad_web_discuss":
        replace_tad_web_discuss();
        header("location: {$_SERVER['PHP_SELF']}?WebID={$WebID}");
        exit;
        break;

    //新增資料
    case "insert_tad_web_discuss":
        $DiscussID = insert_tad_web_discuss();
        header("location: {$_SERVER['PHP_SELF']}?WebID=$WebID&DiscussID=$DiscussID");
        exit;
        break;

    //更新資料
    case "update_tad_web_discuss":
        update_tad_web_discuss($DiscussID);
        header("location: {$_SERVER['PHP_SELF']}?WebID={$WebID}");
        exit;
        break;

    //輸入表格
    case "tad_web_discuss_form":
        tad_web_discuss_form($DiscussID);
        break;

    //刪除資料
    case "delete_tad_web_discuss":
        delete_tad_web_discuss($DiscussID);
        header("location: {$_SERVER['PHP_SELF']}?WebID={$WebID}");
        exit;
        break;

    //登入
    case "mem_login":
        mem_login($_POST['MemUname'], $_POST['MemPasswd']);
        header("location: {$_SERVER['PHP_SELF']}?WebID={$WebID}");
        exit;
        break;

    //登出
    case "logout":
        $_SESSION['LoginMemID'] = $_SESSION['LoginMemName'] = $_SESSION['LoginMemNickName'] = $_SESSION['LoginWebID'] = "";
        header("location: {$_SERVER['PHP_SELF']}?WebID={$WebID}");
        exit;
        break;

    //預設動作
    default:
        if (empty($DiscussID)) {
            list_tad_web_discuss($WebID, $CateID);
        } else {
            show_one_tad_web_discuss($DiscussID);
        }
        break;

}

/*-----------秀出結果區--------------*/
include_once 'footer.php';
include_once XOOPS_ROOT_PATH . '/footer.php';
