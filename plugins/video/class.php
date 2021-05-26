<?php
use Xmf\Request;
use XoopsModules\Tadtools\FormValidator;
use XoopsModules\Tadtools\SweetAlert;
use XoopsModules\Tadtools\Utility;
use XoopsModules\Tad_web\Power;
use XoopsModules\Tad_web\Tags;
use XoopsModules\Tad_web\WebCate;

class tad_web_video
{
    public $WebID = 0;
    public $web_cate;
    public $setup;

    public function __construct($WebID)
    {
        $this->WebID = $WebID;
        $this->WebCate = new WebCate($WebID, 'video', 'tad_web_video');
        $this->Power = new Power($WebID);
        $this->tags = new Tags($WebID);
        $this->setup = get_plugin_setup_values($WebID, 'video');
    }

    //影片
    public function list_all($CateID = '', $limit = '', $mode = 'assign', $tag = '', $display = 'list')
    {
        global $xoopsDB, $xoopsTpl, $MyWebs, $isMyWeb, $plugin_menu_var;

        $power = $this->Power->check_power("read", "CateID", $CateID, 'video');
        if (!$power) {
            redirect_header("video.php?WebID={$this->WebID}", 3, _MD_TCW_NOW_READ_POWER);
        }

        $andWebID = (empty($this->WebID)) ? '' : "and a.WebID='{$this->WebID}'";

        $andCateID = $andCateDataSort = '';
        if ('assign' === $mode) {
            //取得tad_web_cate所有資料陣列
            if (!empty($plugin_menu_var)) {
                $this->WebCate->set_button_value($plugin_menu_var['video']['short'] . _MD_TCW_CATE_TOOLS);
                $this->WebCate->set_default_option_text(sprintf(_MD_TCW_SELECT_PLUGIN_CATE, $plugin_menu_var['video']['short']));
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
                $andCateDataSort = 'a.VideoSort,';
                $xoopsTpl->assign('VideoDefCateID', $CateID);
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

            $sql = 'select a.* from ' . $xoopsDB->prefix('tad_web_video') . ' as a
            left join ' . $xoopsDB->prefix('tad_web') . ' as b on a.WebID=b.WebID
            left join ' . $xoopsDB->prefix('apply') . ' as c on b.WebOwnerUid=c.uid
            left join ' . $xoopsDB->prefix('tad_web_cate') . " as d on a.CateID=d.CateID
            where b.`WebEnable`='1' and (d.CateEnable='1' or a.CateID='0') $andCounty $andCity $andSchoolName
            order by a.VideoDate desc , a.VideoID desc";
        } elseif (!empty($tag)) {
            $sql = 'select distinct a.* from ' . $xoopsDB->prefix('tad_web_video') . ' as a
            left join ' . $xoopsDB->prefix('tad_web') . ' as b on a.WebID=b.WebID
            join ' . $xoopsDB->prefix('tad_web_tags') . " as c on c.col_name='VideoID' and c.col_sn=a.VideoID
            left join " . $xoopsDB->prefix('tad_web_cate') . " as d on a.CateID=d.CateID
            where b.`WebEnable`='1'and (d.CateEnable='1' or a.CateID='0') and c.`tag_name`='{$tag}' $andWebID $andCateID
            order by a.VideoDate desc , a.VideoID desc";
        } else {
            $sql = 'select a.* from ' . $xoopsDB->prefix('tad_web_video') . ' as a
            left join ' . $xoopsDB->prefix('tad_web') . ' as b on a.WebID=b.WebID
            left join ' . $xoopsDB->prefix('tad_web_cate') . " as c on a.CateID=c.CateID
            where b.`WebEnable`='1' and (c.CateEnable='1' or a.CateID='0') $andWebID $andCateID
            order by $andCateDataSort a.VideoDate desc , a.VideoID desc";
        }

        if (empty($CateID)) {
            $to_limit = empty($limit) ? 20 : $limit;

            //getPageBar($原sql語法, 每頁顯示幾筆資料, 最多顯示幾個頁數選項);
            $PageBar = Utility::getPageBar($sql, $to_limit, 10);
            $bar = $PageBar['bar'];
            $sql = $PageBar['sql'];
            $total = $PageBar['total'];
        }

        $result = $xoopsDB->query($sql) or Utility::web_error($sql, __FILE__, __LINE__);

        $main_data = [];

        $i = 0;

        $Webs = getAllWebInfo();

        $cate = $this->WebCate->get_tad_web_cate_arr(null, null, 'video');

        while (false !== ($all = $xoopsDB->fetchArray($result))) {
            //以下會產生這些變數： $VideoID , $VideoName , $VideoDesc , $VideoDate , $VideoPlace , $uid , $WebID, $VideoCount , $Youtube, $VideoSort
            foreach ($all as $k => $v) {
                $$k = $v;
            }

            $power = $this->Power->check_power("read", "CateID", $CateID, 'video');
            if (!$power) {
                continue;
            }

            $main_data[$i] = $all;
            $main_data[$i]['id'] = $VideoID;
            $main_data[$i]['id_name'] = 'VideoID';
            $main_data[$i]['title'] = $VideoName;
            $main_data[$i]['isAssistant'] = is_assistant($this->WebID, 'video', $CateID, 'VideoID', $VideoID);
            if (_IS_EZCLASS) {
                $main_data[$i]['VideoCount'] = redis_do($this->WebID, 'get', 'video', "VideoCount:$VideoID");
            }

            $this->WebCate->set_WebID($WebID);

            $main_data[$i]['cate'] = isset($cate[$CateID]) ? $cate[$CateID] : '';
            $main_data[$i]['WebTitle'] = "<a href='index.php?WebID={$WebID}'>{$Webs[$WebID]}</a>";
            // $main_data[$i]['isMyWeb']  = in_array($WebID, $MyWebs) ? 1 : 0;
            $main_data[$i]['isMyWeb'] = $isMyWeb;
            if (empty($VideoPlace)) {
                $VideoPlace = $this->tad_web_getYTid($Youtube);
                if (!empty($VideoPlace)) {
                    $main_data[$i]['VideoPlace'] = $VideoPlace;
                    $sql = 'update ' . $xoopsDB->prefix('tad_web_video') . " set `VideoPlace` = '{$VideoPlace}' where VideoID='{$VideoID}'";
                    $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);
                }
            }

            $i++;
        }

        $SweetAlert = new SweetAlert();
        $SweetAlert->render('delete_video_func', "video.php?op=delete&WebID={$this->WebID}&VideoID=", 'VideoID');

        if ('return' === $mode) {
            $data['main_data'] = $main_data;
            $data['total'] = $total;
            $data['display'] = $display;

            return $data;
        } else {
            $xoopsTpl->assign('video_data', $main_data);
            $xoopsTpl->assign('bar', $bar);
            $xoopsTpl->assign('display', $display);
            $xoopsTpl->assign('video', get_db_plugin($this->WebID, 'video'));
            return $total;
        }
    }

