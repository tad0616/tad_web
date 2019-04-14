<?php

class tad_web_action
{
    public $WebID = 0;
    public $web_cate;
    public $setup;

    public function __construct($WebID)
    {
        $this->WebID    = $WebID;
        $this->web_cate = new web_cate($WebID, 'action', 'tad_web_action');
        $this->power    = new power($WebID);
        $this->tags     = new tags($WebID);
        $this->setup    = get_plugin_setup_values($WebID, 'action');
    }

    //活動剪影
    public function list_all($CateID = '', $limit = null, $mode = 'assign', $tag = '')
    {
        global $xoopsDB, $xoopsTpl, $TadUpFiles, $MyWebs, $isMyWeb, $plugin_menu_var;

        $andWebID = (empty($this->WebID)) ? '' : "and a.WebID='{$this->WebID}'";

        $andCateID = '';
        if ('assign' === $mode) {
            //取得tad_web_cate所有資料陣列
            if (!empty($plugin_menu_var)) {
                $this->web_cate->set_button_value($plugin_menu_var['action']['short'] . _MD_TCW_CATE_TOOLS);
                $this->web_cate->set_default_option_text(sprintf(_MD_TCW_SELECT_PLUGIN_CATE, $plugin_menu_var['action']['short']));
                $this->web_cate->set_col_md(0, 6);
                $cate_menu = $this->web_cate->cate_menu($CateID, 'page', false, true, false, false);
                $xoopsTpl->assign('cate_menu', $cate_menu);
            }

            if (!empty($CateID) and is_numeric($CateID)) {
                //取得單一分類資料
                $cate = $this->web_cate->get_tad_web_cate($CateID);
                if ($CateID and '1' != $cate['CateEnable']) {
                    return;
                }
                $xoopsTpl->assign('cate', $cate);
                $andCateID = "and a.`CateID`='$CateID'";
                $xoopsTpl->assign('ActionDefCateID', $CateID);
            }
        }

        if (_IS_EZCLASS and !empty($_GET['county'])) {
            //https://class.tn.edu.tw/modules/tad_web/index.php?county=臺南市&city=永康區&SchoolName=XX國小
            require_once $GLOBALS['xoops']->path('/modules/system/include/functions.php');
            $county        = system_CleanVars($_REQUEST, 'county', '', 'string');
            $city          = system_CleanVars($_REQUEST, 'city', '', 'string');
            $SchoolName    = system_CleanVars($_REQUEST, 'SchoolName', '', 'string');
            $andCounty     = !empty($county) ? "and c.county='{$county}'" : '';
            $andCity       = !empty($city) ? "and c.city='{$city}'" : '';
            $andSchoolName = !empty($SchoolName) ? "and c.SchoolName='{$SchoolName}'" : '';

            $sql = 'select a.* from ' . $xoopsDB->prefix('tad_web_action') . ' as a
            left join ' . $xoopsDB->prefix('tad_web') . ' as b on a.WebID=b.WebID
            left join ' . $xoopsDB->prefix('apply') . ' as c on b.WebOwnerUid=c.uid
            left join ' . $xoopsDB->prefix('tad_web_cate') . " as d on a.CateID=d.CateID
            where b.`WebEnable`='1' and (d.CateEnable='1' or a.CateID='0') $andCounty $andCity $andSchoolName
            order by a.ActionID desc";
        } elseif (!empty($tag)) {
            $sql = 'select distinct a.* from ' . $xoopsDB->prefix('tad_web_action') . ' as a
            left join ' . $xoopsDB->prefix('tad_web') . ' as b on a.WebID=b.WebID
            join ' . $xoopsDB->prefix('tad_web_tags') . " as c on c.col_name='ActionID' and c.col_sn=a.ActionID
            left join " . $xoopsDB->prefix('tad_web_cate') . " as d on a.CateID=d.CateID
            where b.`WebEnable`='1' and (d.CateEnable='1' or a.CateID='0') and c.`tag_name`='{$tag}' $andWebID $andCateID
            order by a.ActionID desc";
        } else {
            $sql = 'select a.* from ' . $xoopsDB->prefix('tad_web_action') . ' as a
            left join ' . $xoopsDB->prefix('tad_web') . ' as b on a.WebID=b.WebID
            left join ' . $xoopsDB->prefix('tad_web_cate') . " as c on a.CateID=c.CateID
            where b.`WebEnable`='1' and (c.CateEnable='1' or a.CateID='0') $andWebID $andCateID
            order by a.ActionID desc";
        }
        $to_limit = empty($limit) ? 20 : $limit;

        //getPageBar($原sql語法, 每頁顯示幾筆資料, 最多顯示幾個頁數選項);
        $PageBar = getPageBar($sql, $to_limit, 10);
        $bar     = $PageBar['bar'];
        $sql     = $PageBar['sql'];
        $total   = $PageBar['total'];

        $result = $xoopsDB->query($sql) or web_error($sql, __FILE__, __LINE__);

        $main_data = [];

        $i = 0;

        $Webs = getAllWebInfo();

        $cate = $this->web_cate->get_tad_web_cate_arr();

        while (false !== ($all = $xoopsDB->fetchArray($result))) {
            //以下會產生這些變數： $ActionID , $ActionName , $ActionDesc , $ActionDate , $ActionPlace , $uid , $WebID , $ActionCount
            foreach ($all as $k => $v) {
                $$k = $v;
            }
            //檢查權限
            $power = $this->power->check_power('read', 'ActionID', $ActionID);
            if (!$power) {
                continue;
            }

            $main_data[$i]            = $all;
            $main_data[$i]['id']      = $ActionID;
            $main_data[$i]['id_name'] = 'ActionID';
            $main_data[$i]['title']   = $ActionName;
            // $main_data[$i]['isAssistant'] = is_assistant($CateID, 'ActionID', $ActionID);
            $main_data[$i]['isCanEdit'] = isCanEdit($this->WebID, 'action', $CateID, 'ActionID', $ActionID);
            $this->web_cate->set_WebID($WebID);

            $main_data[$i]['cate']     = isset($cate[$CateID]) ? $cate[$CateID] : '';
            $main_data[$i]['WebTitle'] = "<a href='index.php?WebID={$WebID}'>{$Webs[$WebID]}</a>";
            // $main_data[$i]['isMyWeb']  = in_array($WebID, $MyWebs) ? 1 : 0;
            $main_data[$i]['isMyWeb'] = $isMyWeb;

            $subdir = isset($WebID) ? "/{$WebID}" : '';
            $TadUpFiles->set_dir('subdir', $subdir);
            $TadUpFiles->set_col('ActionID', $ActionID);
            $ActionPic = $TadUpFiles->get_pic_file('thumb');
            // die('ActionPic:' . $ActionPic);
            $main_data[$i]['ActionPic'] = $ActionPic;
            $i++;
        }

        //可愛刪除
        if (!file_exists(XOOPS_ROOT_PATH . '/modules/tadtools/sweet_alert.php')) {
            redirect_header('index.php', 3, _MA_NEED_TADTOOLS);
        }
        require_once XOOPS_ROOT_PATH . '/modules/tadtools/sweet_alert.php';
        $sweet_alert = new sweet_alert();
        $sweet_alert->render('delete_action_func', "action.php?op=delete&WebID={$this->WebID}&ActionID=", 'ActionID');

        if ('return' === $mode) {
            $data['main_data'] = $main_data;
            $data['total']     = $total;
            $data['isCanEdit'] = isCanEdit($this->WebID, 'action', $CateID, 'ActionID', $ActionID);
            return $data;
        } else {
            $xoopsTpl->assign('bar', $bar);
            $xoopsTpl->assign('action_data', $main_data);
            $xoopsTpl->assign('action', get_db_plugin($this->WebID, 'action'));
            $xoopsTpl->assign('isCanEdit', isCanEdit($this->WebID, 'action', $CateID, 'ActionID', $ActionID));
            return $total;
        }
    }

