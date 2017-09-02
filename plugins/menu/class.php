<?php
class tad_web_menu
{

    public $WebID = 0;
    public $web_cate;
    public $setup;

    public function __construct($WebID)
    {
        $this->WebID    = $WebID;
        $this->web_cate = new web_cate($WebID, "menu", "tad_web_menu");
        $this->power    = new power($WebID);
        $this->tags     = new tags($WebID);
        $this->setup    = get_plugin_setup_values($WebID, "menu");
    }

    //選項剪影
    public function list_all($CateID = "", $limit = null, $mode = "assign", $tag = '')
    {
        global $xoopsDB, $xoopsTpl, $TadUpFiles, $MyWebs, $plugin_menu_var;

        $andWebID = (empty($this->WebID)) ? "" : "and a.WebID='{$this->WebID}'";

        $andCateID = "";
        if ($mode == "assign") {
            //取得tad_web_cate所有資料陣列

            if (!empty($plugin_menu_var)) {
                $this->web_cate->set_button_value($plugin_menu_var['menu']['short'] . _MD_TCW_CATE_TOOLS);
                $this->web_cate->set_default_option_text(sprintf(_MD_TCW_SELECT_PLUGIN_CATE, $plugin_menu_var['menu']['short']));
                $this->web_cate->set_col_md(0, 6);
                $cate_menu = $this->web_cate->cate_menu($CateID, 'page', false, true, false, true);
                $xoopsTpl->assign('cate_menu', $cate_menu);
            }

            if (!empty($CateID) and is_numeric($CateID)) {
                //取得單一分類資料
                $cate = $this->web_cate->get_tad_web_cate($CateID);
                $xoopsTpl->assign('cate', $cate);
                $andCateID = "and a.`CateID`='$CateID'";
                $xoopsTpl->assign('MenuDefCateID', $CateID);
            }
        }

        if (_IS_EZCLASS and !empty($_GET['county'])) {
            //http://class.tn.edu.tw/modules/tad_web/index.php?county=臺南市&city=永康區&SchoolName=XX國小
            include_once $GLOBALS['xoops']->path('/modules/system/include/functions.php');
            $county        = system_CleanVars($_REQUEST, 'county', '', 'string');
            $city          = system_CleanVars($_REQUEST, 'city', '', 'string');
            $SchoolName    = system_CleanVars($_REQUEST, 'SchoolName', '', 'string');
            $andCounty     = !empty($county) ? "and c.county='{$county}'" : "";
            $andCity       = !empty($city) ? "and c.city='{$city}'" : "";
            $andSchoolName = !empty($SchoolName) ? "and c.SchoolName='{$SchoolName}'" : "";

            $sql = "select a.* from " . $xoopsDB->prefix("tad_web_menu") . " as a
            left join " . $xoopsDB->prefix("tad_web") . " as b on a.WebID=b.WebID
            left join " . $xoopsDB->prefix("apply") . " as c on b.WebOwnerUid=c.uid
            left join " . $xoopsDB->prefix("tad_web_cate") . " as d on a.CateID=d.CateID
            where b.`WebEnable`='1' and d.CateEnable='1' $andCounty $andCity $andSchoolName
            order by a.MenuID desc";
        } elseif (!empty($tag)) {
            $sql = "select distinct a.* from " . $xoopsDB->prefix("tad_web_menu") . " as a
            left join " . $xoopsDB->prefix("tad_web") . " as b on a.WebID=b.WebID
            join " . $xoopsDB->prefix("tad_web_tags") . " as c on c.col_name='MenuID' and c.col_sn=a.MenuID
            left join " . $xoopsDB->prefix("tad_web_cate") . " as d on a.CateID=d.CateID
            where b.`WebEnable`='1' and d.CateEnable='1' and c.`tag_name`='{$tag}' $andWebID $andCateID
            order by a.MenuID desc";
        } else {
            $sql = "select a.* from " . $xoopsDB->prefix("tad_web_menu") . " as a
            left join " . $xoopsDB->prefix("tad_web") . " as b on a.WebID=b.WebID
            left join " . $xoopsDB->prefix("tad_web_cate") . " as c on a.CateID=c.CateID
            where b.`WebEnable`='1' and c.CateEnable='1' $andWebID $andCateID
            order by a.MenuID desc";
        }
        $to_limit = empty($limit) ? 20 : $limit;

        //getPageBar($原sql語法, 每頁顯示幾筆資料, 最多顯示幾個頁數選項);
        $PageBar = getPageBar($sql, $to_limit, 10);
        $bar     = $PageBar['bar'];
        $sql     = $PageBar['sql'];
        $total   = $PageBar['total'];

        $result = $xoopsDB->query($sql) or web_error($sql);

        $main_data = "";

        $i = 0;

        $Webs = getAllWebInfo();

        $cate = $this->web_cate->get_tad_web_cate_arr();

        while ($all = $xoopsDB->fetchArray($result)) {
            //以下會產生這些變數： $MenuID , $MenuName , $MenuDesc , $MenuDate , $MenuPlace , $uid , $WebID , $MenuCount
            foreach ($all as $k => $v) {
                $$k = $v;
            }
            //檢查權限
            $power = $this->power->check_power("read", "MenuID", $MenuID);
            if (!$power) {
                continue;
            }

            $main_data[$i] = $all;

            $this->web_cate->set_WebID($WebID);

            $main_data[$i]['cate']     = isset($cate[$CateID]) ? $cate[$CateID] : '';
            $main_data[$i]['WebTitle'] = "<a href='index.php?WebID={$WebID}'>{$Webs[$WebID]}</a>";
            $main_data[$i]['isMyWeb']  = in_array($WebID, $MyWebs) ? 1 : 0;

            $subdir = isset($WebID) ? "/{$WebID}" : "";
            $TadUpFiles->set_dir('subdir', $subdir);
            $TadUpFiles->set_col("MenuID", $MenuID);
            $MenuPic = $TadUpFiles->get_pic_file('thumb');
            // die('MenuPic:' . $MenuPic);
            $main_data[$i]['MenuPic'] = $MenuPic;
            $i++;
        }

        //可愛刪除
        if (!file_exists(XOOPS_ROOT_PATH . "/modules/tadtools/sweet_alert.php")) {
            redirect_header("index.php", 3, _MA_NEED_TADTOOLS);
        }
        include_once XOOPS_ROOT_PATH . "/modules/tadtools/sweet_alert.php";
        $sweet_alert = new sweet_alert();
        $sweet_alert->render("delete_menu_func", "menu.php?op=delete&WebID={$this->WebID}&MenuID=", 'MenuID');

        if ($mode == "return") {
            $data['main_data'] = $main_data;
            $data['total']     = $total;
            return $data;
        } else {
            $xoopsTpl->assign('bar', $bar);
            $xoopsTpl->assign('menu_data', $main_data);
            $xoopsTpl->assign('menu', get_db_plugin($this->WebID, 'menu'));
            return $total;
        }
    }

