<?php
/*-----------引入檔案區--------------*/
$xoopsOption['template_main'] = "tad_web_adm_main.html";
include_once 'header.php';
include_once "../function.php";
include_once "../class/cate.php";
/*-----------function區--------------*/

//環境檢查
function chk_evn()
{
    $error = '';
    if (!function_exists('imagecreatetruecolor')) {
        $error[_MA_TCW_NEED_IMAGECREATETURECOLOR] = _MA_TCW_NEED_IMAGECREATETURECOLOR_CONTENT;
    }

    if (!is_dir(XOOPS_ROOT_PATH . "/themes/for_tad_web_theme")) {
        $error[_MA_TCW_NEED_THEME] = _MA_TCW_NEED_THEME_CONTENT;
    }

    $modhandler    = &xoops_gethandler('module');
    $ttxoopsModule = &$modhandler->getByDirname("tadtools");
    $version       = $ttxoopsModule->version();
    if ($version < 274) {
        $error[_MA_TCW_NEED_TADTOOLS] = _MA_TCW_NEED_TADTOOLS_CONTENT;
    }
    return $error;
}

//取得所有班級
function list_all_web($defCateID = '')
{
    global $xoopsDB, $xoopsTpl;
    $error = chk_evn();
    if ($error) {
        $xoopsTpl->assign('error', $error);
        $xoopsTpl->assign('op', 'error');
        return;
    }

    $and_cate = empty($defCateID) ? "" : "and CateID='{$defCateID}'";

    $sql = "select * from " . $xoopsDB->prefix("tad_web") . " where 1 $and_cate order by WebSort";

    $result = $xoopsDB->query($sql) or web_error($sql);

    $data = "";
    $i    = 1;
    include_once XOOPS_ROOT_PATH . "/modules/tadtools/jeditable.php";
    $file = "save.php";
    //$jeditable = new jeditable(false);
    while ($all = $xoopsDB->fetchArray($result)) {
        //以下會產生這些變數： $WebID , $WebName , $WebSort , $WebEnable , $WebCounter
        foreach ($all as $k => $v) {
            $$k           = $v;
            $data[$i][$k] = $v;
        }

        $data[$i]['memAmount'] = memAmount($WebID);
        $data[$i]['uname']     = XoopsUser::getUnameFromId($WebOwnerUid, 0);
        $web_admin_arr         = get_web_roles($WebID, 'admin');
        if ($web_admin_arr) {
            $admin_str = implode("','", $web_admin_arr);
            $sql2      = "SELECT `uid`,`name`,`uname`,`email` FROM `" . $xoopsDB->prefix("users") . "` WHERE `uid` in('{$admin_str}')";

            $result2   = $xoopsDB->queryF($sql2) or web_error($sql2);
            $j         = 0;
            $admin_arr = '';
            while (list($uid, $name, $uname, $email) = $xoopsDB->fetchRow($result2)) {
                $admin_arr[$j]['uid']   = $uid;
                $admin_arr[$j]['name']  = $name;
                $admin_arr[$j]['uname'] = $uname;
                $admin_arr[$j]['email'] = $email;
                $j++;
            }
        } else {
            $admin_arr = "";
        }
        $data[$i]['admin_arr'] = $admin_arr;
        //$jeditable->setSelectCol(".Class{$WebID}",$file,"{{$teacher_option}, 'selected':'{$WebOwnerUid}'}","{'WebID' : $WebID , 'op' : 'save_teacher'}",_MA_TCW_CLICK_TO_EDIT);
        $i++;
    }

    if (empty($data)) {
        $xoopsTpl->assign('op', 'create_web');

    } else {

        //$jeditable_set=$jeditable->render();

        $jquery = get_jquery(true);
        //$xoopsTpl->assign('jeditable_set',$jeditable_set);
        $xoopsTpl->assign('WebYear', $WebYear);
        $xoopsTpl->assign('data', $data);
        $xoopsTpl->assign('jquery', $jquery);
        $xoopsTpl->assign('CateID', $defCateID);

        $web_cate = new web_cate("", "web_cate", "tad_web");
        $cate     = $web_cate->get_tad_web_cate_arr();
        $xoopsTpl->assign('cate', $cate);

    }
}

