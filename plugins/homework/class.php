<?php
class tad_web_homework
{

    public $WebID = 0;
    public $web_cate;
    public $setup;
    public $calendar_setup;

    public function __construct($WebID)
    {
        $this->WebID          = $WebID;
        $this->web_cate       = new web_cate($WebID, "homework", "tad_web_homework");
        $this->setup          = get_plugin_setup_values($WebID, "homework");
        $this->calendar_setup = get_plugin_setup_values($WebID, "calendar");
    }

    //聯絡簿
    public function list_all($CateID = "", $limit = null, $mode = "assign")
    {
        global $xoopsDB, $xoopsTpl, $MyWebs, $isMyWeb, $plugin_menu_var;

        $myts     = MyTextSanitizer::getInstance();
        $andWebID = (empty($this->WebID)) ? "" : "and a.WebID='{$this->WebID}'";

        $andCateID = "";
        if ($mode == "assign") {
            //取得tad_web_cate所有資料陣列
            if (!empty($plugin_menu_var)) {
                $this->web_cate->set_button_value($plugin_menu_var['homework']['short'] . _MD_TCW_CATE_TOOLS);
                $this->web_cate->set_default_option_text(sprintf(_MD_TCW_SELECT_PLUGIN_CATE, $plugin_menu_var['homework']['short']));
                $this->web_cate->set_col_md(0, 6);
                $cate_menu = $this->web_cate->cate_menu($CateID, 'page', false, true, false, false);
                $xoopsTpl->assign('cate_menu', $cate_menu);
            }

            if (!empty($CateID) and is_numeric($CateID)) {
                //取得單一分類資料
                $cate = $this->web_cate->get_tad_web_cate($CateID);
                if ($CateID and $cate['CateEnable'] != '1') {
                    return;
                }
                $xoopsTpl->assign('cate', $cate);
                $andCateID = "and a.`CateID`='$CateID'";
                $xoopsTpl->assign('HomeworkDefCateID', $CateID);
            }
        }

        $now = date("Y-m-d H:i:s");

        if (_IS_EZCLASS and !empty($_GET['county'])) {
            //https://class.tn.edu.tw/modules/tad_web/index.php?county=臺南市&city=永康區&SchoolName=XX國小
            include_once $GLOBALS['xoops']->path('/modules/system/include/functions.php');
            $county        = system_CleanVars($_REQUEST, 'county', '', 'string');
            $city          = system_CleanVars($_REQUEST, 'city', '', 'string');
            $SchoolName    = system_CleanVars($_REQUEST, 'SchoolName', '', 'string');
            $andCounty     = !empty($county) ? "and c.county='{$county}'" : "";
            $andCity       = !empty($city) ? "and c.city='{$city}'" : "";
            $andSchoolName = !empty($SchoolName) ? "and c.SchoolName='{$SchoolName}'" : "";

            $sql = "select a.* from " . $xoopsDB->prefix("tad_web_homework") . " as a
            left join " . $xoopsDB->prefix("tad_web") . " as b on a.WebID=b.WebID
            left join " . $xoopsDB->prefix("apply") . " as c on b.WebOwnerUid=c.uid
            left join " . $xoopsDB->prefix("tad_web_cate") . " as d on a.CateID=d.CateID
            where  b.`WebEnable`='1' and (d.CateEnable='1' or a.CateID='0') and a.HomeworkPostDate <= '{$now}' $andCounty $andCity $andSchoolName
            order by a.toCal desc";
        } else {
            $sql = "select a.* from " . $xoopsDB->prefix("tad_web_homework") . " as a
            left join " . $xoopsDB->prefix("tad_web") . " as b on a.WebID=b.WebID
            left join " . $xoopsDB->prefix("tad_web_cate") . " as c on a.CateID=c.CateID
            where b.`WebEnable`='1' and (c.CateEnable='1' or a.CateID='0') and a.HomeworkPostDate <= '{$now}' $andWebID $andCateID
            order by a.toCal desc";
        }
        $to_limit = empty($limit) ? 20 : $limit;

        //getPageBar($原sql語法, 每頁顯示幾筆資料, 最多顯示幾個頁數選項);
        $PageBar = getPageBar($sql, $to_limit, 10);
        $bar     = $PageBar['bar'];
        $sql     = $PageBar['sql'];
        $total   = $PageBar['total'];

        $result = $xoopsDB->query($sql) or web_error($sql);

        $main_data = array();

        $i = 0;

        $Webs     = getAllWebInfo();
        $WebNames = getAllWebInfo('WebName');
        $cweek    = array(0 => _MD_TCW_SUN, _MD_TCW_MON, _MD_TCW_TUE, _MD_TCW_WED, _MD_TCW_THU, _MD_TCW_FRI, _MD_TCW_SAT);

        $cate = $this->web_cate->get_tad_web_cate_arr();
        $yet  = "";
        while ($all = $xoopsDB->fetchArray($result)) {
            //以下會產生這些變數： $HomeworkID , $HomeworkTitle , $HomeworkContent , $HomeworkDate , $toCal , $WebID  , $HomeworkCounter, $uid, $HomeworkPostDate
            foreach ($all as $k => $v) {
                $$k = $v;
            }

            $main_data[$i]                = $all;
            $main_data[$i]['id'] = $HomeworkID;
            $main_data[$i]['id_name'] = 'HomeworkID';
            $main_data[$i]['title'] = $HomeworkTitle;
            // $assistant = get_assistant($CateID);
            // die(var_dump($assistant));
            // $isAssistant                = is_assistant($CateID, 'HomeworkID', $HomeworkID);
            $main_data[$i]['isCanEdit'] = isCanEdit($this->WebID, 'homework', $CateID, 'HomeworkID', $HomeworkID);

            //找出聯絡簿內容
            $sql     = "select `HomeworkCol`, `Content` from " . $xoopsDB->prefix("tad_web_homework_content") . " where HomeworkID='{$HomeworkID}'";
            $result2 = $xoopsDB->query($sql) or web_error($sql);

            $ColsNum = 0;
            while (list($HomeworkCol, $Content) = $xoopsDB->fetchRow($result2)) {
                $Content                     = $myts->displayTarea($Content, 1, 0, 0, 1, 0);
                $main_data[$i][$HomeworkCol] = $Content;
                if ($HomeworkCol != 'other') {
                    $ColsNum++;
                }
            }

            $ColWidth                  = empty($ColsNum) ? 1 : 12 / $ColsNum;
            $main_data[$i]['ColsNum']  = $ColsNum;
            $main_data[$i]['ColWidth'] = $ColWidth;
            $this->web_cate->set_WebID($WebID);

            $main_data[$i]['cate']     = isset($cate[$CateID]) ? $cate[$CateID] : '';
            $main_data[$i]['WebName']  = $WebNames[$WebID];
            $main_data[$i]['WebTitle'] = "<a href='index.php?WebID={$WebID}'>{$Webs[$WebID]}</a>";
            // $main_data[$i]['isMyWeb']  = in_array($WebID, $MyWebs) ? 1 : 0;

            if (empty($HomeworkTitle)) {
                $HomeworkTitle = _MD_TCW_EMPTY_TITLE;
            }

            $main_data[$i]['HomeworkTitle'] = $HomeworkTitle;
            $main_data[$i]['HomeworkDate']  = $HomeworkDate;
            $w                              = date("w", strtotime($toCal));
            $main_data[$i]['Week']          = $cweek[$w];
            $i++;
        }
        // die(var_export($main_data));
        //可愛刪除
        if (!file_exists(XOOPS_ROOT_PATH . "/modules/tadtools/sweet_alert.php")) {
            redirect_header("index.php", 3, _MA_NEED_TADTOOLS);
        }
        include_once XOOPS_ROOT_PATH . "/modules/tadtools/sweet_alert.php";
        $sweet_alert = new sweet_alert();
        $sweet_alert->render("delete_homework_func", "homework.php?op=delete&WebID={$this->WebID}&HomeworkID=", 'HomeworkID');

        //找出尚未發布的聯絡簿
        $yet_data = array();
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
                $total++;
            }
        }

        // if ($_GET['test'] == 1) {
        //     die(var_export($yet_data));
        // }

        if (!file_exists(XOOPS_ROOT_PATH . "/modules/tadtools/fullcalendar.php")) {
            redirect_header("http://campus-xoops.tn.edu.tw/modules/tad_modules/index.php?module_sn=1", 3, _TAD_NEED_TADTOOLS);
        }
        include_once XOOPS_ROOT_PATH . "/modules/tadtools/fullcalendar.php";
        $fullcalendar = new fullcalendar();
        if ($this->WebID) {
            $fullcalendar->add_js_parameter('firstDay', $this->calendar_setup['week_first_day']);
            $fullcalendar->add_json_parameter('WebID', $this->WebID);
        }
        if (strrpos($_SERVER['PHP_SELF'], 'homework.php') !== false) {
            $fullcalendar->add_json_parameter('CalKind', 'homework');
        }
        $fullcalendar_code = $fullcalendar->render('#calendar', XOOPS_URL . '/modules/tad_web/get_event.php');

        if ($mode == "return") {
            $data['main_data'] = $main_data;
            $data['yet_data']  = $yet_data;
            $data['total']     = $total;
            $data['today']     = date("Y-m-d");
            return $data;
        } else {
            $xoopsTpl->assign('fullcalendar_code', $fullcalendar_code);
            $xoopsTpl->assign('CalKind', 'homework');
            $xoopsTpl->assign('homework_data', $main_data);
            $xoopsTpl->assign('yet_data', $yet_data);
            $xoopsTpl->assign('bar', $bar);
            $xoopsTpl->assign('today', date("Y-m-d"));
            $xoopsTpl->assign('homework', get_db_plugin($this->WebID, 'homework'));
            return $total;
        }
    }

    //以流水號秀出某筆tad_web_homework資料內容
    public function show_one($HomeworkID = "")
    {
        global $xoopsDB, $WebID, $isAdmin, $xoopsTpl, $TadUpFiles, $isMyWeb;
        if (empty($HomeworkID)) {
            return;
        }
        $myts       = MyTextSanitizer::getInstance();
        $HomeworkID = (int)$HomeworkID;
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

        //找出聯絡簿內容
        $sql     = "select `HomeworkCol`, `Content` from " . $xoopsDB->prefix("tad_web_homework_content") . " where HomeworkID='{$HomeworkID}'";
        $result  = $xoopsDB->query($sql) or web_error($sql);
        $ColsNum = 0;
        while (list($HomeworkCol, $Content) = $xoopsDB->fetchRow($result)) {
            $Content = $myts->displayTarea($Content, 1, 0, 0, 1, 0);
            $xoopsTpl->assign($HomeworkCol, $Content);
            if ($HomeworkCol != 'other') {
                $ColsNum++;
            }
        }
        $ColWidth = 12 / $ColsNum;
        $xoopsTpl->assign('ColsNum', $ColsNum);
        $xoopsTpl->assign('ColWidth', $ColWidth);

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

        $xoopsTpl->assign('HomeworkTitle', $HomeworkTitle);
        $xoopsTpl->assign('HomeworkContent', $HomeworkContent);
        $xoopsTpl->assign('uid_name', $uid_name);
        $xoopsTpl->assign('HomeworkDate', $HomeworkDate);
        $xoopsTpl->assign('HomeworkCounter', $HomeworkCounter);
        $xoopsTpl->assign('HomeworkPostDate', $HomeworkPostDate);
        $xoopsTpl->assign('HomeworkID', $HomeworkID);
        $assistant   = is_assistant($CateID, 'HomeworkID', $HomeworkID);
        $isAssistant = !empty($assistant) ? true : false;
        $uid_name = $isAssistant ? "{$uid_name} <a href='#' title='由{$assistant['MemName']}代理發布'><i class='fa fa-male'></i></a>" : $uid_name;
        $xoopsTpl->assign("isAssistant", $isAssistant);

        $xoopsTpl->assign("isCanEdit", isCanEdit($this->WebID, 'homework', $CateID, 'HomeworkID', $HomeworkID));

        $xoopsTpl->assign('HomeworkInfo', sprintf(_MD_TCW_INFO, $uid_name, $HomeworkDate, $HomeworkCounter));

        $xoopsTpl->assign('xoops_pagetitle', $HomeworkTitle);
        $xoopsTpl->assign('fb_description', $HomeworkDate . xoops_substr(strip_tags($HomeworkContent), 0, 300));

        //取得單一分類資料
        $cate = $this->web_cate->get_tad_web_cate($CateID);
        if ($CateID and $cate['CateEnable'] != '1') {
            return;
        }
        $xoopsTpl->assign('cate', $cate);

        //可愛刪除
        if (!file_exists(XOOPS_ROOT_PATH . "/modules/tadtools/sweet_alert.php")) {
            redirect_header("index.php", 3, _MA_NEED_TADTOOLS);
        }
        include_once XOOPS_ROOT_PATH . "/modules/tadtools/sweet_alert.php";
        $sweet_alert = new sweet_alert();
        $sweet_alert->render("delete_homework_func", "homework.php?op=delete&WebID={$this->WebID}&HomeworkID=", 'HomeworkID');
        $xoopsTpl->assign("fb_comments", fb_comments($this->setup['use_fb_comments']));

    }

    //tad_web_homework編輯表單
    public function edit_form($HomeworkID = "")
    {
        global $xoopsDB, $xoopsUser, $MyWebs, $isMyWeb, $xoopsTpl, $TadUpFiles, $plugin_menu_var;

        chk_self_web($this->WebID, $_SESSION['isAssistant']['homework']);
        get_quota($this->WebID);

        $Class = get_tad_web($this->WebID);

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
        // $HomeworkPostDate = (!isset($DBV['HomeworkPostDate'])) ? date("Y-m-d 12:00:00") : $DBV['HomeworkPostDate'];
        // $xoopsTpl->assign('HomeworkPostDate', $HomeworkPostDate);

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
                $HomeworkPostDate = $DBV['HomeworkPostDate'];
            }
        } else {
            $HomeworkPostDate = date("Y-m-d H:i:s");
        }
        $xoopsTpl->assign('HomeworkPostDate', $HomeworkPostDate);

        //設定「HomeworkContent1」欄位預設值
        $today_homework = (!isset($DBV['today_homework'])) ? '<ol><li></li></ol>' : $DBV['today_homework'];
        //設定「HomeworkContent2」欄位預設值
        $bring = (!isset($DBV['bring'])) ? '<ol><li></li></ol>' : $DBV['bring'];
        //設定「HomeworkContent3」欄位預設值
        $teacher_say = (!isset($DBV['teacher_say'])) ? '<ol><li></li></ol>' : $DBV['teacher_say'];
        //設定「HomeworkContent4」欄位預設值
        $other = (!isset($DBV['other'])) ? '' : $DBV['other'];

        //設定「CateID」欄位預設值
        $DefCateID = isset($_SESSION['isAssistant']['homework']) ? $_SESSION['isAssistant']['homework'] : '';
        $CateID    = (!isset($DBV['CateID'])) ? $DefCateID : $DBV['CateID'];
        $this->web_cate->set_button_value($plugin_menu_var['homework']['short'] . _MD_TCW_CATE_TOOLS);
        $this->web_cate->set_default_option_text(sprintf(_MD_TCW_SELECT_PLUGIN_CATE, $plugin_menu_var['homework']['short']));
        $cate_menu = isset($_SESSION['isAssistant']['homework']) ? $this->web_cate->hidden_cate_menu($CateID) : $this->web_cate->cate_menu($CateID);
        $xoopsTpl->assign('cate_menu_form', $cate_menu);

        $op = (empty($HomeworkID)) ? "insert" : "update";

        if (!file_exists(TADTOOLS_PATH . "/formValidator.php")) {
            redirect_header("index.php", 3, _MD_NEED_TADTOOLS);
        }
        include_once TADTOOLS_PATH . "/formValidator.php";
        $formValidator      = new formValidator("#myForm", true);
        $formValidator_code = $formValidator->render();
        $xoopsTpl->assign('formValidator_code', $formValidator_code);

        include_once XOOPS_ROOT_PATH . "/modules/tadtools/ck.php";
        mk_dir(XOOPS_ROOT_PATH . "/uploads/tad_web/{$this->WebID}/homework");
        mk_dir(XOOPS_ROOT_PATH . "/uploads/tad_web/{$this->WebID}/homework/image");
        mk_dir(XOOPS_ROOT_PATH . "/uploads/tad_web/{$this->WebID}/homework/file");

        if (!empty($DBV['HomeworkContent'])) {
            $ck = new CKEditor("tad_web/{$this->WebID}/homework", "HomeworkContent", $HomeworkContent);
            $ck->setHeight(300);
            $editor = $ck->render();
            $xoopsTpl->assign('HomeworkContent_editor', $editor);
        } else {
            $ck1 = new CKEditor("tad_web/{$this->WebID}/homework", "today_homework", $today_homework);
            $ck1->setToolbarSet('mySimple');
            $ck1->setHeight(150);
            $editor1 = $ck1->render();
            $xoopsTpl->assign('HomeworkContent_editor1', $editor1);

            $ck2 = new CKEditor("tad_web/{$this->WebID}/homework", "bring", $bring);
            $ck2->setToolbarSet('mySimple');
            $ck2->setHeight(150);
            $editor2 = $ck2->render();
            $xoopsTpl->assign('HomeworkContent_editor2', $editor2);

            $ck3 = new CKEditor("tad_web/{$this->WebID}/homework", "teacher_say", $teacher_say);
            $ck3->setToolbarSet('mySimple');
            $ck3->setHeight(150);
            $editor3 = $ck3->render();
            $xoopsTpl->assign('HomeworkContent_editor3', $editor3);

            $ck4 = new CKEditor("tad_web/{$this->WebID}/homework", "other", $other);
            $ck4->setToolbarSet('mySimple');
            $ck4->setHeight(100);
            $editor4 = $ck4->render();
            $xoopsTpl->assign('HomeworkContent_editor4', $editor4);
        }

        $xoopsTpl->assign('next_op', $op);

        $TadUpFiles->set_col("HomeworkID", $HomeworkID);
        $upform = $TadUpFiles->upform();
        $xoopsTpl->assign('upform', $upform);
    }

    //新增資料到tad_web_homework中
    public function insert()
    {
        global $xoopsDB, $xoopsUser, $TadUpFiles, $WebOwnerUid;
        if (isset($_SESSION['isAssistant']['homework'])) {
            $uid = $WebOwnerUid;
        } else {
            $uid = ($xoopsUser) ? $xoopsUser->uid() : "";
        }

        $myts                     = MyTextSanitizer::getInstance();
        $_POST['HomeworkTitle']   = $myts->addSlashes($_POST['HomeworkTitle']);
        $_POST['HomeworkContent'] = $myts->addSlashes($_POST['HomeworkContent']);
        $_POST['CateID']          = (int)$_POST['CateID'];
        $_POST['WebID']           = (int)$_POST['WebID'];
        $HomeworkDate             = date("Y-m-d H:i:s");

        $_POST['today_homework'] = $myts->addSlashes($_POST['today_homework']);
        $today_homework          = $this->remove_html($_POST['today_homework']);
        $_POST['bring']          = $myts->addSlashes($_POST['bring']);
        $bring                   = $this->remove_html($_POST['bring']);
        $_POST['teacher_say']    = $myts->addSlashes($_POST['teacher_say']);
        $teacher_say             = $this->remove_html($_POST['teacher_say']);
        $_POST['other']          = $myts->addSlashes($_POST['other']);
        $other                   = $this->remove_html($_POST['other']);

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
            // $HomeworkPostDate = $_POST['toCal'] . " 00:00:00";
            $HomeworkPostDate = $HomeworkDate;
        }

        $CateID = $this->web_cate->save_tad_web_cate($_POST['CateID'], $_POST['newCateName']);

        $sql = "insert into " . $xoopsDB->prefix("tad_web_homework") . "
        (`CateID`,`HomeworkTitle` , `HomeworkContent` , `HomeworkDate` , `toCal` , `WebID` , `HomeworkCounter` , `uid` , `HomeworkPostDate`)
        values('{$CateID}','{$_POST['HomeworkTitle']}' , '{$_POST['HomeworkContent']}' , '{$HomeworkDate}' , '{$_POST['toCal']}' , '{$_POST['WebID']}' , '0' , '{$uid}' , '{$HomeworkPostDate}')";
        $xoopsDB->query($sql) or web_error($sql);

        //取得最後新增資料的流水編號
        $HomeworkID = $xoopsDB->getInsertId();
        save_assistant_post($CateID, 'HomeworkID', $HomeworkID);

        if (!empty($today_homework)) {
            $sql = "insert into " . $xoopsDB->prefix("tad_web_homework_content") . "
            (`HomeworkID`,`HomeworkCol` , `Content` , `WebID` )
            values('{$HomeworkID}','today_homework' , '{$_POST['today_homework']}' , '{$_POST['WebID']}')";
            $xoopsDB->query($sql) or web_error($sql);
        }

        if (!empty($bring)) {
            $sql = "insert into " . $xoopsDB->prefix("tad_web_homework_content") . "
            (`HomeworkID`,`HomeworkCol` , `Content` , `WebID` )
            values('{$HomeworkID}','bring' , '{$_POST['bring']}' , '{$_POST['WebID']}')";
            $xoopsDB->query($sql) or web_error($sql);
        }

        if (!empty($teacher_say)) {
            $sql = "insert into " . $xoopsDB->prefix("tad_web_homework_content") . "
            (`HomeworkID`,`HomeworkCol` , `Content` , `WebID` )
            values('{$HomeworkID}','teacher_say' , '{$_POST['teacher_say']}' , '{$_POST['WebID']}')";
            $xoopsDB->query($sql) or web_error($sql);
        }

        if (!empty($other)) {
            $sql = "insert into " . $xoopsDB->prefix("tad_web_homework_content") . "
            (`HomeworkID`,`HomeworkCol` , `Content` , `WebID` )
            values('{$HomeworkID}','other' , '{$_POST['other']}' , '{$_POST['WebID']}')";
            $xoopsDB->query($sql) or web_error($sql);
        }

        $TadUpFiles->set_col("HomeworkID", $HomeworkID);
        $TadUpFiles->upload_file('upfile', 640, null, null, null, true);

        check_quota($this->WebID);
        return $HomeworkID;
    }

    //更新tad_web_homework某一筆資料
    public function update($HomeworkID = "")
    {
        global $xoopsDB, $TadUpFiles;

        $myts                     = MyTextSanitizer::getInstance();
        $_POST['HomeworkTitle']   = $myts->addSlashes($_POST['HomeworkTitle']);
        $_POST['HomeworkContent'] = $myts->addSlashes($_POST['HomeworkContent']);
        $_POST['CateID']          = (int)$_POST['CateID'];
        $_POST['WebID']           = (int)$_POST['WebID'];
        $HomeworkDate             = date("Y-m-d H:i:s");

        $_POST['today_homework'] = $myts->addSlashes($_POST['today_homework']);
        $today_homework          = $this->remove_html($_POST['today_homework']);
        $_POST['bring']          = $myts->addSlashes($_POST['bring']);
        $bring                   = $this->remove_html($_POST['bring']);
        $_POST['teacher_say']    = $myts->addSlashes($_POST['teacher_say']);
        $teacher_say             = $this->remove_html($_POST['teacher_say']);
        $_POST['other']          = $myts->addSlashes($_POST['other']);
        $other                   = $this->remove_html($_POST['other']);
        // die($bring);
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
            // $HomeworkPostDate = $_POST['toCal'] . " 00:00:00";
            $HomeworkPostDate = $HomeworkDate;
        }

        $CateID = $this->web_cate->save_tad_web_cate($_POST['CateID'], $_POST['newCateName']);

        if (!is_assistant($CateID, 'HomeworkID', $HomeworkID)) {
            $anduid = onlyMine();
        }

        $sql = "update " . $xoopsDB->prefix("tad_web_homework") . " set
         `CateID` = '{$CateID}' ,
         `HomeworkTitle` = '{$_POST['HomeworkTitle']}' ,
         `HomeworkContent` = '{$_POST['HomeworkContent']}' ,
         `HomeworkDate` = '{$HomeworkDate}' ,
         `toCal` = '{$_POST['toCal']}' ,
         `HomeworkPostDate` = '{$HomeworkPostDate}'
        where HomeworkID='$HomeworkID' $anduid";
        $xoopsDB->queryF($sql) or web_error($sql);

        if (!empty($today_homework)) {
            $sql = "replace into " . $xoopsDB->prefix("tad_web_homework_content") . "
            (`HomeworkID`,`HomeworkCol` , `Content` , `WebID` )
            values('{$HomeworkID}','today_homework' , '{$_POST['today_homework']}' , '{$_POST['WebID']}')";
            $xoopsDB->query($sql) or web_error($sql);
        } else {
            $sql = "delete from " . $xoopsDB->prefix("tad_web_homework_content") . "
            where `HomeworkID`='{$HomeworkID}' and `HomeworkCol`='today_homework' and `WebID`='{$_POST['WebID']}'";
            $xoopsDB->queryF($sql) or web_error($sql);
        }

        if (!empty($bring)) {
            $sql = "replace into " . $xoopsDB->prefix("tad_web_homework_content") . "
            (`HomeworkID`,`HomeworkCol` , `Content` , `WebID` )
            values('{$HomeworkID}','bring' , '{$_POST['bring']}' , '{$_POST['WebID']}')";
            $xoopsDB->query($sql) or web_error($sql);
        } else {
            $sql = "delete from " . $xoopsDB->prefix("tad_web_homework_content") . "
            where `HomeworkID`='{$HomeworkID}' and `HomeworkCol`='bring' and `WebID`='{$_POST['WebID']}'";
            $xoopsDB->queryF($sql) or web_error($sql);
        }

        if (!empty($teacher_say)) {
            $sql = "replace into " . $xoopsDB->prefix("tad_web_homework_content") . "
            (`HomeworkID`,`HomeworkCol` , `Content` , `WebID` )
            values('{$HomeworkID}','teacher_say' , '{$_POST['teacher_say']}' , '{$_POST['WebID']}')";
            $xoopsDB->query($sql) or web_error($sql);
        } else {
            $sql = "delete from " . $xoopsDB->prefix("tad_web_homework_content") . "
            where `HomeworkID`='{$HomeworkID}' and `HomeworkCol`='teacher_say' and `WebID`='{$_POST['WebID']}'";
            $xoopsDB->queryF($sql) or web_error($sql);
        }

        if (!empty($other)) {
            $sql = "replace into " . $xoopsDB->prefix("tad_web_homework_content") . "
            (`HomeworkID`,`HomeworkCol` , `Content` , `WebID` )
            values('{$HomeworkID}','other' , '{$_POST['other']}' , '{$_POST['WebID']}')";
            $xoopsDB->query($sql) or web_error($sql);
        } else {
            $sql = "delete from " . $xoopsDB->prefix("tad_web_homework_content") . "
            where `HomeworkID`='{$HomeworkID}' and `HomeworkCol`='other' and `WebID`='{$_POST['WebID']}'";
            $xoopsDB->queryF($sql) or web_error($sql);
        }

        $TadUpFiles->set_col("HomeworkID", $HomeworkID);
        $TadUpFiles->upload_file('upfile', 640, null, null, null, true);

        check_quota($this->WebID);
        return $HomeworkID;
    }

    //刪除tad_web_homework某筆資料資料
    public function delete($HomeworkID = "")
    {
        global $xoopsDB, $TadUpFiles;

        $sql          = "select CateID from " . $xoopsDB->prefix("tad_web_homework") . " where HomeworkID='$HomeworkID'";
        $result       = $xoopsDB->query($sql) or web_error($sql);
        list($CateID) = $xoopsDB->fetchRow($result);
        if (!is_assistant($CateID, 'HomeworkID', $HomeworkID)) {
            $anduid = onlyMine();
        }
        $sql = "delete from " . $xoopsDB->prefix("tad_web_homework_content") . " where HomeworkID='$HomeworkID'";
        $xoopsDB->queryF($sql) or web_error($sql);

        $sql = "delete from " . $xoopsDB->prefix("tad_web_homework") . " where HomeworkID='$HomeworkID' $anduid";
        $xoopsDB->queryF($sql) or web_error($sql);

        $TadUpFiles->set_col("HomeworkID", $HomeworkID);
        $TadUpFiles->del_files();
        check_quota($this->WebID);
    }

    //刪除所有資料
    public function delete_all()
    {
        global $xoopsDB, $TadUpFiles;
        $allCateID = array();
        $sql       = "select HomeworkID,CateID from " . $xoopsDB->prefix("tad_web_homework") . " where WebID='{$this->WebID}'";
        $result    = $xoopsDB->queryF($sql) or web_error($sql);
        while (list($HomeworkID, $CateID) = $xoopsDB->fetchRow($result)) {
            $this->delete($HomeworkID);
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
        $sql         = "select count(*) from " . $xoopsDB->prefix("tad_web_homework") . " where WebID='{$this->WebID}'";
        $result      = $xoopsDB->query($sql) or web_error($sql);
        list($count) = $xoopsDB->fetchRow($result);
        return $count;
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

        $myts   = MyTextSanitizer::getInstance();
        $sql    = "select * from " . $xoopsDB->prefix("tad_web_homework") . " where HomeworkID='$HomeworkID'";
        $result = $xoopsDB->query($sql) or web_error($sql);
        $data   = $xoopsDB->fetchArray($result);

        //找出聯絡簿內容
        $sql     = "select `HomeworkCol`, `Content` from " . $xoopsDB->prefix("tad_web_homework_content") . " where HomeworkID='{$data['HomeworkID']}'";
        $result  = $xoopsDB->query($sql) or web_error($sql);
        $ColsNum = 0;
        while (list($HomeworkCol, $Content) = $xoopsDB->fetchRow($result)) {
            $Content            = $myts->displayTarea($Content, 1, 0, 0, 1, 0);
            $data[$HomeworkCol] = $Content;
            if ($HomeworkCol != 'other') {
                $ColsNum++;
            }
        }
        $ColWidth         = 12 / $ColsNum;
        $data['ColsNum']  = $ColsNum;
        $data['ColWidth'] = $ColWidth;
        return $data;
    }

    public function remove_html($str = '')
    {
        $str = strip_tags($str);
        $str = preg_replace('/(?![ ])\s+/', '', trim($str));
        $str = str_replace('&nbsp;', '', $str);
        return $str;
    }
}
