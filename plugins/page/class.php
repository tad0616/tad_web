<?php
class tad_web_page
{

    public $WebID = 0;
    public $web_cate;

    public function tad_web_page($WebID)
    {
        $this->WebID = $WebID;
        //die('$WebID=' . $WebID);
        $this->web_cate = new web_cate($WebID, "page", "tad_web_page");
    }

    //文章剪影
    public function list_all($CateID = "", $limit = null)
    {
        global $xoopsDB, $xoopsTpl, $TadUpFiles, $isMyWeb;

        $showWebTitle = (empty($this->WebID)) ? 1 : 0;
        $andWebID     = (empty($this->WebID)) ? "" : "and a.WebID='{$this->WebID}'";

        //取得tad_web_cate所有資料陣列
        $cate_arr = $this->web_cate->get_tad_web_cate_arr();
        $xoopsTpl->assign('cate_arr', $cate_arr);
        //die(var_export($cate_arr));
        $andCateID = "";
        if (!empty($CateID)) {
            //取得單一分類資料
            $cate = $this->web_cate->get_tad_web_cate($CateID);
            $xoopsTpl->assign('cate', $cate);
            $andCateID = "and a.`CateID`='$CateID'";
            $xoopsTpl->assign('PageDefCateID', $CateID);
        }

        $sql = "select a.* from " . $xoopsDB->prefix("tad_web_page") . " as a left join " . $xoopsDB->prefix("tad_web") . " as b on a.WebID=b.WebID where b.`WebEnable`='1' $andWebID $andCateID order by a.PageSort";

        $result = $xoopsDB->query($sql) or web_error($sql);
        $total  = $xoopsDB->getRowsNum($result);

        $page_data = $cate_data = "";

        $i = 0;

        $Webs = getAllWebInfo();

        while ($all = $xoopsDB->fetchArray($result)) {
            //以下會產生這些變數： $PageID , $PageTitle , $PageContent , $PageDate , $PageSort , $uid , $WebID , $PageCount
            foreach ($all as $k => $v) {
                $$k = $v;
            }

            $page_data[$i] = $all;

            $this->web_cate->set_WebID($WebID);
            $cate = $this->web_cate->get_tad_web_cate_arr();

            $page_data[$i]['cate']     = $cate[$CateID];
            $page_data[$i]['WebTitle'] = "<a href='index.php?WebID={$WebID}'>{$Webs[$WebID]}</a>";
            $cate_data[$CateID][]      = $all;
            $i++;
        }
        //die(var_export($page_data));
        $xoopsTpl->assign('cate_data', $cate_data);
        $xoopsTpl->assign('page_data', $page_data);
        $xoopsTpl->assign('page_bar', $show_bar);
        $xoopsTpl->assign('isMinePage', $isMyWeb);
        $xoopsTpl->assign('showWebTitlePage', $showWebTitle);
        $xoopsTpl->assign('page', get_db_plugin($this->WebID, 'page'));
        return $total;

    }

    //以流水號秀出某筆tad_web_page資料內容
    public function show_one($PageID = "")
    {
        global $xoopsDB, $xoopsTpl, $TadUpFiles, $isMyWeb;
        if (empty($PageID)) {
            return;
        }

        $PageID = intval($PageID);
        $this->add_counter($PageID);

        $sql    = "select * from " . $xoopsDB->prefix("tad_web_page") . " where PageID='{$PageID}'";
        $result = $xoopsDB->query($sql) or web_error($sql);
        $all    = $xoopsDB->fetchArray($result);

        //以下會產生這些變數： $PageID , $PageTitle , $PageContent , $PageDate , $PageSort , $uid , $WebID , $PageCount
        foreach ($all as $k => $v) {
            $$k = $v;
        }

        if (empty($uid)) {
            redirect_header('index.php', 3, _MD_TCW_DATA_NOT_EXIST);
        }

        $TadUpFiles->set_col("PageID", $PageID);
        $files = $TadUpFiles->show_files('upfile', true, "", true, false, null, null, false, '');

        $uid_name = XoopsUser::getUnameFromId($uid, 1);
        if (empty($uid_name)) {
            $uid_name = XoopsUser::getUnameFromId($uid, 0);
        }

        $xoopsTpl->assign('isMinePage', $isMyWeb);
        $xoopsTpl->assign('PageTitle', $PageTitle);
        $xoopsTpl->assign('PageDate', $PageDate);
        $xoopsTpl->assign('PageSort', $PageSort);
        $xoopsTpl->assign('PageContent', $PageContent);
        $xoopsTpl->assign('uid_name', $uid_name);
        $xoopsTpl->assign('PageCount', $PageCount);
        $xoopsTpl->assign('files', $files);
        $xoopsTpl->assign('PageID', $PageID);
        $xoopsTpl->assign('PageInfo', sprintf(_MD_TCW_INFO, $uid_name, $PageDate, $PageCount));

        //取得單一分類資料
        $cate = $this->web_cate->get_tad_web_cate($CateID);
        $xoopsTpl->assign('cate', $cate);
    }