//根據使用者來開網頁
function create_by_user()
{
    global $xoopsTpl, $xoopsDB;

    //檢查有無班級網頁群組
    $groupid = chk_tad_web_group(_MA_TCW_GROUP_NAME);

    $sql    = "select uid from " . $xoopsDB->prefix("groups_users_link") . " where `groupid`='$groupid' order by uid";
    $result = $xoopsDB->query($sql) or web_error($sql);
    while (list($uid) = $xoopsDB->fetchRow($result)) {
        $ok_uid[] = $uid;
    }
    $WebOwnerUid = implode(',', $ok_uid);

    $sql    = "select uid,uname,name from " . $xoopsDB->prefix("users") . " order by uname";
    $result = $xoopsDB->query($sql) or web_error($sql);

    $myts = MyTextSanitizer::getInstance();
    $opt  = "";
    while ($all = $xoopsDB->fetchArray($result)) {
        foreach ($all as $k => $v) {
            $$k = $v;
        }
        $name  = $myts->htmlSpecialChars($name);
        $uname = $myts->htmlSpecialChars($uname);
        $name  = empty($name) ? "" : " ({$name})";
        if (in_array($uid, $ok_uid)) {
            $opt2 .= "<option value=\"$uid\">{$uname} {$name}</option>";
        } else {
            $opt .= "<option value=\"$uid\">{$uname} {$name}</option>";
        }
    }

    $form = "
  <script type=\"text/javascript\" src=\"" . XOOPS_URL . "/modules/tad_web/class/tmt_core.js\"></script>
    <script type=\"text/javascript\" src=\"" . XOOPS_URL . "/modules/tad_web/class/tmt_spry_linkedselect.js\"></script>
    <script type=\"text/javascript\">
    function getOptions()
    {

    var values = [];
    var sel = document.getElementById('destination');
    for (var i=0, n=sel.options.length;i<n;i++) {
      if (sel.options[i].value) values.push(sel.options[i].value);
    }
      document.getElementById('WebOwnerUid').value=values.join(',');
      }
    </script>

  <table style='width:auto'>

        <tr>
        <td style='vertical-align:top;'>
            <select name=\"repository\" id=\"repository\" size=\"12\" multiple=\"multiple\" tmt:linkedselect=\"true\" style='width: 300px;'>
            $opt
            </select>
        </td>
        <td style='vertical-align:middle'>
        <img src=\"" . XOOPS_URL . "/modules/tad_web/images/right.png\" onclick=\"tmt.spry.linkedselect.util.moveOptions('repository', 'destination');getOptions();\"><br>
        <img src=\"" . XOOPS_URL . "/modules/tad_web/images/left.png\" onclick=\"tmt.spry.linkedselect.util.moveOptions('destination' , 'repository');getOptions();\"><br><br>

    <img src=\"" . XOOPS_URL . "/modules/tad_web/images/up.png\" onclick=\"tmt.spry.linkedselect.util.moveOptionUp('destination');getOptions();\"><br>
        <img src=\"" . XOOPS_URL . "/modules/tad_web/images/down.png\" onclick=\"tmt.spry.linkedselect.util.moveOptionDown('destination');getOptions();\">
        </td>
        <td style='vertical-align:top;'>
            <select id=\"destination\" size=\"12\" multiple=\"multiple\" tmt:linkedselect=\"true\" style='width: 300px;'>
            $opt2
            </select>
        </td>
    </tr>
    <tr><td colspan=4>
    <input type='hidden' name='WebOwnerUid' id='WebOwnerUid' value='$WebOwnerUid'>
  </td></tr>
    </table>
    ";
    $xoopsTpl->assign('op', 'create_by_user');
    $xoopsTpl->assign('form', $form);
}

