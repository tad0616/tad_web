<?php
class tad_web_news
{

    public $WebID = 0;
    public $web_cate;
    public $setup;

    public function __construct($WebID)
    {
        $this->WebID    = $WebID;
        $this->web_cate = new web_cate($WebID, "news", "tad_web_news");
        $this->power    = new power($WebID);
        $this->tags     = new tags($WebID);
        $this->setup    = get_plugin_setup_values($WebID, "news");
    }

    //最新消息
    public function list_all($CateID = "", $limit = null, $mode = "assign", $tag = '')
    {
        global $xoopsDB, $xoopsTpl, $MyWebs, $isMyWeb, $plugin_menu_var;

        $andWebID = (empty($this->WebID)) ? "" : "and a.WebID='{$this->WebID}'";

        $andCateID = "";
        if ($mode == "assign") {
            //取得tad_web_cate所有資料陣列
            if (!empty($plugin_menu_var)) {
                $this->web_cate->set_button_value($plugin_menu_var['news']['short'] . _MD_TCW_CATE_TOOLS);
                $this->web_cate->set_default_option_text(sprintf(_MD_TCW_SELECT_PLUGIN_CATE, $plugin_menu_var['news']['short']));
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
                $xoopsTpl->assign('NewsDefCateID', $CateID);
            }
        }

        $andEnable = $isMyWeb ? '' : "and a.`NewsEnable`='1'";

        if (_IS_EZCLASS and !empty($_GET['county'])) {
            //https://class.tn.edu.tw/modules/tad_web/index.php?county=臺南市&city=永康區&SchoolName=XX國小
            include_once $GLOBALS['xoops']->path('/modules/system/include/functions.php');
            $county        = system_CleanVars($_REQUEST, 'county', '', 'string');
            $city          = system_CleanVars($_REQUEST, 'city', '', 'string');
            $SchoolName    = system_CleanVars($_REQUEST, 'SchoolName', '', 'string');
            $andCounty     = !empty($county) ? "and c.county='{$county}'" : "";
            $andCity       = !empty($city) ? "and c.city='{$city}'" : "";
            $andSchoolName = !empty($SchoolName) ? "and c.SchoolName='{$SchoolName}'" : "";

            $sql = "select a.* from " . $xoopsDB->prefix("tad_web_news") . " as a
            left join " . $xoopsDB->prefix("tad_web") . " as b on a.WebID=b.WebID
            left join " . $xoopsDB->prefix("apply") . " as c on b.WebOwnerUid=c.uid
            left join " . $xoopsDB->prefix("tad_web_cate") . " as d on a.CateID=d.CateID
            where b.`WebEnable`='1' and (d.CateEnable='1' or a.CateID='0') {$andEnable} $andCounty $andCity $andSchoolName
            order by a.NewsDate desc";
        } elseif (!empty($tag)) {
            $sql = "select distinct a.* from " . $xoopsDB->prefix("tad_web_news") . " as a
            left join " . $xoopsDB->prefix("tad_web") . " as b on a.WebID=b.WebID
            join " . $xoopsDB->prefix("tad_web_tags") . " as c on c.col_name='NewsID' and c.col_sn=a.NewsID
            left join " . $xoopsDB->prefix("tad_web_cate") . " as d on a.CateID=d.CateID
            where b.`WebEnable`='1' and (d.CateEnable='1' or a.CateID='0') and c.`tag_name`='{$tag}' {$andEnable} $andWebID $andCateID
            order by a.NewsDate desc";
            // die($sql);

        } else {
            $sql = "select a.* from " . $xoopsDB->prefix("tad_web_news") . " as a
            left join " . $xoopsDB->prefix("tad_web") . " as b on a.WebID=b.WebID
            left join " . $xoopsDB->prefix("tad_web_cate") . " as c on a.CateID=c.CateID
            where b.`WebEnable`='1' and (c.CateEnable='1' or a.CateID='0') {$andEnable} $andWebID $andCateID
            order by a.NewsDate desc";
        }

        // if ($_GET['test'] == 1) {
        //     die($sql);
        // }

        $to_limit = empty($limit) ? 10 : $limit;

        //getPageBar($原sql語法, 每頁顯示幾筆資料, 最多顯示幾個頁數選項);
        $PageBar = getPageBar($sql, $to_limit, 10);
        $bar     = $PageBar['bar'];
        $sql     = $PageBar['sql'];
        $total   = $PageBar['total'];

        $result = $xoopsDB->query($sql) or web_error($sql);

        $main_data = array();

        $i = 0;

        $Webs = getAllWebInfo();

        $cate = $this->web_cate->get_tad_web_cate_arr();

        while ($all = $xoopsDB->fetchArray($result)) {
            //以下會產生這些變數： $NewsID , $NewsTitle , $NewsContent , $NewsDate , $toCal  , $NewsUrl , $WebID  , $NewsCounter , $NewsEnable
            foreach ($all as $k => $v) {
                $$k = $v;
            }

            //檢查權限
            $power = $this->power->check_power("read", "NewsID", $NewsID);
            if (!$power) {
                continue;
            }

            $main_data[$i] = $all;
            $main_data[$i]['id'] = $NewsID;
            $main_data[$i]['id_name'] = 'NewsID';
            $main_data[$i]['title'] = $NewsTitle;

            // $main_data[$i]['isAssistant'] = is_assistant($CateID, 'NewsID', $NewsID);
            $main_data[$i]['isCanEdit'] = isCanEdit($this->WebID, 'news', $CateID, 'NewsID', $NewsID);

            $this->web_cate->set_WebID($WebID);

            $Content = get_article_content($NewsContent);

            if ($Content['pages'] > 1) {
                $main_data[$i]['NewsContent'] = $Content['info'];
                $main_data[$i]['more']        = true;
            } else {
                $main_data[$i]['more'] = false;
            }
            $main_data[$i]['cate']     = isset($cate[$CateID]) ? $cate[$CateID] : '';
            $main_data[$i]['WebTitle'] = "<a href='index.php?WebID={$WebID}'>{$Webs[$WebID]}</a>";

            $Date = substr($NewsDate, 0, 10);
            if (empty($NewsTitle)) {
                $NewsTitle = _MD_TCW_EMPTY_TITLE;
            }

            $main_data[$i]['NewsTitle'] = $NewsTitle;
            // $main_data[$i]['isMyWeb']  = in_array($WebID, $MyWebs) ? 1 : 0;
            $main_data[$i]['isMyWeb'] = $isMyWeb;
            $main_data[$i]['Date']      = $Date;
            $i++;
        }

        if (!file_exists(XOOPS_ROOT_PATH . "/modules/tadtools/sweet_alert.php")) {
            redirect_header("index.php", 3, _MA_NEED_TADTOOLS);
        }
        include_once XOOPS_ROOT_PATH . "/modules/tadtools/sweet_alert.php";
        $sweet_alert = new sweet_alert();
        $sweet_alert->render("delete_news_func", "news.php?op=delete&WebID={$this->WebID}&NewsID=", 'NewsID');

        if ($mode == "return") {
            $data['main_data'] = $main_data;
            $data['total']     = $total;
            return $data;
        } else {
            $xoopsTpl->assign('news_data', $main_data);
            $xoopsTpl->assign('bar', $bar);
            $xoopsTpl->assign('news', get_db_plugin($this->WebID, 'news'));
            return $total;
        }
    }

