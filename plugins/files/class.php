<?php
class tad_web_files
{

    public $WebID = 0;
    public $web_cate;

    public function tad_web_files($WebID)
    {
        $this->WebID    = $WebID;
        $this->web_cate = new web_cate($WebID, "files", "tad_web_files");
        $this->tags     = new tags($WebID);
    }

    //檔案下載
    public function list_all($CateID = "", $limit = "", $mode = "assign", $tag = '')
    {
        global $xoopsDB, $xoopsTpl, $MyWebs, $plugin_menu_var;

        $andWebID = (empty($this->WebID)) ? "" : "and a.WebID='{$this->WebID}'";

        $andCateID = "";
        if ($mode == "assign") {
            //取得tad_web_cate所有資料陣列
            if (!empty($plugin_menu_var)) {
                $this->web_cate->set_button_value($plugin_menu_var['files']['short'] . _MD_TCW_CATE_TOOLS);
                $this->web_cate->set_default_option_text(sprintf(_MD_TCW_SELECT_PLUGIN_CATE, $plugin_menu_var['files']['short']));
                $cate_menu = $this->web_cate->cate_menu($CateID, 'page', false, true, false, true);
                $xoopsTpl->assign('cate_menu', $cate_menu);
            }

            if (!empty($CateID)) {
                //取得單一分類資料
                $cate = $this->web_cate->get_tad_web_cate($CateID);
                $xoopsTpl->assign('cate', $cate);
                $andCateID = "and a.`CateID`='$CateID'";
                $xoopsTpl->assign('FilesDefCateID', $CateID);
            }
        }

        $data = $title = "";

        if (_IS_EZCLASS and !empty($_GET['county'])) {
            //http://class.tn.edu.tw/modules/tad_web/index.php?county=臺南市&city=永康區&SchoolName=XX國小
            include_once $GLOBALS['xoops']->path('/modules/system/include/functions.php');
            $county        = system_CleanVars($_REQUEST, 'county', '', 'string');
            $city          = system_CleanVars($_REQUEST, 'city', '', 'string');
            $SchoolName    = system_CleanVars($_REQUEST, 'SchoolName', '', 'string');
            $andCounty     = !empty($county) ? "and d.county='{$county}'" : "";
            $andCity       = !empty($city) ? "and d.city='{$city}'" : "";
            $andSchoolName = !empty($SchoolName) ? "and d.SchoolName='{$SchoolName}'" : "";

            $sql = "select a.* , b.* from " . $xoopsDB->prefix("tad_web_files") . "  as a left join " . $xoopsDB->prefix("tad_web_files_center") . " as b on a.fsn=b.col_sn and b.col_name='fsn' left join " . $xoopsDB->prefix("tad_web") . " as c on a.WebID=c.WebID left join " . $xoopsDB->prefix("apply") . " as d on c.WebOwnerUid=d.uid where c.`WebEnable`='1' $andCounty $andCity $andSchoolName order by a.file_date desc";
        } elseif (!empty($tag)) {
            $sql = "select a.* , b.* from " . $xoopsDB->prefix("tad_web_files") . " as a left join " . $xoopsDB->prefix("tad_web_files_center") . " as b on a.fsn=b.col_sn  and b.col_name='fsn' left join " . $xoopsDB->prefix("tad_web") . " as c on a.WebID=c.WebID join " . $xoopsDB->prefix("tad_web_tags") . " as d on d.col_name='fsn' and d.col_sn=a.fsn where c.`WebEnable`='1' $andWebID $andCateID order by a.file_date desc";

        } else {
            $sql = "select a.* , b.* from " . $xoopsDB->prefix("tad_web_files") . " as a left join " . $xoopsDB->prefix("tad_web_files_center") . " as b on a.fsn=b.col_sn  and b.col_name='fsn' left join " . $xoopsDB->prefix("tad_web") . " as c on a.WebID=c.WebID where c.`WebEnable`='1' $andWebID $andCateID order by a.file_date desc";
        }
        $to_limit = empty($limit) ? 20 : $limit;

        //getPageBar($原sql語法, 每頁顯示幾筆資料, 最多顯示幾個頁數選項);
        $PageBar = getPageBar($sql, $to_limit, 10);
        $bar     = $PageBar['bar'];
        $sql     = $PageBar['sql'];
        $total   = $PageBar['total'];

        $result = $xoopsDB->query($sql) or web_error($sql);

        $main_data = "";

        $i        = 0;
        $need_del = $no_need_del = array();

        $Webs = getAllWebInfo();

        $this->web_cate->set_WebID($this->WebID);
        $cate = $this->web_cate->get_tad_web_cate_arr();
        // die(var_export($cate));
        while ($all = $xoopsDB->fetchArray($result)) {

            //以下會產生這些變數： $fsn , $uid , $CateID , $file_date  , $WebID
            foreach ($all as $k => $v) {
                $$k = $v;
            }

            //偵測是否有已刪儲檔案，但tad_web_files未刪的檔案
            if (!empty($fsn) and empty($files_sn) and empty($file_link)) {
                $need_del[$fsn] = $fsn;
            } else {
                $no_need_del[$fsn] = $fsn;
            }

            $main_data[$i] = $all;

            $main_data[$i]['cate']     = isset($cate[$CateID]) ? $cate[$CateID] : '';
            $main_data[$i]['WebTitle'] = "<a href='index.php?WebID={$WebID}'>{$Webs[$WebID]}</a>";
            $main_data[$i]['isMyWeb']  = in_array($WebID, $MyWebs) ? 1 : 0;

            $uid_name  = XoopsUser::getUnameFromId($uid, 1);
            $file_date = substr($file_date, 0, 10);

            $showurl = empty($file_link) ? "<a href='" . XOOPS_URL . "/modules/tad_web/files.php?WebID={$WebID}&op=tufdl&files_sn=$files_sn#{$original_filename}' class='iconize'>{$description}</a>" : "<a href='{$file_link}' class='iconize'>{$file_description}</a>";

            $main_data[$i]['showurl']  = $showurl;
            $main_data[$i]['uid_name'] = $uid_name;
            $i++;
        }

        //可愛刪除
        if (!file_exists(XOOPS_ROOT_PATH . "/modules/tadtools/sweet_alert.php")) {
            redirect_header("index.php", 3, _MA_NEED_TADTOOLS);
        }
        include_once XOOPS_ROOT_PATH . "/modules/tadtools/sweet_alert.php";
        $sweet_alert = new sweet_alert();
        $sweet_alert->render("delete_files_func", "files.php?op=delete&WebID={$this->WebID}&fsn={$fsn}&files_sn=", 'files_sn');

        //刪除檔案
        if (is_array($need_del)) {
            foreach ($need_del as $fsn) {
                if (!in_array($fsn, $no_need_del)) {
                    $this->delete($fsn);
                }
            }
        }

        if ($mode == "return") {
            $data['main_data'] = $main_data;
            $data['total']     = $total;
            return $data;
        } else {
            $xoopsTpl->assign('file_data', $main_data);
            $xoopsTpl->assign('bar', $bar);
            $xoopsTpl->assign('files', get_db_plugin($this->WebID, 'files'));
            return $total;
        }

    }

