<?php
use Xmf\Request;
use XoopsModules\Tadtools\CkEditor;
use XoopsModules\Tadtools\FormValidator;
use XoopsModules\Tadtools\JqueryPrintPreview;
use XoopsModules\Tadtools\SweetAlert;
use XoopsModules\Tadtools\Utility;
use XoopsModules\Tadtools\Wcag;
use XoopsModules\Tad_web\Power;
use XoopsModules\Tad_web\Tags;
use XoopsModules\Tad_web\WebCate;

class tad_web_news
{
    public $WebID = 0;
    public $WebCate;
    public $setup;
    public $tags;
    public $Power;

    public function __construct($WebID)
    {
        $this->WebID = $WebID;
        $this->WebCate = new WebCate($WebID, 'news', 'tad_web_news');
        $this->Power = new Power($WebID);
        $this->tags = new Tags($WebID);
        $this->setup = get_plugin_setup_values($WebID, 'news');
    }

    //最新消息
    public function list_all($CateID = '', $limit = null, $mode = 'assign', $tag = '')
    {
        global $xoopsDB, $xoopsTpl, $isMyWeb, $plugin_menu_var;

        $power = $this->Power->check_power("read", "CateID", $CateID, 'news');
        if (!$power) {
            redirect_header("news.php?WebID={$this->WebID}", 3, _MD_TCW_NOW_READ_POWER);
        }

        $andWebID = (empty($this->WebID)) ? '' : "and a.WebID='{$this->WebID}'";

        $andCateID = '';
        if ('assign' === $mode) {
            //取得tad_web_cate所有資料陣列
            if (!empty($plugin_menu_var)) {
                $this->WebCate->set_button_value($plugin_menu_var['news']['short'] . _MD_TCW_CATE_TOOLS);
                $this->WebCate->set_default_option_text(sprintf(_MD_TCW_SELECT_PLUGIN_CATE, $plugin_menu_var['news']['short']));
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
                $xoopsTpl->assign('NewsDefCateID', $CateID);
            }
            $andEnable = $isMyWeb ? '' : "and a.`NewsEnable`='1'";
        } else {
            $andEnable = "and a.`NewsEnable`='1'";
        }

        if (_IS_EZCLASS and !empty($_GET['county'])) {
            //https://class.tn.edu.tw/modules/tad_web/index.php?county=臺南市&city=永康區&SchoolName=XX國小

            $county = Request::getString('county');
            $city = Request::getString('city');
            $SchoolName = Request::getString('SchoolName');
            $andCounty = !empty($county) ? "and c.county='{$county}'" : '';
            $andCity = !empty($city) ? "and c.city='{$city}'" : '';
            $andSchoolName = !empty($SchoolName) ? "and c.SchoolName='{$SchoolName}'" : '';

            $sql = 'select a.* from ' . $xoopsDB->prefix('tad_web_news') . ' as a
            left join ' . $xoopsDB->prefix('tad_web') . ' as b on a.WebID=b.WebID
            left join ' . $xoopsDB->prefix('apply') . ' as c on b.WebOwnerUid=c.uid
            left join ' . $xoopsDB->prefix('tad_web_cate') . " as d on a.CateID=d.CateID
            where b.`WebEnable`='1' and (d.CateEnable='1' or a.CateID='0') {$andEnable} $andCounty $andCity $andSchoolName
            order by a.NewsDate desc";
        } elseif (!empty($tag)) {
            $sql = 'select distinct a.* from ' . $xoopsDB->prefix('tad_web_news') . ' as a
            left join ' . $xoopsDB->prefix('tad_web') . ' as b on a.WebID=b.WebID
            join ' . $xoopsDB->prefix('tad_web_tags') . " as c on c.col_name='NewsID' and c.col_sn=a.NewsID
            left join " . $xoopsDB->prefix('tad_web_cate') . " as d on a.CateID=d.CateID
            where b.`WebEnable`='1' and (d.CateEnable='1' or a.CateID='0') and c.`tag_name`='{$tag}' {$andEnable} $andWebID $andCateID
            order by a.NewsDate desc";
        } else {
            if (empty($this->WebID)) {
                return;
            }

            $sql = 'select a.* from ' . $xoopsDB->prefix('tad_web_news') . ' as a
            left join ' . $xoopsDB->prefix('tad_web') . ' as b on a.WebID=b.WebID
            left join ' . $xoopsDB->prefix('tad_web_cate') . " as c on a.CateID=c.CateID
            where b.`WebEnable`='1' and (c.CateEnable='1' or a.CateID='0') {$andEnable} $andWebID $andCateID
            order by a.NewsDate desc";
        }

        $to_limit = empty($limit) ? 10 : $limit;

        //getPageBar($原sql語法, 每頁顯示幾筆資料, 最多顯示幾個頁數選項);
        $PageBar = Utility::getPageBar($sql, $to_limit, 10);
        $bar = $PageBar['bar'];
        $sql = $PageBar['sql'];
        $total = $PageBar['total'];

        $result = $xoopsDB->query($sql) or Utility::web_error($sql, __FILE__, __LINE__);

        $main_data = [];

        $i = 0;

        $Webs = getAllWebInfo();

        $cate = $this->WebCate->get_tad_web_cate_arr(null, null, 'news');

        while ($all = $xoopsDB->fetchArray($result)) {
            //以下會產生這些變數： $NewsID , $NewsTitle , $NewsContent , $NewsDate , $toCal  , $NewsUrl , $WebID  , $NewsCounter , $NewsEnable
            foreach ($all as $k => $v) {
                $$k = $v;
            }

            //檢查權限
            $power = $this->Power->check_power('read', 'NewsID', $NewsID);
            if (!$power) {
                continue;
            }

            $power = $this->Power->check_power("read", "CateID", $CateID, 'news');
            if (!$power) {
                continue;
            }

            $main_data[$i] = $all;
            $main_data[$i]['id'] = $NewsID;
            $main_data[$i]['id_name'] = 'NewsID';
            $main_data[$i]['title'] = $NewsTitle;
            if (_IS_EZCLASS) {
                $main_data[$i]['NewsCounter'] = redis_do($this->WebID, 'get', 'news', "NewsCounter:$NewsID");
            }

            // $main_data[$i]['isAssistant'] = is_assistant($this->WebID, 'news', $CateID, 'NewsID', $NewsID);
            $main_data[$i]['isCanEdit'] = isCanEdit($this->WebID, 'news', $CateID, 'NewsID', $NewsID);

            $this->WebCate->set_WebID($WebID);

            $Content = get_article_content($NewsContent);

            if ($Content['pages'] > 1) {
                $main_data[$i]['NewsContent'] = $Content['info'];
                $main_data[$i]['more'] = true;
            } else {
                $main_data[$i]['more'] = false;
            }
            $main_data[$i]['cate'] = isset($cate[$CateID]) ? $cate[$CateID] : '';
            $main_data[$i]['WebTitle'] = "<a href='index.php?WebID={$WebID}'>{$Webs[$WebID]}</a>";

            $Date = substr($NewsDate, 0, 10);
            if (empty($NewsTitle)) {
                $NewsTitle = _MD_TCW_EMPTY_TITLE;
            }

            $main_data[$i]['NewsTitle'] = $NewsTitle;
            // $main_data[$i]['isMyWeb']  = in_array($WebID, $MyWebs) ? 1 : 0;
            $main_data[$i]['isMyWeb'] = $isMyWeb;
            $main_data[$i]['Date'] = $Date;
            $i++;
        }

        $SweetAlert = new SweetAlert();
        $SweetAlert->render('delete_news_func', "news.php?op=delete&WebID={$this->WebID}&NewsID=", 'NewsID');

        if ('return' === $mode) {
            $data['main_data'] = $main_data;
            $data['total'] = $total;
            $data['isCanEdit'] = isCanEdit($this->WebID, 'news', $CateID, 'NewsID', $NewsID);
            return $data;
        } else {
            $xoopsTpl->assign('news_data', $main_data);
            $xoopsTpl->assign('bar', $bar);
            $xoopsTpl->assign('news', get_db_plugin($this->WebID, 'news'));
            $xoopsTpl->assign('isCanEdit', isCanEdit($this->WebID, 'news', $CateID, 'NewsID', $NewsID));
            return $total;
        }
    }

