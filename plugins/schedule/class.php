<?php
class tad_web_schedule
{

    public $WebID = 0;
    public $web_cate;

    public function tad_web_schedule($WebID)
    {
        $this->WebID = $WebID;
        //die('$WebID=' . $WebID);
        $this->web_cate = new web_cate($WebID, "schedule", "tad_web_schedule");
    }

    //課表
    public function list_all($CateID = "", $limit = null)
    {
        global $xoopsDB, $xoopsTpl, $isMyWeb;

        $showWebTitle = (empty($this->WebID)) ? 1 : 0;
        $andWebID     = (empty($this->WebID)) ? "" : "and a.WebID='{$this->WebID}'";

        //取得tad_web_cate所有資料陣列
        $cate_menu = $this->web_cate->cate_menu($CateID, 'page', false, true, false, true);
        $xoopsTpl->assign('cate_menu', $cate_menu);

        $andCateID = $andDisplay = "";
        if (!empty($CateID)) {
            //取得單一分類資料
            $cate = $this->web_cate->get_tad_web_cate($CateID);
            $xoopsTpl->assign('cate', $cate);
            $andCateID = "and a.`CateID`='$CateID'";
        } else {
            $andDisplay = "and a.`ScheduleDisplay`='1'";
        }

        $sql = "select a.* from " . $xoopsDB->prefix("tad_web_schedule") . " as a left join " . $xoopsDB->prefix("tad_web") . " as b on a.WebID=b.WebID where b.`WebEnable`='1' $andWebID $andCateID $andDisplay order by b.WebSort";

        $result = $xoopsDB->query($sql) or web_error($sql);

        $main_data = "";

        $i = 0;

        $Webs = getAllWebInfo();

        while ($all = $xoopsDB->fetchArray($result)) {
            //以下會產生這些變數： $ScheduleID , $ScheduleName , $ScheduleDisplay , $uid , $WebID , $ScheduleCount , $ScheduleTime
            foreach ($all as $k => $v) {
                $$k = $v;
            }

            $main_data[$i] = $all;

            $this->web_cate->set_WebID($WebID);
            $cate = $this->web_cate->get_tad_web_cate_arr();

            $main_data[$i]['cate']     = $cate[$CateID];
            $main_data[$i]['WebTitle'] = "<a href='index.php?WebID={$WebID}'>{$Webs[$WebID]}</a>";
            $main_data[$i]['schedule'] = $this->get_one_schedule($ScheduleID);
            $i++;
        }

        $xoopsTpl->assign('schedule_amount', $i);
        $xoopsTpl->assign('schedule_data', $main_data);
        $xoopsTpl->assign('isMineSchedule', $isMyWeb);
        $xoopsTpl->assign('showWebTitleSchedule', $showWebTitle);
        $xoopsTpl->assign('schedule', get_db_plugin($this->WebID, 'schedule'));
        return $i;

    }

    //以流水號秀出某筆tad_web_schedule資料內容
    public function show_one($ScheduleID = "")
    {
        global $xoopsDB, $xoopsTpl, $isMyWeb, $xoopsModuleConfig;
        if (empty($ScheduleID)) {
            return;
        }

        $ScheduleID = intval($ScheduleID);
        $this->add_counter($ScheduleID);

        $sql    = "select * from " . $xoopsDB->prefix("tad_web_schedule") . " where ScheduleID='{$ScheduleID}'";
        $result = $xoopsDB->query($sql) or web_error($sql);
        $all    = $xoopsDB->fetchArray($result);

        //以下會產生這些變數： $ScheduleID , $ScheduleName , $ScheduleDisplay , $uid , $WebID , $ScheduleCount ,$ScheduleTime
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

        $xoopsTpl->assign('isMineSchedule', $isMyWeb);
        $xoopsTpl->assign('ScheduleName', $ScheduleName);
        $xoopsTpl->assign('ScheduleDisplay', $ScheduleDisplay);
        $xoopsTpl->assign('uid_name', $uid_name);
        $xoopsTpl->assign('ScheduleCount', $ScheduleCount);
        $xoopsTpl->assign('ScheduleTime', $ScheduleTime);
        $xoopsTpl->assign('ScheduleID', $ScheduleID);
        $xoopsTpl->assign('ScheduleInfo', sprintf(_MD_TCW_INFO, $uid_name, $ScheduleTime, $ScheduleCount));

        //取得單一分類資料
        $cate = $this->web_cate->get_tad_web_cate($CateID);
        $xoopsTpl->assign('cate', $cate);

        $schedule_template = $this->get_one_schedule($ScheduleID);
        $xoopsTpl->assign('schedule_template', $schedule_template);
    }

