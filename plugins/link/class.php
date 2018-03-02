<?php

class tad_web_link
{
    public $WebID = 0;
    public $web_cate;

    public function __construct($WebID)
    {
        $this->WebID    = $WebID;
        $this->web_cate = new web_cate($WebID, "link", "tad_web_link");
        $this->tags     = new tags($WebID);
    }

    //好站連結
    public function list_all($CateID = "", $limit = "", $mode = "assign", $tag = '', $hide_link = 0, $hide_desc = 0)
    {
        global $xoopsDB, $xoopsTpl, $MyWebs, $isMyWeb, $plugin_menu_var;

        $andWebID = (empty($this->WebID)) ? "" : "and a.WebID='{$this->WebID}'";

        $andCateID = "";
        if ($mode == "assign") {
            //取得tad_web_cate所有資料陣列
            if (!empty($plugin_menu_var)) {
                $this->web_cate->set_button_value($plugin_menu_var['link']['short'] . _MD_TCW_CATE_TOOLS);
                $this->web_cate->set_default_option_text(sprintf(_MD_TCW_SELECT_PLUGIN_CATE, $plugin_menu_var['link']['short']));
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
                $xoopsTpl->assign('LinkDefCateID', $CateID);
            }
        }

        if (_IS_EZCLASS and !empty($_GET['county'])) {
            //https://class.tn.edu.tw/modules/tad_web/index.php?county=臺南市&city=永康區&SchoolName=XX國小
            include_once $GLOBALS['xoops']->path('/modules/system/include/functions.php');
            $county        = system_CleanVars($_REQUEST, 'county', '', 'string');
            $city          = system_CleanVars($_REQUEST, 'city', '', 'string');
            $SchoolName    = system_CleanVars($_REQUEST, 'SchoolName', '', 'string');
            $andCounty     = !empty($county) ? "and c.county='{$county}'" : "";
            $andCity       = !empty($city) ? "and c.city='{$city}'" : "";
            $andSchoolName = !empty($SchoolName) ? "and c.SchoolName='{$SchoolName}'" : "";

            $sql = "select a.* from " . $xoopsDB->prefix("tad_web_link") . " as a
            left join " . $xoopsDB->prefix("tad_web") . " as b on a.WebID=b.WebID
            left join " . $xoopsDB->prefix("apply") . " as c on b.WebOwnerUid=c.uid
            left join " . $xoopsDB->prefix("tad_web_cate") . " as d on a.CateID=d.CateID
            where b.`WebEnable`='1' and (d.CateEnable='1' or a.CateID='0') $andCounty $andCity $andSchoolName
            order by a.LinkID desc";
        } elseif (!empty($tag)) {
            $sql = "select distinct a.* from " . $xoopsDB->prefix("tad_web_link") . " as a
            left join " . $xoopsDB->prefix("tad_web") . " as b on a.WebID=b.WebID
            join " . $xoopsDB->prefix("tad_web_tags") . " as c on c.col_name='LinkID' and c.col_sn=a.LinkID
            left join " . $xoopsDB->prefix("tad_web_cate") . " as d on a.CateID=d.CateID
            where b.`WebEnable`='1' and (d.CateEnable='1' or a.CateID='0') and c.`tag_name`='{$tag}' $andWebID $andCateID
            order by a.LinkID desc";
        } else {
            $sql = "select a.* from " . $xoopsDB->prefix("tad_web_link") . " as a
            left join " . $xoopsDB->prefix("tad_web") . " as b on a.WebID=b.WebID
            left join " . $xoopsDB->prefix("tad_web_cate") . " as c on a.CateID=c.CateID
            where b.`WebEnable`='1' and (c.CateEnable='1' or a.CateID='0') $andWebID $andCateID
            order by a.LinkSort, a.LinkID desc";
        }

        if (empty($CateID)) {
            $to_limit = empty($limit) ? 20 : $limit;

            //getPageBar($原sql語法, 每頁顯示幾筆資料, 最多顯示幾個頁數選項);
            $PageBar = getPageBar($sql, $to_limit, 10);
            $bar     = $PageBar['bar'];
            $sql     = $PageBar['sql'];
            $total   = $PageBar['total'];
        }

        $result = $xoopsDB->query($sql) or web_error($sql);

        $main_data = array();

        $i = 0;

        $Webs = getAllWebInfo();

        $cate = $this->web_cate->get_tad_web_cate_arr();

        while ($all = $xoopsDB->fetchArray($result)) {
            //以下會產生這些變數： $LinkID , $LinkTitle , $LinkDesc , $LinkUrl , $LinkCounter , $LinkSort , $WebID , $uid
            foreach ($all as $k => $v) {
                $$k = $v;
            }

            $main_data[$i]            = $all;
            $main_data[$i]['id']      = $LinkID;
            $main_data[$i]['id_name'] = 'LinkID';
            $main_data[$i]['title']   = $LinkTitle;

            $main_data[$i]['isAssistant'] = is_assistant($CateID, 'LinkID', $LinkID);

            $this->web_cate->set_WebID($WebID);

            $main_data[$i]['cate']     = isset($cate[$CateID]) ? $cate[$CateID] : '';
            $main_data[$i]['WebTitle'] = "<a href='index.php?WebID={$WebID}'>{$Webs[$WebID]}</a>";
            // $main_data[$i]['isMyWeb']  = in_array($WebID, $MyWebs) ? 1 : 0;
            $main_data[$i]['isMyWeb']      = $isMyWeb;
            $main_data[$i]['LinkShortUrl'] = xoops_substr($LinkUrl, 0, 100, '...');
            $LinkDesc                      = nl2br(xoops_substr(strip_tags($LinkDesc), 0, 150));
            $main_data[$i]['LinkDesc']     = $LinkDesc;
            $main_data[$i]['hide_link']    = $hide_link;
            $main_data[$i]['hide_desc']    = $hide_desc;
            $i++;
        }

        //可愛刪除
        if (!file_exists(XOOPS_ROOT_PATH . "/modules/tadtools/sweet_alert.php")) {
            redirect_header("index.php", 3, _MA_NEED_TADTOOLS);
        }
        include_once XOOPS_ROOT_PATH . "/modules/tadtools/sweet_alert.php";
        $sweet_alert = new sweet_alert();
        $sweet_alert->render("delete_link_func", "link.php?op=delete&WebID={$this->WebID}&LinkID=", 'LinkID');

        if ($mode == "return") {
            $data['main_data'] = $main_data;
            $data['total']     = $total;
            return $data;
        } else {
            $xoopsTpl->assign('link_data', $main_data);
            $xoopsTpl->assign('bar', $bar);
            $xoopsTpl->assign('link', get_db_plugin($this->WebID, 'link'));
            return $total;
        }
    }

