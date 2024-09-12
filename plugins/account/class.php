<?php
use Xmf\Request;
use XoopsModules\Tadtools\FormValidator;
use XoopsModules\Tadtools\SweetAlert;
use XoopsModules\Tadtools\Utility;
use XoopsModules\Tad_web\Power;
use XoopsModules\Tad_web\WebCate;

// use XoopsModules\Tad_web\Power;
// use XoopsModules\Tad_web\Tags;

class tad_web_account
{
    public $WebID = 0;
    public $WebCate;
    public $setup;
    public $Power;

    public function __construct($WebID)
    {
        $this->WebID = $WebID;
        $this->WebCate = new WebCate($WebID, 'account', 'tad_web_account');
        $this->Power = new Power($WebID);
        // $this->tags     = new Tags($WebID);
        $this->setup = get_plugin_setup_values($WebID, 'account');
    }

    //列出帳目
    public function list_all($CateID = '', $limit = null, $mode = 'assign', $tag = '')
    {
        global $xoopsDB, $xoopsTpl, $TadUpFiles, $MyWebs, $isAdmin, $isMyWeb;

        if (empty($this->WebID)) {
            return;
        }

        $power = $this->Power->check_power("read", "CateID", $CateID, 'account');
        if (!$power) {
            redirect_header("account.php?WebID={$this->WebID}", 3, _MD_TCW_NOW_READ_POWER);
        }

        $andWebID = (empty($this->WebID)) ? '' : "and a.WebID='{$this->WebID}'";
        if (empty($CateID)) {
            $CateID = $this->get_last_account_book();
        }

        $andCateID = '';
        if ('assign' === $mode) {
            //取得tad_web_cate所有資料陣列
            $this->WebCate->set_button_value(_MD_TCW_ACCOUNT_BOOK_TOOL);
            $this->WebCate->set_default_option_text(_MD_TCW_ACCOUNT_SELECT_BOOK);
            $this->WebCate->set_col_md(0, 6);
            $cate_menu = $this->WebCate->cate_menu($CateID, 'page', false, true, false, false);
            $xoopsTpl->assign('cate_menu', $cate_menu);

            if (!empty($CateID) and is_numeric($CateID)) {
                //取得單一分類資料
                $cate = $this->WebCate->get_tad_web_cate($CateID);
                if ($CateID and '1' != $cate['CateEnable']) {
                    return;
                }
                $xoopsTpl->assign('cate', $cate);
                $andCateID = "and a.`CateID`='$CateID'";
                $xoopsTpl->assign('AccountDefCateID', $CateID);
            }
        }

        if (_IS_EZCLASS and !empty($_GET['county'])) {
            //https://class.tn.edu.tw/modules/tad_web/index.php?county=臺南市&city=永康區&SchoolName=XX國小

            $county = Request::getString('county');
            $city = Request::getString('city');
            $SchoolName = Request::getString('SchoolName');

            $andCounty = !empty($county) ? "and c.county='{$county}'" : '';
            $andCity = !empty($city) ? "and c.city='{$city}'" : '';
            $andSchoolName = !empty($SchoolName) ? "and c.SchoolName='{$SchoolName}'" : '';

            $sql = 'select a.* from ' . $xoopsDB->prefix('tad_web_account') . ' as a
            left join ' . $xoopsDB->prefix('tad_web') . ' as b on a.WebID=b.WebID
            left join ' . $xoopsDB->prefix('apply') . ' as c on b.WebOwnerUid=c.uid
            left join ' . $xoopsDB->prefix('tad_web_cate') . " as d on a.CateID=d.CateID
            where b.`WebEnable`='1' and (d.CateEnable='1' or a.CateID='0') $andCounty $andCity $andSchoolName
            order by a.AccountDate ,a.AccountID";
        } else {
            if (empty($this->WebID)) {
                return;
            }

            $sql = 'select a.* from ' . $xoopsDB->prefix('tad_web_account') . ' as a
            left join ' . $xoopsDB->prefix('tad_web') . ' as b on a.WebID=b.WebID
            left join ' . $xoopsDB->prefix('tad_web_cate') . " as c on a.CateID=c.CateID
            where b.`WebEnable`='1' and (c.CateEnable='1' or a.CateID='0') $andWebID $andCateID
            order by a.AccountDate ,a.AccountID";
        }
        $to_limit = empty($limit) ? 20 : $limit;

        //getPageBar($原sql語法, 每頁顯示幾筆資料, 最多顯示幾個頁數選項);
        // $PageBar = Utility::getPageBar($sql, $to_limit, 10);
        // $bar     = $PageBar['bar'];
        // $sql     = $PageBar['sql'];
        // $total   = $PageBar['total'];

        $result = $xoopsDB->query($sql) or Utility::web_error($sql, __FILE__, __LINE__);

        $main_data = [];

        $i = 0;

        $Webs = getAllWebInfo();

        $cate = $this->WebCate->get_tad_web_cate_arr(null, null, 'account');
        $AccountTotal = 0;
        while (false !== ($all = $xoopsDB->fetchArray($result))) {
            //以下會產生這些變數： $AccountID , $AccountTitle , $AccountDesc , $AccountDate , $AccountIncome , $AccountOutgoings , $uid , $WebID , $AccountCount
            foreach ($all as $k => $v) {
                $$k = $v;
            }

            $power = $this->Power->check_power("read", "CateID", $CateID, 'account');
            if (!$power) {
                continue;
            }

            $main_data[$i] = $all;
            $main_data[$i]['id'] = $AccountID;
            $main_data[$i]['id_name'] = 'AccountID';
            $main_data[$i]['title'] = $AccountTitle;
            // $main_data[$i]['isAssistant'] = is_assistant($this->WebID, 'account', $CateID, 'AccountID', $AccountID);
            $main_data[$i]['isCanEdit'] = isCanEdit($this->WebID, 'account', $CateID, 'AccountID', $AccountID);
            if (_IS_EZCLASS) {
                $main_data[$i]['AccountCount'] = redis_do($this->WebID, 'get', 'account', "AccountCount:$AccountID");
            }

            $this->WebCate->set_WebID($WebID);

            $main_data[$i]['cate'] = isset($cate[$CateID]) ? $cate[$CateID] : '';
            $main_data[$i]['WebTitle'] = "<a href='index.php?WebID={$WebID}'>{$Webs[$WebID]}</a>";
            // $main_data[$i]['isMyWeb']  = in_array($WebID, $MyWebs) ? 1 : 0;
            $main_data[$i]['isMyWeb'] = $isMyWeb;
            $main_data[$i]['Money'] = !empty($AccountOutgoings) ? "<span class='text-danger'>-{$AccountOutgoings}</span>" : "<span class='text-primary'>$AccountIncome</span>";

            $subdir = isset($WebID) ? "/{$WebID}" : '';
            $TadUpFiles->set_dir('subdir', $subdir);
            $TadUpFiles->set_col('AccountID', $AccountID);
            $AccountPic = $TadUpFiles->get_pic_file('thumb');
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

        $SweetAlert = new SweetAlert();
        $SweetAlert->render('delete_account_func', "account.php?op=delete&WebID={$this->WebID}&AccountID=", 'AccountID');

        if ('return' === $mode) {
            $data['main_data'] = $main_data;
            $data['AccountTotal'] = $AccountTotal;
            $data['total'] = $total;
            $data['isCanEdit'] = isCanEdit($this->WebID, 'account', $CateID, 'AccountID', $AccountID);
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
    public function show_one($AccountID = '')
    {
        global $xoopsDB, $xoopsTpl, $TadUpFiles, $isMyWeb, $xoopsUser, $isAdmin, $isMyWeb;

        if (empty($AccountID)) {
            redirect_header("{$_SERVER['PHP_SELF']}?WebID={$this->WebID}", 3, _MD_TCW_DATA_NOT_EXIST);
        }

        $AccountID = (int) $AccountID;

        $sql = 'select * from ' . $xoopsDB->prefix('tad_web_account') . " where AccountID='{$AccountID}'";
        $result = $xoopsDB->query($sql) or Utility::web_error($sql, __FILE__, __LINE__);
        $all = $xoopsDB->fetchArray($result);

        //以下會產生這些變數： $AccountID ,$CateID, $AccountTitle , $AccountDesc , $AccountDate , $AccountIncome ,$AccountOutgoings , $uid , $WebID , $AccountCount
        foreach ($all as $k => $v) {
            $$k = $v;
        }

        $power = $this->Power->check_power("read", "CateID", $CateID, 'account');
        if (!$power) {
            redirect_header("account.php?WebID={$this->WebID}", 3, _MD_TCW_NOW_READ_POWER);
        }

        if (_IS_EZCLASS) {
            $AccountCount = $data['AccountCount'] = $this->add_counter($AccountID);
        } else {
            $this->add_counter($AccountID);
        }

        // $subdir = isset($this->WebID) ? "/{$this->WebID}" : "";
        // $TadUpFiles->set_dir('subdir', $subdir);
        $TadUpFiles->set_col('AccountID', $AccountID);
        $pics = $TadUpFiles->show_files('upfile'); //是否縮圖,顯示模式 filename、small,顯示描述,顯示下載次數

        $uid_name = \XoopsUser::getUnameFromId($uid, 1);
        if (empty($uid_name)) {
            $uid_name = \XoopsUser::getUnameFromId($uid, 0);
        }

        $assistant = is_assistant($this->WebID, 'account', $CateID, 'AccountID', $AccountID);
        $isAssistant = !empty($assistant) ? true : false;
        $uid_name = $isAssistant ? "{$uid_name} <a href='#' title='由{$assistant['MemName']}代理發布'><i class='fa fa-male'></i></a>" : $uid_name;
        $xoopsTpl->assign('isAssistant', $isAssistant);
        $xoopsTpl->assign('isCanEdit', isCanEdit($this->WebID, 'account', $CateID, 'AccountID', $AccountID));

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
        $cate = $this->WebCate->get_tad_web_cate($CateID);
        if ($CateID and '1' != $cate['CateEnable']) {
            return;
        }
        $xoopsTpl->assign('cate', $cate);

        $SweetAlert = new SweetAlert();
        $SweetAlert->render('delete_account_func', "account.php?op=delete&WebID={$this->WebID}&AccountID=", 'AccountID');

        // $xoopsTpl->assign("tags", $this->tags->list_tags("AccountID", $AccountID, 'account'));
    }

    //tad_web_account編輯表單
    public function edit_form($AccountID = '')
    {
        global $xoopsDB, $xoopsUser, $MyWebs, $isMyWeb, $xoopsTpl, $TadUpFiles, $WebTitle;

        chk_self_web($this->WebID, $_SESSION['isAssistant']['account']);
        get_quota($this->WebID);

        //抓取預設值
        if (!empty($AccountID)) {
            $DBV = $this->get_one_data($AccountID);
        } else {
            $DBV = [];
        }

        //預設值設定

        //設定「AccountID」欄位預設值
        $AccountID = (!isset($DBV['AccountID'])) ? $AccountID : $DBV['AccountID'];
        $xoopsTpl->assign('AccountID', $AccountID);

        //設定「AccountTitle」欄位預設值
        $AccountTitle = (!isset($DBV['AccountTitle'])) ? '' : $DBV['AccountTitle'];
        $xoopsTpl->assign('AccountTitle', $AccountTitle);

        //設定「AccountDesc」欄位預設值
        $AccountDesc = (!isset($DBV['AccountDesc'])) ? '' : $DBV['AccountDesc'];
        $xoopsTpl->assign('AccountDesc', $AccountDesc);

        //設定「AccountDate」欄位預設值
        $AccountDate = (!isset($DBV['AccountDate'])) ? date('Y-m-d') : $DBV['AccountDate'];
        $xoopsTpl->assign('AccountDate', $AccountDate);

        //設定「AccountIncome」欄位預設值
        $AccountIncome = (!isset($DBV['AccountIncome'])) ? '' : $DBV['AccountIncome'];
        $xoopsTpl->assign('AccountIncome', $AccountIncome);

        //設定「AccountOutgoings」欄位預設值
        $AccountOutgoings = (!isset($DBV['AccountOutgoings'])) ? '' : $DBV['AccountOutgoings'];
        $xoopsTpl->assign('AccountOutgoings', $AccountOutgoings);

        //設定「uid」欄位預設值
        $user_uid = ($xoopsUser) ? $xoopsUser->getVar('uid') : '';
        $uid = (!isset($DBV['uid'])) ? $user_uid : $DBV['uid'];
        $xoopsTpl->assign('uid', $uid);

        //設定「WebID」欄位預設值
        $WebID = (!isset($DBV['WebID'])) ? $this->WebID : $DBV['WebID'];
        $xoopsTpl->assign('WebID', $WebID);

        //設定「AccountCount」欄位預設值
        $AccountCount = (!isset($DBV['AccountCount'])) ? '' : $DBV['AccountCount'];
        $xoopsTpl->assign('AccountCount', $AccountCount);

        if (!empty($AccountIncome)) {
            $AccountKind = 'AccountIncome';
            $AccountMoney = $AccountIncome;
        } else {
            $AccountKind = 'AccountOutgoings';
            $AccountMoney = $AccountOutgoings;
        }
        $xoopsTpl->assign('AccountKind', $AccountKind);
        $xoopsTpl->assign('AccountMoney', $AccountMoney);

        //設定「CateID」欄位預設值
        $DefCateID = isset($_SESSION['isAssistant']['account']) ? $_SESSION['isAssistant']['account'] : $this->get_last_account_book();
        $CateID = (!isset($DBV['CateID'])) ? $DefCateID : $DBV['CateID'];
        $this->WebCate->set_label(_MD_TCW_ACCOUNT_BOOK);
        $this->WebCate->set_default_option_text(_MD_TCW_ACCOUNT_SELECT_BOOK);
        $cate_menu = isset($_SESSION['isAssistant']['account']) ? $this->WebCate->hidden_cate_menu($CateID) : $this->WebCate->cate_menu($CateID);
        $xoopsTpl->assign('cate_menu_form', $cate_menu);

        $op = (empty($AccountID)) ? 'insert' : 'update';

        $FormValidator = new FormValidator('#myForm', true);
        $FormValidator->render();

        $xoopsTpl->assign('next_op', $op);

        // $subdir = isset($this->WebID) ? "/{$this->WebID}" : "";
        // $TadUpFiles->set_dir('subdir', $subdir);
        $TadUpFiles->set_col('AccountID', $AccountID); //若 $show_list_del_file ==true 時一定要有
        $upform = $TadUpFiles->upform(true, 'upfile');
        $xoopsTpl->assign('upform', $upform);

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
            $uid = ($xoopsUser) ? $xoopsUser->uid() : '';
        }

        $AccountTitle = $xoopsDB->escape($_POST['AccountTitle']);
        $AccountDesc = $xoopsDB->escape($_POST['AccountDesc']);
        $AccountKind = $xoopsDB->escape($_POST['AccountKind']);
        $AccountDate = $xoopsDB->escape($_POST['AccountDate']);
        $newCateName = $xoopsDB->escape($_POST['newCateName']);
        $AccountMoney = (int) $_POST['AccountMoney'];
        $AccountCount = (int) $_POST['AccountCount'];
        $CateID = (int) $_POST['CateID'];
        $WebID = (int) $_POST['WebID'];

        if ('AccountIncome' === $AccountKind) {
            $AccountIncome = $AccountMoney;
            $AccountOutgoings = 0;
        } else {
            $AccountIncome = 0;
            $AccountOutgoings = $AccountMoney;
        }

        if ($newCateName != '') {
            $CateID = $this->WebCate->save_tad_web_cate($CateID, $newCateName);
        }

        $sql = 'insert into ' . $xoopsDB->prefix('tad_web_account') . "
        (`CateID`,`AccountTitle` , `AccountDesc` , `AccountDate` , `AccountIncome` , `AccountOutgoings` , `uid` , `WebID` , `AccountCount`)
        values('{$CateID}' ,'{$AccountTitle}' , '{$AccountDesc}' , '{$AccountDate}' , '{$AccountIncome}' , '{$AccountOutgoings}' , '{$uid}' , '{$WebID}' , '{$AccountCount}')";
        $xoopsDB->query($sql) or Utility::web_error($sql, __FILE__, __LINE__);

        //取得最後新增資料的流水編號
        $AccountID = $xoopsDB->getInsertId();
        save_assistant_post('account', $CateID, 'AccountID', $AccountID);

        // $subdir = isset($this->WebID) ? "/{$this->WebID}" : "";
        // $TadUpFiles->set_dir('subdir', $subdir);
        $TadUpFiles->set_col('AccountID', $AccountID);
        $TadUpFiles->upload_file('upfile', 800, null, null, null, true);
        check_quota($this->WebID);
        //儲存標籤
        // $this->tags->save_tags("AccountID", $AccountID, $_POST['tag_name'], $_POST['tags']);
        return $AccountID;
    }

    //更新tad_web_account某一筆資料
    public function update($AccountID = '')
    {
        global $xoopsDB, $TadUpFiles;

        $AccountTitle = $xoopsDB->escape($_POST['AccountTitle']);
        $AccountDesc = $xoopsDB->escape($_POST['AccountDesc']);
        $AccountKind = $xoopsDB->escape($_POST['AccountKind']);
        $newCateName = $xoopsDB->escape($_POST['newCateName']);
        $AccountDate = $xoopsDB->escape($_POST['AccountDate']);
        $AccountMoney = (int) $_POST['AccountMoney'];
        $CateID = (int) $_POST['CateID'];
        $WebID = (int) $_POST['WebID'];

        if ('AccountIncome' === $AccountKind) {
            $AccountIncome = $AccountMoney;
            $AccountOutgoings = '';
        } else {
            $AccountIncome = '';
            $AccountOutgoings = $AccountMoney;
        }

        if ($newCateName != '') {
            $CateID = $this->WebCate->save_tad_web_cate($CateID, $newCateName);
        }

        if (!is_assistant($this->WebID, 'account', $CateID, 'AccountID', $AccountID)) {
            $anduid = onlyMine();
        }

        $sql = 'update ' . $xoopsDB->prefix('tad_web_account') . " set
        `CateID` = '{$CateID}' ,
        `AccountTitle` = '{$AccountTitle}' ,
        `AccountDesc` = '{$AccountDesc}' ,
        `AccountDate` = '{$AccountDate}' ,
        `AccountIncome` = '{$AccountIncome}',
        `AccountOutgoings` = '{$AccountOutgoings}'
        where AccountID='$AccountID' $anduid";
        $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);

        // $subdir = isset($this->WebID) ? "/{$this->WebID}" : "";
        // $TadUpFiles->set_dir('subdir', $subdir);
        $TadUpFiles->set_col('AccountID', $AccountID);
        $TadUpFiles->upload_file('upfile', 800, null, null, null, true);
        check_quota($this->WebID);
        //儲存標籤
        //$this->tags->save_tags("AccountID", $AccountID, $_POST['tag_name'], $_POST['tags']);
        return $AccountID;
    }

    //刪除tad_web_account某筆資料資料
    public function delete($AccountID = '')
    {
        global $xoopsDB, $TadUpFiles;
        $sql = 'select CateID from ' . $xoopsDB->prefix('tad_web_account') . " where AccountID='$AccountID'";
        $result = $xoopsDB->query($sql) or Utility::web_error($sql, __FILE__, __LINE__);
        list($CateID) = $xoopsDB->fetchRow($result);
        if (!is_assistant($this->WebID, 'account', $CateID, 'AccountID', $AccountID)) {
            $anduid = onlyMine();
        }
        $sql = 'delete from ' . $xoopsDB->prefix('tad_web_account') . " where AccountID='$AccountID' $anduid";
        $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);

        // $subdir = isset($this->WebID) ? "/{$this->WebID}" : "";
        // $TadUpFiles->set_dir('subdir', $subdir);
        $TadUpFiles->set_col('AccountID', $AccountID);
        $TadUpFiles->del_files();
        check_quota($this->WebID);

        // $this->Power->delete_power("AccountID", $AccountID, 'read');
        //刪除標籤
        // $this->tags->delete_tags("AccountID", $AccountID);
    }