    //以流水號秀出某筆tad_web_file資料內容
    public function show_one($fsn = "")
    {
    }

    //tad_web_files編輯表單
    public function edit_form($fsn = "", $WebID = "")
    {
        global $xoopsDB, $xoopsUser, $MyWebs, $isMyWeb, $xoopsTpl, $TadUpFiles, $plugin_menu_var;

        if (!$isMyWeb and $MyWebs) {
            redirect_header($_SERVER['PHP_SELF'] . "?WebID={$MyWebs[0]}&op=edit_form", 3, _MD_TCW_AUTO_TO_HOME);
        } elseif (!$isMyWeb) {
            redirect_header("index.php", 3, _MD_TCW_NOT_OWNER);
        }
        get_quota($this->WebID);

        //抓取預設值
        if (!empty($fsn)) {
            $DBV = $this->get_one_data($fsn);
        } else {
            $DBV = array();
        }

        //預設值設定

        //設定「fsn」欄位預設值
        $fsn = (!isset($DBV['fsn'])) ? "" : $DBV['fsn'];
        $xoopsTpl->assign('fsn', $fsn);

        //設定「uid」欄位預設值
        $user_uid = ($xoopsUser) ? $xoopsUser->getVar('uid') : "";

        $uid = (!isset($DBV['uid'])) ? $user_uid : $DBV['uid'];
        $xoopsTpl->assign('uid', $uid);

        //設定「file_date」欄位預設值
        $file_date = (!isset($DBV['file_date'])) ? date("Y-m-d H:i:s") : $DBV['file_date'];

        //設定「WebID」欄位預設值
        $WebID = (!isset($DBV['WebID'])) ? $this->WebID : $DBV['WebID'];
        $xoopsTpl->assign('WebID', $WebID);

        //設定「CateID」欄位預設值
        $CateID = (!isset($DBV['CateID'])) ? "" : $DBV['CateID'];
        $this->web_cate->set_button_value($plugin_menu_var['files']['short'] . _MD_TCW_CATE_TOOLS);
        $this->web_cate->set_default_option_text(sprintf(_MD_TCW_SELECT_PLUGIN_CATE, $plugin_menu_var['files']['short']));
        $cate_menu = $this->web_cate->cate_menu($CateID);
        $xoopsTpl->assign('cate_menu_form', $cate_menu);

        //設定「file_link」欄位預設值
        $file_link = (!isset($DBV['file_link'])) ? '' : $DBV['file_link'];
        $xoopsTpl->assign('file_link', $file_link);

        //設定「file_description」欄位預設值
        $file_description = (!isset($DBV['file_description'])) ? '' : $DBV['file_description'];
        $xoopsTpl->assign('file_description', $file_description);

        $op = (empty($fsn)) ? "insert" : "update";

        $xoopsTpl->assign('next_op', $op);

        // $subdir = isset($this->WebID) ? "/{$this->WebID}" : "";
        // $TadUpFiles->set_dir('subdir', $subdir);
        $TadUpFiles->set_col("fsn", $fsn);

        $upform = $TadUpFiles->upform(true, 'upfile', '1', true);
        $xoopsTpl->assign('upform', $upform);

        //套用formValidator驗證機制
        if (!file_exists(TADTOOLS_PATH . "/formValidator.php")) {
            redirect_header("index.php", 3, _TAD_NEED_TADTOOLS);
        }
        include_once TADTOOLS_PATH . "/formValidator.php";
        $formValidator = new formValidator("#myForm", true);
        $formValidator->render();

        $tags_form = $this->tags->tags_menu("fsn", $fsn);
        $xoopsTpl->assign('tags_form', $tags_form);

    }

