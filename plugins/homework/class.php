<?php
class tad_web_homework
{

    public $WebID = 0;
    public $web_cate;

    public function tad_web_homework($WebID)
    {
        $this->WebID    = $WebID;
        $this->web_cate = new web_cate($WebID, "homework", "tad_web_homework");
    }

    //最新消息
    public function list_all($CateID = "", $limit = null)
    {
        global $xoopsDB, $xoopsTpl, $isMyWeb;

        $showWebTitle = (empty($this->WebID)) ? 1 : 0;
        $andWebID     = (empty($this->WebID)) ? "" : "and a.WebID='{$this->WebID}'";

        //取得tad_web_cate所有資料陣列
        $cate_menu = $this->web_cate->cate_menu($CateID, 'page', false, true, false, true);
        $xoopsTpl->assign('cate_menu', $cate_menu);

        $andCateID = "";
        if (!empty($CateID)) {
            //取得單一分類資料
            $cate = $this->web_cate->get_tad_web_cate($CateID);
            $xoopsTpl->assign('cate', $cate);
            $andCateID = "and a.`CateID`='$CateID'";
        }
        $now = date("Y-m-d H:i:s");

        $sql = "select a.* from " . $xoopsDB->prefix("tad_web_homework") . " as a left join " . $xoopsDB->prefix("tad_web") . " as b on a.WebID=b.WebID where a.HomeworkPostDate <= '{$now}' and b.`WebEnable`='1' $andWebID $andCateID order by HomeworkPostDate desc";

        $to_limit = empty($limit) ? 20 : $limit;

        //getPageBar($原sql語法, 每頁顯示幾筆資料, 最多顯示幾個頁數選項);
        $PageBar  = getPageBar($sql, $to_limit, 10);
        $bar      = $PageBar['bar'];
        $sql      = $PageBar['sql'];
        $total    = $PageBar['total'];
        $show_bar = empty($limit) ? $bar : "";

        $result = $xoopsDB->query($sql) or web_error($sql);

        $main_data = "";

        $i = 0;

        $Webs     = getAllWebInfo();
        $WebNames = getAllWebInfo('WebName');
        $cweek    = array(0 => _MD_TCW_SUN, _MD_TCW_MON, _MD_TCW_TUE, _MD_TCW_WED, _MD_TCW_THU, _MD_TCW_FRI, _MD_TCW_SAT);

        while ($all = $xoopsDB->fetchArray($result)) {
            //以下會產生這些變數： $HomeworkID , $HomeworkTitle , $HomeworkContent , $HomeworkDate , $toCal , $WebID  , $HomeworkCounter, $uid, $HomeworkPostDate
            foreach ($all as $k => $v) {
                $$k = $v;
            }

            $main_data[$i] = $all;

            $this->web_cate->set_WebID($WebID);
            $cate = $this->web_cate->get_tad_web_cate_arr();

            $main_data[$i]['cate']     = $cate[$CateID];
            $main_data[$i]['WebName']  = $WebNames[$WebID];
            $main_data[$i]['WebTitle'] = "<a href='index.php?WebID={$WebID}'>{$Webs[$WebID]}</a>";

            if (empty($HomeworkTitle)) {
                $HomeworkTitle = _MD_TCW_EMPTY_TITLE;
            }

            $main_data[$i]['HomeworkTitle'] = $HomeworkTitle;
            $main_data[$i]['HomeworkDate']  = $HomeworkDate;
            $w                              = date("w", strtotime($toCal));
            $main_data[$i]['Week']          = $cweek[$w];
            $i++;
        }

        $yet_data = '';
        if ($isMyWeb) {
            $i      = 0;
            $sql    = "select a.* from " . $xoopsDB->prefix("tad_web_homework") . " as a left join " . $xoopsDB->prefix("tad_web") . " as b on a.WebID=b.WebID where a.HomeworkPostDate > '{$now}' and b.`WebEnable`='1' $andWebID $andCateID order by HomeworkPostDate desc";
            $result = $xoopsDB->query($sql) or web_error($sql);
            while ($all = $xoopsDB->fetchArray($result)) {
                $yet_data[$i]               = $all;
                $yet_data[$i]['display_at'] = sprintf(_MD_TCW_HOMEWORK_POST_AT, $all['HomeworkPostDate']);
                $w                          = date("w", strtotime($toCal));
                $yet_data[$i]['Week']       = $cweek[$w];
                $i++;
            }
        }
        $xoopsTpl->assign('yet_data', $yet_data);

        if (!file_exists(XOOPS_ROOT_PATH . "/modules/tadtools/fullcalendar.php")) {
            redirect_header("http://campus-xoops.tn.edu.tw/modules/tad_modules/index.php?module_sn=1", 3, _TAD_NEED_TADTOOLS);
        }
        include_once XOOPS_ROOT_PATH . "/modules/tadtools/fullcalendar.php";
        $fullcalendar = new fullcalendar();
        if ($this->WebID) {
            $fullcalendar->add_json_parameter('WebID', $this->WebID);
        }
        if (strrpos($_SERVER['PHP_SELF'], 'homework.php') !== false) {
            $fullcalendar->add_json_parameter('CalKind', 'homework');
        }
        $fullcalendar_code = $fullcalendar->render('#calendar', XOOPS_URL . '/modules/tad_web/get_event.php');

        $xoopsTpl->assign('fullcalendar_code', $fullcalendar_code);
        $xoopsTpl->assign('CalKind', 'homework');
        $xoopsTpl->assign('homework_data', $main_data);
        $xoopsTpl->assign('homework_bar', $show_bar);
        $xoopsTpl->assign('isMineHomework', $isMyWeb);
        $xoopsTpl->assign('showWebTitleHomework', $showWebTitle);
        $xoopsTpl->assign('homework', get_db_plugin($this->WebID, 'homework'));
        return $total;
    }

