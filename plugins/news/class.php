<?php
class tad_web_news
{

    public $WebID = 0;
    public $web_cate;

    public function tad_web_news($WebID)
    {
        $this->WebID    = $WebID;
        $this->web_cate = new web_cate($WebID, "news", "tad_web_news");
    }

    //最新消息
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
            $cate = $this->web_cate->get_tad_web_cate($CateID);
            $xoopsTpl->assign('cate', $cate);
            $andCateID = "and a.`CateID`='$CateID'";
        }

        $sql = "select a.* from " . $xoopsDB->prefix("tad_web_news") . " as a left join " . $xoopsDB->prefix("tad_web") . " as b on a.WebID=b.WebID where b.`WebEnable`='1' $andWebID $andCateID order by NewsDate desc";

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
            //以下會產生這些變數： $NewsID , $NewsTitle , $NewsContent , $NewsDate , $toCal , $NewsPlace , $NewsMaster , $NewsUrl , $WebID , $NewsKind , $NewsCounter
            foreach ($all as $k => $v) {
                $$k = $v;
            }

            $main_data[$i] = $all;

            $this->web_cate->set_WebID($WebID);
            $cate = $this->web_cate->get_tad_web_cate_arr();

            $main_data[$i]['cate']     = $cate[$CateID];
            $main_data[$i]['WebTitle'] = "<a href='index.php?WebID={$WebID}'>{$Webs[$WebID]}</a>";

            $Date = substr($NewsDate, 0, 10);
            if (empty($NewsTitle)) {
                $NewsTitle = _MD_TCW_EMPTY_TITLE;
            }

            $main_data[$i]['NewsTitle'] = $NewsTitle;
            $main_data[$i]['Date']      = $Date;
            $i++;
        }

        $xoopsTpl->assign('news_data', $main_data);
        $xoopsTpl->assign('news_bar', $show_bar);
        $xoopsTpl->assign('isMineNews', $isMyWeb);
        $xoopsTpl->assign('showWebTitleNews', $showWebTitle);
        $xoopsTpl->assign('news', get_db_plugin($this->WebID, 'news'));
        return $total;

    }

    //以流水號秀出某筆tad_web_news資料內容
    public function show_one($NewsID = "")
    {
        global $xoopsDB, $WebID, $isAdmin, $xoopsTpl, $TadUpFiles, $isMyWeb;
        if (empty($NewsID)) {
            return;
        }
        $NewsID = intval($NewsID);
        $this->add_counter($NewsID);

        $sql    = "select * from " . $xoopsDB->prefix("tad_web_news") . " where NewsID='{$NewsID}'";
        $result = $xoopsDB->query($sql) or web_error($sql);
        $all    = $xoopsDB->fetchArray($result);

        //以下會產生這些變數： $NewsID , $NewsTitle , $NewsContent , $NewsDate , $toCal , $NewsPlace , $NewsMaster , $NewsUrl , $WebID , $NewsKind , $NewsCounter ,$uid
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

        $NewsUrlTxt = empty($NewsUrl) ? "" : "<div>" . _MD_TCW_NEWSURL . _TAD_FOR . "<a href='$NewsUrl' target='_blank'>$NewsUrl</a></div>";

        $TadUpFiles->set_col("NewsID", $NewsID);
        $NewsFiles = $TadUpFiles->show_files('upfile', true, null, true);

        $xoopsTpl->assign('isMine', $isMyWeb);
        $xoopsTpl->assign('NewsTitle', $NewsTitle);
        $xoopsTpl->assign('NewsUrlTxt', $NewsUrlTxt);
        $xoopsTpl->assign('NewsContent', $NewsContent);
        $xoopsTpl->assign('uid_name', $uid_name);
        $xoopsTpl->assign('NewsDate', $NewsDate);
        $xoopsTpl->assign('NewsCounter', $NewsCounter);
        $xoopsTpl->assign('NewsFiles', $NewsFiles);
        $xoopsTpl->assign('NewsID', $NewsID);
        $xoopsTpl->assign('NewsInfo', sprintf(_MD_TCW_INFO, $uid_name, $NewsDate, $NewsCounter));

        //取得單一分類資料
        $cate = $this->web_cate->get_tad_web_cate($CateID);
        $xoopsTpl->assign('cate', $cate);
    }

    //tad_web_news編輯表單
    public function edit_form($NewsID = "")
    {
        global $xoopsDB, $xoopsUser, $MyWebs, $isMyWeb, $xoopsTpl, $TadUpFiles;

        if (!$isMyWeb and $MyWebs) {
            redirect_header($_SERVER['PHP_SELF'] . "?WebID={$MyWebs[0]}&op=edit_form", 3, _MD_TCW_AUTO_TO_HOME);
        } elseif (!$xoopsUser or empty($this->WebID) or empty($MyWebs)) {
            redirect_header("index.php", 3, _MD_TCW_NOT_OWNER);
        }

        //抓取預設值
        if (!empty($NewsID)) {
            $DBV = $this->get_one_data($NewsID);
        } else {
            $DBV = array();
        }

        //設定「NewsID」欄位預設值
        $NewsID = (!isset($DBV['NewsID'])) ? "" : $DBV['NewsID'];
        $xoopsTpl->assign('NewsID', $NewsID);

        //設定「NewsTitle」欄位預設值
        $NewsTitle = isset($DBV['NewsDate']) ? $DBV['NewsTitle'] : "";
        $xoopsTpl->assign('NewsTitle', $NewsTitle);

        //設定「NewsContent」欄位預設值
        $NewsContent = isset($DBV['NewsContent']) ? $DBV['NewsContent'] : "";
        $xoopsTpl->assign('NewsContent', $NewsContent);

        //設定「NewsDate」欄位預設值
        $NewsDate = (!isset($DBV['NewsDate'])) ? date("Y-m-d H:i:s") : $DBV['NewsDate'];
        $xoopsTpl->assign('NewsDate', $NewsDate);

        //設定「toCal」欄位預設值
        if (!isset($DBV['toCal'])) {
            $toCal = "";
        } else {
            $toCal = ($DBV['toCal'] == "0000-00-00 00:00:00") ? "" : $DBV['toCal'];
        }
        $xoopsTpl->assign('toCal', $toCal);

        //設定「NewsPlace」欄位預設值
        $NewsPlace = (!isset($DBV['NewsPlace'])) ? "" : $DBV['NewsPlace'];
        $xoopsTpl->assign('NewsPlace', $NewsPlace);

        //設定「NewsMaster」欄位預設值
        $NewsMaster = (!isset($DBV['NewsMaster'])) ? "" : $DBV['NewsMaster'];
        $xoopsTpl->assign('NewsMaster', $NewsMaster);

        //設定「NewsUrl」欄位預設值
        $NewsUrl = (!isset($DBV['NewsUrl'])) ? "" : $DBV['NewsUrl'];
        $xoopsTpl->assign('NewsUrl', $NewsUrl);

        //設定「WebID」欄位預設值
        $WebID = (!isset($DBV['WebID'])) ? $this->WebID : $DBV['WebID'];
        $xoopsTpl->assign('WebID', $WebID);

        //設定「NewsKind」欄位預設值
        $NewsKind = (!isset($DBV['NewsKind'])) ? "news" : $DBV['NewsKind'];
        $xoopsTpl->assign('NewsKind', $NewsKind);

        //設定「NewsCounter」欄位預設值
        $NewsCounter = (!isset($DBV['NewsCounter'])) ? "" : $DBV['NewsCounter'];
        $xoopsTpl->assign('NewsCounter', $NewsCounter);

        //設定「CateID」欄位預設值
        $CateID    = (!isset($DBV['CateID'])) ? "" : $DBV['CateID'];
        $cate_menu = $this->web_cate->cate_menu($CateID);
        $xoopsTpl->assign('cate_menu', $cate_menu);

        $op = (empty($NewsID)) ? "insert" : "update";

        if (!file_exists(TADTOOLS_PATH . "/formValidator.php")) {
            redirect_header("index.php", 3, _MD_NEED_TADTOOLS);
        }
        include_once TADTOOLS_PATH . "/formValidator.php";
        $formValidator      = new formValidator("#myForm", true);
        $formValidator_code = $formValidator->render();
        $xoopsTpl->assign('formValidator_code', $formValidator_code);

        include_once XOOPS_ROOT_PATH . "/modules/tadtools/ck.php";
        $ck = new CKEditor("tad_web", "NewsContent", $NewsContent);
        $ck->setHeight(300);
        $editor = $ck->render();
        $xoopsTpl->assign('NewsContent_editor', $editor);

        $xoopsTpl->assign('next_op', $op);

        $TadUpFiles->set_col("NewsID", $NewsID);
        $upform = $TadUpFiles->upform();
        $xoopsTpl->assign('upform', $upform);
    }

    //新增資料到tad_web_news中
    public function insert()
    {
        global $xoopsDB, $xoopsUser, $TadUpFiles;

        $uid = $xoopsUser->getVar('uid');

        $myts                 = &MyTextSanitizer::getInstance();
        $_POST['NewsTitle']   = $myts->addSlashes($_POST['NewsTitle']);
        $_POST['NewsPlace']   = $myts->addSlashes($_POST['NewsPlace']);
        $_POST['NewsMaster']  = $myts->addSlashes($_POST['NewsMaster']);
        $_POST['NewsUrl']     = $myts->addSlashes($_POST['NewsUrl']);
        $_POST['NewsContent'] = $myts->addSlashes($_POST['NewsContent']);
        $_POST['CateID']      = intval($_POST['CateID']);
        $_POST['WebID']       = intval($_POST['WebID']);

        if (empty($_POST['toCal'])) {
            $_POST['toCal'] = "0000-00-00 00:00:00";
        }

        $CateID = $this->web_cate->save_tad_web_cate($_POST['CateID'], $_POST['newCateName']);
        $sql    = "insert into " . $xoopsDB->prefix("tad_web_news") . "
        (`CateID`,`NewsTitle` , `NewsContent` , `NewsDate` , `toCal` , `NewsPlace` , `NewsMaster` , `NewsUrl` , `WebID` , `NewsKind` , `NewsCounter` , `uid`)
        values('{$CateID}','{$_POST['NewsTitle']}' , '{$_POST['NewsContent']}' , '{$_POST['NewsDate']}' , '{$_POST['toCal']}' , '{$_POST['NewsPlace']}' , '{$_POST['NewsMaster']}' , '{$_POST['NewsUrl']}' , '{$_POST['WebID']}' , '{$_POST['NewsKind']}' , '0' , '{$uid}')";
        $xoopsDB->query($sql) or web_error($sql);

        //取得最後新增資料的流水編號
        $NewsID = $xoopsDB->getInsertId();

        $TadUpFiles->set_col("NewsID", $NewsID);
        $TadUpFiles->upload_file('upfile', 640, null, null, null, true);

        return $NewsID;
    }

    //更新tad_web_news某一筆資料
    public function update($NewsID = "")
    {
        global $xoopsDB, $TadUpFiles;

        $myts                 = &MyTextSanitizer::getInstance();
        $_POST['NewsTitle']   = $myts->addSlashes($_POST['NewsTitle']);
        $_POST['NewsPlace']   = $myts->addSlashes($_POST['NewsPlace']);
        $_POST['NewsMaster']  = $myts->addSlashes($_POST['NewsMaster']);
        $_POST['NewsUrl']     = $myts->addSlashes($_POST['NewsUrl']);
        $_POST['NewsContent'] = $myts->addSlashes($_POST['NewsContent']);
        $_POST['CateID']      = intval($_POST['CateID']);
        $_POST['WebID']       = intval($_POST['WebID']);

        if (empty($_POST['toCal'])) {
            $_POST['toCal'] = "0000-00-00 00:00:00";
        }

        $CateID = $this->web_cate->save_tad_web_cate($_POST['CateID'], $_POST['newCateName']);

        $anduid = onlyMine();

        $sql = "update " . $xoopsDB->prefix("tad_web_news") . " set
         `CateID` = '{$CateID}' ,
         `NewsTitle` = '{$_POST['NewsTitle']}' ,
         `NewsContent` = '{$_POST['NewsContent']}' ,
         `NewsDate` = '{$_POST['NewsDate']}' ,
         `toCal` = '{$_POST['toCal']}' ,
         `NewsPlace` = '{$_POST['NewsPlace']}' ,
         `NewsMaster` = '{$_POST['NewsMaster']}' ,
         `NewsUrl` = '{$_POST['NewsUrl']}'
        where NewsID='$NewsID' $anduid";
        $xoopsDB->queryF($sql) or web_error($sql);

        $TadUpFiles->set_col("NewsID", $NewsID);
        $TadUpFiles->upload_file('upfile', 640, null, null, null, true);

        return $NewsID;
    }

    //刪除tad_web_news某筆資料資料
    public function delete($NewsID = "")
    {
        global $xoopsDB, $TadUpFiles;
        $anduid = onlyMine();
        $sql    = "delete from " . $xoopsDB->prefix("tad_web_news") . " where NewsID='$NewsID' $anduid";
        $xoopsDB->queryF($sql) or web_error($sql);

        $TadUpFiles->set_col("NewsID", $NewsID);
        $TadUpFiles->del_files();
    }

    //新增tad_web_news計數器
    public function add_counter($NewsID = '')
    {
        global $xoopsDB;
        $sql = "update " . $xoopsDB->prefix("tad_web_news") . " set `NewsCounter`=`NewsCounter`+1 where `NewsID`='{$NewsID}'";
        $xoopsDB->queryF($sql) or web_error($sql);
    }

    //以流水號取得某筆tad_web_news資料
    public function get_one_data($NewsID = "")
    {
        global $xoopsDB;
        if (empty($NewsID)) {
            return;
        }

        $sql    = "select * from " . $xoopsDB->prefix("tad_web_news") . " where NewsID='$NewsID'";
        $result = $xoopsDB->query($sql) or web_error($sql);
        $data   = $xoopsDB->fetchArray($result);
        return $data;
    }

}