    //以流水號秀出某筆tad_web_news資料內容
    public function show_one($NewsID = "", $mode = "assign")
    {
        global $xoopsDB, $WebID, $isAdmin, $xoopsTpl, $TadUpFiles, $isMyWeb;
        if (empty($NewsID)) {
            return;
        }

        $NewsID = (int)$NewsID;
        $this->add_counter($NewsID);

        $andEnable = $isMyWeb ? '' : "and `NewsEnable`='1'";

        $sql    = "select * from " . $xoopsDB->prefix("tad_web_news") . " where NewsID='{$NewsID}' {$andEnable}";
        $result = $xoopsDB->query($sql) or web_error($sql);
        $all    = $xoopsDB->fetchArray($result);
        $data   = $all;

        //以下會產生這些變數： $NewsID , $NewsTitle , $NewsContent , $NewsDate , $toCal  , $NewsUrl , $WebID , $NewsCounter ,$uid, $NewsEnable
        foreach ($all as $k => $v) {
            $$k = $v;
        }

        //檢查權限
        $power = $this->power->check_power("read", "NewsID", $NewsID);
        if (!$power) {
            redirect_header("index.php?WebID={$this->WebID}", 3, _MD_TCW_NOW_READ_POWER);
        }

        $prev_next = $this->get_prev_next($NewsID);
        // if (isset($_GET['test'])) {
        //     die(var_export($prev_next));
        // }
        $xoopsTpl->assign('prev_next', $prev_next);

        if (empty($uid)) {
            redirect_header('index.php', 3, _MD_TCW_DATA_NOT_EXIST);
        }

        $uid_name = XoopsUser::getUnameFromId($uid, 1);
        if (empty($uid_name)) {
            $uid_name = XoopsUser::getUnameFromId($uid, 0);
        }

        $NewsUrlTxt = empty($NewsUrl) ? "" : "<div>" . _MD_TCW_NEWSURL . _TAD_FOR . "<a href='$NewsUrl' target='_blank'>$NewsUrl</a></div>";

        $TadUpFiles->set_col("NewsID", $NewsID);
        $NewsFiles = $TadUpFiles->show_files('upfile', true, "", true, false, null, null, false, '');

        //取消換頁符號
        $pattern     = "/<div style=\"page-break-after: always;?\">\s*<span style=\"display: none;?\">&nbsp;<\/span>\s*<\/div>/";
        $NewsContent = preg_replace($pattern, '', $NewsContent);

        $assistant = is_assistant($CateID, 'NewsID', $NewsID);
        $isAssistant = !empty($assistant) ? true : false;
        $uid_name = $isAssistant ? "{$uid_name} <a href='#' title='由{$assistant['MemName']}代理發布'><i class='fa fa-male'></i></a>" : $uid_name;
        $xoopsTpl->assign("isAssistant", $isAssistant);

        $xoopsTpl->assign("isCanEdit", isCanEdit($this->WebID, 'news', $CateID, 'NewsID', $NewsID));

        if ($mode == "return") {
            $data['uid_name']    = $uid_name;
            $data['NewsUrlTxt']  = $NewsUrlTxt;
            $data['NewsFiles']   = $NewsFiles;
            $data['NewsContent'] = $NewsContent;
            $data['NewsInfo']    = sprintf(_MD_TCW_INFO, $uid_name, $NewsDate, $NewsCounter);
            return $data;
        } else {
            $xoopsTpl->assign('NewsTitle', $NewsTitle);
            $xoopsTpl->assign('NewsUrlTxt', $NewsUrlTxt);
            $xoopsTpl->assign('NewsContent', $NewsContent);
            $xoopsTpl->assign('uid_name', $uid_name);
            $xoopsTpl->assign('NewsDate', $NewsDate);
            $xoopsTpl->assign('NewsCounter', $NewsCounter);
            $xoopsTpl->assign('NewsFiles', $NewsFiles);
            $xoopsTpl->assign('NewsID', $NewsID);
            $xoopsTpl->assign('NewsEnable', $NewsEnable);
            $xoopsTpl->assign('NewsInfo', sprintf(_MD_TCW_INFO, $uid_name, $NewsDate, $NewsCounter));
        }

        $xoopsTpl->assign('xoops_pagetitle', $NewsTitle);
        $xoopsTpl->assign('fb_description', xoops_substr(strip_tags($NewsContent), 0, 300));

        //取得單一分類資料
        $cate = $this->web_cate->get_tad_web_cate($CateID);
        if ($CateID and $cate['CateEnable'] != '1') {
            return;
        }
        $xoopsTpl->assign('cate', $cate);

        if (!file_exists(XOOPS_ROOT_PATH . "/modules/tadtools/sweet_alert.php")) {
            redirect_header("index.php", 3, _MA_NEED_TADTOOLS);
        }
        include_once XOOPS_ROOT_PATH . "/modules/tadtools/sweet_alert.php";
        $sweet_alert = new sweet_alert();
        $sweet_alert->render("delete_news_func", "news.php?op=delete&WebID={$WebID}&NewsID=", 'NewsID');

        if (!file_exists(XOOPS_ROOT_PATH . "/modules/tadtools/jquery-print-preview.php")) {
            redirect_header("index.php", 3, _MA_NEED_TADTOOLS);
        }
        include_once XOOPS_ROOT_PATH . "/modules/tadtools/jquery-print-preview.php";
        $print_preview = new print_preview('a.print-preview');
        $print_preview->render();

        $xoopsTpl->assign("module_css", '<link rel="stylesheet" href="' . XOOPS_URL . '/modules/tad_web/plugins/news/print.css" type="text/css" media="print" />');

        $xoopsTpl->assign("fb_comments", fb_comments($this->setup['use_fb_comments']));

        //取得標籤
        $xoopsTpl->assign("tags", $this->tags->list_tags("NewsID", $NewsID, 'news'));

        // $xoopsTpl->assign("isAssistant", is_assistant($CateID, 'NewsID', $NewsID));

    }