    //以流水號秀出某筆tad_web_homework資料內容
    public function show_one($HomeworkID = "")
    {
        global $xoopsDB, $WebID, $isAdmin, $xoopsTpl, $TadUpFiles, $isMyWeb;
        if (empty($HomeworkID)) {
            return;
        }
        $HomeworkID = intval($HomeworkID);
        $this->add_counter($HomeworkID);

        $now     = date("Y-m-d H:i:s");
        $andTime = $isMyWeb ? '' : "and HomeworkPostDate <= '{$now}'";
        $sql     = "select * from " . $xoopsDB->prefix("tad_web_homework") . " where HomeworkID='{$HomeworkID}' $andTime";
        $result  = $xoopsDB->query($sql) or web_error($sql);
        $all     = $xoopsDB->fetchArray($result);

        //以下會產生這些變數： $HomeworkID , $HomeworkTitle , $HomeworkContent , $HomeworkDate , $toCal , $WebID , $HomeworkCounter ,$uid ,$HomeworkPostDate
        foreach ($all as $k => $v) {
            $$k = $v;
        }

        if (empty($uid)) {
            redirect_header('index.php', 3, _MD_TCW_DATA_NOT_EXIST);
        }

        $uid_name = XoopsUser::getUnameFromId($uid, 1);
        if (empty($uid_name)) {
            $uid_name = XoopsUser::getUnameFromId($uid, 0);
        }

        $TadUpFiles->set_col("HomeworkID", $HomeworkID);
        $HomeworkFiles = $TadUpFiles->show_files('upfile', true, null, true);
        $xoopsTpl->assign('HomeworkFiles', $HomeworkFiles);

        $xoopsTpl->assign('isMine', $isMyWeb);
        $xoopsTpl->assign('HomeworkTitle', $HomeworkTitle);
        $xoopsTpl->assign('HomeworkContent', $HomeworkContent);
        $xoopsTpl->assign('uid_name', $uid_name);
        $xoopsTpl->assign('HomeworkDate', $HomeworkDate);
        $xoopsTpl->assign('HomeworkCounter', $HomeworkCounter);
        $xoopsTpl->assign('HomeworkPostDate', $HomeworkPostDate);
        $xoopsTpl->assign('HomeworkID', $HomeworkID);
        $xoopsTpl->assign('HomeworkInfo', sprintf(_MD_TCW_INFO, $uid_name, $HomeworkDate, $HomeworkCounter));

        //取得單一分類資料
        $cate = $this->web_cate->get_tad_web_cate($CateID);
        $xoopsTpl->assign('cate', $cate);
    }

