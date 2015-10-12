<?php
class tad_web_schedule
{

    public $WebID = 0;
    public $web_cate;

    public function tad_web_schedule($WebID)
    {
        $this->WebID = $WebID;
        //die('$WebID=' . $WebID);
        $this->web_cate = new web_cate($WebID, "schedule", "tad_web_schedule");
    }

    //活動剪影
    public function list_all($CateID = "", $limit = null)
    {
        global $xoopsDB, $xoopsTpl, $TadUpFiles, $isMyWeb;

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

        $sql = "select a.* from " . $xoopsDB->prefix("tad_web_schedule") . " as a left join " . $xoopsDB->prefix("tad_web") . " as b on a.WebID=b.WebID where b.`WebEnable`='1' $andWebID $andCateID";

        $to_limit = empty($limit) ? 20 : $limit;

        //getPageBar($原sql語法, 每頁顯示幾筆資料, 最多顯示幾個頁數選項);
        $PageBar  = getPageBar($sql, $to_limit, 10);
        $bar      = $PageBar['bar'];
        $sql      = $PageBar['sql'];
        $total    = $PageBar['total'];
        $show_bar = empty($limit) ? $bar : "";

        $result = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'], 3, mysql_error());

        $main_data = "";

        $i = 0;

        $Webs = getAllWebInfo();

        while ($all = $xoopsDB->fetchArray($result)) {
            //以下會產生這些變數： $ScheduleID , $ScheduleName , $ScheduleDesc , $ScheduleDate , $SchedulePlace , $uid , $WebID , $ScheduleCount
            foreach ($all as $k => $v) {
                $$k = $v;
            }

            $main_data[$i] = $all;

            $this->web_cate->set_WebID($WebID);
            $cate = $this->web_cate->get_tad_web_cate_arr();

            $main_data[$i]['cate']     = $cate[$CateID];
            $main_data[$i]['WebTitle'] = "<a href='index.php?WebID={$WebID}'>{$Webs[$WebID]}</a>";

            $subdir = isset($WebID) ? "/{$WebID}" : "";
            $TadUpFiles->set_dir('subdir', $subdir);
            $TadUpFiles->set_col("ScheduleID", $ScheduleID);
            $SchedulePic = $TadUpFiles->get_pic_file('thumb');
            //die('SchedulePic:' . $SchedulePic);
            $main_data[$i]['SchedulePic'] = $SchedulePic;
            $i++;
        }

        $xoopsTpl->assign('schedule_data', $main_data);
        $xoopsTpl->assign('schedule_bar', $show_bar);
        $xoopsTpl->assign('isMineSchedule', $isMyWeb);
        $xoopsTpl->assign('showWebTitleSchedule', $showWebTitle);
        $xoopsTpl->assign('schedule', get_db_plugin($this->WebID, 'schedule'));
        return $total;

    }

    //以流水號秀出某筆tad_web_schedule資料內容
    public function show_one($ScheduleID = "")
    {
        global $xoopsDB, $xoopsTpl, $TadUpFiles, $isMyWeb;
        if (empty($ScheduleID)) {
            return;
        }

        $ScheduleID = intval($ScheduleID);
        $this->add_counter($ScheduleID);

        $sql    = "select * from " . $xoopsDB->prefix("tad_web_schedule") . " where ScheduleID='{$ScheduleID}'";
        $result = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'], 3, mysql_error());
        $all    = $xoopsDB->fetchArray($result);

        //以下會產生這些變數： $ScheduleID , $ScheduleName , $ScheduleDesc , $ScheduleDate , $SchedulePlace , $uid , $WebID , $ScheduleCount
        foreach ($all as $k => $v) {
            $$k = $v;
        }

        if (empty($uid)) {
            redirect_header('index.php', 3, _MD_TCW_DATA_NOT_EXIST);
        }

        $TadUpFiles->set_col("ScheduleID", $ScheduleID);
        $pics = $TadUpFiles->show_files('upfile'); //是否縮圖,顯示模式 filename、small,顯示描述,顯示下載次數

        $TadUpFiles->set_col("ScheduleID", $ScheduleID, 1);
        $bg_pic = $TadUpFiles->get_file_for_smarty();
        //die(var_export($bg_pic));
        $new_name = XOOPS_ROOT_PATH . "/uploads/tad_web/{$this->WebID}/blur_pic_{$ScheduleID}.jpg";
        if (!file_exists($new_name)) {
            $this->mk_blur_pic($bg_pic[0]['path'], $new_name);
        }

        $xoopsTpl->assign('bg_pic', XOOPS_URL . "/uploads/tad_web/{$this->WebID}/blur_pic_{$ScheduleID}.jpg");

        $uid_name = XoopsUser::getUnameFromId($uid, 1);
        if (empty($uid_name)) {
            $uid_name = XoopsUser::getUnameFromId($uid, 0);
        }

        $xoopsTpl->assign('isMineSchedule', $isMyWeb);
        $xoopsTpl->assign('ScheduleName', $ScheduleName);
        $xoopsTpl->assign('ScheduleDate', $ScheduleDate);
        $xoopsTpl->assign('SchedulePlace', $SchedulePlace);
        $xoopsTpl->assign('ScheduleDesc', nl2br($ScheduleDesc));
        $xoopsTpl->assign('uid_name', $uid_name);
        $xoopsTpl->assign('ScheduleCount', $ScheduleCount);
        $xoopsTpl->assign('pics', $pics);
        $xoopsTpl->assign('ScheduleID', $ScheduleID);
        $xoopsTpl->assign('ScheduleInfo', sprintf(_MD_TCW_INFO, $uid_name, $ScheduleDate, $ScheduleCount));

        //取得單一分類資料
        $cate = $this->web_cate->get_tad_web_cate($CateID);
        $xoopsTpl->assign('cate', $cate);
    }