    //以流水號秀出某筆tad_web_action資料內容
    public function show_one($ActionID = '')
    {
        global $xoopsDB, $xoopsTpl, $TadUpFiles, $isMyWeb, $xoopsUser;
        if (empty($ActionID)) {
            return;
        }

        //檢查權限
        $power = $this->power->check_power('read', 'ActionID', $ActionID);
        if (!$power) {
            redirect_header("index.php?WebID={$this->WebID}", 3, _MD_TCW_NOW_READ_POWER);
        }

        $ActionID = (int) $ActionID;
        $this->add_counter($ActionID);

        $sql    = 'select * from ' . $xoopsDB->prefix('tad_web_action') . " where ActionID='{$ActionID}'";
        $result = $xoopsDB->query($sql) or web_error($sql, __FILE__, __LINE__);
        $all    = $xoopsDB->fetchArray($result);

        //以下會產生這些變數： $ActionID , $ActionName , $ActionDesc , $ActionDate , $ActionPlace , $uid , $WebID , $ActionCount
        foreach ($all as $k => $v) {
            $$k = $v;
        }

        if (empty($uid)) {
            redirect_header('index.php', 3, _MD_TCW_DATA_NOT_EXIST);
        }
        // $subdir = isset($this->WebID) ? "/{$this->WebID}" : "";
        // $TadUpFiles->set_dir('subdir', $subdir);
        $TadUpFiles->set_col('ActionID', $ActionID);
        $pics = $TadUpFiles->show_files('upfile', null, null, null, null, null, null, null, $this->setup['auto_play_images']); //是否縮圖,顯示模式 filename、small,顯示描述,顯示下載次數

        // $TadUpFiles->set_col("ActionID", $ActionID, 1);
        // $bg_pic = $TadUpFiles->get_file_for_smarty();
        //die(var_export($bg_pic));
        // $new_name = XOOPS_ROOT_PATH . "/uploads/tad_web/{$this->WebID}/blur_pic_{$ActionID}.jpg";
        // if (!file_exists($new_name)) {
        //     $this->mk_blur_pic($bg_pic[0]['path'], $new_name);
        // }

        // $xoopsTpl->assign('bg_pic', XOOPS_URL . "/uploads/tad_web/{$this->WebID}/blur_pic_{$ActionID}.jpg");

        $uid_name = XoopsUser::getUnameFromId($uid, 1);
        if (empty($uid_name)) {
            $uid_name = XoopsUser::getUnameFromId($uid, 0);
        }

        $assistant   = is_assistant($CateID, 'ActionID', $ActionID);
        $isAssistant = !empty($assistant) ? true : false;
        $uid_name    = $isAssistant ? "{$uid_name} <a href='#' title='由{$assistant['MemName']}代理發布'><i class='fa fa-male'></i></a>" : $uid_name;
        $xoopsTpl->assign('isAssistant', $isAssistant);
        $xoopsTpl->assign('isCanEdit', isCanEdit($this->WebID, 'action', $CateID, 'ActionID', $ActionID));

        $xoopsTpl->assign('ActionName', $ActionName);
        $xoopsTpl->assign('ActionDate', $ActionDate);
        $xoopsTpl->assign('ActionPlace', $ActionPlace);
        $xoopsTpl->assign('ActionDesc', nl2br($ActionDesc));
        $xoopsTpl->assign('uid_name', $uid_name);
        $xoopsTpl->assign('ActionCount', $ActionCount);
        $xoopsTpl->assign('pics', $pics);
        $xoopsTpl->assign('ActionID', $ActionID);
        $xoopsTpl->assign('ActionInfo', sprintf(_MD_TCW_INFO, $uid_name, $ActionDate, $ActionCount));

        $xoopsTpl->assign('xoops_pagetitle', $ActionName);
        $xoopsTpl->assign('fb_description', $ActionPlace . $ActionDate . xoops_substr(strip_tags($ActionDesc), 0, 300));

        //取得單一分類資料
        $cate = $this->web_cate->get_tad_web_cate($CateID);
        if ($CateID and '1' != $cate['CateEnable']) {
            return;
        }
        $xoopsTpl->assign('cate', $cate);

        //可愛刪除
        if (!file_exists(XOOPS_ROOT_PATH . '/modules/tadtools/sweet_alert.php')) {
            redirect_header('index.php', 3, _MA_NEED_TADTOOLS);
        }
        require_once XOOPS_ROOT_PATH . '/modules/tadtools/sweet_alert.php';
        $sweet_alert = new sweet_alert();
        $sweet_alert->render('delete_action_func', "action.php?op=delete&WebID={$this->WebID}&ActionID=", 'ActionID');
        $xoopsTpl->assign('fb_comments', fb_comments($this->setup['use_fb_comments']));

        $xoopsTpl->assign('tags', $this->tags->list_tags('ActionID', $ActionID, 'action'));
    }

