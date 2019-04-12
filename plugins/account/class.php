<?php

class tad_web_account
{
    public $WebID = 0;
    public $web_cate;
    public $setup;

    public function __construct($WebID)
    {
        $this->WebID    = $WebID;
        $this->web_cate = new web_cate($WebID, "account", "tad_web_account");
        // $this->power    = new power($WebID);
        // $this->tags     = new tags($WebID);
        $this->setup = get_plugin_setup_values($WebID, "account");
    }

    //列出帳目
    public function list_all($CateID = "", $limit = null, $mode = "assign", $tag = '')
    {
        global $xoopsDB, $xoopsTpl, $TadUpFiles, $MyWebs, $isAdmin, $isMyWeb;

        if (empty($this->WebID)) {
            return;
        }

        // if (!$isAdmin and !$isMyWeb and !$_SESSION['LoginMemID'] and !$_SESSION['LoginParentID']) {
        //     redirect_header("index.php?WebID={$this->WebID}", 3, _MD_TCW_NOT_OWNER);
        // }

        $andWebID = (empty($this->WebID)) ? "" : "and a.WebID='{$this->WebID}'";
        if (empty($CateID)) {
            $CateID = $this->get_last_account_book();
        }

        $andCateID = "";
        if ($mode == "assign") {
            //取得tad_web_cate所有資料陣列
            $this->web_cate->set_button_value(_MD_TCW_ACCOUNT_BOOK_TOOL);
            $this->web_cate->set_default_option_text(_MD_TCW_ACCOUNT_SELECT_BOOK);
            $this->web_cate->set_col_md(0, 6);
            $cate_menu = $this->web_cate->cate_menu($CateID, 'page', false, true, false, false);
            $xoopsTpl->assign('cate_menu', $cate_menu);

            if (!empty($CateID) and is_numeric($CateID)) {
                //取得單一分類資料
                $cate = $this->web_cate->get_tad_web_cate($CateID);
                if ($CateID and $cate['CateEnable'] != '1') {
                    return;
                }
                $xoopsTpl->assign('cate', $cate);
                $andCateID = "and a.`CateID`='$CateID'";
                $xoopsTpl->assign('AccountDefCateID', $CateID);
            }
        }

        if (_IS_EZCLASS and !empty($_GET['county'])) {
            //https://class.tn.edu.tw/modules/tad_web/index.php?county=臺南市&city=永康區&SchoolName=XX國小
            include_once $GLOBALS['xoops']->path('/modules/system/include/functions.php');
            $county        = system_CleanVars($_REQUEST, 'county', '', 'string');
            $city          = system_CleanVars($_REQUEST, 'city', '', 'string');
            $SchoolName    = system_CleanVars($_REQUEST, 'SchoolName', '', 'string');
            $andCounty     = !empty($county) ? "and c.county='{$county}'" : "";
            $andCity       = !empty($city) ? "and c.city='{$city}'" : "";
            $andSchoolName = !empty($SchoolName) ? "and c.SchoolName='{$SchoolName}'" : "";

            $sql = "select a.* from " . $xoopsDB->prefix("tad_web_account") . " as a
            left join " . $xoopsDB->prefix("tad_web") . " as b on a.WebID=b.WebID
            left join " . $xoopsDB->prefix("apply") . " as c on b.WebOwnerUid=c.uid
            left join " . $xoopsDB->prefix("tad_web_cate") . " as d on a.CateID=d.CateID
            where b.`WebEnable`='1' and (d.CateEnable='1' or a.CateID='0') $andCounty $andCity $andSchoolName
            order by a.AccountDate ,a.AccountID";
        } else {
            $sql = "select a.* from " . $xoopsDB->prefix("tad_web_account") . " as a
            left join " . $xoopsDB->prefix("tad_web") . " as b on a.WebID=b.WebID
            left join " . $xoopsDB->prefix("tad_web_cate") . " as c on a.CateID=c.CateID
            where b.`WebEnable`='1' and (c.CateEnable='1' or a.CateID='0') $andWebID $andCateID
            order by a.AccountDate ,a.AccountID";
        }
        $to_limit = empty($limit) ? 20 : $limit;

        //getPageBar($原sql語法, 每頁顯示幾筆資料, 最多顯示幾個頁數選項);
        // $PageBar = getPageBar($sql, $to_limit, 10);
        // $bar     = $PageBar['bar'];
        // $sql     = $PageBar['sql'];
        // $total   = $PageBar['total'];

        $result = $xoopsDB->query($sql) or web_error($sql, __FILE__, __LINE__);

        $main_data = [];

        $i = 0;

        $Webs = getAllWebInfo();

        $cate         = $this->web_cate->get_tad_web_cate_arr();
        $AccountTotal = 0;
        while ($all = $xoopsDB->fetchArray($result)) {
            //以下會產生這些變數： $AccountID , $AccountTitle , $AccountDesc , $AccountDate , $AccountIncome , $AccountOutgoings , $uid , $WebID , $AccountCount
            foreach ($all as $k => $v) {
                $$k = $v;
            }
            //檢查權限
            // $power = $this->power->check_power("read", "AccountID", $AccountID);
            // if (!$power) {
            //     continue;
            // }

            $main_data[$i]                = $all;
            $main_data[$i]['id'] = $AccountID;
            $main_data[$i]['id_name'] = 'AccountID';
            $main_data[$i]['title'] = $AccountTitle;
            // $main_data[$i]['isAssistant'] = is_assistant($CateID, 'AccountID', $AccountID);
            $main_data[$i]['isCanEdit'] = isCanEdit($this->WebID, 'account', $CateID, 'AccountID', $AccountID);

            $this->web_cate->set_WebID($WebID);

            $main_data[$i]['cate']     = isset($cate[$CateID]) ? $cate[$CateID] : '';
            $main_data[$i]['WebTitle'] = "<a href='index.php?WebID={$WebID}'>{$Webs[$WebID]}</a>";
            // $main_data[$i]['isMyWeb']  = in_array($WebID, $MyWebs) ? 1 : 0;
            $main_data[$i]['isMyWeb'] = $isMyWeb;
            $main_data[$i]['Money']    = !empty($AccountOutgoings) ? "<span class='text-danger'>-{$AccountOutgoings}</span>" : "<span class='text-primary'>$AccountIncome</span>";

            $subdir = isset($WebID) ? "/{$WebID}" : "";
            $TadUpFiles->set_dir('subdir', $subdir);
            $TadUpFiles->set_col("AccountID", $AccountID);
            $AccountPic                  = $TadUpFiles->get_pic_file('thumb');
            $main_data[$i]['AccountPic'] = $AccountPic;
            if (!empty($AccountIncome)) {
                $AccountTotal += $AccountIncome;
            } else {
                $AccountTotal -= $AccountOutgoings;
            }
            $i++;
        }

        if ($AccountTotal > 0) {
            $AccountTotal = "<span class='text-primary' style='font-size:2em;'>{$AccountTotal}</span>";
        } else {
            $AccountTotal = "<span class='text-danger' style='font-size:2em;'>{$AccountTotal}</span>";
        }

        //可愛刪除
        if (!file_exists(XOOPS_ROOT_PATH . "/modules/tadtools/sweet_alert.php")) {
            redirect_header("index.php", 3, _MA_NEED_TADTOOLS);
        }
        include_once XOOPS_ROOT_PATH . "/modules/tadtools/sweet_alert.php";
        $sweet_alert = new sweet_alert();
        $sweet_alert->render("delete_account_func", "account.php?op=delete&WebID={$this->WebID}&AccountID=", 'AccountID');

        if ($mode == "return") {
            $data['main_data']    = $main_data;
            $data['AccountTotal'] = $AccountTotal;
<<<<<<< HEAD
            $data['total'] = $total;
            $data['isCanEdit'] = isCanEdit($this->WebID, 'account', $CateID, 'AccountID', $AccountID);
=======
            $data['total']        = $total;
>>>>>>> 826dbd105d48639c01fd80ed38edf4d75ec4d744
            return $data;
        } else {
            $xoopsTpl->assign('bar', $bar);
            $xoopsTpl->assign('account_data', $main_data);
            $xoopsTpl->assign('AccountTotal', $AccountTotal);
            $xoopsTpl->assign('account', get_db_plugin($this->WebID, 'account'));
            $xoopsTpl->assign('isCanEdit', isCanEdit($this->WebID, 'account', $CateID, 'AccountID', $AccountID));
            return $total;
        }
    }