    //以流水號秀出某筆tad_web_video資料內容
    public function show_one($VideoID = '')
    {
        global $xoopsDB, $xoopsTpl, $isMyWeb;
        if (empty($VideoID)) {
            return;
        }

        $VideoID = (int) $VideoID;

        $sql = 'select * from ' . $xoopsDB->prefix('tad_web_video') . " where VideoID='{$VideoID}'";
        $result = $xoopsDB->query($sql) or Utility::web_error($sql, __FILE__, __LINE__);
        $all = $xoopsDB->fetchArray($result);

        //以下會產生這些變數： $VideoID , $VideoName , $VideoDesc , $VideoDate , $VideoPlace , $uid , $WebID , $VideoCount , $Youtube, $VideoSort
        foreach ($all as $k => $v) {
            $$k = $v;
        }

        $power = $this->Power->check_power("read", "CateID", $CateID, 'video');
        if (!$power) {
            redirect_header("video.php?WebID={$this->WebID}", 3, _MD_TCW_NOW_READ_POWER);
        }

        if (_IS_EZCLASS) {
            $VideoCount = $data['VideoCount'] = $this->add_counter($VideoID);
        } else {
            $this->add_counter($VideoID);
        }

        if (empty($uid)) {
            redirect_header('index.php', 3, _MD_TCW_DATA_NOT_EXIST);
        }

        $uid_name = \XoopsUser::getUnameFromId($uid, 1);
        if (empty($uid_name)) {
            $uid_name = \XoopsUser::getUnameFromId($uid, 0);
        }

        $xoopsTpl->assign('VideoName', $VideoName);
        $xoopsTpl->assign('VideoDate', $VideoDate);
        $xoopsTpl->assign('VideoPlace', $VideoPlace);
        $xoopsTpl->assign('VideoDesc', nl2br($VideoDesc));
        $xoopsTpl->assign('uid_name', $uid_name);
        $xoopsTpl->assign('VideoCountInfo', sprintf(_MD_TCW_VIDEOCOUNTINFO, $VideoCount));
        $xoopsTpl->assign('VideoSort', $VideoSort);
        $xoopsTpl->assign('VideoID', $VideoID);
        $xoopsTpl->assign('VideoInfo', sprintf(_MD_TCW_INFO, $uid_name, $VideoDate, $VideoCount));

        $xoopsTpl->assign('xoops_pagetitle', $VideoName);
        $xoopsTpl->assign('fb_description', xoops_substr(strip_tags($VideoDesc), 0, 300));

        //取得單一分類資料
        $cate = $this->WebCate->get_tad_web_cate($CateID);
        if ($CateID and '1' != $cate['CateEnable']) {
            return;
        }
        $xoopsTpl->assign('cate', $cate);

        $SweetAlert = new SweetAlert();
        $SweetAlert->render('delete_video_func', "video.php?op=delete&WebID={$this->WebID}&VideoID=", 'VideoID');
        $xoopsTpl->assign('fb_comments', fb_comments($this->setup['use_fb_comments']));

        $xoopsTpl->assign('tags', $this->tags->list_tags('VideoID', $VideoID, 'video'));
        $xoopsTpl->assign('isAssistant', is_assistant($this->WebID, 'video', $CateID, 'VideoID', $VideoID));
    }