//檢查某人是否沒有網頁
function no_web($uid = '')
{
    global $xoopsDB;

    $sql         = "select count(*) from " . $xoopsDB->prefix("tad_web") . " where WebOwnerUid='$uid'";
    $result      = $xoopsDB->query($sql) or web_error($sql);
    list($count) = $xoopsDB->fetchRow($result);
    if (empty($count)) {
        return true;
    }
    return false;
}

//批次開啟網頁
function batch_add_class_by_user()
{
    global $xoopsDB;
    //檢查有無班級網頁群組
    $groupid     = chk_tad_web_group(_MA_TCW_GROUP_NAME);
    $WebOwnerUid = explode(",", $_REQUEST['WebOwnerUid']);
    foreach ($WebOwnerUid as $uid) {
        if (empty($uid)) {
            continue;
        }

        $uid_name = XoopsUser::getUnameFromId($uid, 1);
        if (empty($uid_name)) {
            $uid_name = XoopsUser::getUnameFromId($uid, 0);
        }

        $WebName = $WebTitle = sprintf(_MA_TCW_SOMEBODY_WEB, $uid_name);

        if (no_web($uid)) {
            insert_tad_web(0, $WebName, $i, '1', $uid_name, $uid, $WebTitle);

            $sql = "replace into " . $xoopsDB->prefix("groups_users_link") . " (`uid` , `groupid`) values('{$uid}' , '{$groupid}')";
            $xoopsDB->queryF($sql) or web_error($sql);
            $i++;
        }
    }
}

//大量開設個人網頁
function create_by_amount()
{
    global $xoopsTpl;

    //檢查有無班級網頁群組
    $groupid = chk_tad_web_group(_MA_TCW_GROUP_NAME);

    $xoopsTpl->assign('op', 'create_by_amount');
}

//快速開設班級網頁
function create_by_class()
{
    global $xoopsTpl;

    //檢查有無班級網頁群組
    $groupid = chk_tad_web_group(_MA_TCW_GROUP_NAME);

    $xoopsTpl->assign('op', 'create_by_class');
}

//檢查有無班級網頁群組
function chk_tad_web_group($name = "")
{
    global $xoopsDB;
    $sql           = "select groupid from " . $xoopsDB->prefix("groups") . " where `name`='$name'";
    $result        = $xoopsDB->query($sql) or web_error($sql);
    list($groupid) = $xoopsDB->fetchRow($result);

    if (empty($groupid)) {
        $sql = "insert into " . $xoopsDB->prefix("groups") . " (`name`,`description`) values('{$name}','" . _MA_TCW_GROUP_DESC . "')";
        $xoopsDB->queryF($sql) or web_error($sql);
        //取得最後新增資料的流水編號

        $groupid = $xoopsDB->getInsertId();
    }
    return $groupid;
}

