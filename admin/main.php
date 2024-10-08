<?php
use Xmf\Request;
use XoopsModules\Tadtools\FormValidator;
use XoopsModules\Tadtools\Utility;
use XoopsModules\Tad_web\WebCate;

/*-----------引入檔案區--------------*/
$xoopsOption['template_main'] = 'tad_web_adm_main.tpl';
require_once __DIR__ . '/header.php';
require_once dirname(__DIR__) . '/function.php';
require_once dirname(__DIR__) . '/class/WebCate.php';
/*-----------function區--------------*/

//環境檢查
function chk_evn()
{
    $error = [];
    if (!function_exists('imagecreatetruecolor')) {
        $error[_MA_TCW_NEED_IMAGECREATETURECOLOR] = _MA_TCW_NEED_IMAGECREATETURECOLOR_CONTENT;
    }

    if (!is_dir(XOOPS_ROOT_PATH . '/themes/for_tad_web_theme') and !is_dir(XOOPS_ROOT_PATH . '/themes/for_tad_web_theme_2')) {
        $error[_MA_TCW_NEED_THEME] = _MA_TCW_NEED_THEME_CONTENT;
    }

    $moduleHandler = xoops_getHandler('module');
    $ttxoopsModule = $moduleHandler->getByDirname('tadtools');
    $version = $ttxoopsModule->version();
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

    $and_cate = empty($defCateID) ? '' : "and CateID='{$defCateID}'";

    $sql = 'select * from ' . $xoopsDB->prefix('tad_web') . " where 1 $and_cate order by WebSort, last_accessed desc ,CreatDate desc";

    //getPageBar($原sql語法, 每頁顯示幾筆資料, 最多顯示幾個頁數選項);
    $PageBar = Utility::getPageBar($sql, 50, 10);
    $bar = $PageBar['bar'];
    $sql = $PageBar['sql'];
    $total = $PageBar['total'];
    $result = $xoopsDB->query($sql) or Utility::web_error($sql, __FILE__, __LINE__);
    $xoopsTpl->assign('bar', $bar);

    $data = [];
    $i = 1;

    while (false !== ($all = $xoopsDB->fetchArray($result))) {
        //以下會產生這些變數： $WebID , $WebName , $WebSort , $WebEnable , $WebCounter
        foreach ($all as $k => $v) {
            $$k = $v;
            $data[$i][$k] = $v;
        }

        $data[$i]['memAmount'] = memAmount($WebID);
        $data[$i]['uname'] = \XoopsUser::getUnameFromId($WebOwnerUid, 0);
        $web_admin_arr = get_web_roles($WebID, 'admin');
        if ($web_admin_arr) {
            $admin_str = implode("','", $web_admin_arr);
            $sql2 = 'SELECT `uid`,`name`,`uname`,`email` FROM ' . $xoopsDB->prefix('users') . " WHERE `uid` in('{$admin_str}')";

            $result2 = $xoopsDB->queryF($sql2) or Utility::web_error($sql2);
            $j = 0;
            $admin_arr = [];
            while (list($uid, $name, $uname, $email) = $xoopsDB->fetchRow($result2)) {
                $admin_arr[$j]['uid'] = $uid;
                $admin_arr[$j]['name'] = $name;
                $admin_arr[$j]['uname'] = $uname;
                $admin_arr[$j]['email'] = $email;
                $j++;
            }
        } else {
            $admin_arr = [];
        }
        $data[$i]['admin_arr'] = $admin_arr;
        $i++;
    }

    if (empty($data)) {
        $xoopsTpl->assign('op', 'create_web');
    } else {

        $jquery = Utility::get_jquery(true);
        $xoopsTpl->assign('WebYear', $WebYear);
        $xoopsTpl->assign('data', $data);
        $xoopsTpl->assign('jquery', $jquery);
        $xoopsTpl->assign('CateID', $defCateID);

        $WebCate = new WebCate('', 'web_cate', 'tad_web');
        $cate = $WebCate->get_tad_web_cate_arr();
        $xoopsTpl->assign('cate', $cate);
    }
}