    //刪除所有資料
    public function delete_all()
    {
        global $xoopsDB, $TadUpFiles;
        $allCateID = [];
        $sql = 'select AccountID,CateID from ' . $xoopsDB->prefix('tad_web_account') . " where WebID='{$this->WebID}'";
        $result = $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);
        while (list($AccountID, $CateID) = $xoopsDB->fetchRow($result)) {
            $this->delete($AccountID);
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
        $sql = 'select count(*) from ' . $xoopsDB->prefix('tad_web_account') . " where WebID='{$this->WebID}'";
        $result = $xoopsDB->query($sql) or Utility::web_error($sql, __FILE__, __LINE__);
        list($count) = $xoopsDB->fetchRow($result);
        return $count;
    }

    //新增tad_web_account計數器
    public function add_counter($AccountID = '')
    {
        global $xoopsDB;

        if (_IS_EZCLASS) {
            $AccountCount = redis_do($this->WebID, 'get', 'account', "AccountCount:$AccountID");
            if (empty($AccountCount)) {
                $sql = 'select AccountCount from ' . $xoopsDB->prefix('tad_web_account') . " where AccountID='$AccountID'";
                $result = $xoopsDB->query($sql) or Utility::web_error($sql, __FILE__, __LINE__);
                list($AccountCount) = $xoopsDB->fetchRow($result);
                redis_do($this->WebID, 'set', 'account', "AccountCount:$AccountID", $AccountCount);
            }
            return redis_do($this->WebID, 'incr', 'account', "AccountCount:$AccountID");
        } else {
            $sql = 'update ' . $xoopsDB->prefix('tad_web_account') . " set `AccountCount`=`AccountCount`+1 where `AccountID`='{$AccountID}'";
            $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);
        }
    }

    //以流水號取得某筆tad_web_account資料
    public function get_one_data($AccountID = '')
    {
        global $xoopsDB;
        if (empty($AccountID)) {
            return;
        }

        $sql = 'select * from ' . $xoopsDB->prefix('tad_web_account') . " where AccountID='$AccountID'";
        $result = $xoopsDB->query($sql) or Utility::web_error($sql, __FILE__, __LINE__);
        $data = $xoopsDB->fetchArray($result);

        if (_IS_EZCLASS) {
            $data['AccountCount'] = redis_do($this->WebID, 'get', 'account', "AccountCount:$AccountID");
        }
        return $data;
    }

    //匯出資料
    public function export_data($start_date = '', $end_date = '', $CateID = '')
    {
        global $xoopsDB, $xoopsTpl, $TadUpFiles, $MyWebs;
        $andCateID = empty($CateID) ? '' : "and `CateID`='$CateID'";
        $andStart = empty($start_date) ? '' : "and AccountDate >= '{$start_date}'";
        $andEnd = empty($end_date) ? '' : "and AccountDate <= '{$end_date}'";

        $sql = 'select AccountID,AccountTitle,AccountDate,CateID from ' . $xoopsDB->prefix('tad_web_account') . " where WebID='{$this->WebID}' {$andStart} {$andEnd} {$andCateID} order by AccountDate";
        $result = $xoopsDB->query($sql) or Utility::web_error($sql, __FILE__, __LINE__);

        $i = 0;
        $main_data = [];
        while (list($ID, $title, $date, $CateID) = $xoopsDB->fetchRow($result)) {
            $main_data[$i]['ID'] = $ID;
            $main_data[$i]['CateID'] = $CateID;
            $main_data[$i]['title'] = $title;
            $main_data[$i]['date'] = $date;

            $i++;
        }
        return $main_data;
    }

    //取得最近更新的帳簿
    public function get_last_account_book()
    {
        global $xoopsDB, $xoopsTpl, $TadUpFiles, $MyWebs;

        $sql = 'select CateID from ' . $xoopsDB->prefix('tad_web_account') . " where WebID='{$this->WebID}' order by AccountDate desc limit 0,1";
        $result = $xoopsDB->query($sql) or Utility::web_error($sql, __FILE__, __LINE__);
        list($CateID) = $xoopsDB->fetchRow($result);
        return $CateID;
    }
}
