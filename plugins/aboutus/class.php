<?php
class tad_web_aboutus
{

    public $WebID = 0;
    public $web_cate;
    public $setup;

    public function tad_web_aboutus($WebID)
    {
        $this->WebID    = $WebID;
        $this->web_cate = new web_cate($WebID, "aboutus", "tad_web_link_mems");
        $this->setup    = get_plugin_setup_values($WebID, "aboutus");
    }

    //所有網站列表
    public function list_all()
    {
        global $xoopsDB, $MyWebs, $xoopsTpl, $TadUpFiles, $MyWebs, $xoopsModuleConfig, $isAdmin;
        $list_web_order = $xoopsModuleConfig['list_web_order'];
        if (empty($list_web_order)) {
            $list_web_order = 'WebSort';
        }
        //全國版
        if (XOOPS_URL == "http://class.tn.edu.tw") {
            include_once $GLOBALS['xoops']->path('/modules/system/include/functions.php');

            $def_county     = system_CleanVars($_REQUEST, 'county', '', 'string');
            $def_city       = system_CleanVars($_REQUEST, 'city', '', 'string');
            $def_SchoolName = system_CleanVars($_REQUEST, 'SchoolName', '', 'string');

            $and_county     = empty($def_county) ? "" : "and b.county='{$def_county}'";
            $and_city       = empty($def_city) ? "" : "and b.city='{$def_city}'";
            $and_SchoolName = empty($def_SchoolName) ? "" : "and b.SchoolName='{$def_SchoolName}'";

            $sql       = "select a.*,b.* from " . $xoopsDB->prefix("tad_web") . " as a left join " . $xoopsDB->prefix("apply") . " as b on a.WebOwnerUid=b.uid where a.`WebEnable`='1' {$and_county} {$and_city} {$and_SchoolName} order by b.zip, {$list_web_order}";
            $result    = $xoopsDB->query($sql) or web_error($sql);
            $total_web = 0;
            $all_webs  = "";
            while ($all = $xoopsDB->fetchArray($result)) {
                //以下會產生這些變數： $WebID , $WebName , $WebSort , $WebEnable , $WebCounter
                foreach ($all as $k => $v) {
                    $$k = $v;
                }

                if (!empty($def_SchoolName)) {
                    $all_webs[$WebID] = "{$WebTitle}-{$WebName}";
                    $county_counter[$WebID]++;
                } elseif (!empty($def_city)) {
                    $all_webs[$SchoolName] = $SchoolName;
                    $county_counter[$SchoolName]++;
                } elseif (!empty($def_county)) {
                    $all_webs[$city] = $city;
                    $county_counter[$city]++;
                } else {
                    $all_webs[$county] = $county;
                    $county_counter[$county]++;
                }
                $total_web++;
            }

            $data = "";
            $i    = 0;
            if (!empty($def_SchoolName)) {
                foreach ($all_webs as $key => $item) {
                    $data[$i]['WebID']    = $key;
                    $data[$i]['WebTitle'] = $item;
                    $data[$i]['counter']  = $county_counter[$key];
                    $i++;
                }
                $xoopsTpl->assign('get_mode', 'web');
                $xoopsTpl->assign('def_SchoolName', $def_SchoolName);
                $xoopsTpl->assign('def_city', $def_city);
                $xoopsTpl->assign('def_county', $def_county);
            } else
            if (!empty($def_city)) {
                foreach ($all_webs as $key => $item) {
                    $data[$i]['SchoolName'] = $item;
                    $data[$i]['counter']    = $county_counter[$key];
                    $i++;
                }
                $xoopsTpl->assign('get_mode', 'school');
                $xoopsTpl->assign('def_city', $def_city);
                $xoopsTpl->assign('def_county', $def_county);
            } elseif (!empty($def_county)) {
                foreach ($all_webs as $key => $item) {
                    $data[$i]['city']    = $item;
                    $data[$i]['counter'] = $county_counter[$key];
                    $i++;
                }

                $xoopsTpl->assign('get_mode', 'city');
                $xoopsTpl->assign('def_county', $def_county);
            } else {
                foreach ($all_webs as $key => $item) {
                    $data[$i]['county']  = $item;
                    $data[$i]['counter'] = $county_counter[$key];
                    $i++;
                }
                $xoopsTpl->assign('get_mode', 'all');
            }

            // die(var_export($data));
            $xoopsTpl->assign('count', sizeof($all_webs));
            $xoopsTpl->assign('web_version', 'all');
            $xoopsTpl->assign('data', $data);
            $xoopsTpl->assign('MyWebs', $MyWebs);
            $xoopsTpl->assign('total_web', $total_web);

        } else {

            $sql    = "select * from " . $xoopsDB->prefix("tad_web") . " where `WebEnable`='1' order by {$list_web_order}";
            $result = $xoopsDB->query($sql) or web_error($sql);

            $data = "";
            $i    = 0;
            while ($all = $xoopsDB->fetchArray($result)) {
                //以下會產生這些變數： $WebID , $WebName , $WebSort , $WebEnable , $WebCounter
                foreach ($all as $k => $v) {
                    $data[$i][$k] = $v;
                }

                $i++;
            }

            $xoopsTpl->assign('MyWebs', $MyWebs);
            $xoopsTpl->assign('data', $data);

            $xoopsTpl->assign('tad_web_cate', $this->get_tad_web_cate_all('tad_web'));
            $xoopsTpl->assign('aboutus', get_db_plugin($this->WebID, 'aboutus'));
            $xoopsTpl->assign('web_version', 'normal');
        }
    }

