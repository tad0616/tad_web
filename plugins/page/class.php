<?php
class tad_web_page
{

    public $WebID = 0;
    public $web_cate;
    public $setup;

    public function __construct($WebID)
    {
        $this->WebID = $WebID;
        //die('$WebID=' . $WebID);
        $this->web_cate = new web_cate($WebID, "page", "tad_web_page");
        $this->tags     = new tags($WebID);
        $this->setup    = get_plugin_setup_values($WebID, "page");
    }

    //所有文章
    public function list_all($CateID = "", $limit = null, $mode = "assign", $tag = '', $show_count = '')
    {
        global $xoopsDB, $xoopsTpl, $TadUpFiles, $MyWebs;

        $andWebID = (empty($this->WebID)) ? "" : "and a.WebID='{$this->WebID}'";

        //取得tad_web_cate所有資料陣列
        $cate_arr = $this->web_cate->get_tad_web_cate_arr();

        $xoopsTpl->assign('cate_arr', $cate_arr);
        $andCateID = "";
        if (!empty($CateID) and is_numeric($CateID)) {
            //取得單一分類資料
            $cate = $this->web_cate->get_tad_web_cate($CateID);
            if ($CateID and $cate['CateEnable'] != '1') {
                return;
            }
            $xoopsTpl->assign('cate', $cate);
            $andCateID = "and a.`CateID`='$CateID'";
            $xoopsTpl->assign('PageDefCateID', $CateID);
            $this->setup['list_pages_title'] = 1;
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

            $sql = "select a.* from " . $xoopsDB->prefix("tad_web_page") . " as a
            left join " . $xoopsDB->prefix("tad_web") . " as b on a.WebID=b.WebID
            left join " . $xoopsDB->prefix("apply") . " as c on b.WebOwnerUid=c.uid
            left join " . $xoopsDB->prefix("tad_web_cate") . " as d on a.CateID=d.CateID
            where b.`WebEnable`='1' and (d.CateEnable='1' or a.CateID='0') $andCounty $andCity $andSchoolName
            order by a.PageSort";
        } elseif (!empty($tag)) {
            $sql = "select distinct a.* from " . $xoopsDB->prefix("tad_web_page") . " as a
            left join " . $xoopsDB->prefix("tad_web") . " as b on a.WebID=b.WebID
            join " . $xoopsDB->prefix("tad_web_tags") . " as c on c.col_name='PageID' and c.col_sn=a.PageID
            left join " . $xoopsDB->prefix("tad_web_cate") . " as d on a.CateID=d.CateID
            where b.`WebEnable`='1' and (d.CateEnable='1' or a.CateID='0') and c.`tag_name`='{$tag}' $andWebID $andCateID
            order by a.PageID desc";
        } else {
            $sql = "select a.* from " . $xoopsDB->prefix("tad_web_page") . " as a
            left join " . $xoopsDB->prefix("tad_web") . " as b on a.WebID=b.WebID
            left join " . $xoopsDB->prefix("tad_web_cate") . " as c on a.CateID=c.CateID
            where b.`WebEnable`='1' and (c.CateEnable='1' or a.CateID='0') $andWebID $andCateID
            order by a.PageSort";
        }
        $to_limit = empty($limit) ? '200' : $limit;

        //getPageBar($原sql語法, 每頁顯示幾筆資料, 最多顯示幾個頁數選項);
        $PageBar = getPageBar($sql, $to_limit, 10);
        $bar     = $PageBar['bar'];
        $sql     = $PageBar['sql'];
        $total   = $PageBar['total'];

        if ($_GET['debug'] == 1 and $CateID == "all") {
            echo "<h2>{$sql}</h2>";
            $debug = 1;
        }
        $result = $xoopsDB->query($sql) or web_error($sql);
        $total  = $xoopsDB->getRowsNum($result);

        $main_data = $cate_data = $cate_size = "";

        $i = 0;

        $Webs = getAllWebInfo();

        while ($all = $xoopsDB->fetchArray($result)) {
            //以下會產生這些變數： $PageID , $PageTitle , $PageContent , $PageDate , $PageSort , $uid , $WebID , $PageCount, $PageCSS
            foreach ($all as $k => $v) {
                $$k = $v;
            }

            if (!empty($CateID) and !empty($cate_arr)) {
                if ($cate_arr[$CateID]['CateEnable'] != '1') {
                    continue;
                }
            }

            $main_data[$i]                = $all;
            $main_data[$i]['isAssistant'] = is_assistant($CateID, 'PageID', $PageID);
            $main_data[$i]['show_count']  = $show_count;
            $this->web_cate->set_WebID($WebID);

            $main_data[$i]['cate']     = $cate_arr[$CateID];
            $main_data[$i]['WebTitle'] = "<a href='index.php?WebID={$WebID}'>{$Webs[$WebID]}</a>";
            $main_data[$i]['isMyWeb']  = in_array($WebID, $MyWebs) ? 1 : 0;

            $cate_data[$CateID][] = $all;
            $cate_size[$CateID]   = $this->get_total($CateID);
            $i++;
        }
        if ($debug == 1) {

            die(var_dump($cate_size));
        }
        if (!file_exists(XOOPS_ROOT_PATH . "/modules/tadtools/sweet_alert.php")) {
            redirect_header("index.php", 3, _MA_NEED_TADTOOLS);
        }
        include_once XOOPS_ROOT_PATH . "/modules/tadtools/sweet_alert.php";
        $sweet_alert = new sweet_alert();
        $sweet_alert->render("delete_page_func", "page.php?op=delete&WebID={$this->WebID}&PageID=", 'PageID');

        if ($mode == "return") {
            $data['cate_arr']         = $cate_arr;
            $data['cate_data']        = $cate_data;
            $data['main_data']        = $main_data;
            $data['cate_size']        = $cate_size;
            $data['total']            = $total;
            $data['list_pages_title'] = $this->setup['list_pages_title'];

            return $data;
        } else {
            $xoopsTpl->assign('cate_data', $cate_data);
            $xoopsTpl->assign('page_data', $main_data);
            $xoopsTpl->assign('cate_size', $cate_size);
            $xoopsTpl->assign('page', get_db_plugin($this->WebID, 'page'));
            $xoopsTpl->assign('list_pages_title', $this->setup['list_pages_title']);
            return $total;
        }
    }