    //以流水號秀出某筆tad_web_link資料內容
    public function show_one($LinkID = "")
    {
        global $xoopsDB;
        if (empty($LinkID)) {
            return;
        }

        $LinkID = (int)$LinkID;
        $this->add_counter($LinkID);

        $sql           = "select LinkUrl from " . $xoopsDB->prefix("tad_web_link") . " where LinkID='{$LinkID}'";
        $result        = $xoopsDB->query($sql) or web_error($sql);
        list($LinkUrl) = $xoopsDB->fetchRow($result);

        header("location: {$LinkUrl}");
        exit;
    }

    //tad_web_link編輯表單
    public function edit_form($LinkID = "")
    {
        global $xoopsDB, $xoopsUser, $MyWebs, $isMyWeb, $xoopsTpl, $plugin_menu_var;

        chk_self_web($this->WebID, $_SESSION['isAssistant']['link']);
        get_quota($this->WebID);

        //抓取預設值
        if (!empty($LinkID)) {
            $DBV = $this->get_one_data($LinkID);
        } else {
            $DBV = array();
        }

        //預設值設定

        //設定「LinkID」欄位預設值
        $LinkID = (!isset($DBV['LinkID'])) ? "" : $DBV['LinkID'];
        $xoopsTpl->assign('LinkID', $LinkID);

        //設定「LinkTitle」欄位預設值
        $LinkTitle = (!isset($DBV['LinkTitle'])) ? "" : $DBV['LinkTitle'];
        $xoopsTpl->assign('LinkTitle', $LinkTitle);

        //設定「LinkDesc」欄位預設值
        $LinkDesc = (!isset($DBV['LinkDesc'])) ? "" : $DBV['LinkDesc'];
        $xoopsTpl->assign('LinkDesc', $LinkDesc);

        //設定「LinkUrl」欄位預設值
        $LinkUrl = (!isset($DBV['LinkUrl'])) ? "" : $DBV['LinkUrl'];
        $xoopsTpl->assign('LinkUrl', $LinkUrl);

        //設定「LinkCounter」欄位預設值
        $LinkCounter = (!isset($DBV['LinkCounter'])) ? "" : $DBV['LinkCounter'];
        $xoopsTpl->assign('LinkCounter', $LinkCounter);

        //設定「LinkSort」欄位預設值
        $LinkSort = (!isset($DBV['LinkSort'])) ? $this->max_sort() : $DBV['LinkSort'];
        $xoopsTpl->assign('LinkSort', $LinkSort);

        //設定「WebID」欄位預設值
        $WebID = (!isset($DBV['WebID'])) ? $this->WebID : $DBV['WebID'];
        $xoopsTpl->assign('WebID', $WebID);

        //設定「uid」欄位預設值
        $user_uid = ($xoopsUser) ? $xoopsUser->getVar('uid') : "";
        $uid      = (!isset($DBV['uid'])) ? $user_uid : $DBV['uid'];

        //設定「CateID」欄位預設值
        $DefCateID = isset($_SESSION['isAssistant']['link']) ? $_SESSION['isAssistant']['link'] : '';
        $CateID    = (!isset($DBV['CateID'])) ? $DefCateID : $DBV['CateID'];
        $this->web_cate->set_button_value($plugin_menu_var['link']['short'] . _MD_TCW_CATE_TOOLS);
        $this->web_cate->set_default_option_text(sprintf(_MD_TCW_SELECT_PLUGIN_CATE, $plugin_menu_var['link']['short']));
        $cate_menu = isset($_SESSION['isAssistant']['link']) ? $this->web_cate->hidden_cate_menu($CateID) : $this->web_cate->cate_menu($CateID);
        $xoopsTpl->assign('cate_menu_form', $cate_menu);

        $op = (empty($LinkID)) ? "insert" : "update";
        //$op="replace_tad_web_link";

        if (!file_exists(TADTOOLS_PATH . "/formValidator.php")) {
            redirect_header("index.php", 3, _MD_NEED_TADTOOLS);
        }
        include_once TADTOOLS_PATH . "/formValidator.php";
        $formValidator      = new formValidator("#myForm", true);
        $formValidator_code = $formValidator->render();

        $xoopsTpl->assign('formValidator_code', $formValidator_code);
        $xoopsTpl->assign('next_op', $op);

        $tags_form = $this->tags->tags_menu("LinkID", $LinkID);
        $xoopsTpl->assign('tags_form', $tags_form);
    }