    //tad_web_schedule編輯表單
    public function edit_form($ScheduleID = "")
    {
        global $xoopsDB, $xoopsUser, $MyWebs, $isMyWeb, $xoopsTpl, $WebName, $xoopsModuleConfig;

        if (!$isMyWeb and $MyWebs) {
            redirect_header($_SERVER['PHP_SELF'] . "?op=WebID={$MyWebs[0]}&edit_form", 3, _MD_TCW_AUTO_TO_HOME);
        } elseif (!$xoopsUser or empty($this->WebID) or empty($MyWebs)) {
            redirect_header("index.php", 3, _MD_TCW_NOT_OWNER);
        }

        //抓取預設值
        if (!empty($ScheduleID)) {
            $DBV = $this->get_one_data($ScheduleID);
        } else {
            $DBV = array();
        }

        //預設值設定

        //設定「ScheduleID」欄位預設值
        $ScheduleID = (!isset($DBV['ScheduleID'])) ? $ScheduleID : $DBV['ScheduleID'];
        $xoopsTpl->assign('ScheduleID', $ScheduleID);

        //設定「ScheduleName」欄位預設值
        $ScheduleName = (!isset($DBV['ScheduleName'])) ? $WebName . _MD_TCW_SCHEDULE : $DBV['ScheduleName'];
        $xoopsTpl->assign('ScheduleName', $ScheduleName);

        //設定「ScheduleDisplay」欄位預設值
        $ScheduleDisplay = (!isset($DBV['ScheduleDisplay'])) ? "0" : $DBV['ScheduleDisplay'];
        $xoopsTpl->assign('ScheduleDisplay', $ScheduleDisplay);

        //設定「uid」欄位預設值
        $user_uid = ($xoopsUser) ? $xoopsUser->getVar('uid') : "";
        $uid      = (!isset($DBV['uid'])) ? $user_uid : $DBV['uid'];
        $xoopsTpl->assign('uid', $uid);

        //設定「WebID」欄位預設值
        $WebID = (!isset($DBV['WebID'])) ? $this->WebID : $DBV['WebID'];
        $xoopsTpl->assign('WebID', $WebID);

        //設定「ScheduleCount」欄位預設值
        $ScheduleCount = (!isset($DBV['ScheduleCount'])) ? "" : $DBV['ScheduleCount'];
        $xoopsTpl->assign('ScheduleCount', $ScheduleCount);

        //設定「ScheduleTime」欄位預設值
        $ScheduleTime = (!isset($DBV['ScheduleTime'])) ? "" : $DBV['ScheduleTime'];
        $xoopsTpl->assign('ScheduleTime', $ScheduleTime);

        //設定「CateID」欄位預設值
        $CateID = (!isset($DBV['CateID'])) ? "" : $DBV['CateID'];

        $ys = $this->get_seme();
        $xoopsTpl->assign('ys', $ys);

        $this->web_cate->set_demo_txt(sprintf(_MD_TCW_SCHEDULE_CATE_DEMO, $ys[0], $ys[1]));
        $cate_menu = $this->web_cate->cate_menu($CateID);
        $xoopsTpl->assign('cate_menu', $cate_menu);

        $op = (empty($ScheduleID)) ? "insert" : "update";

        if (!file_exists(TADTOOLS_PATH . "/formValidator.php")) {
            redirect_header("index.php", 3, _MD_NEED_TADTOOLS);
        }
        include_once TADTOOLS_PATH . "/formValidator.php";
        $formValidator      = new formValidator("#myForm", true);
        $formValidator_code = $formValidator->render();

        $xoopsTpl->assign('formValidator_code', $formValidator_code);
        $xoopsTpl->assign('next_op', $op);

        $sql        = "select * from " . $xoopsDB->prefix("tad_web_schedule_data") . " where ScheduleID='{$ScheduleID}'";
        $result     = $xoopsDB->query($sql) or web_error($sql);
        $SubjectArr = '';
        while ($all = $xoopsDB->fetchArray($result)) {
            foreach ($all as $k => $v) {
                $$k = $v;
            }
            $key = "{$SDWeek}-{$SDSort}";

            $SubjectArr[$key]   = $Subject;
            $colorArr[$key]     = $color;
            $bg_colortArr[$key] = $bg_color;
        }

        $schedule_template = $xoopsModuleConfig['schedule_template'];
        preg_match_all('/{([0-9]+)-([0-9]+)}/', $schedule_template, $opt);

        foreach ($opt[0] as $tag) {
            $new_tag   = str_replace("{", '', $tag);
            $new_tag   = str_replace("}", '', $new_tag);
            $val       = empty($SubjectArr[$new_tag]) ? _MD_TCW_SCHEDULE_BLANK : $SubjectArr[$new_tag];
            $dropped   = empty($SubjectArr[$new_tag]) ? '' : 'dropped';
            $new_input = '<div id="' . $new_tag . '" class="droppable ' . $dropped . '" style="padding: 8px; margin: 0px; color: ' . $colorArr[$new_tag] . '; background-color: ' . $bg_colortArr[$new_tag] . ';"><div>' . $val . '</div></div>';

            $schedule_template = str_replace($tag, $new_input, $schedule_template);
        }

        $xoopsTpl->assign('schedule_template', $schedule_template);
        $xoopsTpl->assign('schedule_subjects', $this->get_subjects());

    }