    //以流水號秀出某筆tad_web_account資料內容
    public function show_one($AccountID = "")
    {
        global $xoopsDB, $xoopsTpl, $TadUpFiles, $isMyWeb, $xoopsUser, $isAdmin, $isMyWeb;

        // if (!$isAdmin and !$isMyWeb and !$_SESSION['LoginMemID'] and !$_SESSION['LoginParentID']) {
        //     redirect_header("index.php?WebID={$this->WebID}", 3, _MD_TCW_NOT_OWNER);
        // }
        if (empty($AccountID)) {
            return;
        }

        //檢查權限
        // $power = $this->power->check_power("read", "AccountID", $AccountID);
        // if (!$power) {
        //     redirect_header("index.php?WebID={$this->WebID}", 3, _MD_TCW_NOW_READ_POWER);
        // }

        $AccountID = (int)$AccountID;
        $this->add_counter($AccountID);

        $sql = "select * from " . $xoopsDB->prefix("tad_web_account") . " where AccountID='{$AccountID}'";
        $result = $xoopsDB->query($sql) or web_error($sql, __FILE__, __LINE__);
        $all = $xoopsDB->fetchArray($result);

        //以下會產生這些變數： $AccountID , $AccountTitle , $AccountDesc , $AccountDate , $AccountIncome ,$AccountOutgoings , $uid , $WebID , $AccountCount
        foreach ($all as $k => $v) {
            $$k = $v;
        }

        if (empty($uid)) {
            redirect_header('index.php', 3, _MD_TCW_DATA_NOT_EXIST);
        }
        // $subdir = isset($this->WebID) ? "/{$this->WebID}" : "";
        // $TadUpFiles->set_dir('subdir', $subdir);
        $TadUpFiles->set_col("AccountID", $AccountID);
        $pics = $TadUpFiles->show_files('upfile'); //是否縮圖,顯示模式 filename、small,顯示描述,顯示下載次數

        // $TadUpFiles->set_col("AccountID", $AccountID, 1);
        // $bg_pic = $TadUpFiles->get_file_for_smarty();
        //die(var_export($bg_pic));
        // $new_name = XOOPS_ROOT_PATH . "/uploads/tad_web/{$this->WebID}/blur_pic_{$AccountID}.jpg";
        // if (!file_exists($new_name)) {
        //     $this->mk_blur_pic($bg_pic[0]['path'], $new_name);
        // }

        // $xoopsTpl->assign('bg_pic', XOOPS_URL . "/uploads/tad_web/{$this->WebID}/blur_pic_{$AccountID}.jpg");

        $uid_name = XoopsUser::getUnameFromId($uid, 1);
        if (empty($uid_name)) {
            $uid_name = XoopsUser::getUnameFromId($uid, 0);
        }

        $assistant = is_assistant($CateID, 'AccountID', $AccountID);
        $isAssistant = !empty($assistant) ? true : false;
        $uid_name = $isAssistant ? "{$uid_name} <a href='#' title='由{$assistant['MemName']}代理發布'><i class='fa fa-male'></i></a>" : $uid_name;
        $xoopsTpl->assign("isAssistant", $isAssistant);
        $xoopsTpl->assign("isCanEdit", isCanEdit($this->WebID, 'account', $CateID, 'AccountID', $AccountID));

        $xoopsTpl->assign('AccountTitle', $AccountTitle);
        $xoopsTpl->assign('AccountDate', $AccountDate);
        $xoopsTpl->assign('AccountIncome', $AccountIncome);
        $xoopsTpl->assign('AccountOutgoings', $AccountOutgoings);
        $xoopsTpl->assign('AccountDesc', nl2br($AccountDesc));
        $xoopsTpl->assign('uid_name', $uid_name);
        $xoopsTpl->assign('AccountCount', $AccountCount);
        $xoopsTpl->assign('pics', $pics);
        $xoopsTpl->assign('AccountID', $AccountID);
        $xoopsTpl->assign('AccountInfo', sprintf(_MD_TCW_INFO, $uid_name, $AccountDate, $AccountCount));

        $xoopsTpl->assign('xoops_pagetitle', $AccountTitle);
        $xoopsTpl->assign('fb_description', $AccountDate . xoops_substr(strip_tags($AccountDesc), 0, 300));

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
        $sweet_alert->render("delete_account_func", "account.php?op=delete&WebID={$this->WebID}&AccountID=", 'AccountID');
        $xoopsTpl->assign("fb_comments", fb_comments($this->setup['use_fb_comments']));

        // $xoopsTpl->assign("tags", $this->tags->list_tags("AccountID", $AccountID, 'account'));
    }