    //tad_web_action編輯表單
    public function edit_form($ActionID = '')
    {
        global $xoopsDB, $xoopsUser, $MyWebs, $isMyWeb, $xoopsTpl, $TadUpFiles, $plugin_menu_var;

        chk_self_web($this->WebID, $_SESSION['isAssistant']['action']);
        get_quota($this->WebID);

        //抓取預設值
        if (!empty($ActionID)) {
            $DBV = $this->get_one_data($ActionID);
        } else {
            $DBV = [];
        }

        //預設值設定

        //設定「ActionID」欄位預設值
        $ActionID = (!isset($DBV['ActionID'])) ? $ActionID : $DBV['ActionID'];
        $xoopsTpl->assign('ActionID', $ActionID);

        //設定「ActionName」欄位預設值
        $ActionName = (!isset($DBV['ActionName'])) ? '' : $DBV['ActionName'];
        $xoopsTpl->assign('ActionName', $ActionName);

        //設定「ActionDesc」欄位預設值
        $ActionDesc = (!isset($DBV['ActionDesc'])) ? '' : $DBV['ActionDesc'];
        $xoopsTpl->assign('ActionDesc', $ActionDesc);

        //設定「ActionDate」欄位預設值
        $ActionDate = (!isset($DBV['ActionDate'])) ? date('Y-m-d') : $DBV['ActionDate'];
        $xoopsTpl->assign('ActionDate', $ActionDate);

        //設定「ActionPlace」欄位預設值
        $ActionPlace = (!isset($DBV['ActionPlace'])) ? '' : $DBV['ActionPlace'];
        $xoopsTpl->assign('ActionPlace', $ActionPlace);

        //設定「uid」欄位預設值
        $user_uid = ($xoopsUser) ? $xoopsUser->getVar('uid') : '';
        $uid      = (!isset($DBV['uid'])) ? $user_uid : $DBV['uid'];
        $xoopsTpl->assign('uid', $uid);

        //設定「WebID」欄位預設值
        $WebID = (!isset($DBV['WebID'])) ? $this->WebID : $DBV['WebID'];
        $xoopsTpl->assign('WebID', $WebID);

        //設定「ActionCount」欄位預設值
        $ActionCount = (!isset($DBV['ActionCount'])) ? '' : $DBV['ActionCount'];
        $xoopsTpl->assign('ActionCount', $ActionCount);

        //設定「CateID」欄位預設值
        $DefCateID = isset($_SESSION['isAssistant']['action']) ? $_SESSION['isAssistant']['action'] : '';
        $CateID    = (!isset($DBV['CateID'])) ? $DefCateID : $DBV['CateID'];

        $this->web_cate->set_button_value($plugin_menu_var['action']['short'] . _MD_TCW_CATE_TOOLS);
        $this->web_cate->set_default_option_text(sprintf(_MD_TCW_SELECT_PLUGIN_CATE, $plugin_menu_var['action']['short']));
        $cate_menu = isset($_SESSION['isAssistant']['action']) ? $this->web_cate->hidden_cate_menu($CateID) : $this->web_cate->cate_menu($CateID);
        $xoopsTpl->assign('cate_menu_form', $cate_menu);

        $op = (empty($ActionID)) ? 'insert' : 'update';

        if (!file_exists(TADTOOLS_PATH . '/formValidator.php')) {
            redirect_header('index.php', 3, _MD_NEED_TADTOOLS);
        }
        require_once TADTOOLS_PATH . '/formValidator.php';
        $formValidator      = new formValidator('#myForm', true);
        $formValidator_code = $formValidator->render();

        $xoopsTpl->assign('formValidator_code', $formValidator_code);
        $xoopsTpl->assign('next_op', $op);

        // $subdir = isset($this->WebID) ? "/{$this->WebID}" : "";
        // $TadUpFiles->set_dir('subdir', $subdir);
        $TadUpFiles->set_col('ActionID', $ActionID); //若 $show_list_del_file ==true 時一定要有
        $upform = $TadUpFiles->upform(true, 'upfile');
        $xoopsTpl->assign('upform', $upform);

        $power_form = $this->power->power_menu('read', 'ActionID', $ActionID);
        $xoopsTpl->assign('power_form', $power_form);

        $tags_form = $this->tags->tags_menu('ActionID', $ActionID);
        $xoopsTpl->assign('tags_form', $tags_form);
    }

