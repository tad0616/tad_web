<?php
class tad_web_calendar
{

    public $WebID = 0;
    public $web_cate;

    public function tad_web_calendar($WebID)
    {
        $this->WebID    = $WebID;
        $this->web_cate = new web_cate($WebID, "calendar", "tad_web_calendar");
    }

    public function list_all($CateID = "", $limit = null)
    {
        global $xoopsDB, $xoopsTpl, $isMyWeb;

        $showWebTitle = (empty($this->WebID)) ? 1 : 0;
        $andWebID     = (empty($this->WebID)) ? "" : "and a.WebID='{$this->WebID}'";

        //取得tad_web_cate所有資料陣列
        $cate_menu = $this->web_cate->cate_menu($CateID, 'page', false, true, false, true);
        $xoopsTpl->assign('cate_menu', $cate_menu);

        $andCateID = "";
        if (!empty($CateID)) {
            //取得單一分類資料
            $cate      = $this->web_cate->get_tad_web_cate($CateID);
            $andCateID = "and a.`CateID`='$CateID'";
            $xoopsTpl->assign('cate', $cate);
            $xoopsTpl->assign('CateID', $CateID);
        }

        $sql = "select a.* from " . $xoopsDB->prefix("tad_web_calendar") . " as a left join " . $xoopsDB->prefix("tad_web") . " as b on a.WebID=b.WebID where b.`WebEnable`='1' $andWebID $andCateID order by a.CalendarDate desc";

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

        while ($all = $xoopsDB->fetchArray($result)) {
            //以下會產生這些變數： $CalendarID , $CalendarName , $CalendarDesc , $CalendarDate , $uid , $WebID , $CalendarCount
            foreach ($all as $k => $v) {
                $$k = $v;
            }

            $main_data[$i] = $all;

            $this->web_cate->set_WebID($WebID);
            $cate = $this->web_cate->get_tad_web_cate_arr();

            $main_data[$i]['cate']     = $cate[$CateID];
            $main_data[$i]['WebTitle'] = "<a href='index.php?WebID={$WebID}'>{$Webs[$WebID]}</a>";

            $i++;
        }

        $xoopsTpl->assign('calendar_data', $main_data);
        $xoopsTpl->assign('calendar_bar', $show_bar);
        $xoopsTpl->assign('isMineCalendar', $isMyWeb);
        $xoopsTpl->assign('showWebTitleCalendar', $showWebTitle);
        $xoopsTpl->assign('calendar', get_db_plugin($this->WebID, 'calendar'));
        return $total;

    }

    //以流水號秀出某筆tad_web_calendar資料內容
    public function show_one($CalendarID = "")
    {
        global $xoopsDB, $xoopsTpl, $TadUpFiles, $isMyWeb;
        if (empty($CalendarID)) {
            return;
        }

        $CalendarID = intval($CalendarID);
        $this->add_counter($CalendarID);

        $sql    = "select * from " . $xoopsDB->prefix("tad_web_calendar") . " where CalendarID='{$CalendarID}'";
        $result = $xoopsDB->query($sql) or web_error($sql);
        $all    = $xoopsDB->fetchArray($result);

        //以下會產生這些變數： $CalendarID , $CalendarName , $CalendarDesc , $CalendarDate , $uid , $WebID , $CalendarCount
        foreach ($all as $k => $v) {
            $$k = $v;
        }

        if (empty($uid)) {
            redirect_header('index.php', 3, _MD_TCW_DATA_NOT_EXIST);
        }

        $uid_name = XoopsUser::getUnameFromId($uid, 1);
        if (empty($uid_name)) {
            $uid_name = XoopsUser::getUnameFromId($uid, 0);
        }

        $xoopsTpl->assign('isMineCalendar', $isMyWeb);
        $xoopsTpl->assign('CalendarName', $CalendarName);
        $xoopsTpl->assign('CalendarDate', $CalendarDate);
        $xoopsTpl->assign('CalendarDesc', nl2br($CalendarDesc));
        $xoopsTpl->assign('uid_name', $uid_name);
        $xoopsTpl->assign('CalendarCount', $CalendarCount);
        $xoopsTpl->assign('CalendarID', $CalendarID);
        $xoopsTpl->assign('CalendarInfo', sprintf(_MD_TCW_INFO, $uid_name, $CalendarDate, $CalendarCount));

        //取得單一分類資料
        $cate = $this->web_cate->get_tad_web_cate($CateID);
        $xoopsTpl->assign('cate', $cate);
    }