    //以流水號秀出某筆tad_web_news資料內容
    public function show_one($NewsID = '', $mode = 'assign')
    {
        global $xoopsDB, $WebID, $xoopsTpl, $TadUpFiles, $isMyWeb;

        if (empty($NewsID)) {
            redirect_header("{$_SERVER['PHP_SELF']}?WebID={$this->WebID}", 3, _MD_TCW_DATA_NOT_EXIST);
        }

        $NewsID = (int) $NewsID;

        $andEnable = $isMyWeb ? '' : "AND `NewsEnable`='1'";

        $sql = 'SELECT * FROM `' . $xoopsDB->prefix('tad_web_news') . '` WHERE `NewsID`=? ' . $andEnable;
        $result = Utility::query($sql, 'i', [$NewsID]) or Utility::web_error($sql, __FILE__, __LINE__);

        $all = $xoopsDB->fetchArray($result);
        $data = $all;

        //以下會產生這些變數： $NewsID , $NewsTitle , $NewsContent , $NewsDate , $toCal  , $NewsUrl , $WebID , $NewsCounter ,$uid, $NewsEnable
        foreach ($all as $k => $v) {
            $$k = $v;
        }

        //檢查權限
        $power = $this->Power->check_power('read', 'NewsID', $NewsID);
        if (!$power) {
            redirect_header("index.php?WebID={$this->WebID}", 3, _MD_TCW_NOW_READ_POWER);
        }

        $power = $this->Power->check_power("read", "CateID", $CateID, 'news');
        if (!$power) {
            redirect_header("news.php?WebID={$this->WebID}", 3, _MD_TCW_NOW_READ_POWER);
        }

        if (_IS_EZCLASS) {
            $NewsCounter = $data['NewsCounter'] = $this->add_counter($NewsID);
        } else {
            $this->add_counter($NewsID);
        }

        $prev_next = $this->get_prev_next($NewsID);

        $xoopsTpl->assign('prev_next', $prev_next);

        $uid_name = \XoopsUser::getUnameFromId($uid, 1);
        if (empty($uid_name)) {
            $uid_name = \XoopsUser::getUnameFromId($uid, 0);
        }

        $NewsUrlTxt = empty($NewsUrl) ? '' : '<div>' . _MD_TCW_NEWSURL . _TAD_FOR . "<a href='$NewsUrl' target='_blank'>$NewsUrl</a></div>";

        $TadUpFiles->set_col('NewsID', $NewsID);
        $TadUpFiles->set_var('other_css', 'margin:6px;');
        $TadUpFiles->set_var('background_size', 'cover');
        $NewsFiles = $TadUpFiles->show_files('upfile', true, '', true, false, null, null, false, '');

        //取消換頁符號
        $pattern = "/<div style=\"page-break-after: always;?\">\s*<span style=\"display: none;?\">&nbsp;<\/span>\s*<\/div>/";
        $NewsContent = preg_replace($pattern, '', $NewsContent);

        $assistant = is_assistant($this->WebID, 'news', $CateID, 'NewsID', $NewsID);
        $isAssistant = !empty($assistant) ? true : false;
        $uid_name = $isAssistant ? "{$uid_name} <a href='#' title='由{$assistant['MemName']}代理發布'><i class='fa fa-male'></i></a>" : $uid_name;
        $xoopsTpl->assign('isAssistant', $isAssistant);

        $xoopsTpl->assign('isCanEdit', isCanEdit($this->WebID, 'news', $CateID, 'NewsID', $NewsID));

        if ('return' === $mode) {
            $data['uid_name'] = $uid_name;
            $data['NewsUrlTxt'] = $NewsUrlTxt;
            $data['NewsFiles'] = $NewsFiles;
            $data['NewsContent'] = $NewsContent;
            $data['NewsInfo'] = sprintf(_MD_TCW_INFO, $uid_name, $NewsDate, $NewsCounter);
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
        $cate = $this->WebCate->get_tad_web_cate($CateID);
        if ($CateID and '1' != $cate['CateEnable']) {
            return;
        }
        $xoopsTpl->assign('cate', $cate);

        $SweetAlert = new SweetAlert();
        $SweetAlert->render('delete_news_func', "news.php?op=delete&WebID={$WebID}&NewsID=", 'NewsID');

        $JqueryPrintPreview = new JqueryPrintPreview('a.print-preview');
        $JqueryPrintPreview->render();

        $xoopsTpl->assign('module_css', '<link rel="stylesheet" href="' . XOOPS_URL . '/modules/tad_web/plugins/news/print.css" type="text/css" media="print">');

        //取得標籤
        $xoopsTpl->assign('tags', $this->tags->list_tags('NewsID', $NewsID, 'news'));

        // $xoopsTpl->assign("isAssistant", is_assistant($this->WebID, 'news', $CateID, 'NewsID', $NewsID));
    }

    //tad_web_news編輯表單
    public function edit_form($NewsID = '')
    {
        global $xoTheme, $xoopsUser, $xoopsTpl, $TadUpFiles, $plugin_menu_var;

        $xoTheme->addScript('modules/tadtools/My97DatePicker/WdatePicker.js');
        if (isset($_SESSION['isAssistant']['news'])) {
            chk_self_web($this->WebID, $_SESSION['isAssistant']['news']);
        } else {
            chk_self_web($this->WebID);
        }

        get_quota($this->WebID);

        //抓取預設值
        if (!empty($NewsID)) {
            $DBV = $this->get_one_data($NewsID);
        } else {
            $DBV = [];
        }

        //設定「NewsID」欄位預設值
        $NewsID = (!isset($DBV['NewsID'])) ? '' : $DBV['NewsID'];
        $xoopsTpl->assign('NewsID', $NewsID);

        //設定「NewsTitle」欄位預設值
        $NewsTitle = isset($DBV['NewsDate']) ? $DBV['NewsTitle'] : '';
        $xoopsTpl->assign('NewsTitle', $NewsTitle);

        //設定「NewsContent」欄位預設值
        $NewsContent = isset($DBV['NewsContent']) ? $DBV['NewsContent'] : '';
        $xoopsTpl->assign('NewsContent', $NewsContent);

        //設定「NewsDate」欄位預設值
        $NewsDate = (!isset($DBV['NewsDate'])) ? date('Y-m-d H:i:s') : $DBV['NewsDate'];
        $xoopsTpl->assign('NewsDate', $NewsDate);

        //設定「toCal」欄位預設值
        if (!isset($DBV['toCal'])) {
            $toCal = '';
        } else {
            $toCal = ('0000-00-00 00:00:00' === $DBV['toCal']) ? '' : $DBV['toCal'];
        }
        $xoopsTpl->assign('toCal', $toCal);

        //設定「NewsUrl」欄位預設值
        $NewsUrl = (!isset($DBV['NewsUrl'])) ? '' : $DBV['NewsUrl'];
        $xoopsTpl->assign('NewsUrl', $NewsUrl);

        //設定「WebID」欄位預設值
        $WebID = (!isset($DBV['WebID'])) ? $this->WebID : $DBV['WebID'];
        $xoopsTpl->assign('WebID', $WebID);

        //設定「NewsCounter」欄位預設值
        $NewsCounter = (!isset($DBV['NewsCounter'])) ? '' : $DBV['NewsCounter'];
        $xoopsTpl->assign('NewsCounter', $NewsCounter);

        //設定「CateID」欄位預設值
        $DefCateID = isset($_SESSION['isAssistant']['news']) ? $_SESSION['isAssistant']['news'] : '';
        $CateID = (!isset($DBV['CateID'])) ? $DefCateID : $DBV['CateID'];
        $this->WebCate->set_button_value($plugin_menu_var['news']['short'] . _MD_TCW_CATE_TOOLS);
        $this->WebCate->set_default_option_text(sprintf(_MD_TCW_SELECT_PLUGIN_CATE, $plugin_menu_var['news']['short']));
        $cate_menu = isset($_SESSION['isAssistant']['news']) ? $this->WebCate->hidden_cate_menu($CateID) : $this->WebCate->cate_menu($CateID);
        $xoopsTpl->assign('cate_menu_form', $cate_menu);

        //設定「NewsEnable」欄位預設值
        $NewsEnable = (!isset($DBV['NewsEnable'])) ? '1' : $DBV['NewsEnable'];
        $xoopsTpl->assign('NewsEnable', $NewsEnable);

        //設定「uid」欄位預設值
        $user_uid = ($xoopsUser) ? $xoopsUser->uid() : '';
        $uid = (!isset($DBV['uid'])) ? $user_uid : $DBV['uid'];
        $xoopsTpl->assign('uid', $uid);

        $op = (empty($NewsID)) ? 'insert' : 'update';

        $FormValidator = new FormValidator('#myForm', true);
        $FormValidator->render();

        Utility::mk_dir(XOOPS_ROOT_PATH . "/uploads/tad_web/{$this->WebID}/news");
        Utility::mk_dir(XOOPS_ROOT_PATH . "/uploads/tad_web/{$this->WebID}/news/image");
        Utility::mk_dir(XOOPS_ROOT_PATH . "/uploads/tad_web/{$this->WebID}/news/file");
        $CkEditor = new CkEditor("tad_web/{$this->WebID}/news", 'NewsContent', $NewsContent);
        $CkEditor->setHeight(300);
        $NewsContent_editor = $CkEditor->render();
        $xoopsTpl->assign('NewsContent_editor', $NewsContent_editor);

        $xoopsTpl->assign('next_op', $op);

        $TadUpFiles->set_col('NewsID', $NewsID);
        $upform = $TadUpFiles->upform();
        $xoopsTpl->assign('upform', $upform);

        //權限設定
        $power_form = $this->Power->power_menu('read', 'NewsID', $NewsID);
        $xoopsTpl->assign('power_form', $power_form);
        //標籤設定
        $tags_form = $this->tags->tags_menu('NewsID', $NewsID);
        $xoopsTpl->assign('tags_form', $tags_form);
    }

    //新增資料到tad_web_news中
    public function insert()
    {
        global $xoopsDB, $xoopsUser, $TadUpFiles, $WebOwnerUid;
        if (isset($_SESSION['isAssistant']['news'])) {
            chk_self_web($this->WebID, $_SESSION['isAssistant']['news']);
        } else {
            chk_self_web($this->WebID);
        }

        if (isset($_SESSION['isAssistant']['news'])) {
            $uid = $WebOwnerUid;
        } elseif (!empty($_POST['uid'])) {
            $uid = (int) $_POST['uid'];
        } else {
            $uid = ($xoopsUser) ? $xoopsUser->uid() : '';
        }

        $NewsTitle = $_POST['NewsTitle'];
        $NewsUrl = $_POST['NewsUrl'];
        $NewsContent = $_POST['NewsContent'];
        $NewsContent = Wcag::amend($NewsContent);
        $NewsDate = $_POST['NewsDate'];
        $toCal = $_POST['toCal'];
        $newCateName = $_POST['newCateName'];
        $tag_name = $_POST['tag_name'];
        $CateID = (int) $_POST['CateID'];
        $WebID = (int) $_POST['WebID'];
        $NewsEnable = (int) $_POST['NewsEnable'];

        if (empty($toCal)) {
            $toCal = '0000-00-00 00:00:00';
        }
        if ($newCateName != '') {
            $CateID = $this->WebCate->save_tad_web_cate($CateID, $newCateName);
        }

        $sql = 'INSERT INTO `' . $xoopsDB->prefix('tad_web_news') . '` (`CateID`, `NewsTitle`, `NewsContent`, `NewsDate`, `toCal`, `NewsUrl`, `WebID`, `NewsCounter`, `uid`, `NewsEnable`) VALUES (?, ?, ?, ?, ?, ?, ?, 0, ?, ?)';
        Utility::query($sql, 'isssssiis', [$CateID, $NewsTitle, $NewsContent, $NewsDate, $toCal, $NewsUrl, $WebID, $uid, $NewsEnable]) or Utility::web_error($sql, __FILE__, __LINE__);

        //取得最後新增資料的流水編號
        $NewsID = $xoopsDB->getInsertId();
        save_assistant_post($WebID, 'news', $CateID, 'NewsID', $NewsID);

        $TadUpFiles->set_col('NewsID', $NewsID);
        $TadUpFiles->upload_file('upfile', 640, null, null, null, true);
        check_quota($this->WebID);

        //儲存權限
        $this->Power->save_power('NewsID', $NewsID, 'read');
        //儲存標籤

        $this->tags->save_tags('NewsID', $NewsID, $tag_name, $_POST['tags']);
        return $NewsID;
    }

    //更新tad_web_news某一筆資料
    public function update($NewsID = '')
    {
        global $xoopsDB, $TadUpFiles;
        if (isset($_SESSION['isAssistant']['news'])) {
            chk_self_web($this->WebID, $_SESSION['isAssistant']['news']);
        } else {
            chk_self_web($this->WebID);
        }

        $NewsTitle = $_POST['NewsTitle'];
        $NewsUrl = $_POST['NewsUrl'];
        $NewsContent = $_POST['NewsContent'];
        $NewsContent = Wcag::amend($NewsContent);
        $NewsDate = $_POST['NewsDate'];
        $toCal = $_POST['toCal'];
        $newCateName = $_POST['newCateName'];
        $tag_name = $_POST['tag_name'];
        $CateID = (int) $_POST['CateID'];
        $WebID = (int) $_POST['WebID'];
        $NewsEnable = (int) $_POST['NewsEnable'];

        if (empty($toCal)) {
            $toCal = '0000-00-00 00:00:00';
        }
        if ($newCateName != '') {
            $CateID = $this->WebCate->save_tad_web_cate($CateID, $newCateName);
        }

        $and_uid = '';
        if (!is_assistant($this->WebID, 'news', $CateID, 'NewsID', $NewsID)) {
            $and_uid = onlyMine();
        }

        $sql = 'UPDATE `' . $xoopsDB->prefix('tad_web_news') . '` SET
        `CateID` = ?,
        `NewsTitle` = ?,
        `NewsContent` = ?,
        `NewsDate` = ?,
        `toCal` = ?,
        `NewsUrl` = ?,
        `NewsEnable` = ?
        WHERE `NewsID` = ? ' . $and_uid;
        Utility::query($sql, 'issssssi', [$CateID, $NewsTitle, $NewsContent, $NewsDate, $toCal, $NewsUrl, $NewsEnable, $NewsID]) or Utility::web_error($sql, __FILE__, __LINE__);

        $TadUpFiles->set_col('NewsID', $NewsID);
        $TadUpFiles->upload_file('upfile', 640, null, null, null, true);
        check_quota($this->WebID);

        //儲存權限
        $this->Power->save_power('NewsID', $NewsID, 'read');
        //儲存標籤
        $this->tags->save_tags('NewsID', $NewsID, $tag_name, $_POST['tags']);
        return $NewsID;
    }

    //刪除tad_web_news某筆資料資料
    public function delete($NewsID = '')
    {
        global $xoopsDB, $TadUpFiles;
        if (isset($_SESSION['isAssistant']['news'])) {
            chk_self_web($this->WebID, $_SESSION['isAssistant']['news']);
        } else {
            chk_self_web($this->WebID);
        }
        $sql = 'SELECT `CateID` FROM `' . $xoopsDB->prefix('tad_web_news') . '` WHERE `NewsID`=?';
        $result = Utility::query($sql, 'i', [$NewsID]) or Utility::web_error($sql, __FILE__, __LINE__);
        list($CateID) = $xoopsDB->fetchRow($result);
        $and_uid = '';
        if (!is_assistant($this->WebID, 'news', $CateID, 'NewsID', $NewsID)) {
            $and_uid = onlyMine();
        }
        $sql = 'DELETE FROM `' . $xoopsDB->prefix('tad_web_news') . '` WHERE `NewsID`=? ' . $and_uid;
        Utility::query($sql, 'i', [$NewsID]) or Utility::web_error($sql, __FILE__, __LINE__);

        $TadUpFiles->set_col('NewsID', $NewsID);
        $TadUpFiles->del_files();
        check_quota($this->WebID);
        //刪除權限
        $this->Power->delete_power('NewsID', $NewsID, 'read');
        //刪除標籤
        $this->tags->delete_tags('NewsID', $NewsID);
    }

    //刪除所有資料
    public function delete_all()
    {
        global $xoopsDB;
        $allCateID = [];
        $sql = 'SELECT `NewsID`, `CateID` FROM `' . $xoopsDB->prefix('tad_web_news') . '` WHERE `WebID`=?';
        $result = Utility::query($sql, 'i', [$this->WebID]) or Utility::web_error($sql, __FILE__, __LINE__);
        while (list($NewsID, $CateID) = $xoopsDB->fetchRow($result)) {
            $this->delete($NewsID);
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
        $sql = 'SELECT COUNT(*) FROM `' . $xoopsDB->prefix('tad_web_news') . '` WHERE `WebID`=? AND `NewsEnable`=?';
        $result = Utility::query($sql, 'is', [$this->WebID, '1']) or Utility::web_error($sql, __FILE__, __LINE__);
        list($count) = $xoopsDB->fetchRow($result);
        return $count;
    }

    //新增tad_web_news計數器
    public function add_counter($NewsID = '')
    {
        global $xoopsDB;

        if (_IS_EZCLASS) {
            $NewsCounter = redis_do($this->WebID, 'get', 'news', "NewsCounter:$NewsID");
            if (empty($NewsCounter)) {
                $sql = 'SELECT `NewsCounter` FROM `' . $xoopsDB->prefix('tad_web_news') . '` WHERE `NewsID` =?';
                $result = Utility::query($sql, 'i', [$NewsID]) or Utility::web_error($sql, __FILE__, __LINE__);
                list($NewsCounter) = $xoopsDB->fetchRow($result);
                redis_do($this->WebID, 'set', 'news', "NewsCounter:$NewsID", $NewsCounter);
            }
            return redis_do($this->WebID, 'incr', 'news', "NewsCounter:$NewsID");
        } else {
            $sql = 'UPDATE `' . $xoopsDB->prefix('tad_web_news') . '` SET `NewsCounter`=`NewsCounter`+1 WHERE `NewsID`=? AND `NewsEnable`=1';
            Utility::query($sql, 'i', [$NewsID]) or Utility::web_error($sql, __FILE__, __LINE__);
        }
    }

    //以流水號取得某筆tad_web_news資料
    public function get_one_data($NewsID = '')
    {
        global $xoopsDB;
        if (empty($NewsID)) {
            return;
        }

        $sql = 'SELECT * FROM `' . $xoopsDB->prefix('tad_web_news') . '` WHERE `NewsID` =?';
        $result = Utility::query($sql, 'i', [$NewsID]) or Utility::web_error($sql, __FILE__, __LINE__);

        $data = $xoopsDB->fetchArray($result);

        if (_IS_EZCLASS) {
            $data['NewsCounter'] = redis_do($this->WebID, 'get', 'news', "NewsCounter:$NewsID");
        }
        return $data;
    }

    //取得上下頁
    public function get_prev_next($DefNewsID)
    {
        global $xoopsDB, $isMyWeb;
        $DefNewsSort = 0;
        $all = $main = [];
        $andEnable = $isMyWeb ? '' : "and `NewsEnable`='1'";
        $sql = 'SELECT `NewsID`,`NewsTitle` FROM `' . $xoopsDB->prefix('tad_web_news') . '` WHERE `WebID`=? ' . $andEnable . ' ORDER BY `NewsDate` DESC';
        $result = Utility::query($sql, 'i', [$this->WebID]) or Utility::web_error($sql, __FILE__, __LINE__);
        $i = 0;
        while (list($NewsID, $NewsTitle) = $xoopsDB->fetchRow($result)) {

            //檢查權限
            $power = $this->Power->check_power('read', 'NewsID', $NewsID);
            if (!$power) {
                continue;
            }

            $all[$i]['NewsID'] = $NewsID;
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
    public function export_data($start_date, $end_date, $CateID = '')
    {
        global $xoopsDB;
        $andCateID = empty($CateID) ? '' : "AND `CateID`='$CateID'";
        $andStart = empty($start_date) ? '' : "AND `NewsDate` >= '{$start_date}'";
        $andEnd = empty($end_date) ? '' : "AND `NewsDate` <= '{$end_date}'";

        $sql = 'SELECT `NewsID`, `NewsTitle`, `NewsDate`, `CateID` FROM `' . $xoopsDB->prefix('tad_web_news') . '` WHERE `WebID` = ? ' . $andStart . ' ' . $andEnd . ' ' . $andCateID . ' ORDER BY `NewsDate`';
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