    //以流水號秀出某筆tad_web_menu資料內容
    public function show_one($MenuID = "")
    {
        global $xoopsDB, $xoopsTpl, $TadUpFiles, $isMyWeb, $xoopsUser;
        if (empty($MenuID)) {
            return;
        }

        //檢查權限
        $power = $this->power->check_power("read", "MenuID", $MenuID);
        if (!$power) {
            redirect_header("index.php?WebID={$this->WebID}", 3, _MD_TCW_NOW_READ_POWER);
        }

        $MenuID = (int)$MenuID;
        $this->add_counter($MenuID);

        $sql    = "select * from " . $xoopsDB->prefix("tad_web_menu") . " where MenuID='{$MenuID}'";
        $result = $xoopsDB->query($sql) or web_error($sql);
        $all    = $xoopsDB->fetchArray($result);

        //以下會產生這些變數： $MenuID , $MenuName , $MenuDesc , $MenuDate , $MenuPlace , $uid , $WebID , $MenuCount
        foreach ($all as $k => $v) {
            $$k = $v;
        }

        if (empty($uid)) {
            redirect_header('index.php', 3, _MD_TCW_DATA_NOT_EXIST);
        }
        // $subdir = isset($this->WebID) ? "/{$this->WebID}" : "";
        // $TadUpFiles->set_dir('subdir', $subdir);
        $TadUpFiles->set_col("MenuID", $MenuID);
        $pics = $TadUpFiles->show_files('upfile'); //是否縮圖,顯示模式 filename、small,顯示描述,顯示下載次數

        // $TadUpFiles->set_col("MenuID", $MenuID, 1);
        // $bg_pic = $TadUpFiles->get_file_for_smarty();
        //die(var_export($bg_pic));
        // $new_name = XOOPS_ROOT_PATH . "/uploads/tad_web/{$this->WebID}/blur_pic_{$MenuID}.jpg";
        // if (!file_exists($new_name)) {
        //     $this->mk_blur_pic($bg_pic[0]['path'], $new_name);
        // }

        // $xoopsTpl->assign('bg_pic', XOOPS_URL . "/uploads/tad_web/{$this->WebID}/blur_pic_{$MenuID}.jpg");

        $uid_name = XoopsUser::getUnameFromId($uid, 1);
        if (empty($uid_name)) {
            $uid_name = XoopsUser::getUnameFromId($uid, 0);
        }

        $xoopsTpl->assign('MenuName', $MenuName);
        $xoopsTpl->assign('MenuDate', $MenuDate);
        $xoopsTpl->assign('MenuPlace', $MenuPlace);
        $xoopsTpl->assign('MenuDesc', nl2br($MenuDesc));
        $xoopsTpl->assign('uid_name', $uid_name);
        $xoopsTpl->assign('MenuCount', $MenuCount);
        $xoopsTpl->assign('pics', $pics);
        $xoopsTpl->assign('MenuID', $MenuID);
        $xoopsTpl->assign('MenuInfo', sprintf(_MD_TCW_INFO, $uid_name, $MenuDate, $MenuCount));

        $xoopsTpl->assign('xoops_pagetitle', $MenuName);
        $xoopsTpl->assign('fb_description', $MenuPlace . $MenuDate . xoops_substr(strip_tags($MenuDesc), 0, 300));

        //取得單一分類資料
        $cate = $this->web_cate->get_tad_web_cate($CateID);
        $xoopsTpl->assign('cate', $cate);

        //可愛刪除
        if (!file_exists(XOOPS_ROOT_PATH . "/modules/tadtools/sweet_alert.php")) {
            redirect_header("index.php", 3, _MA_NEED_TADTOOLS);
        }
        include_once XOOPS_ROOT_PATH . "/modules/tadtools/sweet_alert.php";
        $sweet_alert = new sweet_alert();
        $sweet_alert->render("delete_menu_func", "menu.php?op=delete&WebID={$this->WebID}&MenuID=", 'MenuID');
        $xoopsTpl->assign("fb_comments", fb_comments($this->setup['use_fb_comments']));

        $xoopsTpl->assign("tags", $this->tags->list_tags("MenuID", $MenuID, 'menu'));
    }