    //新增資料到tad_web_files中
    public function insert()
    {
        global $xoopsDB, $xoopsUser, $TadUpFiles;

        //取得使用者編號
        $uid = ($xoopsUser) ? $xoopsUser->getVar('uid') : "";

        $myts = &MyTextSanitizer::getInstance();

        $CateID           = intval($_POST['CateID']);
        $WebID            = intval($_POST['WebID']);
        $file_link        = $myts->addSlashes($_POST['file_link']);
        $file_description = $myts->addSlashes($_POST['file_description']);

        $CateID = $this->web_cate->save_tad_web_cate($CateID, $_POST['newCateName']);

        $sql = "insert into " . $xoopsDB->prefix("tad_web_files") . "
          (`uid` , `CateID` , `file_date`  , `WebID` , `file_link` , `file_description`)
          values('{$uid}' , '{$CateID}' , now()  , '{$WebID}' , '{$file_link}' , '{$file_description}')";

        $xoopsDB->query($sql) or web_error($sql);

        //取得最後新增資料的流水編號
        $fsn = $xoopsDB->getInsertId();
        if ($_POST['file_method'] == "upload_file") {
            $TadUpFiles->set_col('fsn', $fsn);
            $TadUpFiles->upload_file('upfile', 640, null, null, null, true);
            check_quota($this->WebID);

        }

        //儲存標籤
        $this->tags->save_tags("fsn", $fsn, $_POST['tag_name'], $_POST['tags']);
        return $fsn;
    }

