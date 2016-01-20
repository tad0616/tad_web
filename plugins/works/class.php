<?php
class tad_web_works
{

    public $WebID = 0;
    public $web_cate;
    public $setup;

    public function tad_web_works($WebID)
    {
        $this->WebID    = $WebID;
        $this->web_cate = new web_cate($WebID, "works", "tad_web_works");
        $this->setup    = get_plugin_setup_values($WebID, "works");
    }

    //作品分享
    public function list_all($CateID = "", $limit = null, $mode = "assign", $kind = '', $order = '', $pic = false)
    {
        global $xoopsDB, $xoopsTpl, $MyWebs, $isMyWeb, $TadUpFiles;
        $andWebID = (empty($this->WebID)) ? "" : "and a.WebID='{$this->WebID}'";

        $andCateID = "";
        if ($mode == "assign") {
            //取得tad_web_cate所有資料陣列
            $cate_menu = $this->web_cate->cate_menu($CateID, 'page', false, true, false, true);
            $xoopsTpl->assign('cate_menu', $cate_menu);
            if (!empty($CateID)) {
                //取得單一分類資料
                $cate = $this->web_cate->get_tad_web_cate($CateID);
                $xoopsTpl->assign('cate', $cate);
                $andCateID = "and a.`CateID`='$CateID'";
                $xoopsTpl->assign('WorksDefCateID', $CateID);
            }
        }

        $now  = date("Y-m-d H:i:s");
        $time = time();
        //列出學生可上傳的
        if ($kind == "list_mem_upload") {
            $andWorksKind = "and a.WorksKind !='' and a.WorksDate >= '{$now}'";
        } else {
            $andWorksKind = $isMyWeb ? '' : "and ((a.WorksKind ='mem_after_end' and a.WorksDate < '$now') or a.WorksKind!='mem_after_end')";
        }

        if ($order == '') {
            $order = 'order by a.WorksDate desc';
        }

        if (_IS_EZCLASS and !empty($_GET['county'])) {
            //http://class.tn.edu.tw/modules/tad_web/index.php?county=臺南市&city=永康區&SchoolName=XX國小
            include_once $GLOBALS['xoops']->path('/modules/system/include/functions.php');
            $county        = system_CleanVars($_REQUEST, 'county', '', 'string');
            $city          = system_CleanVars($_REQUEST, 'city', '', 'string');
            $SchoolName    = system_CleanVars($_REQUEST, 'SchoolName', '', 'string');
            $andCounty     = !empty($county) ? "and c.county='{$county}'" : "";
            $andCity       = !empty($city) ? "and c.city='{$city}'" : "";
            $andSchoolName = !empty($SchoolName) ? "and c.SchoolName='{$SchoolName}'" : "";

            $sql = "select a.* from " . $xoopsDB->prefix("tad_web_works") . " as a left join " . $xoopsDB->prefix("tad_web") . " as b on a.WebID=b.WebID left join " . $xoopsDB->prefix("apply") . " as c on b.WebOwnerUid=c.uid where b.`WebEnable`='1' $andCounty $andCity $andSchoolName $order";
        } else {
            $sql = "select a.* from " . $xoopsDB->prefix("tad_web_works") . " as a left join " . $xoopsDB->prefix("tad_web") . " as b on a.WebID=b.WebID where b.`WebEnable`='1' $andWebID $andCateID $andWorksKind $order";
            // die($sql);
        }

        $to_limit = empty($limit) ? 20 : $limit;

        //getPageBar($原sql語法, 每頁顯示幾筆資料, 最多顯示幾個頁數選項);
        $PageBar = getPageBar($sql, $to_limit, 10);
        $bar     = $PageBar['bar'];
        $sql     = $PageBar['sql'];
        $total   = $PageBar['total'];

        $result = $xoopsDB->query($sql) or web_error($sql);

        $main_data = "";

        $i = 0;

        $Webs = getAllWebInfo();

        $cate = $this->web_cate->get_tad_web_cate_arr();

        while ($all = $xoopsDB->fetchArray($result)) {
            //以下會產生這些變數： $WorksID , $WorksName , $WorksDesc , $WorksDate , $WorksPlace , $uid , $WebID , $WorksCount
            foreach ($all as $k => $v) {
                $$k = $v;
            }
            $main_data[$i] = $all;
            $this->web_cate->set_WebID($WebID);

            if ($pic !== false) {
                if (empty($pic)) {
                    $pic = 12;
                }

                $TadUpFiles->set_col("WorksID", $WorksID);
                $main_data[$i]['pics'] = $TadUpFiles->show_files('upfile', true, null, true, null, $pic);
            } else {
                $main_data[$i]['pics'] = '';
            }

            $main_data[$i]['cate']      = isset($cate[$CateID]) ? $cate[$CateID] : '';
            $main_data[$i]['WebTitle']  = "<a href='index.php?WebID=$WebID'>{$Webs[$WebID]}</a>";
            $main_data[$i]['isMyWeb']   = in_array($WebID, $MyWebs) ? 1 : 0;
            $main_data[$i]['WorksDate'] = $WorksDate;
            if (strtotime($WorksDate) > $time and $WorksKind == 'mem_after_end') {
                $main_data[$i]['hide'] = sprintf(_MD_TCW_WORKS_DISPLAY_DATE, $WorksDate);
            } else {
                $main_data[$i]['hide'] = false;
            }
            $i++;
        }

        if (!file_exists(XOOPS_ROOT_PATH . "/modules/tadtools/sweet_alert.php")) {
            redirect_header("index.php", 3, _MA_NEED_TADTOOLS);
        }
        include_once XOOPS_ROOT_PATH . "/modules/tadtools/sweet_alert.php";
        $sweet_alert = new sweet_alert();
        $sweet_alert->render("delete_works_func", "works.php?op=delete&WebID={$this->WebID}&WorksID=", 'WorksID');

        if ($mode == "return") {
            $data['main_data'] = $main_data;
            $data['total']     = $total;
            return $data;
        } else {
            $xoopsTpl->assign('works_data', $main_data);
            $xoopsTpl->assign('bar', $bar);
            $xoopsTpl->assign('works', get_db_plugin($this->WebID, 'works'));
            return $total;
        }
    }