    //tad_web_menu編輯表單
    public function edit_form($MenuID = "")
    {
        global $xoopsDB, $xoopsUser, $MyWebs, $isMyWeb, $xoopsTpl, $TadUpFiles, $plugin_menu_var;

        if (!$isMyWeb and $MyWebs) {
            redirect_header($_SERVER['PHP_SELF'] . "?op=WebID={$MyWebs[0]}&op=edit_form", 3, _MD_TCW_AUTO_TO_HOME);
        } elseif (!$isMyWeb) {
            redirect_header("index.php?WebID={$this->WebID}", 3, _MD_TCW_NOT_OWNER);
        }
        get_quota($this->WebID);

        //抓取預設值
        if (!empty($MenuID)) {
            $DBV = $this->get_one_data($MenuID);
        } else {
            $DBV = array();
        }

        //預設值設定

        //設定「MenuID」欄位預設值
        $MenuID = (!isset($DBV['MenuID'])) ? $MenuID : $DBV['MenuID'];
        $xoopsTpl->assign('MenuID', $MenuID);

        //設定「MenuName」欄位預設值
        $MenuName = (!isset($DBV['MenuName'])) ? "" : $DBV['MenuName'];
        $xoopsTpl->assign('MenuName', $MenuName);

        //設定「MenuDesc」欄位預設值
        $MenuDesc = (!isset($DBV['MenuDesc'])) ? "" : $DBV['MenuDesc'];
        $xoopsTpl->assign('MenuDesc', $MenuDesc);

        //設定「MenuDate」欄位預設值
        $MenuDate = (!isset($DBV['MenuDate'])) ? date("Y-m-d") : $DBV['MenuDate'];
        $xoopsTpl->assign('MenuDate', $MenuDate);

        //設定「MenuPlace」欄位預設值
        $MenuPlace = (!isset($DBV['MenuPlace'])) ? "" : $DBV['MenuPlace'];
        $xoopsTpl->assign('MenuPlace', $MenuPlace);

        //設定「uid」欄位預設值
        $user_uid = ($xoopsUser) ? $xoopsUser->getVar('uid') : "";
        $uid      = (!isset($DBV['uid'])) ? $user_uid : $DBV['uid'];
        $xoopsTpl->assign('uid', $uid);

        //設定「WebID」欄位預設值
        $WebID = (!isset($DBV['WebID'])) ? $this->WebID : $DBV['WebID'];
        $xoopsTpl->assign('WebID', $WebID);

        //設定「MenuCount」欄位預設值
        $MenuCount = (!isset($DBV['MenuCount'])) ? "" : $DBV['MenuCount'];
        $xoopsTpl->assign('MenuCount', $MenuCount);

        //設定「CateID」欄位預設值
        $CateID = (!isset($DBV['CateID'])) ? '' : $DBV['CateID'];
        $this->web_cate->set_button_value($plugin_menu_var['menu']['short'] . _MD_TCW_CATE_TOOLS);
        $this->web_cate->set_default_option_text(sprintf(_MD_TCW_SELECT_PLUGIN_CATE, $plugin_menu_var['menu']['short']));
        $cate_menu = $this->web_cate->cate_menu($CateID);
        $xoopsTpl->assign('cate_menu_form', $cate_menu);

        $op = (empty($MenuID)) ? "insert" : "update";

        if (!file_exists(TADTOOLS_PATH . "/formValidator.php")) {
            redirect_header("index.php", 3, _MD_NEED_TADTOOLS);
        }
        include_once TADTOOLS_PATH . "/formValidator.php";
        $formValidator      = new formValidator("#myForm", true);
        $formValidator_code = $formValidator->render();

        $xoopsTpl->assign('formValidator_code', $formValidator_code);
        $xoopsTpl->assign('next_op', $op);

        // $subdir = isset($this->WebID) ? "/{$this->WebID}" : "";
        // $TadUpFiles->set_dir('subdir', $subdir);
        $TadUpFiles->set_col('MenuID', $MenuID); //若 $show_list_del_file ==true 時一定要有
        $upform = $TadUpFiles->upform(true, 'upfile');
        $xoopsTpl->assign('upform', $upform);

        $power_form = $this->power->power_menu('read', "MenuID", $MenuID);
        $xoopsTpl->assign('power_form', $power_form);

        $tags_form = $this->tags->tags_menu("MenuID", $MenuID);
        $xoopsTpl->assign('tags_form', $tags_form);
    }