    //新增資料到tad_web_schedule中
    public function insert()
    {
        global $xoopsDB, $xoopsUser;

        //取得使用者編號
        $uid = ($xoopsUser) ? $xoopsUser->getVar('uid') : "";

        $myts                     = &MyTextSanitizer::getInstance();
        $_POST['ScheduleName']    = $myts->addSlashes($_POST['ScheduleName']);
        $_POST['ScheduleDisplay'] = $myts->addSlashes($_POST['ScheduleDisplay']);
        $_POST['CateID']          = intval($_POST['CateID']);
        $_POST['WebID']           = intval($_POST['WebID']);
        $ScheduleTime             = date("Y-m-d H:i:s");

        $CateID = $this->web_cate->save_tad_web_cate($_POST['CateID'], $_POST['newCateName']);
        $sql    = "insert into " . $xoopsDB->prefix("tad_web_schedule") . "
        (`CateID`,`ScheduleName` , `ScheduleDisplay` , `uid` , `WebID` , `ScheduleCount` , `ScheduleTime`)
        values('{$CateID}' ,'{$_POST['ScheduleName']}' , '{$_POST['ScheduleDisplay']}'  , '{$uid}' , '{$_POST['WebID']}' , '0' , '{$ScheduleTime}')";
        $xoopsDB->query($sql) or web_error($sql);

        //取得最後新增資料的流水編號
        $ScheduleID = $xoopsDB->getInsertId();

        return $ScheduleID;
    }

