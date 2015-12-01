<?php
class tad_web_works
{

    public $WebID = 0;
    public $web_cate;

    public function tad_web_works($WebID)
    {
        $this->WebID    = $WebID;
        $this->web_cate = new web_cate($WebID, "works", "tad_web_works");
    }

    //作品分享
    public function list_all($CateID = "", $limit = null, $mode = "assign")
    {
        global $xoopsDB, $xoopsTpl, $MyWebs;
        $andWebID = (empty($this->WebID)) ? "" : "and a.WebID='{$this->WebID}'";

        $andCateID = "";
        if ($mode == "assign") {
            //取得tad_web_cate所有資料陣列
            $cate_menu = $this->web_cate->cate_menu($CateID, 'page', false, true, false, true);
            $xoopsTpl->assign('cate_menu', $cate_menu);
            if (!empty($CateID)) {
                //取得單一分類資料
                $cate = $this->web_cate->get_tad_web_cate($CateID);
                $xoopsTpl->assign('cate', $cate);
                $andCateID = "and a.`CateID`='$CateID'";
                $xoopsTpl->assign('WorksDefCateID', $CateID);
            }
        }

        $sql = "select a.* from " . $xoopsDB->prefix("tad_web_works") . " as a left join " . $xoopsDB->prefix("tad_web") . " as b on a.WebID=b.WebID where b.`WebEnable`='1' $andWebID $andCateID order by a.WorksDate desc";

        $to_limit = empty($limit) ? 20 : $limit;

        //getPageBar($原sql語法, 每頁顯示幾筆資料, 最多顯示幾個頁數選項);
        $PageBar  = getPageBar($sql, $to_limit, 10);
        $bar      = $PageBar['bar'];
        $sql      = $PageBar['sql'];
        $total    = $PageBar['total'];
        $show_bar = empty($limit) ? $bar : "";

        $result = $xoopsDB->query($sql) or web_error($sql);

        $main_data = "";

        $i = 0;

        $Webs = getAllWebInfo();

        $cate = $this->web_cate->get_tad_web_cate_arr();

        while ($all = $xoopsDB->fetchArray($result)) {
            //以下會產生這些變數： $WorksID , $WorksName , $WorksDesc , $WorksDate , $WorksPlace , $uid , $WebID , $WorksCount
            foreach ($all as $k => $v) {
                $$k = $v;
            }

            $main_data[$i] = $all;

            $this->web_cate->set_WebID($WebID);

            $main_data[$i]['cate']      = isset($cate[$CateID]) ? $cate[$CateID] : '';
            $main_data[$i]['WebTitle']  = "<a href='index.php?WebID=$WebID'>{$Webs[$WebID]}</a>";
            $main_data[$i]['isMyWeb']   = in_array($WebID, $MyWebs) ? 1 : 0;
            $main_data[$i]['WorksDate'] = substr($WorksDate, 0, 10);
            $i++;
        }

        if (!file_exists(XOOPS_ROOT_PATH . "/modules/tadtools/sweet_alert.php")) {
            redirect_header("index.php", 3, _MA_NEED_TADTOOLS);
        }
        include_once XOOPS_ROOT_PATH . "/modules/tadtools/sweet_alert.php";
        $sweet_alert      = new sweet_alert();
        $sweet_alert_code = $sweet_alert->render("delete_works_func", "works.php?op=delete&WebID={$this->WebID}&WorksID=", 'WorksID');
        $xoopsTpl->assign('sweet_delete_works_func_code', $sweet_alert_code);

        if ($mode == "return") {
            $data['main_data'] = $main_data;
            $data['total']     = $total;
            return $data;
        } else {
            $xoopsTpl->assign('works_data', $main_data);
            $xoopsTpl->assign('works_bar', $show_bar);
            $xoopsTpl->assign('works', get_db_plugin($this->WebID, 'works'));
            return $total;
        }
    }