    //tad_web_homework編輯表單
    public function edit_form($HomeworkID = "")
    {
        global $xoopsDB, $xoopsUser, $MyWebs, $isMyWeb, $xoopsTpl, $TadUpFiles;

        if (!$isMyWeb and $MyWebs) {
            redirect_header($_SERVER['PHP_SELF'] . "?WebID={$MyWebs[0]}&op=edit_form", 3, _MD_TCW_AUTO_TO_HOME);
        } elseif (!$xoopsUser or empty($this->WebID) or empty($MyWebs)) {
            redirect_header("index.php", 3, _MD_TCW_NOT_OWNER);
        }

        $Class = getWebInfo($this->WebID);

        //抓取預設值
        if (!empty($HomeworkID)) {
            $DBV = $this->get_one_data($HomeworkID);
        } else {
            $DBV = array();
        }

        //設定「HomeworkID」欄位預設值
        $HomeworkID = (!isset($DBV['HomeworkID'])) ? "" : $DBV['HomeworkID'];
        $xoopsTpl->assign('HomeworkID', $HomeworkID);

        //設定「HomeworkTitle」欄位預設值
        $HomeworkTitle = (!isset($DBV['HomeworkTitle'])) ? $Class['WebTitle'] . date(" Y-m-d ") . _MD_TCW_HOMEWORK : $DBV['HomeworkTitle'];
        $xoopsTpl->assign('HomeworkTitle', $HomeworkTitle);

        //設定「HomeworkContent」欄位預設值
        $HomeworkContent = (!isset($DBV['HomeworkContent'])) ? _MD_TCW_HOMEWORK_DEFAULT : $DBV['HomeworkContent'];
        $xoopsTpl->assign('HomeworkContent', $HomeworkContent);

        //設定「HomeworkDate」欄位預設值
        $HomeworkDate = (!isset($DBV['HomeworkDate'])) ? date("Y-m-d H:i:s") : $DBV['HomeworkDate'];
        $xoopsTpl->assign('HomeworkDate', $HomeworkDate);

        //設定「HomeworkPostDate」欄位預設值
        $HomeworkPostDate = (!isset($DBV['HomeworkPostDate'])) ? date("Y-m-d 12:00:00") : $DBV['HomeworkPostDate'];
        $xoopsTpl->assign('HomeworkPostDate', $HomeworkPostDate);

        //設定「toCal」欄位預設值
        if (!isset($DBV['toCal'])) {
            $toCal = date("Y-m-d");
        } else {
            $toCal = ($DBV['toCal'] == "0000-00-00") ? "" : $DBV['toCal'];
        }
        $xoopsTpl->assign('toCal', $toCal);

        //設定「WebID」欄位預設值
        $WebID = (!isset($DBV['WebID'])) ? $this->WebID : $DBV['WebID'];
        $xoopsTpl->assign('WebID', $WebID);

        //設定「HomeworkCounter」欄位預設值
        $HomeworkCounter = (!isset($DBV['HomeworkCounter'])) ? "" : $DBV['HomeworkCounter'];
        $xoopsTpl->assign('HomeworkCounter', $HomeworkCounter);

        //設定「HomeworkPostDate」欄位預設值
        if (isset($DBV['HomeworkPostDate'])) {
            if (strrpos($DBV['HomeworkPostDate'], "08:00:00") !== false) {
                $HomeworkPostDate = 8;
            } elseif (strrpos($DBV['HomeworkPostDate'], "12:00:00") !== false) {
                $HomeworkPostDate = 12;
            } elseif (strrpos($DBV['HomeworkPostDate'], "16:00:00") !== false) {
                $HomeworkPostDate = 16;
            } else {
                $HomeworkPostDate = date("Y-m-d H:i:s");
            }
        }
        $xoopsTpl->assign('HomeworkPostDate', $HomeworkPostDate);

        //設定「CateID」欄位預設值
        $CateID    = (!isset($DBV['CateID'])) ? "" : $DBV['CateID'];
        $cate_menu = $this->web_cate->cate_menu($CateID);
        $xoopsTpl->assign('cate_menu', $cate_menu);

        $op = (empty($HomeworkID)) ? "insert" : "update";

        if (!file_exists(TADTOOLS_PATH . "/formValidator.php")) {
            redirect_header("index.php", 3, _MD_NEED_TADTOOLS);
        }
        include_once TADTOOLS_PATH . "/formValidator.php";
        $formValidator      = new formValidator("#myForm", true);
        $formValidator_code = $formValidator->render();
        $xoopsTpl->assign('formValidator_code', $formValidator_code);

        include_once XOOPS_ROOT_PATH . "/modules/tadtools/ck.php";
        $ck = new CKEditor("tad_web", "HomeworkContent", $HomeworkContent);
        $ck->setHeight(300);
        $editor = $ck->render();
        $xoopsTpl->assign('HomeworkContent_editor', $editor);

        $xoopsTpl->assign('next_op', $op);

        $TadUpFiles->set_col("HomeworkID", $HomeworkID);
        $upform = $TadUpFiles->upform();
        $xoopsTpl->assign('upform', $upform);
    }

