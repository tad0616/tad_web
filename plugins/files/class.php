<?php
use Xmf\Request;
use XoopsModules\Tadtools\FormValidator;
use XoopsModules\Tadtools\SweetAlert;
use XoopsModules\Tadtools\Utility;
use XoopsModules\Tad_web\Power;
use XoopsModules\Tad_web\Tags;
use XoopsModules\Tad_web\WebCate;

class tad_web_files
{
    public $WebID = 0;
    public $WebCate;
    public $tags;
    public $Power;

    public function __construct($WebID)
    {
        $this->WebID = $WebID;
        $this->WebCate = new WebCate($WebID, 'files', 'tad_web_files');
        $this->tags = new Tags($WebID);
        $this->Power = new Power($WebID);
    }

    //檔案下載
    public function list_all($CateID = '', $limit = '', $mode = 'assign', $tag = '')
    {
        global $xoopsDB, $xoopsTpl, $MyWebs, $plugin_menu_var, $isMyWeb;

        $power = $this->Power->check_power("read", "CateID", $CateID, 'files');
        if (!$power) {
            redirect_header("files.php?WebID={$this->WebID}", 3, _MD_TCW_NOW_READ_POWER);
        }

        $andWebID = (empty($this->WebID)) ? '' : "and a.WebID='{$this->WebID}'";

        $andCateID = '';
        if ('assign' === $mode) {
            //取得tad_web_cate所有資料陣列
            if (!empty($plugin_menu_var)) {
                $this->WebCate->set_button_value($plugin_menu_var['files']['short'] . _MD_TCW_CATE_TOOLS);
                $this->WebCate->set_default_option_text(sprintf(_MD_TCW_SELECT_PLUGIN_CATE, $plugin_menu_var['files']['short']));
                $this->WebCate->set_col_md(0, 6);
                $cate_menu = $this->WebCate->cate_menu($CateID, 'page', false, true, false, false);
                $xoopsTpl->assign('cate_menu', $cate_menu);
            }

            if (!empty($CateID) and is_numeric($CateID)) {
                //取得單一分類資料
                $cate = $this->WebCate->get_tad_web_cate($CateID);
                if ($CateID and '1' != $cate['CateEnable']) {
                    return;
                }
                $xoopsTpl->assign('cate', $cate);
                $andCateID = "and a.`CateID`='$CateID'";
                $xoopsTpl->assign('FilesDefCateID', $CateID);
            }
        }

        $title = '';

        if (_IS_EZCLASS and !empty($_GET['county'])) {
            //https://class.tn.edu.tw/modules/tad_web/index.php?county=臺南市&city=永康區&SchoolName=XX國小

            $county = Request::getString('county');
            $city = Request::getString('city');
            $SchoolName = Request::getString('SchoolName');
            $andCounty = !empty($county) ? "and d.county='{$county}'" : '';
            $andCity = !empty($city) ? "and d.city='{$city}'" : '';
            $andSchoolName = !empty($SchoolName) ? "and d.SchoolName='{$SchoolName}'" : '';

            $sql = 'select a.* , b.files_sn, b.col_name, b.col_sn, b.sort, b.kind, b.file_name, b.file_type, b.file_size, b.description, b.counter, b.original_filename, b.hash_filename, b.sub_dir from ' . $xoopsDB->prefix('tad_web_files') . '  as a
            left join ' . $xoopsDB->prefix('tad_web_files_center') . " as b on a.fsn=b.col_sn and b.col_name='fsn'
            left join " . $xoopsDB->prefix('tad_web') . ' as c on a.WebID=c.WebID
            left join ' . $xoopsDB->prefix('apply') . ' as d on c.WebOwnerUid=d.uid
            left join ' . $xoopsDB->prefix('tad_web_cate') . " as e on a.CateID=e.CateID
            where c.`WebEnable`='1' and (e.CateEnable='1' or a.CateID='0') $andCounty $andCity $andSchoolName
            order by a.file_date desc";
        } elseif (!empty($tag)) {
            $sql = 'select distinct a.* , b.files_sn, b.col_name, b.col_sn, b.sort, b.kind, b.file_name, b.file_type, b.file_size, b.description, b.counter, b.original_filename, b.hash_filename, b.sub_dir from ' . $xoopsDB->prefix('tad_web_files') . ' as a
            left join ' . $xoopsDB->prefix('tad_web_files_center') . " as b on a.fsn=b.col_sn  and b.col_name='fsn'
            left join " . $xoopsDB->prefix('tad_web') . ' as c on a.WebID=c.WebID
            join ' . $xoopsDB->prefix('tad_web_tags') . " as d on d.col_name='fsn' and d.col_sn=a.fsn
            left join " . $xoopsDB->prefix('tad_web_cate') . " as e on a.CateID=e.CateID
            where c.`WebEnable`='1' and (e.CateEnable='1' or a.CateID='0') $andWebID $andCateID
            order by a.file_date desc";
        } else {
            $sql = 'select a.* , b.files_sn, b.col_name, b.col_sn, b.sort, b.kind, b.file_name, b.file_type, b.file_size, b.description, b.counter, b.original_filename, b.hash_filename, b.sub_dir from ' . $xoopsDB->prefix('tad_web_files') . ' as a
            left join ' . $xoopsDB->prefix('tad_web_files_center') . " as b on a.fsn=b.col_sn  and b.col_name='fsn'
            left join " . $xoopsDB->prefix('tad_web') . ' as c on a.WebID=c.WebID
            left join ' . $xoopsDB->prefix('tad_web_cate') . " as d on a.CateID=d.CateID
            where c.`WebEnable`='1' and (d.CateEnable='1' or a.CateID='0') $andWebID $andCateID
            order by a.file_date desc";
        }
        $to_limit = empty($limit) ? 20 : $limit;
        // if ($this->WebID == 10) {
        //     die($sql);
        // }
        //getPageBar($原sql語法, 每頁顯示幾筆資料, 最多顯示幾個頁數選項);
        $PageBar = Utility::getPageBar($sql, $to_limit, 10);
        $bar = $PageBar['bar'];
        $sql = $PageBar['sql'];
        $total = $PageBar['total'];

        $result = $xoopsDB->query($sql) or Utility::web_error($sql, __FILE__, __LINE__);

        $main_data = $data = [];

        $i = 0;
        $need_del = $no_need_del = [];

        $Webs = getAllWebInfo();

        $this->WebCate->set_WebID($this->WebID);
        $cate = $this->WebCate->get_tad_web_cate_arr(null, null, 'files');
        // die(var_export($cate));
        while (false !== ($all = $xoopsDB->fetchArray($result))) {
            //以下會產生這些變數： $fsn , $uid , $CateID , $file_date  , $WebID
            foreach ($all as $k => $v) {
                $$k = $v;
            }

            $power = $this->Power->check_power("read", "CateID", $CateID, 'files');
            if (!$power) {
                continue;
            }

            //偵測是否有已刪儲檔案，但tad_web_files未刪的檔案
            if (!empty($fsn) and empty($files_sn) and empty($file_link)) {
                $need_del[$fsn] = $fsn;
            } else {
                $no_need_del[$fsn] = $fsn;
            }

            $main_data[$i] = $all;
            $main_data[$i]['id'] = $fsn;
            $main_data[$i]['id_name'] = 'fsn';
            $main_data[$i]['title'] = $file_description;
            // $main_data[$i]['isAssistant'] = is_assistant($this->WebID, 'files', $CateID, 'fsn', $fsn);

            $main_data[$i]['isCanEdit'] = isCanEdit($this->WebID, 'files', $CateID, 'fsn', $fsn);

            $main_data[$i]['cate'] = isset($cate[$CateID]) ? $cate[$CateID] : '';
            $main_data[$i]['WebTitle'] = "<a href='index.php?WebID={$WebID}'>{$Webs[$WebID]}</a>";
            // $main_data[$i]['isMyWeb']  = in_array($WebID, $MyWebs) ? 1 : 0;
            $main_data[$i]['isMyWeb'] = $isMyWeb;

            $uid_name = \XoopsUser::getUnameFromId($uid, 1);
            if (empty($uid_name)) {
                $uid_name = \XoopsUser::getUnameFromId($uid, 0);
            }

            $file_date = mb_substr($file_date, 0, 10);
            $showurl = '';
            if (!empty($file_link)) {
                $file_description = empty($file_description) ? $file_link : $file_description;
                $showurl = "<a href='{$file_link}' class='iconize'>{$file_description}</a>";
            } elseif (!empty($files_sn)) {
                $description = empty($description) ? $original_filename : $description;
                $showurl = "<a href='" . XOOPS_URL . "/modules/tad_web/files.php?WebID={$WebID}&op=tufdl&files_sn=$files_sn#{$original_filename}' class='iconize'>{$description}</a>";
            }

            $main_data[$i]['showurl'] = $showurl;
            $main_data[$i]['uid_name'] = $uid_name;
            $main_data[$i]['files_sn'] = $files_sn;
            $i++;
        }

        $SweetAlert = new SweetAlert();
        $SweetAlert->render('delete_files_func', "files.php?op=delete&WebID={$this->WebID}&fsn=", 'fsn');

        //刪除檔案
        if (is_array($need_del)) {
            foreach ($need_del as $fsn) {
                if (!in_array($fsn, $no_need_del)) {
                    $this->delete($fsn);
                }
            }
        }

        if ('return' === $mode) {
            $data['main_data'] = $main_data;
            $data['total'] = $total;
            $data['isCanEdit'] = isCanEdit($this->WebID, 'files', $CateID, 'fsn', $fsn);

            return $data;
        }
        $xoopsTpl->assign('file_data', $main_data);
        $xoopsTpl->assign('bar', $bar);
        $xoopsTpl->assign('files', get_db_plugin($this->WebID, 'files'));
        $xoopsTpl->assign('isCanEdit', isCanEdit($this->WebID, 'files', $CateID, 'fsn', $fsn));

        return $total;
    }