    //以流水號秀出某筆tad_web_works資料內容
    public function show_one($WorksID = "")
    {
        global $xoopsDB, $xoopsTpl, $TadUpFiles, $isMyWeb;
        if (empty($WorksID)) {
            return;
        }

        $WorksID = intval($WorksID);
        $this->add_counter($WorksID);

        $sql    = "select * from " . $xoopsDB->prefix("tad_web_works") . " where WorksID='{$WorksID}'";
        $result = $xoopsDB->query($sql) or web_error($sql);
        $all    = $xoopsDB->fetchArray($result);

        //以下會產生這些變數： $WorksID , $WorkName , $WorkDesc , $WorksDate , $uid , $WebID , $WorksCount
        foreach ($all as $k => $v) {
            $$k = $v;
            $xoopsTpl->assign($k, $v);
        }

        $deadline             = strtotime($WorksDate);
        $time                 = time();
        $show_score_form      = ($isMyWeb and $WorksKind != '') ? true : false;
        $is_mem_upload        = ($_SESSION['LoginWebID'] == $WebID and !empty($_SESSION['LoginWebID']) and $WorksKind != '') ? true : false;
        $show_mem_upload_form = ($is_mem_upload and $deadline >= $time) ? true : false;

        if ($WorksKind == 'mem_after_end' and $deadline >= $time and !$isMyWeb and ($_SESSION['LoginWebID'] != $WebID or empty($_SESSION['LoginWebID']))) {
            redirect_header("{$_SERVER['PHP_SELF']}?WebID=$WebID", 3, sprintf(_MD_TCW_WORKS_DISPLAY_DATE, $WorksDate));
        }

        if (empty($WorksID)) {
            redirect_header("{$_SERVER['PHP_SELF']}?WebID=$WebID", 3, _MD_TCW_DATA_NOT_EXIST);
        }

        $TadUpFiles->set_col("WorksID", $WorksID);
        $pics = $TadUpFiles->show_files('upfile', true, null, true); //是否縮圖,顯示模式 filename、small,顯示描述,顯示下載次數

        $uid_name = XoopsUser::getUnameFromId($uid, 1);

        if (strtotime($WorksDate) > $time and $WorksKind == 'mem_after_end') {
            $hide = sprintf(_MD_TCW_WORKS_DISPLAY_DATE, $WorksDate);
        } else {
            $hide = false;
        }
        $xoopsTpl->assign('hide', $hide);
        $WorksDate = str_replace(' 00:00:00', '', $WorksDate);

        $xoopsTpl->assign('WorksDate', $WorksDate);
        $xoopsTpl->assign('WorkDesc', nl2br($WorkDesc));
        $xoopsTpl->assign('uid_name', $uid_name);
        $xoopsTpl->assign('pics', $pics);
        $xoopsTpl->assign('WorksInfo', sprintf(_MD_TCW_INFO, $uid_name, $WorksDate, $WorksCount));

        $xoopsTpl->assign('xoops_pagetitle', $WorkName);
        $xoopsTpl->assign('fb_description', xoops_substr(strip_tags($WorkDesc), 0, 300));

        //取得單一分類資料
        $cate = $this->web_cate->get_tad_web_cate($CateID);
        $xoopsTpl->assign('cate', $cate);

        if (!file_exists(XOOPS_ROOT_PATH . "/modules/tadtools/sweet_alert.php")) {
            redirect_header("index.php", 3, _MA_NEED_TADTOOLS);
        }
        include_once XOOPS_ROOT_PATH . "/modules/tadtools/sweet_alert.php";
        $sweet_alert      = new sweet_alert();
        $sweet_alert_code = $sweet_alert->render("delete_works_func", "works.php?op=delete&WebID={$this->WebID}&WorksID=", 'WorksID');
        $xoopsTpl->assign("fb_comments", fb_comments($this->setup['use_fb_comments']));

        $xoopsTpl->assign('show_mem_upload_form', $show_mem_upload_form);
        if ($show_mem_upload_form) {
            $TadUpFiles->set_col('WorksID', $WorksID); //若 $show_list_del_file ==true 時一定要有
            $upform = $TadUpFiles->upform(false, 'upfile', null, false);
            $xoopsTpl->assign('upform', $upform);
            $mem_upload_content = $this->get_mem_upload_content($WorksID, $_SESSION['LoginMemID']);
            $xoopsTpl->assign('mem_upload_content', $mem_upload_content);
        }
        $xoopsTpl->assign('show_score_form', $show_score_form);

    }

