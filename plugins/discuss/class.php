<?php
class tad_web_discuss
{

    public $WebID = 0;
    public $web_cate;

    public function tad_web_discuss($WebID)
    {
        $this->WebID    = $WebID;
        $this->web_cate = new web_cate($WebID, "discuss", "tad_web_discuss");
    }

    //列出所有tad_web_discuss資料
    public function list_all($CateID = "", $limit = null, $mode = "assign")
    {
        global $xoopsDB, $xoopsUser, $xoopsTpl, $MyWebs;

        // if (!$xoopsUser and empty($_SESSION['LoginMemID'])) {
        //     $xoopsTpl->assign('mode', 'need_login');
        // } else {

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
                $xoopsTpl->assign('DiscussDefCateID', $CateID);
            }
        }

        $sql = "select a.* from " . $xoopsDB->prefix("tad_web_discuss") . " as a left join " . $xoopsDB->prefix("tad_web") . " as b on a.WebID=b.WebID where b.`WebEnable`='1' and a.ReDiscussID='0' $andWebID $andCateID order by a.LastTime desc";

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
            //`DiscussID`, `ReDiscussID`, `CateID`, `WebID`, `MemID`, `MemName`, `DiscussTitle`, `DiscussContent`, `DiscussDate`, `LastTime`, `DiscussCounter`
            foreach ($all as $k => $v) {
                $$k = $v;
            }

            $main_data[$i] = $all;

            $this->web_cate->set_WebID($WebID);

            $main_data[$i]['cate']     = isset($cate[$CateID]) ? $cate[$CateID] : '';
            $main_data[$i]['WebTitle'] = "<a href='index.php?WebID={$WebID}'>{$Webs[$WebID]}</a>";
            $main_data[$i]['isMyWeb']  = in_array($WebID, $MyWebs) ? 1 : 0;

            $renum       = $this->get_re_num($DiscussID);
            $show_re_num = empty($renum) ? "" : " ({$renum}) ";
            $LastTime    = substr($LastTime, 0, 10);

            $main_data[$i]['show_re_num'] = $show_re_num;
            $main_data[$i]['LastTime']    = $LastTime;
            $i++;
        }

        //可愛刪除
        if (!file_exists(XOOPS_ROOT_PATH . "/modules/tadtools/sweet_alert.php")) {
            redirect_header("index.php", 3, _MA_NEED_TADTOOLS);
        }
        include_once XOOPS_ROOT_PATH . "/modules/tadtools/sweet_alert.php";
        $sweet_alert      = new sweet_alert();
        $sweet_alert_code = $sweet_alert->render("delete_discuss_func", "discuss.php?op=delete&WebID={$this->WebID}&DiscussID=", 'DiscussID');
        $xoopsTpl->assign('sweet_delete_discuss_func_code', $sweet_alert_code);

        if ($mode == "return") {
            $data['main_data'] = $main_data;
            $data['total']     = $total;
            return $data;
        } else {
            $xoopsTpl->assign('discuss_data', $main_data);
            $xoopsTpl->assign('discuss_bar', $show_bar);
            $xoopsTpl->assign('isMineDiscuss', $this->isMineDiscuss($MemID, $this->WebID));
            $xoopsTpl->assign('discuss', get_db_plugin($this->WebID, 'discuss'));
            return $total;
        }
        // }
    }

    //以流水號秀出某筆tad_web_discuss資料內容
    public function show_one($DiscussID = "")
    {
        global $xoopsDB, $xoopsUser, $isAdmin, $xoopsTpl, $TadUpFiles;
        if (empty($DiscussID)) {
            return;
        }

        $DiscussID = intval($DiscussID);
        $this->add_counter($DiscussID);

        $sql    = "select * from " . $xoopsDB->prefix("tad_web_discuss") . " where DiscussID='{$DiscussID}'";
        $result = $xoopsDB->query($sql) or web_error($sql);
        $all    = $xoopsDB->fetchArray($result);

        //以下會產生這些變數： $DiscussID , $ReDiscussID , $uid , $DiscussTitle , $DiscussContent , $DiscussDate , $WebID , $LastTime , $DiscussCounter
        foreach ($all as $k => $v) {
            $$k = $v;
        }

        // if (empty($uid)) {
        //     redirect_header('index.php', 3, _MD_TCW_DATA_NOT_EXIST);
        // }

        if (!empty($uid)) {
            $TadUpFiles->set_col("WebOwner", $WebID, "1");
            $pic = $TadUpFiles->get_pic_file("thumb");
            if (empty($pic)) {
                $pic = "images/nobody.png";
            }

        } else {
            $TadUpFiles->set_col("MemID", $MemID, "1");
            $pic = $TadUpFiles->get_pic_file("thumb");
            $M   = get_tad_web_mems($MemID);
            if (empty($pic)) {
                $pic = ($M['MemSex'] == '1') ? XOOPS_URL . "/modules/tad_web/images/boy.gif" : XOOPS_URL . "/modules/tad_web/images/girl.gif";
            }
        }
        $xoopsTpl->assign('pic', $pic);

        $TadUpFiles->set_col("DiscussID", $DiscussID);
        $DiscussFiles = $TadUpFiles->show_files('upfile', true, null, true);
        //$xoopsTpl->assign('DiscussFiles', $DiscussFiles);

        $xoopsTpl->assign('isMineDiscuss', $this->isMineDiscuss($MemID, $WebID));
        $xoopsTpl->assign('DiscussTitle', $DiscussTitle);
        $xoopsTpl->assign('MemID', $MemID);
        $xoopsTpl->assign('DiscussContent', $this->bubble(nl2br($DiscussContent) . $DiscussFiles));
        $xoopsTpl->assign('DiscussDate', $DiscussDate);
        $xoopsTpl->assign('LastTime', $LastTime);
        $xoopsTpl->assign('MemName', $MemName);
        $xoopsTpl->assign('WebID', $WebID);
        $xoopsTpl->assign('DiscussCounter', $DiscussCounter);
        $xoopsTpl->assign('DiscussID', $DiscussID);
        $xoopsTpl->assign('DiscussInfo', sprintf(_MD_TCW_INFO, $MemName, $DiscussDate, $DiscussCounter));
        $xoopsTpl->assign('re', $this->get_re($DiscussID));
        $xoopsTpl->assign('LoginMemID', $_SESSION['LoginMemID']);

        //取得單一分類資料
        $cate = $this->web_cate->get_tad_web_cate($CateID);
        $xoopsTpl->assign('cate', $cate);

        $upform = $TadUpFiles->upform(false, 'upfile', null, false);
        $xoopsTpl->assign('upform', $upform);

        //可愛刪除
        if (!file_exists(XOOPS_ROOT_PATH . "/modules/tadtools/sweet_alert.php")) {
            redirect_header("index.php", 3, _MA_NEED_TADTOOLS);
        }
        include_once XOOPS_ROOT_PATH . "/modules/tadtools/sweet_alert.php";
        $sweet_alert      = new sweet_alert();
        $sweet_alert_code = $sweet_alert->render("delete_discuss_func", "discuss.php?op=delete&WebID={$this->WebID}&DefDiscussID={$DiscussID}&DiscussID=", 'DiscussID');
        $xoopsTpl->assign('sweet_delete_discuss_func_code', $sweet_alert_code);
    }

    //tad_web_discuss編輯表單
    public function edit_form($DiscussID = "")
    {
        global $xoopsDB, $xoopsUser, $MyWebs, $isMyWeb, $xoopsTpl, $TadUpFiles;

        if (!$isAdmin and !$isMyWeb and empty($_SESSION['LoginMemID'])) {
            redirect_header("index.php", 3, _MD_TCW_LOGIN_TO_POST);
        }
        get_quota($this->WebID);

        //抓取預設值
        if (!empty($DiscussID)) {
            $DBV = $this->get_one_data($DiscussID);
        } else {
            $DBV = array();
        }

        //預設值設定

        if ($isMyWeb) {

            //設定「uid」欄位預設值
            $uid = (!isset($DBV['uid'])) ? $xoopsUser->uid() : $DBV['uid'];

            //設定「MemID」欄位預設值
            $MemID = (!isset($DBV['MemID'])) ? 0 : $DBV['MemID'];

            //設定「LoginMemName」欄位預設值
            $MemName = (!isset($DBV['MemName'])) ? $xoopsUser->name() : $DBV['MemName'];

            //設定「WebID」欄位預設值
            $WebID = (!isset($DBV['WebID'])) ? $this->WebID : $DBV['WebID'];
        } else {

            //設定「uid」欄位預設值
            $uid = (!isset($DBV['uid'])) ? 0 : $DBV['uid'];

            //設定「MemID」欄位預設值
            $MemID = (!isset($DBV['MemID'])) ? $LoginMemID : $DBV['MemID'];

            //設定「LoginMemName」欄位預設值
            $MemName = (!isset($DBV['MemName'])) ? $LoginMemName : $DBV['MemName'];

            //設定「WebID」欄位預設值
            $WebID = (!isset($DBV['WebID'])) ? $_SESSION['LoginWebID'] : $DBV['WebID'];
        }
        $xoopsTpl->assign('uid', $uid);
        $xoopsTpl->assign('MemID', $MemID);
        $xoopsTpl->assign('MemName', $MemName);
        $xoopsTpl->assign('WebID', $WebID);

        //設定「DiscussID」欄位預設值
        $DiscussID = (!isset($DBV['DiscussID'])) ? "" : $DBV['DiscussID'];
        $xoopsTpl->assign('DiscussID', $DiscussID);

        //設定「ReDiscussID」欄位預設值
        $ReDiscussID = (!isset($DBV['ReDiscussID'])) ? "" : $DBV['ReDiscussID'];
        $xoopsTpl->assign('ReDiscussID', $ReDiscussID);

        //設定「DiscussTitle」欄位預設值
        $DiscussTitle = (!isset($DBV['DiscussTitle'])) ? "" : $DBV['DiscussTitle'];
        $xoopsTpl->assign('DiscussTitle', $DiscussTitle);

        //設定「DiscussContent」欄位預設值
        $DiscussContent = (!isset($DBV['DiscussContent'])) ? "" : $DBV['DiscussContent'];
        $xoopsTpl->assign('DiscussContent', $DiscussContent);

        //設定「DiscussDate」欄位預設值
        $DiscussDate = (!isset($DBV['DiscussDate'])) ? date("Y-m-d H:i:s") : $DBV['DiscussDate'];
        $xoopsTpl->assign('DiscussDate', $DiscussDate);

        //設定「LastTime」欄位預設值
        $LastTime = (!isset($DBV['LastTime'])) ? date("Y-m-d H:i:s") : $DBV['LastTime'];
        $xoopsTpl->assign('LastTime', $LastTime);

        //設定「DiscussCounter」欄位預設值
        $DiscussCounter = (!isset($DBV['DiscussCounter'])) ? "" : $DBV['DiscussCounter'];
        $xoopsTpl->assign('DiscussCounter', $DiscussCounter);

        //設定「CateID」欄位預設值
        $CateID = (!isset($DBV['CateID'])) ? "" : $DBV['CateID'];

        $new_cate  = empty($_SESSION['LoginMemID']) ? true : false;
        $cate_menu = $this->web_cate->cate_menu($CateID, 'form', $new_cate);
        $xoopsTpl->assign('cate_menu_form', $cate_menu);

        $op = (empty($DiscussID)) ? "insert" : "update";

        if (!file_exists(TADTOOLS_PATH . "/formValidator.php")) {
            redirect_header("index.php", 3, _MD_NEED_TADTOOLS);
        }
        include_once TADTOOLS_PATH . "/formValidator.php";
        $formValidator      = new formValidator("#myForm", true);
        $formValidator_code = $formValidator->render();

        $xoopsTpl->assign('formValidator_code', $formValidator_code);
        $xoopsTpl->assign('next_op', $op);

        $TadUpFiles->set_col("DiscussID", $DiscussID);
        $upform = $TadUpFiles->upform();
        $xoopsTpl->assign('upform', $upform);
    }

    //新增資料到tad_web_discuss中
    public function insert()
    {
        global $xoopsDB, $xoopsUser, $WebID, $isMyWeb, $isAdmin, $TadUpFiles;

        if (empty($_SESSION['LoginMemID']) and !$isMyWeb and $isAdmin) {
            redirect_header("index.php", 3, _MD_TCW_LOGIN_TO_POST);
        }

        $myts                    = MyTextSanitizer::getInstance();
        $_POST['DiscussTitle']   = $myts->addSlashes($_POST['DiscussTitle']);
        $_POST['DiscussContent'] = $myts->addSlashes($_POST['DiscussContent']);
        $_POST['CateID']         = intval($_POST['CateID']);
        $_POST['WebID']          = intval($_POST['WebID']);
        $_POST['ReDiscussID']    = intval($_POST['ReDiscussID']);

        if ($isMyWeb) {
            $uid     = $xoopsUser->uid();
            $MemID   = 0;
            $MemName = $xoopsUser->name();
        } else {
            $uid     = 0;
            $MemID   = $_SESSION['LoginMemID'];
            $MemName = $_SESSION['LoginMemName'];
            $WebID   = $_SESSION['LoginWebID'];
        }

        $CateID = $this->web_cate->save_tad_web_cate($_POST['CateID'], $_POST['newCateName']);
        $sql    = "insert into " . $xoopsDB->prefix("tad_web_discuss") . "  (`CateID`,`ReDiscussID` , `uid` , `MemID`, `MemName` , `DiscussTitle` , `DiscussContent` , `DiscussDate` , `WebID` , `LastTime` , `DiscussCounter`)
        values('{$CateID}'  ,'{$_POST['ReDiscussID']}'  , '{$uid}' , '{$MemID}', '{$MemName}' , '{$_POST['DiscussTitle']}' , '{$_POST['DiscussContent']}' , now() , '{$WebID}' , now() , 0)";
        $xoopsDB->query($sql) or web_error($sql);

        //取得最後新增資料的流水編號
        $DiscussID = $xoopsDB->getInsertId();

        $TadUpFiles->set_col("DiscussID", $DiscussID);
        $TadUpFiles->upload_file('upfile', 640, null, null, null, true);

        if (!empty($_POST['ReDiscussID'])) {
            $sql = "update " . $xoopsDB->prefix("tad_web_discuss") . " set `LastTime` = now()
            where `DiscussID` = '{$_POST['ReDiscussID']}' or `ReDiscussID` = '{$_POST['ReDiscussID']}'";
            $xoopsDB->queryF($sql) or web_error($sql);
        }

        if (!empty($_POST['ReDiscussID'])) {
            return $_POST['ReDiscussID'];
        }

        check_quota($this->WebID);
        return $DiscussID;
    }

    //更新tad_web_discuss某一筆資料
    public function update($DiscussID = "")
    {
        global $xoopsDB, $xoopsUser, $isAdmin, $isMyWeb, $TadUpFiles;

        if (empty($_SESSION['LoginMemID']) and !$isMyWeb and $isAdmin) {
            redirect_header("index.php", 3, _MD_TCW_LOGIN_TO_POST);
        }

        if ($isMyWeb) {
            $uid     = $xoopsUser->uid();
            $MemID   = 0;
            $MemName = $xoopsUser->name();
            $anduid  = ($isAdmin) ? "" : "and `WebID`='{$this->WebID}'";
        } else {
            $uid     = 0;
            $MemID   = $_SESSION['LoginMemID'];
            $MemName = $_SESSION['LoginMemName'];
            $WebID   = $_SESSION['LoginWebID'];
            $anduid  = "and `MemID`='{$MemID}'";
        }

        $myts                    = MyTextSanitizer::getInstance();
        $_POST['DiscussTitle']   = $myts->addSlashes($_POST['DiscussTitle']);
        $_POST['DiscussContent'] = $myts->addSlashes($_POST['DiscussContent']);
        $_POST['CateID']         = intval($_POST['CateID']);
        $_POST['WebID']          = intval($_POST['WebID']);
        $_POST['ReDiscussID']    = intval($_POST['ReDiscussID']);

        $CateID = $this->web_cate->save_tad_web_cate($_POST['CateID'], $_POST['newCateName']);

        $sql = "update " . $xoopsDB->prefix("tad_web_discuss") . " set
         `CateID` = '{$CateID}' ,
         `ReDiscussID` = '{$_POST['ReDiscussID']}' ,
         `DiscussTitle` = '{$_POST['DiscussTitle']}' ,
         `DiscussContent` = '{$_POST['DiscussContent']}' ,
         `LastTime` = now()
        where DiscussID='{$DiscussID}' {$anduid}";
        $xoopsDB->queryF($sql) or web_error($sql);

        $TadUpFiles->set_col("DiscussID", $DiscussID);
        $TadUpFiles->upload_file('upfile', 640, null, null, null, true);

        check_quota($this->WebID);
        return $DiscussID;
    }

    //刪除tad_web_discuss某筆資料資料
    public function delete($DiscussID = "")
    {
        global $xoopsDB, $xoopsUser, $isAdmin, $isMyWeb, $TadUpFiles;

        if (empty($_SESSION['LoginMemID']) and !$isMyWeb and $isAdmin) {
            redirect_header("index.php", 3, _MD_TCW_LOGIN_TO_POST);
        }

        if ($isMyWeb) {
            $anduid = ($isAdmin) ? "" : "and `WebID`='{$this->WebID}'";
        } else {
            $anduid = "and `MemID`='{$_SESSION['LoginMemID']}'";
        }

        $sql = "delete from " . $xoopsDB->prefix("tad_web_discuss") . " where `DiscussID`='$DiscussID' $anduid";
        // die($sql);
        $xoopsDB->queryF($sql) or web_error($sql);

        $sql = "delete from " . $xoopsDB->prefix("tad_web_discuss") . " where `ReDiscussID`='$DiscussID' $anduid";
        $xoopsDB->queryF($sql) or web_error($sql);

        $TadUpFiles->set_col("DiscussID", $DiscussID);
        $TadUpFiles->del_files();
        check_quota($this->WebID);
    }

    //刪除所有資料
    public function delete_all()
    {
        global $xoopsDB, $TadUpFiles;
        $allCateID = array();
        $sql       = "select DiscussID,CateID from " . $xoopsDB->prefix("tad_web_discuss") . " where WebID='{$this->WebID}' and ReDiscussID='0'";
        $result    = $xoopsDB->queryF($sql) or web_error($sql);
        while (list($DiscussID, $CateID) = $xoopsDB->fetchRow($result)) {
            $this->delete($DiscussID);
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
        $sql         = "select count(*) from " . $xoopsDB->prefix("tad_web_discuss") . " where WebID='{$this->WebID}'";
        $result      = $xoopsDB->query($sql) or web_error($sql);
        list($count) = $xoopsDB->fetchRow($result);
        return $count;
    }

    //新增tad_web_discuss計數器
    public function add_counter($DiscussID = '')
    {
        global $xoopsDB;
        $sql = "update " . $xoopsDB->prefix("tad_web_discuss") . " set `DiscussCounter`=`DiscussCounter`+1 where `DiscussID`='{$DiscussID}'";
        $xoopsDB->queryF($sql) or web_error($sql);
    }

    //以流水號取得某筆tad_web_discuss資料
    public function get_one_data($DiscussID = "")
    {
        global $xoopsDB;
        if (empty($DiscussID)) {
            return;
        }

        $sql    = "select * from " . $xoopsDB->prefix("tad_web_discuss") . " where DiscussID='$DiscussID'";
        $result = $xoopsDB->query($sql) or web_error($sql);
        $data   = $xoopsDB->fetchArray($result);
        return $data;
    }

    //是否有管理權（或由自己發布的），判斷是否要秀出管理工具
    public function isMineDiscuss($DiscussMemID = null, $DiscussWebID = null)
    {
        global $isMyWeb, $isAdmin;

        if (!empty($DiscussMemID) and $_SESSION['LoginMemID'] == $DiscussMemID) {
            return true;
        } elseif (!empty($DiscussWebID) and $isMyWeb) {
            return true;
        } elseif ($isAdmin) {
            return true;
        }

        return false;
    }

    //回覆的留言
    public function get_re($DiscussID = "")
    {
        global $xoopsDB, $isMyWeb, $TadUpFiles;
        if (empty($DiscussID)) {
            return;
        }

        $sql    = "select * from " . $xoopsDB->prefix("tad_web_discuss") . " where ReDiscussID='$DiscussID' order by DiscussDate";
        $result = $xoopsDB->query($sql) or web_error($sql);

        $re_data = "";

        while ($all = $xoopsDB->fetchArray($result)) {
            //以下會產生這些變數： $DiscussID , $ReDiscussID , $MemID , $DiscussTitle , $DiscussContent , $DiscussDate , $WebID , $LastTime , $DiscussCounter
            foreach ($all as $k => $v) {
                $$k = $v;
            }

            if (!empty($uid)) {
                $TadUpFiles->set_col("WebOwner", $WebID, "1");
                $pic = $TadUpFiles->get_pic_file("thumb");
                if (empty($pic)) {
                    $pic = "images/nobody.png";
                }

            } else {
                $TadUpFiles->set_col("MemID", $MemID, "1");
                $pic = $TadUpFiles->get_pic_file("thumb");
                $M   = get_tad_web_mems($MemID);
                if (empty($pic)) {
                    $pic = ($M['MemSex'] == '1') ? XOOPS_URL . "/modules/tad_web/images/boy.gif" : XOOPS_URL . "/modules/tad_web/images/girl.gif";
                }
            }

            $fun = ($this->isMineDiscuss($MemID)) ? "<div style='font-size:12px;'>
            <a href='{$_SERVER['PHP_SELF']}?WebID=$WebID&op=edit_form&DiscussID=$DiscussID'>" . _TAD_EDIT . "</a> | <a href=\"javascript:delete_discuss_func($DiscussID);\">" . _TAD_DEL . "</a>
            </div>" : "";

            $TadUpFiles->set_col("DiscussID", $DiscussID);
            $DiscussFiles = $TadUpFiles->show_files('upfile', true, null, true);

            $DiscussContent = nl2br($DiscussContent);
            $DiscussContent = $this->bubble($DiscussContent . $DiscussFiles);
            $re_data .= "<tr><td style='line-height:180%;'>
            {$DiscussContent}
            <img src='$pic' alt='{$MemName}" . _MD_TCW_DISCUSS_REPLY . "' style='max-width: 120px; max-height: 120px; margin: 0px 15px 0px 30px; float: left;' class='img-rounded img-polaroid'>
            <div style='line-height:1.5em;'>
              <div>{$MemName}</div><div style='font-size:12px;'>$DiscussDate</div>
              {$fun}
            </div>
            <div style='clean:both;'></div>
            </td></tr>";
        }

        $re = "";
        if (!empty($re_data)) {
            $re = "
            <table>
            $re_data
            </table>
            ";
        }
        return $re;
    }

    //取得回覆數量
    public function get_re_num($DiscussID = "")
    {
        global $xoopsDB, $xoopsUser;
        if (empty($DiscussID)) {
            return 0;
        }

        $sql = "select count(*) from " . $xoopsDB->prefix("tad_web_discuss") . " where ReDiscussID='$DiscussID'";

        $result        = $xoopsDB->query($sql) or web_error($sql);
        list($counter) = $xoopsDB->fetchRow($result);
        return $counter;
    }

    public function bubble($content = "")
    {
        $main = "<div class='xsnazzy'>
          <b class='xb1'></b><b class='xb2'></b><b class='xb3'></b><b class='xb4'></b><b class='xb5'></b><b class='xb6'></b><b class='xb7'></b>
          <div class='xboxcontent'>
          <p>$content</p>
          </div>
          <b class='xb7'></b><b class='xb6'></b><b class='xb5'></b><b class='xb4'></b><b class='xb3'></b><b class='xb2'></b><b class='xb1'></b>
          <em></em><span></span>
          </div>";
        return $main;
    }
}