    //tad_web_account編輯表單
    public function edit_form($AccountID = "")
    {
        global $xoopsDB, $xoopsUser, $MyWebs, $isMyWeb, $xoopsTpl, $TadUpFiles, $WebTitle;

        chk_self_web($this->WebID, $_SESSION['isAssistant']['account']);
        get_quota($this->WebID);

        //抓取預設值
        if (!empty($AccountID)) {
            $DBV = $this->get_one_data($AccountID);
        } else {
            $DBV = array();
        }

        //預設值設定

        //設定「AccountID」欄位預設值
        $AccountID = (!isset($DBV['AccountID'])) ? $AccountID : $DBV['AccountID'];
        $xoopsTpl->assign('AccountID', $AccountID);

        //設定「AccountTitle」欄位預設值
        $AccountTitle = (!isset($DBV['AccountTitle'])) ? "" : $DBV['AccountTitle'];
        $xoopsTpl->assign('AccountTitle', $AccountTitle);

        //設定「AccountDesc」欄位預設值
        $AccountDesc = (!isset($DBV['AccountDesc'])) ? "" : $DBV['AccountDesc'];
        $xoopsTpl->assign('AccountDesc', $AccountDesc);

        //設定「AccountDate」欄位預設值
        $AccountDate = (!isset($DBV['AccountDate'])) ? date("Y-m-d") : $DBV['AccountDate'];
        $xoopsTpl->assign('AccountDate', $AccountDate);

        //設定「AccountIncome」欄位預設值
        $AccountIncome = (!isset($DBV['AccountIncome'])) ? "" : $DBV['AccountIncome'];
        $xoopsTpl->assign('AccountIncome', $AccountIncome);

        //設定「AccountOutgoings」欄位預設值
        $AccountOutgoings = (!isset($DBV['AccountOutgoings'])) ? "" : $DBV['AccountOutgoings'];
        $xoopsTpl->assign('AccountOutgoings', $AccountOutgoings);

        //設定「uid」欄位預設值
        $user_uid = ($xoopsUser) ? $xoopsUser->getVar('uid') : "";
        $uid      = (!isset($DBV['uid'])) ? $user_uid : $DBV['uid'];
        $xoopsTpl->assign('uid', $uid);

        //設定「WebID」欄位預設值
        $WebID = (!isset($DBV['WebID'])) ? $this->WebID : $DBV['WebID'];
        $xoopsTpl->assign('WebID', $WebID);

        //設定「AccountCount」欄位預設值
        $AccountCount = (!isset($DBV['AccountCount'])) ? "" : $DBV['AccountCount'];
        $xoopsTpl->assign('AccountCount', $AccountCount);

        if (!empty($AccountIncome)) {
            $AccountKind  = "AccountIncome";
            $AccountMoney = $AccountIncome;
        } else {
            $AccountKind  = "AccountOutgoings";
            $AccountMoney = $AccountOutgoings;
        }
        $xoopsTpl->assign('AccountKind', $AccountKind);
        $xoopsTpl->assign('AccountMoney', $AccountMoney);

        //設定「CateID」欄位預設值
        $DefCateID = isset($_SESSION['isAssistant']['account']) ? $_SESSION['isAssistant']['account'] : $this->get_last_account_book();
        $CateID    = (!isset($DBV['CateID'])) ? $DefCateID : $DBV['CateID'];
        $this->web_cate->set_label(_MD_TCW_ACCOUNT_BOOK);
        $this->web_cate->set_default_value($WebTitle);
        $this->web_cate->set_default_option_text(_MD_TCW_ACCOUNT_SELECT_BOOK);
        $cate_menu = isset($_SESSION['isAssistant']['account']) ? $this->web_cate->hidden_cate_menu($CateID) : $this->web_cate->cate_menu($CateID);
        $xoopsTpl->assign('cate_menu_form', $cate_menu);

        $op = (empty($AccountID)) ? "insert" : "update";

        if (!file_exists(TADTOOLS_PATH . "/formValidator.php")) {
            redirect_header("index.php", 3, _MD_NEED_TADTOOLS);
        }
        include_once TADTOOLS_PATH . "/formValidator.php";
        $formValidator      = new formValidator("#myForm", true);
        $formValidator_code = $formValidator->render();

        $xoopsTpl->assign('formValidator_code', $formValidator_code);
        $xoopsTpl->assign('next_op', $op);

        // $subdir = isset($this->WebID) ? "/{$this->WebID}" : "";
        // $TadUpFiles->set_dir('subdir', $subdir);
        $TadUpFiles->set_col('AccountID', $AccountID); //若 $show_list_del_file ==true 時一定要有
        $upform = $TadUpFiles->upform(true, 'upfile');
        $xoopsTpl->assign('upform', $upform);

        // $power_form = $this->power->power_menu('read', "AccountID", $AccountID);
        // $xoopsTpl->assign('power_form', $power_form);

        // $tags_form = $this->tags->tags_menu("AccountID", $AccountID);
        $xoopsTpl->assign('tags_form', $tags_form);
    }

