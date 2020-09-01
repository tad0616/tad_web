<?php
use Xmf\Request;
use XoopsModules\Tadtools\FormValidator;
use XoopsModules\Tadtools\SweetAlert;
use XoopsModules\Tadtools\Utility;
use XoopsModules\Tad_web\Power;
use XoopsModules\Tad_web\Tags;
use XoopsModules\Tad_web\WebCate;

class tad_web_link
{
    public $WebID = 0;
    public $web_cate;

    public function __construct($WebID)
    {
        $this->WebID = $WebID;
        $this->WebCate = new WebCate($WebID, 'link', 'tad_web_link');
        $this->tags = new Tags($WebID);
        $this->Power = new Power($WebID);
    }

    //好站連結
    public function list_all($CateID = '', $limit = '', $mode = 'assign', $tag = '', $hide_link = 0, $hide_desc = 0)
    {
        global $xoopsDB, $xoopsTpl, $MyWebs, $isMyWeb, $plugin_menu_var;

        $power = $this->Power->check_power("read", "CateID", $CateID, 'link');
        if (!$power) {
            redirect_header("link.php?WebID={$this->WebID}", 3, _MD_TCW_NOW_READ_POWER);
        }

        $andWebID = (empty($this->WebID)) ? '' : "and a.WebID='{$this->WebID}'";

        $andCateID = '';
        if ('assign' === $mode) {
            //取得tad_web_cate所有資料陣列
            if (!empty($plugin_menu_var)) {
                $this->WebCate->set_button_value($plugin_menu_var['link']['short'] . _MD_TCW_CATE_TOOLS);
                $this->WebCate->set_default_option_text(sprintf(_MD_TCW_SELECT_PLUGIN_CATE, $plugin_menu_var['link']['short']));
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
                $xoopsTpl->assign('LinkDefCateID', $CateID);
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

            $sql = 'select a.* from ' . $xoopsDB->prefix('tad_web_link') . ' as a
            left join ' . $xoopsDB->prefix('tad_web') . ' as b on a.WebID=b.WebID
            left join ' . $xoopsDB->prefix('apply') . ' as c on b.WebOwnerUid=c.uid
            left join ' . $xoopsDB->prefix('tad_web_cate') . " as d on a.CateID=d.CateID
            where b.`WebEnable`='1' and (d.CateEnable='1' or a.CateID='0') $andCounty $andCity $andSchoolName
            order by a.LinkID desc";
        } elseif (!empty($tag)) {
            $sql = 'select distinct a.* from ' . $xoopsDB->prefix('tad_web_link') . ' as a
            left join ' . $xoopsDB->prefix('tad_web') . ' as b on a.WebID=b.WebID
            join ' . $xoopsDB->prefix('tad_web_tags') . " as c on c.col_name='LinkID' and c.col_sn=a.LinkID
            left join " . $xoopsDB->prefix('tad_web_cate') . " as d on a.CateID=d.CateID
            where b.`WebEnable`='1' and (d.CateEnable='1' or a.CateID='0') and c.`tag_name`='{$tag}' $andWebID $andCateID
            order by a.LinkID desc";
        } else {
            $sql = 'select a.* from ' . $xoopsDB->prefix('tad_web_link') . ' as a
            left join ' . $xoopsDB->prefix('tad_web') . ' as b on a.WebID=b.WebID
            left join ' . $xoopsDB->prefix('tad_web_cate') . " as c on a.CateID=c.CateID
            where b.`WebEnable`='1' and (c.CateEnable='1' or a.CateID='0') $andWebID $andCateID
            order by a.LinkSort, a.LinkID desc";
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

        $cate = $this->WebCate->get_tad_web_cate_arr(null, null, 'link');

        while (false !== ($all = $xoopsDB->fetchArray($result))) {
            //以下會產生這些變數： $LinkID , $LinkTitle , $LinkDesc , $LinkUrl , $LinkCounter , $LinkSort , $WebID , $uid
            foreach ($all as $k => $v) {
                $$k = $v;
            }

            $power = $this->Power->check_power("read", "CateID", $CateID, 'link');
            if (!$power) {
                continue;
            }

            $main_data[$i] = $all;
            $main_data[$i]['id'] = $LinkID;
            $main_data[$i]['id_name'] = 'LinkID';
            $main_data[$i]['title'] = $LinkTitle;

            $main_data[$i]['isAssistant'] = is_assistant($CateID, 'LinkID', $LinkID);

            $this->WebCate->set_WebID($WebID);

            $main_data[$i]['cate'] = isset($cate[$CateID]) ? $cate[$CateID] : '';
            $main_data[$i]['WebTitle'] = "<a href='index.php?WebID={$WebID}'>{$Webs[$WebID]}</a>";
            // $main_data[$i]['isMyWeb']  = in_array($WebID, $MyWebs) ? 1 : 0;
            $main_data[$i]['isMyWeb'] = $isMyWeb;
            $main_data[$i]['LinkShortUrl'] = xoops_substr($LinkUrl, 0, 100, '...');
            $LinkDesc = nl2br(xoops_substr(strip_tags($LinkDesc), 0, 150));
            $main_data[$i]['LinkDesc'] = $LinkDesc;
            $main_data[$i]['hide_link'] = $hide_link;
            $main_data[$i]['hide_desc'] = $hide_desc;
            $i++;
        }

        $SweetAlert = new SweetAlert();
        $SweetAlert->render('delete_link_func', "link.php?op=delete&WebID={$this->WebID}&LinkID=", 'LinkID');

        if ('return' === $mode) {
            $data['main_data'] = $main_data;
            $data['total'] = $total;
            return $data;
        } else {
            $xoopsTpl->assign('link_data', $main_data);
            $xoopsTpl->assign('bar', $bar);
            $xoopsTpl->assign('link', get_db_plugin($this->WebID, 'link'));
            return $total;
        }
    }

    //以流水號秀出某筆tad_web_link資料內容
    public function show_one($LinkID = '')
    {
        global $xoopsDB;
        if (empty($LinkID)) {
            return;
        }

        $LinkID = (int) $LinkID;

        $sql = 'select CateID,LinkUrl from ' . $xoopsDB->prefix('tad_web_link') . " where LinkID='{$LinkID}'";
        $result = $xoopsDB->query($sql) or Utility::web_error($sql, __FILE__, __LINE__);
        list($CateID, $LinkUrl) = $xoopsDB->fetchRow($result);

        $power = $this->Power->check_power("read", "CateID", $CateID, 'link');
        if (!$power) {
            redirect_header("link.php?WebID={$this->WebID}", 3, _MD_TCW_NOW_READ_POWER);
        }
        $this->add_counter($LinkID);

        header("location: {$LinkUrl}");
        exit;
    }

    //tad_web_link編輯表單
    public function edit_form($LinkID = '')
    {
        global $xoopsDB, $xoopsUser, $MyWebs, $isMyWeb, $xoopsTpl, $plugin_menu_var;

        chk_self_web($this->WebID, $_SESSION['isAssistant']['link']);
        get_quota($this->WebID);

        //抓取預設值
        if (!empty($LinkID)) {
            $DBV = $this->get_one_data($LinkID);
        } else {
            $DBV = [];
        }

        //預設值設定

        //設定「LinkID」欄位預設值
        $LinkID = (!isset($DBV['LinkID'])) ? '' : $DBV['LinkID'];
        $xoopsTpl->assign('LinkID', $LinkID);

        //設定「LinkTitle」欄位預設值
        $LinkTitle = (!isset($DBV['LinkTitle'])) ? '' : $DBV['LinkTitle'];
        $xoopsTpl->assign('LinkTitle', $LinkTitle);

        //設定「LinkDesc」欄位預設值
        $LinkDesc = (!isset($DBV['LinkDesc'])) ? '' : $DBV['LinkDesc'];
        $xoopsTpl->assign('LinkDesc', $LinkDesc);

        //設定「LinkUrl」欄位預設值
        $LinkUrl = (!isset($DBV['LinkUrl'])) ? '' : $DBV['LinkUrl'];
        $xoopsTpl->assign('LinkUrl', $LinkUrl);

        //設定「LinkCounter」欄位預設值
        $LinkCounter = (!isset($DBV['LinkCounter'])) ? '' : $DBV['LinkCounter'];
        $xoopsTpl->assign('LinkCounter', $LinkCounter);

        //設定「LinkSort」欄位預設值
        $LinkSort = (!isset($DBV['LinkSort'])) ? $this->max_sort() : $DBV['LinkSort'];
        $xoopsTpl->assign('LinkSort', $LinkSort);

        //設定「WebID」欄位預設值
        $WebID = (!isset($DBV['WebID'])) ? $this->WebID : $DBV['WebID'];
        $xoopsTpl->assign('WebID', $WebID);

        //設定「uid」欄位預設值
        $user_uid = ($xoopsUser) ? $xoopsUser->getVar('uid') : '';
        $uid = (!isset($DBV['uid'])) ? $user_uid : $DBV['uid'];

        //設定「CateID」欄位預設值
        $DefCateID = isset($_SESSION['isAssistant']['link']) ? $_SESSION['isAssistant']['link'] : '';
        $CateID = (!isset($DBV['CateID'])) ? $DefCateID : $DBV['CateID'];
        $this->WebCate->set_button_value($plugin_menu_var['link']['short'] . _MD_TCW_CATE_TOOLS);
        $this->WebCate->set_default_option_text(sprintf(_MD_TCW_SELECT_PLUGIN_CATE, $plugin_menu_var['link']['short']));
        $cate_menu = isset($_SESSION['isAssistant']['link']) ? $this->WebCate->hidden_cate_menu($CateID) : $this->WebCate->cate_menu($CateID);
        $xoopsTpl->assign('cate_menu_form', $cate_menu);

        $op = (empty($LinkID)) ? 'insert' : 'update';
        //$op="replace_tad_web_link";

        $FormValidator = new FormValidator('#myForm', true);
        $FormValidator->render();

        $xoopsTpl->assign('next_op', $op);

        $tags_form = $this->tags->tags_menu('LinkID', $LinkID);
        $xoopsTpl->assign('tags_form', $tags_form);
    }

    //新增資料到tad_web_link中
    public function insert()
    {
        global $xoopsDB, $xoopsUser, $WebOwnerUid;
        if (isset($_SESSION['isAssistant']['link'])) {
            $uid = $WebOwnerUid;
        } else {
            $uid = ($xoopsUser) ? $xoopsUser->uid() : '';
        }

        $myts = \MyTextSanitizer::getInstance();
        $LinkTitle = $myts->addSlashes($_POST['LinkTitle']);
        $LinkDesc = $myts->addSlashes($_POST['LinkDesc']);
        $LinkUrl = $myts->addSlashes($_POST['LinkUrl']);
        $newCateName = $myts->addSlashes($_POST['newCateName']);
        $tag_name = $myts->addSlashes($_POST['tag_name']);
        $LinkCounter = (int) $_POST['LinkCounter'];
        $LinkSort = (int) $_POST['LinkSort'];
        $CateID = (int) $_POST['CateID'];
        $WebID = (int) $_POST['WebID'];

        $CateID = $this->WebCate->save_tad_web_cate($CateID, $newCateName);

        $sql = 'insert into ' . $xoopsDB->prefix('tad_web_link') . "
          (`CateID`, `LinkTitle` , `LinkDesc` , `LinkUrl` , `LinkCounter` , `LinkSort` , `WebID` , `uid`)
          values('{$CateID}', '{$LinkTitle}' , '{$LinkDesc}' , '{$LinkUrl}' , '{$LinkCounter}' , '{$LinkSort}' , '{$WebID}' , '{$uid}')";
        $xoopsDB->query($sql) or Utility::web_error($sql, __FILE__, __LINE__);

        //取得最後新增資料的流水編號
        $LinkID = $xoopsDB->getInsertId();
        save_assistant_post($CateID, 'LinkID', $LinkID);
        check_quota($this->WebID);

        //儲存標籤
        $this->tags->save_tags('LinkID', $LinkID, $tag_name, $_POST['tags']);
        return $LinkID;
    }

    //更新tad_web_link某一筆資料
    public function update($LinkID = '')
    {
        global $xoopsDB, $xoopsUser;

        $myts = \MyTextSanitizer::getInstance();
        $LinkTitle = $myts->addSlashes($_POST['LinkTitle']);
        $LinkDesc = $myts->addSlashes($_POST['LinkDesc']);
        $LinkUrl = $myts->addSlashes($_POST['LinkUrl']);
        $newCateName = $myts->addSlashes($_POST['newCateName']);
        $tag_name = $myts->addSlashes($_POST['tag_name']);
        $CateID = (int) $_POST['CateID'];
        $WebID = (int) $_POST['WebID'];

        $CateID = $this->WebCate->save_tad_web_cate($CateID, $newCateName);

        if (!is_assistant($CateID, 'LinkID', $LinkID)) {
            $anduid = onlyMine();
        }

        $sql = 'update ' . $xoopsDB->prefix('tad_web_link') . " set
       `CateID` = '{$CateID}' ,
       `LinkTitle` = '{$LinkTitle}' ,
       `LinkDesc` = '{$LinkDesc}' ,
       `LinkUrl` = '{$LinkUrl}' ,
       `WebID` = '{$WebID}'
        where LinkID='$LinkID' $anduid";
        $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);
        check_quota($this->WebID);

        //儲存標籤
        $this->tags->save_tags('LinkID', $LinkID, $tag_name, $_POST['tags']);
        return $LinkID;
    }

    //刪除tad_web_link某筆資料資料
    public function delete($LinkID = '')
    {
        global $xoopsDB;

        $sql = 'select CateID from ' . $xoopsDB->prefix('tad_web_link') . " where LinkID='$LinkID'";
        $result = $xoopsDB->query($sql) or Utility::web_error($sql, __FILE__, __LINE__);
        list($CateID) = $xoopsDB->fetchRow($result);

        if (!is_assistant($CateID, 'LinkID', $LinkID)) {
            $anduid = onlyMine();
        }

        $sql = 'delete from ' . $xoopsDB->prefix('tad_web_link') . " where LinkID='$LinkID' $anduid";
        $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);
        check_quota($this->WebID);

        $myts = \MyTextSanitizer::getInstance();
        $tag_name = $myts->addSlashes($_POST['tag_name']);
        //儲存標籤
        $this->tags->save_tags('LinkID', $LinkID, $tag_name, $_POST['tags']);
    }

