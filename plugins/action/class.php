<?php
use Xmf\Request;
use XoopsModules\Tadtools\FancyBox;
use XoopsModules\Tadtools\FormValidator;
use XoopsModules\Tadtools\SweetAlert;
use XoopsModules\Tadtools\Utility;
use XoopsModules\Tad_web\Crawler;
use XoopsModules\Tad_web\Power;
use XoopsModules\Tad_web\Tags;
use XoopsModules\Tad_web\WebCate;

class tad_web_action
{
    public $WebID = 0;
    public $WebCate;
    public $setup;
    public $Power;
    public $tags;

    public function __construct($WebID)
    {
        $this->WebID = $WebID;
        $this->WebCate = new WebCate($WebID, 'action', 'tad_web_action');
        $this->Power = new Power($WebID);
        $this->tags = new Tags($WebID);
        $this->setup = get_plugin_setup_values($WebID, 'action');
    }

    //活動剪影
    public function list_all($CateID = '', $limit = null, $mode = 'assign', $tag = '')
    {
        global $xoopsDB, $xoopsTpl, $TadUpFiles, $MyWebs, $isMyWeb, $plugin_menu_var;

        $power = $this->Power->check_power("read", "CateID", $CateID, 'action');
        // Utility::test($power, 'power', 'dd');
        if (!$power) {
            redirect_header("action.php?WebID={$this->WebID}", 3, _MD_TCW_NOW_READ_POWER);
        }

        $andWebID = (empty($this->WebID)) ? '' : "and a.WebID='{$this->WebID}'";

        $andCateID = '';
        if ('assign' === $mode) {
            //取得tad_web_cate所有資料陣列
            if (!empty($plugin_menu_var)) {
                $this->WebCate->set_button_value($plugin_menu_var['action']['short'] . _MD_TCW_CATE_TOOLS);
                $this->WebCate->set_default_option_text(sprintf(_MD_TCW_SELECT_PLUGIN_CATE, $plugin_menu_var['action']['short']));
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
                $xoopsTpl->assign('ActionDefCateID', $CateID);
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

            $sql = 'select a.* from ' . $xoopsDB->prefix('tad_web_action') . ' as a
            join ' . $xoopsDB->prefix('tad_web') . ' as b on a.WebID=b.WebID
            join ' . $xoopsDB->prefix('apply') . ' as c on b.WebOwnerUid=c.uid
            left join ' . $xoopsDB->prefix('tad_web_cate') . " as d on a.CateID=d.CateID
            where b.`WebEnable`='1' and (d.CateEnable='1' or a.CateID='0') $andCounty $andCity $andSchoolName
            order by a.ActionID desc";
        } elseif (!empty($tag)) {
            $sql = 'select distinct a.* from ' . $xoopsDB->prefix('tad_web_action') . ' as a
            join ' . $xoopsDB->prefix('tad_web') . ' as b on a.WebID=b.WebID
            join ' . $xoopsDB->prefix('tad_web_tags') . " as c on c.col_name='ActionID' and c.col_sn=a.ActionID
            left join " . $xoopsDB->prefix('tad_web_cate') . " as d on a.CateID=d.CateID
            where b.`WebEnable`='1' and (d.CateEnable='1' or a.CateID='0') and c.`tag_name`='{$tag}' $andWebID $andCateID
            order by a.ActionID desc";
        } else {
            if (empty($this->WebID)) {
                return;
            }

            $sql = 'select a.* from ' . $xoopsDB->prefix('tad_web_action') . ' as a
            join ' . $xoopsDB->prefix('tad_web') . ' as b on a.WebID=b.WebID
            left join ' . $xoopsDB->prefix('tad_web_cate') . " as c on a.CateID=c.CateID
            where b.`WebEnable`='1' and (c.CateEnable='1' or a.CateID='0') $andWebID $andCateID
            order by a.ActionID desc";
        }
        $to_limit = empty($limit) ? 20 : $limit;

        //getPageBar($原sql語法, 每頁顯示幾筆資料, 最多顯示幾個頁數選項);
        $PageBar = Utility::getPageBar($sql, $to_limit, 10);
        $bar = $PageBar['bar'];
        $sql = $PageBar['sql'];
        $total = $PageBar['total'];
        Utility::test($sql, 'sql', 'die');
        $result = $xoopsDB->query($sql) or Utility::web_error($sql, __FILE__, __LINE__);

        $main_data = [];

        $i = 0;

        $Webs = getAllWebInfo();

        $cate = $this->WebCate->get_tad_web_cate_arr(null, null, 'action');

        while (false !== ($all = $xoopsDB->fetchArray($result))) {
            //以下會產生這些變數： $ActionID , $ActionName , $ActionDesc , $ActionDate , $ActionPlace , $uid , $WebID , $ActionCount, $gphoto_link
            foreach ($all as $k => $v) {
                $$k = $v;
            }
            //檢查權限
            $power = $this->Power->check_power('read', 'ActionID', $ActionID);
            // Utility::test($power, 'power', 'dd');
            if (!$power) {
                continue;
            }

            // Utility::test($CateID, 'power', 'dd');
            $power = $this->Power->check_power("read", "CateID", $CateID, 'action');
            if (!$power) {
                continue;
            }

            $main_data[$i] = $all;
            $main_data[$i]['id'] = $ActionID;
            $main_data[$i]['id_name'] = 'ActionID';
            $main_data[$i]['title'] = $ActionName;
            // $main_data[$i]['isAssistant'] = is_assistant($this->WebID, 'action', $CateID, 'ActionID', $ActionID);
            $main_data[$i]['isCanEdit'] = isCanEdit($this->WebID, 'action', $CateID, 'ActionID', $ActionID);
            $this->WebCate->set_WebID($WebID);
            if (_IS_EZCLASS) {
                $main_data[$i]['ActionCount'] = redis_do($this->WebID, 'get', 'action', "ActionCount:$ActionID");
            }

            $main_data[$i]['cate'] = isset($cate[$CateID]) ? $cate[$CateID] : '';
            $main_data[$i]['WebTitle'] = "<a href='index.php?WebID={$WebID}'>{$Webs[$WebID]}</a>";
            // $main_data[$i]['isMyWeb']  = in_array($WebID, $MyWebs) ? 1 : 0;
            $main_data[$i]['isMyWeb'] = $isMyWeb;

            if ($gphoto_link != '') {
                $main_data[$i]['ActionPic'] = $this->get_rand_gphoto($ActionID);
            } else {
                $subdir = isset($WebID) ? "/{$WebID}" : '';
                $TadUpFiles->set_dir('subdir', $subdir);
                $TadUpFiles->set_col('ActionID', $ActionID);
                $ActionPic = $TadUpFiles->get_pic_file('thumb');
                $main_data[$i]['ActionPic'] = $ActionPic;
            }

            $i++;
        }

        $SweetAlert = new SweetAlert();
        $SweetAlert->render('delete_action_func', "action.php?op=delete&WebID={$this->WebID}&ActionID=", 'ActionID');

        if ('return' === $mode) {
            $data['main_data'] = $main_data;
            $data['total'] = $total;
            $data['isCanEdit'] = isCanEdit($this->WebID, 'action', $CateID, 'ActionID', $ActionID);
            return $data;
        } else {
            $xoopsTpl->assign('bar', $bar);
            $xoopsTpl->assign('action_data', $main_data);
            $xoopsTpl->assign('action', get_db_plugin($this->WebID, 'action'));
            $xoopsTpl->assign('isCanEdit', isCanEdit($this->WebID, 'action', $CateID, 'ActionID', $ActionID));
            return $total;
        }
    }

    //隨機取得某相簿照片
    public function get_rand_gphoto($ActionID = '')
    {
        global $xoopsDB;

        $sql = 'SELECT `image_url` FROM `' . $xoopsDB->prefix('tad_web_action_gphotos') . '` WHERE `ActionID` =? ORDER BY RAND() LIMIT 0,1';
        $result = Utility::query($sql, 'i', [$ActionID]) or Utility::web_error($sql, __FILE__, __LINE__);

        list($image_url) = $xoopsDB->fetchRow($result);
        return $image_url;
    }

    //以流水號秀出某筆tad_web_action資料內容
    public function show_one($ActionID = '')
    {
        global $xoopsDB, $xoopsTpl, $TadUpFiles, $isMyWeb, $xoopsUser;

        if (empty($ActionID)) {
            redirect_header("{$_SERVER['PHP_SELF']}?WebID={$this->WebID}", 3, _MD_TCW_DATA_NOT_EXIST);
        }

        //檢查權限
        $power = $this->Power->check_power('read', 'ActionID', $ActionID);
        if (!$power) {
            redirect_header("index.php?WebID={$this->WebID}", 3, _MD_TCW_NOW_READ_POWER);
        }

        $ActionID = (int) $ActionID;

        $sql = 'SELECT * FROM `' . $xoopsDB->prefix('tad_web_action') . '` WHERE `ActionID`=?';
        $result = Utility::query($sql, 'i', [$ActionID]) or Utility::web_error($sql, __FILE__, __LINE__);
        $all = $xoopsDB->fetchArray($result);

        //以下會產生這些變數： $ActionID ,$CateID , $ActionName , $ActionDesc , $ActionDate , $ActionPlace , $uid , $WebID , $ActionCount, $gphoto_link
        foreach ($all as $k => $v) {
            $$k = $v;
        }

        $power = $this->Power->check_power("read", "CateID", $CateID, 'action');
        if (!$power) {
            redirect_header("action.php?WebID={$this->WebID}", 3, _MD_TCW_NOW_READ_POWER);
        }

        if (_IS_EZCLASS) {
            $ActionCount = $data['ActionCount'] = $this->add_counter($ActionID);
        } else {
            $this->add_counter($ActionID);
        }

        $TadUpFiles->set_col('ActionID', $ActionID);
        $TadUpFiles->set_var('other_css', 'margin:6px;');
        $TadUpFiles->set_var('background_size', 'cover');
        if ($gphoto_link != '') {
            $fancybox = new FancyBox('.fancybox_ActionID', 640, 480);
            $fancybox->render(false, null, false);
            list($url, $key) = explode('?key=', $gphoto_link);
            $pics = $this->tad_gphotos_list($ActionID, $url, $key);
        } else {
            $pics = $TadUpFiles->show_files('upfile', null, null, null, null, null, null, null, $this->setup['auto_play_images']); //是否縮圖,顯示模式 filename、small,顯示描述,顯示下載次數
        }

        $uid_name = \XoopsUser::getUnameFromId($uid, 1);
        if (empty($uid_name)) {
            $uid_name = \XoopsUser::getUnameFromId($uid, 0);
        }

        $assistant = is_assistant($this->WebID, 'action', $CateID, 'ActionID', $ActionID);
        $isAssistant = !empty($assistant) ? true : false;
        $uid_name = $isAssistant ? "{$uid_name} <a href='#' title='由{$assistant['MemName']}代理發布'><i class='fa fa-male'></i></a>" : $uid_name;
        $xoopsTpl->assign('isAssistant', $isAssistant);
        $xoopsTpl->assign('isCanEdit', isCanEdit($this->WebID, 'action', $CateID, 'ActionID', $ActionID));

        $xoopsTpl->assign('ActionName', $ActionName);
        $xoopsTpl->assign('ActionDate', $ActionDate);
        $xoopsTpl->assign('ActionPlace', $ActionPlace);
        $xoopsTpl->assign('ActionDesc', nl2br($ActionDesc));
        $xoopsTpl->assign('uid_name', $uid_name);
        $xoopsTpl->assign('ActionCount', $ActionCount);
        $xoopsTpl->assign('pics', $pics);
        $xoopsTpl->assign('ActionID', $ActionID);
        $xoopsTpl->assign('ActionInfo', sprintf(_MD_TCW_INFO, $uid_name, $ActionDate, $ActionCount));

        $xoopsTpl->assign('xoops_pagetitle', $ActionName);
        $xoopsTpl->assign('fb_description', $ActionPlace . $ActionDate . xoops_substr(strip_tags($ActionDesc), 0, 300));
        $xoopsTpl->assign('gphoto_link', $gphoto_link);

        //取得單一分類資料
        $cate = $this->WebCate->get_tad_web_cate($CateID);
        if ($CateID and '1' != $cate['CateEnable']) {
            return;
        }
        $xoopsTpl->assign('cate', $cate);

        $SweetAlert = new SweetAlert();
        $SweetAlert->render('delete_action_func', "action.php?op=delete&WebID={$this->WebID}&ActionID=", 'ActionID');

        $xoopsTpl->assign('tags', $this->tags->list_tags('ActionID', $ActionID, 'action'));
    }

    //列出所有tad_gphotos_images資料
    public function tad_gphotos_list($ActionID = '', $url = "", $key = "")
    {
        global $xoopsDB;

        $sql = 'SELECT * FROM `' . $xoopsDB->prefix('tad_web_action_gphotos') . '` WHERE `ActionID`=?';
        $result = Utility::query($sql, 'i', [$ActionID]) or Utility::web_error($sql, __FILE__, __LINE__);

        $gphotos_arr = array();
        $i = 0;
        while ($all = $xoopsDB->fetchArray($result)) {
            $gphotos_arr[$i] = $all;
            //以下會產生這些變數： $ActionID, $image_id, $image_width, $image_height, $image_url
            foreach ($all as $k => $v) {
                $$k = $v;
            }
            $gphotos_arr[$i]['image_link'] = "{$url}/photo/{$image_id}?key={$key}";
            $i++;
        }

        return $gphotos_arr;
    }

    //tad_web_action編輯表單
    public function edit_form($ActionID = '')
    {
        global $xoTheme, $xoopsUser, $xoopsTpl, $TadUpFiles, $plugin_menu_var;

        $xoTheme->addScript('modules/tadtools/My97DatePicker/WdatePicker.js');
        if (isset($_SESSION['isAssistant']['action'])) {
            chk_self_web($this->WebID, $_SESSION['isAssistant']['action']);
        } else {
            chk_self_web($this->WebID);
        }

        get_quota($this->WebID);

        //抓取預設值
        if (!empty($ActionID)) {
            $DBV = $this->get_one_data($ActionID);
        } else {
            $DBV = [];
        }

        //預設值設定

        //設定「ActionID」欄位預設值
        $ActionID = (!isset($DBV['ActionID'])) ? $ActionID : $DBV['ActionID'];
        $xoopsTpl->assign('ActionID', $ActionID);

        //設定「ActionName」欄位預設值
        $ActionName = (!isset($DBV['ActionName'])) ? '' : $DBV['ActionName'];
        $xoopsTpl->assign('ActionName', $ActionName);

        //設定「ActionDesc」欄位預設值
        $ActionDesc = (!isset($DBV['ActionDesc'])) ? '' : $DBV['ActionDesc'];
        $xoopsTpl->assign('ActionDesc', $ActionDesc);

        //設定「ActionDate」欄位預設值
        $ActionDate = (!isset($DBV['ActionDate'])) ? date('Y-m-d') : $DBV['ActionDate'];
        $xoopsTpl->assign('ActionDate', $ActionDate);

        //設定「ActionPlace」欄位預設值
        $ActionPlace = (!isset($DBV['ActionPlace'])) ? '' : $DBV['ActionPlace'];
        $xoopsTpl->assign('ActionPlace', $ActionPlace);

        //設定「uid」欄位預設值
        $user_uid = ($xoopsUser) ? $xoopsUser->uid() : '';
        $uid = (!isset($DBV['uid'])) ? $user_uid : $DBV['uid'];
        $xoopsTpl->assign('uid', $uid);

        //設定「WebID」欄位預設值
        $WebID = (!isset($DBV['WebID'])) ? $this->WebID : $DBV['WebID'];
        $xoopsTpl->assign('WebID', $WebID);

        //設定「ActionCount」欄位預設值
        $ActionCount = (!isset($DBV['ActionCount'])) ? '' : $DBV['ActionCount'];
        $xoopsTpl->assign('ActionCount', $ActionCount);

        //設定「gphoto_link」欄位預設值
        $gphoto_link = (!isset($DBV['gphoto_link'])) ? '' : $DBV['gphoto_link'];
        $xoopsTpl->assign('gphoto_link', $gphoto_link);

        //設定「CateID」欄位預設值
        $DefCateID = isset($_SESSION['isAssistant']['action']) ? $_SESSION['isAssistant']['action'] : '';
        $CateID = (!isset($DBV['CateID'])) ? $DefCateID : $DBV['CateID'];

        $this->WebCate->set_button_value($plugin_menu_var['action']['short'] . _MD_TCW_CATE_TOOLS);
        $this->WebCate->set_default_option_text(sprintf(_MD_TCW_SELECT_PLUGIN_CATE, $plugin_menu_var['action']['short']));
        $cate_menu = isset($_SESSION['isAssistant']['action']) ? $this->WebCate->hidden_cate_menu($CateID) : $this->WebCate->cate_menu($CateID);
        $xoopsTpl->assign('cate_menu_form', $cate_menu);

        $op = (empty($ActionID)) ? 'insert' : 'update';

        $FormValidator = new FormValidator('#myForm', true);
        $FormValidator->render();

        $xoopsTpl->assign('next_op', $op);

        // $subdir = isset($this->WebID) ? "/{$this->WebID}" : "";
        // $TadUpFiles->set_dir('subdir', $subdir);
        $TadUpFiles->set_col('ActionID', $ActionID); //若 $show_list_del_file ==true 時一定要有
        $upform = $TadUpFiles->upform(true, 'upfile', '', true, "image/*");
        $xoopsTpl->assign('upform', $upform);

        $power_form = $this->Power->power_menu('read', 'ActionID', $ActionID);
        $xoopsTpl->assign('power_form', $power_form);

        $tags_form = $this->tags->tags_menu('ActionID', $ActionID);
        $xoopsTpl->assign('tags_form', $tags_form);
    }

    //新增資料到tad_web_action中
    public function insert()
    {
        global $xoopsDB, $xoopsUser, $TadUpFiles, $WebOwnerUid;
        if (isset($_SESSION['isAssistant']['action'])) {
            $uid = $WebOwnerUid;
        } else {
            $uid = $xoopsUser->uid();
        }

        $ActionName = (string) $_POST['ActionName'];
        $ActionDesc = (string) $_POST['ActionDesc'];
        $ActionPlace = (string) $_POST['ActionPlace'];
        $ActionDate = (string) $_POST['ActionDate'];
        $tag_name = (string) $_POST['tag_name'];
        $newCateName = (string) $_POST['newCateName'];
        $ActionCount = (int) $_POST['ActionCount'];
        $gphoto_link = (string) $_POST['gphoto_link'];
        $CateID = (int) $_POST['CateID'];
        $WebID = (int) $_POST['WebID'];
        if ($newCateName != '') {
            $CateID = $this->WebCate->save_tad_web_cate($CateID, $newCateName);
        }
        $sql = 'INSERT INTO `' . $xoopsDB->prefix('tad_web_action') . '` (`CateID`, `ActionName`, `ActionDesc`, `ActionDate`, `ActionPlace`, `uid`, `WebID`, `ActionCount`, `gphoto_link`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)';
        Utility::query($sql, 'issssiiis', [$CateID, $ActionName, $ActionDesc, $ActionDate, $ActionPlace, $uid, $WebID, $ActionCount, $gphoto_link]) or Utility::web_error($sql, __FILE__, __LINE__);

        //取得最後新增資料的流水編號
        $ActionID = $xoopsDB->getInsertId();
        save_assistant_post($WebID, 'action', $CateID, 'ActionID', $ActionID);

        if ($gphoto_link != '') {
            require 'vendor/autoload.php';
            require 'class/Crawler.php';
            $crawler = new Crawler();
            $album = $crawler->getAlbum($gphoto_link);
            foreach ($album['images'] as $photo) {
                $this->insert_gphotos($ActionID, $photo);
            }
        } else {

            // $subdir = isset($this->WebID) ? "/{$this->WebID}" : "";
            // $TadUpFiles->set_dir('subdir', $subdir);
            $TadUpFiles->set_col('ActionID', $ActionID);
            $TadUpFiles->upload_file('upfile', 800, null, null, null, true);
        }
        check_quota($this->WebID);

        //儲存權限
        $this->Power->save_power('ActionID', $ActionID, 'read');
        //儲存標籤
        $this->tags->save_tags('ActionID', $ActionID, $tag_name, $_POST['tags']);
        return $ActionID;
    }

    //新增Google Photo相片
    public function insert_gphotos($ActionID, $photo = [])
    {
        global $xoopsDB;

        $image_id = $photo['id'];
        $image_width = (int) $photo['width'];
        $image_height = (int) $photo['height'];
        $image_url = $photo['url'];

        $sql = 'INSERT INTO `' . $xoopsDB->prefix('tad_web_action_gphotos') . '` ( `ActionID`, `image_id`, `image_width`, `image_height`, `image_url` ) VALUES (?, ?, ?, ?, ?)';
        Utility::query($sql, 'isiis', [$ActionID, $image_id, $image_width, $image_height, $image_url]) or Utility::web_error($sql, __FILE__, __LINE__, true);
    }

    //更新tad_web_action某一筆資料
    public function update($ActionID = '')
    {
        global $xoopsDB, $TadUpFiles;

        $ActionName = (string) $_POST['ActionName'];
        $ActionDesc = (string) $_POST['ActionDesc'];
        $ActionPlace = (string) $_POST['ActionPlace'];
        $ActionDate = (string) $_POST['ActionDate'];
        $gphoto_link = (string) $_POST['gphoto_link'];
        $tag_name = (string) $_POST['tag_name'];
        $newCateName = (string) $_POST['newCateName'];
        $read = (string) $_POST['read'];
        $CateID = (int) $_POST['CateID'];
        $WebID = (int) $_POST['WebID'];
        if ($newCateName != '') {
            $CateID = $this->WebCate->save_tad_web_cate($CateID, $newCateName);
        }

        $and_uid = '';
        if (!is_assistant($this->WebID, 'action', $CateID, 'ActionID', $ActionID)) {
            $and_uid = onlyMine();
        }

        $sql = 'UPDATE `' . $xoopsDB->prefix('tad_web_action') . '` SET `CateID` = ?, `ActionName` = ?, `ActionDesc` = ?, `ActionDate` = ?, `ActionPlace` = ?, `gphoto_link` = ? WHERE `ActionID`=? ' . $and_uid;
        Utility::query($sql, 'isssssi', [$CateID, $ActionName, $ActionDesc, $ActionDate, $ActionPlace, $gphoto_link, $ActionID]) or Utility::web_error($sql, __FILE__, __LINE__);

        // $subdir = isset($this->WebID) ? "/{$this->WebID}" : "";
        // $TadUpFiles->set_dir('subdir', $subdir);
        $TadUpFiles->set_col('ActionID', $ActionID);
        $TadUpFiles->upload_file('upfile', 800, null, null, null, true);
        check_quota($this->WebID);

        //儲存權限
        $this->Power->save_power('ActionID', $ActionID, 'read', $read);
        //儲存標籤
        $this->tags->save_tags('ActionID', $ActionID, $tag_name, $_POST['tags']);
        return $ActionID;
    }

    //刪除tad_web_action某筆資料資料
    public function delete($ActionID = '')
    {
        global $xoopsDB, $TadUpFiles;
        $sql = 'SELECT `CateID` FROM `' . $xoopsDB->prefix('tad_web_action') . '` WHERE `ActionID`=?';
        $result = Utility::query($sql, 'i', [$ActionID]) or Utility::web_error($sql, __FILE__, __LINE__);
        list($CateID) = $xoopsDB->fetchRow($result);

        $and_uid = '';
        if (!is_assistant($this->WebID, 'action', $CateID, 'ActionID', $ActionID)) {
            $and_uid = onlyMine();
        }
        $sql = 'DELETE FROM `' . $xoopsDB->prefix('tad_web_action') . '` WHERE `ActionID`=? ' . $and_uid;
        Utility::query($sql, 'i', [$ActionID]) or Utility::web_error($sql, __FILE__, __LINE__);

        // $subdir = isset($this->WebID) ? "/{$this->WebID}" : "";
        // $TadUpFiles->set_dir('subdir', $subdir);
        $TadUpFiles->set_col('ActionID', $ActionID);
        $TadUpFiles->del_files();
        check_quota($this->WebID);

        $this->Power->delete_power('ActionID', $ActionID, 'read');
        //刪除標籤
        $this->tags->delete_tags('ActionID', $ActionID);
    }

    //刪除所有資料
    public function delete_all()
    {
        global $xoopsDB;
        $allCateID = [];
        $sql = 'SELECT `ActionID`, `CateID` FROM `' . $xoopsDB->prefix('tad_web_action') . '` WHERE `WebID`=?';
        $result = Utility::query($sql, 'i', [$this->WebID]) or Utility::web_error($sql, __FILE__, __LINE__);
        while (list($ActionID, $CateID) = $xoopsDB->fetchRow($result)) {
            $this->delete($ActionID);
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
        $sql = 'SELECT COUNT(*) FROM `' . $xoopsDB->prefix('tad_web_action') . '` WHERE `WebID`=?';
        $result = Utility::query($sql, 'i', [$this->WebID]) or Utility::web_error($sql, __FILE__, __LINE__);
        list($count) = $xoopsDB->fetchRow($result);
        return $count;
    }

    //新增tad_web_action計數器
    public function add_counter($ActionID = '')
    {
        global $xoopsDB;

        if (_IS_EZCLASS) {
            $ActionCount = redis_do($this->WebID, 'get', 'action', "ActionCount:$ActionID");
            if (empty($ActionCount)) {
                $sql = 'SELECT `ActionCount` FROM `' . $xoopsDB->prefix('tad_web_action') . '` WHERE `ActionID`=?';
                $result = Utility::query($sql, 'i', [$ActionID]) or Utility::web_error($sql, __FILE__, __LINE__);
                list($ActionCount) = $xoopsDB->fetchRow($result);
                redis_do($this->WebID, 'set', 'action', "ActionCount:$ActionID", $ActionCount);
            }
            return redis_do($this->WebID, 'incr', 'action', "ActionCount:$ActionID");
        } else {
            $sql = 'UPDATE `' . $xoopsDB->prefix('tad_web_action') . '` SET `ActionCount`=`ActionCount`+1 WHERE `ActionID`=?';
            Utility::query($sql, 'i', [$ActionID]) or Utility::web_error($sql, __FILE__, __LINE__);
        }
    }

    //以流水號取得某筆tad_web_action資料
    public function get_one_data($ActionID = '')
    {
        global $xoopsDB;
        if (empty($ActionID)) {
            return;
        }

        $sql = 'SELECT * FROM `' . $xoopsDB->prefix('tad_web_action') . '` WHERE `ActionID`=?';
        $result = Utility::query($sql, 'i', [$ActionID]) or Utility::web_error($sql, __FILE__, __LINE__);
        $data = $xoopsDB->fetchArray($result);

        if (_IS_EZCLASS) {
            $data['ActionCount'] = redis_do($this->WebID, 'get', 'action', "ActionCount:$ActionID");
        }
        return $data;
    }

    public function blur($gdImageResource, $blurFactor = 3)
    {
        // blurFactor has to be an integer
        $blurFactor = round($blurFactor);

        $originalWidth = imagesx($gdImageResource);
        $originalHeight = imagesy($gdImageResource);

        $smallestWidth = ceil($originalWidth * pow(0.5, $blurFactor));
        $smallestHeight = ceil($originalHeight * pow(0.5, $blurFactor));

        // for the first run, the previous image is the original input
        $prevImage = $gdImageResource;
        $prevWidth = $originalWidth;
        $prevHeight = $originalHeight;

        // scale way down and gradually scale back up, blurring all the way
        for ($i = 0; $i < $blurFactor; $i += 1) {
            // determine dimensions of next image
            $nextWidth = $smallestWidth * pow(2, $i);
            $nextHeight = $smallestHeight * pow(2, $i);

            // resize previous image to next size
            $nextImage = imagecreatetruecolor($nextWidth, $nextHeight);
            imagecopyresized($nextImage, $prevImage, 0, 0, 0, 0, $nextWidth, $nextHeight, $prevWidth, $prevHeight);

            // apply blur filter
            imagefilter($nextImage, IMG_FILTER_GAUSSIAN_BLUR);

            // now the new image becomes the previous image for the next step
            $prevImage = $nextImage;
            $prevWidth = $nextWidth;
            $prevHeight = $nextHeight;
        }

        // scale back to original size and blur one more time
        imagecopyresized($gdImageResource, $nextImage, 0, 0, 0, 0, $originalWidth, $originalHeight, $nextWidth, $nextHeight);
        imagefilter($gdImageResource, IMG_FILTER_GAUSSIAN_BLUR);

        // clean up
        imagedestroy($prevImage);

        // return result
        return $gdImageResource;
    }

    public function mk_blur_pic($filepath, $new_name)
    {
        $type = exif_imagetype($filepath); // [] if you don't have exif you could use getImageSize()
        $allowedTypes = [
            1, // [] gif
            2, // [] jpg
            3, // [] png
            6, // [] bmp
        ];
        if (!in_array($type, $allowedTypes)) {
            return false;
        }
        switch ($type) {
            case 1:
                $im = imagecreatefromgif($filepath);
                break;
            case 2:
                $im = imagecreatefromjpeg($filepath);
                break;
            case 3:
                $im = imagecreatefrompng($filepath);
                break;
            case 6:
                $im = imagecreatefrombmp($filepath);
                break;
        }

        //$im = $this->blur($im, 3);

        $color = imagecolorallocatealpha($im, 255, 255, 255, 10);
        $this->ImageFillAlpha($im, $color);

        imagejpeg($im, $new_name);
        imagedestroy($im);
        return $im;
    }

    public function ImageFillAlpha($image, $color)
    {
        imagefilledrectangle($image, 0, 0, imagesx($image), imagesy($image), $color);
    }

    //匯出資料
    public function export_data($start_date = '', $end_date = '', $CateID = '')
    {
        global $xoopsDB;
        $andCateID = empty($CateID) ? '' : "AND `CateID`='$CateID'";
        $andStart = empty($start_date) ? '' : "AND `ActionDate` >= '{$start_date}'";
        $andEnd = empty($end_date) ? '' : "AND `ActionDate` <= '{$end_date}'";

        $sql = 'SELECT `ActionID`, `ActionName`, `ActionDate`, `CateID` FROM `' . $xoopsDB->prefix('tad_web_action') . '` WHERE `WebID`=? ' . $andStart . ' ' . $andEnd . ' ' . $andCateID . ' ORDER BY `ActionDate`';
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

    // 重新擷取
    public function re_get($ActionID)
    {
        global $xoopsDB;
        if (isset($_SESSION['isAssistant']['action'])) {
            chk_self_web($this->WebID, $_SESSION['isAssistant']['action']);
        } else {
            chk_self_web($this->WebID);
        }

        get_quota($this->WebID);

        if (empty($ActionID)) {
            redirect_header($_SERVER['PHP_SELF'], 3, "Missing ActionID");
        }

        $sql = 'DELETE FROM `' . $xoopsDB->prefix('tad_web_action_gphotos') . '` WHERE `ActionID` = ?';
        Utility::query($sql, 'i', [$ActionID]) or Utility::web_error($sql, __FILE__, __LINE__);

        $sql = 'SELECT `gphoto_link` FROM `' . $xoopsDB->prefix('tad_web_action') . '` WHERE `ActionID` = ?';
        $result = Utility::query($sql, 'i', [$ActionID]) or Utility::web_error($sql, __FILE__, __LINE__);

        list($gphoto_link) = $xoopsDB->fetchRow($result);

        if ($gphoto_link) {
            require 'vendor/autoload.php';
            require 'class/Crawler.php';
            $crawler = new Crawler();
            $album = $crawler->getAlbum($gphoto_link);
            foreach ($album['images'] as $photo) {
                $this->insert_gphotos($ActionID, $photo);
            }
        }
    }

}