    //新增資料到tad_web_account中
    public function insert()
    {
        global $xoopsDB, $xoopsUser, $TadUpFiles, $WebOwnerUid;
        if (isset($_SESSION['isAssistant']['account'])) {
            $uid = $WebOwnerUid;
        } else {
            $uid = ($xoopsUser) ? $xoopsUser->uid() : "";
        }

        $myts         = MyTextSanitizer::getInstance();
        $AccountTitle = $myts->addSlashes($_POST['AccountTitle']);
        $AccountDesc  = $myts->addSlashes($_POST['AccountDesc']);
        $AccountKind  = $myts->addSlashes($_POST['AccountKind']);
        $AccountDate  = $myts->addSlashes($_POST['AccountDate']);
        $newCateName  = $myts->addSlashes($_POST['newCateName']);
        $AccountMoney = (int)$_POST['AccountMoney'];
        $AccountCount = (int)$_POST['AccountCount'];
        $CateID       = (int)$_POST['CateID'];
        $WebID        = (int)$_POST['WebID'];

        if ($AccountKind == "AccountIncome") {
<<<<<<< HEAD
            $AccountIncome = (int) $AccountMoney;
            $AccountOutgoings = 0;
        } else {
            $AccountIncome = 0;
            $AccountOutgoings = (int) $AccountMoney;
=======
            $AccountIncome    = $AccountMoney;
            $AccountOutgoings = "";
        } else {
            $AccountIncome    = "";
            $AccountOutgoings = $AccountMoney;
>>>>>>> 826dbd105d48639c01fd80ed38edf4d75ec4d744
        }

        $CateID = $this->web_cate->save_tad_web_cate($CateID, $newCateName);
        $sql    = "insert into " . $xoopsDB->prefix("tad_web_account") . "
        (`CateID`,`AccountTitle` , `AccountDesc` , `AccountDate` , `AccountIncome` , `AccountOutgoings` , `uid` , `WebID` , `AccountCount`)
        values('{$CateID}' ,'{$AccountTitle}' , '{$AccountDesc}' , '{$AccountDate}' , '{$AccountIncome}' , '{$AccountOutgoings}' , '{$uid}' , '{$WebID}' , '{$AccountCount}')";
        $xoopsDB->query($sql) or web_error($sql, __FILE__, __LINE__);

        //取得最後新增資料的流水編號
        $AccountID = $xoopsDB->getInsertId();
        save_assistant_post($CateID, 'AccountID', $AccountID);

        // $subdir = isset($this->WebID) ? "/{$this->WebID}" : "";
        // $TadUpFiles->set_dir('subdir', $subdir);
        $TadUpFiles->set_col('AccountID', $AccountID);
        $TadUpFiles->upload_file('upfile', 800, null, null, null, true);
        check_quota($this->WebID);

        //儲存權限
        // $this->power->save_power("AccountID", $AccountID, 'read');
        //儲存標籤
        // $this->tags->save_tags("AccountID", $AccountID, $_POST['tag_name'], $_POST['tags']);
        return $AccountID;
    }

