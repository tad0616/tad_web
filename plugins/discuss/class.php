<?php
use Xmf\Request;
use XoopsModules\Tadtools\FormValidator;
use XoopsModules\Tadtools\SweetAlert;
use XoopsModules\Tadtools\Utility;
use XoopsModules\Tad_web\Power;
use XoopsModules\Tad_web\Tags;
use XoopsModules\Tad_web\WebCate;

class tad_web_discuss
{
    public $WebID = 0;
    public $WebCate;
    public $aboutus_setup;
    public $discuss_setup;
    public $tags;
    public $Power;

    public function __construct($WebID)
    {
        $this->WebID = $WebID;
        $this->WebCate = new WebCate($WebID, 'discuss', 'tad_web_discuss');
        $this->tags = new Tags($WebID);
        $this->Power = new Power($WebID);
        $this->aboutus_setup = get_plugin_setup_values($WebID, 'aboutus');
        $this->discuss_setup = get_plugin_setup_values($WebID, 'discuss');
    }

    //列出所有tad_web_discuss資料
    public function list_all($CateID = '', $limit = null, $mode = 'assign', $tag = '')
    {
        global $xoopsDB, $xoopsUser, $xoopsTpl, $MyWebs, $isMyWeb, $plugin_menu_var;

        $power = $this->Power->check_power("read", "CateID", $CateID, 'discuss');
        if (!$power) {
            redirect_header("discuss.php?WebID={$this->WebID}", 3, _MD_TCW_NOW_READ_POWER);
        }

        $andWebID = (empty($this->WebID)) ? '' : "and a.WebID='{$this->WebID}'";

        $andCateID = '';
        if ('assign' === $mode) {
            //取得tad_web_cate所有資料陣列
            if (!empty($plugin_menu_var)) {
                $this->WebCate->set_button_value($plugin_menu_var['discuss']['short'] . _MD_TCW_CATE_TOOLS);
                $this->WebCate->set_default_option_text(sprintf(_MD_TCW_SELECT_PLUGIN_CATE, $plugin_menu_var['discuss']['short']));
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
                $xoopsTpl->assign('DiscussDefCateID', $CateID);
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

            $sql = 'select a.* from ' . $xoopsDB->prefix('tad_web_discuss') . ' as a
            left join ' . $xoopsDB->prefix('tad_web') . ' as b on a.WebID=b.WebID
            left join ' . $xoopsDB->prefix('apply') . ' as c on b.WebOwnerUid=c.uid
            left join ' . $xoopsDB->prefix('tad_web_cate') . " as d on a.CateID=d.CateID
            where b.`WebEnable`='1' and (d.CateEnable='1' or a.CateID='0') and a.ReDiscussID='0' $andCounty $andCity $andSchoolName
            order by a.LastTime desc";
        } elseif (!empty($tag)) {
            $sql = 'select distinct a.* from ' . $xoopsDB->prefix('tad_web_discuss') . ' as a
            left join ' . $xoopsDB->prefix('tad_web') . ' as b on a.WebID=b.WebID
            join ' . $xoopsDB->prefix('tad_web_tags') . " as c on c.col_name='DiscussID' and c.col_sn=a.DiscussID
            left join " . $xoopsDB->prefix('tad_web_cate') . " as d on a.CateID=d.CateID
            where b.`WebEnable`='1' and (d.CateEnable='1' or a.CateID='0') and a.ReDiscussID='0' and c.`tag_name`='{$tag}' $andWebID $andCateID
            order by a.LastTime desc";
        } else {
            if (empty($this->WebID)) {
                return;
            }

            $sql = 'select a.* from ' . $xoopsDB->prefix('tad_web_discuss') . ' as a
            left join ' . $xoopsDB->prefix('tad_web') . ' as b on a.WebID=b.WebID
            left join ' . $xoopsDB->prefix('tad_web_cate') . " as c on a.CateID=c.CateID
            where b.`WebEnable`='1' and (c.CateEnable='1' or a.CateID='0') and a.ReDiscussID='0' $andWebID $andCateID
            order by a.LastTime desc";
        }
        $to_limit = empty($limit) ? 20 : $limit;

        //getPageBar($原sql語法, 每頁顯示幾筆資料, 最多顯示幾個頁數選項);
        $PageBar = Utility::getPageBar($sql, $to_limit, 10);
        $bar = $PageBar['bar'];
        $sql = $PageBar['sql'];
        $total = $PageBar['total'];

        $result = $xoopsDB->query($sql) or Utility::web_error($sql, __FILE__, __LINE__);

        $main_data = [];

        $i = 0;

        $Webs = getAllWebInfo();

        $cate = $this->WebCate->get_tad_web_cate_arr(null, null, 'discuss');

        while (false !== ($all = $xoopsDB->fetchArray($result))) {
            //`DiscussID`, `ReDiscussID`, `CateID`, `WebID`, `MemID`, `MemName`, `DiscussTitle`, `DiscussContent`, `DiscussDate`, `LastTime`, `DiscussCounter`
            foreach ($all as $k => $v) {
                $$k = $v;
            }

            $power = $this->Power->check_power("read", "CateID", $CateID, 'discuss');
            if (!$power) {
                continue;
            }

            $main_data[$i] = $all;
            $main_data[$i]['id'] = $DiscussID;
            $main_data[$i]['id_name'] = 'DiscussID';
            $main_data[$i]['title'] = $DiscussTitle;
            if (_IS_EZCLASS) {
                $main_data[$i]['DiscussCounter'] = redis_do($this->WebID, 'get', 'discuss', "DiscussCounter:$DiscussID");
            }

            $this->WebCate->set_WebID($WebID);

            $main_data[$i]['cate'] = isset($cate[$CateID]) ? $cate[$CateID] : '';
            $main_data[$i]['WebTitle'] = "<a href='index.php?WebID={$WebID}'>{$Webs[$WebID]}</a>";
            // $main_data[$i]['isMyWeb']  = in_array($WebID, $MyWebs) ? 1 : 0;
            $main_data[$i]['isMyWeb'] = $isMyWeb;

            $renum = $this->get_re_num($DiscussID);
            $show_re_num = empty($renum) ? '' : " ({$renum}) ";
            $LastTime = substr($LastTime, 0, 10);

            $main_data[$i]['show_re_num'] = $show_re_num;
            $main_data[$i]['LastTime'] = $LastTime;
            if (!$xoopsUser and !$_SESSION['LoginMemID'] and !$_SESSION['LoginParentID']) {
                if ($MemID) {
                    $main_data[$i]['MemName'] = ('1' != $this->aboutus_setup['mem_fullname']) ? mb_substr($MemName, 0, 1, _CHARSET) . _MD_TCW_SOMEBODY : $MemName;
                } elseif ($ParentID) {
                    $main_data[$i]['MemName'] = ('1' != $this->aboutus_setup['mem_fullname']) ? mb_substr($MemName, 0, 1, _CHARSET) . _MD_TCW_DISCUSS_PARENTS : $MemName . _MD_TCW_DISCUSS_PARENTS;
                } else {
                    $main_data[$i]['MemName'] = $MemName;
                }
            } else {
                $main_data[$i]['MemName'] = $MemName;
            }

            $i++;
        }

        $SweetAlert = new SweetAlert();
        $SweetAlert->render('delete_discuss_func', "discuss.php?op=delete&WebID={$this->WebID}&DiscussID=", 'DiscussID');

        if ('return' === $mode) {
            $data['main_data'] = $main_data;
            $data['total'] = $total;
            return $data;
        } else {
            $xoopsTpl->assign('discuss_data', $main_data);
            $xoopsTpl->assign('bar', $bar);
            $xoopsTpl->assign('discuss', get_db_plugin($this->WebID, 'discuss'));
            return $total;
        }
        // }
    }

    //以流水號秀出某筆tad_web_discuss資料內容
    public function show_one($DiscussID = '')
    {
        global $xoopsDB, $xoopsUser, $xoopsTpl, $TadUpFiles, $isMyWeb;
        if (empty($DiscussID)) {
            return;
        }

        $DiscussID = (int) $DiscussID;

        $sql = 'SELECT * FROM `' . $xoopsDB->prefix('tad_web_discuss') . '` WHERE `DiscussID`=?';
        $result = Utility::query($sql, 'i', [$DiscussID]) or Utility::web_error($sql, __FILE__, __LINE__);
        $all = $xoopsDB->fetchArray($result);

        //以下會產生這些變數： $DiscussID , $ReDiscussID, $CateID , $uid , $DiscussTitle , $DiscussContent , $DiscussDate , $WebID , $LastTime , $DiscussCounter
        foreach ($all as $k => $v) {
            $$k = $v;
        }

        $power = $this->Power->check_power("read", "CateID", $CateID, 'discuss');
        if (!$power) {
            redirect_header("discuss.php?WebID={$this->WebID}", 3, _MD_TCW_NOW_READ_POWER);
        }

        if (_IS_EZCLASS) {
            $DiscussCounter = $data['DiscussCounter'] = $this->add_counter($DiscussID);
        } else {
            $this->add_counter($DiscussID);
        }

        if (!empty($uid)) {
            $TadUpFiles->set_col('WebOwner', $WebID, '1');
            $pic = $TadUpFiles->get_pic_file('thumb');
            if (empty($pic)) {
                $pic = 'images/nobody.png';
            }

            $isMineDiscuss = $isMyWeb ? true : false;
        } elseif ($MemID) {
            $TadUpFiles->set_col('MemID', $MemID, '1');
            $pic = $TadUpFiles->get_pic_file('thumb');
            $M = get_tad_web_mems($MemID);
            if (empty($pic)) {
                $pic = ('1' == $M['MemSex']) ? XOOPS_URL . '/modules/tad_web/images/boy.gif' : XOOPS_URL . '/modules/tad_web/images/girl.gif';
            }
            $isMineDiscuss = $this->isMineDiscuss('LoginMemID', $MemID, $WebID);
        } elseif ($ParentID) {
            $TadUpFiles->set_col('ParentID', $ParentID, '1');
            $pic = $TadUpFiles->get_pic_file('thumb');
            if (empty($pic)) {
                $pic = XOOPS_URL . '/modules/tad_web/images/nobody.png';
            }
            $isMineDiscuss = $this->isMineDiscuss('LoginParentID', $ParentID, $WebID);
        }
        $xoopsTpl->assign('pic', $pic);

        $TadUpFiles->set_col('DiscussID', $DiscussID);
        $TadUpFiles->set_var('other_css', 'margin:6px;');
        $TadUpFiles->set_var('background_size', 'cover');
        $DiscussFiles = $TadUpFiles->show_files('upfile', true, null, true);
        //$xoopsTpl->assign('DiscussFiles', $DiscussFiles);
        $DiscussContent = $this->addLink(nl2br($DiscussContent));
        preg_match_all('/\[([a-zA-Z_0-9.]+)\]/', $DiscussContent, $smile_pic);
        foreach ($smile_pic[1] as $pic_name) {
            $new_pic_name = strtolower($pic_name);
            $DiscussContent = str_replace("[$pic_name]", '<img src="' . XOOPS_URL . "/modules/tad_web/plugins/discuss/smiles/$new_pic_name\" alt=\"{$pic_name}\" hspace=2 align='absmiddle'>", $DiscussContent);
        }

        // $DiscussContent = str_replace("[e_", "<img src='" . XOOPS_URL . "/modules/tad_web/plugins/discuss/smiles/e_", $DiscussContent);
        // $DiscussContent = str_replace(".png]", ".png' hspace=2 align='absmiddle'>", $DiscussContent);

        $xoopsTpl->assign('isMineDiscuss', $isMineDiscuss);
        $xoopsTpl->assign('DiscussTitle', $DiscussTitle);
        $xoopsTpl->assign('MemID', $MemID);
        $xoopsTpl->assign('ParentID', $ParentID);
        $xoopsTpl->assign('DiscussContent', $this->bubble($DiscussContent . $DiscussFiles));
        $xoopsTpl->assign('DiscussDate', $DiscussDate);
        $xoopsTpl->assign('LastTime', $LastTime);
        if (!$xoopsUser and !$_SESSION['LoginMemID'] and !$_SESSION['LoginParentID']) {
            if ($MemID) {
                $MemName = ('1' != $this->aboutus_setup['mem_fullname']) ? mb_substr($MemName, 0, 1, _CHARSET) . _MD_TCW_SOMEBODY : $MemName;
            } elseif ($ParentID) {
                $MemName = ('1' != $this->aboutus_setup['mem_fullname']) ? mb_substr($MemName, 0, 1, _CHARSET) . _MD_TCW_DISCUSS_PARENTS : $MemName . _MD_TCW_DISCUSS_PARENTS;
            } else {
                $MemName = $MemName;
            }
        }
        $xoopsTpl->assign('MemName', $MemName);
        $xoopsTpl->assign('WebID', $WebID);
        $xoopsTpl->assign('DiscussCounter', $DiscussCounter);
        $xoopsTpl->assign('DiscussID', $DiscussID);
        $xoopsTpl->assign('DiscussInfo', sprintf(_MD_TCW_INFO, $MemName, $DiscussDate, $DiscussCounter));
        $xoopsTpl->assign('re', $this->get_re($DiscussID));
        $xoopsTpl->assign('LoginMemID', $_SESSION['LoginMemID']);
        $xoopsTpl->assign('LoginParentID', $_SESSION['LoginParentID']);

        $xoopsTpl->assign('xoops_pagetitle', $DiscussTitle);
        $xoopsTpl->assign('fb_description', xoops_substr(strip_tags($DiscussContent), 0, 300));

        //取得單一分類資料
        $cate = $this->WebCate->get_tad_web_cate($CateID);
        if ($CateID and '1' != $cate['CateEnable']) {
            return;
        }
        $xoopsTpl->assign('cate', $cate);

        $upform = $TadUpFiles->upform(false, 'upfile', null, false);
        $xoopsTpl->assign('upform', $upform);

        $SweetAlert = new SweetAlert();
        $SweetAlert->render('delete_discuss_func', "discuss.php?op=delete&WebID={$this->WebID}&DefDiscussID={$DiscussID}&DiscussID=", 'DiscussID');

        //找出表情圖
        $dir = XOOPS_ROOT_PATH . '/modules/tad_web/plugins/discuss/smiles/';
        if (is_dir($dir)) {
            if ($dh = opendir($dir)) {
                while (false !== ($file = readdir($dh))) {
                    if ('.' === substr($file, 0, 1) or 'e' !== substr($file, 0, 1)) {
                        continue;
                    }

                    $key = substr($file, 1, -4);
                    $smile_pics[$key] = $file;
                }
                closedir($dh);
            }
        }
        // die(var_export($smile_pics));
        sort($smile_pics);
        $xoopsTpl->assign('smile_pics', $smile_pics);

        $xoopsTpl->assign('tags', $this->tags->list_tags('DiscussID', $DiscussID, 'discuss'));
    }

    //tad_web_discuss編輯表單
    public function edit_form($DiscussID = '')
    {
        global $xoTheme, $xoopsUser, $isMyWeb, $xoopsTpl, $TadUpFiles, $plugin_menu_var;

        if (!$_SESSION['tad_web_adm'] and !$isMyWeb and empty($_SESSION['LoginMemID']) and empty($_SESSION['LoginParentID'])) {
            redirect_header('index.php', 3, _MD_TCW_LOGIN_TO_POST);
        }
        $xoTheme->addScript('modules/tadtools/My97DatePicker/WdatePicker.js');
        get_quota($this->WebID);

        //抓取預設值
        if (!empty($DiscussID)) {
            $DBV = $this->get_one_data($DiscussID);
        } else {
            $DBV = [];
        }

        //預設值設定

        if ($isMyWeb) {

            //設定「uid」欄位預設值
            $uid = (!isset($DBV['uid'])) ? $xoopsUser->uid() : $DBV['uid'];

            //設定「MemID」欄位預設值
            $MemID = (!isset($DBV['MemID'])) ? 0 : $DBV['MemID'];

            //設定「ParentID」欄位預設值
            $ParentID = (!isset($DBV['ParentID'])) ? 0 : $DBV['ParentID'];

            //設定「LoginMemName」欄位預設值
            $MemName = (!isset($DBV['MemName'])) ? $xoopsUser->name() : $DBV['MemName'];

            //設定「WebID」欄位預設值
            $WebID = (!isset($DBV['WebID'])) ? $this->WebID : $DBV['WebID'];
        } else {

            //設定「uid」欄位預設值
            $uid = (!isset($DBV['uid'])) ? 0 : $DBV['uid'];
            if ($_SESSION['LoginMemID']) {
                //設定「MemID」欄位預設值
                $MemID = (!isset($DBV['MemID'])) ? $_SESSION['LoginMemID'] : $DBV['MemID'];

                //設定「ParentID」欄位預設值
                $ParentID = (!isset($DBV['ParentID'])) ? 0 : $DBV['ParentID'];

                //設定「LoginMemName」欄位預設值
                $MemName = (!isset($DBV['MemName'])) ? $_SESSION['LoginMemName'] : $DBV['MemName'];
            } elseif ($_SESSION['LoginParentID']) {
                //設定「MemID」欄位預設值
                $MemID = (!isset($DBV['MemID'])) ? 0 : $DBV['MemID'];

                //設定「ParentID」欄位預設值
                $ParentID = (!isset($DBV['ParentID'])) ? $_SESSION['ParentID'] : $DBV['ParentID'];

                //設定「LoginMemName」欄位預設值
                $MemName = (!isset($DBV['MemName'])) ? $_SESSION['LoginParentName'] : $DBV['MemName'];
            }
            //設定「WebID」欄位預設值
            $WebID = (!isset($DBV['WebID'])) ? $_SESSION['LoginWebID'] : $DBV['WebID'];
        }
        $xoopsTpl->assign('uid', $uid);
        $xoopsTpl->assign('MemID', $MemID);
        $xoopsTpl->assign('ParentID', $ParentID);
        $xoopsTpl->assign('MemName', $MemName);
        $xoopsTpl->assign('WebID', $WebID);

        //設定「DiscussID」欄位預設值
        $DiscussID = (!isset($DBV['DiscussID'])) ? '' : $DBV['DiscussID'];
        $xoopsTpl->assign('DiscussID', $DiscussID);

        //設定「ReDiscussID」欄位預設值
        $ReDiscussID = (!isset($DBV['ReDiscussID'])) ? '' : $DBV['ReDiscussID'];
        $xoopsTpl->assign('ReDiscussID', $ReDiscussID);

        //設定「DiscussTitle」欄位預設值
        $DiscussTitle = (!isset($DBV['DiscussTitle'])) ? '' : $DBV['DiscussTitle'];
        $xoopsTpl->assign('DiscussTitle', $DiscussTitle);

        //設定「DiscussContent」欄位預設值
        $DiscussContent = (!isset($DBV['DiscussContent'])) ? '' : $DBV['DiscussContent'];
        $xoopsTpl->assign('DiscussContent', $DiscussContent);

        //設定「DiscussDate」欄位預設值
        $DiscussDate = (!isset($DBV['DiscussDate'])) ? date('Y-m-d H:i:s') : $DBV['DiscussDate'];
        $xoopsTpl->assign('DiscussDate', $DiscussDate);

        //設定「LastTime」欄位預設值
        $LastTime = (!isset($DBV['LastTime'])) ? date('Y-m-d H:i:s') : $DBV['LastTime'];
        $xoopsTpl->assign('LastTime', $LastTime);

        //設定「DiscussCounter」欄位預設值
        $DiscussCounter = (!isset($DBV['DiscussCounter'])) ? '' : $DBV['DiscussCounter'];
        $xoopsTpl->assign('DiscussCounter', $DiscussCounter);

        //設定「CateID」欄位預設值
        $CateID = (!isset($DBV['CateID'])) ? '' : $DBV['CateID'];

        $new_cate = $isMyWeb ? true : false;

        $this->WebCate->set_button_value($plugin_menu_var['discuss']['short'] . _MD_TCW_CATE_TOOLS);
        $this->WebCate->set_default_option_text(sprintf(_MD_TCW_SELECT_PLUGIN_CATE, $plugin_menu_var['discuss']['short']));
        $cate_menu = $this->WebCate->cate_menu($CateID, 'form', $new_cate);
        $xoopsTpl->assign('cate_menu_form', $cate_menu);

        $op = (empty($DiscussID)) ? 'insert' : 'update';

        $FormValidator = new FormValidator('#myForm', true);
        $FormValidator->render();

        $xoopsTpl->assign('next_op', $op);

        $TadUpFiles->set_col('DiscussID', $DiscussID);
        $upform = $TadUpFiles->upform();
        $xoopsTpl->assign('upform', $upform);

        //找出表情圖
        $dir = XOOPS_ROOT_PATH . '/modules/tad_web/plugins/discuss/smiles/';
        if (is_dir($dir)) {
            if ($dh = opendir($dir)) {
                while (false !== ($file = readdir($dh))) {
                    if ('.' === substr($file, 0, 1) or 'e' !== substr($file, 0, 1)) {
                        continue;
                    }

                    $key = substr($file, 1, -4);
                    $smile_pics[$key] = $file;
                }
                closedir($dh);
            }
        }
        // die(var_export($smile_pics));
        sort($smile_pics);
        $xoopsTpl->assign('smile_pics', $smile_pics);

        $tags_form = $this->tags->tags_menu('DiscussID', $DiscussID);
        $xoopsTpl->assign('tags_form', $tags_form);
    }

    //新增資料到tad_web_discuss中
    public function insert()
    {
        global $xoopsDB, $xoopsUser, $WebID, $isMyWeb, $TadUpFiles;

        if (empty($_SESSION['LoginMemID']) and empty($_SESSION['LoginParentID']) and !$isMyWeb and $_SESSION['tad_web_adm']) {
            redirect_header('index.php', 3, _MD_TCW_LOGIN_TO_POST);
        }

        $DiscussTitle = (string) $_POST['DiscussTitle'];
        $DiscussContent = (string) $_POST['DiscussContent'];
        $newCateName = (string) $_POST['newCateName'];
        $tag_name = (string) $_POST['tag_name'];
        $CateID = (int) $_POST['CateID'];
        $WebID = (int) $_POST['WebID'];
        $ReDiscussID = (int) $_POST['ReDiscussID'];

        if ($isMyWeb) {
            $uid = $xoopsUser->uid();
            $MemID = 0;
            $ParentID = 0;
            $MemName = $xoopsUser->name();
        } elseif ($_SESSION['LoginMemID']) {
            $uid = 0;
            $ParentID = 0;
            $MemID = $_SESSION['LoginMemID'];
            $MemName = $_SESSION['LoginMemName'];
            $WebID = $_SESSION['LoginWebID'];
        } elseif ($_SESSION['LoginParentID']) {
            $uid = 0;
            $MemID = 0;
            $ParentID = $_SESSION['LoginParentID'];
            $MemName = $_SESSION['LoginParentName'];
            $WebID = $_SESSION['LoginWebID'];
        }
        if ($newCateName != '') {
            $CateID = $this->WebCate->save_tad_web_cate($CateID, $newCateName);
        }

        $sql = 'INSERT INTO `' . $xoopsDB->prefix('tad_web_discuss') . '` (`CateID`, `ReDiscussID`, `uid`, `MemID`, `ParentID`, `MemName`, `DiscussTitle`, `DiscussContent`, `DiscussDate`, `WebID`, `LastTime`, `DiscussCounter`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW(), ?, NOW(), 0)';
        Utility::query($sql, 'iiiiisssi', [$CateID, $ReDiscussID, $uid, $MemID, $ParentID, $MemName, $DiscussTitle, $DiscussContent, $WebID]) or Utility::web_error($sql, __FILE__, __LINE__);

        //取得最後新增資料的流水編號
        $DiscussID = $xoopsDB->getInsertId();

        $TadUpFiles->set_col('DiscussID', $DiscussID);
        $TadUpFiles->upload_file('upfile', 640, null, null, null, true);

        if (!empty($ReDiscussID)) {
            $sql = 'UPDATE `' . $xoopsDB->prefix('tad_web_discuss') . '` SET `LastTime` = NOW() WHERE `DiscussID` = ? OR `ReDiscussID` = ?';
            Utility::query($sql, 'ii', [$ReDiscussID, $ReDiscussID]) or Utility::web_error($sql, __FILE__, __LINE__);
        }

        if (!empty($ReDiscussID)) {
            return $ReDiscussID;
        }

        check_quota($this->WebID);
        //儲存標籤
        $this->tags->save_tags('DiscussID', $DiscussID, $tag_name, $_POST['tags']);
        return $DiscussID;
    }

    //更新tad_web_discuss某一筆資料
    public function update($DiscussID = '')
    {
        global $xoopsDB, $xoopsUser, $isMyWeb, $TadUpFiles;

        if (empty($_SESSION['LoginMemID']) and empty($_SESSION['LoginParentID']) and !$isMyWeb and $_SESSION['tad_web_adm']) {
            redirect_header('index.php', 3, _MD_TCW_LOGIN_TO_POST);
        }

        $and_uid = '';
        if ($isMyWeb) {
            $uid = $xoopsUser->uid();
            $MemID = $ParentID = 0;
            $MemName = $xoopsUser->name();
            $and_uid = ($_SESSION['tad_web_adm']) ? '' : "and `WebID`='{$this->WebID}'";
        } elseif ($_SESSION['LoginMemID']) {
            $uid = $ParentID = 0;
            $MemID = $_SESSION['LoginMemID'];
            $MemName = $_SESSION['LoginMemName'];
            $WebID = $_SESSION['LoginWebID'];
            $and_uid = "and `MemID`='{$MemID}'";
        } elseif ($_SESSION['LoginParentID']) {
            $uid = $MemID = 0;
            $ParentID = $_SESSION['LoginParentID'];
            $MemName = $_SESSION['LoginParentName'];
            $WebID = $_SESSION['LoginWebID'];
            $and_uid = "and `ParentID`='{$ParentID}'";
        }

        $DiscussTitle = (string) $_POST['DiscussTitle'];
        $DiscussContent = (string) $_POST['DiscussContent'];
        $newCateName = (string) $_POST['newCateName'];
        $tag_name = (string) $_POST['tag_name'];
        $CateID = (int) $_POST['CateID'];
        $WebID = (int) $_POST['WebID'];
        $ReDiscussID = (int) $_POST['ReDiscussID'];
        if ($newCateName != '') {
            $CateID = $this->WebCate->save_tad_web_cate($CateID, $newCateName);
        }

        $sql = 'UPDATE `' . $xoopsDB->prefix('tad_web_discuss') . '` SET `CateID` = ?, `ReDiscussID` = ?, `DiscussTitle` = ?, `DiscussContent` = ?, `LastTime` = NOW() WHERE `DiscussID` = ? ' . $and_uid;
        Utility::query($sql, 'iissi', [$CateID, $ReDiscussID, $DiscussTitle, $DiscussContent, $DiscussID]) or Utility::web_error($sql, __FILE__, __LINE__);

        $TadUpFiles->set_col('DiscussID', $DiscussID);
        $TadUpFiles->upload_file('upfile', 640, null, null, null, true);

        check_quota($this->WebID);
        //儲存標籤
        $this->tags->save_tags('DiscussID', $DiscussID, $tag_name, $_POST['tags']);
        return $DiscussID;
    }

    //刪除tad_web_discuss某筆資料資料
    public function delete($DiscussID = '')
    {
        global $xoopsDB, $isMyWeb, $TadUpFiles;

        if (empty($_SESSION['LoginMemID']) and empty($_SESSION['LoginParentID']) and !$isMyWeb and $_SESSION['tad_web_adm']) {
            redirect_header('index.php', 3, _MD_TCW_LOGIN_TO_POST);
        }

        $and_uid = '';
        if ($isMyWeb) {
            $and_uid = ($_SESSION['tad_web_adm']) ? '' : "AND `WebID`='{$this->WebID}'";
        } elseif ($_SESSION['LoginMemID']) {
            $and_uid = "AND `MemID`='{$_SESSION['LoginMemID']}'";
        } elseif ($_SESSION['LoginParentID']) {
            $and_uid = "AND `ParentID`='{$_SESSION['LoginParentID']}'";
        } else {
            return;
        }

        $sql = 'DELETE FROM `' . $xoopsDB->prefix('tad_web_discuss') . '` WHERE `DiscussID`=? ' . $and_uid;
        Utility::query($sql, 'i', [$DiscussID]) or Utility::web_error($sql, __FILE__, __LINE__);

        $sql = 'DELETE FROM `' . $xoopsDB->prefix('tad_web_discuss') . '` WHERE `ReDiscussID`=? ' . $and_uid;
        Utility::query($sql, 'i', [$DiscussID]) or Utility::web_error($sql, __FILE__, __LINE__);

        $TadUpFiles->set_col('DiscussID', $DiscussID);
        $TadUpFiles->del_files();
        check_quota($this->WebID);
        //刪除標籤
        $this->tags->delete_tags('DiscussID', $DiscussID);
    }

    //刪除所有資料
    public function delete_all()
    {
        global $xoopsDB;
        $allCateID = [];
        $sql = 'SELECT `DiscussID`, `CateID` FROM `' . $xoopsDB->prefix('tad_web_discuss') . '` WHERE `WebID`=? AND `ReDiscussID`=?';
        $result = Utility::query($sql, 'is', [$this->WebID, '0']) or Utility::web_error($sql, __FILE__, __LINE__);

        while (list($DiscussID, $CateID) = $xoopsDB->fetchRow($result)) {
            $this->delete($DiscussID);
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
        $sql = 'SELECT COUNT(*) FROM `' . $xoopsDB->prefix('tad_web_discuss') . '` WHERE `WebID`=?';
        $result = Utility::query($sql, 'i', [$this->WebID]) or Utility::web_error($sql, __FILE__, __LINE__);

        list($count) = $xoopsDB->fetchRow($result);
        return $count;
    }

    //新增tad_web_discuss計數器
    public function add_counter($DiscussID = '')
    {
        global $xoopsDB;

        if (_IS_EZCLASS) {
            $DiscussCounter = redis_do($this->WebID, 'get', 'discuss', "DiscussCounter:$DiscussID");
            if (empty($DiscussCounter)) {
                $sql = 'SELECT `DiscussCounter` FROM `' . $xoopsDB->prefix('tad_web_discuss') . '` WHERE `DiscussID`=?';
                $result = Utility::query($sql, 'i', [$DiscussID]) or Utility::web_error($sql, __FILE__, __LINE__);
                list($DiscussCounter) = $xoopsDB->fetchRow($result);
                redis_do($this->WebID, 'set', 'discuss', "DiscussCounter:$DiscussID", $DiscussCounter);
            }
            return redis_do($this->WebID, 'incr', 'discuss', "DiscussCounter:$DiscussID");
        } else {
            $sql = 'UPDATE `' . $xoopsDB->prefix('tad_web_discuss') . '` SET `DiscussCounter`=`DiscussCounter`+1 WHERE `DiscussID`=?';
            Utility::query($sql, 'i', [$DiscussID]) or Utility::web_error($sql, __FILE__, __LINE__);
        }
    }

    //以流水號取得某筆tad_web_discuss資料
    public function get_one_data($DiscussID = '')
    {
        global $xoopsDB;
        if (empty($DiscussID)) {
            return;
        }

        $sql = 'SELECT * FROM `' . $xoopsDB->prefix('tad_web_discuss') . '` WHERE `DiscussID` = ?';
        $result = Utility::query($sql, 'i', [$DiscussID]) or Utility::web_error($sql, __FILE__, __LINE__);

        $data = $xoopsDB->fetchArray($result);

        if (_IS_EZCLASS) {
            $data['DiscussCounter'] = redis_do($this->WebID, 'get', 'discuss', "DiscussCounter:$DiscussID");
        }
        return $data;
    }

    //是否有管理權（或由自己發布的），判斷是否要秀出管理工具
    public function isMineDiscuss($col_name = '', $col_sn = '', $DiscussWebID = null)
    {
        global $isMyWeb;

        if (!empty($col_name) and $_SESSION[$col_name] == $col_sn) {
            return true;
        } elseif (!empty($DiscussWebID) and $isMyWeb) {
            return true;
        } elseif ($_SESSION['tad_web_adm']) {
            return true;
        }

        return false;
    }

    //回覆的留言
    public function get_re($DiscussID = '')
    {
        global $xoopsDB, $isMyWeb, $TadUpFiles, $xoopsUser;
        if (empty($DiscussID)) {
            return;
        }

        $desc = ('1' == $this->discuss_setup['new2old']) ? 'desc' : '';

        $sql = 'SELECT * FROM `' . $xoopsDB->prefix('tad_web_discuss') . '` WHERE `ReDiscussID`=? ORDER BY `DiscussDate` ' . $desc;
        $result = Utility::query($sql, 'i', [$DiscussID]) or Utility::web_error($sql, __FILE__, __LINE__);

        $re_data = '';

        while (false !== ($all = $xoopsDB->fetchArray($result))) {
            //以下會產生這些變數： $DiscussID , $ReDiscussID , $MemID , $DiscussTitle , $DiscussContent , $DiscussDate , $WebID , $LastTime , $DiscussCounter
            foreach ($all as $k => $v) {
                $$k = $v;
            }

            if (!empty($uid)) {
                $TadUpFiles->set_col('WebOwner', $WebID, '1');
                $pic = $TadUpFiles->get_pic_file('thumb');
                if (empty($pic)) {
                    $pic = 'images/nobody.png';
                }
                $isMineDiscuss = $isMyWeb ? true : false;
            } elseif ($MemID) {
                $TadUpFiles->set_col('MemID', $MemID, '1');
                $pic = $TadUpFiles->get_pic_file('thumb');
                $M = get_tad_web_mems($MemID);
                if (empty($pic)) {
                    $pic = ('1' == $M['MemSex']) ? XOOPS_URL . '/modules/tad_web/images/boy.gif' : XOOPS_URL . '/modules/tad_web/images/girl.gif';
                }

                $isMineDiscuss = $this->isMineDiscuss('LoginMemID', $MemID, $WebID);
            } elseif ($ParentID) {
                $TadUpFiles->set_col('ParentID', $ParentID, '1');
                $pic = $TadUpFiles->get_pic_file('thumb');
                if (empty($pic)) {
                    $pic = XOOPS_URL . '/modules/tad_web/images/nobody.png';
                }
                $isMineDiscuss = $this->isMineDiscuss('LoginParentID', $ParentID, $WebID);
            }

            $fun = $isMineDiscuss ? "<div style='float:right;'>
            <a href=\"javascript:delete_discuss_func($DiscussID);\" class='btn btn-sm btn-xs btn-danger'>" . _TAD_DEL . "</a>
            <a href='{$_SERVER['PHP_SELF']}?WebID=$WebID&op=edit_form&DiscussID=$DiscussID' class='btn btn-sm btn-warning'>" . _TAD_EDIT . '</a>
            </div>' : '';

            $TadUpFiles->set_col('DiscussID', $DiscussID);
            $TadUpFiles->set_var('other_css', 'margin:6px;');
            $TadUpFiles->set_var('background_size', 'cover');
            $DiscussFiles = $TadUpFiles->show_files('upfile', true, null, true);

            $DiscussContent = $this->addLink(nl2br($DiscussContent));
            preg_match_all('/\[([a-zA-Z_0-9.]+)\]/', $DiscussContent, $smile_pic);
            foreach ($smile_pic[1] as $pic_name) {
                $new_pic_name = strtolower($pic_name);
                $DiscussContent = str_replace("[$pic_name]", '<img src="' . XOOPS_URL . "/modules/tad_web/plugins/discuss/smiles/$new_pic_name\" alt=\"{$pic_name}\" hspace=2 align='absmiddle'>", $DiscussContent);
            }

            // $DiscussContent = str_replace("[e_", "<img src='" . XOOPS_URL . "/modules/tad_web/plugins/discuss/smiles/e_", $DiscussContent);
            // $DiscussContent = str_replace(".png]", ".png' hspace=2 align='absmiddle'>", $DiscussContent);

            $DiscussContent = $this->bubble($DiscussContent . $DiscussFiles);
            if (!$xoopsUser and !$_SESSION['LoginMemID'] and !$_SESSION['LoginParentID']) {
                if ($MemID) {
                    $MemName = ('1' != $this->aboutus_setup['mem_fullname']) ? mb_substr($MemName, 0, 1, _CHARSET) . _MD_TCW_SOMEBODY : $MemName;
                } elseif ($ParentID) {
                    $MemName = ('1' != $this->aboutus_setup['mem_fullname']) ? mb_substr($MemName, 0, 1, _CHARSET) . _MD_TCW_DISCUSS_PARENTS : $MemName . _MD_TCW_DISCUSS_PARENTS;
                } else {
                    $MemName = $MemName;
                }
            }
            $re_data .= "
            <hr>
            <div class='row'>
                <div class='col-md-2  text-center'>
                    <img src='$pic' alt='{$MemName}" . _MD_TCW_DISCUSS_REPLY . "' style='max-width: 100%;' class='img-rounded img-polaroid rounded'>
                    <div style='line-height:1.5em;'>
                      <div>{$MemName}</div>
                      <div style='font-size: 62.5%; background: #1d649b; color: #fff; border-radius: 3px;'>$DiscussDate</div>
                    </div>
                </div>
                <div class='col-md-10'>
                    {$DiscussContent}
                    {$fun}
                </div>
            </div>
            ";
        }

        return $re_data;
    }

    //取得回覆數量
    public function get_re_num($DiscussID = '')
    {
        global $xoopsDB, $xoopsUser;
        if (empty($DiscussID)) {
            return 0;
        }

        $sql = 'SELECT COUNT(*) FROM `' . $xoopsDB->prefix('tad_web_discuss') . '` WHERE `ReDiscussID`=?';
        $result = Utility::query($sql, 'i', [$DiscussID]) or Utility::web_error($sql, __FILE__, __LINE__);
        list($counter) = $xoopsDB->fetchRow($result);
        return $counter;
    }

    public function bubble($content = '')
    {
        $main = "
        <div class='xsnazzy'>
          $content
        </div>";
        return $main;
    }

    //匯出資料
    public function export_data($start_date, $end_date, $CateID = '')
    {
        global $xoopsDB;
        $andCateID = empty($CateID) ? '' : "AND `CateID`='$CateID'";
        $andStart = empty($start_date) ? '' : "AND `DiscussDate` >= '{$start_date}'";
        $andEnd = empty($end_date) ? '' : "AND `DiscussDate` <= '{$end_date}'";

        $sql = 'SELECT `DiscussID`, `DiscussTitle`, `DiscussDate`, `CateID` FROM `' . $xoopsDB->prefix('tad_web_discuss') . '` WHERE `WebID`=? AND `ReDiscussID`=? ' . $andStart . ' ' . $andEnd . ' ' . $andCateID . ' ORDER BY `DiscussDate`';
        $result = Utility::query($sql, 'is', [$this->WebID, '0']) or Utility::web_error($sql, __FILE__, __LINE__);

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

    //網址轉連結
    public function addLink($str)
    {
        $str = preg_replace('#(http|https|ftp|telnet)://([0-9a-z\.\-]+)(:?[0-9]*)([0-9a-z\_\/\?\&\=\%\.\;\#\-\~\+]*)#i', '<a
href="\1://\2\3\4" rel="nofollow" target="_blank">\1://\2\3\4</a>', $str);
        return $str;
    }
}