//根據使用者來開網頁
function create_by_user()
{
    global $xoopsTpl, $xoopsDB;

    //檢查有無班級網頁群組
    $groupid = chk_tad_web_group(_MA_TCW_GROUP_NAME);
    $ok_uid = [];
    $sql = 'select uid from ' . $xoopsDB->prefix('groups_users_link') . " where `groupid`='$groupid' order by uid";
    $result = $xoopsDB->query($sql) or Utility::web_error($sql, __FILE__, __LINE__);
    while (list($uid) = $xoopsDB->fetchRow($result)) {
        if (!empty($uid)) {
            $ok_uid[$uid] = $uid;
        }
    }
    $WebOwnerUid = implode(',', $ok_uid);

    $sql = 'select uid,uname,name from ' . $xoopsDB->prefix('users') . ' order by uname';
    $result = $xoopsDB->query($sql) or Utility::web_error($sql, __FILE__, __LINE__);

    $myts = \MyTextSanitizer::getInstance();
    $opt = [];
    while (false !== ($all = $xoopsDB->fetchArray($result))) {
        foreach ($all as $k => $v) {
            $$k = $v;
        }
        $name = $myts->htmlSpecialChars($name);
        $uname = $myts->htmlSpecialChars($uname);
        $name = empty($name) ? '' : " ({$name})";
        if (in_array($uid, $ok_uid)) {
            $opt2[$uid] = "{$uname} {$name}";
        } else {
            $opt[$uid] = "{$uname} {$name}";
        }
    }

    $xoopsTpl->assign('opt', $opt);
    $xoopsTpl->assign('opt2', $opt2);
    $xoopsTpl->assign('WebOwnerUid', $WebOwnerUid);
}

//檢查某人是否沒有網頁
function no_web($uid = '')
{
    global $xoopsDB;

    $sql = 'select count(*) from ' . $xoopsDB->prefix('tad_web') . " where WebOwnerUid='$uid'";
    $result = $xoopsDB->query($sql) or Utility::web_error($sql, __FILE__, __LINE__);
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
    $groupid = chk_tad_web_group(_MA_TCW_GROUP_NAME);
    $WebOwnerUid = explode(',', $_REQUEST['WebOwnerUid']);
    foreach ($WebOwnerUid as $uid) {
        if (empty($uid)) {
            continue;
        }

        $uid_name = \XoopsUser::getUnameFromId($uid, 1);
        if (empty($uid_name)) {
            $uid_name = \XoopsUser::getUnameFromId($uid, 0);
        }

        $WebName = $WebTitle = sprintf(_MA_TCW_SOMEBODY_WEB, $uid_name);
        $i = 0;
        if (no_web($uid)) {
            insert_tad_web(0, $WebName, $i, '1', $uid_name, $uid, $WebTitle);
            $i++;
            $sql = 'replace into ' . $xoopsDB->prefix('groups_users_link') . " (`uid` , `groupid`) values('{$uid}' , '{$groupid}')";
            $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);
        }
    }
}

//檢查有無班級網頁群組
function chk_tad_web_group($name = '')
{
    global $xoopsDB;
    $sql = 'select groupid from ' . $xoopsDB->prefix('groups') . " where `name`='$name'";
    $result = $xoopsDB->query($sql) or Utility::web_error($sql, __FILE__, __LINE__);
    list($groupid) = $xoopsDB->fetchRow($result);

    if (empty($groupid)) {
        $sql = 'insert into ' . $xoopsDB->prefix('groups') . " (`name`,`description`) values('{$name}','" . _MA_TCW_GROUP_DESC . "')";
        $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);
        //取得最後新增資料的流水編號

        $groupid = $xoopsDB->getInsertId();
    }

    return $groupid;
}