    //以流水號秀出某筆tad_web_page資料內容
    public function show_one($PageID = "", $mode = "assign")
    {
        global $xoopsDB, $xoopsTpl, $TadUpFiles, $isMyWeb;
        if (empty($PageID)) {
            return;
        }

        $PageID = (int)$PageID;
        $this->add_counter($PageID);

        $sql    = "select * from " . $xoopsDB->prefix("tad_web_page") . " where PageID='{$PageID}'";
        $result = $xoopsDB->query($sql) or web_error($sql);
        $all    = $xoopsDB->fetchArray($result);

        if ($mode == "return") {
            return $all;
        }

        //以下會產生這些變數： $PageID , $PageTitle , $PageContent , $PageDate , $PageSort , $uid , $WebID , $PageCount, $PageCSS
        foreach ($all as $k => $v) {
            $$k = $v;
        }

        if (empty($PageContent)) {
            redirect_header('index.php', 3, _MD_TCW_DATA_NOT_EXIST);
        }

        $prev_next = $this->get_prev_next($PageID, $CateID);
        $xoopsTpl->assign('prev_next', $prev_next);
        // die(var_export($prev_next));
        $TadUpFiles->set_col("PageID", $PageID);
        $files = $TadUpFiles->show_files('upfile', true, "", true, false, null, null, false, '');

        $uid_name = XoopsUser::getUnameFromId($uid, 1);
        if (empty($uid_name)) {
            $uid_name = XoopsUser::getUnameFromId($uid, 0);
        }

        //取消換頁符號
        $pattern     = "/<div style=\"page-break-after: always;?\">\s*<span style=\"display: none;?\">&nbsp;<\/span>\s*<\/div>/";
        $PageContent = preg_replace($pattern, '', $PageContent);

        $xoopsTpl->assign('PageTitle', $PageTitle);
        $xoopsTpl->assign('PageDate', $PageDate);
        $xoopsTpl->assign('PageSort', $PageSort);
        $xoopsTpl->assign('PageContent', $PageContent);
        $xoopsTpl->assign('uid_name', $uid_name);
        $xoopsTpl->assign('PageCount', $PageCount);
        $xoopsTpl->assign('files', $files);
        $xoopsTpl->assign('PageID', $PageID);
        $xoopsTpl->assign('PageInfo', sprintf(_MD_TCW_INFO, $uid_name, $PageDate, $PageCount));
        $xoopsTpl->assign('PageCSS', $PageCSS);

        $xoopsTpl->assign('xoops_pagetitle', $PageTitle);
        $xoopsTpl->assign('fb_description', xoops_substr(strip_tags($PageContent), 0, 300));

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
        $sweet_alert->render("delete_page_func", "page.php?op=delete&WebID={$this->WebID}&PageID=", 'PageID');

        if (!file_exists(XOOPS_ROOT_PATH . "/modules/tadtools/jquery-print-preview.php")) {
            redirect_header("index.php", 3, _MA_NEED_TADTOOLS);
        }
        include_once XOOPS_ROOT_PATH . "/modules/tadtools/jquery-print-preview.php";
        $print_preview = new print_preview('a.print-preview');
        $print_preview->render();

        $xoopsTpl->assign("module_css", '<link rel="stylesheet" href="' . XOOPS_URL . '/modules/tad_web/plugins/page/print.css" type="text/css" media="print" />');
        $xoopsTpl->assign("fb_comments", fb_comments($this->setup['use_fb_comments']));

        $xoopsTpl->assign("tags", $this->tags->list_tags("PageID", $PageID, 'page'));

        $xoopsTpl->assign("isAssistant", is_assistant($CateID, 'PageID', $PageID));
    }