    //tad_web_works編輯表單
    public function edit_form($WorksID = "")
    {
        global $xoopsDB, $xoopsUser, $MyWebs, $isMyWeb, $xoopsTpl, $TadUpFiles;

        if (!$isMyWeb and $MyWebs) {
            redirect_header($_SERVER['PHP_SELF'] . "?op=WebID={$MyWebs[0]}&op=edit_form", 3, _MD_TCW_AUTO_TO_HOME);
        } elseif (!$xoopsUser or empty($this->WebID) or empty($MyWebs)) {
            redirect_header("{$_SERVER['PHP_SELF']}?WebID=$WebID", 3, _MD_TCW_NOT_OWNER);
        }
        get_quota($this->WebID);

        //抓取預設值
        if (!empty($WorksID)) {
            $DBV = $this->get_one_data($WorksID);
        } else {
            $DBV = array();
        }

        //預設值設定

        //設定「WorksID」欄位預設值
        $WorksID = (!isset($DBV['WorksID'])) ? $WorksID : $DBV['WorksID'];
        $xoopsTpl->assign('WorksID', $WorksID);

        //設定「WorkName」欄位預設值
        $WorkName = (!isset($DBV['WorkName'])) ? "" : $DBV['WorkName'];
        $xoopsTpl->assign('WorkName', $WorkName);

        //設定「WorkDesc」欄位預設值
        $WorkDesc = (!isset($DBV['WorkDesc'])) ? "" : $DBV['WorkDesc'];
        $xoopsTpl->assign('WorkDesc', $WorkDesc);

        //設定「WorksDate」欄位預設值
        $WorksDate = (!isset($DBV['WorksDate'])) ? date("Y-m-d H:i:00") : $DBV['WorksDate'];
        $xoopsTpl->assign('WorksDate', $WorksDate);

        //設定「uid」欄位預設值
        $user_uid = ($xoopsUser) ? $xoopsUser->getVar('uid') : "";
        $uid      = (!isset($DBV['uid'])) ? $user_uid : $DBV['uid'];

        //設定「WebID」欄位預設值
        $WebID = (!isset($DBV['WebID'])) ? $this->WebID : $DBV['WebID'];
        $xoopsTpl->assign('WebID', $WebID);

        //設定「WorksCount」欄位預設值
        $WorksCount = (!isset($DBV['WorksCount'])) ? "" : $DBV['WorksCount'];
        $xoopsTpl->assign('WorksCount', $WorksCount);

        //設定「WorksKind」欄位預設值
        $WorksKind = (!isset($DBV['WorksKind'])) ? "" : $DBV['WorksKind'];
        $xoopsTpl->assign('WorksKind', $WorksKind);

        //設定「WorksEnable」欄位預設值
        $WorksEnable = (!isset($DBV['WorksEnable'])) ? "1" : $DBV['WorksEnable'];
        $xoopsTpl->assign('WorksEnable', $WorksEnable);

        //設定「CateID」欄位預設值
        $CateID    = (!isset($DBV['CateID'])) ? "" : $DBV['CateID'];
        $cate_menu = $this->web_cate->cate_menu($CateID);
        $xoopsTpl->assign('cate_menu_form', $cate_menu);

        $op = (empty($WorksID)) ? "insert" : "update";

        if (!file_exists(TADTOOLS_PATH . "/formValidator.php")) {
            redirect_header("index.php", 3, _MD_NEED_TADTOOLS);
        }
        include_once TADTOOLS_PATH . "/formValidator.php";
        $formValidator      = new formValidator("#myForm", true);
        $formValidator_code = $formValidator->render();

        $xoopsTpl->assign('formValidator_code', $formValidator_code);
        $xoopsTpl->assign('next_op', $op);

        $TadUpFiles->set_col('WorksID', $WorksID); //若 $show_list_del_file ==true 時一定要有
        $upform = $TadUpFiles->upform(true, 'upfile');
        $xoopsTpl->assign('upform', $upform);

    }