    //以流水號秀出某筆tad_web_mems資料內容
    public function show_one($DefCateID = "")
    {
        global $xoopsDB, $xoopsTpl, $MyWebs, $op, $TadUpFiles, $isMyWeb;
        $Web = get_tad_web($this->WebID, true);
        $xoopsTpl->assign('CateID', $DefCateID);

        $mode = ($isMyWeb and $MyWebs) ? "mem_adm" : "";

        // $TadUpFiles->set_col("WebOwner", $this->WebID, 1);
        // $teacher_pic = $TadUpFiles->get_pic_file();

        // $upform = $TadUpFiles->upform(true, 'upfile', '1', false);
        // $xoopsTpl->assign('upform_teacher', $upform);

        //班級圖片
        $TadUpFiles->set_col("ClassPic", $DefCateID, 1);
        $class_pic_thumb = $TadUpFiles->get_pic_file("thumb");
        $xoopsTpl->assign('class_pic_thumb', $class_pic_thumb);
        $upform = $TadUpFiles->upform(true, 'upfile', '1', false);
        $xoopsTpl->assign('upform_class', $upform);

        $ys = get_seme();
        $xoopsTpl->assign('ys', $ys);

        $this->web_cate->set_default_value(sprintf(_MD_TCW_SEME_CATE, $ys[0]));
        $this->web_cate->set_label(sprintf(_MD_TCW_SET_SEME, $this->setup['class_title']));
        $this->web_cate->set_default_option_text(sprintf(_MD_TCW_SELECT_SEME, $this->setup['class_title']));
        $this->web_cate->set_col_md(3, 12);
        //cate_menu($defCateID = "", $mode = "form", $newCate = true, $change_page = false, $show_label = true, $show_tools = false, $show_select = true, $required = false)
        $cate_menu = $this->web_cate->cate_menu($DefCateID, 'page', false, true, false, false);
        $cate      = $this->web_cate->get_tad_web_cate($DefCateID);
        $xoopsTpl->assign('cate_menu', $cate_menu);
        $xoopsTpl->assign('cate', $cate);

        $sql    = "select a.*,b.* from " . $xoopsDB->prefix("tad_web_link_mems") . " as a left join " . $xoopsDB->prefix("tad_web_mems") . " as b on a.MemID=b.MemID where a.WebID ='{$this->WebID}' and a.MemEnable='1' and a.CateID='{$DefCateID}'";
        $result = $xoopsDB->query($sql) or web_error($sql);
        $i      = 0;

        $students1   = $students2   = "";
        $class_total = $class_boy = $class_girl = 0;

        // $subdir = isset($this->WebID) ? "/{$this->WebID}" : "";
        // $TadUpFiles->set_dir('subdir', $subdir);
        while ($all = $xoopsDB->fetchArray($result)) {

            //以下會產生這些變數： `MemID`, `MemName`, `MemNickName`, `MemSex`, `MemUnicode`, `MemBirthday`, `MemUrl`, `MemClassOrgan`, `MemExpertises`, `uid`, `MemUname`, `MemPasswd`,`WebID`, `MemNum`, `MemSort`, `MemEnable`, `top`, `left`
            foreach ($all as $k => $v) {
                $$k               = $v;
                $all_main[$i][$k] = $v;
            }

            $TadUpFiles->set_col("MemID", $MemID, 1);
            $pic_url = $TadUpFiles->get_pic_file('thumb');

            if (empty($pic_url) or !$isMyWeb) {
                $pic   = ($MemSex == '1') ? "images/boy.gif" : "images/girl.gif";
                $cover = "";
            } else {
                $pic   = $pic_url;
                $cover = "background-size: cover;";
            }

            if (!$isMyWeb) {
                $MemName = empty($MemNickName) ? mb_substr($MemName, 0, 1, _CHARSET) . _MD_TCW_SOMEBODY : $MemNickName;
            }

            $color  = ($MemSex == '1') ? "#006699" : "#CC3300";
            $color2 = ($MemSex == '1') ? "#000066" : "#660000";
            if ($MemSex == '1') {
                $class_boy++;
            } else {
                $class_girl++;
            }

            $style = (empty($top) and empty($left)) ? "float:left;" : "top:{$top}px;left:{$left}px;";

            $MemName = empty($MemName) ? "---" : $MemName;

            $StuUrl = ($mode == "mem_adm") ? "aboutus.php?WebID={$this->WebID}&CateID={$DefCateID}&MemID={$MemID}&op=edit_stu" : '#';

            $students = "<div id='{$MemNum}' class='draggable' style='width:60px;height:60px;background:transparent url($pic) top center no-repeat;{$style};{$cover}padding:0px;'><p style='width:100%;line-height:1;text-align:center;margin:50px 0px 0px 0px;font-size:11px;padding:3px 1px;color:{$color2};text-shadow: 1px 1px 0 #FFFFFF, -1px -1px 0 #FFFFFF, 1px -1px 0 #FFFFFF, -1px 1px 0 #FFFFFF, 0px -1px 0 #FFFFFF, 0px 1px 0 #FFFFFF, -1px 0px 0 #FFFFFF, 1px 0px 0 #FFFFFF'>{$MemNum} <a href='{$StuUrl}' style='font-weight:normal;color:{$color2};text-shadow: 1px 1px 0 #FFFFFF, -1px -1px 0 #FFFFFF, 1px -1px 0 #FFFFFF, -1px 1px 0 #FFFFFF, 0px -1px 0 #FFFFFF, 0px 1px 0 #FFFFFF, -1px 0px 0 #FFFFFF, 1px 0px 0 #FFFFFF;'>{$MemName}</a></p></div>";

            //$students = "<div id='{$StuID}' class='draggable'>{$MemName}</a></p></div>";

            if (empty($top) and empty($left)) {
                $students2 .= $students;
            } else {
                $students1 .= $students;
            }
            $class_total++;

            $i++;
        }

        if ($mode == "mem_adm") {
            $xoopsTpl->assign('mode', 'mem_adm');
        }

        $xoopsTpl->assign('all_mems', $all_main);
        $xoopsTpl->assign('WebOwner', $Web['WebOwner']);
        $xoopsTpl->assign('class_total', $class_total);
        $xoopsTpl->assign('class_boy', $class_boy);
        $xoopsTpl->assign('class_girl', $class_girl);
        $xoopsTpl->assign('students1', $students1);
        $xoopsTpl->assign('students2', $students2);

        $sql             = "select min(`MemNum`) as min , max(`MemNum`) as max from " . $xoopsDB->prefix("tad_web_link_mems") . " where `CateID` = '{$DefCateID}' and MemEnable='1' and `MemNum` > 0";
        $result          = $xoopsDB->query($sql) or web_error($sql);
        list($min, $max) = $xoopsDB->fetchRow($result);

        $xoopsTpl->assign('min', $min);
        $xoopsTpl->assign('max', $max);
        if (empty($all_main)) {
            $xoopsTpl->assign('add_stud', sprintf(_MD_TCW_ADD_MEM, $this->setup['student_title']));
            $xoopsTpl->assign('no_student', sprintf(_MD_TCW_NO_MEM, $this->setup['student_title']));
        }
        $xoopsTpl->assign('class_pic', sprintf(_MD_TCW_CLASS_PIC, $this->setup['class_title']));
        $xoopsTpl->assign('add_stud', sprintf(_MD_TCW_ADD_MEM, $this->setup['student_title']));
        $xoopsTpl->assign('import_excel', sprintf(_MD_TCW_IMPORT_EXCEL, $this->setup['student_title']));
        $xoopsTpl->assign('student_amount', sprintf(_MD_TCW_MEM_AMOUNT, $this->setup['student_title']));
        $xoopsTpl->assign('class_setup', sprintf(_MD_TCW_CLASS_SETUP, $this->setup['class_title']));
        $xoopsTpl->assign('no_class_photo', sprintf(_MD_TCW_NO_CLASS_PIC, $this->setup['class_title']));
        $xoopsTpl->assign('edit_student', sprintf(_MD_TCW_EDIT_MEM, $this->setup['student_title']));
        $xoopsTpl->assign('teacher_name', sprintf(_MD_TCW_OWNER_NAME, $this->setup['teacher_title']));
        $xoopsTpl->assign('add_class', sprintf(_MD_TCW_ADD_CLASS, $this->setup['class_title']));

    }