    //新增資料到tad_web_homework中
    public function insert()
    {
        global $xoopsDB, $xoopsUser, $TadUpFiles;

        $uid = $xoopsUser->getVar('uid');

        $myts                     = &MyTextSanitizer::getInstance();
        $_POST['HomeworkTitle']   = $myts->addSlashes($_POST['HomeworkTitle']);
        $_POST['HomeworkContent'] = $myts->addSlashes($_POST['HomeworkContent']);
        $_POST['CateID']          = intval($_POST['CateID']);
        $_POST['WebID']           = intval($_POST['WebID']);
        $HomeworkDate             = date("Y-m-d H:i:s");

        if (empty($_POST['toCal'])) {
            $_POST['toCal'] = "0000-00-00";
        }

        if ($_POST['HomeworkPostDate'] == 8) {
            $HomeworkPostDate = $_POST['toCal'] . " 08:00:00";
        } elseif ($_POST['HomeworkPostDate'] == 12) {
            $HomeworkPostDate = $_POST['toCal'] . " 12:00:00";
        } elseif ($_POST['HomeworkPostDate'] == 16) {
            $HomeworkPostDate = $_POST['toCal'] . " 16:00:00";
        } else {
            $HomeworkPostDate = date("Y-m-d H:i:s");
        }

        $CateID = $this->web_cate->save_tad_web_cate($_POST['CateID'], $_POST['newCateName']);

        $sql = "insert into " . $xoopsDB->prefix("tad_web_homework") . "
        (`CateID`,`HomeworkTitle` , `HomeworkContent` , `HomeworkDate` , `toCal` , `WebID` , `HomeworkCounter` , `uid` , `HomeworkPostDate`)
        values('{$CateID}','{$_POST['HomeworkTitle']}' , '{$_POST['HomeworkContent']}' , '{$HomeworkDate}' , '{$_POST['toCal']}' , '{$_POST['WebID']}' , '0' , '{$uid}' , '{$HomeworkPostDate}')";
        $xoopsDB->query($sql) or web_error($sql);

        //取得最後新增資料的流水編號
        $HomeworkID = $xoopsDB->getInsertId();

        $TadUpFiles->set_col("HomeworkID", $HomeworkID);
        $TadUpFiles->upload_file('upfile', 640, null, null, null, true);

        return $HomeworkID;
    }