    //新增資料到tad_web_works中
    public function insert()
    {
        global $xoopsDB, $xoopsUser, $TadUpFiles;

        //取得使用者編號
        $uid = ($xoopsUser) ? $xoopsUser->getVar('uid') : "";

        $myts                 = &MyTextSanitizer::getInstance();
        $_POST['WorkName']    = $myts->addSlashes($_POST['WorkName']);
        $_POST['WorkDesc']    = $myts->addSlashes($_POST['WorkDesc']);
        $_POST['WorksKind']   = $myts->addSlashes($_POST['WorksKind']);
        $_POST['CateID']      = intval($_POST['CateID']);
        $_POST['WebID']       = intval($_POST['WebID']);
        $_POST['WorksEnable'] = intval($_POST['WorksEnable']);

        $CateID = $this->web_cate->save_tad_web_cate($_POST['CateID'], $_POST['newCateName']);

        $sql = "insert into " . $xoopsDB->prefix("tad_web_works") . "
        (`CateID`,`WorkName` , `WorkDesc` , `WorksDate` ,  `uid` , `WebID` , `WorksCount` , `WorksKind` , `WorksEnable`)
        values('{$CateID}' , '{$_POST['WorkName']}' , '{$_POST['WorkDesc']}' , '{$_POST['WorksDate']}' , '{$uid}' , '{$_POST['WebID']}' , '0', '{$_POST['WorksKind']}', '{$_POST['WorksEnable']}')";
        $xoopsDB->query($sql) or web_error($sql);

        //取得最後新增資料的流水編號
        $WorksID = $xoopsDB->getInsertId();

        $TadUpFiles->set_col('WorksID', $WorksID);
        $TadUpFiles->upload_file('upfile', 800, null, null, null, true);

        check_quota($this->WebID);
        return $WorksID;
    }

    //更新tad_web_works某一筆資料
    public function update($WorksID = "")
    {
        global $xoopsDB, $TadUpFiles;

        $myts                 = &MyTextSanitizer::getInstance();
        $_POST['WorkName']    = $myts->addSlashes($_POST['WorkName']);
        $_POST['WorkDesc']    = $myts->addSlashes($_POST['WorkDesc']);
        $_POST['WorksKind']   = $myts->addSlashes($_POST['WorksKind']);
        $_POST['CateID']      = intval($_POST['CateID']);
        $_POST['WebID']       = intval($_POST['WebID']);
        $_POST['WorksEnable'] = intval($_POST['WorksEnable']);

        $CateID = $this->web_cate->save_tad_web_cate($_POST['CateID'], $_POST['newCateName']);

        $anduid = onlyMine();

        $sql = "update " . $xoopsDB->prefix("tad_web_works") . " set
         `CateID` = '{$CateID}' ,
         `WorkName` = '{$_POST['WorkName']}' ,
         `WorkDesc` = '{$_POST['WorkDesc']}' ,
         `WorksDate` = '{$_POST['WorksDate']}' ,
         `WorksKind` = '{$_POST['WorksKind']}' ,
         `WorksEnable` = '{$_POST['WorksEnable']}'
        where WorksID='$WorksID' $anduid";
        $xoopsDB->queryF($sql) or web_error($sql);

        $TadUpFiles->set_col('WorksID', $WorksID);
        $TadUpFiles->upload_file('upfile', 800, null, null, null, true);

        check_quota($this->WebID);
        return $WorksID;
    }

