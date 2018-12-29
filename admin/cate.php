<?php
/*-----------引入檔案區--------------*/
$xoopsOption['template_main'] = "tad_web_adm_cate.tpl";
include_once 'header.php';
include_once "../function.php";
/*-----------function區--------------*/
//tad_web_cate編輯表單
function tad_web_cate_form($CateID = '')
{
    global $xoopsDB, $xoopsTpl, $isAdmin;

    //抓取預設值
    if (!empty($CateID) and is_numeric($CateID)) {
        $DBV = get_tad_web_cate($CateID);
    } else {
        $DBV = array();
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

    $op = empty($CateID) ? "save_tad_web_cate" : "update_tad_web_cate";
    //$op = "replace_tad_web_cate";

    //套用formValidator驗證機制
    if (!file_exists(TADTOOLS_PATH . "/formValidator.php")) {
        redirect_header("index.php", 3, _TAD_NEED_TADTOOLS);
    }
    include_once TADTOOLS_PATH . "/formValidator.php";
    $formValidator      = new formValidator("#myForm", true);
    $formValidator_code = $formValidator->render();

    $xoopsTpl->assign('action', $_SERVER["PHP_SELF"]);
    $xoopsTpl->assign('formValidator_code', $formValidator_code);
    $xoopsTpl->assign('now_op', 'tad_web_cate_form');
    $xoopsTpl->assign('next_op', $op);
}

//自動取得tad_web_cate的最新排序
function tad_web_cate_max_sort($WebID = '', $ColName = '', $ColSN = '')
{
    global $xoopsDB;
    $sql        = "select max(`CateSort`) from `" . $xoopsDB->prefix("tad_web_cate") . "` where WebID='{$WebID}' and  ColName='{$ColName}' and ColSN='{$ColSN}'";
    $result     = $xoopsDB->query($sql) or web_error($sql, __FILE__, _LINE__);
    list($sort) = $xoopsDB->fetchRow($result);
    return ++$sort;
}

//新增資料到tad_web_cate中
function save_tad_web_cate()
{
    global $xoopsDB, $xoopsUser, $isAdmin;

    $myts = MyTextSanitizer::getInstance();

    $CateID      = intval($_POST['CateID']);
    $WebID       = $_POST['WebID'];
    $CateName    = $myts->addSlashes($_POST['CateName']);
    $ColName     = $myts->addSlashes($_POST['ColName']);
    $ColSN       = $myts->addSlashes($_POST['ColSN']);
    $CateSort    = intval($_POST['CateSort']);
    $CateEnable  = intval($_POST['CateEnable']);
    $CateCounter = intval($_POST['CateCounter']);

    $sql = "insert into `" . $xoopsDB->prefix("tad_web_cate") . "` (
        `WebID`,
        `CateName`,
        `ColName`,
        `ColSN`,
        `CateSort`,
        `CateEnable`,
        `CateCounter`
    ) values(
        '{$WebID}',
        '{$CateName}',
        '{$ColName}',
        '{$ColSN}',
        '{$CateSort}',
        '{$CateEnable}',
        0
    )";
    $xoopsDB->query($sql) or web_error($sql, __FILE__, _LINE__);

    //取得最後新增資料的流水編號
    $CateID = $xoopsDB->getInsertId();

    return $CateID;
}

//更新tad_web_cate某一筆資料
function update_tad_web_cate($CateID = '')
{
    global $xoopsDB, $isAdmin, $xoopsUser;

    $myts     = MyTextSanitizer::getInstance();
    $CateName = $myts->addSlashes($_POST['CateName']);

    $sql = "update `" . $xoopsDB->prefix("tad_web_cate") . "` set
       `CateName` = '{$CateName}' where `CateID`='{$CateID}'";
    $xoopsDB->queryF($sql) or web_error($sql, __FILE__, _LINE__);

    return $CateID;
}