    //班級管理
    public function edit_form($DefCateID = "")
    {

        global $xoopsDB, $xoopsTpl, $MyWebs, $op, $TadUpFiles, $isMyWeb, $xoopsUser;
        if (!$isMyWeb and $MyWebs) {
            redirect_header($_SERVER['PHP_SELF'] . "?op=WebID={$MyWebs[0]}&op=edit_form", 3, _MD_TCW_AUTO_TO_HOME);
        } elseif (!$xoopsUser or empty($this->WebID) or empty($MyWebs)) {
            redirect_header("index.php", 3, _MD_TCW_NOT_OWNER);
        }

        $xoopsTpl->assign('class_pic', sprintf(_MD_TCW_CLASS_PIC, $this->setup['class_title']));

        //班級圖片
        $TadUpFiles->set_col("ClassPic", $DefCateID, 1);
        $upform = $TadUpFiles->upform(true, 'upfile', '1', false);
        $xoopsTpl->assign('upform_class', $upform);
        $class_pic_thumb = $TadUpFiles->get_pic_file("thumb");
        $xoopsTpl->assign('class_pic_thumb', $class_pic_thumb);

        $ys        = get_seme();
        $last_year = $ys[0] - 1;
        $next_year = $ys[0] + 1;
        $xoopsTpl->assign('now_year', sprintf(_MD_TCW_SEME_CATE, $ys[0]));
        $xoopsTpl->assign('last_year', sprintf(_MD_TCW_SEME_CATE, $last_year));
        $xoopsTpl->assign('next_year', sprintf(_MD_TCW_SEME_CATE, $next_year));

        //所有曾經的班級
        $cate = $this->web_cate->get_tad_web_cate_arr();
        // die(var_export($cate));
        foreach ($cate as $key => $value) {
            // _MD_TCW_STUDENT_COPY
            $old_cate[$key]                 = $value;
            $old_cate[$key]['CateNameMem']  = sprintf(_MD_TCW_STUDENT_COPY, $value['CateName'], $this->setup['student_title']);
            $old_cate[$key]['CateMemCount'] = $this->get_total($value['CateID']);

        }
        $xoopsTpl->assign('old_cate', $old_cate);

        //目前欲編輯的班級
        if ($DefCateID) {
            $now_cate = $this->web_cate->get_tad_web_cate($DefCateID);
            $xoopsTpl->assign('now_cate', $now_cate);
            $xoopsTpl->assign('CateID', $DefCateID);
            $xoopsTpl->assign('next_op', "update_class");
        } else {
            $xoopsTpl->assign('next_op', "insert_class");
        }

        //我的所有班級
        $xoopsTpl->assign('class_list', sprintf(_MD_TCW_CLASS_LIST, $this->setup['class_title']));
        $xoopsTpl->assign('class_title', sprintf(_MD_TCW_CLASS_TITLE, $this->setup['class_title']));
        $xoopsTpl->assign('student_amount', sprintf(_MD_TCW_MEM_AMOUNT, $this->setup['student_title']));
        $xoopsTpl->assign('edit_student', sprintf(_MD_TCW_EDIT_MEM, $this->setup['student_title']));
        $xoopsTpl->assign('add_class', sprintf(_MD_TCW_ADD_CLASS, $this->setup['class_title']));
        $xoopsTpl->assign('class_setup', sprintf(_MD_TCW_CLASS_SETUP, $this->setup['class_title']));
        $xoopsTpl->assign('no_class_photo', sprintf(_MD_TCW_NO_CLASS_PIC, $this->setup['class_title']));
        $xoopsTpl->assign('setup_stud', sprintf(_MD_TCW_STUDENT_SETUP, $this->setup['student_title']));
        $xoopsTpl->assign('edit_class_title', sprintf(_MD_TCW_EDIT_CLASS_TITLE, $this->setup['class_title']));

        if (!file_exists(XOOPS_ROOT_PATH . "/modules/tadtools/sweet_alert.php")) {
            redirect_header("index.php", 3, _MA_NEED_TADTOOLS);
        }
        include_once XOOPS_ROOT_PATH . "/modules/tadtools/sweet_alert.php";
        $sweet_alert      = new sweet_alert();
        $sweet_alert_code = $sweet_alert->render("del_class", "aboutus.php?op=del_class&WebID={$this->WebID}&CateID=", 'CateID');
        $xoopsTpl->assign('sweet_alert_code', $sweet_alert_code);

        $default_class = get_web_config('default_class', $this->WebID);
        $xoopsTpl->assign('default_class', $default_class);
    }

    //新增班級
    public function insert_class($year = "", $newCateName = "")
    {
        global $xoopsDB, $xoopsUser, $TadUpFiles, $xoopsTpl;

        $myts        = MyTextSanitizer::getInstance();
        $and_year    = empty($year) ? '' : "{$year} ";
        $newCateName = $myts->addSlashes($and_year . $newCateName);
        $CateID      = $this->web_cate->save_tad_web_cate("", $newCateName);
        $TadUpFiles->set_col("ClassPic", $CateID, 1);
        $TadUpFiles->upload_file("upfile", 1280, 300, null, null, true);

        if ($_POST['default_class'] == '1') {
            save_web_config("default_class", $CateID, $this->WebID);
            $this->update_web_title($newCateName);
        }

        if (!empty($_POST['form_CateID'])) {
            $form_CateID = intval($_POST['form_CateID']);
            $sql         = "select * from " . $xoopsDB->prefix("tad_web_link_mems") . " where CateID='{$form_CateID}'";
            $result      = $xoopsDB->query($sql) or web_error($sql);
            while ($all = $xoopsDB->fetchArray($result)) {
                $sql = "insert into " . $xoopsDB->prefix("tad_web_link_mems") . "
              (`MemID`, `WebID`, `CateID`, `MemNum`, `MemSort`, `MemEnable` , `top` ,`left`)
              values('{$all['MemID']}' , '{$this->WebID}' , '{$CateID}', '{$all['MemNum']}' , '{$all['MemSort']}' , '{$all['MemEnable']}' , '{$all['top']}' , '{$all['left']}' )";

                $xoopsDB->queryF($sql) or web_error($sql);
            }
        }

        return $CateID;
    }

    //更新班級
    public function update_class($CateID = '', $year = "", $newCateName = "")
    {
        global $xoopsDB, $xoopsUser, $TadUpFiles, $xoopsTpl;

        $myts        = MyTextSanitizer::getInstance();
        $and_year    = empty($year) ? '' : "{$year} ";
        $newCateName = $myts->addSlashes($and_year . $newCateName);
        $TadUpFiles->set_col("ClassPic", $CateID, 1);
        $TadUpFiles->upload_file("upfile", 1280, 300, null, null, true);
        $this->web_cate->update_tad_web_cate($CateID, $newCateName);

        if ($_POST['default_class'] == '1') {
            save_web_config("default_class", $CateID, $this->WebID);
            $this->update_web_title($newCateName);
        }
    }

    //刪除班級
    public function del_class($CateID = '')
    {
        global $xoopsDB, $xoopsUser, $TadUpFiles, $xoopsTpl;

        //刪除學生連結
        $this->delete('', $CateID);

        //刪除照片
        $TadUpFiles->set_col("ClassPic", $CateID);
        $TadUpFiles->del_files();

        //刪除班級
        $this->web_cate->delete_tad_web_cate($CateID);
    }