//tad_web編輯表單
function tad_web_form($WebID = null)
{
    global $xoopsDB, $xoopsUser, $xoopsTpl, $TadUpFiles;
    $pic = '';
    //抓取預設值
    if (!empty($WebID)) {
        $DBV = get_tad_web($WebID);
    } else {
        $DBV = [];
    }

    //預設值設定

    //設定「WebID」欄位預設值
    $WebID = (!isset($DBV['WebID'])) ? $WebID : $DBV['WebID'];
    $xoopsTpl->assign('WebID', $WebID);

    //設定「WebName」欄位預設值
    $WebName = (!isset($DBV['WebName'])) ? '' : $DBV['WebName'];
    $xoopsTpl->assign('WebName', $WebName);

    //設定「WebSort」欄位預設值
    $WebSort = (!isset($DBV['WebSort'])) ? tad_web_max_sort() : $DBV['WebSort'];
    $xoopsTpl->assign('WebSort', $WebSort);

    //設定「WebEnable」欄位預設值
    $WebEnable = (!isset($DBV['WebEnable'])) ? '' : $DBV['WebEnable'];
    $xoopsTpl->assign('WebEnable', $WWebEnableebID);

    //設定「WebCounter」欄位預設值
    $WebCounter = (!isset($DBV['WebCounter'])) ? '' : $DBV['WebCounter'];
    $xoopsTpl->assign('WebCounter', $WebCounter);

    //設定「WebOwner」欄位預設值
    $WebOwner = (!isset($DBV['WebOwner'])) ? '' : $DBV['WebOwner'];
    $xoopsTpl->assign('WebOwner', $WebOwner);

    //設定「WebOwnerUid」欄位預設值
    $WebOwnerUid = (!isset($DBV['WebOwnerUid'])) ? '' : $DBV['WebOwnerUid'];
    $xoopsTpl->assign('WebOwnerUid', $WebOwnerUid);

    //設定「WebTitle」欄位預設值
    $WebTitle = (!isset($DBV['WebTitle'])) ? '' : $DBV['WebTitle'];
    $xoopsTpl->assign('WebTitle', $WebTitle);

    //設定「CateID」欄位預設值
    $CateID = (!isset($DBV['CateID'])) ? '' : $DBV['CateID'];
    $xoopsTpl->assign('CateID', $CateID);

    $op = (empty($WebID)) ? 'insert_tad_web' : 'update_tad_web';
    //$op="replace_tad_web";

    $FormValidator = new FormValidator('#myForm', true);
    $FormValidator->render();

    $sql = 'select uid,uname,name from ' . $xoopsDB->prefix('users') . ' order by uname';
    $result = $xoopsDB->query($sql) or Utility::web_error($sql, __FILE__, __LINE__);

    $user_menu = "<select name='WebOwnerUid' class='form-control'>";
    while (false !== ($all = $xoopsDB->fetchArray($result))) {
        foreach ($all as $k => $v) {
            $$k = $v;
        }
        $name = empty($name) ? '' : "（{$name}）";
        $selected = ($uid == $WebOwnerUid) ? 'selected' : '';
        $user_menu .= "<option value='$uid' $selected>{$uname} {$name}</option>";
    }

    $user_menu .= '</select>';

    //$jquery = Utility::get_jquery(true);
    $xoopsTpl->assign('pic', $pic);
    $xoopsTpl->assign('user_menu', $user_menu);
    $xoopsTpl->assign('WebEnable1', Utility::chk($WebEnable, '1', '1'));
    $xoopsTpl->assign('WebEnable0', Utility::chk($WebEnable, '0'));
    $xoopsTpl->assign('next_op', $op);

    $ys = get_seme();
    $last_year = $ys[0] - 1;
    $next_year = $ys[0] + 1;
    $xoopsTpl->assign('now_year', sprintf(_MD_TCW_SEME_CATE, $ys[0]));
    $xoopsTpl->assign('last_year', sprintf(_MD_TCW_SEME_CATE, $last_year));
    $xoopsTpl->assign('next_year', sprintf(_MD_TCW_SEME_CATE, $next_year));

    //網站設定
    $WebCate = new WebCate(0, 'web_cate', 'tad_web');
    $WebCate->set_col_md(3, 3);
    //cate_menu($defCateID = "", $mode = "form", $newCate = true, $change_page = false, $show_label = true, $show_tools = false, $show_select = true, $required = false, $default_opt = true)
    $cate_menu = $WebCate->cate_menu($CateID, 'page', false, false, false, false, true, true, false);
    $xoopsTpl->assign('cate_menu', $cate_menu);
}