    //tad_web_news編輯表單
    public function edit_form($NewsID = "")
    {
        global $xoopsDB, $xoopsUser, $MyWebs, $isMyWeb, $xoopsTpl, $TadUpFiles, $plugin_menu_var;

        chk_self_web($this->WebID, $_SESSION['isAssistant']['news']);
        get_quota($this->WebID);

        //抓取預設值
        if (!empty($NewsID)) {
            $DBV = $this->get_one_data($NewsID);
        } else {
            $DBV = array();
        }

        //設定「NewsID」欄位預設值
        $NewsID = (!isset($DBV['NewsID'])) ? "" : $DBV['NewsID'];
        $xoopsTpl->assign('NewsID', $NewsID);

        //設定「NewsTitle」欄位預設值
        $NewsTitle = isset($DBV['NewsDate']) ? $DBV['NewsTitle'] : "";
        $xoopsTpl->assign('NewsTitle', $NewsTitle);

        //設定「NewsContent」欄位預設值
        $NewsContent = isset($DBV['NewsContent']) ? $DBV['NewsContent'] : "";
        $xoopsTpl->assign('NewsContent', $NewsContent);

        //設定「NewsDate」欄位預設值
        $NewsDate = (!isset($DBV['NewsDate'])) ? date("Y-m-d H:i:s") : $DBV['NewsDate'];
        $xoopsTpl->assign('NewsDate', $NewsDate);

        //設定「toCal」欄位預設值
        if (!isset($DBV['toCal'])) {
            $toCal = "";
        } else {
            $toCal = ($DBV['toCal'] == "0000-00-00 00:00:00") ? "" : $DBV['toCal'];
        }
        $xoopsTpl->assign('toCal', $toCal);

        //設定「NewsUrl」欄位預設值
        $NewsUrl = (!isset($DBV['NewsUrl'])) ? "" : $DBV['NewsUrl'];
        $xoopsTpl->assign('NewsUrl', $NewsUrl);

        //設定「WebID」欄位預設值
        $WebID = (!isset($DBV['WebID'])) ? $this->WebID : $DBV['WebID'];
        $xoopsTpl->assign('WebID', $WebID);

        //設定「NewsCounter」欄位預設值
        $NewsCounter = (!isset($DBV['NewsCounter'])) ? "" : $DBV['NewsCounter'];
        $xoopsTpl->assign('NewsCounter', $NewsCounter);

        //設定「CateID」欄位預設值
        $DefCateID = isset($_SESSION['isAssistant']['news']) ? $_SESSION['isAssistant']['news'] : '';
        $CateID    = (!isset($DBV['CateID'])) ? $DefCateID : $DBV['CateID'];
        $this->web_cate->set_button_value($plugin_menu_var['news']['short'] . _MD_TCW_CATE_TOOLS);
        $this->web_cate->set_default_option_text(sprintf(_MD_TCW_SELECT_PLUGIN_CATE, $plugin_menu_var['news']['short']));
        $cate_menu = isset($_SESSION['isAssistant']['news']) ? $this->web_cate->hidden_cate_menu($CateID) : $this->web_cate->cate_menu($CateID);
        $xoopsTpl->assign('cate_menu_form', $cate_menu);

        //設定「NewsEnable」欄位預設值
        $NewsEnable = (!isset($DBV['NewsEnable'])) ? "1" : $DBV['NewsEnable'];
        $xoopsTpl->assign('NewsEnable', $NewsEnable);

        //設定「uid」欄位預設值
        $user_uid = ($xoopsUser) ? $xoopsUser->getVar('uid') : "";
        $uid = (!isset($DBV['uid'])) ? $user_uid : $DBV['uid'];
        $xoopsTpl->assign('uid', $uid);

        $op = (empty($NewsID)) ? "insert" : "update";

        if (!file_exists(TADTOOLS_PATH . "/formValidator.php")) {
            redirect_header("index.php", 3, _MD_NEED_TADTOOLS);
        }
        include_once TADTOOLS_PATH . "/formValidator.php";
        $formValidator      = new formValidator("#myForm", true);
        $formValidator_code = $formValidator->render();
        $xoopsTpl->assign('formValidator_code', $formValidator_code);

        include_once XOOPS_ROOT_PATH . "/modules/tadtools/ck.php";
        mk_dir(XOOPS_ROOT_PATH . "/uploads/tad_web/{$this->WebID}/news");
        mk_dir(XOOPS_ROOT_PATH . "/uploads/tad_web/{$this->WebID}/news/image");
        mk_dir(XOOPS_ROOT_PATH . "/uploads/tad_web/{$this->WebID}/news/file");
        $ck = new CKEditor("tad_web/{$this->WebID}/news", "NewsContent", $NewsContent);
        $ck->setHeight(300);
        $editor = $ck->render();
        $xoopsTpl->assign('NewsContent_editor', $editor);

        $xoopsTpl->assign('next_op', $op);

        $TadUpFiles->set_col("NewsID", $NewsID);
        $upform = $TadUpFiles->upform();
        $xoopsTpl->assign('upform', $upform);

        //權限設定
        $power_form = $this->power->power_menu('read', "NewsID", $NewsID);
        $xoopsTpl->assign('power_form', $power_form);
        //標籤設定
        $tags_form = $this->tags->tags_menu("NewsID", $NewsID);
        $xoopsTpl->assign('tags_form', $tags_form);

    }