//tad_web編輯表單
function tad_web_form($WebID = null)
{
    global $xoopsDB, $xoopsUser, $xoopsTpl, $TadUpFiles;
    $pic = "";
    //抓取預設值
    if (!empty($WebID)) {
        $DBV = get_tad_web($WebID);
        //圖案
        // $TadUpFiles->set_col("WebLogo", $WebID, "1");
        // $web_logo = $TadUpFiles->get_pic_file("thumb");
        // $pic      = empty($web_logo) ? "" : "background-image:url($web_logo);background-repeat: no-repeat;  background-position: top right;";
    } else {
        $DBV = array();
    }

    //預設值設定

    //設定「WebID」欄位預設值
    $WebID = (!isset($DBV['WebID'])) ? $WebID : $DBV['WebID'];

    //設定「WebName」欄位預設值
    $WebName = (!isset($DBV['WebName'])) ? "" : $DBV['WebName'];

    //設定「WebSort」欄位預設值
    $WebSort = (!isset($DBV['WebSort'])) ? tad_web_max_sort() : $DBV['WebSort'];

    //設定「WebEnable」欄位預設值
    $WebEnable = (!isset($DBV['WebEnable'])) ? "" : $DBV['WebEnable'];

    //設定「WebCounter」欄位預設值
    $WebCounter = (!isset($DBV['WebCounter'])) ? "" : $DBV['WebCounter'];

    //設定「WebOwner」欄位預設值
    $WebOwner = (!isset($DBV['WebOwner'])) ? "" : $DBV['WebOwner'];

    //設定「WebOwnerUid」欄位預設值
    $WebOwnerUid = (!isset($DBV['WebOwnerUid'])) ? "" : $DBV['WebOwnerUid'];

    //設定「WebTitle」欄位預設值
    $WebTitle = (!isset($DBV['WebTitle'])) ? "" : $DBV['WebTitle'];

    $op = (empty($WebID)) ? "insert_tad_web" : "update_tad_web";
    //$op="replace_tad_web";

    if (!file_exists(TADTOOLS_PATH . "/formValidator.php")) {
        redirect_header("index.php", 3, _MA_NEED_TADTOOLS);
    }
    include_once TADTOOLS_PATH . "/formValidator.php";
    $formValidator      = new formValidator("#myForm", true);
    $formValidator_code = $formValidator->render();

    $sql    = "select uid,uname,name from " . $xoopsDB->prefix("users") . " order by uname";
    $result = $xoopsDB->query($sql) or web_error($sql);

    $user_menu = "<select name='WebOwnerUid'>";
    while ($all = $xoopsDB->fetchArray($result)) {
        foreach ($all as $k => $v) {
            $$k = $v;
        }
        $name     = empty($name) ? "" : "（{$name}）";
        $selected = ($uid == $WebOwnerUid) ? "selected" : "";
        $user_menu .= "<option value='$uid' $selected>{$uname} {$name}</option>";
    }

    $user_menu .= "</select>";

    //$jquery = get_jquery(true);
    $xoopsTpl->assign('pic', $pic);
    $xoopsTpl->assign('user_menu', $user_menu);
    $xoopsTpl->assign('WebName', $WebName);
    $xoopsTpl->assign('WebTitle', $WebTitle);
    $xoopsTpl->assign('WebOwner', $WebOwner);
    $xoopsTpl->assign('WebEnable1', chk($WebEnable, "1", "1"));
    $xoopsTpl->assign('WebEnable0', chk($WebEnable, "0"));
    $xoopsTpl->assign('WebSort', $WebSort);
    $xoopsTpl->assign('WebID', $WebID);
    $xoopsTpl->assign('next_op', $op);
    $xoopsTpl->assign('jquery', $jquery);

}

//自動取得tad_web的最新排序
function tad_web_max_sort()
{
    global $xoopsDB;
    $sql        = "select max(`WebSort`) from " . $xoopsDB->prefix("tad_web");
    $result     = $xoopsDB->query($sql) or web_error($sql);
    list($sort) = $xoopsDB->fetchRow($result);
    return ++$sort;
}

