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
        // $this->tags     = new tags($WebID);
        $this->setup    = get_plugin_setup_values($WebID, "menu");
    }

    //列出所有tad_web_menu資料內容
    public function list_all($CateID = "", $limit = null, $mode = "assign", $tag = '')
    {
        global $xoopsDB, $xoopsTpl, $TadUpFiles, $MyWebs, $isMyWeb, $plugin_menu_var;
        return;

        $andWebID = (empty($this->WebID)) ? "" : "and a.WebID='{$this->WebID}'";

        //取得tad_web_cate所有資料陣列
        $cate_arr = $this->web_cate->get_tad_web_cate_arr();
        // die(var_dump($cate_arr));
        $andCateID = "";
        if ($mode == "assign") {
            //取得tad_web_cate所有資料陣列

            if (!empty($plugin_menu_var)) {
                $this->web_cate->set_button_value($plugin_menu_var['menu']['short'] . _MD_TCW_CATE_TOOLS);
                $this->web_cate->set_default_option_text(sprintf(_MD_TCW_SELECT_PLUGIN_CATE, $plugin_menu_var['menu']['short']));
                $this->web_cate->set_col_md(0, 6);
                $cate_menu = $this->web_cate->cate_menu($CateID, 'menu', false, true, false, true);
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
            //https://class.tn.edu.tw/modules/tad_web/index.php?county=臺南市&city=永康區&SchoolName=XX國小
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
            order by a.Sort";
            // } elseif (!empty($tag)) {
            //     $sql = "select distinct a.* from " . $xoopsDB->prefix("tad_web_menu") . " as a
            //     left join " . $xoopsDB->prefix("tad_web") . " as b on a.WebID=b.WebID
            //     join " . $xoopsDB->prefix("tad_web_tags") . " as c on c.col_name='MenuID' and c.col_sn=a.MenuID
            //     left join " . $xoopsDB->prefix("tad_web_cate") . " as d on a.CateID=d.CateID
            //     where b.`WebEnable`='1' and d.CateEnable='1' and c.`tag_name`='{$tag}' $andWebID $andCateID
            //     order by a.MenuID desc";
        } else {
            $sql = "select a.* from " . $xoopsDB->prefix("tad_web_menu") . " as a
            left join " . $xoopsDB->prefix("tad_web") . " as b on a.WebID=b.WebID
            left join " . $xoopsDB->prefix("tad_web_cate") . " as c on a.CateID=c.CateID
            where b.`WebEnable`='1' and c.CateEnable='1' $andWebID $andCateID
            order by a.Sort";
        }

        $result = $xoopsDB->query($sql) or web_error($sql, __FILE__, __LINE__);

        $main_data = $cate_data = $cate_size = array();

        $i = 0;

        $Webs = getAllWebInfo();

        $cate = $this->web_cate->get_tad_web_cate_arr();

        while ($all = $xoopsDB->fetchArray($result)) {
            //以下會產生這些變數： $MenuID , $MenuTitle , $MenuDesc , $MenuDate , $MenuPlace , $uid , $WebID , $MenuCount
            foreach ($all as $k => $v) {
                $$k = $v;
            }

            if (!empty($CateID) and !empty($cate_arr)) {
                if ($cate_arr[$CateID]['CateEnable'] != '1') {
                    continue;
                }
            }

            //檢查權限
            $power = $this->power->check_power("read", "MenuID", $MenuID);
            if (!$power) {
                continue;
            }

            $all['isMyWeb']           = $isMyWeb;
            $main_data[$i] = $all;
            $main_data[$i]['id']      = $MenuID;
            $main_data[$i]['id_name'] = 'MenuID';
            $main_data[$i]['title']   = $MenuTitle;

            $this->web_cate->set_WebID($WebID);

            $main_data[$i]['cate']     = $cate_arr[$CateID];
            $main_data[$i]['WebTitle'] = "<a href='index.php?WebID={$WebID}'>{$Webs[$WebID]}</a>";
            // $main_data[$i]['isMyWeb']  = in_array($WebID, $MyWebs) ? 1 : 0;

            $cate_data[$CateID][] = $all;
            $cate_size[$CateID]   = $this->get_total($CateID);

            // $subdir = isset($WebID) ? "/{$WebID}" : "";
            // $TadUpFiles->set_dir('subdir', $subdir);
            // $TadUpFiles->set_col("MenuID", $MenuID);
            // $MenuPic = $TadUpFiles->get_pic_file('thumb');
            // die('MenuPic:' . $MenuPic);
            // $main_data[$i]['MenuPic'] = $MenuPic;
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
            $data['cate_arr']  = $cate_arr;
            $data['cate_data'] = $cate_data;
            $data['main_data'] = $main_data;
            $data['cate_size'] = $cate_size;
            // $data['total']     = $total;

            return $data;
        } else {
            // var_export($cate_arr);
            // var_export($cate_data);
            // var_export($main_data);
            // var_export($cate_size);
            // exit;
            $xoopsTpl->assign('cate_arr', $cate_arr);
            $xoopsTpl->assign('cate_data', $cate_data);
            $xoopsTpl->assign('menu_data', $main_data);
            $xoopsTpl->assign('cate_size', $cate_size);
            $xoopsTpl->assign('menu', get_db_plugin($this->WebID, 'menu'));
            // return $total;
        }
    }

    //以流水號秀出某筆tad_web_menu資料內容
    public function show_one($MenuID = "")
    {
            return;
        }

    //tad_web_menu編輯表單
    public function edit_form($MenuID = "")
    {
        global $xoopsDB, $xoopsUser, $MyWebs, $isMyWeb, $xoopsTpl, $TadUpFiles, $plugin_menu_var;

        $xoopsTpl->assign('plugin_menu_var', $plugin_menu_var);

        chk_self_web($this->WebID);
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

        //設定「ParentMenuID」欄位預設值
        $ParentMenuID = (!isset($DBV['ParentMenuID'])) ? 0 : $DBV['ParentMenuID'];
        $xoopsTpl->assign('ParentMenuID', $ParentMenuID);

        //設定「WebID」欄位預設值
        $WebID = (!isset($DBV['WebID'])) ? $this->WebID : $DBV['WebID'];
        $xoopsTpl->assign('WebID', $WebID);

        //設定「MenuTitle」欄位預設值
        $MenuTitle = (!isset($DBV['MenuTitle'])) ? "" : $DBV['MenuTitle'];
        $xoopsTpl->assign('MenuTitle', $MenuTitle);

        //設定「Plugin」欄位預設值
        $Plugin = (!isset($DBV['Plugin'])) ? "" : $DBV['Plugin'];
        $xoopsTpl->assign('Plugin', $Plugin);

        //設定「CateID」欄位預設值
        $CateID = (!isset($DBV['CateID'])) ? '' : $DBV['CateID'];
        $this->web_cate->set_button_value($plugin_menu_var['menu']['short'] . _MD_TCW_CATE_TOOLS);
        $this->web_cate->set_default_option_text(sprintf(_MD_TCW_SELECT_PLUGIN_CATE, $plugin_menu_var['menu']['short']));
        $cate_menu = $this->web_cate->cate_menu($CateID);
        $xoopsTpl->assign('cate_menu_form', $cate_menu);

        //設定「ColName」欄位預設值
        $ColName = (!isset($DBV['ColName'])) ? "" : $DBV['ColName'];
        $xoopsTpl->assign('ColName', $ColName);

        //設定「ColSn」欄位預設值
        $ColSn = (!isset($DBV['ColSn'])) ? "" : $DBV['ColSn'];
        $xoopsTpl->assign('ColSn', $ColSn);

        //設定「Link」欄位預設值
        $Link = (!isset($DBV['Link'])) ? "" : $DBV['Link'];
        $xoopsTpl->assign('Link', $Link);

        //設定「Target」欄位預設值
        $Target = (!isset($DBV['Target'])) ? "" : $DBV['Target'];
        $xoopsTpl->assign('Target', $Target);

        //設定「Icon」欄位預設值
        $Icon = (!isset($DBV['Icon'])) ? "" : $DBV['Icon'];
        $xoopsTpl->assign('Icon', $Icon);

        //設定「Color」欄位預設值
        $Color = (!isset($DBV['Color'])) ? "#000000" : $DBV['Color'];
        $xoopsTpl->assign('Color', $Color);

        //設定「BgColor」欄位預設值
        $BgColor = (!isset($DBV['BgColor'])) ? "tranparent" : $DBV['BgColor'];
        $xoopsTpl->assign('BgColor', $BgColor);

        //設定「Status」欄位預設值
        $Status = (!isset($DBV['Status'])) ? "" : $DBV['Status'];
        $xoopsTpl->assign('Status', $Status);

        //設定「Sort」欄位預設值
        $Sort = (!isset($DBV['Sort'])) ? "" : $DBV['Sort'];
        $xoopsTpl->assign('Sort', $Sort);

        //設定「MenuCount」欄位預設值
        $MenuCount = (!isset($DBV['MenuCount'])) ? "" : $DBV['MenuCount'];
        $xoopsTpl->assign('MenuCount', $MenuCount);

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
        // $TadUpFiles->set_col('MenuID', $MenuID); //若 $show_list_del_file ==true 時一定要有
        // $upform = $TadUpFiles->upform(true, 'upfile');
        // $xoopsTpl->assign('upform', $upform);

        $power_form = $this->power->power_menu('read', "MenuID", $MenuID);
        $xoopsTpl->assign('power_form', $power_form);

        // $tags_form = $this->tags->tags_menu("MenuID", $MenuID);
        // $xoopsTpl->assign('tags_form', $tags_form);

        //顏色設定
        if (!file_exists(XOOPS_ROOT_PATH . "/modules/tadtools/mColorPicker.php")) {
            redirect_header("index.php", 3, _MA_NEED_TADTOOLS);
        }
        include_once XOOPS_ROOT_PATH . "/modules/tadtools/mColorPicker.php";
        $mColorPicker = new mColorPicker('.color');
        $mColorPicker->render();
    }

    //新增資料到tad_web_menu中
    public function insert()
    {
        global $xoopsDB, $xoopsUser, $TadUpFiles, $WebOwnerUid;

        $myts               = MyTextSanitizer::getInstance();
        $MenuTitle     = $myts->addSlashes($_POST['MenuTitle']);
        $Plugin        = $myts->addSlashes($_POST['Plugin']);
        $PluginCate    = (int)$_POST['PluginCate'];
        $PluginContent = $myts->addSlashes($_POST['PluginContent']);
        $Link          = $myts->addSlashes($_POST['Link']);
        $Target        = $myts->addSlashes($_POST['Target']);
        $Icon          = $myts->addSlashes($_POST['Icon']);
        $Color         = $myts->addSlashes($_POST['Color']);
        $BgColor       = $myts->addSlashes($_POST['BgColor']);
        $ParentMenuID  = (int)$_POST['ParentMenuID'];
        $WebID         = (int)$_POST['WebID'];
        $Status        = (int)$_POST['Status'];
        $menu_type     = $myts->addSlashes($_POST['menu_type']);
        $CateID        = intval($_POST['CateID']);
        $newCateName     = $myts->addSlashes($_POST['newCateName']);

        $ColName = $ColSn = '';
        if ($menu_type == "Plugin") {
            if ($PluginContent) {
                list($ColName, $ColSn) = explode('=', $PluginContent);
            } elseif ($PluginCate) {
                $ColName = 'CateID';
                $ColSn   = $PluginCate;
            }
            $Link = '';
        } else {
            $Plugin = '';
        }

        $CateID = $this->web_cate->save_tad_web_cate($CateID, $newCateName);
        $Sort   = $this->max_sort($WebID, $CateID);

        $sql    = "insert into " . $xoopsDB->prefix("tad_web_menu") . "
        (`ParentMenuID`,`WebID`,`MenuTitle`,`Plugin`,`CateID`,`ColName`,`ColSn`,`Link`,`Target`,`Icon`,`Color`,`BgColor`,`Status`,`Sort`,`MenuCount`)
        values('{$ParentMenuID}' ,'{$WebID}' , '{$MenuTitle}' , '{$Plugin}' , '{$CateID}' ,'{$ColName}' , '{$ColSn}' ,'{$Link}' ,'{$Target}' ,'{$Icon}' ,'{$Color}' ,'{$BgColor}','{$Status}','{$Sort}',0)";
        $xoopsDB->query($sql) or web_error($sql, __FILE__, __LINE__);

        //取得最後新增資料的流水編號
        $MenuID = $xoopsDB->getInsertId();

        // $subdir = isset($this->WebID) ? "/{$this->WebID}" : "";
        // $TadUpFiles->set_dir('subdir', $subdir);
        // $TadUpFiles->set_col('MenuID', $MenuID);
        // $TadUpFiles->upload_file('upfile', 800, null, null, null, true);
        // check_quota($this->WebID);

        //儲存權限
        $this->power->save_power("MenuID", $MenuID, 'read');
        //儲存標籤
        // $this->tags->save_tags("MenuID", $MenuID, $_POST['tag_name'], $_POST['tags']);
        return $MenuID;
    }

    //更新tad_web_menu某一筆資料
    public function update($MenuID = "")
    {
        global $xoopsDB, $TadUpFiles;

        $myts               = MyTextSanitizer::getInstance();

        $myts          = MyTextSanitizer::getInstance();
        $MenuTitle     = $myts->addSlashes($_POST['MenuTitle']);
        $Plugin        = $myts->addSlashes($_POST['Plugin']);
        $PluginCate    = intval($_POST['PluginCate']);
        $PluginContent = intval($_POST['PluginContent']);
        $Link          = $myts->addSlashes($_POST['Link']);
        $Target        = $myts->addSlashes($_POST['Target']);
        $Icon          = $myts->addSlashes($_POST['Icon']);
        $Color         = $myts->addSlashes($_POST['Color']);
        $BgColor       = $myts->addSlashes($_POST['BgColor']);
        $ParentMenuID  = intval($_POST['ParentMenuID']);
        $WebID         = intval($_POST['WebID']);
        $Status        = intval($_POST['Status']);
        $menu_type     = $myts->addSlashes($_POST['menu_type']);
        $CateID        = intval($_POST['CateID']);
        $newCateName     = $myts->addSlashes($_POST['newCateName']);
        $read     = $myts->addSlashes($_POST['read']);

        $ColName = $ColSn = '';
        if ($menu_type == "Plugin") {
            if ($PluginContent) {
                list($ColName, $ColSn) = explode('=', $PluginContent);
            } elseif ($PluginCate) {
                $ColName = 'CateID';
                $ColSn   = $PluginCate;
            }
            $Link = '';
        } else {
            $Plugin = '';
        }
        $CateID = $this->web_cate->save_tad_web_cate($CateID, $newCateName);
        $Sort   = $this->max_sort($WebID, $CateID);

        $sql = "update " . $xoopsDB->prefix("tad_web_menu") . " set
            `ParentMenuID` = '{$ParentMenuID}' ,
            `WebID` = '{$WebID}' ,
            `MenuTitle` = '{$MenuTitle}' ,
            `Plugin` = '{$Plugin}' ,
         `CateID` = '{$CateID}' ,
            `ColName` = '{$ColName}' ,
            `ColSn` = '{$ColSn}' ,
            `Link` = '{$Link}' ,
            `Target` = '{$Target}' ,
            `Icon` = '{$Icon}' ,
            `Color` = '{$Color}' ,
            `BgColor` = '{$BgColor}' ,
            `Status` = '{$Status}'
            where MenuID='$MenuID'";
        $xoopsDB->queryF($sql) or web_error($sql, __FILE__, __LINE__);

        // $subdir = isset($this->WebID) ? "/{$this->WebID}" : "";
        // $TadUpFiles->set_dir('subdir', $subdir);
        // $TadUpFiles->set_col('MenuID', $MenuID);
        // $TadUpFiles->upload_file('upfile', 800, null, null, null, true);
        // check_quota($this->WebID);

        //儲存權限
        $read = $myts->addSlashes($read);
        $this->power->save_power("MenuID", $MenuID, 'read', $read);
        //儲存標籤
        // $this->tags->save_tags("MenuID", $MenuID, $_POST['tag_name'], $_POST['tags']);
        return $MenuID;
    }

    //刪除tad_web_menu某筆資料資料
    public function delete($MenuID = "")
    {
        global $xoopsDB, $TadUpFiles;
        $anduid = onlyMine();

        $sql = "delete from " . $xoopsDB->prefix("tad_web_menu") . " where MenuID='$MenuID' $anduid";
        $xoopsDB->queryF($sql) or web_error($sql, __FILE__, __LINE__);

        // $subdir = isset($this->WebID) ? "/{$this->WebID}" : "";
        // $TadUpFiles->set_dir('subdir', $subdir);
        $TadUpFiles->set_col('MenuID', $MenuID);
        $TadUpFiles->del_files();
        check_quota($this->WebID);

        $this->power->delete_power("MenuID", $MenuID, 'read');
        //刪除標籤
        // $this->tags->delete_tags("MenuID", $MenuID);
    }

    //刪除所有資料
    public function delete_all()
    {
        global $xoopsDB, $TadUpFiles;
        $allCateID = array();
        $sql       = "select MenuID,CateID from " . $xoopsDB->prefix("tad_web_menu") . " where WebID='{$this->WebID}'";
        $result    = $xoopsDB->queryF($sql) or web_error($sql, __FILE__, __LINE__);
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
    public function get_total($CateID = '')
    {
        global $xoopsDB;
        $andCate     = empty($CateID) ? '' : "and CateID='$CateID'";
        $sql         = "select count(*) from " . $xoopsDB->prefix("tad_web_menu") . " where WebID='{$this->WebID}' {$andCate}";
        $result      = $xoopsDB->query($sql) or web_error($sql, __FILE__, __LINE__);
        list($count) = $xoopsDB->fetchRow($result);
        return $count;
    }

    //新增tad_web_menu計數器
    public function add_counter($MenuID = '')
    {
        global $xoopsDB;
        $sql = "update " . $xoopsDB->prefix("tad_web_menu") . " set `MenuCount`=`MenuCount`+1 where `MenuID`='{$MenuID}'";
        $xoopsDB->queryF($sql) or web_error($sql, __FILE__, __LINE__);
    }

    //以流水號取得某筆tad_web_menu資料
    public function get_one_data($MenuID = "")
    {
        global $xoopsDB;
        if (empty($MenuID)) {
            return;
        }

        $sql    = "select * from " . $xoopsDB->prefix("tad_web_menu") . " where MenuID='$MenuID'";
        $result = $xoopsDB->query($sql) or web_error($sql, __FILE__, __LINE__);
        $data   = $xoopsDB->fetchArray($result);
        return $data;
    }

    //匯出資料
    public function export_data($start_date = "", $end_date = "", $CateID = "")
    {

        global $xoopsDB, $xoopsTpl, $TadUpFiles, $MyWebs;
        return;
    }

    //自動取得 tad_web_menu 的最新排序
    public function max_sort($WebID, $CateID)
    {
        global $xoopsDB;
        $sql        = "select max(`Sort`) from " . $xoopsDB->prefix("tad_web_menu") . " where WebID='$WebID' and CateID='{$CateID}'";
<<<<<<< HEAD
        $result     = $xoopsDB->query($sql) or web_error($sql, __FILE__, __LINE__);
=======
        $result = $xoopsDB->query($sql) or web_error($sql);
>>>>>>> 826dbd105d48639c01fd80ed38edf4d75ec4d744
        list($sort) = $xoopsDB->fetchRow($result);
        return ++$sort;
    }
}