    //以流水號秀出某筆tad_web_file資料內容
    public function show_one($fsn = '')
    {
    }

    //tad_web_files編輯表單
    public function edit_form($fsn = '', $WebID = '')
    {
        global $xoTheme, $xoopsUser, $xoopsTpl, $TadUpFiles, $plugin_menu_var;

        $xoTheme->addScript('modules/tadtools/My97DatePicker/WdatePicker.js');
        if (isset($_SESSION['isAssistant']['files'])) {
            chk_self_web($this->WebID, $_SESSION['isAssistant']['files']);
        } else {
            chk_self_web($this->WebID);
        }

        get_quota($this->WebID);

        //抓取預設值
        if (!empty($fsn)) {
            $DBV = $this->get_one_data($fsn);
        } else {
            $DBV = [];
        }

        //預設值設定

        //設定「fsn」欄位預設值
        $fsn = (!isset($DBV['fsn'])) ? '' : $DBV['fsn'];
        $xoopsTpl->assign('fsn', $fsn);

        //設定「uid」欄位預設值
        $user_uid = ($xoopsUser) ? $xoopsUser->uid() : '';

        $uid = (!isset($DBV['uid'])) ? $user_uid : $DBV['uid'];
        $xoopsTpl->assign('uid', $uid);

        //設定「file_date」欄位預設值
        $file_date = (!isset($DBV['file_date'])) ? date('Y-m-d H:i:s') : $DBV['file_date'];

        //設定「WebID」欄位預設值
        $WebID = (!isset($DBV['WebID'])) ? $this->WebID : $DBV['WebID'];
        $xoopsTpl->assign('WebID', $WebID);

        //設定「CateID」欄位預設值
        $DefCateID = isset($_SESSION['isAssistant']['files']) ? $_SESSION['isAssistant']['files'] : '';
        $CateID = (!isset($DBV['CateID'])) ? $DefCateID : $DBV['CateID'];
        $this->WebCate->set_button_value($plugin_menu_var['files']['short'] . _MD_TCW_CATE_TOOLS);
        $this->WebCate->set_default_option_text(sprintf(_MD_TCW_SELECT_PLUGIN_CATE, $plugin_menu_var['files']['short']));
        $cate_menu = isset($_SESSION['isAssistant']['files']) ? $this->WebCate->hidden_cate_menu($CateID) : $this->WebCate->cate_menu($CateID);
        $xoopsTpl->assign('cate_menu_form', $cate_menu);

        //設定「file_link」欄位預設值
        $file_link = (!isset($DBV['file_link'])) ? '' : $DBV['file_link'];
        $xoopsTpl->assign('file_link', $file_link);

        //設定「file_description」欄位預設值
        $file_description = (!isset($DBV['file_description'])) ? '' : $DBV['file_description'];
        $xoopsTpl->assign('file_description', $file_description);

        $op = (empty($fsn)) ? 'insert' : 'update';

        $xoopsTpl->assign('next_op', $op);

        // $subdir = isset($this->WebID) ? "/{$this->WebID}" : "";
        // $TadUpFiles->set_dir('subdir', $subdir);
        $TadUpFiles->set_col('fsn', $fsn);

        $upform = $TadUpFiles->upform(true, 'upfile', '1', true);
        $xoopsTpl->assign('upform', $upform);

        $FormValidator = new FormValidator('#myForm', true);
        $FormValidator->render();

        $tags_form = $this->tags->tags_menu('fsn', $fsn);
        $xoopsTpl->assign('tags_form', $tags_form);
    }

