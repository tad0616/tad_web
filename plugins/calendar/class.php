<?php
class tad_web_calendar
{

    public $WebID = 0;
    public $web_cate;
    public $setup;

    public function __construct($WebID)
    {
        $this->WebID    = $WebID;
        $this->web_cate = new web_cate($WebID, "calendar", "tad_web_calendar");
        $this->setup    = get_plugin_setup_values($WebID, "calendar");
    }

    public function list_all($CateID = "", $limit = null, $mode = "assign")
    {
        global $xoopsDB, $xoopsTpl, $MyWebs, $isMyWeb;

        $andWebID = (empty($this->WebID)) ? "" : "and a.WebID='{$this->WebID}'";

        if (!file_exists(XOOPS_ROOT_PATH . "/modules/tadtools/fullcalendar.php")) {
            redirect_header("http://campus-xoops.tn.edu.tw/modules/tad_modules/index.php?module_sn=1", 3, _TAD_NEED_TADTOOLS);
        }

        $sql         = "select count(*) from " . $xoopsDB->prefix("tad_web_calendar") . " where WebID='{$this->WebID}'";
        $result      = $xoopsDB->query($sql) or web_error($sql);
        list($total) = $xoopsDB->fetchRow($result);

        $sql          = "select count(*) from " . $xoopsDB->prefix("tad_web_homework") . " where WebID='{$this->WebID}'";
        $result       = $xoopsDB->query($sql) or web_error($sql);
        list($total2) = $xoopsDB->fetchRow($result);

        $sql          = "select count(*) from " . $xoopsDB->prefix("tad_web_news") . " where WebID='{$this->WebID}' and toCal!='0000-00-00 00:00:00'";
        $result       = $xoopsDB->query($sql) or web_error($sql);
        list($total3) = $xoopsDB->fetchRow($result);

        if (empty($total) and empty($total2) and empty($total3)) {
            $calendar_data = '';
        } else {
            $calendar_data = $total + $total2 + $total3;
        }
        // die('$calendar_data=' . $calendar_data);
        include_once XOOPS_ROOT_PATH . "/modules/tadtools/fullcalendar.php";
        $fullcalendar = new fullcalendar();

        //$fullcalendar->add_js_parameter('dayClick', "function(date, jsEvent, view) {alert('新增事件')}", false);
        if ($this->WebID) {
            $fullcalendar->add_js_parameter('firstDay', $this->setup['week_first_day']);
            $fullcalendar->add_json_parameter('WebID', $this->WebID);
        }
        $fullcalendar_code = $fullcalendar->render('#calendar', XOOPS_URL . '/modules/tad_web/get_event.php');

        if (isset($_GET['debug']) and $_GET['debug'] == 1) {
            die(var_export($fullcalendar_code));
        }

        if ($mode == "return") {
            $data['fullcalendar_code'] = $fullcalendar_code;
            $data['main_data']         = $calendar_data;
            $data['calendar_data']     = $calendar_data;
            return $data;
        } else {
            $xoopsTpl->assign('calendar', get_db_plugin($this->WebID, 'calendar'));
            $xoopsTpl->assign('fullcalendar_code', $fullcalendar_code);
            $xoopsTpl->assign('calendar_data', $calendar_data);
        }

    }

    //以流水號秀出某筆tad_web_calendar資料內容
    public function show_one($CalendarID = "")
    {
        global $xoopsDB, $xoopsTpl, $TadUpFiles, $isMyWeb;
        if (empty($CalendarID)) {
            return;
        }

        $CalendarID = intval($CalendarID);
        $this->add_counter($CalendarID);

        $sql    = "select * from " . $xoopsDB->prefix("tad_web_calendar") . " where CalendarID='{$CalendarID}'";
        $result = $xoopsDB->query($sql) or web_error($sql);
        $all    = $xoopsDB->fetchArray($result);

        //以下會產生這些變數： $CalendarID , $CalendarName , $CalendarType , $CalendarDesc , $CalendarDate , $uid , $WebID , $CalendarCount
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

        $xoopsTpl->assign('CalendarName', $CalendarName);
        $xoopsTpl->assign('CalendarType', $CalendarType);
        $xoopsTpl->assign('CalendarDate', $CalendarDate);
        $xoopsTpl->assign('CalendarDesc', nl2br($CalendarDesc));
        $xoopsTpl->assign('uid_name', $uid_name);
        $xoopsTpl->assign('CalendarCount', $CalendarCount);
        $xoopsTpl->assign('CalendarID', $CalendarID);
        $xoopsTpl->assign('CalendarInfo', sprintf(_MD_TCW_INFO, $uid_name, $CalendarDate, $CalendarCount));

        $xoopsTpl->assign('xoops_pagetitle', $CalendarName);
        $xoopsTpl->assign('fb_description', xoops_substr(strip_tags($CalendarDesc), 0, 300));
        //取得單一分類資料
        // $cate = $this->web_cate->get_tad_web_cate($CateID);
        // $xoopsTpl->assign('cate', $cate);

        //可愛刪除
        if (!file_exists(XOOPS_ROOT_PATH . "/modules/tadtools/sweet_alert.php")) {
            redirect_header("index.php", 3, _MA_NEED_TADTOOLS);
        }
        include_once XOOPS_ROOT_PATH . "/modules/tadtools/sweet_alert.php";
        $sweet_alert = new sweet_alert();
        $sweet_alert->render("delete_calendar_func", "calendar.php?op=delete&WebID={$this->WebID}&CalendarID=", 'CalendarID');
        $xoopsTpl->assign("fb_comments", fb_comments($this->setup['use_fb_comments']));
    }