    //新增資料到tad_web_news中
    public function insert()
    {
        global $xoopsDB, $xoopsUser, $TadUpFiles, $WebOwnerUid;
        if (isset($_SESSION['isAssistant']['news'])) {
            $uid = $WebOwnerUid;
        } elseif (!empty($_POST['uid'])) {
            $uid = (int)$_POST['uid'];
        } else {
            $uid = ($xoopsUser) ? $xoopsUser->uid() : "";
        }

        $myts                 = MyTextSanitizer::getInstance();
        $_POST['NewsTitle']   = $myts->addSlashes($_POST['NewsTitle']);
        $_POST['NewsUrl']     = $myts->addSlashes($_POST['NewsUrl']);
        $_POST['NewsContent'] = $myts->addSlashes($_POST['NewsContent']);
        $_POST['CateID']      = (int)$_POST['CateID'];
        $_POST['WebID']       = (int)$_POST['WebID'];
        $_POST['NewsEnable']  = (int)$_POST['NewsEnable'];

        if (empty($_POST['toCal'])) {
            $_POST['toCal'] = "0000-00-00 00:00:00";
        }

        $CateID = $this->web_cate->save_tad_web_cate($_POST['CateID'], $_POST['newCateName']);
        $sql    = "insert into " . $xoopsDB->prefix("tad_web_news") . "
        (`CateID`,`NewsTitle` , `NewsContent` , `NewsDate` , `toCal` , `NewsUrl` , `WebID` , `NewsCounter` , `uid` , `NewsEnable`)
        values('{$CateID}','{$_POST['NewsTitle']}' , '{$_POST['NewsContent']}' , '{$_POST['NewsDate']}' , '{$_POST['toCal']}' , '{$_POST['NewsUrl']}' , '{$_POST['WebID']}'  , '0' , '{$uid}', '{$_POST['NewsEnable']}' )";
        $xoopsDB->query($sql) or web_error($sql);

        //取得最後新增資料的流水編號
        $NewsID = $xoopsDB->getInsertId();
        save_assistant_post($CateID, 'NewsID', $NewsID);

        $TadUpFiles->set_col("NewsID", $NewsID);
        $TadUpFiles->upload_file('upfile', 640, null, null, null, true);
        check_quota($this->WebID);

        //儲存權限
        $this->power->save_power("NewsID", $NewsID, 'read');
        //儲存標籤

        $this->tags->save_tags("NewsID", $NewsID, $_POST['tag_name'], $_POST['tags']);
        return $NewsID;
    }

