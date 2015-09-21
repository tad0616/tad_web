<?php
/*-----------引入檔案區--------------*/
include_once "header.php";
if (!empty($_GET['WebID'])) {
    $xoopsOption['template_main'] = 'tad_web_cate_b3.html';
} else {
    header("location: index.php");
    exit;
}
include_once XOOPS_ROOT_PATH . "/header.php";
/*-----------function區--------------*/

//分類設定
function list_all_cate($WebID = "", $ColName = "")
{
    global $xoopsTpl;
    $web_cate = new web_cate($WebID, $ColName);
    $web_cate->set_WebID($WebID);
    $cate = $web_cate->get_tad_web_cate_arr();
/*
array (
13 =>
array (
'CateID' => '13',
'WebID' => '1',
'CateName' => '生活與藝術',
'ColName' => 'works',
'ColSN' => '0',
'CateSort' => '1',
'CateEnable' => '1',
'CateCounter' => '0',
),
21 =>
array (
'CateID' => '21',
'WebID' => '1',
'CateName' => '作文',
'ColName' => 'works',
'ColSN' => '0',
'CateSort' => '2',
'CateEnable' => '1',
'CateCounter' => '0',
),
)*/
    $xoopsTpl->assign('cate', $cate);
    $xoopsTpl->assign('ColName', $ColName);
    $xoopsTpl->assign('WebID', $WebID);

    if (!file_exists(TADTOOLS_PATH . "/formValidator.php")) {
        redirect_header("index.php", 3, _MD_NEED_TADTOOLS);
    }
    include_once TADTOOLS_PATH . "/formValidator.php";
    $formValidator      = new formValidator("#myForm", true);
    $formValidator_code = $formValidator->render();

}

/*-----------執行動作判斷區----------*/
include_once $GLOBALS['xoops']->path('/modules/system/include/functions.php');
$op      = system_CleanVars($_REQUEST, 'op', '', 'string');
$WebID   = system_CleanVars($_REQUEST, 'WebID', 0, 'int');
$ColName = system_CleanVars($_REQUEST, 'ColName', '', 'string');

common_template($WebID);

switch ($op) {

    case "save_cate":
        break;

    default:
        list_all_cate($WebID, $ColName);
        break;
}

/*-----------秀出結果區--------------*/
include_once 'footer.php';
include_once XOOPS_ROOT_PATH . '/footer.php';
