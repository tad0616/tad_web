<?php
use Xmf\Request;
use XoopsModules\Tadtools\FormValidator;
use XoopsModules\Tadtools\SweetAlert;
use XoopsModules\Tadtools\Utility;
/*-----------引入檔案區--------------*/
$xoopsOption['template_main'] = 'tad_web_adm_cate.tpl';
require_once __DIR__ . '/header.php';
require_once dirname(__DIR__) . '/function.php';
/*-----------執行動作判斷區----------*/
$op = Request::getString('op');
$WebID = Request::getInt('WebID');
$CateID = Request::getInt('CateID');

$xoopsTpl->assign('op', $op);

switch ($op) {

    //新增資料
    case 'save_tad_web_cate':
        $CateID = save_tad_web_cate();
        clear_block_cache($WebID);
        header("location: {$_SERVER['PHP_SELF']}");
        exit;

    //更新資料
    case 'update_tad_web_cate':
        update_tad_web_cate($CateID);
        clear_block_cache($WebID);
        header("location: {$_SERVER['PHP_SELF']}");
        exit;

    //更新資料
    case 'update_tad_web_cate_arr':
        update_tad_web_cate_arr($CateID);
        clear_block_cache($WebID);
        header("location: {$_SERVER['PHP_SELF']}");
        exit;

    //刪除資料
    case 'delete_tad_web_cate':
        delete_tad_web_cate($CateID);
        clear_block_cache($WebID);
        header("location: {$_SERVER['PHP_SELF']}");
        exit;

    //預設動作
    default:
        tad_web_cate_form($CateID);
        tad_web_list_cate();
        break;

}

/*-----------秀出結果區--------------*/
require_once __DIR__ . '/footer.php';

/*-----------function區--------------*/
//tad_web_cate編輯表單
function tad_web_cate_form($CateID = '')
{
    global $xoopsTpl;

    //抓取預設值
    if (!empty($CateID) and is_numeric($CateID)) {
        $DBV = get_tad_web_cate($CateID);
    } else {
        $DBV = [];
    }

    //預設值設定

    //設定 CateID 欄位的預設值
    $CateID = !isset($DBV['CateID']) ? $CateID : $DBV['CateID'];
    $xoopsTpl->assign('CateID', $CateID);
    //設定 WebID 欄位的預設值
    $WebID = !isset($DBV['WebID']) ? '' : $DBV['WebID'];
    $xoopsTpl->assign('WebID', $WebID);
    //設定 CateName 欄位的預設值
    $CateName = !isset($DBV['CateName']) ? '' : $DBV['CateName'];
    $xoopsTpl->assign('CateName', $CateName);
    //設定 ColName 欄位的預設值
    $ColName = !isset($DBV['ColName']) ? 'web_cate' : $DBV['ColName'];
    $xoopsTpl->assign('ColName', $ColName);
    //設定 ColSN 欄位的預設值
    $ColSN = !isset($DBV['ColSN']) ? '0' : $DBV['ColSN'];
    $xoopsTpl->assign('ColSN', $ColSN);
    //設定 CateSort 欄位的預設值
    $CateSort = !isset($DBV['CateSort']) ? tad_web_cate_max_sort($WebID, $ColName, $ColSN) : $DBV['CateSort'];
    $xoopsTpl->assign('CateSort', $CateSort);
    //設定 CateEnable 欄位的預設值
    $CateEnable = !isset($DBV['CateEnable']) ? '1' : $DBV['CateEnable'];
    $xoopsTpl->assign('CateEnable', $CateEnable);
    //設定 CateCounter 欄位的預設值
    $CateCounter = !isset($DBV['CateCounter']) ? '0' : $DBV['CateCounter'];
    $xoopsTpl->assign('CateCounter', $CateCounter);

    $op = empty($CateID) ? 'save_tad_web_cate' : 'update_tad_web_cate';
    //$op = "replace_tad_web_cate";

    $FormValidator = new FormValidator('#myForm', true);
    $FormValidator->render();

    $xoopsTpl->assign('action', $_SERVER['PHP_SELF']);
    $xoopsTpl->assign('now_op', 'tad_web_cate_form');
    $xoopsTpl->assign('next_op', $op);
}