    //以流水號秀出某筆tad_web_works資料內容
    public function show_one($WorksID = "")
    {
        global $xoopsDB, $xoopsTpl, $TadUpFiles, $isMyWeb;
        if (empty($WorksID)) {
            return;
        }

        $WorksID = intval($WorksID);
        $this->add_counter($WorksID);

        $sql    = "select * from " . $xoopsDB->prefix("tad_web_works") . " where WorksID='{$WorksID}'";
        $result = $xoopsDB->query($sql) or web_error($sql);
        $all    = $xoopsDB->fetchArray($result);

        //以下會產生這些變數： $WorksID , $WorkName , $WorkDesc , $WorksDate , $uid , $WebID , $WorksCount
        foreach ($all as $k => $v) {
            $$k = $v;
        }

        if (empty($uid)) {
            redirect_header('index.php', 3, _MD_TCW_DATA_NOT_EXIST);
        }

        $TadUpFiles->set_col("WorksID", $WorksID);
        $pics = $TadUpFiles->show_files('upfile', true, null, true); //是否縮圖,顯示模式 filename、small,顯示描述,顯示下載次數

        $uid_name  = XoopsUser::getUnameFromId($uid, 1);
        $WorksDate = str_replace(' 00:00:00', '', $WorksDate);

        $xoopsTpl->assign('WorkName', $WorkName);
        $xoopsTpl->assign('WorksDate', $WorksDate);
        $xoopsTpl->assign('WorkDesc', nl2br($WorkDesc));
        $xoopsTpl->assign('uid_name', $uid_name);
        $xoopsTpl->assign('WorksCount', $WorksCount);
        $xoopsTpl->assign('pics', $pics);
        $xoopsTpl->assign('WorksID', $WorksID);
        $xoopsTpl->assign('WorksInfo', sprintf(_MD_TCW_INFO, $uid_name, $WorksDate, $WorksCount));

        //取得單一分類資料
        $cate = $this->web_cate->get_tad_web_cate($CateID);
        $xoopsTpl->assign('cate', $cate);

        if (!file_exists(XOOPS_ROOT_PATH . "/modules/tadtools/sweet_alert.php")) {
            redirect_header("index.php", 3, _MA_NEED_TADTOOLS);
        }
        include_once XOOPS_ROOT_PATH . "/modules/tadtools/sweet_alert.php";
        $sweet_alert      = new sweet_alert();
        $sweet_alert_code = $sweet_alert->render("delete_works_func", "works.php?op=delete&WebID={$this->WebID}&WorksID=", 'WorksID');
        $xoopsTpl->assign('sweet_delete_works_func_code', $sweet_alert_code);

    }

    //tad_web_works編輯表單
    public function edit_form($WorksID = "")
    {
        global $xoopsDB, $xoopsUser, $MyWebs, $isMyWeb, $xoopsTpl, $TadUpFiles;

        if (!$isMyWeb and $MyWebs) {
            redirect_header($_SERVER['PHP_SELF'] . "?op=WebID={$MyWebs[0]}&edit_form", 3, _MD_TCW_AUTO_TO_HOME);
        } elseif (!$xoopsUser or empty($this->WebID) or empty($MyWebs)) {
            redirect_header("index.php", 3, _MD_TCW_NOT_OWNER);
        }

        //抓取預設值
        if (!empty($WorksID)) {
            $DBV = $this->get_one_data($WorksID);
        } else {
            $DBV = array();
        }

        //預設值設定

        //設定「WorksID」欄位預設值
        $WorksID = (!isset($DBV['WorksID'])) ? $WorksID : $DBV['WorksID'];
        $xoopsTpl->assign('WorksID', $WorksID);

        //設定「WorkName」欄位預設值
        $WorkName = (!isset($DBV['WorkName'])) ? "" : $DBV['WorkName'];
        $xoopsTpl->assign('WorkName', $WorkName);

        //設定「WorkDesc」欄位預設值
        $WorkDesc = (!isset($DBV['WorkDesc'])) ? "" : $DBV['WorkDesc'];
        $xoopsTpl->assign('WorkDesc', $WorkDesc);

        //設定「WorksDate」欄位預設值
        $WorksDate = (!isset($DBV['WorksDate'])) ? date("Y-m-d") : $DBV['WorksDate'];
        $xoopsTpl->assign('WorksDate', $WorksDate);

        //設定「uid」欄位預設值
        $user_uid = ($xoopsUser) ? $xoopsUser->getVar('uid') : "";
        $uid      = (!isset($DBV['uid'])) ? $user_uid : $DBV['uid'];

        //設定「WebID」欄位預設值
        $WebID = (!isset($DBV['WebID'])) ? $this->WebID : $DBV['WebID'];
        $xoopsTpl->assign('WebID', $WebID);

        //設定「WorksCount」欄位預設值
        $WorksCount = (!isset($DBV['WorksCount'])) ? "" : $DBV['WorksCount'];
        $xoopsTpl->assign('WorksCount', $WorksCount);

        //設定「CateID」欄位預設值
        $CateID    = (!isset($DBV['CateID'])) ? "" : $DBV['CateID'];
        $cate_menu = $this->web_cate->cate_menu($CateID);
        $xoopsTpl->assign('cate_menu_form', $cate_menu);

        $op = (empty($WorksID)) ? "insert" : "update";

        if (!file_exists(TADTOOLS_PATH . "/formValidator.php")) {
            redirect_header("index.php", 3, _MD_NEED_TADTOOLS);
        }
        include_once TADTOOLS_PATH . "/formValidator.php";
        $formValidator      = new formValidator("#myForm", true);
        $formValidator_code = $formValidator->render();

        $xoopsTpl->assign('formValidator_code', $formValidator_code);
        $xoopsTpl->assign('next_op', $op);

        $TadUpFiles->set_col('WorksID', $WorksID); //若 $show_list_del_file ==true 時一定要有
        $upform = $TadUpFiles->upform(true, 'upfile');
        $xoopsTpl->assign('upform', $upform);

    }