    //新增資料到tad_web_action中
    public function insert()
    {
        global $xoopsDB, $xoopsUser, $TadUpFiles, $WebOwnerUid;
        if (isset($_SESSION['isAssistant']['action'])) {
            $uid = $WebOwnerUid;
        } else {
            $uid = $xoopsUser->getVar('uid');
        }

        $myts                 = MyTextSanitizer::getInstance();
        $ActionName  = $myts->addSlashes($_POST['ActionName']);
        $ActionDesc  = $myts->addSlashes($_POST['ActionDesc']);
        $ActionPlace = $myts->addSlashes($_POST['ActionPlace']);
        $ActionDate  = $myts->addSlashes($_POST['ActionDate']);
        $tag_name    = $myts->addSlashes($_POST['tag_name']);
        $newCateName = $myts->addSlashes($_POST['newCateName']);
        $ActionCount = (int) $_POST['ActionCount'];
        $CateID      = (int) $_POST['CateID'];
        $WebID       = (int) $_POST['WebID'];

        $CateID = $this->web_cate->save_tad_web_cate($CateID, $newCateName);
        $sql    = 'insert into ' . $xoopsDB->prefix('tad_web_action') . "
        (`CateID`,`ActionName` , `ActionDesc` , `ActionDate` , `ActionPlace` , `uid` , `WebID` , `ActionCount`)
        values('{$CateID}' ,'{$ActionName}' , '{$ActionDesc}' , '{$ActionDate}' , '{$ActionPlace}' , '{$uid}' , '{$WebID}' , '{$ActionCount}')";
        $xoopsDB->query($sql) or web_error($sql, __FILE__, __LINE__);

        //取得最後新增資料的流水編號
        $ActionID = $xoopsDB->getInsertId();
        save_assistant_post($CateID, 'ActionID', $ActionID);

        // $subdir = isset($this->WebID) ? "/{$this->WebID}" : "";
        // $TadUpFiles->set_dir('subdir', $subdir);
        $TadUpFiles->set_col('ActionID', $ActionID);
        $TadUpFiles->upload_file('upfile', 800, null, null, null, true);
        check_quota($this->WebID);

        //儲存權限
        $this->power->save_power('ActionID', $ActionID, 'read');
        //儲存標籤
        $this->tags->save_tags('ActionID', $ActionID, $tag_name, $_POST['tags']);
        return $ActionID;
    }