    //tad_web_video編輯表單
    public function edit_form($VideoID = '')
    {
        global $xoopsDB, $xoopsUser, $MyWebs, $isMyWeb, $xoopsTpl, $TadUpFiles, $plugin_menu_var;
        chk_self_web($this->WebID, $_SESSION['isAssistant']['video']);
        get_quota($this->WebID);

        //抓取預設值
        if (!empty($VideoID)) {
            $DBV = $this->get_one_data($VideoID);
        } else {
            $DBV = [];
        }

        //預設值設定

        //設定「VideoID」欄位預設值
        $VideoID = (!isset($DBV['VideoID'])) ? '' : $DBV['VideoID'];
        $xoopsTpl->assign('VideoID', $VideoID);

        //設定「VideoName」欄位預設值
        $VideoName = (!isset($DBV['VideoName'])) ? '' : $DBV['VideoName'];
        $xoopsTpl->assign('VideoName', $VideoName);

        //設定「VideoDesc」欄位預設值
        $VideoDesc = (!isset($DBV['VideoDesc'])) ? '' : $DBV['VideoDesc'];
        $xoopsTpl->assign('VideoDesc', $VideoDesc);

        //設定「VideoDate」欄位預設值
        $VideoDate = (!isset($DBV['VideoDate'])) ? '' : $DBV['VideoDate'];
        $xoopsTpl->assign('VideoDate', $VideoDate);

        //設定「VideoPlace」欄位預設值
        $VideoPlace = (!isset($DBV['VideoPlace'])) ? '' : $DBV['VideoPlace'];
        $xoopsTpl->assign('VideoPlace', $VideoPlace);

        //設定「uid」欄位預設值
        $user_uid = ($xoopsUser) ? $xoopsUser->getVar('uid') : '';
        $uid = (!isset($DBV['uid'])) ? $user_uid : $DBV['uid'];
        $xoopsTpl->assign('uid', $uid);

        //設定「WebID」欄位預設值
        $WebID = (!isset($DBV['WebID'])) ? $this->WebID : $DBV['WebID'];
        $xoopsTpl->assign('WebID', $WebID);

        //設定「VideoCount」欄位預設值
        $VideoCount = (!isset($DBV['VideoCount'])) ? '' : $DBV['VideoCount'];
        $xoopsTpl->assign('VideoCount', $VideoCount);

        //設定「Youtube」欄位預設值
        $Youtube = (!isset($DBV['Youtube'])) ? '' : $DBV['Youtube'];
        $xoopsTpl->assign('Youtube', $Youtube);

        //設定「VideoSort」欄位預設值
        $VideoSort = (!isset($DBV['VideoSort'])) ? '' : $DBV['VideoSort'];
        $xoopsTpl->assign('VideoSort', $VideoSort);

        //設定「CateID」欄位預設值
        $DefCateID = isset($_SESSION['isAssistant']['video']) ? $_SESSION['isAssistant']['video'] : '';
        $CateID = (!isset($DBV['CateID'])) ? $DefCateID : $DBV['CateID'];
        $this->WebCate->set_button_value($plugin_menu_var['video']['short'] . _MD_TCW_CATE_TOOLS);
        $this->WebCate->set_default_option_text(sprintf(_MD_TCW_SELECT_PLUGIN_CATE, $plugin_menu_var['video']['short']));
        $cate_menu = isset($_SESSION['isAssistant']['video']) ? $this->WebCate->hidden_cate_menu($CateID) : $this->WebCate->cate_menu($CateID);
        $xoopsTpl->assign('cate_menu_form', $cate_menu);

        $op = (empty($VideoID)) ? 'insert' : 'update';

        $FormValidator = new FormValidator('#myForm', true);
        $FormValidator->render();

        $xoopsTpl->assign('next_op', $op);

        $tags_form = $this->tags->tags_menu('VideoID', $VideoID);
        $xoopsTpl->assign('tags_form', $tags_form);
    }