//更新tad_web_cate資料
function update_tad_web_cate_arr($CateID = '')
{
    global $xoopsDB, $isAdmin, $xoopsUser;

    $myts               = MyTextSanitizer::getInstance();
    $web_cate_arr       = $myts->addSlashes($_POST['web_cate_arr']);
    $web_cate_blank_arr = $myts->addSlashes($_POST['web_cate_blank_arr']);

    $web_cate_array = explode(',', $web_cate_arr);

    $i = 1;
    foreach ($web_cate_array as $WebID) {
        $sql = "update `" . $xoopsDB->prefix("tad_web") . "` set `CateID` = '{$CateID}', WebSort='{$i}' where `WebID` ='{$WebID}'";
        $xoopsDB->queryF($sql) or web_error($sql, __FILE__, _LINE__);
        $i++;
    }

    if ($web_cate_blank_arr) {
        $sql = "update `" . $xoopsDB->prefix("tad_web") . "` set
       `CateID` = '0' where `WebID` in($web_cate_blank_arr)";
        $xoopsDB->queryF($sql) or web_error($sql, __FILE__, _LINE__);
    }

    return $CateID;
}

//刪除tad_web_cate某筆資料資料
function delete_tad_web_cate($CateID = '')
{
    global $xoopsDB, $isAdmin;

    if (empty($CateID)) {
        return;
    }

    $sql = "update `" . $xoopsDB->prefix("tad_web") . "` set
       `CateID` = '0' where `CateID`='{$CateID}'";
    $xoopsDB->queryF($sql) or web_error($sql, __FILE__, _LINE__);

    $sql = "delete from `" . $xoopsDB->prefix("tad_web_cate") . "`
    where `CateID` = '{$CateID}'";
    $xoopsDB->queryF($sql) or web_error($sql, __FILE__, _LINE__);

}

//以流水號取得某筆tad_web_cate資料
function get_tad_web_cate($CateID = '')
{
    global $xoopsDB;

    if (empty($CateID)) {
        return;
    }

    $sql = "select * from `" . $xoopsDB->prefix("tad_web_cate") . "`
    where `CateID` = '{$CateID}'";
    $result = $xoopsDB->query($sql) or web_error($sql, __FILE__, _LINE__);
    $data   = $xoopsDB->fetchArray($result);
    return $data;
}