    //tad_web_page編輯表單
    public function edit_form($PageID = "")
    {
        global $xoopsDB, $xoopsUser, $MyWebs, $isMyWeb, $xoopsTpl, $TadUpFiles, $plugin_menu_var;

        if (!$isMyWeb and $MyWebs) {
            redirect_header($_SERVER['PHP_SELF'] . "?op=WebID={$MyWebs[0]}&op=edit_form", 3, _MD_TCW_AUTO_TO_HOME);
        } elseif (!$isMyWeb and !$_SESSION['isAssistant']['page']) {
            redirect_header("index.php?WebID={$this->WebID}", 3, _MD_TCW_NOT_OWNER);
        }
        get_quota($this->WebID);

        //抓取預設值
        if (!empty($PageID)) {
            $DBV = $this->get_one_data($PageID);
        } else {
            $DBV = array();
        }

        //預設值設定

        //設定「PageID」欄位預設值
        $PageID = (!isset($DBV['PageID'])) ? $PageID : $DBV['PageID'];
        $xoopsTpl->assign('PageID', $PageID);

        //設定「PageTitle」欄位預設值
        $PageTitle = (!isset($DBV['PageTitle'])) ? "" : $DBV['PageTitle'];
        $xoopsTpl->assign('PageTitle', $PageTitle);

        //設定「PageContent」欄位預設值
        $PageContent = (!isset($DBV['PageContent'])) ? "" : $DBV['PageContent'];
        $xoopsTpl->assign('PageContent', $PageContent);

        // //設定「PageDate」欄位預設值
        // $PageDate = (!isset($DBV['PageDate'])) ? date("Y-m-d") : $DBV['PageDate'];
        // $xoopsTpl->assign('PageDate', $PageDate);

        // //設定「PageSort」欄位預設值
        // $PageSort = (!isset($DBV['PageSort'])) ? "" : $DBV['PageSort'];
        // $xoopsTpl->assign('PageSort', $PageSort);

        //設定「uid」欄位預設值
        $user_uid = ($xoopsUser) ? $xoopsUser->getVar('uid') : "";
        $uid      = (!isset($DBV['uid'])) ? $user_uid : $DBV['uid'];
        $xoopsTpl->assign('uid', $uid);

        //設定「WebID」欄位預設值
        $WebID = (!isset($DBV['WebID'])) ? $this->WebID : $DBV['WebID'];
        $xoopsTpl->assign('WebID', $WebID);

        //設定「PageCount」欄位預設值
        $PageCount = (!isset($DBV['PageCount'])) ? "" : $DBV['PageCount'];
        $xoopsTpl->assign('PageCount', $PageCount);

        //設定「PageCSS」欄位預設值
        $PageCSS = empty($DBV['PageCSS']) ? "line-height: 2; font-size:120%; background: #FFFFFF;" : $DBV['PageCSS'];
        $xoopsTpl->assign('PageCSS', $PageCSS);

        //設定「CateID」欄位預設值
        $DefCateID = isset($_SESSION['isAssistant']['page']) ? $_SESSION['isAssistant']['page'] : '';
        $CateID    = (!isset($DBV['CateID'])) ? $DefCateID : $DBV['CateID'];
        if (!empty($plugin_menu_var)) {
            $this->web_cate->set_button_value($plugin_menu_var['page']['short'] . _MD_TCW_CATE_TOOLS);
            $this->web_cate->set_default_option_text(sprintf(_MD_TCW_SELECT_PLUGIN_CATE, $plugin_menu_var['page']['short']));
            $cate_menu = isset($_SESSION['isAssistant']['page']) ? $this->web_cate->hidden_cate_menu($CateID) : $this->web_cate->cate_menu($CateID);
            $xoopsTpl->assign('cate_menu_form', $cate_menu);
        }

        $op = (empty($PageID)) ? "insert" : "update";

        if (!file_exists(TADTOOLS_PATH . "/formValidator.php")) {
            redirect_header("index.php", 3, _MD_NEED_TADTOOLS);
        }
        include_once TADTOOLS_PATH . "/formValidator.php";
        $formValidator      = new formValidator("#myForm", true);
        $formValidator_code = $formValidator->render();

        $xoopsTpl->assign('formValidator_code', $formValidator_code);
        $xoopsTpl->assign('next_op', $op);

        $TadUpFiles->set_col('PageID', $PageID); //若 $show_list_del_file ==true 時一定要有
        $upform = $TadUpFiles->upform(true, 'upfile');
        $xoopsTpl->assign('upform', $upform);

        include_once XOOPS_ROOT_PATH . "/modules/tadtools/ck.php";
        mk_dir(XOOPS_ROOT_PATH . "/uploads/tad_web/{$this->WebID}/page");
        mk_dir(XOOPS_ROOT_PATH . "/uploads/tad_web/{$this->WebID}/page/image");
        mk_dir(XOOPS_ROOT_PATH . "/uploads/tad_web/{$this->WebID}/page/file");
        $ck = new CKEditor("tad_web/{$this->WebID}/page", "PageContent", $PageContent);
        $ck->setHeight(500);
        $editor = $ck->render();
        $xoopsTpl->assign('PageContent_editor', $editor);

        $tags_form = $this->tags->tags_menu("PageID", $PageID);
        $xoopsTpl->assign('tags_form', $tags_form);

    }

