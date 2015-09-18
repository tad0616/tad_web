<?php
/*-----------引入檔案區--------------*/
include_once "header.php";
if (!empty($_GET['WebID'])) {
    $xoopsOption['template_main'] = 'tad_web_calendar_b3.html';
} else {
    $xoopsOption['template_main'] = set_bootstrap('tad_web_calendar.html');
}
include_once XOOPS_ROOT_PATH . "/header.php";
/*-----------function區--------------*/

/*-----------執行動作判斷區----------*/
$op = (empty($_REQUEST['op'])) ? "" : $_REQUEST['op'];
common_template($WebID);
/*-----------秀出結果區--------------*/
include_once '/footer.php';
include_once XOOPS_ROOT_PATH . '/footer.php';