//自動取得tad_web的最新排序
function tad_web_max_sort()
{
    global $xoopsDB;
    $sql = 'select max(`WebSort`) from ' . $xoopsDB->prefix('tad_web');
    $result = $xoopsDB->query($sql) or Utility::web_error($sql, __FILE__, __LINE__);
    list($sort) = $xoopsDB->fetchRow($result);

    return ++$sort;
}

//新增資料到tad_web中
function insert_tad_web($CateID = '', $WebName = '', $WebSort = '', $WebEnable = '', $WebOwner = '', $WebOwnerUid = '', $WebTitle = '', $WebYear = '', $year = '')
{
    global $xoopsDB;

    if (empty($WebOwner)) {
        $WebOwner = \XoopsUser::getUnameFromId($WebOwnerUid, 1);
        if (empty($WebOwner)) {
            $WebOwner = \XoopsUser::getUnameFromId($WebOwnerUid, 0);
        }
    }

    $WebName = $xoopsDB->escape($WebName);
    $WebOwner = $xoopsDB->escape($WebOwner);

    $and_year = empty($year) ? '' : "{$year} ";
    $WebTitle = $xoopsDB->escape($and_year . $WebTitle);

    if (empty($WebYear)) {
        $WebYear = date('Y');
    }

    $WebSort = (int) $$WebSort;

    $sql = 'insert into ' . $xoopsDB->prefix('tad_web') . "
    (`CateID`, `WebName`, `WebSort`, `WebEnable`, `WebCounter`, `WebOwner`, `WebOwnerUid`, `WebTitle`, `CreatDate`, `WebYear`,`used_size`, `last_accessed`)
    values('{$CateID}' , '{$WebName}' , '{$WebSort}', '{$WebEnable}', '0' , '{$WebOwner}', '{$WebOwnerUid}', '{$WebTitle}', now() , '{$WebYear}', 0, now()  )";
    $xoopsDB->query($sql) or Utility::web_error($sql, __FILE__, __LINE__);

    //取得最後新增資料的流水編號
    $WebID = $xoopsDB->getInsertId();

    //新增一個預設班級
    $WebCate = new WebCate($WebID, 'aboutus', 'tad_web_link_mems');
    $ClassCateID = $WebCate->save_tad_web_cate('', $WebTitle);

    save_one_web_title($WebID, $WebTitle);

    return $WebID;
}

//更新tad_web某一筆資料
function update_tad_web($WebID = '')
{
    global $xoopsDB;

    $WebName = $xoopsDB->escape($_POST['WebName']);
    $WebTitle = $xoopsDB->escape($_POST['WebTitle']);
    $CateID = (int) $_POST['CateID'];
    $WebSort = (int) $_POST['WebSort'];
    $WebEnable = (int) $_POST['WebEnable'];
    $WebOwnerUid = (int) $_POST['WebOwnerUid'];

    $WebOwner = \XoopsUser::getUnameFromId($WebOwnerUid, 1);
    if (empty($WebOwner)) {
        $WebOwner = \XoopsUser::getUnameFromId($WebOwnerUid, 0);
    }
    $sql = 'update ' . $xoopsDB->prefix('tad_web') . " set
    `CateID`='{$CateID}',
    `WebName` = '{$WebName}' ,
    `WebSort` = '{$WebSort}' ,
    `WebEnable` = '{$WebEnable}' ,
    `WebOwner` = '{$WebOwner}' ,
    `WebOwnerUid` = '{$WebOwnerUid}' ,
    `WebTitle` = '{$WebTitle}'
    where WebID='$WebID'";
    $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);

    unset($_SESSION['tad_web'][$WebID]);

    save_one_web_title($WebID, $WebTitle);

    return $WebID;
}

function save_webs_title($webTitles = [], $old_webTitle = [])
{
    foreach ($webTitles as $WebID => $WebTitle) {
        if ($old_webTitle[$WebID] != $WebTitle) {
            save_one_web_title($WebID, $WebTitle);
        }
    }

    return $WebID;
}