    //新增資料到tad_web_page中
    public function insert()
    {
        global $xoopsDB, $xoopsUser, $TadUpFiles, $WebOwnerUid;
        if (isset($_SESSION['isAssistant']['page'])) {
            $uid = $WebOwnerUid;
        } elseif (!empty($_POST['uid'])) {
            $uid = (int)$_POST['uid'];
        } else {
            $uid = ($xoopsUser) ? $xoopsUser->uid() : "";
        }

        $myts        = MyTextSanitizer::getInstance();
        $PageTitle   = $myts->addSlashes($_POST['PageTitle']);
        $PageContent = $myts->addSlashes($_POST['PageContent']);
        $CateID      = (int)$_POST['CateID'];
        $WebID       = (int)$_POST['WebID'];
        $PageSort    = $this->max_sort($WebID, $CateID);
        $PageDate    = date("Y-m-d H:i:s");
        $PageCSS     = $myts->addSlashes($_POST['PageCSS']);

        $CateID = $this->web_cate->save_tad_web_cate($CateID, $_POST['newCateName']);
        $sql    = "insert into " . $xoopsDB->prefix("tad_web_page") . "
        (`CateID`,`PageTitle` , `PageContent` , `PageDate` , `PageSort` , `uid` , `WebID` , `PageCount` , `PageCSS`)
        values('{$CateID}' ,'{$PageTitle}' , '{$PageContent}' , '{$PageDate}' , '{$PageSort}' , '{$uid}' , '{$WebID}' , '0' , '{$PageCSS}')";
        $xoopsDB->query($sql) or web_error($sql);

        //取得最後新增資料的流水編號
        $PageID = $xoopsDB->getInsertId();
        save_assistant_post($CateID, 'PageID', $PageID);

        $TadUpFiles->set_col('PageID', $PageID);
        $TadUpFiles->upload_file('upfile', 800, null, null, null, true);
        check_quota($this->WebID);
        //儲存標籤
        $this->tags->save_tags("PageID", $PageID, $_POST['tag_name'], $_POST['tags']);
        return $PageID;
    }