    //新增資料到tad_web_link中
    public function insert()
    {
        global $xoopsDB, $xoopsUser, $WebOwnerUid;
        if (isset($_SESSION['isAssistant']['link'])) {
            $uid = $WebOwnerUid;
        } else {
            $uid = ($xoopsUser) ? $xoopsUser->uid() : "";
        }

        $myts                 = MyTextSanitizer::getInstance();
        $_POST['LinkTitle']   = $myts->addSlashes($_POST['LinkTitle']);
        $_POST['LinkDesc']    = $myts->addSlashes($_POST['LinkDesc']);
        $_POST['LinkUrl']     = $myts->addSlashes($_POST['LinkUrl']);
        $_POST['LinkCounter'] = (int)$_POST['LinkCounter'];
        $_POST['LinkSort']    = (int)$_POST['LinkSort'];
        $_POST['CateID']      = (int)$_POST['CateID'];
        $_POST['WebID']       = (int)$_POST['WebID'];

        $CateID = $this->web_cate->save_tad_web_cate($_POST['CateID'], $_POST['newCateName']);

        $sql = "insert into " . $xoopsDB->prefix("tad_web_link") . "
          (`CateID`, `LinkTitle` , `LinkDesc` , `LinkUrl` , `LinkCounter` , `LinkSort` , `WebID` , `uid`)
          values('{$CateID}', '{$_POST['LinkTitle']}' , '{$_POST['LinkDesc']}' , '{$_POST['LinkUrl']}' , '{$_POST['LinkCounter']}' , '{$_POST['LinkSort']}' , '{$_POST['WebID']}' , '{$uid}')";
        $xoopsDB->query($sql) or web_error($sql);

        //取得最後新增資料的流水編號
        $LinkID = $xoopsDB->getInsertId();
        save_assistant_post($CateID, 'LinkID', $LinkID);
        check_quota($this->WebID);

        //儲存標籤
        $this->tags->save_tags("LinkID", $LinkID, $_POST['tag_name'], $_POST['tags']);
        return $LinkID;
    }