//新增資料到tad_web中
function insert_tad_web($CateID = "", $WebName = "", $WebSort = "", $WebEnable = "", $WebOwner = "", $WebOwnerUid = "", $WebTitle = "", $WebYear = "")
{
    global $xoopsDB, $xoopsUser;

    if (empty($WebOwner)) {
        $WebOwner = XoopsUser::getUnameFromId($WebOwnerUid, 1);
        if (empty($WebOwner)) {
            $WebOwner = XoopsUser::getUnameFromId($WebOwnerUid, 0);
        }

    }

    $myts     = &MyTextSanitizer::getInstance();
    $WebName  = $myts->addSlashes($WebName);
    $WebTitle = $myts->addSlashes($WebTitle);
    $WebOwner = $myts->addSlashes($WebOwner);

    if (empty($WebYear)) {
        $WebYear = date('Y');
    }

    $WebSort = intval($$WebSort);

    $sql = "insert into " . $xoopsDB->prefix("tad_web") . "
    (`CateID`, `WebName`, `WebSort`, `WebEnable`, `WebCounter`, `WebOwner`, `WebOwnerUid`, `WebTitle`, `CreatDate`, `WebYear`)
    values('{$CateID}' , '{$WebName}' , '{$WebSort}', '{$WebEnable}', '0' , '{$WebOwner}', '{$WebOwnerUid}', '{$WebTitle}', now() , '{$WebYear}')";
    $xoopsDB->query($sql) or web_error($sql);

    //取得最後新增資料的流水編號
    $WebID = $xoopsDB->getInsertId();
    mklogoPic($WebID);
    $TadUpFilesLogo = TadUpFilesLogo($WebID);
    $TadUpFilesLogo->set_col('logo', $WebID, 1);
    $TadUpFilesLogo->del_files();

    $TadUpFilesLogo->import_one_file(XOOPS_ROOT_PATH . "/uploads/tad_web/{$WebID}/auto_logo/auto_logo.png", null, 1280, 150, null, 'auto_logo.png', false);
    output_head_file($WebID);
    return $WebID;
}

//更新tad_web某一筆資料
function update_tad_web($WebID = "")
{
    global $xoopsDB, $xoopsUser;

    $myts              = &MyTextSanitizer::getInstance();
    $_POST['WebName']  = $myts->addSlashes($_POST['WebName']);
    $_POST['WebTitle'] = $myts->addSlashes($_POST['WebTitle']);

    $WebOwner = XoopsUser::getUnameFromId($_POST['WebOwnerUid'], 1);
    if (empty($WebOwner)) {
        $WebOwner = XoopsUser::getUnameFromId($_POST['WebOwnerUid'], 0);
    }
    $sql = "update " . $xoopsDB->prefix("tad_web") . " set
    `WebName` = '{$_POST['WebName']}' ,
    `WebSort` = '{$_POST['WebSort']}' ,
    `WebEnable` = '{$_POST['WebEnable']}' ,
    `WebOwner` = '{$WebOwner}' ,
    `WebOwnerUid` = '{$_POST['WebOwnerUid']}' ,
    `WebTitle` = '{$_POST['WebTitle']}'
    where WebID='$WebID'";
    $xoopsDB->queryF($sql) or web_error($sql);
    mklogoPic($WebID);
    $TadUpFilesLogo = TadUpFilesLogo($WebID);
    $TadUpFilesLogo->set_col('logo', $WebID, 1);
    $TadUpFilesLogo->del_files();

    $TadUpFilesLogo->import_one_file(XOOPS_ROOT_PATH . "/uploads/tad_web/{$WebID}/auto_logo/auto_logo.png", null, 1280, 150, null, 'auto_logo.png', false);
    output_head_file($WebID);

    return $WebID;
}