    //更新tad_web_schedule某一筆資料
    public function update($ScheduleID = "")
    {
        global $xoopsDB;

        $myts                     = &MyTextSanitizer::getInstance();
        $_POST['ScheduleName']    = $myts->addSlashes($_POST['ScheduleName']);
        $_POST['ScheduleDisplay'] = $myts->addSlashes($_POST['ScheduleDisplay']);
        $_POST['CateID']          = intval($_POST['CateID']);
        $_POST['WebID']           = intval($_POST['WebID']);
        $ScheduleTime             = date("Y-m-d H:i:s");

        $CateID = $this->web_cate->save_tad_web_cate($_POST['CateID'], $_POST['newCateName']);

        $anduid = onlyMine();

        $sql = "update " . $xoopsDB->prefix("tad_web_schedule") . " set
         `CateID` = '{$CateID}' ,
         `ScheduleName` = '{$_POST['ScheduleName']}' ,
         `ScheduleDisplay` = '{$_POST['ScheduleDisplay']}',
         `ScheduleTime` = '{$ScheduleTime}'
        where ScheduleID='$ScheduleID' $anduid";
        $xoopsDB->queryF($sql) or web_error($sql);

        if ($_POST['ScheduleDisplay'] == '1') {
            $sql = "update " . $xoopsDB->prefix("tad_web_schedule") . " set
             `ScheduleDisplay` = '0'
            where WebID='{$_POST['WebID']}' and ScheduleID!='{$ScheduleID}' $anduid";
            $xoopsDB->queryF($sql) or web_error($sql);
        }

        return $ScheduleID;
    }

    //刪除tad_web_schedule某筆資料資料
    public function delete($ScheduleID = "")
    {
        global $xoopsDB;
        $anduid = onlyMine();
        $sql    = "delete from " . $xoopsDB->prefix("tad_web_schedule") . " where ScheduleID='$ScheduleID' $anduid";
        if ($xoopsDB->queryF($sql)) {

            $sql = "delete from " . $xoopsDB->prefix("tad_web_schedule_data") . " where ScheduleID='$ScheduleID'";
            $xoopsDB->queryF($sql) or web_error($sql);
        } else {
            web_error($sql);
        }

    }

    //新增tad_web_schedule計數器
    public function add_counter($ScheduleID = '')
    {
        global $xoopsDB;
        $sql = "update " . $xoopsDB->prefix("tad_web_schedule") . " set `ScheduleCount`=`ScheduleCount`+1 where `ScheduleID`='{$ScheduleID}'";
        $xoopsDB->queryF($sql) or web_error($sql);
    }

    //以流水號取得某筆tad_web_schedule資料
    public function get_one_data($ScheduleID = "")
    {
        global $xoopsDB;
        if (empty($ScheduleID)) {
            return;
        }

        $sql    = "select * from " . $xoopsDB->prefix("tad_web_schedule") . " where ScheduleID='$ScheduleID'";
        $result = $xoopsDB->query($sql) or web_error($sql);
        $data   = $xoopsDB->fetchArray($result);
        return $data;
    }

    //取得目前的學年學期陣列
    public function get_seme()
    {
        global $xoopsDB;
        $y = date("Y");
        $m = date("n");
        $d = date("j");
        if ($m >= 8) {
            $ys[0] = $y - 1911;
            $ys[1] = 1;
        } elseif ($m >= 2) {
            $ys[0] = $y - 1912;
            $ys[1] = 2;
        } else {
            $ys[0] = $y - 1912;
            $ys[1] = 1;
        }
        return $ys;
    }

