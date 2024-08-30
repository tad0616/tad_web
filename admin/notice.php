<?php
use Xmf\Request;
use XoopsModules\Tadtools\CkEditor;
use XoopsModules\Tadtools\FormValidator;
use XoopsModules\Tadtools\SweetAlert;
use XoopsModules\Tadtools\Utility;
use XoopsModules\Tadtools\Wcag;
/*-----------引入檔案區--------------*/
$xoopsOption['template_main'] = 'tad_web_adm_notice.tpl';
require_once __DIR__ . '/header.php';
require_once dirname(__DIR__) . '/function.php';
require_once dirname(__DIR__) . '/class/WebCate.php';
/*-----------function區--------------*/
//tad_web_notice編輯表單
function tad_web_notice_form($NoticeID = '')
{
    global $xoopsDB, $xoopsTpl, $isAdmin;
    if (!$isAdmin) {
        redirect_header($_SERVER['PHP_SELF'], 3, _TAD_PERMISSION_DENIED);
    }

    //抓取預設值
    if (!empty($NoticeID)) {
        $DBV = get_tad_web_notice($NoticeID);
    } else {
        $DBV = [];
    }

    //預設值設定

    //設定 NoticeID 欄位的預設值
    $NoticeID = !isset($DBV['NoticeID']) ? $NoticeID : $DBV['NoticeID'];
    $xoopsTpl->assign('NoticeID', $NoticeID);
    //設定 NoticeTitle 欄位的預設值
    $NoticeTitle = !isset($DBV['NoticeTitle']) ? '' : $DBV['NoticeTitle'];
    $xoopsTpl->assign('NoticeTitle', $NoticeTitle);
    //設定 NoticeContent 欄位的預設值
    $NoticeContent = !isset($DBV['NoticeContent']) ? '' : $DBV['NoticeContent'];
    $xoopsTpl->assign('NoticeContent', $NoticeContent);
    //設定 NoticeWeb 欄位的預設值
    $NoticeWeb = !isset($DBV['NoticeWeb']) ? '' : $DBV['NoticeWeb'];
    $xoopsTpl->assign('NoticeWeb', $NoticeWeb);
    //設定 NoticeWho 欄位的預設值
    $NoticeWho = !isset($DBV['NoticeWho']) ? explode(';', _MA_TADWEB_NOTICEWHO_DEF) : explode(';', $DBV['NoticeWho']);
    $xoopsTpl->assign('NoticeWho', $NoticeWho);
    //設定 NoticeDate 欄位的預設值
    $NoticeDate = !isset($DBV['NoticeDate']) ? date('Y-m-d H:i:s') : $DBV['NoticeDate'];
    $xoopsTpl->assign('NoticeDate', $NoticeDate);

    $op = empty($NoticeID) ? 'insert_tad_web_notice' : 'update_tad_web_notice';
    //$op = "replace_tad_web_notice";

    $FormValidator = new FormValidator('#myForm', true);
    $FormValidator->render();

    $CkEditor = new CkEditor('tad_web', 'NoticeContent', $NoticeContent);
    $CkEditor->setHeight(400);
    $NoticeContent_editor = $CkEditor->render();
    $xoopsTpl->assign('NoticeContent_editor', $NoticeContent_editor);

    //加入Token安全機制
    require_once XOOPS_ROOT_PATH . '/class/xoopsformloader.php';
    $token = new \XoopsFormHiddenToken();
    $token_form = $token->render();
    $xoopsTpl->assign('token_form', $token_form);
    $xoopsTpl->assign('action', $_SERVER['PHP_SELF']);

    $xoopsTpl->assign('now_op', 'tad_web_notice_form');
    $xoopsTpl->assign('next_op', $op);
}