    //新增資料到tad_web_menu中
    public function insert()
    {
        global $xoopsDB, $xoopsUser, $TadUpFiles, $WebOwnerUid;
        $uid = ($xoopsUser) ? $xoopsUser->uid() : "";

        $myts               = MyTextSanitizer::getInstance();
        $_POST['MenuName']  = $myts->addSlashes($_POST['MenuName']);
        $_POST['MenuDesc']  = $myts->addSlashes($_POST['MenuDesc']);
        $_POST['MenuPlace'] = $myts->addSlashes($_POST['MenuPlace']);
        $_POST['MenuCount'] = (int)$_POST['MenuCount'];
        $_POST['CateID']    = (int)$_POST['CateID'];
        $_POST['WebID']     = (int)$_POST['WebID'];

        $CateID = $this->web_cate->save_tad_web_cate($_POST['CateID'], $_POST['newCateName']);
        $sql    = "insert into " . $xoopsDB->prefix("tad_web_menu") . "
        (`CateID`,`MenuName` , `MenuDesc` , `MenuDate` , `MenuPlace` , `uid` , `WebID` , `MenuCount`)
        values('{$CateID}' ,'{$_POST['MenuName']}' , '{$_POST['MenuDesc']}' , '{$_POST['MenuDate']}' , '{$_POST['MenuPlace']}' , '{$uid}' , '{$_POST['WebID']}' , '{$_POST['MenuCount']}')";
        $xoopsDB->query($sql) or web_error($sql);

        //取得最後新增資料的流水編號
        $MenuID = $xoopsDB->getInsertId();

        // $subdir = isset($this->WebID) ? "/{$this->WebID}" : "";
        // $TadUpFiles->set_dir('subdir', $subdir);
        $TadUpFiles->set_col('MenuID', $MenuID);
        $TadUpFiles->upload_file('upfile', 800, null, null, null, true);
        check_quota($this->WebID);

        //儲存權限
        $this->power->save_power("MenuID", $MenuID, 'read');
        //儲存標籤
        $this->tags->save_tags("MenuID", $MenuID, $_POST['tag_name'], $_POST['tags']);
        return $MenuID;
    }