    //交作業
    public function mem_upload($WorksID = "")
    {
        global $xoopsDB, $TadUpFiles;

        $myts     = &MyTextSanitizer::getInstance();
        $WorkDesc = $myts->addSlashes($_POST['WorkDesc']);

        //讀出原有分數及評語
        $sql = "select WorkScore , WorkJudgment from " . $xoopsDB->prefix("tad_web_works_content") . " where `WorksID`='{$WorksID}' and `MemID`='{$_SESSION['LoginMemID']}' and  `WebID`='{$this->WebID}'";

        $result                         = $xoopsDB->query($sql) or web_error($sql);
        list($WorkScore, $WorkJudgment) = $xoopsDB->fetchRow($result);

        //若已有上傳圖片
        foreach ($_POST['save_description'] as $files_sn => $desc) {
            $all_files_sn[$files_sn] = $files_sn;

            $desc = str_replace("{$_SESSION['LoginMemName']}-", '', $myts->addSlashes($desc));

            $_POST['save_description'][$files_sn] = "{$_SESSION['LoginMemName']}-{$desc}";
        }

        //$TadUpFiles->upload_file($upname,$width,$thumb_width,$files_sn,$desc,$safe_name=false,$hash=false);
        $TadUpFiles->set_col('WorksID', $WorksID);
        $files_sn_arr = $TadUpFiles->upload_file('upfile', 800, null, null, "{$_SESSION['LoginMemName']}-{$WorkDesc}", true, false, 'files_sn');

        foreach ($files_sn_arr as $files_sn) {
            $all_files_sn[$files_sn] = $files_sn;
        }

        $all_files_arr = implode(',', $all_files_sn);
        $UploadDate    = date("Y-m-d H:i:s");

        $sql = "replace into " . $xoopsDB->prefix("tad_web_works_content") . "
        (`WorksID`,`MemID` , `MemName` , `WebID` , `WorkDesc` , `UploadDate` , `WorkScore`, `WorkJudgment` ,`all_files_sn`)
        values('{$WorksID}' , '{$_SESSION['LoginMemID']}' , '{$_SESSION['LoginMemName']}' , '{$this->WebID}' , '{$WorkDesc}' , '{$UploadDate}', '{$WorkScore}', '{$WorkJudgment}', '{$all_files_arr}')";
        $xoopsDB->query($sql) or web_error($sql);

        check_quota($this->WebID);
        return $WorksID;
    }

    //刪除tad_web_works某筆資料資料
    public function delete($WorksID = "")
    {
        global $xoopsDB, $TadUpFiles;
        $anduid = onlyMine();
        $sql    = "delete from " . $xoopsDB->prefix("tad_web_works") . " where WorksID='$WorksID' $anduid";
        $xoopsDB->queryF($sql) or web_error($sql);

        $TadUpFiles->set_col('WorksID', $WorksID);
        $TadUpFiles->del_files();
        check_quota($this->WebID);
    }