    //取得某一個功課表
    public function get_one_schedule($ScheduleID)
    {
        global $xoopsDB, $xoopsModuleConfig;
        if (!isset($xoopsModuleConfig)) {

            $modhandler        = &xoops_gethandler('module');
            $xoopsModule       = &$modhandler->getByDirname("tad_web");
            $config_handler    = &xoops_gethandler('config');
            $xoopsModuleConfig = &$config_handler->getConfigsByCat(0, $xoopsModule->getVar('mid'));
        }

        $sql        = "select * from " . $xoopsDB->prefix("tad_web_schedule_data") . " where ScheduleID='{$ScheduleID}'";
        $result     = $xoopsDB->query($sql) or web_error($sql);
        $SubjectArr = '';
        while ($all = $xoopsDB->fetchArray($result)) {
            //以下會產生這些變數： $ScheduleID , $ScheduleName , $ScheduleDisplay , $uid , $WebID , $ScheduleCount
            foreach ($all as $k => $v) {
                $$k = $v;
            }
            $key = "{$SDWeek}-{$SDSort}";

            $SubjectArr[$key] = "<div style='padding:8px; margin:0px; color: {$color}; background-color: {$bg_color};'><div>{$Subject}</div><div style='font-size:12px;'>{$Teacher}</div></div>";
        }

        $schedule_template = $xoopsModuleConfig['schedule_template'];

        preg_match_all('/{([0-9]+)-([0-9]+)}/', $schedule_template, $opt);

        foreach ($opt[0] as $tag) {
            $new_tag = str_replace("{", '', $tag);
            $new_tag = str_replace("}", '', $new_tag);
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
            $schedule_subjects     = explode(';', $xoopsModuleConfig['schedule_subjects']);
            $schedule_subjects_arr = '';

            $i = 0;
            foreach ($schedule_subjects as $subject) {
                $schedule_subjects_arr[$i]['Subject']  = $subject;
                $schedule_subjects_arr[$i]['Teacher']  = '';
                $schedule_subjects_arr[$i]['color']    = '#000000';
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
        if (!$isMyWeb and $MyWebs) {
            redirect_header($_SERVER['PHP_SELF'] . "?op=WebID={$MyWebs[0]}&edit_form", 3, _MD_TCW_AUTO_TO_HOME);
        } elseif (!$xoopsUser or empty($this->WebID) or empty($MyWebs)) {
            redirect_header("index.php", 3, _MD_TCW_NOT_OWNER);
        }

        $xoopsTpl->assign('ScheduleID', $ScheduleID);

        $schedule_subjects_arr = $this->get_subjects();
        $xoopsTpl->assign('schedule_subjects_arr', $schedule_subjects_arr);
        $xoopsTpl->assign('item_form_index_start', sizeof($schedule_subjects_arr));

        //顏色設定
        if (!file_exists(XOOPS_ROOT_PATH . "/modules/tadtools/mColorPicker.php")) {
            redirect_header("index.php", 3, _MA_NEED_TADTOOLS);
        }
        include_once XOOPS_ROOT_PATH . "/modules/tadtools/mColorPicker.php";
        $mColorPicker      = new mColorPicker('.color');
        $mColorPicker_code = $mColorPicker->render();
        $xoopsTpl->assign('mColorPicker_code', $mColorPicker_code);

    }

    //儲存科目設定
    public function save_subject($ScheduleID)
    {
        global $xoopsDB;
        $my_subject_file = XOOPS_ROOT_PATH . "/uploads/tad_web/{$this->WebID}/my_subject.json";
        foreach ($_POST['old_Subject'] as $k => $old_Subject) {
            $schedule_subjects_arr[$k]['Subject']  = $_POST['Subject'][$k];
            $schedule_subjects_arr[$k]['Teacher']  = $_POST['Teacher'][$k];
            $schedule_subjects_arr[$k]['color']    = $_POST['color'][$k];
            $schedule_subjects_arr[$k]['bg_color'] = $_POST['bg_color'][$k];
        }
        $schedule_subjects = json_encode($schedule_subjects_arr);
        file_put_contents($my_subject_file, $schedule_subjects);

        //找出本站所有功課表
        // $sql    = "select ScheduleID from " . $xoopsDB->prefix("tad_web_schedule") . " where WebID='{$this->WebID}'";
        // $result = $xoopsDB->query($sql) or web_error($sql);

        // while (list($ScheduleID) = $xoopsDB->fetchRow($result)) {
        foreach ($_POST['old_Subject'] as $k => $old_Subject) {
            $Subject  = $_POST['Subject'][$k];
            $Teacher  = $_POST['Teacher'][$k];
            $color    = $_POST['color'][$k];
            $bg_color = $_POST['bg_color'][$k];

            $sql2 = "update " . $xoopsDB->prefix("tad_web_schedule_data") . " set `Subject`='{$Subject}', `Teacher`='{$Teacher}', `color`='{$color}', `bg_color`='{$bg_color}' where ScheduleID='{$ScheduleID}' and `Subject`='{$old_Subject}'";
            $xoopsDB->queryF($sql2) or web_error($sql2);
        }
        //}
    }
}
