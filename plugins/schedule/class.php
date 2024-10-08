<?php
use Xmf\Request;
use XoopsModules\Tadtools\FormValidator;
use XoopsModules\Tadtools\MColorPicker;
use XoopsModules\Tadtools\SweetAlert;
use XoopsModules\Tadtools\Utility;
use XoopsModules\Tad_web\Power;
use XoopsModules\Tad_web\WebCate;

class tad_web_schedule
{
    public $WebID = 0;
    public $WebCate;
    public $Power;

    public function __construct($WebID)
    {
        $this->WebID = $WebID;
        //die('$WebID=' . $WebID);
        $this->WebCate = new WebCate($WebID, 'schedule', 'tad_web_schedule');
        $this->Power = new Power($WebID);
    }

    //課表
    public function list_all($CateID = '', $limit = null, $mode = 'assign')
    {
        global $xoopsDB, $xoopsTpl, $MyWebs, $isMyWeb, $plugin_menu_var;

        $power = $this->Power->check_power("read", "CateID", $CateID, 'schedule');
        if (!$power) {
            redirect_header("schedule.php?WebID={$this->WebID}", 3, _MD_TCW_NOW_READ_POWER);
        }

        $andWebID = (empty($this->WebID)) ? '' : "and a.WebID='{$this->WebID}'";

        $andCateID = '';
        if ('assign' === $mode) {
            //取得tad_web_cate所有資料陣列
            if (!empty($plugin_menu_var)) {
                $this->WebCate->set_button_value($plugin_menu_var['schedule']['short'] . _MD_TCW_CATE_TOOLS);
                $this->WebCate->set_default_option_text(sprintf(_MD_TCW_SELECT_PLUGIN_CATE, $plugin_menu_var['schedule']['short']));
                $this->WebCate->set_col_md(0, 6);
                $cate_menu = $this->WebCate->cate_menu($CateID, 'page', false, true, false, false);
                $xoopsTpl->assign('cate_menu', $cate_menu);
            }
        }

        if (!empty($CateID) and 'assign' === $mode) {
            //取得單一分類資料
            $cate = $this->WebCate->get_tad_web_cate($CateID);
            if ($CateID and '1' != $cate['CateEnable']) {
                return;
            }
            $xoopsTpl->assign('cate', $cate);
            $andCateID = "and a.`CateID`='$CateID'";
            $xoopsTpl->assign('ScheduleDefCateID', $CateID);
        }

        if (_IS_EZCLASS and !empty($_GET['county'])) {
            //https://class.tn.edu.tw/modules/tad_web/index.php?county=臺南市&city=永康區&SchoolName=XX國小

            $county = Request::getString('county');
            $city = Request::getString('city');
            $SchoolName = Request::getString('SchoolName');
            $andCounty = !empty($county) ? "and c.county='{$county}'" : '';
            $andCity = !empty($city) ? "and c.city='{$city}'" : '';
            $andSchoolName = !empty($SchoolName) ? "and c.SchoolName='{$SchoolName}'" : '';

            $sql = 'select a.* from ' . $xoopsDB->prefix('tad_web_schedule') . ' as a
            left join ' . $xoopsDB->prefix('tad_web') . ' as b on a.WebID=b.WebID
            left join ' . $xoopsDB->prefix('apply') . ' as c on b.WebOwnerUid=c.uid
            left join ' . $xoopsDB->prefix('tad_web_cate') . " as d on a.CateID=d.CateID
            where b.`WebEnable`='1' and (d.CateEnable='1' or a.CateID='0') $andCounty $andCity $andSchoolName order by b.WebSort";
        } else {
            if (empty($this->WebID)) {
                return;
            }

            $sql = 'select a.* from ' . $xoopsDB->prefix('tad_web_schedule') . ' as a
            left join ' . $xoopsDB->prefix('tad_web') . ' as b on a.WebID=b.WebID
            left join ' . $xoopsDB->prefix('tad_web_cate') . " as c on a.CateID=c.CateID
            where b.`WebEnable`='1' and (c.CateEnable='1' or a.CateID='0') $andWebID $andCateID order by a.ScheduleDisplay desc, b.WebSort";
        }
        $result = $xoopsDB->query($sql) or Utility::web_error($sql, __FILE__, __LINE__);

        $main_data = [];

        $i = $total = 0;

        $Webs = getAllWebInfo();

        $cate = $this->WebCate->get_tad_web_cate_arr(null, null, 'schedule');

        while (false !== ($all = $xoopsDB->fetchArray($result))) {
            //以下會產生這些變數： $ScheduleID , $ScheduleName , $ScheduleDisplay , $uid , $WebID , $ScheduleCount , $ScheduleTime
            foreach ($all as $k => $v) {
                $$k = $v;
            }

            $power = $this->Power->check_power("read", "CateID", $CateID, 'schedule');
            if (!$power) {
                continue;
            }

            $main_data[$i] = $all;
            $main_data[$i]['id'] = $ScheduleID;
            $main_data[$i]['id_name'] = 'ScheduleID';
            $main_data[$i]['title'] = $ScheduleName;
            // $main_data[$i]['isAssistant'] = is_assistant($this->WebID, 'schedule', $CateID, 'ScheduleID', $ScheduleID);
            $main_data[$i]['isCanEdit'] = isCanEdit($this->WebID, 'schedule', $CateID, 'ScheduleID', $ScheduleID);
            if (_IS_EZCLASS) {
                $main_data[$i]['ScheduleCount'] = redis_do($this->WebID, 'get', 'schedule', "ScheduleCount:$ScheduleID");
            }

            $this->WebCate->set_WebID($WebID);

            $main_data[$i]['cate'] = isset($cate[$CateID]) ? $cate[$CateID] : '';
            $main_data[$i]['WebTitle'] = "<a href='index.php?WebID={$WebID}'>{$Webs[$WebID]}</a>";
            // $main_data[$i]['isMyWeb']  = in_array($WebID, $MyWebs) ? 1 : 0;
            $main_data[$i]['isMyWeb'] = $isMyWeb;
            $main_data[$i]['schedule'] = $this->get_one_schedule($ScheduleID);
            $i++;
            $total++;
        }

        $SweetAlert = new SweetAlert();
        $SweetAlert->render('delete_schedule_func', "schedule.php?op=delete&PageID={$this->WebID}&ScheduleID=", 'ScheduleID');

        if ('return' === $mode) {
            $data['schedule_amount'] = $i;
            $data['main_data'] = $main_data;
            $data['total'] = $total;
            $data['isCanEdit'] = isCanEdit($this->WebID, 'schedule', $CateID, 'ScheduleID', $ScheduleID);
            return $data;
        } else {
            $xoopsTpl->assign('schedule_amount', $i);
            $xoopsTpl->assign('schedule_data', $main_data);
            $xoopsTpl->assign('schedule', get_db_plugin($this->WebID, 'schedule'));
            $xoopsTpl->assign('isCanEdit', isCanEdit($this->WebID, 'schedule', $CateID, 'ScheduleID', $ScheduleID));
            return $i;
        }
    }