    //tad_web_page編輯表單
    public function edit_form($PageID = "")
    {
        global $xoopsDB, $xoopsUser, $MyWebs, $isMyWeb, $xoopsTpl, $TadUpFiles;

        if (!$isMyWeb and $MyWebs) {
            redirect_header($_SERVER['PHP_SELF'] . "?op=WebID={$MyWebs[0]}&edit_form", 3, _MD_TCW_AUTO_TO_HOME);
        } elseif (!$xoopsUser or empty($this->WebID) or empty($MyWebs)) {
            redirect_header("index.php", 3, _MD_TCW_NOT_OWNER);
        }

        //抓取預設值
        if (!empty($PageID)) {
            $DBV = $this->get_one_data($PageID);
        } else {
            $DBV = array();
        }

        //預設值設定

        //設定「PageID」欄位預設值
        $PageID = (!isset($DBV['PageID'])) ? $PageID : $DBV['PageID'];
        $xoopsTpl->assign('PageID', $PageID);

        //設定「PageTitle」欄位預設值
        $PageTitle = (!isset($DBV['PageTitle'])) ? "" : $DBV['PageTitle'];
        $xoopsTpl->assign('PageTitle', $PageTitle);

        //設定「PageContent」欄位預設值
        $PageContent = (!isset($DBV['PageContent'])) ? "" : $DBV['PageContent'];
        $xoopsTpl->assign('PageContent', $PageContent);

        // //設定「PageDate」欄位預設值
        // $PageDate = (!isset($DBV['PageDate'])) ? date("Y-m-d") : $DBV['PageDate'];
        // $xoopsTpl->assign('PageDate', $PageDate);

        // //設定「PageSort」欄位預設值
        // $PageSort = (!isset($DBV['PageSort'])) ? "" : $DBV['PageSort'];
        // $xoopsTpl->assign('PageSort', $PageSort);

        //設定「uid」欄位預設值
        $user_uid = ($xoopsUser) ? $xoopsUser->getVar('uid') : "";
        $uid      = (!isset($DBV['uid'])) ? $user_uid : $DBV['uid'];
        $xoopsTpl->assign('uid', $uid);

        //設定「WebID」欄位預設值
        $WebID = (!isset($DBV['WebID'])) ? $this->WebID : $DBV['WebID'];
        $xoopsTpl->assign('WebID', $WebID);

        //設定「PageCount」欄位預設值
        $PageCount = (!isset($DBV['PageCount'])) ? "" : $DBV['PageCount'];
        $xoopsTpl->assign('PageCount', $PageCount);

        //設定「CateID」欄位預設值
        $CateID    = (!isset($DBV['CateID'])) ? "" : $DBV['CateID'];
        $cate_menu = $this->web_cate->cate_menu($CateID);
        $xoopsTpl->assign('cate_menu', $cate_menu);

        $op = (empty($PageID)) ? "insert" : "update";

        if (!file_exists(TADTOOLS_PATH . "/formValidator.php")) {
            redirect_header("index.php", 3, _MD_NEED_TADTOOLS);
        }
        include_once TADTOOLS_PATH . "/formValidator.php";
        $formValidator      = new formValidator("#myForm", true);
        $formValidator_code = $formValidator->render();

        $xoopsTpl->assign('formValidator_code', $formValidator_code);
        $xoopsTpl->assign('next_op', $op);

        $TadUpFiles->set_col('PageID', $PageID); //若 $show_list_del_file ==true 時一定要有
        $upform = $TadUpFiles->upform(true, 'upfile');
        $xoopsTpl->assign('upform', $upform);

        include_once XOOPS_ROOT_PATH . "/modules/tadtools/ck.php";
        $ck = new CKEditor("tad_web", "PageContent", $PageContent);
        $ck->setHeight(500);
        $editor = $ck->render();
        $xoopsTpl->assign('PageContent_editor', $editor);
    }

