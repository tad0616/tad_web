<?php
use XoopsModules\Tadtools\FormValidator;
use XoopsModules\Tadtools\FullCalendar6;
use XoopsModules\Tadtools\SweetAlert;
use XoopsModules\Tadtools\Utility;
use XoopsModules\Tad_web\WebCate;

class tad_web_calendar
{
    public $WebID = 0;
    public $WebCate;
    public $setup;

    public function __construct($WebID)
    {
        $this->WebID = $WebID;
        $this->WebCate = new WebCate($WebID, 'calendar', 'tad_web_calendar');
        $this->setup = get_plugin_setup_values($WebID, 'calendar');
    }

    public function list_all($CateID = '', $limit = null, $mode = 'assign')
    {
        global $xoopsDB, $xoopsTpl;

        $andWebID = (empty($this->WebID)) ? '' : "and a.WebID='{$this->WebID}'";

        $sql = 'SELECT COUNT(*) FROM `' . $xoopsDB->prefix('tad_web_calendar') . '` WHERE `WebID` = ?';
        $result = Utility::query($sql, 'i', [$this->WebID]) or Utility::web_error($sql, __FILE__, __LINE__);
        list($total) = $xoopsDB->fetchRow($result);

        $sql = 'SELECT COUNT(*) FROM `' . $xoopsDB->prefix('tad_web_homework') . '` WHERE `WebID`=?';
        $result = Utility::query($sql, 'i', [$this->WebID]) or Utility::web_error($sql, __FILE__, __LINE__);

        list($total2) = $xoopsDB->fetchRow($result);

        $sql = 'SELECT COUNT(*) FROM `' . $xoopsDB->prefix('tad_web_news') . '` WHERE `WebID`=? AND `toCal`!=?';
        $result = Utility::query($sql, 'is', [$this->WebID, '0000-00-00 00:00:00']) or Utility::web_error($sql, __FILE__, __LINE__);

        list($total3) = $xoopsDB->fetchRow($result);

        if (empty($total) and empty($total2) and empty($total3)) {
            $calendar_data = '';
        } else {
            $calendar_data = $total + $total2 + $total3;
        }

        $FullCalendar = new FullCalendar6();

        if ($this->WebID) {
            $FullCalendar->add_js_parameter('firstDay', $this->setup['week_first_day']);
            // $FullCalendar->add_json_parameter('WebID', $this->WebID);
        }
        $fullcalendar_code = $FullCalendar->render('calendar', XOOPS_URL . '/modules/tad_web/get_event.php?WebID=' . $this->WebID, 'return');

        if ('return' === $mode) {
            $data['fullcalendar_code'] = $fullcalendar_code;
            $data['main_data'] = $calendar_data;
            $data['calendar_data'] = $calendar_data;
            return $data;
        } else {
            $xoopsTpl->assign('calendar', get_db_plugin($this->WebID, 'calendar'));
            $xoopsTpl->assign('fullcalendar_code', $fullcalendar_code);
            $xoopsTpl->assign('calendar_data', $calendar_data);
        }
    }