    //tad_web_calendar編輯表單
    public function edit_form($CalendarID = "")
    {
        global $xoopsDB, $xoopsUser, $MyWebs, $isMyWeb, $xoopsTpl;

        chk_self_web($this->WebID);
        get_quota($this->WebID);

        //抓取預設值
        if (!empty($CalendarID)) {
            $DBV = $this->get_one_data($CalendarID);
        } else {
            $DBV = array();
        }

        //預設值設定

        //設定「CalendarID」欄位預設值
        $CalendarID = (!isset($DBV['CalendarID'])) ? $CalendarID : $DBV['CalendarID'];
        $xoopsTpl->assign('CalendarID', $CalendarID);

        //設定「CalendarName」欄位預設值
        $CalendarName = (!isset($DBV['CalendarName'])) ? "" : $DBV['CalendarName'];
        $xoopsTpl->assign('CalendarName', $CalendarName);

        //設定「CalendarType」欄位預設值
        $CalendarType = (!isset($DBV['CalendarType'])) ? "" : $DBV['CalendarType'];
        $xoopsTpl->assign('CalendarType', $CalendarType);

        //設定「CalendarDesc」欄位預設值
        $CalendarDesc = (!isset($DBV['CalendarDesc'])) ? "" : $DBV['CalendarDesc'];
        $xoopsTpl->assign('CalendarDesc', $CalendarDesc);

        //設定「CalendarDate」欄位預設值
        $CalendarDate = (!isset($DBV['CalendarDate'])) ? date("Y-m-d") : $DBV['CalendarDate'];
        $xoopsTpl->assign('CalendarDate', $CalendarDate);

        //設定「uid」欄位預設值
        $user_uid = ($xoopsUser) ? $xoopsUser->getVar('uid') : "";
        $uid      = (!isset($DBV['uid'])) ? $user_uid : $DBV['uid'];
        $xoopsTpl->assign('uid', $uid);

        //設定「WebID」欄位預設值
        $WebID = (!isset($DBV['WebID'])) ? $this->WebID : $DBV['WebID'];
        $xoopsTpl->assign('WebID', $WebID);

        //設定「CalendarCount」欄位預設值
        $CalendarCount = (!isset($DBV['CalendarCount'])) ? "" : $DBV['CalendarCount'];
        $xoopsTpl->assign('CalendarCount', $CalendarCount);

        //設定「CateID」欄位預設值
        // $CateID    = (!isset($DBV['CateID'])) ? "" : $DBV['CateID'];
        // $cate_menu = $this->web_cate->cate_menu($CateID);
        // $xoopsTpl->assign('cate_menu', $cate_menu);

        $op = (empty($CalendarID)) ? "insert" : "update";

        if (!file_exists(TADTOOLS_PATH . "/formValidator.php")) {
            redirect_header("index.php", 3, _MD_NEED_TADTOOLS);
        }
        include_once TADTOOLS_PATH . "/formValidator.php";
        $formValidator      = new formValidator("#myForm", true);
        $formValidator_code = $formValidator->render();

        $xoopsTpl->assign('formValidator_code', $formValidator_code);
        $xoopsTpl->assign('next_op', $op);
    }