    //排座位
    public function edit_position($DefCateID = '')
    {
        global $xoopsDB, $xoopsUser, $MyWebs, $isMyWeb, $xoopsTpl, $TadUpFiles;
        if (!$isMyWeb and $MyWebs) {
            redirect_header($_SERVER['PHP_SELF'] . "?op=WebID={$MyWebs[0]}&op=edit_form", 3, _MD_TCW_AUTO_TO_HOME);
        } elseif (!$xoopsUser or empty($this->WebID) or empty($MyWebs) or empty($DefCateID)) {
            redirect_header("index.php", 3, _MD_TCW_NOT_OWNER);
        }
        get_quota($this->WebID);
        // $Web = get_tad_web($this->WebID);
        $xoopsTpl->assign('CateID', $DefCateID);
        $xoopsTpl->assign('setup_stud', sprintf(_MD_TCW_STUDENT_SETUP, $this->setup['student_title']));

        $sql    = "select a.*,b.* from " . $xoopsDB->prefix("tad_web_link_mems") . " as a left join " . $xoopsDB->prefix("tad_web_mems") . " as b on a.MemID=b.MemID where a.WebID ='{$this->WebID}' and a.MemEnable='1' and a.CateID='{$DefCateID}'";
        $result = $xoopsDB->query($sql) or web_error($sql);
        $i      = 0;

        $students1   = $students2   = "";
        $class_total = $class_boy = $class_girl = 0;

        while ($all = $xoopsDB->fetchArray($result)) {

            //以下會產生這些變數： `MemID`, `MemName`, `MemNickName`, `MemSex`, `MemUnicode`, `MemBirthday`, `MemUrl`, `MemClassOrgan`, `MemExpertises`, `uid`, `MemUname`, `MemPasswd`,`WebID`, `MemNum`, `MemSort`, `MemEnable`, `top`, `left`
            foreach ($all as $k => $v) {
                $$k               = $v;
                $all_main[$i][$k] = $v;
            }

            $TadUpFiles->set_col("MemID", $MemID, 1);
            $pic_url = $TadUpFiles->get_pic_file('thumb');

            if (empty($pic_url) or !$isMyWeb) {
                $pic   = ($MemSex == '1') ? "images/boy.gif" : "images/girl.gif";
                $cover = "";
            } else {
                $pic   = $pic_url;
                $cover = "background-size: cover;";
            }

            if (!$isMyWeb) {
                $MemName = empty($MemNickName) ? mb_substr($MemName, 0, 1, _CHARSET) . _MD_TCW_SOMEBODY : $MemNickName;
            }

            $color  = ($MemSex == '1') ? "#006699" : "#CC3300";
            $color2 = ($MemSex == '1') ? "#000066" : "#660000";

            $style = (empty($top) and empty($left)) ? "float:left;" : "top:{$top}px;left:{$left}px;";

            $MemName = empty($MemName) ? "---" : $MemName;

            $StuID  = $MemID;
            $StuUrl = "aboutus.php?WebID={$this->WebID}&CateID={$DefCateID}&MemID={$MemID}&op=edit_stu";

            $students = "<div id='{$StuID}' class='draggable' style='width:60px;height:60px;background:transparent url($pic) top center no-repeat;{$style};{$cover}padding:0px;'><p style='width:100%;line-height:1;text-align:center;margin:50px 0px 0px 0px;font-size:11px;padding:3px 1px;color:{$color2};text-shadow: 1px 1px 0 #FFFFFF, -1px -1px 0 #FFFFFF, 1px -1px 0 #FFFFFF, -1px 1px 0 #FFFFFF, 0px -1px 0 #FFFFFF, 0px 1px 0 #FFFFFF, -1px 0px 0 #FFFFFF, 1px 0px 0 #FFFFFF'>{$MemNum} <a href='{$StuUrl}' style='font-weight:normal;color:{$color2};text-shadow: 1px 1px 0 #FFFFFF, -1px -1px 0 #FFFFFF, 1px -1px 0 #FFFFFF, -1px 1px 0 #FFFFFF, 0px -1px 0 #FFFFFF, 0px 1px 0 #FFFFFF, -1px 0px 0 #FFFFFF, 1px 0px 0 #FFFFFF;'>{$MemName}</a></p></div>";

            //$students = "<div id='{$StuID}' class='draggable'>{$MemName}</a></p></div>";

            if (empty($top) and empty($left)) {
                $students2 .= $students;
            } else {
                $students1 .= $students;
            }
            $class_total++;

            $i++;
        }
        $xoopsTpl->assign('students1', $students1);
        $xoopsTpl->assign('students2', $students2);

        $xoopsTpl->assign('add_stud', sprintf(_MD_TCW_ADD_MEM, $this->setup['student_title']));
        $xoopsTpl->assign('no_student', sprintf(_MD_TCW_NO_MEM, $this->setup['student_title']));
        $xoopsTpl->assign('import_excel', sprintf(_MD_TCW_IMPORT_EXCEL, $this->setup['student_title']));

        // $this->web_cate->set_default_value(sprintf(_MD_TCW_SEME_CATE, $ys[0]));
        $this->web_cate->set_label(sprintf(_MD_TCW_SET_SEME, $this->setup['class_title']));
        $this->web_cate->set_default_option_text(sprintf(_MD_TCW_SELECT_SEME, $this->setup['class_title']));
        $this->web_cate->set_col_md(2, 10);
        $cate_menu = $this->web_cate->cate_menu($DefCateID, 'page', false, true, true, false, true, false, false);
        $cate      = $this->web_cate->get_tad_web_cate($DefCateID);
        $xoopsTpl->assign('cate_menu', $cate_menu);
        $xoopsTpl->assign('cate', $cate);
    }

    //編輯班級學生
    public function edit_class_stu($DefCateID = '')
    {
        global $xoopsDB, $xoopsUser, $MyWebs, $isMyWeb, $xoopsTpl, $TadUpFiles;
        if (!$isMyWeb and $MyWebs) {
            redirect_header($_SERVER['PHP_SELF'] . "?op=WebID={$MyWebs[0]}&op=edit_form", 3, _MD_TCW_AUTO_TO_HOME);
        } elseif (!$xoopsUser or empty($this->WebID) or empty($MyWebs)) {
            redirect_header("index.php", 3, _MD_TCW_NOT_OWNER);
        }
        get_quota($this->WebID);
        // $Web = get_tad_web($this->WebID);
        $xoopsTpl->assign('CateID', $DefCateID);
        $xoopsTpl->assign('setup_stud', sprintf(_MD_TCW_STUDENT_SETUP, $this->setup['student_title']));

        $sql    = "select a.*,b.* from " . $xoopsDB->prefix("tad_web_link_mems") . " as a left join " . $xoopsDB->prefix("tad_web_mems") . " as b on a.MemID=b.MemID where a.WebID ='{$this->WebID}' and a.MemEnable='1' and a.CateID='{$DefCateID}'";
        $result = $xoopsDB->query($sql) or web_error($sql);
        $i      = 0;

        $students = "";

        while ($all = $xoopsDB->fetchArray($result)) {

            //以下會產生這些變數： `MemID`, `MemName`, `MemNickName`, `MemSex`, `MemUnicode`, `MemBirthday`, `MemUrl`, `MemClassOrgan`, `MemExpertises`, `uid`, `MemUname`, `MemPasswd`,`WebID`, `MemNum`, `MemSort`, `MemEnable`, `top`, `left`
            foreach ($all as $k => $v) {
                $$k               = $v;
                $students[$i][$k] = $v;
            }

            $TadUpFiles->set_col("MemID", $MemID, 1);
            $pic_url = $TadUpFiles->get_pic_file('thumb');

            if (empty($pic_url) or !$isMyWeb) {
                $students[$i]['pic'] = ($MemSex == '1') ? "images/boy.gif" : "images/girl.gif";
            } else {
                $students[$i]['pic'] = $pic_url;
            }

            $students[$i]['MemSex'] = ($MemSex == '1') ? _MD_TCW_BOY : _MD_TCW_GIRL;

            $i++;
        }
        $xoopsTpl->assign('students', $students);
        $xoopsTpl->assign('edit_student', sprintf(_MD_TCW_EDIT_MEM, $this->setup['student_title']));
        $xoopsTpl->assign('add_stud', sprintf(_MD_TCW_ADD_MEM, $this->setup['student_title']));
        $xoopsTpl->assign('no_student', sprintf(_MD_TCW_NO_MEM, $this->setup['student_title']));
        $xoopsTpl->assign('import_excel', sprintf(_MD_TCW_IMPORT_EXCEL, $this->setup['student_title']));
        // $xoopsTpl->assign('add_stud', sprintf(_MD_TCW_ADD_STUDENT, $this->setup['student_title']));

        $cate = $this->web_cate->get_tad_web_cate($DefCateID);
        $xoopsTpl->assign('cate', $cate);
    }