    //更新tad_web_account某一筆資料
    public function update($AccountID = "")
    {
        global $xoopsDB, $TadUpFiles;

        $myts         = MyTextSanitizer::getInstance();
        $AccountTitle = $myts->addSlashes($_POST['AccountTitle']);
        $AccountDesc  = $myts->addSlashes($_POST['AccountDesc']);
        $AccountKind  = $myts->addSlashes($_POST['AccountKind']);
        $newCateName  = $myts->addSlashes($_POST['newCateName']);
        $AccountDate  = $myts->addSlashes($_POST['AccountDate']);
        $AccountMoney = (int)$_POST['AccountMoney'];
        $CateID       = (int)$_POST['CateID'];
        $WebID        = (int)$_POST['WebID'];

        if ($AccountKind == "AccountIncome") {
            $AccountIncome    = $AccountMoney;
            $AccountOutgoings = "";
        } else {
            $AccountIncome    = "";
            $AccountOutgoings = $AccountMoney;
        }

        $CateID = $this->web_cate->save_tad_web_cate($CateID, $newCateName);

        if (!is_assistant($CateID, 'AccountID', $AccountID)) {
            $anduid = onlyMine();
        }

        $sql = "update " . $xoopsDB->prefix("tad_web_account") . " set
         `CateID` = '{$CateID}' ,
         `AccountTitle` = '{$AccountTitle}' ,
         `AccountDesc` = '{$AccountDesc}' ,
         `AccountDate` = '{$AccountDate}' ,
         `AccountIncome` = '{$AccountIncome}',
         `AccountOutgoings` = '{$AccountOutgoings}'
        where AccountID='$AccountID' $anduid";
        $xoopsDB->queryF($sql) or web_error($sql, __FILE__, __LINE__);

        // $subdir = isset($this->WebID) ? "/{$this->WebID}" : "";
        // $TadUpFiles->set_dir('subdir', $subdir);
        $TadUpFiles->set_col('AccountID', $AccountID);
        $TadUpFiles->upload_file('upfile', 800, null, null, null, true);
        check_quota($this->WebID);

        //儲存權限
        // $read = $myts->addSlashes($_POST['read']);
        // $this->power->save_power("AccountID", $AccountID, 'read', $read);
        //儲存標籤
        //$this->tags->save_tags("AccountID", $AccountID, $_POST['tag_name'], $_POST['tags']);
        return $AccountID;
    }