    //新增資料到tad_web_video中
    public function insert()
    {
        global $xoopsDB, $xoopsUser, $WebOwnerUid;
        if (isset($_SESSION['isAssistant']['video'])) {
            $uid = $WebOwnerUid;
        } else {
            $uid = ($xoopsUser) ? $xoopsUser->uid() : '';
        }

        $myts = \MyTextSanitizer::getInstance();
        $VideoName = $myts->addSlashes($_POST['VideoName']);
        $VideoDesc = $myts->addSlashes($_POST['VideoDesc']);
        $Youtube = $myts->addSlashes($_POST['Youtube']);
        $newCateName = $myts->addSlashes($_POST['newCateName']);
        $tag_name = $myts->addSlashes($_POST['tag_name']);
        $CateID = (int) $_POST['CateID'];
        $WebID = (int) $_POST['WebID'];

        $VideoPlace = $this->tad_web_getYTid($Youtube);
        $VideoCount = (int) $_POST['VideoCount'];
        if ($newCateName != '') {
            $CateID = $this->WebCate->save_tad_web_cate($CateID, $newCateName);
        }

        $VideoSort = $this->max_sort($WebID, $CateID);

        $sql = 'insert into ' . $xoopsDB->prefix('tad_web_video') . "
        (`CateID`, `VideoName` , `VideoDesc` , `VideoDate` , `VideoPlace` , `uid` , `WebID` , `VideoCount` , `Youtube` , `VideoSort`)
        values('{$CateID}', '{$VideoName}' , '{$VideoDesc}' , now() , '{$VideoPlace}' , '{$uid}' , '{$WebID}' , '{$VideoCount}' , '{$Youtube}' , '{$VideoSort}')";
        $xoopsDB->query($sql) or Utility::web_error($sql, __FILE__, __LINE__);

        //取得最後新增資料的流水編號
        $VideoID = $xoopsDB->getInsertId();
        save_assistant_post('video', $CateID, 'VideoID', $VideoID);
        check_quota($this->WebID);
        //儲存標籤
        $this->tags->save_tags('VideoID', $VideoID, $tag_name, $_POST['tags']);
        return $VideoID;
    }

    //抓取 Youtube ID
    public function tad_web_getYTid($ytURL = '')
    {
        if ('https://youtu.be/' === substr($ytURL, 0, 16)) {
            return substr($ytURL, 16);
        } elseif ('https://youtu.be/' === substr($ytURL, 0, 17)) {
            return substr($ytURL, 17);
        } else {
            parse_str(parse_url($ytURL, PHP_URL_QUERY), $params);
            return $params['v'];
        }
    }