    //以流水號秀出某筆tad_web_calendar資料內容
    public function show_one($CalendarID = '')
    {
        global $xoopsDB, $xoopsTpl, $TadUpFiles, $isMyWeb;

        if (empty($CalendarID)) {
            redirect_header("{$_SERVER['PHP_SELF']}?WebID={$this->WebID}", 3, _MD_TCW_DATA_NOT_EXIST);
        }

        $CalendarID = (int) $CalendarID;

        if (_IS_EZCLASS) {
            $CalendarCount = $data['CalendarCount'] = $this->add_counter($CalendarID);
        } else {
            $this->add_counter($CalendarID);
        }

        $sql = 'SELECT * FROM `' . $xoopsDB->prefix('tad_web_calendar') . '` WHERE `CalendarID`=?';
        $result = Utility::query($sql, 'i', [$CalendarID]) or Utility::web_error($sql, __FILE__, __LINE__);
        $all = $xoopsDB->fetchArray($result);

        //以下會產生這些變數： $CalendarID , $CalendarName , $CalendarType , $CalendarDesc , $CalendarDate , $uid , $WebID , $CalendarCount
        foreach ($all as $k => $v) {
            $$k = $v;
        }

        $uid_name = \XoopsUser::getUnameFromId($uid, 1);
        if (empty($uid_name)) {
            $uid_name = \XoopsUser::getUnameFromId($uid, 0);
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

        $SweetAlert = new SweetAlert();
        $SweetAlert->render('delete_calendar_func', "calendar.php?op=delete&WebID={$this->WebID}&CalendarID=", 'CalendarID');
    }

    //tad_web_calendar編輯表單
    public function edit_form($CalendarID = '')
    {
        global $xoTheme, $xoopsUser, $xoopsTpl;

        $xoTheme->addScript('modules/tadtools/My97DatePicker/WdatePicker.js');
        chk_self_web($this->WebID);
        get_quota($this->WebID);

        //抓取預設值
        if (!empty($CalendarID)) {
            $DBV = $this->get_one_data($CalendarID);
        } else {
            $DBV = [];
        }

        //預設值設定

        //設定「CalendarID」欄位預設值
        $CalendarID = (!isset($DBV['CalendarID'])) ? $CalendarID : $DBV['CalendarID'];
        $xoopsTpl->assign('CalendarID', $CalendarID);

        //設定「CalendarName」欄位預設值
        $CalendarName = (!isset($DBV['CalendarName'])) ? '' : $DBV['CalendarName'];
        $xoopsTpl->assign('CalendarName', $CalendarName);

        //設定「CalendarType」欄位預設值
        $CalendarType = (!isset($DBV['CalendarType'])) ? '' : $DBV['CalendarType'];
        $xoopsTpl->assign('CalendarType', $CalendarType);

        //設定「CalendarDesc」欄位預設值
        $CalendarDesc = (!isset($DBV['CalendarDesc'])) ? '' : $DBV['CalendarDesc'];
        $xoopsTpl->assign('CalendarDesc', $CalendarDesc);

        //設定「CalendarDate」欄位預設值
        $CalendarDate = (!isset($DBV['CalendarDate'])) ? date('Y-m-d') : $DBV['CalendarDate'];
        $xoopsTpl->assign('CalendarDate', $CalendarDate);

        //設定「uid」欄位預設值
        $user_uid = ($xoopsUser) ? $xoopsUser->uid() : '';
        $uid = (!isset($DBV['uid'])) ? $user_uid : $DBV['uid'];
        $xoopsTpl->assign('uid', $uid);

        //設定「WebID」欄位預設值
        $WebID = (!isset($DBV['WebID'])) ? $this->WebID : $DBV['WebID'];
        $xoopsTpl->assign('WebID', $WebID);

        //設定「CalendarCount」欄位預設值
        $CalendarCount = (!isset($DBV['CalendarCount'])) ? '' : $DBV['CalendarCount'];
        $xoopsTpl->assign('CalendarCount', $CalendarCount);

        //設定「CateID」欄位預設值
        // $CateID    = (!isset($DBV['CateID'])) ? "" : $DBV['CateID'];
        // $cate_menu = $this->WebCate->cate_menu($CateID);
        // $xoopsTpl->assign('cate_menu', $cate_menu);

        $op = (empty($CalendarID)) ? 'insert' : 'update';

        $FormValidator = new FormValidator('#myForm', true);
        $FormValidator->render();

        $xoopsTpl->assign('next_op', $op);
    }

    //新增資料到tad_web_calendar中
    public function insert()
    {
        global $xoopsDB, $xoopsUser;
        $uid = ($xoopsUser) ? $xoopsUser->uid() : '';

        $CalendarName = (string) $_POST['CalendarName'];
        $CalendarType = (string) $_POST['CalendarType'];
        $CalendarDesc = (string) $_POST['CalendarDesc'];
        $CalendarDate = (string) $_POST['CalendarDate'];
        $newCateName = (string) $_POST['newCateName'];
        $CalendarCount = (int) $_POST['CalendarCount'];
        $CateID = (int) $_POST['CateID'];
        $WebID = (int) $_POST['WebID'];
        if ($newCateName != '') {
            $CateID = $this->WebCate->save_tad_web_cate($CateID, $newCateName);
        }

        $sql = 'INSERT INTO `' . $xoopsDB->prefix('tad_web_calendar') . '` (`CateID`,`CalendarName`,`CalendarType`,`CalendarDesc`,`CalendarDate`,`uid`,`WebID`,`CalendarCount`) VALUES (0, ?, ?, ?, ?, ?, ?, ?)';
        Utility::query($sql, 'ssssiii', [$CalendarName, $CalendarType, $CalendarDesc, $CalendarDate, $uid, $WebID, $CalendarCount]) or Utility::web_error($sql, __FILE__, __LINE__);

        //取得最後新增資料的流水編號
        $CalendarID = $xoopsDB->getInsertId();

        check_quota($this->WebID);
        return $CalendarID;
    }

    //更新tad_web_calendar某一筆資料
    public function update($CalendarID = '')
    {
        global $xoopsDB;

        $CalendarName = (string) $_POST['CalendarName'];
        $CalendarType = (string) $_POST['CalendarType'];
        $CalendarDesc = (string) $_POST['CalendarDesc'];
        $CalendarDate = (string) $_POST['CalendarDate'];
        $newCateName = (string) $_POST['newCateName'];
        $CateID = (int) $_POST['CateID'];
        if ($newCateName != '') {
            $CateID = $this->WebCate->save_tad_web_cate($CateID, $newCateName);
        }

        $and_uid = onlyMine();

        $sql = 'UPDATE `' . $xoopsDB->prefix('tad_web_calendar') . '` SET `CalendarName` = ?, `CalendarType` = ?, `CalendarDesc` = ?, `CalendarDate` = ? WHERE `CalendarID` = ? ' . $and_uid;
        Utility::query($sql, 'ssssi', [$CalendarName, $CalendarType, $CalendarDesc, $CalendarDate, $CalendarID]) or Utility::web_error($sql, __FILE__, __LINE__);

        check_quota($this->WebID);
        return $CalendarID;
    }

    //刪除tad_web_calendar某筆資料資料
    public function delete($CalendarID = '')
    {
        global $xoopsDB;
        $and_uid = onlyMine();
        $sql = 'DELETE FROM `' . $xoopsDB->prefix('tad_web_calendar') . '` WHERE `CalendarID`=? ' . $and_uid;
        Utility::query($sql, 'i', [$CalendarID]) or Utility::web_error($sql, __FILE__, __LINE__);
        check_quota($this->WebID);
    }

    //刪除所有資料
    public function delete_all()
    {
        global $xoopsDB;
        $allCateID = [];
        $sql = 'SELECT `CalendarID`,`CateID` FROM `' . $xoopsDB->prefix('tad_web_calendar') . '` WHERE `WebID`=?';
        $result = Utility::query($sql, 'i', [$this->WebID]) or Utility::web_error($sql, __FILE__, __LINE__);
        while (list($CalendarID, $CateID) = $xoopsDB->fetchRow($result)) {
            $this->delete($CalendarID);
            $allCateID[$CateID] = $CateID;
        }
        foreach ($allCateID as $CateID) {
            $this->WebCate->delete_tad_web_cate($CateID);
        }
        check_quota($this->WebID);
    }

    //取得資料總數
    public function get_total()
    {
        global $xoopsDB;
        $sql = 'SELECT COUNT(*) FROM `' . $xoopsDB->prefix('tad_web_calendar') . '` WHERE `WebID`=?';
        $result = Utility::query($sql, 'i', [$this->WebID]) or Utility::web_error($sql, __FILE__, __LINE__);
        list($count) = $xoopsDB->fetchRow($result);
        return $count;
    }

    //新增tad_web_calendar計數器
    public function add_counter($CalendarID = '')
    {
        global $xoopsDB;

        if (_IS_EZCLASS) {
            $CalendarCount = redis_do($this->WebID, 'get', 'calendar', "CalendarCount:$CalendarID");
            if (empty($CalendarCount)) {
                $sql = 'SELECT `CalendarCount` FROM `' . $xoopsDB->prefix('tad_web_calendar') . '` WHERE `CalendarID` =?';
                $result = Utility::query($sql, 'i', [$CalendarID]) or Utility::web_error($sql, __FILE__, __LINE__);

                list($CalendarCount) = $xoopsDB->fetchRow($result);
                redis_do($this->WebID, 'set', 'calendar', "CalendarCount:$CalendarID", $CalendarCount);
            }
            return redis_do($this->WebID, 'incr', 'calendar', "CalendarCount:$CalendarID");
        } else {
            $sql = 'UPDATE `' . $xoopsDB->prefix('tad_web_calendar') . '` SET `CalendarCount`=`CalendarCount`+1 WHERE `CalendarID`=?';
            Utility::query($sql, 'i', [$CalendarID]) or Utility::web_error($sql, __FILE__, __LINE__);
        }
    }

    //以流水號取得某筆tad_web_calendar資料
    public function get_one_data($CalendarID = '')
    {
        global $xoopsDB;
        if (empty($CalendarID)) {
            return;
        }

        $sql = 'SELECT * FROM `' . $xoopsDB->prefix('tad_web_calendar') . '` WHERE `CalendarID` = ?';
        $result = Utility::query($sql, 'i', [$CalendarID]) or Utility::web_error($sql, __FILE__, __LINE__);
        $data = $xoopsDB->fetchArray($result);

        if (_IS_EZCLASS) {
            $data['CalendarCount'] = redis_do($this->WebID, 'get', 'calendar', "CalendarCount:$CalendarID");
        }
        return $data;
    }
}