    //更新tad_web_news某一筆資料
    public function update($NewsID = "")
    {
        global $xoopsDB, $TadUpFiles;

        $myts                 = MyTextSanitizer::getInstance();
        $_POST['NewsTitle']   = $myts->addSlashes($_POST['NewsTitle']);
        $_POST['NewsUrl']     = $myts->addSlashes($_POST['NewsUrl']);
        $_POST['NewsContent'] = $myts->addSlashes($_POST['NewsContent']);
        $_POST['CateID']      = (int)$_POST['CateID'];
        $_POST['WebID']       = (int)$_POST['WebID'];
        $_POST['NewsEnable']  = (int)$_POST['NewsEnable'];

        if (empty($_POST['toCal'])) {
            $_POST['toCal'] = "0000-00-00 00:00:00";
        }

        $CateID = $this->web_cate->save_tad_web_cate($_POST['CateID'], $_POST['newCateName']);

        if (!is_assistant($CateID, 'NewsID', $NewsID)) {
            $anduid = onlyMine();
        }

        $sql = "update " . $xoopsDB->prefix("tad_web_news") . " set
         `CateID` = '{$CateID}' ,
         `NewsTitle` = '{$_POST['NewsTitle']}' ,
         `NewsContent` = '{$_POST['NewsContent']}' ,
         `NewsDate` = '{$_POST['NewsDate']}' ,
         `toCal` = '{$_POST['toCal']}' ,
         `NewsUrl` = '{$_POST['NewsUrl']}',
         `NewsEnable`='{$_POST['NewsEnable']}'
        where NewsID='$NewsID' $anduid";
        $xoopsDB->queryF($sql) or web_error($sql);

        $TadUpFiles->set_col("NewsID", $NewsID);
        $TadUpFiles->upload_file('upfile', 640, null, null, null, true);
        check_quota($this->WebID);

        //儲存權限
        $this->power->save_power("NewsID", $NewsID, 'read');
        //儲存標籤
        $this->tags->save_tags("NewsID", $NewsID, $_POST['tag_name'], $_POST['tags']);
        return $NewsID;
    }