    //顯示某個學生
    public function show_stu($MemID = "0", $DefCateID = '')
    {
        global $xoopsDB, $xoopsUser, $TadUpFiles, $xoopsTpl, $isMyWeb, $MyWebs;
        if (empty($MemID)) {
            return;
        }
        $mem       = get_tad_web_mems($MemID);
        $class_mem = get_tad_web_link_mems($MemID, $DefCateID);

        $TadUpFiles->set_col("MemID", $MemID, 1);
        $pic_url = $TadUpFiles->get_pic_file();

        if (empty($pic_url)) {
            $pic = ($mem['MemSex'] == '1') ? XOOPS_URL . "/modules/tad_web/images/boy.gif" : XOOPS_URL . "/modules/tad_web/images/girl.gif";
        } else {
            $pic = $pic_url;
        }
        $mem['MemSex'] = ($mem['MemSex'] == '1') ? _MD_TCW_BOY : _MD_TCW_GIRL;

        $xoopsTpl->assign('pic', $pic);
        $xoopsTpl->assign('CateID', $DefCateID);
        $xoopsTpl->assign('mem', $mem);
        $xoopsTpl->assign('class_mem', $class_mem);
        $cate = $this->web_cate->get_tad_web_cate($DefCateID);
        $xoopsTpl->assign('cate', $cate);

        //所有學生
        $sql    = "select a.*,b.* from " . $xoopsDB->prefix("tad_web_link_mems") . " as a left join " . $xoopsDB->prefix("tad_web_mems") . " as b on a.MemID=b.MemID where a.WebID ='{$this->WebID}' and a.MemEnable='1' and a.CateID='{$DefCateID}'";
        $result = $xoopsDB->query($sql) or web_error($sql);
        $i      = 0;

        $students = "";
        while ($all = $xoopsDB->fetchArray($result)) {
            $students[$i]           = $all;
            $students[$i]['color']  = ($all['MemSex'] == '1') ? 'blue' : 'red';
            $students[$i]['MemSex'] = ($all['MemSex'] == '1') ? _MD_TCW_BOY : _MD_TCW_GIRL;
            $i++;
        }
        $xoopsTpl->assign('students', $students);
    }