    //新增資料到tad_web_page中
    public function insert()
    {
        global $xoopsDB, $xoopsUser, $TadUpFiles;

        //取得使用者編號
        $uid = ($xoopsUser) ? $xoopsUser->getVar('uid') : "";

        $myts                 = &MyTextSanitizer::getInstance();
        $_POST['PageTitle']   = $myts->addSlashes($_POST['PageTitle']);
        $_POST['PageContent'] = $myts->addSlashes($_POST['PageContent']);
        $_POST['CateID']      = intval($_POST['CateID']);
        $_POST['WebID']       = intval($_POST['WebID']);
        $PageSort             = $this->max_sort($_POST['WebID'], $_POST['CateID']);
        $PageDate             = date("Y-m-d H:i:s");

        $CateID = $this->web_cate->save_tad_web_cate($_POST['CateID'], $_POST['newCateName']);
        $sql    = "insert into " . $xoopsDB->prefix("tad_web_page") . "
        (`CateID`,`PageTitle` , `PageContent` , `PageDate` , `PageSort` , `uid` , `WebID` , `PageCount`)
        values('{$CateID}' ,'{$_POST['PageTitle']}' , '{$_POST['PageContent']}' , '{$PageDate}' , '{$PageSort}' , '{$uid}' , '{$_POST['WebID']}' , '0')";
        $xoopsDB->query($sql) or web_error($sql);

        //取得最後新增資料的流水編號
        $PageID = $xoopsDB->getInsertId();

        $TadUpFiles->set_col('PageID', $PageID);
        $TadUpFiles->upload_file('upfile', 800, null, null, null, true);

        return $PageID;
    }

    //更新tad_web_page某一筆資料
    public function update($PageID = "")
    {
        global $xoopsDB, $TadUpFiles;

        $myts                 = &MyTextSanitizer::getInstance();
        $_POST['PageTitle']   = $myts->addSlashes($_POST['PageTitle']);
        $_POST['PageContent'] = $myts->addSlashes($_POST['PageContent']);
        $_POST['CateID']      = intval($_POST['CateID']);
        $_POST['WebID']       = intval($_POST['WebID']);
        $PageDate             = date("Y-m-d H:i:s");

        $CateID = $this->web_cate->save_tad_web_cate($_POST['CateID'], $_POST['newCateName']);

        $anduid = onlyMine();

        $sql = "update " . $xoopsDB->prefix("tad_web_page") . " set
         `CateID` = '{$CateID}' ,
         `PageTitle` = '{$_POST['PageTitle']}' ,
         `PageContent` = '{$_POST['PageContent']}' ,
         `PageDate` = '{$PageDate}'
        where PageID='$PageID' $anduid";
        $xoopsDB->queryF($sql) or web_error($sql);

        $TadUpFiles->set_col('PageID', $PageID);
        $TadUpFiles->upload_file('upfile', 800, null, null, null, true);

        return $PageID;
    }

    //刪除tad_web_page某筆資料資料
    public function delete($PageID = "")
    {
        global $xoopsDB, $TadUpFiles;
        $anduid = onlyMine();
        $sql    = "delete from " . $xoopsDB->prefix("tad_web_page") . " where PageID='$PageID' $anduid";
        $xoopsDB->queryF($sql) or web_error($sql);

        $TadUpFiles->set_col('PageID', $PageID);
        $TadUpFiles->del_files();
    }

    //新增tad_web_page計數器
    public function add_counter($PageID = '')
    {
        global $xoopsDB;
        $sql = "update " . $xoopsDB->prefix("tad_web_page") . " set `PageCount`=`PageCount`+1 where `PageID`='{$PageID}'";
        $xoopsDB->queryF($sql) or web_error($sql);
    }

    //以流水號取得某筆tad_web_page資料
    public function get_one_data($PageID = "")
    {
        global $xoopsDB;
        if (empty($PageID)) {
            return;
        }

        $sql    = "select * from " . $xoopsDB->prefix("tad_web_page") . " where PageID='$PageID'";
        $result = $xoopsDB->query($sql) or web_error($sql);
        $data   = $xoopsDB->fetchArray($result);
        return $data;
    }

    //自動取得tad_web_page的最新排序
    public function max_sort($WebID, $CateID)
    {
        global $xoopsDB;
        $sql        = "select max(`PageSort`) from " . $xoopsDB->prefix("tad_web_page") . " where WebID='$WebID' and CateID='{$CateID}'";
        $result     = $xoopsDB->query($sql) or web_error($sql);
        list($sort) = $xoopsDB->fetchRow($result);
        return ++$sort;
    }
}