    //刪除所有資料
    public function delete_all()
    {
        global $xoopsDB, $TadUpFiles;
        $allCateID = [];
        $sql = 'select LinkID,CateID from ' . $xoopsDB->prefix('tad_web_link') . " where WebID='{$this->WebID}'";
        $result = $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);
        while (list($LinkID, $CateID) = $xoopsDB->fetchRow($result)) {
            $this->delete($LinkID);
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
        $sql = 'select count(*) from ' . $xoopsDB->prefix('tad_web_link') . " where WebID='{$this->WebID}'";
        $result = $xoopsDB->query($sql) or Utility::web_error($sql, __FILE__, __LINE__);
        list($count) = $xoopsDB->fetchRow($result);
        return $count;
    }

    //自動取得tad_web_link的最新排序
    public function max_sort()
    {
        global $xoopsDB;
        $sql = 'SELECT max(`LinkSort`) FROM ' . $xoopsDB->prefix('tad_web_link');
        $result = $xoopsDB->query($sql) or Utility::web_error($sql, __FILE__, __LINE__);
        list($sort) = $xoopsDB->fetchRow($result);
        return ++$sort;
    }

    //新增tad_web_link計數器
    public function add_counter($LinkID = '')
    {
        global $xoopsDB;
        $sql = 'update ' . $xoopsDB->prefix('tad_web_link') . " set `LinkCounter`=`LinkCounter`+1 where `LinkID`='{$LinkID}'";
        $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);
    }

    //以流水號取得某筆tad_web_link資料
    public function get_one_data($LinkID = '')
    {
        global $xoopsDB;
        if (empty($LinkID)) {
            return;
        }

        $sql = 'select * from ' . $xoopsDB->prefix('tad_web_link') . " where LinkID='$LinkID'";
        $result = $xoopsDB->query($sql) or Utility::web_error($sql, __FILE__, __LINE__);
        $data = $xoopsDB->fetchArray($result);
        return $data;
    }
}