    //更新tad_web_action某一筆資料
    public function update($ActionID = '')
    {
        global $xoopsDB, $TadUpFiles;

        $myts                 = MyTextSanitizer::getInstance();
        $ActionName  = $myts->addSlashes($_POST['ActionName']);
        $ActionDesc  = $myts->addSlashes($_POST['ActionDesc']);
        $ActionPlace = $myts->addSlashes($_POST['ActionPlace']);
        $ActionDate  = $myts->addSlashes($_POST['ActionDate']);
        $tag_name    = $myts->addSlashes($_POST['tag_name']);
        $newCateName = $myts->addSlashes($_POST['newCateName']);
        $read        = $myts->addSlashes($_POST['read']);
        $CateID      = (int) $_POST['CateID'];
        $WebID       = (int) $_POST['WebID'];

        $CateID = $this->web_cate->save_tad_web_cate($CateID, $newCateName);

        if (!is_assistant($CateID, 'ActionID', $ActionID)) {
            $anduid = onlyMine();
        }

        $sql = 'update ' . $xoopsDB->prefix('tad_web_action') . " set
         `CateID` = '{$CateID}' ,
         `ActionName` = '{$ActionName}' ,
         `ActionDesc` = '{$ActionDesc}' ,
         `ActionDate` = '{$ActionDate}' ,
         `ActionPlace` = '{$ActionPlace}'
        where ActionID='$ActionID' $anduid";
        $xoopsDB->queryF($sql) or web_error($sql, __FILE__, __LINE__);

        // $subdir = isset($this->WebID) ? "/{$this->WebID}" : "";
        // $TadUpFiles->set_dir('subdir', $subdir);
        $TadUpFiles->set_col('ActionID', $ActionID);
        $TadUpFiles->upload_file('upfile', 800, null, null, null, true);
        check_quota($this->WebID);

        //儲存權限
        $this->power->save_power('ActionID', $ActionID, 'read', $read);
        //儲存標籤
        $this->tags->save_tags('ActionID', $ActionID, $tag_name, $_POST['tags']);
        return $ActionID;
    }