    //以流水號秀出某筆tad_web_schedule資料內容
    public function show_one($ScheduleID = '')
    {
        global $xoopsDB, $xoopsTpl, $isMyWeb, $xoopsModuleConfig;

        if (empty($ScheduleID)) {
            redirect_header("{$_SERVER['PHP_SELF']}?WebID={$this->WebID}", 3, _MD_TCW_DATA_NOT_EXIST);
        }

        $ScheduleID = (int) $ScheduleID;

        $sql = 'select * from ' . $xoopsDB->prefix('tad_web_schedule') . " where ScheduleID='{$ScheduleID}'";
        $result = $xoopsDB->query($sql) or Utility::web_error($sql, __FILE__, __LINE__);
        $all = $xoopsDB->fetchArray($result);

        //以下會產生這些變數： $ScheduleID , $ScheduleName , $ScheduleDisplay , $uid , $WebID , $ScheduleCount ,$ScheduleTime
        foreach ($all as $k => $v) {
            $$k = $v;
        }

        $power = $this->Power->check_power("read", "CateID", $CateID, 'schedule');
        if (!$power) {
            redirect_header("schedule.php?WebID={$this->WebID}", 3, _MD_TCW_NOW_READ_POWER);
        }

        if (_IS_EZCLASS) {
            $ScheduleCount = $data['ScheduleCount'] = $this->add_counter($ScheduleID);
        } else {
            $this->add_counter($ScheduleID);
        }

        $uid_name = \XoopsUser::getUnameFromId($uid, 1);
        if (empty($uid_name)) {
            $uid_name = \XoopsUser::getUnameFromId($uid, 0);
        }

        $assistant = is_assistant($this->WebID, 'schedule', $CateID, 'ScheduleID', $ScheduleID);
        $isAssistant = !empty($assistant) ? true : false;
        $uid_name = $isAssistant ? "{$uid_name} <a href='#' title='由{$assistant['MemName']}代理發布'><i class='fa fa-male'></i></a>" : $uid_name;
        $xoopsTpl->assign('isAssistant', $isAssistant);
        $xoopsTpl->assign('isCanEdit', isCanEdit($this->WebID, 'schedule', $CateID, 'ScheduleID', $ScheduleID));

        $xoopsTpl->assign('ScheduleName', $ScheduleName);
        $xoopsTpl->assign('ScheduleDisplay', $ScheduleDisplay);
        $xoopsTpl->assign('uid_name', $uid_name);
        $xoopsTpl->assign('ScheduleCount', $ScheduleCount);
        $xoopsTpl->assign('ScheduleTime', $ScheduleTime);
        $xoopsTpl->assign('ScheduleID', $ScheduleID);
        $xoopsTpl->assign('ScheduleInfo', sprintf(_MD_TCW_INFO, $uid_name, $ScheduleTime, $ScheduleCount));

        $xoopsTpl->assign('xoops_pagetitle', $ScheduleName);
        $xoopsTpl->assign('fb_description', xoops_substr(strip_tags($ScheduleName), 0, 300));
        //取得單一分類資料
        $cate = $this->WebCate->get_tad_web_cate($CateID);
        if ($CateID and '1' != $cate['CateEnable']) {
            return;
        }
        $xoopsTpl->assign('cate', $cate);

        $schedule_template = $this->get_one_schedule($ScheduleID);
        // if ($_GET['test'] == '1') {
        //     die($schedule_template);
        // }

        $xoopsTpl->assign('schedule_template', $schedule_template);

        $SweetAlert = new SweetAlert();
        $SweetAlert->render('delete_schedule_func', "schedule.php?op=delete&PageID={$this->WebID}&ScheduleID=", 'ScheduleID');
    }