//刪除tad_web某筆資料資料確認
function delete_tad_web_chk($WebID = "")
{
    global $xoopsDB, $xoopsTpl;

    $sql        = "select b.`MemName` from " . $xoopsDB->prefix("tad_web_link_mems") . " as a left join " . $xoopsDB->prefix("tad_web_mems") . " as b on a.`MemID`=b.`MemID` where a.`WebID`='$WebID'";
    $result     = $xoopsDB->query($sql) or web_error($sql);
    $allMemName = "";
    while (list($MemName) = $xoopsDB->fetchRow($result)) {
        $allMemName .= "{$MemName} ,";
    }
    $xoopsTpl->assign('allMemName', $allMemName);

    $sql          = "select LinkID,LinkTitle from " . $xoopsDB->prefix("tad_web_link") . " where WebID='$WebID'";
    $result       = $xoopsDB->query($sql) or web_error($sql);
    $allLinkTitle = "<ol>";
    while (list($LinkID, $LinkTitle) = $xoopsDB->fetchRow($result)) {
        $allLinkTitle .= "<li><a href='../link.php?WebID=$WebID&LinkID=$LinkID'>$LinkTitle</a></li>";
    }
    $allLinkTitle .= "</ol>";
    $xoopsTpl->assign('allLinkTitle', $allLinkTitle);

    $sql          = "select NewsID,NewsTitle from " . $xoopsDB->prefix("tad_web_news") . " where WebID='$WebID'";
    $result       = $xoopsDB->query($sql) or web_error($sql);
    $allNewsTitle = "<ol>";
    while (list($NewsID, $NewsTitle) = $xoopsDB->fetchRow($result)) {
        $allNewsTitle .= "<li><a href='../news.php?WebID=$WebID&NewsID=$NewsID'>$NewsTitle</a></li>";
    }
    $allNewsTitle .= "</ol>";
    $xoopsTpl->assign('allNewsTitle', $allNewsTitle);

    $sql           = "select ActionID,ActionName from " . $xoopsDB->prefix("tad_web_action") . " where WebID='$WebID'";
    $result        = $xoopsDB->query($sql) or web_error($sql);
    $allActionName = "<ol>";
    while (list($ActionID, $ActionName) = $xoopsDB->fetchRow($result)) {
        $allActionName .= "<li><a href='../action.php?WebID=$WebID&ActionID=$ActionID'>$ActionName</a></li>";
    }
    $allActionName .= "</ol>";
    $xoopsTpl->assign('allActionName', $allActionName);

    $sql         = "select b.files_sn,b.description from " . $xoopsDB->prefix("tad_web_files") . " as a left join " . $xoopsDB->prefix("tad_web_files_center") . " as b on a.fsn=b.col_sn where a.WebID='$WebID' and b.col_name='fsn'";
    $result      = $xoopsDB->query($sql) or web_error($sql);
    $allFileName = "<ol>";
    while (list($files_sn, $file_name) = $xoopsDB->fetchRow($result)) {
        $allFileName .= "<li><a href='../files.php?WebID=$WebID&fop=dl&files_sn={$files_sn}'>$file_name</a></li>";
    }
    $allFileName .= "</ol>";
    $xoopsTpl->assign('allFileName', $allFileName);

    $sql          = "select VideoID,VideoName from " . $xoopsDB->prefix("tad_web_video") . " where WebID='$WebID'";
    $result       = $xoopsDB->query($sql) or web_error($sql);
    $allVideoName = "<ol>";
    while (list($VideoID, $VideoName) = $xoopsDB->fetchRow($result)) {
        $allVideoName .= "<li><a href='../video.php?WebID=$WebID&VideoID=$VideoID'>$VideoName</a></li>";
    }
    $allLinkTitle .= "</ol>";
    $xoopsTpl->assign('allVideoName', $allVideoName);

    $sql             = "select DiscussID,DiscussTitle from " . $xoopsDB->prefix("tad_web_discuss") . " where WebID='$WebID'";
    $result          = $xoopsDB->query($sql) or web_error($sql);
    $allDiscussTitle = "<ol>";
    while (list($DiscussID, $DiscussTitle) = $xoopsDB->fetchRow($result)) {
        $allDiscussTitle .= "<li><a href='../discuss.php?WebID=$WebID&DiscussID=$DiscussID'>$DiscussTitle</a></li>";
    }
    $allDiscussTitle .= "</ol>";
    $xoopsTpl->assign('allDiscussTitle', $allDiscussTitle);

    $xoopsTpl->assign('WebID', $WebID);
    $xoopsTpl->assign('op', 'delete_tad_web_chk');

}