    //新增資料到tad_web_files中
    public function insert()
    {
        global $xoopsDB, $xoopsUser, $TadUpFiles, $WebOwnerUid;
        if (isset($_SESSION['isAssistant']['files'])) {
            $uid = $WebOwnerUid;
        } else {
            $uid = ($xoopsUser) ? $xoopsUser->uid() : '';
        }

        $CateID = (int) $_POST['CateID'];
        $WebID = (int) $_POST['WebID'];
        $file_link = (string) $_POST['file_link'];
        $file_description = (string) $_POST['file_description'];
        $newCateName = (string) $_POST['newCateName'];
        $file_method = (string) $_POST['file_method'];
        $tag_name = (string) $_POST['tag_name'];
        if ($newCateName != '') {
            $CateID = $this->WebCate->save_tad_web_cate($CateID, $newCateName);
        }

        $sql = 'INSERT INTO `' . $xoopsDB->prefix('tad_web_files') . '` (`uid`, `CateID`, `file_date`, `WebID`, `file_link`, `file_description`) VALUES (?, ?, NOW(), ?, ?, ?)';
        Utility::query($sql, 'iiiss', [$uid, $CateID, $WebID, $file_link, $file_description]) or Utility::web_error($sql, __FILE__, __LINE__);

        //取得最後新增資料的流水編號
        $fsn = $xoopsDB->getInsertId();
        save_assistant_post($WebID, 'files', $CateID, 'fsn', $fsn);

        if ('upload_file' === $file_method) {
            $TadUpFiles->set_col('fsn', $fsn);
            $TadUpFiles->upload_file('upfile', 640, null, null, null, true);
            check_quota($this->WebID);
        }

        //儲存標籤
        $this->tags->save_tags('fsn', $fsn, $tag_name, $_POST['tags']);

        return $fsn;
    }