    //tad_web_calendar編輯表單
    public function edit_form($CalendarID = "")
    {
        global $xoopsDB, $xoopsUser, $MyWebs, $isMyWeb, $xoopsTpl;

        if (!$isMyWeb and $MyWebs) {
            redirect_header($_SERVER['PHP_SELF'] . "?op=WebID={$MyWebs[0]}&edit_form", 3, _MD_TCW_AUTO_TO_HOME);
        } elseif (!$xoopsUser or empty($this->WebID) or empty($MyWebs)) {
            redirect_header("index.php", 3, _MD_TCW_NOT_OWNER);
        }

        //抓取預設值
        if (!empty($CalendarID)) {
            $DBV = $this->get_one_data($CalendarID);
        } else {
            $DBV = array();
        }

        //預設值設定

        //設定「CalendarID」欄位預設值
        $CalendarID = (!isset($DBV['CalendarID'])) ? $CalendarID : $DBV['CalendarID'];
        $xoopsTpl->assign('CalendarID', $CalendarID);

        //設定「CalendarName」欄位預設值
        $CalendarName = (!isset($DBV['CalendarName'])) ? "" : $DBV['CalendarName'];
        $xoopsTpl->assign('CalendarName', $CalendarName);

        //設定「CalendarDesc」欄位預設值
        $CalendarDesc = (!isset($DBV['CalendarDesc'])) ? "" : $DBV['CalendarDesc'];
        $xoopsTpl->assign('CalendarDesc', $CalendarDesc);

        //設定「CalendarDate」欄位預設值
        $CalendarDate = (!isset($DBV['CalendarDate'])) ? date("Y-m-d") : $DBV['CalendarDate'];
        $xoopsTpl->assign('CalendarDate', $CalendarDate);

        //設定「uid」欄位預設值
        $user_uid = ($xoopsUser) ? $xoopsUser->getVar('uid') : "";
        $uid      = (!isset($DBV['uid'])) ? $user_uid : $DBV['uid'];
        $xoopsTpl->assign('uid', $uid);

        //設定「WebID」欄位預設值
        $WebID = (!isset($DBV['WebID'])) ? $this->WebID : $DBV['WebID'];
        $xoopsTpl->assign('WebID', $WebID);

        //設定「CalendarCount」欄位預設值
        $CalendarCount = (!isset($DBV['CalendarCount'])) ? "" : $DBV['CalendarCount'];
        $xoopsTpl->assign('CalendarCount', $CalendarCount);

        //設定「CateID」欄位預設值
        $CateID    = (!isset($DBV['CateID'])) ? "" : $DBV['CateID'];
        $cate_menu = $this->web_cate->cate_menu($CateID);
        $xoopsTpl->assign('cate_menu', $cate_menu);

        $op = (empty($CalendarID)) ? "insert" : "update";

        if (!file_exists(TADTOOLS_PATH . "/formValidator.php")) {
            redirect_header("index.php", 3, _MD_NEED_TADTOOLS);
        }
        include_once TADTOOLS_PATH . "/formValidator.php";
        $formValidator      = new formValidator("#myForm", true);
        $formValidator_code = $formValidator->render();

        $xoopsTpl->assign('formValidator_code', $formValidator_code);
        $xoopsTpl->assign('next_op', $op);
    }

    //新增資料到tad_web_calendar中
    public function insert()
    {
        global $xoopsDB, $xoopsUser;

        //取得使用者編號
        $uid = ($xoopsUser) ? $xoopsUser->getVar('uid') : "";

        $myts                   = &MyTextSanitizer::getInstance();
        $_POST['CalendarName']  = $myts->addSlashes($_POST['CalendarName']);
        $_POST['CalendarDesc']  = $myts->addSlashes($_POST['CalendarDesc']);
        $_POST['CalendarCount'] = intval($_POST['CalendarCount']);
        $_POST['CateID']        = intval($_POST['CateID']);
        $_POST['WebID']         = intval($_POST['WebID']);

        $CateID = $this->web_cate->save_tad_web_cate($_POST['CateID'], $_POST['newCateName']);
        $sql    = "insert into " . $xoopsDB->prefix("tad_web_calendar") . "
        (`CateID`,`CalendarName` , `CalendarDesc` , `CalendarDate` , `uid` , `WebID` , `CalendarCount`)
        values('{$CateID}' ,'{$_POST['CalendarName']}' , '{$_POST['CalendarDesc']}' , '{$_POST['CalendarDate']}' , '{$uid}' , '{$_POST['WebID']}' , '{$_POST['CalendarCount']}')";
        $xoopsDB->query($sql) or web_error($sql);

        //取得最後新增資料的流水編號
        $CalendarID = $xoopsDB->getInsertId();

        return $CalendarID;
    }

    //更新tad_web_calendar某一筆資料
    public function update($CalendarID = "")
    {
        global $xoopsDB;

        $myts                  = &MyTextSanitizer::getInstance();
        $_POST['CalendarName'] = $myts->addSlashes($_POST['CalendarName']);
        $_POST['CalendarDesc'] = $myts->addSlashes($_POST['CalendarDesc']);
        $_POST['CateID']       = intval($_POST['CateID']);
        $_POST['WebID']        = intval($_POST['WebID']);

        $CateID = $this->web_cate->save_tad_web_cate($_POST['CateID'], $_POST['newCateName']);

        $anduid = onlyMine();

        $sql = "update " . $xoopsDB->prefix("tad_web_calendar") . " set
         `CateID` = '{$CateID}' ,
         `CalendarName` = '{$_POST['CalendarName']}' ,
         `CalendarDesc` = '{$_POST['CalendarDesc']}' ,
         `CalendarDate` = '{$_POST['CalendarDate']}'
        where CalendarID='$CalendarID' $anduid";
        $xoopsDB->queryF($sql) or web_error($sql);

        return $CalendarID;
    }

    //刪除tad_web_calendar某筆資料資料
    public function delete($CalendarID = "")
    {
        global $xoopsDB;
        $anduid = onlyMine();
        $sql    = "delete from " . $xoopsDB->prefix("tad_web_calendar") . " where CalendarID='$CalendarID' $anduid";
        $xoopsDB->queryF($sql) or web_error($sql);
    }

    //新增tad_web_calendar計數器
    public function add_counter($CalendarID = '')
    {
        global $xoopsDB;
        $sql = "update " . $xoopsDB->prefix("tad_web_calendar") . " set `CalendarCount`=`CalendarCount`+1 where `CalendarID`='{$CalendarID}'";
        $xoopsDB->queryF($sql) or web_error($sql);
    }

    //以流水號取得某筆tad_web_calendar資料
    public function get_one_data($CalendarID = "")
    {
        global $xoopsDB;
        if (empty($CalendarID)) {
            return;
        }

        $sql    = "select * from " . $xoopsDB->prefix("tad_web_calendar") . " where CalendarID='$CalendarID'";
        $result = $xoopsDB->query($sql) or web_error($sql);
        $data   = $xoopsDB->fetchArray($result);
        return $data;
    }
}