    //更新tad_web_menu某一筆資料
    public function update($MenuID = "")
    {
        global $xoopsDB, $TadUpFiles;

        $myts               = MyTextSanitizer::getInstance();
        $_POST['MenuName']  = $myts->addSlashes($_POST['MenuName']);
        $_POST['MenuDesc']  = $myts->addSlashes($_POST['MenuDesc']);
        $_POST['MenuPlace'] = $myts->addSlashes($_POST['MenuPlace']);
        $_POST['CateID']    = (int)$_POST['CateID'];
        $_POST['WebID']     = (int)$_POST['WebID'];

        $CateID = $this->web_cate->save_tad_web_cate($_POST['CateID'], $_POST['newCateName']);

        $anduid = onlyMine();

        $sql = "update " . $xoopsDB->prefix("tad_web_menu") . " set
         `CateID` = '{$CateID}' ,
         `MenuName` = '{$_POST['MenuName']}' ,
         `MenuDesc` = '{$_POST['MenuDesc']}' ,
         `MenuDate` = '{$_POST['MenuDate']}' ,
         `MenuPlace` = '{$_POST['MenuPlace']}'
        where MenuID='$MenuID' $anduid";
        $xoopsDB->queryF($sql) or web_error($sql);

        // $subdir = isset($this->WebID) ? "/{$this->WebID}" : "";
        // $TadUpFiles->set_dir('subdir', $subdir);
        $TadUpFiles->set_col('MenuID', $MenuID);
        $TadUpFiles->upload_file('upfile', 800, null, null, null, true);
        check_quota($this->WebID);

        //儲存權限
        $read = $myts->addSlashes($_POST['read']);
        $this->power->save_power("MenuID", $MenuID, 'read', $read);
        //儲存標籤
        $this->tags->save_tags("MenuID", $MenuID, $_POST['tag_name'], $_POST['tags']);
        return $MenuID;
    }

    //刪除tad_web_menu某筆資料資料
    public function delete($MenuID = "")
    {
        global $xoopsDB, $TadUpFiles;
        $anduid = onlyMine();

        $sql = "delete from " . $xoopsDB->prefix("tad_web_menu") . " where MenuID='$MenuID' $anduid";
        $xoopsDB->queryF($sql) or web_error($sql);

        // $subdir = isset($this->WebID) ? "/{$this->WebID}" : "";
        // $TadUpFiles->set_dir('subdir', $subdir);
        $TadUpFiles->set_col('MenuID', $MenuID);
        $TadUpFiles->del_files();
        check_quota($this->WebID);

        $this->power->delete_power("MenuID", $MenuID, 'read');
        //刪除標籤
        $this->tags->delete_tags("MenuID", $MenuID);
    }