    //更新tad_web_files某一筆資料
    public function update($fsn = '')
    {
        global $xoopsDB, $TadUpFiles;

        $CateID = (int) $_POST['CateID'];
        $WebID = (int) $_POST['WebID'];
        $file_link = (string) $_POST['file_link'];
        $file_description = (string) $_POST['file_description'];
        $newCateName = (string) $_POST['newCateName'];
        $file_method = (string) $_POST['file_method'];
        $tag_name = (string) $_POST['tag_name'];
        if ($newCateName != '') {
            $CateID = $this->WebCate->save_tad_web_cate($CateID, $newCateName);
        }

        $and_uid = '';
        if (!is_assistant($this->WebID, 'files', $CateID, 'fsn', $fsn)) {
            $and_uid = onlyMine();
        }

        $sql = 'UPDATE `' . $xoopsDB->prefix('tad_web_files') . '` SET `CateID` = ? , `file_date` = NOW() , `WebID` = ? , `file_link` = ? , `file_description` = ? WHERE `fsn`=? ' . $and_uid;
        Utility::query($sql, 'iissi', [$CateID, $WebID, $file_link, $file_description, $fsn]) or Utility::web_error($sql, __FILE__, __LINE__);

        if ('upload_file' === $file_method) {
            $TadUpFiles->set_col('fsn', $fsn);
            $TadUpFiles->upload_file('upfile', 640, null, null, null, true);
            check_quota($this->WebID);
        }

        //儲存標籤
        $this->tags->save_tags('fsn', $fsn, $tag_name, $_POST['tags']);

        return $fsn;
    }

