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
$op          = system_CleanVars($_REQUEST, 'op', '', 'string');
$MemID       = system_CleanVars($_REQUEST, 'MemID', 0, 'int');
$year        = system_CleanVars($_REQUEST, 'year', '', 'string');
$newCateName = system_CleanVars($_REQUEST, 'newCateName', '', 'string');
$CateID      = system_CleanVars($_REQUEST, 'CateID', 0, 'int');

common_template($WebID, $web_all_config);

switch ($op) {

    //新增學生資料
    case "insert":
        $MemID = $tad_web_aboutus->insert();
        header("location: {$_SERVER['PHP_SELF']}?WebID={$WebID}&CateID={$CateID}&MemID={$MemID}&op=show_stu");
        exit;
        break;

    //更新學生資料
    case "update":
        $tad_web_aboutus->update($MemID);
        header("location: {$_SERVER['PHP_SELF']}?WebID={$WebID}&CateID={$CateID}&MemID={$MemID}&op=show_stu");
        exit;
        break;

    //刪除學生資料
    case "delete":
        $tad_web_aboutus->delete($MemID);
        header("location: {$_SERVER['PHP_SELF']}?WebID={$WebID}&CateID={$CateID}");
        exit;
        break;

    case "insert_class":
        $CateID = $tad_web_aboutus->insert_class($year, $newCateName);
        header("location: {$_SERVER['PHP_SELF']}?WebID={$WebID}&CateID={$CateID}");
        exit;
        break;

    case "update_class":
        $tad_web_aboutus->update_class($CateID, $year, $newCateName);
        header("location: {$_SERVER['PHP_SELF']}?WebID={$WebID}&CateID={$CateID}");
        exit;
        break;

    case "del_class":
        $tad_web_aboutus->del_class($CateID);
        header("location: {$_SERVER['PHP_SELF']}?WebID={$WebID}&op=edit_form");
        exit;
        break;

    case "edit_class_stu":
        $tad_web_aboutus->edit_class_stu($CateID);
        break;

    case "edit_position":
        $tad_web_aboutus->edit_position($CateID);
        break;

    case "import_excel_form":
        $tad_web_aboutus->import_excel_form($CateID);
        break;

    case "import_excel":
        $tad_web_aboutus->import_excel($_FILES['importfile']['tmp_name'], $CateID);
        break;

    case "import2DB":
        $tad_web_aboutus->import2DB($CateID);
        break;

    //儲存座位
    case "save_seat":
        $tad_web_aboutus->save_seat($MemID);
        header("location: {$_SERVER['PHP_SELF']}?WebID={$WebID}");
        exit;
        break;

    //登入
    case "reset_position":
        $tad_web_aboutus->reset_position($CateID);
        header("location: {$_SERVER['PHP_SELF']}?WebID={$WebID}&CateID={$CateID}&op=edit_position");
        exit;
        break;

    case "edit_form":
        $tad_web_aboutus->edit_form($CateID);
        break;

    case "edit_stu":
        $tad_web_aboutus->edit_stu($MemID, $CateID);
        break;

    case "show_stu":
        $tad_web_aboutus->show_stu($MemID, $CateID);
        break;

    //登入
    case "mem_login":
        $login = $tad_web_aboutus->mem_login($WebID, $_POST['MemUname'], $_POST['MemPasswd']);
        if ($login) {
            header("location: {$_SERVER['PHP_SELF']}?WebID={$WebID}&CateID={$_SESSION['LoginCateID']}&MemID={$_SESSION['LoginMemID']}&op=show_stu");
        } else {
            header("location: {$_SERVER['PHP_SELF']}?WebID={$WebID}");

        }
        exit;
        break;

    //登出
    case "mem_logout":
        $_SESSION['LoginMemID'] = $_SESSION['LoginMemName'] = $_SESSION['LoginMemNickName'] = $_SESSION['LoginWebID'] = "";
        $GLOBALS["sess_handler"]->regenerate_id(true);
        $_SESSION = array();
        setcookie($xoopsConfig['usercookie'], 0, -1, '/', XOOPS_COOKIE_DOMAIN, 0);
        setcookie($xoopsConfig['usercookie'], 0, -1, '/');
        // clear entry from online users table
        if (is_object($xoopsUser)) {
            $online_handler = &xoops_gethandler('online');
            $online_handler->destroy($xoopsUser->getVar('uid'));
        }
        header("location: {$_SERVER['PHP_SELF']}?WebID={$WebID}");
        exit;
        break;

    //更新照片
    case "update_photo":
        $TadUpFiles->set_col("WebOwner", $WebID, 1);
        $TadUpFiles->del_files();
        $TadUpFiles->upload_file('upfile', 480, 120, null, null, true);
        header("location: {$_SERVER['PHP_SELF']}?WebID={$WebID}");
        exit;
        break;

    case "new_class":
        $tad_web_aboutus->edit_form();
        break;

    //匯出設定
    case "export_config":
        $tad_web_aboutus->export_config();
        break;

    //預設動作
    default:
        if (empty($WebID)) {
            $tad_web_aboutus->list_all();
            $op = 'list_all';
        } else {
            if (!empty($MemID)) {
                $tad_web_aboutus->show_stu($MemID);
                $op = 'show_stu';
            } else {

                $default_class = get_web_config('default_class', $WebID);
                // if (empty($CateID)) {
                //     $CateID = $tad_web_aboutus->web_cate->tad_web_cate_max_id();
                // }

                $tad_web_aboutus->show_one($default_class);
                $op = 'show_one';
            }
        }

        break;

}

/*-----------秀出結果區--------------*/
include_once 'footer.php';
include_once XOOPS_ROOT_PATH . '/footer.php';