    //更新tad_web_homework某一筆資料
    public function update($HomeworkID = "")
    {
        global $xoopsDB, $TadUpFiles;

        $myts                     = &MyTextSanitizer::getInstance();
        $_POST['HomeworkTitle']   = $myts->addSlashes($_POST['HomeworkTitle']);
        $_POST['HomeworkContent'] = $myts->addSlashes($_POST['HomeworkContent']);
        $_POST['CateID']          = intval($_POST['CateID']);
        $_POST['WebID']           = intval($_POST['WebID']);
        $HomeworkDate             = date("Y-m-d H:i:s");

        if (empty($_POST['toCal'])) {
            $_POST['toCal'] = "0000-00-00";
        }

        if ($_POST['HomeworkPostDate'] == 8) {
            $HomeworkPostDate = $_POST['toCal'] . " 08:00:00";
        } elseif ($_POST['HomeworkPostDate'] == 12) {
            $HomeworkPostDate = $_POST['toCal'] . " 12:00:00";
        } elseif ($_POST['HomeworkPostDate'] == 16) {
            $HomeworkPostDate = $_POST['toCal'] . " 16:00:00";
        } else {
            $HomeworkPostDate = date("Y-m-d H:i:s");
        }

        $CateID = $this->web_cate->save_tad_web_cate($_POST['CateID'], $_POST['newCateName']);

        $anduid = onlyMine();

        $sql = "update " . $xoopsDB->prefix("tad_web_homework") . " set
         `CateID` = '{$CateID}' ,
         `HomeworkTitle` = '{$_POST['HomeworkTitle']}' ,
         `HomeworkContent` = '{$_POST['HomeworkContent']}' ,
         `HomeworkDate` = '{$HomeworkDate}' ,
         `toCal` = '{$_POST['toCal']}' ,
         `HomeworkPostDate` = '{$HomeworkPostDate}'
        where HomeworkID='$HomeworkID' $anduid";
        $xoopsDB->queryF($sql) or web_error($sql);

        $TadUpFiles->set_col("HomeworkID", $HomeworkID);
        $TadUpFiles->upload_file('upfile', 640, null, null, null, true);

        return $HomeworkID;
    }

    //刪除tad_web_homework某筆資料資料
    public function delete($HomeworkID = "")
    {
        global $xoopsDB, $TadUpFiles;
        $anduid = onlyMine();
        $sql    = "delete from " . $xoopsDB->prefix("tad_web_homework") . " where HomeworkID='$HomeworkID' $anduid";
        $xoopsDB->queryF($sql) or web_error($sql);

        $TadUpFiles->set_col("HomeworkID", $HomeworkID);
        $TadUpFiles->del_files();
    }

    //新增tad_web_homework計數器
    public function add_counter($HomeworkID = '')
    {
        global $xoopsDB;
        $sql = "update " . $xoopsDB->prefix("tad_web_homework") . " set `HomeworkCounter`=`HomeworkCounter`+1 where `HomeworkID`='{$HomeworkID}'";
        $xoopsDB->queryF($sql) or web_error($sql);
    }

    //以流水號取得某筆tad_web_homework資料
    public function get_one_data($HomeworkID = "")
    {
        global $xoopsDB;
        if (empty($HomeworkID)) {
            return;
        }

        $sql    = "select * from " . $xoopsDB->prefix("tad_web_homework") . " where HomeworkID='$HomeworkID'";
        $result = $xoopsDB->query($sql) or web_error($sql);
        $data   = $xoopsDB->fetchArray($result);
        return $data;
    }
}