    //更新tad_web_page某一筆資料
    public function update($PageID = "")
    {
        global $xoopsDB, $TadUpFiles;

        $myts        = MyTextSanitizer::getInstance();
        $PageTitle   = $myts->addSlashes($_POST['PageTitle']);
        $PageContent = $myts->addSlashes($_POST['PageContent']);
        $CateID      = (int)$_POST['CateID'];
        $WebID       = (int)$_POST['WebID'];
        $PageDate    = date("Y-m-d H:i:s");
        $PageCSS     = $myts->addSlashes($_POST['PageCSS']);

        $CateID = $this->web_cate->save_tad_web_cate($CateID, $_POST['newCateName']);

        if (!is_assistant($CateID, 'PageID', $PageID)) {
            $anduid = onlyMine();
        }

        $sql = "update " . $xoopsDB->prefix("tad_web_page") . " set
         `CateID` = '{$CateID}' ,
         `PageTitle` = '{$PageTitle}' ,
         `PageContent` = '{$PageContent}' ,
         `PageDate` = '{$PageDate}',
         `PageCSS` = '{$PageCSS}'
        where PageID='$PageID' $anduid";
        $xoopsDB->queryF($sql) or web_error($sql);

        $TadUpFiles->set_col('PageID', $PageID);
        $TadUpFiles->upload_file('upfile', 800, null, null, null, true);
        check_quota($this->WebID);
        //儲存標籤
        $this->tags->save_tags("PageID", $PageID, $_POST['tag_name'], $_POST['tags']);
        return $PageID;
    }