    //更新tad_web_files某一筆資料
    public function update($fsn = "")
    {
        global $xoopsDB, $TadUpFiles;

        $myts = &MyTextSanitizer::getInstance();

        $CateID           = intval($_POST['CateID']);
        $WebID            = intval($_POST['WebID']);
        $file_link        = $myts->addSlashes($_POST['file_link']);
        $file_description = $myts->addSlashes($_POST['file_description']);

        $CateID = $this->web_cate->save_tad_web_cate($CateID, $_POST['newCateName']);
        // die('CateID=' . $CateID);
        $anduid = onlyMine();

        $sql = "update " . $xoopsDB->prefix("tad_web_files") . " set
        `CateID` = '{$CateID}' ,
        `file_date` = now() ,
        `WebID` = '{$WebID}' ,
        `file_link` = '{$file_link}' ,
        `file_description` = '{$file_description}'
        where fsn='$fsn' $anduid";
        // die($sql);
        $xoopsDB->queryF($sql) or web_error($sql);

        if ($_POST['file_method'] == "upload_file") {
            $TadUpFiles->set_col('fsn', $fsn);
            $TadUpFiles->upload_file('upfile', 640, null, null, null, true);
            check_quota($this->WebID);
        }

        //儲存標籤
        $this->tags->save_tags("fsn", $fsn, $_POST['tag_name'], $_POST['tags']);
        return $fsn;
    }

    //刪除tad_web_files某筆資料資料
    public function delete($fsn = "")
    {
        global $xoopsDB, $TadUpFiles;
        $anduid = onlyMine();
        $sql    = "delete from " . $xoopsDB->prefix("tad_web_files") . " where fsn='$fsn' $anduid";
        $xoopsDB->queryF($sql) or web_error($sql);

        $TadUpFiles->set_col('fsn', $fsn);
        $TadUpFiles->del_files();
        check_quota($this->WebID);
        //刪除標籤
        $this->tags->delete_tags("fsn", $fsn);
    }

    //刪除所有資料
    public function delete_all()
    {
        global $xoopsDB, $TadUpFiles;
        $allCateID = array();
        $sql       = "select fsn,CateID from " . $xoopsDB->prefix("tad_web_files") . " where WebID='{$this->WebID}'";
        $result    = $xoopsDB->queryF($sql) or web_error($sql);
        while (list($fsn, $CateID) = $xoopsDB->fetchRow($result)) {
            $this->delete($fsn);
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
        $sql         = "select count(*) from " . $xoopsDB->prefix("tad_web_files") . " where WebID='{$this->WebID}'";
        $result      = $xoopsDB->query($sql) or web_error($sql);
        list($count) = $xoopsDB->fetchRow($result);
        return $count;
    }

    //以流水號取得某筆tad_web_files資料
    public function get_one_data($fsn = "")
    {
        global $xoopsDB;
        if (empty($fsn)) {
            return;
        }

        $sql    = "select * from " . $xoopsDB->prefix("tad_web_files") . " where fsn='$fsn'";
        $result = $xoopsDB->query($sql) or web_error($sql);
        $data   = $xoopsDB->fetchArray($result);
        return $data;
    }

    //匯出資料
    public function export_data($start_date, $end_date, $CateID = "")
    {

        global $xoopsDB, $xoopsTpl, $TadUpFiles, $MyWebs;
        $andCateID = empty($CateID) ? "" : "and `CateID`='$CateID'";
        $andStart  = empty($start_date) ? "" : "and file_date >= '{$start_date}'";
        $andEnd    = empty($end_date) ? "" : "and file_date <= '{$end_date}'";

        $sql    = "select fsn,file_description,file_date,CateID from " . $xoopsDB->prefix("tad_web_files") . " where WebID='{$this->WebID}' {$andStart} {$andEnd} {$andCateID} order by file_date";
        $result = $xoopsDB->query($sql) or web_error($sql);

        $i         = 0;
        $main_data = '';
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