//列出所有tad_web_cate資料
function tad_web_list_cate()
{
    global $xoopsDB, $xoopsTpl, $isAdmin;

    $myts = MyTextSanitizer::getInstance();

    $sql    = "SELECT * FROM `" . $xoopsDB->prefix("tad_web_cate") . "` WHERE `WebID`='0' AND `ColName`='web_cate' AND `ColSN`='0' ORDER BY `CateSort`";
    $result = $xoopsDB->query($sql) or web_error($sql, __FILE__, _LINE__);

    $all_content = array();

    $i = 0;

    $web_cate = get_web_cate_arr();

    while ($all = $xoopsDB->fetchArray($result)) {
        //以下會產生這些變數： $CateID, $WebID, $CateName, $ColName, $ColSN, $CateSort, $CateEnable, $CateCounter
        foreach ($all as $k => $v) {
            $$k = $v;
        }

        //將是/否選項轉換為圖示
        $CateEnable = $CateEnable == 1 ? '<img src="' . XOOPS_URL . '/modules/tad_web/images/yes.gif" alt="' . _YES . '" title="' . _YES . '">' : '<img src="' . XOOPS_URL . '/modules/tad_web/images/no.gif" alt="' . _NO . '" title="' . _NO . '">';

        //過濾讀出的變數值
        $CateName = $myts->htmlSpecialChars($CateName);
        $ColName  = $myts->htmlSpecialChars($ColName);
        $ColSN    = $myts->htmlSpecialChars($ColSN);

        $all_content[$i]['CateID']      = $CateID;
        $all_content[$i]['WebID']       = $WebID;
        $all_content[$i]['CateName']    = $CateName;
        $all_content[$i]['ColName']     = $ColName;
        $all_content[$i]['ColSN']       = $ColSN;
        $all_content[$i]['CateSort']    = $CateSort;
        $all_content[$i]['CateEnable']  = $CateEnable;
        $all_content[$i]['CateCounter'] = $CateCounter;
        //die(var_export($web_cate[0]));
        if (is_array($web_cate)) {
            $all_content[$i]['repository']         = isset($web_cate[0]) ? $web_cate[0] : '';
            $all_content[$i]['destination']        = isset($web_cate[$CateID]) ? $web_cate[$CateID] : '';
            $all_content[$i]['web_cate_arr_str']   = isset($web_cate[$CateID]) ? implode(",", $web_cate[$CateID]['WebID']) : '';
            $all_content[$i]['web_cate_blank_arr'] = isset($web_cate[0]) ? implode(",", $web_cate[0]['WebID']) : '';
        }
        $i++;
    }

    //刪除確認的JS
    if (!file_exists(XOOPS_ROOT_PATH . "/modules/tadtools/sweet_alert.php")) {
        redirect_header("index.php", 3, _MA_NEED_TADTOOLS);
    }
    include_once XOOPS_ROOT_PATH . "/modules/tadtools/sweet_alert.php";
    $sweet_alert_obj = new sweet_alert();
    $sweet_alert_obj->render('delete_tad_web_cate_func',
        "{$_SERVER['PHP_SELF']}?op=delete_tad_web_cate&CateID=", "CateID");

    $xoopsTpl->assign('tad_web_cate_jquery_ui', get_jquery(true));
    $xoopsTpl->assign('action', $_SERVER['PHP_SELF']);
    $xoopsTpl->assign('isAdmin', $isAdmin);
    $xoopsTpl->assign('all_content', $all_content);
}

//更新排序
function update_tad_web_cate_sort()
{
    global $xoopsDB;
    $sort = 1;
    foreach ($_POST['tr'] as $CateID) {
        $sql = "update " . $xoopsDB->prefix("tad_web_cate") . " set `CateSort`='{$sort}' where `CateID`='{$CateID}'";
        $xoopsDB->queryF($sql) or die(_TAD_SORT_FAIL . " (" . date("Y-m-d H:i:s") . ")");
        $sort++;
    }
    return _TAD_SORTED . " (" . date("Y-m-d H:i:s") . ")";
}
/*-----------執行動作判斷區----------*/
include_once $GLOBALS['xoops']->path('/modules/system/include/functions.php');
$op     = system_CleanVars($_REQUEST, 'op', '', 'string');
$WebID  = system_CleanVars($_REQUEST, 'WebID', 0, 'int');
$CateID = system_CleanVars($_REQUEST, 'CateID', 0, 'int');

$xoopsTpl->assign('op', $op);

switch ($op) {
    /*---判斷動作請貼在下方---*/

    //新增資料
    case "save_tad_web_cate":
        $CateID = save_tad_web_cate();
        header("location: {$_SERVER['PHP_SELF']}");
        exit;
        break;

    //更新資料
    case "update_tad_web_cate":
        update_tad_web_cate($CateID);
        header("location: {$_SERVER['PHP_SELF']}");
        exit;
        break;

    //更新資料
    case "update_tad_web_cate_arr":
        update_tad_web_cate_arr($CateID);
        header("location: {$_SERVER['PHP_SELF']}");
        exit;
        break;

    //刪除資料
    case "delete_tad_web_cate":
        delete_tad_web_cate($CateID);
        header("location: {$_SERVER['PHP_SELF']}");
        exit;
        break;

    //預設動作
    default:
        tad_web_cate_form($CateID);
        tad_web_list_cate();
        break;

        /*---判斷動作請貼在上方---*/
}

/*-----------秀出結果區--------------*/
include_once 'footer.php';