    //刪除tad_web_page某筆資料資料
    public function delete($PageID = "")
    {
        global $xoopsDB, $TadUpFiles;
        $sql          = "select CateID from " . $xoopsDB->prefix("tad_web_page") . " where PageID='$PageID'";
        $result       = $xoopsDB->query($sql) or web_error($sql);
        list($CateID) = $xoopsDB->fetchRow($result);

        if (!is_assistant($CateID, 'PageID', $PageID)) {
            $anduid = onlyMine();
        }
        $sql = "delete from " . $xoopsDB->prefix("tad_web_page") . " where PageID='$PageID' $anduid";
        $xoopsDB->queryF($sql) or web_error($sql);

        $TadUpFiles->set_col('PageID', $PageID);
        $TadUpFiles->del_files();
        check_quota($this->WebID);
        //刪除標籤
        $this->tags->delete_tags("PageID", $PageID);
    }

    //刪除所有資料
    public function delete_all()
    {
        global $xoopsDB, $TadUpFiles;
        $allCateID = array();
        $sql       = "select PageID,CateID from " . $xoopsDB->prefix("tad_web_page") . " where WebID='{$this->WebID}'";
        $result    = $xoopsDB->queryF($sql) or web_error($sql);
        while (list($PageID, $CateID) = $xoopsDB->fetchRow($result)) {
            $this->delete($PageID);
            $allCateID[$CateID] = $CateID;
        }
        foreach ($allCateID as $CateID) {
            $this->web_cate->delete_tad_web_cate($CateID);
        }
        check_quota($this->WebID);
    }

    //取得資料總數
    public function get_total($CateID = '')
    {
        global $xoopsDB;
        $andCate     = empty($CateID) ? '' : "and CateID='$CateID'";
        $sql         = "select count(*) from " . $xoopsDB->prefix("tad_web_page") . " where WebID='{$this->WebID}' {$andCate}";
        $result      = $xoopsDB->query($sql) or web_error($sql);
        list($count) = $xoopsDB->fetchRow($result);
        return $count;
    }

    //新增tad_web_page計數器
    public function add_counter($PageID = '')
    {
        global $xoopsDB;
        $sql = "update " . $xoopsDB->prefix("tad_web_page") . " set `PageCount`=`PageCount`+1 where `PageID`='{$PageID}'";
        $xoopsDB->queryF($sql) or web_error($sql);
    }

    //以流水號取得某筆tad_web_page資料
    public function get_one_data($PageID = "")
    {
        global $xoopsDB;
        if (empty($PageID)) {
            return;
        }

        $sql    = "select * from " . $xoopsDB->prefix("tad_web_page") . " where PageID='$PageID'";
        $result = $xoopsDB->query($sql) or web_error($sql);
        $data   = $xoopsDB->fetchArray($result);
        return $data;
    }

    //自動取得tad_web_page的最新排序
    public function max_sort($WebID, $CateID)
    {
        global $xoopsDB;
        $sql        = "select max(`PageSort`) from " . $xoopsDB->prefix("tad_web_page") . " where WebID='$WebID' and CateID='{$CateID}'";
        $result     = $xoopsDB->query($sql) or web_error($sql);
        list($sort) = $xoopsDB->fetchRow($result);
        return ++$sort;
    }

    //取得上下頁
    public function get_prev_next($DefPageID, $DefCateID)
    {
        global $xoopsDB;
        $DefPageSort = $all = $main = '';
        $sql         = "select PageID,PageTitle,PageSort from " . $xoopsDB->prefix("tad_web_page") . " where CateID='{$DefCateID}' order by PageSort";
        // die($sql);
        $result = $xoopsDB->query($sql) or web_error($sql);
        while (list($PageID, $PageTitle, $PageSort) = $xoopsDB->fetchRow($result)) {
            $all[$PageSort]['PageID']    = $PageID;
            $all[$PageSort]['PageTitle'] = $PageTitle;
            if ($PageID == $DefPageID) {
                $DefPageSort = $PageSort;
            }
        }
        // die(var_export($all));
        $prev = $DefPageSort - 1;
        $next = $DefPageSort + 1;

        $main['prev'] = $all[$prev];
        $main['next'] = $all[$next];

        return $main;
    }
}