    //刪除所有資料
    public function delete_all()
    {
        global $xoopsDB, $TadUpFiles;
        $allCateID = array();
        $sql       = "select WorksID,CateID from " . $xoopsDB->prefix("tad_web_works") . " where WebID='{$this->WebID}'";
        $result    = $xoopsDB->queryF($sql) or web_error($sql);
        while (list($WorksID, $CateID) = $xoopsDB->fetchRow($result)) {
            $this->delete($WorksID);
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
        $sql         = "select count(*) from " . $xoopsDB->prefix("tad_web_works") . " where WebID='{$this->WebID}'";
        $result      = $xoopsDB->query($sql) or web_error($sql);
        list($count) = $xoopsDB->fetchRow($result);
        return $count;
    }

    //新增tad_web_works計數器
    public function add_counter($WorksID = '')
    {
        global $xoopsDB;
        $sql = "update " . $xoopsDB->prefix("tad_web_works") . " set `WorksCount`=`WorksCount`+1 where `WorksID`='{$WorksID}'";
        $xoopsDB->queryF($sql) or web_error($sql);
    }

    //以流水號取得某筆tad_web_works資料
    public function get_one_data($WorksID = "")
    {
        global $xoopsDB;
        if (empty($WorksID)) {
            return;
        }

        $sql    = "select * from " . $xoopsDB->prefix("tad_web_works") . " where WorksID='$WorksID'";
        $result = $xoopsDB->query($sql) or web_error($sql);
        $data   = $xoopsDB->fetchArray($result);
        return $data;
    }

    //7取得某人上傳資料
    public function get_mem_upload_content($WorksID = "", $MemID = "")
    {
        global $xoopsDB, $TadUpFiles;
        if (empty($WorksID)) {
            return;
        }
        $TadUpFiles->set_col('WorksID', $WorksID);

        $andMemID = empty($MemID) ? "" : "and MemID='$MemID'";

        $sql    = "select * from " . $xoopsDB->prefix("tad_web_works_content") . " where WebID='{$this->WebID}' and WorksID='$WorksID' {$andMemID}";
        $result = $xoopsDB->query($sql) or web_error($sql);
        if (empty($MemID)) {
            $i    = 0;
            $data = '';
            while ($all = $xoopsDB->fetchArray($result)) {
                $TadUpFiles->set_files_sn(explode(',', $all['all_files_sn']));
                $all['list_del_file'] = $TadUpFiles->show_files('upfile', true, null, true);
                $data[$i]             = $all;
                $i++;
            }
        } else {
            $data                  = $xoopsDB->fetchArray($result);
            $data['list_del_file'] = $TadUpFiles->list_del_file(true, true, explode(',', $data['all_files_sn']), false, false);
        }

        return $data;
    }

    public function score_form($WorksID = "")
    {
        global $xoopsDB, $xoopsTpl, $TadUpFiles, $isMyWeb;
        $work = $this->get_one_data($WorksID);
        foreach ($work as $k => $v) {
            $$k = $v;
            $xoopsTpl->assign($k, $v);
        }
        $deadline        = strtotime($WorksDate);
        $time            = time();
        $show_score_form = ($isMyWeb and $WorksKind != '') ? true : false;
        $uid_name        = XoopsUser::getUnameFromId($uid, 1);

        if (strtotime($WorksDate) > $time and $WorksKind == 'mem_after_end') {
            $hide = sprintf(_MD_TCW_WORKS_DISPLAY_DATE, $WorksDate);
        } else {
            $hide = false;
        }
        $xoopsTpl->assign('hide', $hide);
        $WorksDate = str_replace(' 00:00:00', '', $WorksDate);

        $xoopsTpl->assign('WorksDate', $WorksDate);
        $xoopsTpl->assign('WorkDesc', nl2br($WorkDesc));
        $xoopsTpl->assign('uid_name', $uid_name);

        $xoopsTpl->assign('WorksInfo', sprintf(_MD_TCW_INFO, $uid_name, $WorksDate, $WorksCount));
        $xoopsTpl->assign('show_score_form', $show_score_form);
        // $xoopsTpl->assign('work', $work);
        if ($show_score_form) {
            $all_upload_content = $this->get_mem_upload_content($WorksID);
            $xoopsTpl->assign('all_upload_content', $all_upload_content);
        }
    }

    public function save_score($WorksID = "", $WorkScoreArr = array(), $WorkJudgmentArr = array())
    {
        global $xoopsDB, $xoopsTpl, $TadUpFiles, $isMyWeb;
        $myts = &MyTextSanitizer::getInstance();
        foreach ($WorkScoreArr as $MemID => $WorkScore) {
            $WorkScore    = $myts->addSlashes($WorkScore);
            $WorkJudgment = $myts->addSlashes($WorkJudgmentArr[$MemID]);
            $sql          = "update " . $xoopsDB->prefix("tad_web_works_content") . " set
             `WorkScore` = '{$WorkScore}' ,
             `WorkJudgment` = '{$WorkJudgment}'
            where WorksID='$WorksID' and `MemID` = '{$MemID}'";
            $xoopsDB->queryF($sql) or web_error($sql);
        }

    }
}