//刪除tad_web某筆資料資料
function delete_tad_web($WebID = "")
{
    global $xoopsDB, $TadUpFiles;

    //刪除影片
    $sql = "delete from " . $xoopsDB->prefix("tad_web_video") . " where WebID='$WebID'";
    $xoopsDB->queryF($sql) or web_error($sql);

    //刪除討論
    $sql = "delete from " . $xoopsDB->prefix("tad_web_discuss") . " where WebID='$WebID'";
    $xoopsDB->queryF($sql) or web_error($sql);

    //刪除連結
    $sql = "delete from " . $xoopsDB->prefix("tad_web_link") . " where WebID='$WebID'";
    $xoopsDB->queryF($sql) or web_error($sql);

    //刪除會員
    $sql    = "select MemID from " . $xoopsDB->prefix("tad_web_link_mems") . " where WebID='$WebID'";
    $result = $xoopsDB->queryF($sql) or web_error($sql);
    while (list($MemID) = $xoopsDB->fetchRow($result)) {
        $sql = "delete from " . $xoopsDB->prefix("tad_web_mems") . " where MemID='$MemID'";
        $xoopsDB->queryF($sql) or web_error($sql);
        $TadUpFiles->set_col("MemID", $MemID);
        $TadUpFiles->del_files();
    }
    $sql = "delete from " . $xoopsDB->prefix("tad_web_link_mems") . " where WebID='$WebID'";
    $xoopsDB->queryF($sql) or web_error($sql);

    //刪除消息
    $sql    = "select NewsID from " . $xoopsDB->prefix("tad_web_news") . " where WebID='$WebID'";
    $result = $xoopsDB->queryF($sql) or web_error($sql);
    while (list($NewsID) = $xoopsDB->fetchRow($result)) {
        $TadUpFiles->set_col("NewsID", $NewsID);
        $TadUpFiles->del_files();
    }
    $sql = "delete from " . $xoopsDB->prefix("tad_web_news") . " where WebID='$WebID'";
    $xoopsDB->queryF($sql) or web_error($sql);

    //刪除活動
    $sql    = "select ActionID from " . $xoopsDB->prefix("tad_web_action") . " where WebID='$WebID'";
    $result = $xoopsDB->queryF($sql) or web_error($sql);
    while (list($ActionID) = $xoopsDB->fetchRow($result)) {

        $TadUpFiles->set_col("ActionID", $ActionID);
        $TadUpFiles->del_files();
    }
    $sql = "delete from " . $xoopsDB->prefix("tad_web_action") . " where WebID='$WebID'";
    $xoopsDB->queryF($sql) or web_error($sql);

    //刪除檔案
    $sql    = "select fsn from " . $xoopsDB->prefix("tad_web_files") . " where WebID='$WebID'";
    $result = $xoopsDB->queryF($sql) or web_error($sql);
    while (list($fsn) = $xoopsDB->fetchRow($result)) {

        $TadUpFiles->set_col("fsn", $fsn);
        $TadUpFiles->del_files();

        $sql = "delete from " . $xoopsDB->prefix("tad_web_files") . " where fsn='$fsn'";
        $xoopsDB->queryF($sql) or web_error($sql);
    }

    //刪除網站
    $sql               = "select WebOwnerUid from " . $xoopsDB->prefix("tad_web") . " where WebID='$WebID'";
    $result            = $xoopsDB->queryF($sql) or web_error($sql);
    list($WebOwnerUid) = $xoopsDB->fetchRow($result);

    $sql = "delete from " . $xoopsDB->prefix("tad_web") . " where WebID='$WebID'";
    $xoopsDB->queryF($sql) or web_error($sql);

    $TadUpFiles->set_col("WebOwner", $WebOwnerUid);
    $TadUpFiles->del_files();
}