    //新增資料到tad_web_calendar中
    public function insert()
    {
        global $xoopsDB, $xoopsUser, $WebOwnerUid;
        $uid = ($xoopsUser) ? $xoopsUser->uid() : "";

        $myts                   = MyTextSanitizer::getInstance();
        $_POST['CalendarName']  = $myts->addSlashes($_POST['CalendarName']);
        $_POST['CalendarType']  = $myts->addSlashes($_POST['CalendarType']);
        $_POST['CalendarDesc']  = $myts->addSlashes($_POST['CalendarDesc']);
        $_POST['CalendarCount'] = intval($_POST['CalendarCount']);
        //$_POST['CateID']        = intval($_POST['CateID']);
        $_POST['WebID'] = intval($_POST['WebID']);

        $CateID = $this->web_cate->save_tad_web_cate($_POST['CateID'], $_POST['newCateName']);
        $sql    = "insert into " . $xoopsDB->prefix("tad_web_calendar") . "
        (`CateID`,`CalendarName`,`CalendarType` , `CalendarDesc` , `CalendarDate` , `uid` , `WebID` , `CalendarCount`)
        values('0' ,'{$_POST['CalendarName']}' ,'{$_POST['CalendarType']}' , '{$_POST['CalendarDesc']}' , '{$_POST['CalendarDate']}' , '{$uid}' , '{$_POST['WebID']}' , '{$_POST['CalendarCount']}')";
        $xoopsDB->query($sql) or web_error($sql);

        //取得最後新增資料的流水編號
        $CalendarID = $xoopsDB->getInsertId();

        check_quota($this->WebID);
        return $CalendarID;
    }

    //更新tad_web_calendar某一筆資料
    public function update($CalendarID = "")
    {
        global $xoopsDB;

        $myts                  = MyTextSanitizer::getInstance();
        $_POST['CalendarName'] = $myts->addSlashes($_POST['CalendarName']);
        $_POST['CalendarType'] = $myts->addSlashes($_POST['CalendarType']);
        $_POST['CalendarDesc'] = $myts->addSlashes($_POST['CalendarDesc']);
        //$_POST['CateID']       = intval($_POST['CateID']);
        $_POST['WebID'] = intval($_POST['WebID']);

        $CateID = $this->web_cate->save_tad_web_cate($_POST['CateID'], $_POST['newCateName']);

        $anduid = onlyMine();

        $sql = "update " . $xoopsDB->prefix("tad_web_calendar") . " set
         `CalendarName` = '{$_POST['CalendarName']}' ,
         `CalendarType` = '{$_POST['CalendarType']}' ,
         `CalendarDesc` = '{$_POST['CalendarDesc']}' ,
         `CalendarDate` = '{$_POST['CalendarDate']}'
        where CalendarID='$CalendarID' $anduid";
        $xoopsDB->queryF($sql) or web_error($sql);

        check_quota($this->WebID);
        return $CalendarID;
    }

    //刪除tad_web_calendar某筆資料資料
    public function delete($CalendarID = "")
    {
        global $xoopsDB;
        $anduid = onlyMine();
        $sql    = "delete from " . $xoopsDB->prefix("tad_web_calendar") . " where CalendarID='$CalendarID' $anduid";
        $xoopsDB->queryF($sql) or web_error($sql);
        check_quota($this->WebID);
    }

    //刪除所有資料
    public function delete_all()
    {
        global $xoopsDB, $TadUpFiles;
        $allCateID = array();
        $sql       = "select CalendarID,CateID from " . $xoopsDB->prefix("tad_web_calendar") . " where WebID='{$this->WebID}'";
        $result    = $xoopsDB->queryF($sql) or web_error($sql);
        while (list($CalendarID, $CateID) = $xoopsDB->fetchRow($result)) {
            $this->delete($CalendarID);
            $allCateID[$CateID] = $CateID;
        }
        foreach ($allCateID as $CateID) {
            $this->web_cate->delete_tad_web_cate($CateID);
        }
        check_quota($this->WebID);
    }

    //取得資料總數
    public function get_total()
    {
        global $xoopsDB;
        $sql         = "select count(*) from " . $xoopsDB->prefix("tad_web_calendar") . " where WebID='{$this->WebID}'";
        $result      = $xoopsDB->query($sql) or web_error($sql);
        list($count) = $xoopsDB->fetchRow($result);
        return $count;
    }

    //新增tad_web_calendar計數器
    public function add_counter($CalendarID = '')
    {
        global $xoopsDB;
        $sql = "update " . $xoopsDB->prefix("tad_web_calendar") . " set `CalendarCount`=`CalendarCount`+1 where `CalendarID`='{$CalendarID}'";
        $xoopsDB->queryF($sql) or web_error($sql);
    }

    //以流水號取得某筆tad_web_calendar資料
    public function get_one_data($CalendarID = "")
    {
        global $xoopsDB;
        if (empty($CalendarID)) {
            return;
        }

        $sql    = "select * from " . $xoopsDB->prefix("tad_web_calendar") . " where CalendarID='$CalendarID'";
        $result = $xoopsDB->query($sql) or web_error($sql);
        $data   = $xoopsDB->fetchArray($result);
        return $data;
    }
}