    //刪除tad_web_news某筆資料資料
    public function delete($NewsID = "")
    {
        global $xoopsDB, $TadUpFiles;
        $sql          = "select CateID from " . $xoopsDB->prefix("tad_web_news") . " where NewsID='$NewsID'";
        $result       = $xoopsDB->query($sql) or web_error($sql);
        list($CateID) = $xoopsDB->fetchRow($result);
        if (!is_assistant($CateID, 'NewsID', $NewsID)) {
            $anduid = onlyMine();
        }
        $sql = "delete from " . $xoopsDB->prefix("tad_web_news") . " where NewsID='$NewsID' $anduid";
        $xoopsDB->queryF($sql) or web_error($sql);

        $TadUpFiles->set_col("NewsID", $NewsID);
        $TadUpFiles->del_files();
        check_quota($this->WebID);
        //刪除權限
        $this->power->delete_power("NewsID", $NewsID, 'read');
        //刪除標籤
        $this->tags->delete_tags("NewsID", $NewsID);
    }

    //刪除所有資料
    public function delete_all()
    {
        global $xoopsDB, $TadUpFiles;
        $allCateID = array();
        $sql       = "select NewsID,CateID from " . $xoopsDB->prefix("tad_web_news") . " where WebID='{$this->WebID}'";
        $result    = $xoopsDB->queryF($sql) or web_error($sql);
        while (list($NewsID, $CateID) = $xoopsDB->fetchRow($result)) {
            $this->delete($NewsID);
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
        $sql         = "select count(*) from " . $xoopsDB->prefix("tad_web_news") . " where WebID='{$this->WebID}' and `NewsEnable`='1'";
        $result      = $xoopsDB->query($sql) or web_error($sql);
        list($count) = $xoopsDB->fetchRow($result);
        return $count;
    }

    //新增tad_web_news計數器
    public function add_counter($NewsID = '')
    {
        global $xoopsDB;
        $sql = "update " . $xoopsDB->prefix("tad_web_news") . " set `NewsCounter`=`NewsCounter`+1 where `NewsID`='{$NewsID}' and `NewsEnable`='1'";
        // echo $sql . time() . "<br>";
        $xoopsDB->queryF($sql) or web_error($sql);
    }

    //以流水號取得某筆tad_web_news資料
    public function get_one_data($NewsID = "")
    {
        global $xoopsDB;
        if (empty($NewsID)) {
            return;
        }

        $sql    = "select * from " . $xoopsDB->prefix("tad_web_news") . " where NewsID='$NewsID'";
        $result = $xoopsDB->query($sql) or web_error($sql);
        $data   = $xoopsDB->fetchArray($result);
        return $data;
    }

    //取得上下頁
    public function get_prev_next($DefNewsID)
    {
        global $xoopsDB, $isMyWeb;
        $DefNewsSort = '';
        $all = $main = array();
        $andEnable   = $isMyWeb ? "" : "and `NewsEnable`='1'";
        $sql         = "select NewsID,NewsTitle from " . $xoopsDB->prefix("tad_web_news") . " where `WebID`='{$this->WebID}' $andEnable order by NewsDate desc";
        // if (isset($_GET['test'])) {
        //     die(var_export($sql));
        // }
        $result = $xoopsDB->query($sql) or web_error($sql);
        $i      = 0;
        while (list($NewsID, $NewsTitle) = $xoopsDB->fetchRow($result)) {

            //檢查權限
            $power = $this->power->check_power("read", "NewsID", $NewsID);
            if (!$power) {
                continue;
            }

            $all[$i]['NewsID']    = $NewsID;
            $all[$i]['NewsTitle'] = xoops_substr($NewsTitle, 0, 60);
            if ($NewsID == $DefNewsID) {
                $DefNewsSort = $i;
            }
            $i++;
        }
        $prev = $DefNewsSort - 1;
        $next = $DefNewsSort + 1;

        $main['prev'] = $all[$prev];
        $main['next'] = $all[$next];

        return $main;
    }

    //匯出資料
    public function export_data($start_date, $end_date, $CateID = "")
    {

        global $xoopsDB, $xoopsTpl, $TadUpFiles, $MyWebs;
        $andCateID = empty($CateID) ? "" : "and `CateID`='$CateID'";
        $andStart  = empty($start_date) ? "" : "and NewsDate >= '{$start_date}'";
        $andEnd    = empty($end_date) ? "" : "and NewsDate <= '{$end_date}'";

        $sql    = "select NewsID,NewsTitle,NewsDate,CateID from " . $xoopsDB->prefix("tad_web_news") . " where WebID='{$this->WebID}' {$andStart} {$andEnd} {$andCateID} order by NewsDate";
        $result = $xoopsDB->query($sql) or web_error($sql);

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
}