function save_one_web_title($WebID = '', $WebTitle = '')
{
    global $xoopsDB;

    $WebTitle = $xoopsDB->escape($WebTitle);
    $sql = 'update ' . $xoopsDB->prefix('tad_web') . " set `WebTitle` = '{$WebTitle}' where WebID='$WebID'";
    $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);

    $_SESSION['tad_web'][$WebID]['WebTitle'] = $WebTitle;

    //修改班級名稱
    $default_class = get_web_config('default_class', $WebID);
    if (!empty($WebID) and !empty($default_class)) {
        $sql = 'update ' . $xoopsDB->prefix('tad_web_cate') . " set `CateName` = '{$WebTitle}' where `CateID`='{$default_class}'";
        $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);
    }

    mk_menu_var_file($WebID);

    mklogoPic($WebID);
    $TadUpFilesLogo = TadUpFilesLogo($WebID);
    $TadUpFilesLogo->set_col('logo', $WebID, 1);
    $TadUpFilesLogo->del_files();

    $TadUpFilesLogo->import_one_file(XOOPS_ROOT_PATH . "/uploads/tad_web/{$WebID}/auto_logo/auto_logo.png", null, 1280, 150, null, 'auto_logo.png', false);

    output_head_file($WebID);
    output_head_file_480($WebID);
}

function save_webs_able($WebID = '', $able = '')
{
    global $xoopsDB;

    $sql = 'update ' . $xoopsDB->prefix('tad_web') . " set `WebEnable` = '{$able}' where WebID='$WebID'";
    $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);

    $_SESSION['tad_web'][$WebID]['WebEnable'] = $able;

    return $WebID;
}

function order_by_teamtitle()
{
    global $xoopsDB;

    $sql = 'select WebID from ' . $xoopsDB->prefix('tad_web') . ' order by WebTitle';
    $result = $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);
    $i = 1;
    while (list($WebID) = $xoopsDB->fetchRow($result)) {
        $sql = 'update ' . $xoopsDB->prefix('tad_web') . " set `WebSort` = '{$i}' where WebID='$WebID'";
        $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);
        $i++;
    }
}

/*-----------執行動作判斷區----------*/
$op = Request::getString('op');
$WebID = Request::getInt('WebID');
$CateID = Request::getInt('CateID');
$g2p = Request::getInt('g2p');

$xoopsTpl->assign('op', $op);
$xoopsTpl->assign('g2p', $g2p);

switch ($op) {
    /*---判斷動作請貼在下方---*/

    case 'create_by_user':
        create_by_user();
        break;

    case 'batch_add_class_by_user':
        batch_add_class_by_user();
        header("location: {$_SERVER['PHP_SELF']}");
        exit;

    case 'save_webs_title':
        save_webs_title($_POST['webTitle'], $_POST['old_webTitle']);
        header("location: {$_SERVER['PHP_SELF']}?g2p=$g2p");
        exit;

    //新增資料
    case 'insert_tad_web':
        $WebID = insert_tad_web($CateID, $_POST['WebName'], $_POST['WebSort'], '1', '', $_POST['WebOwnerUid'], $_POST['WebTitle'], '', $_POST['year']);
        header("location: {$_SERVER['PHP_SELF']}");
        exit;

    //更新資料
    case 'update_tad_web':
        update_tad_web($WebID);
        header("location: {$_SERVER['PHP_SELF']}?g2p=$g2p");
        exit;

    //輸入表格
    case 'add_tad_web_form':
        tad_web_form($WebID);
        break;

    //輸入表格
    case 'tad_web_form':
        tad_web_form($WebID);
        break;

    //刪除資料
    case 'delete_tad_web_chk':
        delete_tad_web_chk($WebID, $g2p);
        break;

    //刪除資料
    case 'delete_tad_web':
        delete_tad_web($WebID);
        header("location: {$_SERVER['PHP_SELF']}?g2p=$g2p");
        exit;

    //刪除資料
    case 'save_webs_able':
        save_webs_able($WebID, $_GET['able']);
        header("location: {$_SERVER['PHP_SELF']}");
        exit;

    //以班級名稱排序
    case 'order_by_teamtitle':
        order_by_teamtitle();
        header("location: {$_SERVER['PHP_SELF']}");
        exit;

    //預設動作
    default:
        list_all_web($CateID);
        break;
        /*---判斷動作請貼在上方---*/
}

/*-----------秀出結果區--------------*/
require_once __DIR__ . '/footer.php';