//自動取得tad_web_cate的最新排序
function tad_web_cate_max_sort($WebID = '', $ColName = '', $ColSN = '')
{
    global $xoopsDB;
    $sql = 'SELECT MAX(`CateSort`) FROM `' . $xoopsDB->prefix('tad_web_cate') . '` WHERE `WebID`=? AND `ColName`=? AND `ColSN`=?';
    $result = Utility::query($sql, 'isi', [$WebID, $ColName, $ColSN]) or Utility::web_error($sql, __FILE__, __LINE__);

    list($sort) = $xoopsDB->fetchRow($result);

    return ++$sort;
}

//新增資料到tad_web_cate中
function save_tad_web_cate()
{
    global $xoopsDB;

    $CateID = (int) $_POST['CateID'];
    $WebID = (int) $_POST['WebID'];
    $CateName = $_POST['CateName'];
    $ColName = $_POST['ColName'];
    $ColSN = $_POST['ColSN'];
    $CateSort = (int) $_POST['CateSort'];
    $CateEnable = (int) $_POST['CateEnable'];
    $sql = 'INSERT INTO `' . $xoopsDB->prefix('tad_web_cate') . '` (`WebID`, `CateName`, `ColName`, `ColSN`, `CateSort`, `CateEnable`, `CateCounter`
    ) VALUES ( ?, ?, ?, ?, ?, ?, 0)';
    Utility::query($sql, 'issiis', [$WebID, $CateName, $ColName, $ColSN, $CateSort, $CateEnable]) or Utility::web_error($sql, __FILE__, __LINE__);

    //取得最後新增資料的流水編號
    $CateID = $xoopsDB->getInsertId();

    return $CateID;
}

//更新tad_web_cate某一筆資料
function update_tad_web_cate($CateID = '')
{
    global $xoopsDB;

    $CateName = $_POST['CateName'];

    $sql = 'UPDATE `' . $xoopsDB->prefix('tad_web_cate') . '` SET `CateName` = ? WHERE `CateID` = ?';
    Utility::query($sql, 'si', [$CateName, $CateID]) or Utility::web_error($sql, __FILE__, __LINE__);

    return $CateID;
}

//更新tad_web_cate資料
function update_tad_web_cate_arr($CateID = '')
{
    global $xoopsDB;

    $web_cate_arr = $_POST['web_cate_arr'];
    $web_cate_blank_arr = $_POST['web_cate_blank_arr'];

    $web_cate_array = explode(',', $web_cate_arr);

    $i = 1;
    foreach ($web_cate_array as $WebID) {
        $sql = 'UPDATE `' . $xoopsDB->prefix('tad_web') . '` SET `CateID` = ?, `WebSort` = ? WHERE `WebID` = ?';
        Utility::query($sql, 'iii', [$CateID, $i, $WebID]) or Utility::web_error($sql, __FILE__, __LINE__);
        $i++;
    }

    if ($web_cate_blank_arr) {
        $sql = 'UPDATE `' . $xoopsDB->prefix('tad_web') . '` SET `CateID` = ? WHERE `WebID` IN (?)';
        Utility::query($sql, 'is', [0, $web_cate_blank_arr]) or Utility::web_error($sql, __FILE__, __LINE__);
    }

    return $CateID;
}

//刪除tad_web_cate某筆資料資料
function delete_tad_web_cate($CateID = '')
{
    global $xoopsDB;

    if (empty($CateID)) {
        return;
    }

    $sql = 'UPDATE `' . $xoopsDB->prefix('tad_web') . '` SET `CateID` = 0 WHERE `CateID` = ?';
    Utility::query($sql, 'i', [$CateID]) or Utility::web_error($sql, __FILE__, __LINE__);

    $sql = 'DELETE FROM `' . $xoopsDB->prefix('tad_web_cate') . '` WHERE `CateID` = ?';
    Utility::query($sql, 'i', [$CateID]) or Utility::web_error($sql, __FILE__, __LINE__);

}