    //tad_students編輯表單 $mode='return', 'assign'
    public function edit_stu($MemID = "0", $DefCateID = '')
    {
        global $xoopsDB, $xoopsUser, $TadUpFiles, $xoopsTpl, $isMyWeb, $MyWebs;
        if (!$isMyWeb and $MyWebs) {
            redirect_header($_SERVER['PHP_SELF'] . "?op=WebID={$MyWebs[0]}&op=edit_form", 3, _MD_TCW_AUTO_TO_HOME);
        } elseif (!$xoopsUser or empty($this->WebID) or empty($MyWebs)) {
            redirect_header("index.php", 3, _MD_TCW_NOT_OWNER);
        }

        // $ys = get_seme();

        //抓取預設值
        if (!empty($MemID)) {
            $DBV  = get_tad_web_mems($MemID);
            $DBV2 = get_tad_web_link_mems($MemID, $DefCateID);
        } else {
            $DBV = $DBV2 = array();
        }

        //`MemID`, `MemName`, `MemNickName`, `MemSex`, `MemUnicode`, `MemBirthday`, `MemUrl`, `MemClassOrgan`, `MemExpertises`, `uid`, `MemUname`, `MemPasswd`, `MemNum`, `MemSort`, `MemEnable`, `top`, `left`

        //設定「MemName」欄位預設值
        $MemName = (!isset($DBV['MemName'])) ? "" : $DBV['MemName'];

        //設定「MemNickName」欄位預設值
        $MemNickName = (!isset($DBV['MemNickName'])) ? "" : $DBV['MemNickName'];

        //設定「MemSex」欄位預設值
        $MemSex = (!isset($DBV['MemSex'])) ? "" : $DBV['MemSex'];

        //設定「MemUnicode」欄位預設值
        $MemUnicode = (!isset($DBV['MemUnicode'])) ? "" : $DBV['MemUnicode'];

        //設定「MemBirthday」欄位預設值
        $MemBirthday = (!isset($DBV['MemBirthday'])) ? "" : $DBV['MemBirthday'];

        //設定「MemUrl」欄位預設值
        $MemUrl = (!isset($DBV['MemUrl'])) ? "" : $DBV['MemUrl'];

        //設定「MemClassOrgan」欄位預設值
        $MemClassOrgan = (!isset($DBV['MemClassOrgan'])) ? "" : $DBV['MemClassOrgan'];

        //設定「MemExpertises」欄位預設值
        $MemExpertises = (!isset($DBV['MemExpertises'])) ? "" : $DBV['MemExpertises'];

        //設定「uid」欄位預設值
        $uid = (!isset($DBV['uid'])) ? "" : $DBV['uid'];

        //設定「MemUname」欄位預設值
        $MemUname = (!isset($DBV['MemUname'])) ? "" : $DBV['MemUname'];

        //設定「MemPasswd」欄位預設值
        $MemPasswd = (!isset($DBV['MemPasswd'])) ? "" : $DBV['MemPasswd'];

        //設定「MemNum」欄位預設值
        $MemNum = (!isset($DBV2['MemNum'])) ? "" : $DBV2['MemNum'];

        //設定「MemSort」欄位預設值
        $MemSort = (!isset($DBV2['MemSort'])) ? "" : $DBV2['MemSort'];

        //設定「MemEnable」欄位預設值
        $MemEnable = (!isset($DBV2['MemEnable'])) ? "" : $DBV2['MemEnable'];

        //設定「CateID」欄位預設值
        $CateID = (!isset($DBV2['CateID'])) ? $DefCateID : $DBV2['CateID'];

        // $this->web_cate->set_label(sprintf(_MD_TCW_SELECT_CLASS, $this->setup['class_title']));
        // $this->web_cate->set_col_md(3, 9);
        // $cate_menu = $this->web_cate->cate_menu($CateID, 'menu', true, false, true, false, true, true, false);
        $cate = $this->web_cate->get_tad_web_cate($DefCateID);
        $xoopsTpl->assign('cate', $cate);

        //設定「top」欄位預設值
        $top = (!isset($DBV['top'])) ? "" : $DBV['top'];

        //設定「left」欄位預設值
        $left = (!isset($DBV['left'])) ? "" : $DBV['left'];

        $op = (empty($MemID)) ? "insert" : "update";

        $TadUpFiles->set_col("MemID", $MemID, 1);

        // $subdir = isset($this->WebID) ? "/{$this->WebID}" : "";
        // $TadUpFiles->set_dir('subdir', $subdir);
        $pic_url = $TadUpFiles->get_pic_file('thumb');

        if (empty($pic_url)) {
            $pic   = ($MemSex == '1') ? XOOPS_URL . "/modules/tad_web/images/boy.gif" : XOOPS_URL . "/modules/tad_web/images/girl.gif";
            $cover = "";
        } else {
            $pic   = $pic_url;
            $cover = "background-size: cover;";
        }

        $color2 = ($MemSex == '1') ? "#000066" : "#660000";

        $pic = !empty($MemID) ? "
          <div id='{$MemID}' style='padding: 5px;font-size: 12px; border:0px dotted gray;width:60px;height:50px;background:transparent url($pic) top center no-repeat;margin:0px auto;{$cover}'>
          </div>" : "";

        if (!empty($MemID)) {
            $del_btn = "<a href=\"javascript:delete_student_func($MemID);\" class='btn btn-danger'>" . _TAD_DEL . "</a>";
        } else {
            $del_btn = "";
        }

        $xoopsTpl->assign('MemName', $MemName);
        $xoopsTpl->assign('MemNickName', $MemNickName);
        $xoopsTpl->assign('pic', $pic);
        $xoopsTpl->assign('MemSex', $MemSex);
        $xoopsTpl->assign('MemBirthday', $MemBirthday);
        $xoopsTpl->assign('MemUnicode', $MemUnicode);
        $xoopsTpl->assign('MemNum', $MemNum);
        $xoopsTpl->assign('MemEnable', $MemEnable);
        $xoopsTpl->assign('MemUname', $MemUname);
        $xoopsTpl->assign('MemPasswd', $MemPasswd);
        $xoopsTpl->assign('MemID', $MemID);
        $xoopsTpl->assign('next_op', $op);
        $xoopsTpl->assign('del_btn', $del_btn);

        $xoopsTpl->assign('setup_stud', sprintf(_MD_TCW_STUDENT_SETUP, $this->setup['student_title']));
        $xoopsTpl->assign('select_class', sprintf(_MD_TCW_SET_SEME, $this->setup['class_title']));
        $xoopsTpl->assign('add_stud', sprintf(_MD_TCW_ADD_MEM, $this->setup['student_title']));

        //所有學生
        $sql    = "select a.*,b.* from " . $xoopsDB->prefix("tad_web_link_mems") . " as a left join " . $xoopsDB->prefix("tad_web_mems") . " as b on a.MemID=b.MemID where a.WebID ='{$this->WebID}' and a.MemEnable='1' and a.CateID='{$DefCateID}'";
        $result = $xoopsDB->query($sql) or web_error($sql);
        $i      = 0;

        $students = "";
        while ($all = $xoopsDB->fetchArray($result)) {
            $students[$i]           = $all;
            $students[$i]['color']  = ($all['MemSex'] == '1') ? 'blue' : 'red';
            $students[$i]['MemSex'] = ($all['MemSex'] == '1') ? _MD_TCW_BOY : _MD_TCW_GIRL;
            $i++;
        }
        $xoopsTpl->assign('students', $students);

        if (!file_exists(TADTOOLS_PATH . "/formValidator.php")) {
            redirect_header("index.php", 3, _MA_NEED_TADTOOLS);
        }
        include_once TADTOOLS_PATH . "/formValidator.php";
        $formValidator      = new formValidator("#myForm", true);
        $formValidator_code = $formValidator->render();
        $xoopsTpl->assign('formValidator_code', $formValidator_code);

        if (!file_exists(XOOPS_ROOT_PATH . "/modules/tadtools/sweet_alert.php")) {
            redirect_header("index.php", 3, _MA_NEED_TADTOOLS);
        }
        include_once XOOPS_ROOT_PATH . "/modules/tadtools/sweet_alert.php";
        $sweet_alert      = new sweet_alert();
        $sweet_alert_code = $sweet_alert->render("delete_student_func", "aboutus.php?op=delete&WebID={$this->WebID}&CateID={$DefCateID}&MemID=", 'MemID');
        $xoopsTpl->assign('sweet_alert_code', $sweet_alert_code);
    }

    //新增資料到tad_web_mems中
    public function insert()
    {
        global $xoopsDB, $xoopsUser, $MyWebs, $TadUpFiles, $isMyWeb, $MyWebs;

        if (!$isMyWeb and $MyWebs) {
            redirect_header($_SERVER['PHP_SELF'] . "?op=WebID={$MyWebs[0]}&op=edit_form", 3, _MD_TCW_AUTO_TO_HOME);
        } elseif (!$xoopsUser or empty($this->WebID) or empty($MyWebs)) {
            redirect_header("index.php", 3, _MD_TCW_NOT_OWNER);
        }

        //tad_web_link_mems:`MemID`, `WebID`, `MemNum`, `MemSort`, `MemEnable`, `top`, `left`
        //tad_web_mems:`MemID`, `MemName`, `MemNickName`, `MemSex`, `MemUnicode`, `MemBirthday`, `MemUrl`, `MemClassOrgan`, `MemExpertises`, `uid`, `MemUname`, `MemPasswd`

        $myts                   = MyTextSanitizer::getInstance();
        $_POST['MemExpertises'] = $myts->addSlashes($_POST['MemExpertises']);
        $_POST['MemUrl']        = $myts->addSlashes($_POST['MemUrl']);
        $_POST['MemClassOrgan'] = $myts->addSlashes($_POST['MemClassOrgan']);
        $_POST['MemName']       = $myts->addSlashes($_POST['MemName']);
        $_POST['MemNickName']   = $myts->addSlashes($_POST['MemNickName']);

        $CateID = intval($_POST['CateID']);

        $MemSort = $this->max_sort($CateID);

        $sql = "insert into " . $xoopsDB->prefix("tad_web_mems") . "
          (`MemName`, `MemNickName`, `MemSex`, `MemUnicode`, `MemBirthday`, `MemUrl`, `MemClassOrgan`, `MemExpertises`,  `MemUname`, `MemPasswd`)
          values( '{$_POST['MemName']}' , '{$_POST['MemNickName']}', '{$_POST['MemSex']}', '{$_POST['MemUnicode']}', '{$_POST['MemBirthday']}', '{$_POST['MemUrl']}', '{$_POST['MemClassOrgan']}', '{$_POST['MemExpertises']}' ,'{$_POST['MemUname']}', '{$_POST['MemPasswd']}')";

        $xoopsDB->queryF($sql) or web_error($sql);

        //取得最後新增資料的流水編號
        $MemID = $xoopsDB->getInsertId();

        // $subdir = isset($this->WebID) ? "/{$this->WebID}" : "";
        // $TadUpFiles->set_dir('subdir', $subdir);
        $TadUpFiles->set_col("MemID", $MemID, 1);
        $TadUpFiles->upload_file("upfile", 180, null, null, null, true);

        $sql = "insert into " . $xoopsDB->prefix("tad_web_link_mems") . "
          (`MemID`, `WebID`, `CateID`, `MemNum`, `MemSort`, `MemEnable`)
          values('{$MemID}' , '{$this->WebID}' , '{$CateID}', '{$_POST['MemNum']}' , '{$MemSort}' , '1' )";

        $xoopsDB->queryF($sql) or web_error($sql);
        check_quota($this->WebID);
        return $MemID;
    }

