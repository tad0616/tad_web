<?php
/*-----------引入檔案區--------------*/
include_once "header.php";
$plugin = "aboutus";
include_once "plugin_header.php";
include_once XOOPS_ROOT_PATH . "/header.php";
//$xoopsTpl->assign('plugin', $plugin);
/*-----------function區--------------*/

/*-----------執行動作判斷區----------*/
include_once $GLOBALS['xoops']->path('/modules/system/include/functions.php');
$op    = system_CleanVars($_REQUEST, 'op', '', 'string');
$MemID = system_CleanVars($_REQUEST, 'MemID', 0, 'int');

common_template($WebID);

switch ($op) {

    //新增資料
    case "insert":
        $tad_web_aboutus->insert();
        header("location: {$_SERVER['PHP_SELF']}?WebID={$WebID}");
        exit;
        break;

    //更新團員資料
    case "update":
        $tad_web_aboutus->update($MemID);
        header("location: {$_SERVER['PHP_SELF']}?WebID={$WebID}&op=edit_form");
        exit;
        break;

    //輸入表格
    case "edit_stu":
        $main = $tad_web_aboutus->edit_stu($MemID);
        die($main);
        break;

    //刪除資料
    case "delete":
        $tad_web_aboutus->delete($MemID);
        header("location: {$_SERVER['PHP_SELF']}?WebID={$WebID}");
        exit;
        break;

    case "import_excel":
        $tad_web_aboutus->import_excel($_FILES['importfile']['tmp_name']);
        break;

    case "import2DB":
        $tad_web_aboutus->import2DB();
        break;

    //儲存座位
    case "save_seat":
        $tad_web_aboutus->save_seat($MemID);
        header("location: {$_SERVER['PHP_SELF']}?WebID={$WebID}");
        exit;
        break;

    case "edit_form":
        $tad_web_aboutus->show_one('mem_adm');
        break;

    //預設動作
    default:
        if (empty($WebID)) {
            $tad_web_aboutus->list_all();
            $op = 'list_all';
        } else {
            $tad_web_aboutus->show_one();
            $op = 'show_one';
        }
        break;

}

/*-----------秀出結果區--------------*/
include_once 'footer.php';
include_once XOOPS_ROOT_PATH . '/footer.php';