    //tad_web_schedule編輯表單
    public function edit_form($ScheduleID = '')
    {
        global $xoopsDB, $xoopsUser, $MyWebs, $isMyWeb, $xoopsTpl, $WebName, $xoopsModuleConfig, $plugin_menu_var;

        chk_self_web($this->WebID, $_SESSION['isAssistant']['schedule']);

        //抓取預設值
        if (!empty($ScheduleID)) {
            $DBV = $this->get_one_data($ScheduleID);
        } else {
            $DBV = [];
        }

        //預設值設定

        //設定「ScheduleID」欄位預設值
        $ScheduleID = (!isset($DBV['ScheduleID'])) ? $ScheduleID : $DBV['ScheduleID'];
        $xoopsTpl->assign('ScheduleID', $ScheduleID);

        //設定「ScheduleName」欄位預設值
        $ScheduleName = (!isset($DBV['ScheduleName'])) ? $WebName . _MD_TCW_SCHEDULE : $DBV['ScheduleName'];
        $xoopsTpl->assign('ScheduleName', $ScheduleName);

        //設定「ScheduleDisplay」欄位預設值
        $ScheduleDisplay = (!isset($DBV['ScheduleDisplay'])) ? '0' : $DBV['ScheduleDisplay'];
        $xoopsTpl->assign('ScheduleDisplay', $ScheduleDisplay);

        //設定「uid」欄位預設值
        $user_uid = ($xoopsUser) ? $xoopsUser->getVar('uid') : '';
        $uid = (!isset($DBV['uid'])) ? $user_uid : $DBV['uid'];
        $xoopsTpl->assign('uid', $uid);

        //設定「WebID」欄位預設值
        $WebID = (!isset($DBV['WebID'])) ? $this->WebID : $DBV['WebID'];
        $xoopsTpl->assign('WebID', $WebID);

        //設定「ScheduleCount」欄位預設值
        $ScheduleCount = (!isset($DBV['ScheduleCount'])) ? '' : $DBV['ScheduleCount'];
        $xoopsTpl->assign('ScheduleCount', $ScheduleCount);

        //設定「ScheduleTime」欄位預設值
        $ScheduleTime = (!isset($DBV['ScheduleTime'])) ? '' : $DBV['ScheduleTime'];
        $xoopsTpl->assign('ScheduleTime', $ScheduleTime);

        //設定「CateID」欄位預設值
        $DefCateID = isset($_SESSION['isAssistant']['schedule']) ? $_SESSION['isAssistant']['schedule'] : '';
        $CateID = (!isset($DBV['CateID'])) ? $DefCateID : $DBV['CateID'];

        $ys = get_seme();
        $xoopsTpl->assign('ys', $ys);

        $this->WebCate->set_demo_txt(sprintf(_MD_TCW_SCHEDULE_CATE_DEMO, $ys[0], $ys[1]));
        $this->WebCate->set_button_value($plugin_menu_var['schedule']['short'] . _MD_TCW_CATE_TOOLS);
        $this->WebCate->set_default_option_text(sprintf(_MD_TCW_SELECT_PLUGIN_CATE, $plugin_menu_var['schedule']['short']));
        $cate_menu = isset($_SESSION['isAssistant']['schedule']) ? $this->WebCate->hidden_cate_menu($CateID) : $this->WebCate->cate_menu($CateID);
        $xoopsTpl->assign('cate_menu_form', $cate_menu);

        $op = (empty($ScheduleID)) ? 'insert' : 'update';

        $FormValidator = new FormValidator('#myForm', true);
        $FormValidator->render();

        $xoopsTpl->assign('next_op', $op);

        $sql = 'select * from ' . $xoopsDB->prefix('tad_web_schedule_data') . " where ScheduleID='{$ScheduleID}'";
        $result = $xoopsDB->query($sql) or Utility::web_error($sql, __FILE__, __LINE__);
        $SubjectArr = $LinkArr = [];
        while (false !== ($all = $xoopsDB->fetchArray($result))) {
            foreach ($all as $k => $v) {
                $$k = $v;
            }
            $key = "{$SDWeek}-{$SDSort}";

            $SubjectArr[$key] = $Subject;
            $LinkArr[$key] = $Link;
            $colorArr[$key] = $color;
            $bg_colortArr[$key] = $bg_color;
        }

        $schedule_template = $xoopsModuleConfig['schedule_template'];
        // if ($_GET['test'] == '1') {
        //     die($schedule_template);
        // }
        preg_match_all('/{([0-9]+)-([0-9]+)}/', $schedule_template, $opt);

        foreach ($opt[0] as $tag) {
            $new_tag = str_replace('{', '', $tag);
            $new_tag = str_replace('}', '', $new_tag);
            $val = empty($SubjectArr[$new_tag]) ? _MD_TCW_SCHEDULE_BLANK : $SubjectArr[$new_tag];
            $val = empty($LinkArr[$new_tag]) ? $val : "<a href='{$LinkArr[$new_tag]}' target='_blank'><i class='fa fa-link'></i> $val</a>";
            $dropped = empty($SubjectArr[$new_tag]) ? '' : 'dropped';
            $new_input = '<div id="' . $new_tag . '" class="droppable ' . $dropped . '" style="padding: 8px; margin: 0px; color: ' . $colorArr[$new_tag] . '; background-color: ' . $bg_colortArr[$new_tag] . ';"><div>' . $val . '</div></div>';

            $schedule_template = str_replace($tag, $new_input, $schedule_template);
        }

        $xoopsTpl->assign('schedule_template', $schedule_template);
        $xoopsTpl->assign('schedule_subjects', $this->get_subjects());
    }