    //更新tad_web_mems某一筆資料
    public function update($MemID = "")
    {
        global $xoopsDB, $xoopsUser, $TadUpFiles, $isMyWeb, $MyWebs;

        if (!$isMyWeb and $MyWebs) {
            redirect_header($_SERVER['PHP_SELF'] . "?op=WebID={$MyWebs[0]}&op=edit_form", 3, _MD_TCW_AUTO_TO_HOME);
        } elseif (!$xoopsUser or empty($this->WebID) or empty($MyWebs)) {
            redirect_header("index.php", 3, _MD_TCW_NOT_OWNER);
        }
        //tad_web_link_mems:`MemID`, `WebID`, `MemNum`, `MemSort`, `MemEnable`, `top`, `left`
        //tad_web_mems:`MemID`, `MemName`, `MemNickName`, `MemSex`, `MemUnicode`, `MemBirthday`, `MemUrl`, `MemClassOrgan`, `MemExpertises`, `uid`, `MemUname`, `MemPasswd`

        $myts                   = &MyTextSanitizer::getInstance();
        $_POST['MemExpertises'] = $myts->addSlashes($_POST['MemExpertises']);
        $_POST['MemUrl']        = $myts->addSlashes($_POST['MemUrl']);
        $_POST['MemClassOrgan'] = $myts->addSlashes($_POST['MemClassOrgan']);
        $_POST['MemName']       = $myts->addSlashes($_POST['MemName']);
        $_POST['MemNickName']   = $myts->addSlashes($_POST['MemNickName']);

        $sql = "update " . $xoopsDB->prefix("tad_web_mems") . " set
           `MemName` = '{$_POST['MemName']}' ,
           `MemNickName` = '{$_POST['MemNickName']}',
           `MemSex` = '{$_POST['MemSex']}',
           `MemUnicode` = '{$_POST['MemUnicode']}',
           `MemBirthday` = '{$_POST['MemBirthday']}',
           `MemUrl` = '{$_POST['MemUrl']}',
           `MemClassOrgan` = '{$_POST['MemClassOrgan']}',
           `MemExpertises` = '{$_POST['MemExpertises']}',
           `MemUname` = '{$_POST['MemUname']}',
           `MemPasswd` = '{$_POST['MemPasswd']}'
          where MemID ='$MemID'";
        $xoopsDB->queryF($sql) or web_error($sql);

        $sql = "update " . $xoopsDB->prefix("tad_web_link_mems") . " set
           `MemNum` = '{$_POST['MemNum']}' ,
           `MemSort` = '{$_POST['MemSort']}'
          where MemID ='$MemID'";
        $xoopsDB->queryF($sql) or web_error($sql);

        // $subdir = isset($this->WebID) ? "/{$this->WebID}" : "";
        // $TadUpFiles->set_dir('subdir', $subdir);
        $TadUpFiles->set_col("MemID", $MemID);
        $TadUpFiles->upload_file("upfile", 180, null, null, null, true);
        check_quota($this->WebID);
        return $uid;
    }

    //刪除tad_web_mems某筆資料資料
    public function delete($MemID = "", $CateID = "")
    {
        global $xoopsDB, $xoopsUser, $TadUpFiles, $isMyWeb, $MyWebs;

        if (!$isMyWeb and $MyWebs) {
            redirect_header($_SERVER['PHP_SELF'] . "?op=WebID={$MyWebs[0]}&op=edit_form", 3, _MD_TCW_AUTO_TO_HOME);
        } elseif (!$xoopsUser or empty($this->WebID) or empty($MyWebs)) {
            redirect_header("index.php", 3, _MD_TCW_NOT_OWNER);
        }

        $whereCateID = $whereMemID = "";
        if (!empty($CateID)) {
            $whereCateID = "CateID ='{$CateID}'";
        } elseif (!empty($MemID)) {
            $whereMemID = "MemID ='{$MemID}'";
        }
        $sql = "delete from " . $xoopsDB->prefix("tad_web_link_mems") . " where {$whereCateID} {$whereMemID}";
        $xoopsDB->queryF($sql) or web_error($sql);

        // $subdir = isset($this->WebID) ? "/{$this->WebID}" : "";
        // $TadUpFiles->set_dir('subdir', $subdir);
        $TadUpFiles->set_col("MemID", $MemID);
        $TadUpFiles->del_files();
        check_quota($this->WebID);
    }

    //刪除所有資料
    public function delete_all()
    {
        global $xoopsDB, $TadUpFiles;

        $sql    = "select MemID from " . $xoopsDB->prefix("tad_web_link_mems") . " where WebID='{$this->WebID}'";
        $result = $xoopsDB->queryF($sql) or web_error($sql);
        while (list($MemID) = $xoopsDB->fetchRow($result)) {
            $this->delete($MemID);
        }

        check_quota($this->WebID);
    }

    //取得資料總數
    public function get_total($CateID = "")
    {
        global $xoopsDB;

        $andCateID   = !empty($CateID) ? "and CateID='{$CateID}'" : "";
        $sql         = "select count(*) from " . $xoopsDB->prefix("tad_web_link_mems") . " where WebID='{$this->WebID}' {$andCateID}";
        $result      = $xoopsDB->query($sql) or web_error($sql);
        list($count) = $xoopsDB->fetchRow($result);
        return $count;
    }

    //自動取得tad_web_mems的最新排序
    public function max_sort($CateID)
    {
        global $xoopsDB;
        $sql        = "select max(`MemSort`) from " . $xoopsDB->prefix("tad_web_link_mems") . " where CateID='$CateID'";
        $result     = $xoopsDB->query($sql) or web_error($sql);
        list($sort) = $xoopsDB->fetchRow($result);
        return ++$sort;
    }

    //匯入 excel 界面
    public function import_excel_form($DefCateID = "")
    {
        global $xoopsDB, $xoopsTpl;
        $cate = $this->web_cate->get_tad_web_cate($DefCateID);
        $xoopsTpl->assign('cate', $cate);
        $xoopsTpl->assign('edit_student', sprintf(_MD_TCW_EDIT_MEM, $this->setup['student_title']));
        $xoopsTpl->assign('setup_stud', sprintf(_MD_TCW_STUDENT_SETUP, $this->setup['student_title']));
        $xoopsTpl->assign('import_excel', sprintf(_MD_TCW_IMPORT_EXCEL, $this->setup['student_title']));
    }