//新增資料到tad_web_notice中
function insert_tad_web_notice()
{
    global $xoopsDB, $isAdmin;
    if (!$isAdmin) {
        redirect_header($_SERVER['PHP_SELF'], 3, _TAD_PERMISSION_DENIED);
    }

    //XOOPS表單安全檢查
    if (!$GLOBALS['xoopsSecurity']->check()) {
        $error = implode('<br>', $GLOBALS['xoopsSecurity']->getErrors());
        redirect_header($_SERVER['PHP_SELF'], 3, $error);
    }

    $NoticeID = (int) $_POST['NoticeID'];
    $NoticeTitle = $xoopsDB->escape($_POST['NoticeTitle']);
    $NoticeContent = $xoopsDB->escape($_POST['NoticeContent']);
    $NoticeContent = Wcag::amend($NoticeContent);
    $NoticeWeb = $xoopsDB->escape($_POST['NoticeWeb']);
    $NoticeWho = implode(';', $_POST['NoticeWho']);
    $NoticeDate = date('Y-m-d H:i:s', xoops_getUserTimestamp(time()));

    $sql = 'insert into `' . $xoopsDB->prefix('tad_web_notice') . "` (
        `NoticeTitle`,
        `NoticeContent`,
        `NoticeWeb`,
        `NoticeWho`,
        `NoticeDate`
    ) values(
        '{$NoticeTitle}',
        '{$NoticeContent}',
        '{$NoticeWeb}',
        '{$NoticeWho}',
        '{$NoticeDate}'
    )";
    $xoopsDB->query($sql) or Utility::web_error($sql, __FILE__, __LINE__);

    //取得最後新增資料的流水編號
    $NoticeID = $xoopsDB->getInsertId();

    return $NoticeID;
}

//更新tad_web_notice某一筆資料
function update_tad_web_notice($NoticeID = '')
{
    global $xoopsDB, $isAdmin;
    if (!$isAdmin) {
        redirect_header($_SERVER['PHP_SELF'], 3, _TAD_PERMISSION_DENIED);
    }

    //XOOPS表單安全檢查
    if (!$GLOBALS['xoopsSecurity']->check()) {
        $error = implode('<br>', $GLOBALS['xoopsSecurity']->getErrors());
        redirect_header($_SERVER['PHP_SELF'], 3, $error);
    }

    $NoticeID = (int) $_POST['NoticeID'];
    $NoticeTitle = $xoopsDB->escape($_POST['NoticeTitle']);
    $NoticeContent = $xoopsDB->escape($_POST['NoticeContent']);
    $NoticeWeb = $xoopsDB->escape($_POST['NoticeWeb']);
    $NoticeWho = implode(';', $_POST['NoticeWho']);
    $NoticeDate = date('Y-m-d H:i:s', xoops_getUserTimestamp(time()));

    $sql = 'update `' . $xoopsDB->prefix('tad_web_notice') . "` set
       `NoticeTitle` = '{$NoticeTitle}',
       `NoticeContent` = '{$NoticeContent}',
       `NoticeWeb` = '{$NoticeWeb}',
       `NoticeWho` = '{$NoticeWho}',
       `NoticeDate` = '{$NoticeDate}'
    where `NoticeID` = '$NoticeID'";
    $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);

    return $NoticeID;
}

//刪除tad_web_notice某筆資料資料
function delete_tad_web_notice($NoticeID = '')
{
    global $xoopsDB, $isAdmin;
    if (!$isAdmin) {
        redirect_header($_SERVER['PHP_SELF'], 3, _TAD_PERMISSION_DENIED);
    }

    if (empty($NoticeID)) {
        return;
    }

    $sql = 'delete from `' . $xoopsDB->prefix('tad_web_notice') . "`
    where `NoticeID` = '{$NoticeID}'";
    $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);
}

//以流水號秀出某筆tad_web_notice資料內容
function show_one_tad_web_notice($NoticeID = '')
{
    global $xoopsDB, $xoopsTpl, $isAdmin;

    if (empty($NoticeID)) {
        return;
    }
    $NoticeID = (int) $NoticeID;

    $myts = \MyTextSanitizer::getInstance();

    $sql = 'select * from `' . $xoopsDB->prefix('tad_web_notice') . "`
    where `NoticeID` = '{$NoticeID}' ";
    $result = $xoopsDB->query($sql)
    or Utility::web_error($sql, __FILE__, __LINE__);
    $all = $xoopsDB->fetchArray($result);

    //以下會產生這些變數： $NoticeID, $NoticeTitle, $NoticeContent, $NoticeWeb, $NoticeWho, $NoticeDate
    foreach ($all as $k => $v) {
        $$k = $v;
    }

    //過濾讀出的變數值
    $NoticeTitle = $myts->htmlSpecialChars($NoticeTitle);
    $NoticeContent = $myts->displayTarea($NoticeContent, 1, 1, 0, 1, 0);
    $NoticeWeb = $myts->displayTarea($NoticeWeb, 0, 1, 0, 1, 1);
    $NoticeWho_arr = explode(';', $NoticeWho);

    $xoopsTpl->assign('NoticeID', $NoticeID);
    $xoopsTpl->assign('NoticeTitle', $NoticeTitle);
    $xoopsTpl->assign('NoticeContent', $NoticeContent);
    $xoopsTpl->assign('NoticeWeb', nl2br($NoticeWeb));
    $xoopsTpl->assign('NoticeWho', $NoticeWho);
    $xoopsTpl->assign('NoticeDate', $NoticeDate);

    $SweetAlert = new SweetAlert();
    $SweetAlert->render('delete_tad_web_notice_func', "{$_SERVER['PHP_SELF']}?op=delete_tad_web_notice&NoticeID=", 'NoticeID');

    $xoopsTpl->assign('action', $_SERVER['PHP_SELF']);
    $xoopsTpl->assign('now_op', 'show_one_tad_web_notice');
}