    //刪除tad_web_files某筆資料資料
    public function delete($fsn = '', $files_sn = '')
    {
        global $xoopsDB, $TadUpFiles;
        $sql = 'SELECT `CateID` FROM `' . $xoopsDB->prefix('tad_web_files') . '` WHERE `fsn`=?';
        $result = Utility::query($sql, 'i', [$fsn]) or Utility::web_error($sql, __FILE__, __LINE__);
        list($CateID) = $xoopsDB->fetchRow($result);

        $and_uid = '';
        if (!is_assistant($this->WebID, 'files', $CateID, 'fsn', $fsn)) {
            $and_uid = onlyMine();
        }
        if (empty($fsn) and !empty($files_sn)) {
            $file = $TadUpFiles->get_one_file($files_sn);
            $fsn = $file['col_sn'];
        }

        $sql = 'DELETE FROM `' . $xoopsDB->prefix('tad_web_files') . '` WHERE `fsn`=? ' . $and_uid;
        Utility::query($sql, 's', [$fsn]) or Utility::web_error($sql, __FILE__, __LINE__);

        $TadUpFiles->set_col('fsn', $fsn);

        $TadUpFiles->del_files();
        check_quota($this->WebID);
        //刪除標籤
        $this->tags->delete_tags('fsn', $fsn);
    }

    //刪除所有資料
    public function delete_all()
    {
        global $xoopsDB;
        $allCateID = [];
        $sql = 'SELECT `fsn`,`CateID` FROM `' . $xoopsDB->prefix('tad_web_files') . '` WHERE `WebID`=?';
        $result = Utility::query($sql, 'i', [$this->WebID]) or Utility::web_error($sql, __FILE__, __LINE__);
        while (list($fsn, $CateID) = $xoopsDB->fetchRow($result)) {
            $this->delete($fsn);
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
        $sql = 'SELECT COUNT(*) FROM `' . $xoopsDB->prefix('tad_web_files') . '` WHERE `WebID` =?';
        $result = Utility::query($sql, 'i', [$this->WebID]) or Utility::web_error($sql, __FILE__, __LINE__);
        list($count) = $xoopsDB->fetchRow($result);

        return $count;
    }

    //以流水號取得某筆tad_web_files資料
    public function get_one_data($fsn = '')
    {
        global $xoopsDB;
        if (empty($fsn)) {
            return;
        }

        $sql = 'SELECT * FROM `' . $xoopsDB->prefix('tad_web_files') . '` WHERE `fsn`=?';
        $result = Utility::query($sql, 'i', [$fsn]) or Utility::web_error($sql, __FILE__, __LINE__);
        $data = $xoopsDB->fetchArray($result);

        return $data;
    }

    //匯出資料
    public function export_data($start_date, $end_date, $CateID = '')
    {
        global $xoopsDB;
        $andCateID = empty($CateID) ? '' : "AND `CateID`='$CateID'";
        $andStart = empty($start_date) ? '' : "AND `file_date` >= '{$start_date}'";
        $andEnd = empty($end_date) ? '' : "AND `file_date` <= '{$end_date}'";

        $sql = 'SELECT `fsn`, `file_description`, `file_date`, `CateID` FROM `' . $xoopsDB->prefix('tad_web_files') . '` WHERE `WebID`= ? ' . $andStart . ' ' . $andEnd . ' ' . $andCateID . ' ORDER BY `file_date`';
        $result = Utility::query($sql, 'i', [$this->WebID]) or Utility::web_error($sql, __FILE__, __LINE__);

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
}