    //刪除tad_web_account某筆資料資料
    public function delete($AccountID = "")
    {
        global $xoopsDB, $TadUpFiles;
        $sql = "select CateID from " . $xoopsDB->prefix("tad_web_account") . " where AccountID='$AccountID'";
        $result = $xoopsDB->query($sql) or web_error($sql, __FILE__, __LINE__);
        list($CateID) = $xoopsDB->fetchRow($result);
        if (!is_assistant($CateID, 'AccountID', $AccountID)) {
            $anduid = onlyMine();
        }
        $sql = "delete from " . $xoopsDB->prefix("tad_web_account") . " where AccountID='$AccountID' $anduid";
        $xoopsDB->queryF($sql) or web_error($sql, __FILE__, __LINE__);

        // $subdir = isset($this->WebID) ? "/{$this->WebID}" : "";
        // $TadUpFiles->set_dir('subdir', $subdir);
        $TadUpFiles->set_col('AccountID', $AccountID);
        $TadUpFiles->del_files();
        check_quota($this->WebID);

        // $this->power->delete_power("AccountID", $AccountID, 'read');
        //刪除標籤
        // $this->tags->delete_tags("AccountID", $AccountID);
    }

    //刪除所有資料
    public function delete_all()
    {
        global $xoopsDB, $TadUpFiles;
        $allCateID = array();
<<<<<<< HEAD
        $sql = "select AccountID,CateID from " . $xoopsDB->prefix("tad_web_account") . " where WebID='{$this->WebID}'";
        $result = $xoopsDB->queryF($sql) or web_error($sql, __FILE__, __LINE__);
=======
        $sql       = "select AccountID,CateID from " . $xoopsDB->prefix("tad_web_account") . " where WebID='{$this->WebID}'";
        $result = $xoopsDB->queryF($sql) or web_error($sql);
>>>>>>> 826dbd105d48639c01fd80ed38edf4d75ec4d744
        while (list($AccountID, $CateID) = $xoopsDB->fetchRow($result)) {
            $this->delete($AccountID);
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
        $sql = "select count(*) from " . $xoopsDB->prefix("tad_web_account") . " where WebID='{$this->WebID}'";
        $result = $xoopsDB->query($sql) or web_error($sql, __FILE__, __LINE__);
        list($count) = $xoopsDB->fetchRow($result);
        return $count;
    }

    //新增tad_web_account計數器
    public function add_counter($AccountID = '')
    {
        global $xoopsDB;
        $sql = "update " . $xoopsDB->prefix("tad_web_account") . " set `AccountCount`=`AccountCount`+1 where `AccountID`='{$AccountID}'";
        $xoopsDB->queryF($sql) or web_error($sql, __FILE__, __LINE__);
    }

    //以流水號取得某筆tad_web_account資料
    public function get_one_data($AccountID = "")
    {
        global $xoopsDB;
        if (empty($AccountID)) {
            return;
        }

        $sql = "select * from " . $xoopsDB->prefix("tad_web_account") . " where AccountID='$AccountID'";
        $result = $xoopsDB->query($sql) or web_error($sql, __FILE__, __LINE__);
        $data = $xoopsDB->fetchArray($result);
        return $data;
    }

    //匯出資料
    public function export_data($start_date = "", $end_date = "", $CateID = "")
    {
        global $xoopsDB, $xoopsTpl, $TadUpFiles, $MyWebs;
        $andCateID = empty($CateID) ? "" : "and `CateID`='$CateID'";
        $andStart  = empty($start_date) ? "" : "and AccountDate >= '{$start_date}'";
        $andEnd    = empty($end_date) ? "" : "and AccountDate <= '{$end_date}'";

        $sql = "select AccountID,AccountTitle,AccountDate,CateID from " . $xoopsDB->prefix("tad_web_account") . " where WebID='{$this->WebID}' {$andStart} {$andEnd} {$andCateID} order by AccountDate";
        $result = $xoopsDB->query($sql) or web_error($sql, __FILE__, __LINE__);

        $i         = 0;
        $main_data = array();
        while (list($ID, $title, $date, $CateID) = $xoopsDB->fetchRow($result)) {
            $main_data[$i]['ID']     = $ID;
            $main_data[$i]['CateID'] = $CateID;
            $main_data[$i]['title']  = $title;
            $main_data[$i]['date']   = $date;

            $i++;
        }
        return $main_data;
    }

    //取得最近更新的帳簿
    public function get_last_account_book()
    {
        global $xoopsDB, $xoopsTpl, $TadUpFiles, $MyWebs;

        $sql = "select CateID from " . $xoopsDB->prefix("tad_web_account") . " where WebID='{$this->WebID}' order by AccountDate desc limit 0,1";
        $result = $xoopsDB->query($sql) or web_error($sql, __FILE__, __LINE__);
        list($CateID) = $xoopsDB->fetchRow($result);
        return $CateID;
    }
}