//列出所有tad_web_notice資料
function list_tad_web_notice()
{
    global $xoopsDB, $xoopsTpl, $isAdmin;

    $myts = \MyTextSanitizer::getInstance();

    $sql = 'SELECT * FROM `' . $xoopsDB->prefix('tad_web_notice') . '` ORDER BY `NoticeTitle`';

    //getPageBar($原sql語法, 每頁顯示幾筆資料, 最多顯示幾個頁數選項);
    $PageBar = Utility::getPageBar($sql, 20, 10, null, null, 3);
    $bar = $PageBar['bar'];
    $sql = $PageBar['sql'];
    $total = $PageBar['total'];

    $result = $xoopsDB->query($sql) or Utility::web_error($sql, __FILE__, __LINE__);

    $all_content = [];
    $i = 0;
    while (false !== ($all = $xoopsDB->fetchArray($result))) {
        //以下會產生這些變數： $NoticeID, $NoticeTitle, $NoticeContent, $NoticeWeb, $NoticeWho, $NoticeDate
        foreach ($all as $k => $v) {
            $$k = $v;
        }

        //過濾讀出的變數值
        $NoticeTitle = $myts->htmlSpecialChars($NoticeTitle);
        $NoticeContent = $myts->displayTarea($NoticeContent, 1, 1, 0, 1, 0);
        $NoticeWeb = $myts->displayTarea($NoticeWeb, 0, 1, 0, 1, 1);
        $NoticeWho_arr = explode(';', $NoticeWho);

        $all_content[$i]['NoticeID'] = $NoticeID;
        $all_content[$i]['NoticeTitle'] = $NoticeTitle;
        $all_content[$i]['NoticeContent'] = $NoticeContent;
        $all_content[$i]['NoticeWeb'] = $NoticeWeb;
        $all_content[$i]['NoticeWho'] = $NoticeWho;
        $all_content[$i]['NoticeDate'] = $NoticeDate;
        $i++;
    }

    $SweetAlert = new SweetAlert();
    $SweetAlert->render(
        'delete_tad_web_notice_func',
        "{$_SERVER['PHP_SELF']}?op=delete_tad_web_notice&NoticeID=",
        'NoticeID'
    );

    $xoopsTpl->assign('bar', $bar);
    $xoopsTpl->assign('action', $_SERVER['PHP_SELF']);
    $xoopsTpl->assign('isAdmin', $isAdmin);
    $xoopsTpl->assign('all_content', $all_content);
    $xoopsTpl->assign('now_op', 'list_tad_web_notice');
}

/*-----------執行動作判斷區----------*/
$op = Request::getString('op');
$WebID = Request::getInt('WebID');
$CateID = Request::getInt('CateID');
$NoticeID = Request::getInt('NoticeID');

$xoopsTpl->assign('op', $op);

switch ($op) {
    /*---判斷動作請貼在下方---*/

    //新增資料
    case 'insert_tad_web_notice':
        $NoticeID = insert_tad_web_notice();
        clear_tad_web_notice();
        header("location: {$_SERVER['PHP_SELF']}?NoticeID=$NoticeID");
        exit;

    //更新資料
    case 'update_tad_web_notice':
        update_tad_web_notice($NoticeID);
        clear_tad_web_notice();
        header("location: {$_SERVER['PHP_SELF']}?NoticeID=$NoticeID");
        exit;

    case 'tad_web_notice_form':
        tad_web_notice_form($NoticeID);
        break;

    case 'delete_tad_web_notice':
        delete_tad_web_notice($NoticeID);
        clear_tad_web_notice();
        header("location: {$_SERVER['PHP_SELF']}");
        exit;

    default:
        if (empty($NoticeID)) {
            list_tad_web_notice();
        } else {
            show_one_tad_web_notice($NoticeID);
        }
        break;
        /*---判斷動作請貼在上方---*/
}

/*-----------秀出結果區--------------*/
require_once __DIR__ . '/footer.php';