    //刪除所有資料
    public function delete_all()
    {
        global $xoopsDB, $TadUpFiles;
        $allCateID = array();
        $sql       = "select MenuID,CateID from " . $xoopsDB->prefix("tad_web_menu") . " where WebID='{$this->WebID}'";
        $result    = $xoopsDB->queryF($sql) or web_error($sql);
        while (list($MenuID, $CateID) = $xoopsDB->fetchRow($result)) {
            $this->delete($MenuID);
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
        $sql         = "select count(*) from " . $xoopsDB->prefix("tad_web_menu") . " where WebID='{$this->WebID}'";
        $result      = $xoopsDB->query($sql) or web_error($sql);
        list($count) = $xoopsDB->fetchRow($result);
        return $count;
    }

    //新增tad_web_menu計數器
    public function add_counter($MenuID = '')
    {
        global $xoopsDB;
        $sql = "update " . $xoopsDB->prefix("tad_web_menu") . " set `MenuCount`=`MenuCount`+1 where `MenuID`='{$MenuID}'";
        $xoopsDB->queryF($sql) or web_error($sql);
    }

    //以流水號取得某筆tad_web_menu資料
    public function get_one_data($MenuID = "")
    {
        global $xoopsDB;
        if (empty($MenuID)) {
            return;
        }

        $sql    = "select * from " . $xoopsDB->prefix("tad_web_menu") . " where MenuID='$MenuID'";
        $result = $xoopsDB->query($sql) or web_error($sql);
        $data   = $xoopsDB->fetchArray($result);
        return $data;
    }

    public function blur($gdImageResource, $blurFactor = 3)
    {
        // blurFactor has to be an integer
        $blurFactor = round($blurFactor);

        $originalWidth  = imagesx($gdImageResource);
        $originalHeight = imagesy($gdImageResource);

        $smallestWidth  = ceil($originalWidth * pow(0.5, $blurFactor));
        $smallestHeight = ceil($originalHeight * pow(0.5, $blurFactor));

        // for the first run, the previous image is the original input
        $prevImage  = $gdImageResource;
        $prevWidth  = $originalWidth;
        $prevHeight = $originalHeight;

        // scale way down and gradually scale back up, blurring all the way
        for ($i = 0; $i < $blurFactor; $i += 1) {
            // determine dimensions of next image
            $nextWidth  = $smallestWidth * pow(2, $i);
            $nextHeight = $smallestHeight * pow(2, $i);

            // resize previous image to next size
            $nextImage = imagecreatetruecolor($nextWidth, $nextHeight);
            imagecopyresized($nextImage, $prevImage, 0, 0, 0, 0,
                $nextWidth, $nextHeight, $prevWidth, $prevHeight);

            // apply blur filter
            imagefilter($nextImage, IMG_FILTER_GAUSSIAN_BLUR);

            // now the new image becomes the previous image for the next step
            $prevImage  = $nextImage;
            $prevWidth  = $nextWidth;
            $prevHeight = $nextHeight;
        }

        // scale back to original size and blur one more time
        imagecopyresized($gdImageResource, $nextImage,
            0, 0, 0, 0, $originalWidth, $originalHeight, $nextWidth, $nextHeight);
        imagefilter($gdImageResource, IMG_FILTER_GAUSSIAN_BLUR);

        // clean up
        imagedestroy($prevImage);

        // return result
        return $gdImageResource;
    }

    public function mk_blur_pic($filepath, $new_name)
    {
        $type         = exif_imagetype($filepath); // [] if you don't have exif you could use getImageSize()
        $allowedTypes = array(
            1, // [] gif
            2, // [] jpg
            3, // [] png
            6, // [] bmp
        );
        if (!in_array($type, $allowedTypes)) {
            return false;
        }
        switch ($type) {
            case 1:
                $im = imageCreateFromGif($filepath);
                break;
            case 2:
                $im = imageCreateFromJpeg($filepath);
                break;
            case 3:
                $im = imageCreateFromPng($filepath);
                break;
            case 6:
                $im = imageCreateFromBmp($filepath);
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
    public function export_data($start_date = "", $end_date = "", $CateID = "")
    {

        global $xoopsDB, $xoopsTpl, $TadUpFiles, $MyWebs;
        $andCateID = empty($CateID) ? "" : "and `CateID`='$CateID'";
        $andStart  = empty($start_date) ? "" : "and MenuDate >= '{$start_date}'";
        $andEnd    = empty($end_date) ? "" : "and MenuDate <= '{$end_date}'";

        $sql    = "select MenuID,MenuName,MenuDate,CateID from " . $xoopsDB->prefix("tad_web_menu") . " where WebID='{$this->WebID}' {$andStart} {$andEnd} {$andCateID} order by MenuDate";
        $result = $xoopsDB->query($sql) or web_error($sql);

        $i         = 0;
        $main_data = '';
        while (list($ID, $title, $date, $CateID) = $xoopsDB->fetchRow($result)) {
            $main_data[$i]['ID']     = $ID;
            $main_data[$i]['CateID'] = $CateID;
            $main_data[$i]['title']  = $title;
            $main_data[$i]['date']   = $date;

            $i++;
        }
        return $main_data;
    }
}