    //刪除tad_web_action某筆資料資料
    public function delete($ActionID = '')
    {
        global $xoopsDB, $TadUpFiles;
        $sql          = 'select CateID from ' . $xoopsDB->prefix('tad_web_action') . " where ActionID='$ActionID'";
        $result       = $xoopsDB->query($sql) or web_error($sql, __FILE__, __LINE__);
        list($CateID) = $xoopsDB->fetchRow($result);
        if (!is_assistant($CateID, 'ActionID', $ActionID)) {
            $anduid = onlyMine();
        }
        $sql = 'delete from ' . $xoopsDB->prefix('tad_web_action') . " where ActionID='$ActionID' $anduid";
        $xoopsDB->queryF($sql) or web_error($sql, __FILE__, __LINE__);

        // $subdir = isset($this->WebID) ? "/{$this->WebID}" : "";
        // $TadUpFiles->set_dir('subdir', $subdir);
        $TadUpFiles->set_col('ActionID', $ActionID);
        $TadUpFiles->del_files();
        check_quota($this->WebID);

        $this->power->delete_power('ActionID', $ActionID, 'read');
        //刪除標籤
        $this->tags->delete_tags('ActionID', $ActionID);
    }

    //刪除所有資料
    public function delete_all()
    {
        global $xoopsDB, $TadUpFiles;
        $allCateID = [];
        $sql       = 'select ActionID,CateID from ' . $xoopsDB->prefix('tad_web_action') . " where WebID='{$this->WebID}'";
        $result    = $xoopsDB->queryF($sql) or web_error($sql, __FILE__, __LINE__);
        while (false !== (list($ActionID, $CateID) = $xoopsDB->fetchRow($result))) {
            $this->delete($ActionID);
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
        $sql         = 'select count(*) from ' . $xoopsDB->prefix('tad_web_action') . " where WebID='{$this->WebID}'";
        $result      = $xoopsDB->query($sql) or web_error($sql, __FILE__, __LINE__);
        list($count) = $xoopsDB->fetchRow($result);
        return $count;
    }

    //新增tad_web_action計數器
    public function add_counter($ActionID = '')
    {
        global $xoopsDB;
        $sql = 'update ' . $xoopsDB->prefix('tad_web_action') . " set `ActionCount`=`ActionCount`+1 where `ActionID`='{$ActionID}'";
        $xoopsDB->queryF($sql) or web_error($sql, __FILE__, __LINE__);
    }

    //以流水號取得某筆tad_web_action資料
    public function get_one_data($ActionID = '')
    {
        global $xoopsDB;
        if (empty($ActionID)) {
            return;
        }

        $sql    = 'select * from ' . $xoopsDB->prefix('tad_web_action') . " where ActionID='$ActionID'";
        $result = $xoopsDB->query($sql) or web_error($sql, __FILE__, __LINE__);
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
            imagecopyresized($nextImage, $prevImage, 0, 0, 0, 0, $nextWidth, $nextHeight, $prevWidth, $prevHeight);

            // apply blur filter
            imagefilter($nextImage, IMG_FILTER_GAUSSIAN_BLUR);

            // now the new image becomes the previous image for the next step
            $prevImage  = $nextImage;
            $prevWidth  = $nextWidth;
            $prevHeight = $nextHeight;
        }

        // scale back to original size and blur one more time
        imagecopyresized($gdImageResource, $nextImage, 0, 0, 0, 0, $originalWidth, $originalHeight, $nextWidth, $nextHeight);
        imagefilter($gdImageResource, IMG_FILTER_GAUSSIAN_BLUR);

        // clean up
        imagedestroy($prevImage);

        // return result
        return $gdImageResource;
    }