    //更新tad_web_link某一筆資料
    public function update($LinkID = "")
    {
        global $xoopsDB, $xoopsUser;

        $myts               = MyTextSanitizer::getInstance();
        $_POST['LinkTitle'] = $myts->addSlashes($_POST['LinkTitle']);
        $_POST['LinkDesc']  = $myts->addSlashes($_POST['LinkDesc']);
        $_POST['LinkUrl']   = $myts->addSlashes($_POST['LinkUrl']);
        $_POST['CateID']    = (int)$_POST['CateID'];
        $_POST['WebID']     = (int)$_POST['WebID'];

        $CateID = $this->web_cate->save_tad_web_cate($_POST['CateID'], $_POST['newCateName']);

        if (!is_assistant($CateID, 'LinkID', $LinkID)) {
            $anduid = onlyMine();
        }

        $sql = "update " . $xoopsDB->prefix("tad_web_link") . " set
       `CateID` = '{$CateID}' ,
       `LinkTitle` = '{$_POST['LinkTitle']}' ,
       `LinkDesc` = '{$_POST['LinkDesc']}' ,
       `LinkUrl` = '{$_POST['LinkUrl']}' ,
       `WebID` = '{$_POST['WebID']}'
        where LinkID='$LinkID' $anduid";
        $xoopsDB->queryF($sql) or web_error($sql);
        check_quota($this->WebID);

        //儲存標籤
        $this->tags->save_tags("LinkID", $LinkID, $_POST['tag_name'], $_POST['tags']);
        return $LinkID;
    }

    //刪除tad_web_link某筆資料資料
    public function delete($LinkID = "")
    {
        global $xoopsDB;

        $sql          = "select CateID from " . $xoopsDB->prefix("tad_web_link") . " where LinkID='$LinkID'";
        $result       = $xoopsDB->query($sql) or web_error($sql);
        list($CateID) = $xoopsDB->fetchRow($result);

        if (!is_assistant($CateID, 'LinkID', $LinkID)) {
            $anduid = onlyMine();
        }

        $sql = "delete from " . $xoopsDB->prefix("tad_web_link") . " where LinkID='$LinkID' $anduid";
        $xoopsDB->queryF($sql) or web_error($sql);
        check_quota($this->WebID);

        //儲存標籤
        $this->tags->save_tags("LinkID", $LinkID, $_POST['tag_name'], $_POST['tags']);
    }

    //刪除所有資料
    public function delete_all()
    {
        global $xoopsDB, $TadUpFiles;
        $allCateID = array();
        $sql       = "select LinkID,CateID from " . $xoopsDB->prefix("tad_web_link") . " where WebID='{$this->WebID}'";
        $result    = $xoopsDB->queryF($sql) or web_error($sql);
        while (list($LinkID, $CateID) = $xoopsDB->fetchRow($result)) {
            $this->delete($LinkID);
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
        $sql         = "select count(*) from " . $xoopsDB->prefix("tad_web_link") . " where WebID='{$this->WebID}'";
        $result      = $xoopsDB->query($sql) or web_error($sql);
        list($count) = $xoopsDB->fetchRow($result);
        return $count;
    }

    //自動取得tad_web_link的最新排序
    public function max_sort()
    {
        global $xoopsDB;
        $sql        = "SELECT max(`LinkSort`) FROM " . $xoopsDB->prefix("tad_web_link");
        $result     = $xoopsDB->query($sql) or web_error($sql);
        list($sort) = $xoopsDB->fetchRow($result);
        return ++$sort;
    }

    //新增tad_web_link計數器
    public function add_counter($LinkID = '')
    {
        global $xoopsDB;
        $sql = "update " . $xoopsDB->prefix("tad_web_link") . " set `LinkCounter`=`LinkCounter`+1 where `LinkID`='{$LinkID}'";
        $xoopsDB->queryF($sql) or web_error($sql);
    }

    //以流水號取得某筆tad_web_link資料
    public function get_one_data($LinkID = "")
    {
        global $xoopsDB;
        if (empty($LinkID)) {
            return;
        }

        $sql    = "select * from " . $xoopsDB->prefix("tad_web_link") . " where LinkID='$LinkID'";
        $result = $xoopsDB->query($sql) or web_error($sql);
        $data   = $xoopsDB->fetchArray($result);
        return $data;
    }
}