    //新增資料到tad_web_schedule中
    public function insert()
    {
        global $xoopsDB, $xoopsUser, $WebOwnerUid;
        if (isset($_SESSION['isAssistant']['schedule'])) {
            $uid = $WebOwnerUid;
        } else {
            $uid = ($xoopsUser) ? $xoopsUser->uid() : '';
        }

        $ScheduleName = $xoopsDB->escape($_POST['ScheduleName']);
        $ScheduleDisplay = $xoopsDB->escape($_POST['ScheduleDisplay']);
        $newCateName = $xoopsDB->escape($_POST['newCateName']);
        $CateID = (int) $_POST['CateID'];
        $WebID = (int) $_POST['WebID'];
        $ScheduleTime = date('Y-m-d H:i:s');
        if ($newCateName != '') {
            $CateID = $this->WebCate->save_tad_web_cate($CateID, $newCateName);
        }

        $sql = 'insert into ' . $xoopsDB->prefix('tad_web_schedule') . "
        (`CateID`,`ScheduleName` , `ScheduleDisplay` , `uid` , `WebID` , `ScheduleCount` , `ScheduleTime`)
        values('{$CateID}' ,'{$ScheduleName}' , '{$ScheduleDisplay}'  , '{$uid}' , '{$WebID}' , '0' , '{$ScheduleTime}')";
        $xoopsDB->query($sql) or Utility::web_error($sql, __FILE__, __LINE__);

        //取得最後新增資料的流水編號
        $ScheduleID = $xoopsDB->getInsertId();
        save_assistant_post('schedule', $CateID, 'ScheduleID', $ScheduleID);

        check_quota($this->WebID);
        return $ScheduleID;
    }