    //匯入 excel
    public function import_excel($file = "", $CateID = "")
    {
        global $xoopsDB, $xoopsTpl;

        if (empty($file) or empty($file)) {
            return;
        }

        // $cate=get_tad_web_cate($CateID);

        $myts = MyTextSanitizer::getInstance();

        include_once XOOPS_ROOT_PATH . '/modules/tadtools/PHPExcel/IOFactory.php';
        $reader     = PHPExcel_IOFactory::createReader('Excel5');
        $PHPExcel   = $reader->load($file); // 檔案名稱
        $sheet      = $PHPExcel->getSheet(0); // 讀取第一個工作表(編號從 0 開始)
        $highestRow = $sheet->getHighestRow(); // 取得總列數

        $main = "";

        // 一次讀取一列
        for ($row = 1; $row <= $highestRow; $row++) {
            $all      = "";
            $continue = false;
            for ($column = 0; $column <= 5; $column++) {

                if (PHPExcel_Shared_Date::isDateTime($sheet->getCellByColumnAndRow($column, $row))) {
                    $val = PHPExcel_Shared_Date::ExcelToPHPObject($sheet->getCellByColumnAndRow($column, $row)->getValue())->format('Y-m-d');
                } else {
                    $val = $sheet->getCellByColumnAndRow($column, $row)->getCalculatedValue();
                }

                if ($column == 0 and $val == _MD_TCW_MEM_NUM) {
                    $continue = true;
                }

                if ($column <= 4 and empty($val)) {
                    $continue = true;
                }

                if ($row == 1 and $column == 1 and $val == _MD_TCW_DEMO_NAME) {
                    $continue = true;
                }

                if ($column == 3 and strlen($val) == 6) {
                    $y   = substr($val, 0, 2) + 1911;
                    $m   = substr($val, 2, 2);
                    $d   = substr($val, 4, 2);
                    $val = "{$y}-{$m}-{$d}";
                }

                if ($column == 10 and strlen($val) == 9 and substr($val, 0, 1) == 9) {
                    $val = "0{$val}";
                }

                $val = $myts->addSlashes($val);

                $all .= "
                <td>
                  <input type='text' name='c[{$row}][$column]' value='{$val}' class='form-control span12'>
                </td>
                ";
            }
            if ($continue) {
                continue;
            }

            $main .= "<tr>{$all}</tr>";
        }

        $xoopsTpl->assign('stud_chk_table', $main);
        $xoopsTpl->assign('CateID', $CateID);
    }

    //匯入資料庫
    public function import2DB($CateID = '')
    {
        global $xoopsDB;

        $i    = 0;
        $j    = 6;
        $myts = MyTextSanitizer::getInstance();
        foreach ($_POST['c'] as $row => $col) {
            $top  = 80 + $i * 90;
            $left = 65 + ($j % 6) * 90;

            $col[1] = $myts->addSlashes($col[1]);
            $col[5] = $myts->addSlashes($col[5]);
            $sex    = (trim($col[4]) == _MD_TCW_BOY) ? 1 : 0;
            $sql    = "insert into " . $xoopsDB->prefix("tad_web_mems") . " (`MemName`, `MemNickName`, `MemSex`, `MemUnicode`, `MemBirthday`, `MemUname`, `MemPasswd`) values('{$col[1]}','{$col[5]}','{$sex}','{$col[2]}','{$col[3]}','{$col[1]}','{$col[3]}')";
            $xoopsDB->queryF($sql) or web_error($sql);

            //取得最後新增資料的流水編號
            $MemID = $xoopsDB->getInsertId();

            $sql = "insert into " . $xoopsDB->prefix("tad_web_link_mems") . " (`MemID`, `WebID`,`CateID`, `MemNum`, `MemSort`, `MemEnable`, `top`, `left`) values('{$MemID}','{$this->WebID}','{$CateID}','{$col[0]}','{$col[0]}','1','{$top}','{$left}')";
            $xoopsDB->queryF($sql) or web_error($sql);

            $j++;
            if ($j % 6 == 0) {
                $i++;
            }

        }

        check_quota($this->WebID);
        redirect_header($_SERVER['PHP_SELF'] . "?WebID={$this->WebID}", 3, _MD_TCW_IMPORT_OK);
    }

    //重製位置
    public function reset_position($CateID = '')
    {

        global $xoopsDB;

        $i      = 0;
        $j      = 6;
        $myts   = MyTextSanitizer::getInstance();
        $sql    = "select * from " . $xoopsDB->prefix("tad_web_link_mems") . " where CateID='{$CateID}'";
        $result = $xoopsDB->query($sql) or web_error($sql);
        while ($all = $xoopsDB->fetchArray($result)) {
            $top  = 80 + $i * 90;
            $left = 65 + ($j % 6) * 90;
            $sql  = "update " . $xoopsDB->prefix("tad_web_link_mems") . " set `top`='{$top}', `left`='{$left}' where MemID='{$all['MemID']}' and WebID='{$this->WebID}' and CateID='{$CateID}'";

            $xoopsDB->queryF($sql) or web_error($sql);
            $j++;
            if ($j % 6 == 0) {
                $i++;
            }
        }
    }

    //儲存位置
    public function save_seat($MemID)
    {
        global $xoopsDB, $xoopsUser, $TadUpFiles;

        $sql = "update " . $xoopsDB->prefix("tad_web_link_mems") . " set
       `top` = '{$_POST['top']}' ,
       `left` = '{$_POST['left']}'
        where MemID='$MemID'";
        //die($sql);
        $xoopsDB->queryF($sql) or web_error($sql);
        return $MemID;
    }

    //取得tad_web_cate所有資料陣列
    public function get_tad_web_cate_all($table)
    {
        global $xoopsDB;
        $web_cate = new web_cate('0', "web_cate", $table);
        $cate     = $web_cate->get_tad_web_cate_arr();
        $webs     = get_web_cate_arr();
        $data_arr = "";
        if (is_array($cate)) {
            foreach ($cate as $CateID => $data) {
                $data_arr[$CateID]         = $data;
                $data_arr[$CateID]['webs'] = $webs[$CateID];
            }
        }
        return $data_arr;
    }

    //登入
    public function mem_login($MemUname = "", $MemPasswd = "")
    {
        global $xoopsDB, $xoopsUser;
        if (empty($MemUname) or empty($MemPasswd)) {
            return false;
        }

        $sql    = "select a.`MemID` , a.`MemName` , a.`MemNickName` , b.`WebID` from " . $xoopsDB->prefix("tad_web_mems") . " as a left join " . $xoopsDB->prefix("tad_web_link_mems") . " as b on a.`MemID`=b.`MemID` where a.`MemUname`='$MemUname' and a.`MemPasswd`='$MemPasswd' and b.`MemEnable`='1'";
        $result = $xoopsDB->query($sql) or web_error($sql);

        list($MemID, $MemName, $MemNickName, $WebID) = $xoopsDB->fetchRow($result);

        if (!empty($MemID)) {
            $_SESSION['LoginMemID']       = $MemID;
            $_SESSION['LoginMemName']     = $MemName;
            $_SESSION['LoginMemNickName'] = $MemNickName;
            $_SESSION['LoginWebID']       = $WebID;
        }
        return true;
    }

    //修改網站預設全銜
    public function update_web_title($WebTitle = "")
    {
        global $xoopsDB;
        $sql = "update  " . $xoopsDB->prefix("tad_web") . " set WebTitle='{$WebTitle}' where `WebID`='{$this->WebID}'";
        $xoopsDB->queryF($sql) or web_error($sql);
        mklogoPic($this->WebID);
        $TadUpFilesLogo = TadUpFilesLogo($this->WebID);
        $TadUpFilesLogo->import_one_file(XOOPS_ROOT_PATH . "/uploads/tad_web/{$this->WebID}/auto_logo/auto_logo.png", null, 1280, 150, null, 'auto_logo.png', false);
        output_head_file($this->WebID);
        return;
    }
}