    //tad_web_schedule編輯表單
    public function edit_form($ScheduleID = "")
    {
        global $xoopsDB, $xoopsUser, $MyWebs, $isMyWeb, $xoopsTpl, $TadUpFiles;

        if (!$isMyWeb and $MyWebs) {
            redirect_header($_SERVER['PHP_SELF'] . "?op=WebID={$MyWebs[0]}&edit_form", 3, _MD_TCW_AUTO_TO_HOME);
        } elseif (empty($this->WebID)) {
            redirect_header("index.php", 3, _MD_TCW_NOT_OWNER);
        }

        //抓取預設值
        if (!empty($ScheduleID)) {
            $DBV = $this->get_one_data($ScheduleID);
        } else {
            $DBV = array();
        }

        //預設值設定

        //設定「ScheduleID」欄位預設值
        $ScheduleID = (!isset($DBV['ScheduleID'])) ? $ScheduleID : $DBV['ScheduleID'];
        $xoopsTpl->assign('ScheduleID', $ScheduleID);

        //設定「ScheduleName」欄位預設值
        $ScheduleName = (!isset($DBV['ScheduleName'])) ? "" : $DBV['ScheduleName'];
        $xoopsTpl->assign('ScheduleName', $ScheduleName);

        //設定「ScheduleDesc」欄位預設值
        $ScheduleDesc = (!isset($DBV['ScheduleDesc'])) ? "" : $DBV['ScheduleDesc'];
        $xoopsTpl->assign('ScheduleDesc', $ScheduleDesc);

        //設定「ScheduleDate」欄位預設值
        $ScheduleDate = (!isset($DBV['ScheduleDate'])) ? date("Y-m-d") : $DBV['ScheduleDate'];
        $xoopsTpl->assign('ScheduleDate', $ScheduleDate);

        //設定「SchedulePlace」欄位預設值
        $SchedulePlace = (!isset($DBV['SchedulePlace'])) ? "" : $DBV['SchedulePlace'];
        $xoopsTpl->assign('SchedulePlace', $SchedulePlace);

        //設定「uid」欄位預設值
        $user_uid = ($xoopsUser) ? $xoopsUser->getVar('uid') : "";
        $uid      = (!isset($DBV['uid'])) ? $user_uid : $DBV['uid'];
        $xoopsTpl->assign('uid', $uid);

        //設定「WebID」欄位預設值
        $WebID = (!isset($DBV['WebID'])) ? $this->WebID : $DBV['WebID'];
        $xoopsTpl->assign('WebID', $WebID);

        //設定「ScheduleCount」欄位預設值
        $ScheduleCount = (!isset($DBV['ScheduleCount'])) ? "" : $DBV['ScheduleCount'];
        $xoopsTpl->assign('ScheduleCount', $ScheduleCount);

        //設定「CateID」欄位預設值
        $CateID    = (!isset($DBV['CateID'])) ? "" : $DBV['CateID'];
        $cate_menu = $this->web_cate->cate_menu($CateID);
        $xoopsTpl->assign('cate_menu', $cate_menu);

        $op = (empty($ScheduleID)) ? "insert" : "update";

        if (!file_exists(TADTOOLS_PATH . "/formValidator.php")) {
            redirect_header("index.php", 3, _MD_NEED_TADTOOLS);
        }
        include_once TADTOOLS_PATH . "/formValidator.php";
        $formValidator      = new formValidator("#myForm", true);
        $formValidator_code = $formValidator->render();

        $xoopsTpl->assign('formValidator_code', $formValidator_code);
        $xoopsTpl->assign('next_op', $op);

        $TadUpFiles->set_col('ScheduleID', $ScheduleID); //若 $show_list_del_file ==true 時一定要有
        $upform = $TadUpFiles->upform(true, 'upfile');
        $xoopsTpl->assign('upform', $upform);
    }