    //更新tad_web_schedule某一筆資料
    public function update($ScheduleID = '')
    {
        global $xoopsDB;

        $ScheduleName = $xoopsDB->escape($_POST['ScheduleName']);
        $ScheduleDisplay = $xoopsDB->escape($_POST['ScheduleDisplay']);
        $newCateName = $xoopsDB->escape($_POST['newCateName']);
        $CateID = (int) $_POST['CateID'];
        $WebID = (int) $_POST['WebID'];
        $ScheduleTime = date('Y-m-d H:i:s');
        if ($newCateName != '') {
            $CateID = $this->WebCate->save_tad_web_cate($CateID, $newCateName);
        }

        if (!is_assistant($this->WebID, 'schedule', $CateID, 'ScheduleID', $ScheduleID)) {
            $anduid = onlyMine();
        }

        $sql = 'update ' . $xoopsDB->prefix('tad_web_schedule') . " set
        `CateID` = '{$CateID}' ,
        `ScheduleName` = '{$ScheduleName}' ,
        `ScheduleDisplay` = '{$ScheduleDisplay}',
        `ScheduleTime` = '{$ScheduleTime}'
        where ScheduleID='$ScheduleID' $anduid";
        $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);

        if ('1' == $ScheduleDisplay) {
            $sql = 'update ' . $xoopsDB->prefix('tad_web_schedule') . " set
            `ScheduleDisplay` = '0'
            where WebID='{$WebID}' and ScheduleID!='{$ScheduleID}' $anduid";
            $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);
        }

        check_quota($this->WebID);
        return $ScheduleID;
    }

    //刪除tad_web_schedule某筆資料資料
    public function delete($ScheduleID = '')
    {
        global $xoopsDB;
        $sql = 'select CateID from ' . $xoopsDB->prefix('tad_web_schedule') . " where ScheduleID='$ScheduleID'";
        $result = $xoopsDB->query($sql) or Utility::web_error($sql, __FILE__, __LINE__);
        list($CateID) = $xoopsDB->fetchRow($result);
        if (!is_assistant($this->WebID, 'schedule', $CateID, 'ScheduleID', $ScheduleID)) {
            $anduid = onlyMine();
        }
        $sql = 'delete from ' . $xoopsDB->prefix('tad_web_schedule') . " where ScheduleID='$ScheduleID' $anduid";
        if ($xoopsDB->queryF($sql)) {
            $sql = 'delete from ' . $xoopsDB->prefix('tad_web_schedule_data') . " where ScheduleID='$ScheduleID'";
            $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);
        } else {
            Utility::web_error($sql, __FILE__, __LINE__);
        }
        check_quota($this->WebID);
    }

    //刪除所有資料
    public function delete_all()
    {
        global $xoopsDB, $TadUpFiles;
        $allCateID = [];
        $sql = 'select ScheduleID,CateID from ' . $xoopsDB->prefix('tad_web_schedule') . " where WebID='{$this->WebID}'";
        $result = $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);
        while (list($ScheduleID, $CateID) = $xoopsDB->fetchRow($result)) {
            $this->delete($ScheduleID);
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
        $sql = 'select count(*) from ' . $xoopsDB->prefix('tad_web_schedule') . " where WebID='{$this->WebID}'";
        $result = $xoopsDB->query($sql) or Utility::web_error($sql, __FILE__, __LINE__);
        list($count) = $xoopsDB->fetchRow($result);
        return $count;
    }

    //新增tad_web_schedule計數器
    public function add_counter($ScheduleID = '')
    {
        global $xoopsDB;

        if (_IS_EZCLASS) {
            $ScheduleCount = redis_do($this->WebID, 'get', 'schedule', "ScheduleCount:$ScheduleID");
            if (empty($ScheduleCount)) {
                $sql = 'select ScheduleCount from ' . $xoopsDB->prefix('tad_web_schedule') . " where ScheduleID='$ScheduleID'";
                $result = $xoopsDB->query($sql) or Utility::web_error($sql, __FILE__, __LINE__);
                list($ScheduleCount) = $xoopsDB->fetchRow($result);
                redis_do($this->WebID, 'set', 'schedule', "ScheduleCount:$ScheduleID", $ScheduleCount);
            }
            return redis_do($this->WebID, 'incr', 'schedule', "ScheduleCount:$ScheduleID");
        } else {
            $sql = 'update ' . $xoopsDB->prefix('tad_web_schedule') . " set `ScheduleCount`=`ScheduleCount`+1 where `ScheduleID`='{$ScheduleID}'";
            $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);
        }
    }

    //以流水號取得某筆tad_web_schedule資料
    public function get_one_data($ScheduleID = '')
    {
        global $xoopsDB;
        if (empty($ScheduleID)) {
            return;
        }

        $sql = 'select * from ' . $xoopsDB->prefix('tad_web_schedule') . " where ScheduleID='$ScheduleID'";
        $result = $xoopsDB->query($sql) or Utility::web_error($sql, __FILE__, __LINE__);
        $data = $xoopsDB->fetchArray($result);

        if (_IS_EZCLASS) {
            $data['ScheduleCount'] = redis_do($this->WebID, 'get', 'schedule', "ScheduleCount:$ScheduleID");
        }
        return $data;
    }

    //取得某一個功課表
    public function get_one_schedule($ScheduleID)
    {
        global $xoopsDB, $xoopsModuleConfig;
        if (!isset($xoopsModuleConfig)) {
            $moduleHandler = xoops_getHandler('module');
            $xoopsModule = $moduleHandler->getByDirname('tad_web');
            $configHandler = xoops_getHandler('config');
            $xoopsModuleConfig = $configHandler->getConfigsByCat(0, $xoopsModule->getVar('mid'));
        }

        $sql = 'select * from ' . $xoopsDB->prefix('tad_web_schedule_data') . " where ScheduleID='{$ScheduleID}'";
        $result = $xoopsDB->query($sql) or Utility::web_error($sql, __FILE__, __LINE__);
        $SubjectArr = [];
        while (false !== ($all = $xoopsDB->fetchArray($result))) {
            //以下會產生這些變數： $ScheduleID , $ScheduleName , $ScheduleDisplay , $uid , $WebID , $ScheduleCount
            foreach ($all as $k => $v) {
                $$k = $v;
            }
            $key = "{$SDWeek}-{$SDSort}";
            $Subject = empty($Link) ? $Subject : "<a href='$Link' target='_blank'><i class='fa fa-link'></i> $Subject</a>";

            $SubjectArr[$key] = "<div style='padding:8px; margin:0px; color: {$color}; background-color: {$bg_color};'><div>{$Subject}</div><div style='font-size: 80%;'>{$Teacher}</div></div>";
        }

        $schedule_template = $xoopsModuleConfig['schedule_template'];
        // if ($_GET['test'] == '1') {
        //     die($schedule_template);
        // }

        preg_match_all('/{([0-9]+)-([0-9]+)}/', $schedule_template, $opt);

        foreach ($opt[0] as $tag) {
            $new_tag = str_replace('{', '', $tag);
            $new_tag = str_replace('}', '', $new_tag);
            if (!isset($SubjectArr[$new_tag])) {
                $SubjectArr[$new_tag] = '';
            }
            $schedule_template = str_replace($tag, $SubjectArr[$new_tag], $schedule_template);
        }
        return $schedule_template;
    }

    //取得科目
    public function get_subjects()
    {
        global $xoopsModuleConfig;
        $my_subject_file = XOOPS_ROOT_PATH . "/uploads/tad_web/{$this->WebID}/my_subject.json";
        if (file_exists($my_subject_file)) {
            $schedule_subjects_arr = json_decode(file_get_contents($my_subject_file), true);
        } else {
            $schedule_subjects = explode(';', $xoopsModuleConfig['schedule_subjects']);
            $schedule_subjects_arr = [];

            $i = 0;
            foreach ($schedule_subjects as $subject) {
                $schedule_subjects_arr[$i]['Subject'] = $subject;
                $schedule_subjects_arr[$i]['Teacher'] = '';
                $schedule_subjects_arr[$i]['color'] = '#000000';
                $schedule_subjects_arr[$i]['bg_color'] = '#FFFFFF';
                $i++;
            }
        }
        //die(var_export($schedule_subjects_arr));
        return $schedule_subjects_arr;
    }

    //設定科目
    public function setup_subject($ScheduleID)
    {
        global $xoopsModuleConfig, $xoopsTpl, $isMyWeb, $MyWebs, $xoopsUser;

        chk_self_web($this->WebID, $_SESSION['isAssistant']['schedule']);
        get_quota($this->WebID);

        $xoopsTpl->assign('ScheduleID', $ScheduleID);

        $schedule_subjects_arr = $this->get_subjects();
        $schedule_subjects_max_key = max(array_keys($schedule_subjects_arr));
        $schedule_subjects_max_key++;

        $xoopsTpl->assign('schedule_subjects_arr', $schedule_subjects_arr);
        $xoopsTpl->assign('item_form_index_start', $schedule_subjects_max_key);

        $MColorPicker = new MColorPicker('.color');
        $MColorPicker->render();
    }

    //儲存科目設定
    public function save_subject($ScheduleID)
    {
        global $xoopsDB;
        $my_subject_file = XOOPS_ROOT_PATH . "/uploads/tad_web/{$this->WebID}/my_subject.json";
        foreach ($_POST['old_Subject'] as $k => $old_Subject) {
            $schedule_subjects_arr[$k]['Subject'] = $_POST['Subject'][$k];
            $schedule_subjects_arr[$k]['Teacher'] = $_POST['Teacher'][$k];
            $schedule_subjects_arr[$k]['Link'] = $_POST['Link'][$k];
            $schedule_subjects_arr[$k]['color'] = $_POST['color'][$k];
            $schedule_subjects_arr[$k]['bg_color'] = $_POST['bg_color'][$k];
        }
        $schedule_subjects = json_encode($schedule_subjects_arr);
        file_put_contents($my_subject_file, $schedule_subjects);

        foreach ($_POST['old_Subject'] as $k => $old_Subject) {
            $old_Subject = $xoopsDB->escape($old_Subject);
            $Subject = $xoopsDB->escape($_POST['Subject'][$k]);
            $Teacher = $xoopsDB->escape($_POST['Teacher'][$k]);
            $Link = $xoopsDB->escape($_POST['Link'][$k]);
            $color = $xoopsDB->escape($_POST['color'][$k]);
            $bg_color = $xoopsDB->escape($_POST['bg_color'][$k]);

            $sql2 = 'update ' . $xoopsDB->prefix('tad_web_schedule_data') . " set `Subject`='{$Subject}', `Teacher`='{$Teacher}', `Link`='{$Link}', `color`='{$color}', `bg_color`='{$bg_color}' where ScheduleID='{$ScheduleID}' and `Subject`='{$old_Subject}'";
            $xoopsDB->queryF($sql2) or Utility::web_error($sql2);
        }
        //}
    }
}