    //新增資料到tad_web_works中
    public function insert()
    {
        global $xoopsDB, $xoopsUser, $TadUpFiles;

        //取得使用者編號
        $uid = ($xoopsUser) ? $xoopsUser->getVar('uid') : "";

        $myts              = &MyTextSanitizer::getInstance();
        $_POST['WorkName'] = $myts->addSlashes($_POST['WorkName']);
        $_POST['WorkDesc'] = $myts->addSlashes($_POST['WorkDesc']);
        $_POST['CateID']   = intval($_POST['CateID']);
        $_POST['WebID']    = intval($_POST['WebID']);

        $CateID = $this->web_cate->save_tad_web_cate($_POST['CateID'], $_POST['newCateName']);

        $sql = "insert into " . $xoopsDB->prefix("tad_web_works") . "
        (`CateID`,`WorkName` , `WorkDesc` , `WorksDate` ,  `uid` , `WebID` , `WorksCount`)
        values('{$CateID}' , '{$_POST['WorkName']}' , '{$_POST['WorkDesc']}' , '{$_POST['WorksDate']}' , '{$uid}' , '{$_POST['WebID']}' , '0')";
        $xoopsDB->query($sql) or web_error($sql);

        //取得最後新增資料的流水編號
        $WorksID = $xoopsDB->getInsertId();

        $TadUpFiles->set_col('WorksID', $WorksID);
        $TadUpFiles->upload_file('upfile', 800, null, null, null, true);

        return $WorksID;
    }

    //更新tad_web_works某一筆資料
    public function update($WorksID = "")
    {
        global $xoopsDB, $TadUpFiles;

        $myts              = &MyTextSanitizer::getInstance();
        $_POST['WorkName'] = $myts->addSlashes($_POST['WorkName']);
        $_POST['WorkDesc'] = $myts->addSlashes($_POST['WorkDesc']);

        $CateID = $this->web_cate->save_tad_web_cate($_POST['CateID'], $_POST['newCateName']);

        $anduid = onlyMine();

        $sql = "update " . $xoopsDB->prefix("tad_web_works") . " set
         `CateID` = '{$CateID}' ,
         `WorkName` = '{$_POST['WorkName']}' ,
         `WorkDesc` = '{$_POST['WorkDesc']}' ,
         `WorksDate` = '{$_POST['WorksDate']}'
        where WorksID='$WorksID' $anduid";
        $xoopsDB->queryF($sql) or web_error($sql);

        $TadUpFiles->set_col('WorksID', $WorksID);
        $TadUpFiles->upload_file('upfile', 800, null, null, null, true);

        return $WorksID;
    }

    //刪除tad_web_works某筆資料資料
    public function delete($WorksID = "")
    {
        global $xoopsDB, $TadUpFiles;
        $anduid = onlyMine();
        $sql    = "delete from " . $xoopsDB->prefix("tad_web_works") . " where WorksID='$WorksID' $anduid";
        $xoopsDB->queryF($sql) or web_error($sql);

        $TadUpFiles->set_col('WorksID', $WorksID);
        $TadUpFiles->del_files();
    }

    //刪除所有資料
    public function delete_all()
    {
        global $xoopsDB, $TadUpFiles;
        $allCateID = array();
        $sql       = "select WorksID,CateID from " . $xoopsDB->prefix("tad_web_works") . " where WebID='{$this->WebID}'";
        $result    = $xoopsDB->queryF($sql) or web_error($sql);
        while (list($WorksID, $CateID) = $xoopsDB->fetchRow($result)) {
            $this->delete($WorksID);
            $allCateID[$CateID] = $CateID;
        }
        foreach ($allCateID as $CateID) {
            $this->web_cate->delete_tad_web_cate($CateID);
        }
    }

    //取得資料總數
    public function get_total()
    {
        global $xoopsDB;
        $sql         = "select count(*) from " . $xoopsDB->prefix("tad_web_works") . " where WebID='{$this->WebID}'";
        $result      = $xoopsDB->query($sql) or web_error($sql);
        list($count) = $xoopsDB->fetchRow($result);
        return $count;
    }

    //新增tad_web_works計數器
    public function add_counter($WorksID = '')
    {
        global $xoopsDB;
        $sql = "update " . $xoopsDB->prefix("tad_web_works") . " set `WorksCount`=`WorksCount`+1 where `WorksID`='{$WorksID}'";
        $xoopsDB->queryF($sql) or web_error($sql);
    }

    //以流水號取得某筆tad_web_works資料
    public function get_one_data($WorksID = "")
    {
        global $xoopsDB;
        if (empty($WorksID)) {
            return;
        }

        $sql    = "select * from " . $xoopsDB->prefix("tad_web_works") . " where WorksID='$WorksID'";
        $result = $xoopsDB->query($sql) or web_error($sql);
        $data   = $xoopsDB->fetchArray($result);
        return $data;
    }
}