    //新增資料到tad_web_schedule中
    public function insert()
    {
        global $xoopsDB, $xoopsUser, $TadUpFiles;

        //取得使用者編號
        $uid = ($xoopsUser) ? $xoopsUser->getVar('uid') : "";

        $myts                   = &MyTextSanitizer::getInstance();
        $_POST['ScheduleName']  = $myts->addSlashes($_POST['ScheduleName']);
        $_POST['ScheduleDesc']  = $myts->addSlashes($_POST['ScheduleDesc']);
        $_POST['SchedulePlace'] = $myts->addSlashes($_POST['SchedulePlace']);
        $_POST['ScheduleCount'] = intval($_POST['ScheduleCount']);
        $_POST['CateID']        = intval($_POST['CateID']);
        $_POST['WebID']         = intval($_POST['WebID']);

        $CateID = $this->web_cate->save_tad_web_cate($_POST['CateID'], $_POST['newCateName']);
        $sql    = "insert into " . $xoopsDB->prefix("tad_web_schedule") . "
        (`CateID`,`ScheduleName` , `ScheduleDesc` , `ScheduleDate` , `SchedulePlace` , `uid` , `WebID` , `ScheduleCount`)
        values('{$CateID}' ,'{$_POST['ScheduleName']}' , '{$_POST['ScheduleDesc']}' , '{$_POST['ScheduleDate']}' , '{$_POST['SchedulePlace']}' , '{$uid}' , '{$_POST['WebID']}' , '{$_POST['ScheduleCount']}')";
        $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'], 3, mysql_error());

        //取得最後新增資料的流水編號
        $ScheduleID = $xoopsDB->getInsertId();

        $TadUpFiles->set_col('ScheduleID', $ScheduleID);
        $TadUpFiles->upload_file('upfile', 800, null, null, null, true);

        return $ScheduleID;
    }

    //更新tad_web_schedule某一筆資料
    public function update($ScheduleID = "")
    {
        global $xoopsDB, $TadUpFiles;

        $myts                   = &MyTextSanitizer::getInstance();
        $_POST['ScheduleName']  = $myts->addSlashes($_POST['ScheduleName']);
        $_POST['ScheduleDesc']  = $myts->addSlashes($_POST['ScheduleDesc']);
        $_POST['SchedulePlace'] = $myts->addSlashes($_POST['SchedulePlace']);
        $_POST['CateID']        = intval($_POST['CateID']);
        $_POST['WebID']         = intval($_POST['WebID']);

        $CateID = $this->web_cate->save_tad_web_cate($_POST['CateID'], $_POST['newCateName']);

        $anduid = onlyMine();

        $sql = "update " . $xoopsDB->prefix("tad_web_schedule") . " set
         `CateID` = '{$CateID}' ,
         `ScheduleName` = '{$_POST['ScheduleName']}' ,
         `ScheduleDesc` = '{$_POST['ScheduleDesc']}' ,
         `ScheduleDate` = '{$_POST['ScheduleDate']}' ,
         `SchedulePlace` = '{$_POST['SchedulePlace']}'
        where ScheduleID='$ScheduleID' $anduid";
        $xoopsDB->queryF($sql) or redirect_header($_SERVER['PHP_SELF'], 3, mysql_error());

        $TadUpFiles->set_col('ScheduleID', $ScheduleID);
        $TadUpFiles->upload_file('upfile', 800, null, null, null, true);

        return $ScheduleID;
    }

    //刪除tad_web_schedule某筆資料資料
    public function delete($ScheduleID = "")
    {
        global $xoopsDB, $TadUpFiles;
        $anduid = onlyMine();
        $sql    = "delete from " . $xoopsDB->prefix("tad_web_schedule") . " where ScheduleID='$ScheduleID' $anduid";
        $xoopsDB->queryF($sql) or redirect_header($_SERVER['PHP_SELF'], 3, mysql_error());

        $TadUpFiles->set_col('ScheduleID', $ScheduleID);
        $TadUpFiles->del_files();
    }

    //新增tad_web_schedule計數器
    public function add_counter($ScheduleID = '')
    {
        global $xoopsDB;
        $sql = "update " . $xoopsDB->prefix("tad_web_schedule") . " set `ScheduleCount`=`ScheduleCount`+1 where `ScheduleID`='{$ScheduleID}'";
        $xoopsDB->queryF($sql) or redirect_header($_SERVER['PHP_SELF'], 3, mysql_error());
    }

    //以流水號取得某筆tad_web_schedule資料
    public function get_one_data($ScheduleID = "")
    {
        global $xoopsDB;
        if (empty($ScheduleID)) {
            return;
        }

        $sql    = "select * from " . $xoopsDB->prefix("tad_web_schedule") . " where ScheduleID='$ScheduleID'";
        $result = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'], 3, mysql_error());
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
}