//以流水號取得某筆tad_web_cate資料
function get_tad_web_cate($CateID = '')
{
    global $xoopsDB;

    if (empty($CateID)) {
        return;
    }

    $sql = 'SELECT * FROM `' . $xoopsDB->prefix('tad_web_cate') . '` WHERE `CateID` = ?';
    $result = Utility::query($sql, 'i', [$CateID]) or Utility::web_error($sql, __FILE__, __LINE__);

    $data = $xoopsDB->fetchArray($result);

    return $data;
}

//列出所有tad_web_cate資料
function tad_web_list_cate()
{
    global $xoopsDB, $xoopsTpl;

    $myts = \MyTextSanitizer::getInstance();

    $sql = 'SELECT * FROM `' . $xoopsDB->prefix('tad_web_cate') . '` WHERE `WebID`=? AND `ColName`=? AND `ColSN`=? ORDER BY `CateSort`';
    $result = Utility::query($sql, 'isi', [0, 'web_cate', 0]) or Utility::web_error($sql, __FILE__, __LINE__);

    $all_content = [];

    $i = 0;

    $web_cate = get_web_cate_arr();

    while (false !== ($all = $xoopsDB->fetchArray($result))) {
        //以下會產生這些變數： $CateID, $WebID, $CateName, $ColName, $ColSN, $CateSort, $CateEnable, $CateCounter
        foreach ($all as $k => $v) {
            $$k = $v;
        }

        //將是/否選項轉換為圖示
        $CateEnable = 1 == $CateEnable ? '<img src="' . XOOPS_URL . '/modules/tad_web/images/yes.gif" alt="' . _YES . '" title="' . _YES . '">' : '<img src="' . XOOPS_URL . '/modules/tad_web/images/no.gif" alt="' . _NO . '" title="' . _NO . '">';

        //過濾讀出的變數值
        $CateName = $myts->htmlSpecialChars($CateName);
        $ColName = $myts->htmlSpecialChars($ColName);
        $ColSN = $myts->htmlSpecialChars($ColSN);

        $all_content[$i]['CateID'] = $CateID;
        $all_content[$i]['WebID'] = $WebID;
        $all_content[$i]['CateName'] = $CateName;
        $all_content[$i]['ColName'] = $ColName;
        $all_content[$i]['ColSN'] = $ColSN;
        $all_content[$i]['CateSort'] = $CateSort;
        $all_content[$i]['CateEnable'] = $CateEnable;
        $all_content[$i]['CateCounter'] = $CateCounter;
        if (is_array($web_cate)) {
            $all_content[$i]['repository'] = isset($web_cate[0]) ? $web_cate[0] : [];
            $all_content[$i]['destination'] = isset($web_cate[$CateID]) ? $web_cate[$CateID] : [];
            $all_content[$i]['web_cate_arr_str'] = isset($web_cate[$CateID]) ? implode(',', $web_cate[$CateID]['WebID']) : '';
            $all_content[$i]['web_cate_blank_arr'] = isset($web_cate[0]) ? implode(',', $web_cate[0]['WebID']) : '';
        }
        $i++;
    }

    $SweetAlert = new SweetAlert();
    $SweetAlert->render(
        'delete_tad_web_cate_func',
        "{$_SERVER['PHP_SELF']}?op=delete_tad_web_cate&CateID=",
        'CateID'
    );

    $xoopsTpl->assign('tad_web_cate_jquery_ui', Utility::get_jquery(true));
    $xoopsTpl->assign('action', $_SERVER['PHP_SELF']);

    $xoopsTpl->assign('all_content', $all_content);
}

//更新排序
function update_tad_web_cate_sort()
{
    global $xoopsDB;
    $sort = 1;
    foreach ($_POST['tr'] as $CateID) {
        $sql = 'UPDATE `' . $xoopsDB->prefix('tad_web_cate') . '` SET `CateSort`=? WHERE `CateID`=?';
        Utility::query($sql, 'ii', [$sort, $CateID]) or die(_TAD_SORT_FAIL . ' (' . date('Y-m-d H:i:s') . ')');
        $sort++;
    }

    return _TAD_SORTED . ' (' . date('Y-m-d H:i:s') . ')';
}