    //更新tad_web_video某一筆資料
    public function update($VideoID = '')
    {
        global $xoopsDB;

        $myts = \MyTextSanitizer::getInstance();
        $VideoName = $myts->addSlashes($_POST['VideoName']);
        $VideoDesc = $myts->addSlashes($_POST['VideoDesc']);
        $Youtube = $myts->addSlashes($_POST['Youtube']);
        $newCateName = $myts->addSlashes($_POST['newCateName']);
        $tag_name = $myts->addSlashes($_POST['tag_name']);
        $CateID = (int) $_POST['CateID'];
        $WebID = (int) $_POST['WebID'];

        $VideoPlace = $this->tad_web_getYTid($Youtube);
        $VideoCount = (int) $_POST['VideoCount'];
        $VideoSort = (int) $_POST['VideoSort'];
        if ($newCateName != '') {
            $CateID = $this->WebCate->save_tad_web_cate($CateID, $newCateName);
        }

        if (!is_assistant($this->WebID, 'video', $CateID, 'VideoID', $VideoID)) {
            $anduid = onlyMine();
        }

        $sql = 'update ' . $xoopsDB->prefix('tad_web_video') . " set
        `CateID` = '{$CateID}' ,
        `VideoName` = '{$VideoName}' ,
        `VideoDesc` = '{$VideoDesc}' ,
        `VideoDate` = now() ,
        `VideoPlace` = '{$VideoPlace}'
        where VideoID='$VideoID' $anduid";
        $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);
        check_quota($this->WebID);
        //儲存標籤
        $this->tags->save_tags('VideoID', $VideoID, $tag_name, $_POST['tags']);
        return $VideoID;
    }

    //刪除tad_web_video某筆資料資料
    public function delete($VideoID = '')
    {
        global $xoopsDB;
        $sql = 'select CateID from ' . $xoopsDB->prefix('tad_web_video') . " where VideoID='$VideoID'";
        $result = $xoopsDB->query($sql) or Utility::web_error($sql, __FILE__, __LINE__);
        list($CateID) = $xoopsDB->fetchRow($result);
        if (!is_assistant($this->WebID, 'video', $CateID, 'VideoID', $VideoID)) {
            $anduid = onlyMine();
        }
        $sql = 'delete from ' . $xoopsDB->prefix('tad_web_video') . " where VideoID='$VideoID' $anduid";
        $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);
        check_quota($this->WebID);
        //刪除標籤
        $this->tags->delete_tags('VideoID', $VideoID);
    }

    //刪除所有資料
    public function delete_all()
    {
        global $xoopsDB, $TadUpFiles;
        $allCateID = [];
        $sql = 'select VideoID,CateID from ' . $xoopsDB->prefix('tad_web_video') . " where WebID='{$this->WebID}'";
        $result = $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);
        while (list($VideoID, $CateID) = $xoopsDB->fetchRow($result)) {
            $this->delete($VideoID);
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
        $sql = 'select count(*) from ' . $xoopsDB->prefix('tad_web_video') . " where WebID='{$this->WebID}'";
        $result = $xoopsDB->query($sql) or Utility::web_error($sql, __FILE__, __LINE__);
        list($count) = $xoopsDB->fetchRow($result);
        return $count;
    }

    //新增tad_web_video計數器
    public function add_counter($VideoID = '')
    {
        global $xoopsDB;

        if (_IS_EZCLASS) {
            $VideoCount = redis_do($this->WebID, 'get', 'video', "VideoCount:$VideoID");
            if (empty($VideoCount)) {
                $sql = 'select VideoCount from ' . $xoopsDB->prefix('tad_web_video') . " where VideoID='$VideoID'";
                $result = $xoopsDB->query($sql) or Utility::web_error($sql, __FILE__, __LINE__);
                list($VideoCount) = $xoopsDB->fetchRow($result);
                redis_do($this->WebID, 'set', 'video', "VideoCount:$VideoID", $VideoCount);
            }
            return redis_do($this->WebID, 'incr', 'video', "VideoCount:$VideoID");
        } else {
            $sql = 'update ' . $xoopsDB->prefix('tad_web_video') . " set `VideoCount`=`VideoCount`+1 where `VideoID`='{$VideoID}'";
            $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);
        }
    }

    //以流水號取得某筆tad_web_video資料
    public function get_one_data($VideoID = '')
    {
        global $xoopsDB;
        if (empty($VideoID)) {
            return;
        }

        $sql = 'select * from ' . $xoopsDB->prefix('tad_web_video') . " where VideoID='$VideoID'";
        $result = $xoopsDB->query($sql) or Utility::web_error($sql, __FILE__, __LINE__);
        $data = $xoopsDB->fetchArray($result);

        if (_IS_EZCLASS) {
            $data['VideoCount'] = redis_do($this->WebID, 'get', 'video', "VideoCount:$VideoID");
        }
        return $data;
    }

    //匯出資料
    public function export_data($start_date, $end_date, $CateID = '')
    {
        global $xoopsDB, $xoopsTpl, $TadUpFiles, $MyWebs;
        $andCateID = empty($CateID) ? '' : "and `CateID`='$CateID'";
        $andStart = empty($start_date) ? '' : "and VideoDate >= '{$start_date}'";
        $andEnd = empty($end_date) ? '' : "and VideoDate <= '{$end_date}'";

        $sql = 'select VideoID,VideoName,VideoDate,CateID from ' . $xoopsDB->prefix('tad_web_video') . " where WebID='{$this->WebID}' {$andStart} {$andEnd} {$andCateID} order by VideoDate";
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

    //自動取得tad_web_video的最新排序
    public function max_sort($WebID, $CateID)
    {
        global $xoopsDB;
        $sql = 'select max(`VideoSort`) from ' . $xoopsDB->prefix('tad_web_video') . " where WebID='$WebID' and CateID='{$CateID}'";
        $result = $xoopsDB->query($sql) or Utility::web_error($sql, __FILE__, __LINE__);
        list($sort) = $xoopsDB->fetchRow($result);
        return ++$sort;
    }
}