function save_webs_title($webTitles = array(), $old_webTitle = array())
{
    global $xoopsDB, $TadUpFiles;

    $myts = &MyTextSanitizer::getInstance();
    foreach ($webTitles as $WebID => $WebTitle) {
        if ($old_webTitle[$WebID] != $WebTitle) {
            $WebTitle = $myts->addSlashes($WebTitle);
            $sql      = "update " . $xoopsDB->prefix("tad_web") . " set `WebTitle` = '{$WebTitle}' where WebID='$WebID'";
            $xoopsDB->queryF($sql) or web_error($sql);

            mklogoPic($WebID);
            $TadUpFilesLogo = TadUpFilesLogo($WebID);
            $TadUpFilesLogo->set_col('logo', $WebID, 1);
            $TadUpFilesLogo->del_files();

            $TadUpFilesLogo->import_one_file(XOOPS_ROOT_PATH . "/uploads/tad_web/{$WebID}/auto_logo/auto_logo.png", null, 1280, 150, null, 'auto_logo.png', false);
            output_head_file($WebID);

            mk_menu_var_file($WebID);
        }
    }

    return $WebID;
}

function save_webs_able($WebID = "", $able = "")
{
    global $xoopsDB;

    $sql = "update " . $xoopsDB->prefix("tad_web") . " set `WebEnable` = '{$able}' where WebID='$WebID'";
    $xoopsDB->queryF($sql) or web_error($sql);

    return $WebID;
}

function order_by_teamtitle()
{
    global $xoopsDB;

    $sql    = "select WebID from " . $xoopsDB->prefix("tad_web") . " order by WebTitle";
    $result = $xoopsDB->queryF($sql) or web_error($sql);
    $i      = 1;
    while (list($WebID) = $xoopsDB->fetchRow($result)) {
        $sql = "update " . $xoopsDB->prefix("tad_web") . " set `WebSort` = '{$i}' where WebID='$WebID'";
        $xoopsDB->queryF($sql) or web_error($sql);
        $i++;
    }

}

/*-----------執行動作判斷區----------*/
include_once $GLOBALS['xoops']->path('/modules/system/include/functions.php');
$op     = system_CleanVars($_REQUEST, 'op', '', 'string');
$WebID  = system_CleanVars($_REQUEST, 'WebID', 0, 'int');
$CateID = system_CleanVars($_REQUEST, 'CateID', 0, 'int');

$xoopsTpl->assign('op', $_REQUEST['op']);

switch ($op) {
    /*---判斷動作請貼在下方---*/

    case "create_by_user":
        create_by_user();
        break;

    case "batch_add_class_by_user":
        batch_add_class_by_user();
        header("location: {$_SERVER['PHP_SELF']}");
        exit;
        break;

    case "save_webs_title":
        save_webs_title($_POST['webTitle'], $_POST['old_webTitle']);
        header("location: {$_SERVER['PHP_SELF']}");
        exit;
        break;

    //新增資料
    case "insert_tad_web":
        $WebID = insert_tad_web(0, $_POST['WebName'], $_POST['WebSort'], '1', "", $_POST['WebOwnerUid'], $_POST['WebTitle']);
        header("location: {$_SERVER['PHP_SELF']}");
        exit;
        break;

    //更新資料
    case "update_tad_web":
        update_tad_web($WebID);
        header("location: {$_SERVER['PHP_SELF']}");
        exit;
        break;

    //輸入表格
    case "add_tad_web_form":
        tad_web_form($WebID);
        break;

    //輸入表格
    case "tad_web_form":
        tad_web_form($WebID);
        break;

    //刪除資料
    case "delete_tad_web_chk":
        delete_tad_web_chk($WebID);
        break;

    //刪除資料
    case "delete_tad_web":
        delete_tad_web($WebID);
        header("location: {$_SERVER['PHP_SELF']}");
        exit;
        break;

    //刪除資料
    case "save_webs_able":
        save_webs_able($WebID, $_GET['able']);
        header("location: {$_SERVER['PHP_SELF']}");
        exit;
        break;

    //以正式名稱排序
    case "order_by_teamtitle":
        order_by_teamtitle();
        header("location: {$_SERVER['PHP_SELF']}");
        exit;
        break;

    //預設動作
    default:
        list_all_web($CateID);
        break;

        /*---判斷動作請貼在上方---*/
}

/*-----------秀出結果區--------------*/
include_once 'footer.php';