    public function mk_blur_pic($filepath, $new_name)
    {
        $type         = exif_imagetype($filepath); // [] if you don't have exif you could use getImageSize()
        $allowedTypes = [
            1, // [] gif
            2, // [] jpg
            3, // [] png
            6, // [] bmp
        ];
        if (!in_array($type, $allowedTypes)) {
            return false;
        }
        switch ($type) {
            case 1:
                $im = imagecreatefromgif($filepath);
                break;
            case 2:
                $im = imagecreatefromjpeg($filepath);
                break;
            case 3:
                $im = imagecreatefrompng($filepath);
                break;
            case 6:
                $im = imagecreatefrombmp($filepath);
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
    public function export_data($start_date = '', $end_date = '', $CateID = '')
    {
        global $xoopsDB, $xoopsTpl, $TadUpFiles, $MyWebs;
        $andCateID = empty($CateID) ? '' : "and `CateID`='$CateID'";
        $andStart  = empty($start_date) ? '' : "and ActionDate >= '{$start_date}'";
        $andEnd    = empty($end_date) ? '' : "and ActionDate <= '{$end_date}'";

        $sql    = 'select ActionID,ActionName,ActionDate,CateID from ' . $xoopsDB->prefix('tad_web_action') . " where WebID='{$this->WebID}' {$andStart} {$andEnd} {$andCateID} order by ActionDate";
        $result = $xoopsDB->query($sql) or web_error($sql, __FILE__, __LINE__);

        $i         = 0;
        $main_data = [];
        while (false !== (list($ID, $title, $date, $CateID) = $xoopsDB->fetchRow($result))) {
            $main_data[$i]['ID']     = $ID;
            $main_data[$i]['CateID'] = $CateID;
            $main_data[$i]['title']  = $title;
            $main_data[$i]['date']   = $date;

            $i++;
        }
        return $main_data;
    }
}
