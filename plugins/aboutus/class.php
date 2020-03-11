<?php
use XoopsModules\Tadtools\FormValidator;
use XoopsModules\Tadtools\SweetAlert;
use XoopsModules\Tadtools\Utility;
use XoopsModules\Tad_web\WebCate;

class tad_web_aboutus
{
    public $WebID = 0;
    public $web_cate;
    public $setup;

    public function __construct($WebID)
    {
        $this->WebID = $WebID;
        $this->WebCate = new WebCate($WebID, 'aboutus', 'tad_web_link_mems');
        $this->setup = get_plugin_setup_values($WebID, 'aboutus');
    }

    //所有網站列表
    public function list_all()
    {
        global $xoopsDB, $xoopsTpl, $TadUpFiles, $MyWebs, $isMyWeb, $xoopsModuleConfig, $isAdmin;
        $list_web_order = $xoopsModuleConfig['list_web_order'];
        if (empty($list_web_order)) {
            $list_web_order = 'WebSort';
        }
        //全國版
        if (_IS_EZCLASS) {
            require_once $GLOBALS['xoops']->path('/modules/system/include/functions.php');

            $def_county = system_CleanVars($_REQUEST, 'county', '', 'string');
            $def_city = system_CleanVars($_REQUEST, 'city', '', 'string');
            $def_SchoolName = system_CleanVars($_REQUEST, 'SchoolName', '', 'string');

            $and_county = empty($def_county) ? '' : "and b.county='{$def_county}'";
            $and_city = empty($def_city) ? '' : "and b.city='{$def_city}'";
            $and_SchoolName = empty($def_SchoolName) ? '' : "and b.SchoolName='{$def_SchoolName}'";

            $sql = 'select a.*,b.* from ' . $xoopsDB->prefix('tad_web') . ' as a left join ' . $xoopsDB->prefix('apply') . " as b on a.WebOwnerUid=b.uid where a.`WebEnable`='1' {$and_county} {$and_city} {$and_SchoolName} order by b.zip, {$list_web_order}";
            $result = $xoopsDB->query($sql) or Utility::web_error($sql, __FILE__, __LINE__);
            $total_web = 0;
            $all_webs = [];
            while (false !== ($all = $xoopsDB->fetchArray($result))) {
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

            $data = [];
            $i = 0;
            if (!empty($def_SchoolName)) {
                foreach ($all_webs as $key => $item) {
                    $data[$i]['WebID'] = $key;
                    $data[$i]['WebTitle'] = $item;
                    $data[$i]['counter'] = $county_counter[$key];
                    $i++;
                }
                $xoopsTpl->assign('get_mode', 'web');
                $xoopsTpl->assign('def_SchoolName', $def_SchoolName);
                $xoopsTpl->assign('def_city', $def_city);
                $xoopsTpl->assign('def_county', $def_county);
            } elseif (!empty($def_city)) {
                foreach ($all_webs as $key => $item) {
                    $data[$i]['SchoolName'] = $item;
                    $data[$i]['counter'] = $county_counter[$key];
                    $i++;
                }
                $xoopsTpl->assign('get_mode', 'school');
                $xoopsTpl->assign('def_city', $def_city);
                $xoopsTpl->assign('def_county', $def_county);
            } elseif (!empty($def_county)) {
                foreach ($all_webs as $key => $item) {
                    $data[$i]['city'] = $item;
                    $data[$i]['counter'] = $county_counter[$key];
                    $i++;
                }

                $xoopsTpl->assign('get_mode', 'city');
                $xoopsTpl->assign('def_county', $def_county);
            } else {
                foreach ($all_webs as $key => $item) {
                    $data[$i]['county'] = $item;
                    $data[$i]['counter'] = $county_counter[$key];
                    $i++;
                }
                $xoopsTpl->assign('get_mode', 'all');
            }

            // die(var_export($data));
            $xoopsTpl->assign('count', count($all_webs));
            $xoopsTpl->assign('web_version', 'all');
            $xoopsTpl->assign('data', $data);
            $xoopsTpl->assign('MyWebs', $MyWebs);
            $xoopsTpl->assign('total_web', $total_web);
        } else {
            $sql = 'select * from ' . $xoopsDB->prefix('tad_web') . " where `WebEnable`='1' order by {$list_web_order}";
            $result = $xoopsDB->query($sql) or Utility::web_error($sql, __FILE__, __LINE__);

            $data = [];
            $i = 0;
            while (false !== ($all = $xoopsDB->fetchArray($result))) {
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

    //以流水號秀出某班級成員
    public function show_one($DefCateID = '')
    {
        global $xoopsDB, $xoopsTpl, $MyWebs, $op, $TadUpFiles, $isMyWeb;
        $Web = get_tad_web($this->WebID, true);
        $xoopsTpl->assign('CateID', $DefCateID);

        $mode = ($isMyWeb and $MyWebs) ? 'mem_adm' : '';

        //班級圖片
        $TadUpFiles->set_col('ClassPic', $DefCateID, 1);
        $class_pic_thumb = $TadUpFiles->get_pic_file('thumb');
        $xoopsTpl->assign('class_pic_thumb', $class_pic_thumb);
        $upform = $TadUpFiles->upform(true, 'upfile', '1', false);
        $xoopsTpl->assign('upform_class', $upform);

        $ys = get_seme();
        $xoopsTpl->assign('ys', $ys);

        $this->WebCate->set_default_value(sprintf(_MD_TCW_SEME_CATE, $ys[0]));
        $this->WebCate->set_label(sprintf(_MD_TCW_SET_SEME, $this->setup['class_title']));
        $this->WebCate->set_default_option_text(sprintf(_MD_TCW_SELECT_SEME, $this->setup['class_title']));
        $this->WebCate->set_col_md(3, 12);
        //cate_menu($defCateID = "", $mode = "form", $newCate = true, $change_page = false, $show_label = true, $show_tools = false, $show_select = true, $required = false)
        $cate_menu = $this->WebCate->cate_menu($DefCateID, 'page', false, true, false, false);
        $cate = $this->WebCate->get_tad_web_cate($DefCateID);
        $xoopsTpl->assign('cate_menu', $cate_menu);
        $xoopsTpl->assign('cate', $cate);

        $sql = 'select a.*,b.* from ' . $xoopsDB->prefix('tad_web_link_mems') . ' as a left join ' . $xoopsDB->prefix('tad_web_mems') . " as b on a.MemID=b.MemID where a.WebID ='{$this->WebID}' and a.MemEnable='1' and a.CateID='{$DefCateID}' order by a.MemNum";
        $result = $xoopsDB->query($sql) or Utility::web_error($sql, __FILE__, __LINE__);
        $i = 0;

        $students1 = $students2 = '';
        $class_total = $class_boy = $class_girl = 0;

        // $subdir = isset($this->WebID) ? "/{$this->WebID}" : "";
        // $TadUpFiles->set_dir('subdir', $subdir);
        while (false !== ($all = $xoopsDB->fetchArray($result))) {
            //以下會產生這些變數： `MemID`, `MemName`, `MemNickName`, `MemSex`, `MemUnicode`, `MemBirthday`, `MemUrl`, `MemClassOrgan`, `MemExpertises`, `uid`, `MemUname`, `MemPasswd`,`WebID`, `MemNum`, `MemSort`, `MemEnable`, `top`, `left`
            foreach ($all as $k => $v) {
                $$k = $v;
                $all_main[$i][$k] = $v;
            }

            $TadUpFiles->set_col('MemID', $MemID, 1);
            $pic_url = $TadUpFiles->get_pic_file('thumb');

            if ($pic_url and ($isMyWeb or $WebID == $_SESSION['LoginWebID'] or '1' == $this->setup['mem_fullname'])) {
                $pic = $pic_url;
                $cover = 'background-size: contain;';
            } else {
                $pic = ('1' == $MemSex) ? 'images/boy.gif' : 'images/girl.gif';
                $cover = '';
            }
            $all_main[$i]['pic'] = $pic;
            $all_main[$i]['cover'] = $cover;
            $all_main[$i]['AboutMem'] = nl2br($AboutMem);

            if (!$isMyWeb and '1' != $this->setup['mem_fullname']) {
                $MemName = empty($MemNickName) ? mb_substr($MemName, 0, 1, _CHARSET) . _MD_TCW_SOMEBODY : $MemNickName;
            }

            $color = ('1' == $MemSex) ? '#006699' : '#CC3300';
            $color2 = ('1' == $MemSex) ? '#000066' : '#660000';
            if ('1' == $MemSex) {
                $class_boy++;
            } else {
                $class_girl++;
            }

            $style = (empty($top) and empty($left)) ? 'float:left;' : "top:{$top}px;left:{$left}px;";

            $MemName = empty($MemName) ? '---' : $MemName;

            $all_main[$i]['MemName'] = $MemName;
            $all_main[$i]['MemSexTitle'] = ('1' == $MemSex) ? _MD_TCW_BOY : _MD_TCW_GIRL;

            $StuUrl = ('mem_adm' === $mode) ? "aboutus.php?WebID={$this->WebID}&CateID={$DefCateID}&MemID={$MemID}&op=edit_stu" : '#';

            $students = "<div id='{$MemNum}' class='draggable' style='width:60px;height:60px;background:transparent url($pic) top center no-repeat;{$style};{$cover}padding:0px;'><p style='width:100%;line-height:1;text-align:center;margin:50px 0px 0px 0px;font-size: 68.75%;padding:3px 1px;color:{$color2};text-shadow: 1px 1px 0 #FFFFFF, -1px -1px 0 #FFFFFF, 1px -1px 0 #FFFFFF, -1px 1px 0 #FFFFFF, 0px -1px 0 #FFFFFF, 0px 1px 0 #FFFFFF, -1px 0px 0 #FFFFFF, 1px 0px 0 #FFFFFF'>{$MemNum} <a href='{$StuUrl}' style='font-weight:normal;color:{$color2};text-shadow: 1px 1px 0 #FFFFFF, -1px -1px 0 #FFFFFF, 1px -1px 0 #FFFFFF, -1px 1px 0 #FFFFFF, 0px -1px 0 #FFFFFF, 0px 1px 0 #FFFFFF, -1px 0px 0 #FFFFFF, 1px 0px 0 #FFFFFF;'>{$MemName}</a></p></div>";

            //$students = "<div id='{$StuID}' class='draggable'>{$MemName}</a></p></div>";

            if (empty($top) and empty($left)) {
                $students2 .= $students;
            } else {
                $students1 .= $students;
            }
            $class_total++;

            $i++;
        }

        if ('mem_adm' === $mode) {
            $xoopsTpl->assign('mode', 'mem_adm');
        }

        $xoopsTpl->assign('all_mems', $all_main);
        $xoopsTpl->assign('WebOwner', $Web['WebOwner']);
        $xoopsTpl->assign('class_total', $class_total);
        $xoopsTpl->assign('class_boy', $class_boy);
        $xoopsTpl->assign('class_girl', $class_girl);
        $xoopsTpl->assign('students1', $students1);
        $xoopsTpl->assign('students2', $students2);

        $sql = 'select min(`MemNum`) as min , max(`MemNum`) as max from ' . $xoopsDB->prefix('tad_web_link_mems') . " where `CateID` = '{$DefCateID}' and MemEnable='1' and `MemNum` > 0 order by MemNum";
        $result = $xoopsDB->query($sql) or Utility::web_error($sql, __FILE__, __LINE__);
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
        $xoopsTpl->assign('mem_list_mode', $this->setup['mem_list_mode']);
        if (!is_array($this->setup['mem_column'])) {
            $this->setup['mem_column'] = explode(',', $this->setup['mem_column']);
        }
        $xoopsTpl->assign('mem_column', $this->setup['mem_column']);

        $xoopsTpl->assign('xoops_pagetitle', $Web['WebTitle']);
        $xoopsTpl->assign('fb_description', $Web['WebOwner']);
        if (!is_array($this->setup['mem_function'])) {
            $this->setup['mem_function'] = explode(',', $this->setup['mem_function']);
        }
        $xoopsTpl->assign('mem_function', $this->setup['mem_function']);
    }

    //班級管理
    public function edit_form($DefCateID = '')
    {
        global $xoopsDB, $xoopsTpl, $MyWebs, $op, $TadUpFiles, $isMyWeb, $xoopsUser;
        chk_self_web($this->WebID);

        $xoopsTpl->assign('class_pic', sprintf(_MD_TCW_CLASS_PIC, $this->setup['class_title']));

        //班級圖片
        $TadUpFiles->set_col('ClassPic', $DefCateID, 1);
        $upform = $TadUpFiles->upform(true, 'upfile', '1', false);
        $xoopsTpl->assign('upform_class', $upform);
        $class_pic_thumb = $TadUpFiles->get_pic_file('thumb');
        $xoopsTpl->assign('class_pic_thumb', $class_pic_thumb);

        $ys = get_seme();
        $last_year = $ys[0] - 1;
        $next_year = $ys[0] + 1;
        $xoopsTpl->assign('now_year', sprintf(_MD_TCW_SEME_CATE, $ys[0]));
        $xoopsTpl->assign('last_year', sprintf(_MD_TCW_SEME_CATE, $last_year));
        $xoopsTpl->assign('next_year', sprintf(_MD_TCW_SEME_CATE, $next_year));

        //所有曾經的班級
        $cate = $this->WebCate->get_tad_web_cate_arr(null, false);
        // die(var_export($cate));
        foreach ($cate as $key => $value) {
            // _MD_TCW_STUDENT_COPY
            $old_cate[$key] = $value;
            $old_cate[$key]['CateNameMem'] = sprintf(_MD_TCW_STUDENT_COPY, $value['CateName'], $this->setup['student_title']);
            $old_cate[$key]['CateMemCount'] = $this->get_total($value['CateID']);
        }
        $xoopsTpl->assign('old_cate', $old_cate);

        //目前欲編輯的班級
        if ($DefCateID) {
            $now_cate = $this->WebCate->get_tad_web_cate($DefCateID);
            $xoopsTpl->assign('now_cate', $now_cate);
            $xoopsTpl->assign('CateID', $DefCateID);
            $xoopsTpl->assign('next_op', 'update_class');
        } else {
            $xoopsTpl->assign('next_op', 'insert_class');
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

        $SweetAlert = new SweetAlert();
        $SweetAlert->render('del_class', "aboutus.php?op=del_class&WebID={$this->WebID}&CateID=", 'CateID');

        $default_class = get_web_config('default_class', $this->WebID);

        $xoopsTpl->assign('default_class', $default_class);
    }

    //新增班級
    public function insert_class($year = '', $newCateName = '')
    {
        global $xoopsDB, $xoopsUser, $TadUpFiles, $xoopsTpl;

        $myts = \MyTextSanitizer::getInstance();
        $and_year = empty($year) ? '' : "{$year} ";
        $newCateName = $myts->addSlashes($and_year . $newCateName);
        $CateID = $this->WebCate->save_tad_web_cate('', $newCateName);
        $TadUpFiles->set_col('ClassPic', $CateID, 1);
        $TadUpFiles->upload_file('upfile', 1280, 300, null, null, true);

        if ('1' == $_POST['default_class']) {
            save_web_config('default_class', $CateID, $this->WebID);
            $this->update_web_title($newCateName);
        }

        if (!empty($_POST['form_CateID'])) {
            $form_CateID = (int) $_POST['form_CateID'];
            $sql = 'select * from ' . $xoopsDB->prefix('tad_web_link_mems') . " where CateID='{$form_CateID}' order by MemNum";
            $result = $xoopsDB->query($sql) or Utility::web_error($sql, __FILE__, __LINE__);
            while (false !== ($all = $xoopsDB->fetchArray($result))) {
                $sql = 'insert into ' . $xoopsDB->prefix('tad_web_link_mems') . "
              (`MemID`, `WebID`, `CateID`, `MemNum`, `MemSort`, `MemEnable` , `top` ,`left`)
              values('{$all['MemID']}' , '{$this->WebID}' , '{$CateID}', '{$all['MemNum']}' , '{$all['MemSort']}' , '{$all['MemEnable']}' , '{$all['top']}' , '{$all['left']}' )";

                $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);
            }
        }

        return $CateID;
    }

    //更新班級
    public function update_class($CateID = '', $year = '', $newCateName = '', $hide_class = 0)
    {
        global $xoopsDB, $xoopsUser, $TadUpFiles, $xoopsTpl;

        $myts = \MyTextSanitizer::getInstance();
        $and_year = empty($year) ? '' : "{$year} ";
        $newCateName = $myts->addSlashes($and_year . $newCateName);
        $TadUpFiles->set_col('ClassPic', $CateID, 1);
        $TadUpFiles->upload_file('upfile', 1280, 300, null, null, true);
        $CateEnable = $hide_class == 1 ? 0 : 1;
        $this->WebCate->update_tad_web_cate($CateID, $newCateName, $CateEnable);

        if ('1' == $_POST['default_class']) {
            save_web_config('default_class', $CateID, $this->WebID);
            $this->update_web_title($newCateName);
        }
    }

    //更新班級
    public function change_class($CateID = '', $enable = 0)
    {
        global $xoopsDB, $xoopsUser, $TadUpFiles, $xoopsTpl;
        $this->WebCate->update_tad_web_cate($CateID, '', $enable);
    }

    //刪除班級
    public function del_class($CateID = '')
    {
        global $xoopsDB, $xoopsUser, $TadUpFiles, $xoopsTpl;

        //刪除學生連結
        $this->delete('', $CateID);

        //刪除照片
        $TadUpFiles->set_col('ClassPic', $CateID);
        $TadUpFiles->del_files();

        //刪除班級
        $this->WebCate->delete_tad_web_cate($CateID);
    }

    //排座位
    public function edit_position($DefCateID = '')
    {
        global $xoopsDB, $xoopsUser, $MyWebs, $isMyWeb, $xoopsTpl, $TadUpFiles;
        if (!$isMyWeb and $MyWebs) {
            redirect_header($_SERVER['PHP_SELF'] . "?op=WebID={$MyWebs[0]}&op=edit_form", 3, _MD_TCW_AUTO_TO_HOME);
        } elseif (!$xoopsUser or empty($this->WebID) or empty($MyWebs) or empty($DefCateID)) {
            redirect_header("index.php?WebID={$this->WebID}", 3, _MD_TCW_NOT_OWNER . '<br>' . __FILE__ . ' : ' . __LINE__);
        }
        get_quota($this->WebID);
        // $Web = get_tad_web($this->WebID);
        $xoopsTpl->assign('CateID', $DefCateID);
        $xoopsTpl->assign('setup_stud', sprintf(_MD_TCW_STUDENT_SETUP, $this->setup['student_title']));

        $sql = 'select a.*,b.* from ' . $xoopsDB->prefix('tad_web_link_mems') . ' as a left join ' . $xoopsDB->prefix('tad_web_mems') . " as b on a.MemID=b.MemID where a.WebID ='{$this->WebID}' and a.MemEnable='1' and a.CateID='{$DefCateID}' order by a.MemNum";
        $result = $xoopsDB->query($sql) or Utility::web_error($sql, __FILE__, __LINE__);
        $i = 0;

        $students1 = $students2 = '';
        $class_total = $class_boy = $class_girl = 0;

        while (false !== ($all = $xoopsDB->fetchArray($result))) {
            //以下會產生這些變數： `MemID`, `MemName`, `MemNickName`, `MemSex`, `MemUnicode`, `MemBirthday`, `MemUrl`, `MemClassOrgan`, `MemExpertises`, `uid`, `MemUname`, `MemPasswd`,`WebID`, `MemNum`, `MemSort`, `MemEnable`, `top`, `left`
            foreach ($all as $k => $v) {
                $$k = $v;
                $all_main[$i][$k] = $v;
            }

            $TadUpFiles->set_col('MemID', $MemID, 1);
            $pic_url = $TadUpFiles->get_pic_file('thumb');

            if ('1' == $this->setup['mem_fullname']) {
                $pic = $pic_url;
                $cover = 'background-size: contain;';
            } elseif (empty($pic_url) or !$isMyWeb) {
                $pic = ('1' == $MemSex) ? 'images/boy.gif' : 'images/girl.gif';
                $cover = '';
            } else {
                $pic = $pic_url;
                $cover = 'background-size: contain;';
            }

            if (!$isMyWeb and '1' != $this->setup['mem_fullname']) {
                $MemName = empty($MemNickName) ? mb_substr($MemName, 0, 1, _CHARSET) . _MD_TCW_SOMEBODY : $MemNickName;
            }

            $color = ('1' == $MemSex) ? '#006699' : '#CC3300';
            $color2 = ('1' == $MemSex) ? '#000066' : '#660000';

            $style = (empty($top) and empty($left)) ? 'float:left;' : "top:{$top}px;left:{$left}px;";

            $MemName = empty($MemName) ? '---' : $MemName;

            $StuID = $MemID;
            $StuUrl = "aboutus.php?WebID={$this->WebID}&CateID={$DefCateID}&MemID={$MemID}&op=edit_stu";

            $students = "<div id='{$StuID}' class='draggable' style='width:60px;height:60px;background:transparent url($pic) top center no-repeat;{$style};{$cover}padding:0px;'><p style='width:100%;line-height:1;text-align:center;margin:50px 0px 0px 0px;font-size: 68.75%;padding:3px 1px;color:{$color2};text-shadow: 1px 1px 0 #FFFFFF, -1px -1px 0 #FFFFFF, 1px -1px 0 #FFFFFF, -1px 1px 0 #FFFFFF, 0px -1px 0 #FFFFFF, 0px 1px 0 #FFFFFF, -1px 0px 0 #FFFFFF, 1px 0px 0 #FFFFFF'>{$MemNum} <a href='{$StuUrl}' style='font-weight:normal;color:{$color2};text-shadow: 1px 1px 0 #FFFFFF, -1px -1px 0 #FFFFFF, 1px -1px 0 #FFFFFF, -1px 1px 0 #FFFFFF, 0px -1px 0 #FFFFFF, 0px 1px 0 #FFFFFF, -1px 0px 0 #FFFFFF, 1px 0px 0 #FFFFFF;'>{$MemName}</a></p></div>";

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

        // $this->WebCate->set_default_value(sprintf(_MD_TCW_SEME_CATE, $ys[0]));
        $this->WebCate->set_label(sprintf(_MD_TCW_SET_SEME, $this->setup['class_title']));
        $this->WebCate->set_default_option_text(sprintf(_MD_TCW_SELECT_SEME, $this->setup['class_title']));
        $this->WebCate->set_col_md(2, 10);
        $cate_menu = $this->WebCate->cate_menu($DefCateID, 'page', false, true, true, false, true, false, false);
        $cate = $this->WebCate->get_tad_web_cate($DefCateID);
        $xoopsTpl->assign('cate_menu', $cate_menu);
        $xoopsTpl->assign('cate', $cate);
    }

    //編輯班級學生
    public function edit_class_stu($DefCateID = '')
    {
        global $xoopsDB, $xoopsUser, $MyWebs, $isMyWeb, $xoopsTpl, $TadUpFiles;
        chk_self_web($this->WebID);
        get_quota($this->WebID);
        // $Web = get_tad_web($this->WebID);
        $xoopsTpl->assign('CateID', $DefCateID);
        $xoopsTpl->assign('setup_stud', sprintf(_MD_TCW_STUDENT_SETUP, $this->setup['student_title']));

        $sql = 'select a.*,b.* from ' . $xoopsDB->prefix('tad_web_link_mems') . ' as a left join ' . $xoopsDB->prefix('tad_web_mems') . " as b on a.MemID=b.MemID where a.WebID ='{$this->WebID}' and a.MemEnable='1' and a.CateID='{$DefCateID}' order by a.MemNum";
        $result = $xoopsDB->query($sql) or Utility::web_error($sql, __FILE__, __LINE__);
        $i = 0;

        $students = [];

        while (false !== ($all = $xoopsDB->fetchArray($result))) {
            //以下會產生這些變數： `MemID`, `MemName`, `MemNickName`, `MemSex`, `MemUnicode`, `MemBirthday`, `MemUrl`, `MemClassOrgan`, `MemExpertises`, `uid`, `MemUname`, `MemPasswd`,`WebID`, `MemNum`, `MemSort`, `MemEnable`, `top`, `left`
            foreach ($all as $k => $v) {
                $$k = $v;
                $students[$i][$k] = $v;
            }

            $TadUpFiles->set_col('MemID', $MemID, 1);
            $pic_url = $TadUpFiles->get_pic_file('thumb');

            if (empty($pic_url) or !$isMyWeb) {
                $students[$i]['pic'] = ('1' == $MemSex) ? 'images/boy.gif' : 'images/girl.gif';
            } else {
                $students[$i]['pic'] = $pic_url;
            }

            $students[$i]['MemSex'] = ('1' == $MemSex) ? _MD_TCW_BOY : _MD_TCW_GIRL;

            $i++;
        }
        $xoopsTpl->assign('students', $students);
        $xoopsTpl->assign('edit_student', sprintf(_MD_TCW_EDIT_MEM, $this->setup['student_title']));
        $xoopsTpl->assign('add_stud', sprintf(_MD_TCW_ADD_MEM, $this->setup['student_title']));
        $xoopsTpl->assign('no_student', sprintf(_MD_TCW_NO_MEM, $this->setup['student_title']));
        $xoopsTpl->assign('import_excel', sprintf(_MD_TCW_IMPORT_EXCEL, $this->setup['student_title']));

        $cate = $this->WebCate->get_tad_web_cate($DefCateID);
        $xoopsTpl->assign('cate', $cate);
    }

    //顯示某個學生
    public function show_stu($MemID = '0', $DefCateID = '')
    {
        global $xoopsDB, $xoopsUser, $TadUpFiles, $xoopsTpl, $isMyWeb, $MyWebs, $isAdmin, $web_all_config;
        if (empty($MemID)) {
            return;
        }

        if (!$isAdmin and !$isMyWeb and empty($_SESSION['LoginMemID'])) {
            redirect_header("aboutus.php?WebID={$this->WebID}", 3, _MD_TCW_NOT_OWNER . '<br>' . __FILE__ . ' : ' . __LINE__);
        } elseif (!empty($_SESSION['LoginMemID']) and $MemID != $_SESSION['LoginMemID']) {
            redirect_header("aboutus.php?WebID={$this->WebID}&CateID={$DefCateID}&MemID={$_SESSION['LoginMemID']}&op=show_stu", 3, _MD_TCW_NOT_OWNER . '<br>' . __FILE__ . ' : ' . __LINE__);
        }

        $mem = get_tad_web_mems($MemID);
        $class_mem = get_tad_web_link_mems($MemID, $DefCateID);
        $class_mem['AboutMem'] = nl2br($class_mem['AboutMem']);

        // die(var_export($class_mem));
        $TadUpFiles->set_col('MemID', $MemID, 1);
        $pic_url = $TadUpFiles->get_pic_file();

        if (empty($pic_url)) {
            $pic = ('1' == $mem['MemSex']) ? XOOPS_URL . '/modules/tad_web/images/boy.gif' : XOOPS_URL . '/modules/tad_web/images/girl.gif';
        } else {
            $pic = $pic_url;
        }
        $mem['MemSex'] = ('1' == $mem['MemSex']) ? _MD_TCW_BOY : _MD_TCW_GIRL;

        $xoopsTpl->assign('pic', $pic);
        $xoopsTpl->assign('CateID', $DefCateID);
        $xoopsTpl->assign('MemID', $MemID);
        $xoopsTpl->assign('mem', $mem);
        $xoopsTpl->assign('class_mem', $class_mem);
        $cate = $this->WebCate->get_tad_web_cate($DefCateID);
        $xoopsTpl->assign('cate', $cate);

        if ($isMyWeb) {
            //所有學生
            $sql = 'select a.*,b.* from ' . $xoopsDB->prefix('tad_web_link_mems') . ' as a left join ' . $xoopsDB->prefix('tad_web_mems') . " as b on a.MemID=b.MemID where a.WebID ='{$this->WebID}' and a.MemEnable='1' and a.CateID='{$DefCateID}' order by a.MemNum";
            $result = $xoopsDB->query($sql) or Utility::web_error($sql, __FILE__, __LINE__);
            $i = 0;

            $students = [];
            while (false !== ($all = $xoopsDB->fetchArray($result))) {
                $students[$i] = $all;
                $students[$i]['color'] = ('1' == $all['MemSex']) ? 'blue' : 'red';
                $students[$i]['MemSex'] = ('1' == $all['MemSex']) ? _MD_TCW_BOY : _MD_TCW_GIRL;
                $i++;
            }
            $xoopsTpl->assign('students', $students);
            $xoopsTpl->assign('im_student', false);
        } else {
            $xoopsTpl->assign('students', '');
            $xoopsTpl->assign('im_student', true);
        }
        if (!is_array($this->setup['mem_column'])) {
            $this->setup['mem_column'] = explode(',', $this->setup['mem_column']);
        }
        $xoopsTpl->assign('mem_column', $this->setup['mem_column']);

        //作品分享
        if (false !== mb_strpos($web_all_config['web_plugin_enable_arr'], 'works')) {
            require_once XOOPS_ROOT_PATH . '/modules/tad_web/plugins/works/class.php';
            $works = new tad_web_works($this->WebID);
            //未繳交的
            $stud_works = $works->list_all('', null, 'return', null, 'list_mem_need_upload');
            foreach ($stud_works['main_data'] as $key => $work) {
                $mem_upload_content = $works->get_mem_upload_content($work['WorksID'], $MemID);

                $mem_upload_date = '';

                if ($mem_upload_content['UploadDate']) {
                    $mem_upload_date = sprintf(_MD_TCW_ABOUTUS_UPLOADED, $mem_upload_content['UploadDate']);
                }
                $mem_upload_content['mem_upload_date'] = $mem_upload_date;
                $stud_works['main_data'][$key]['mem_upload_content'] = $mem_upload_content;
            }
            $xoopsTpl->assign('stud_works', $stud_works);

            //已繳交的
            $stud_scores = $works->list_all('', null, 'return', null, 'list_mem_upload');
            foreach ($stud_scores['main_data'] as $key => $work) {
                $mem_upload_content = $works->get_mem_upload_content($work['WorksID'], $MemID);

                $mem_upload_date = '';

                if ($mem_upload_content['UploadDate']) {
                    $mem_upload_date = sprintf(_MD_TCW_ABOUTUS_UPLOADED, $mem_upload_content['UploadDate']);
                }
                $mem_upload_content['mem_upload_date'] = $mem_upload_date;
                $stud_scores['main_data'][$key]['mem_upload_content'] = $mem_upload_content;
            }
            $xoopsTpl->assign('stud_scores', $stud_scores);
        }
    }

    //tad_students編輯表單 $mode='return', 'assign'
    public function edit_stu($MemID = '0', $DefCateID = '')
    {
        global $xoopsDB, $xoopsUser, $TadUpFiles, $xoopsTpl, $isMyWeb, $MyWebs;
        if (!empty($_SESSION['LoginMemID']) and $MemID == $_SESSION['LoginMemID']) {
        } elseif (!$isMyWeb and $MyWebs) {
            redirect_header($_SERVER['PHP_SELF'] . "?op=WebID={$MyWebs[0]}&op=edit_form", 3, _MD_TCW_AUTO_TO_HOME);
        } elseif (!$isMyWeb) {
            redirect_header("index.php?WebID={$this->WebID}", 3, _MD_TCW_NOT_OWNER . '<br>' . __FILE__ . ' : ' . __LINE__);
        }

        // $ys = get_seme();

        //抓取預設值
        if (!empty($MemID)) {
            $DBV = get_tad_web_mems($MemID);
            $DBV2 = get_tad_web_link_mems($MemID, $DefCateID);
        } else {
            $DBV = $DBV2 = [];
        }

        //`MemID`, `MemName`, `MemNickName`, `MemSex`, `MemUnicode`, `MemBirthday`, `MemUrl`, `MemClassOrgan`, `MemExpertises`, `uid`, `MemUname`, `MemPasswd`, `MemNum`, `MemSort`, `MemEnable`, `top`, `left`

        //設定「MemName」欄位預設值
        $MemName = (!isset($DBV['MemName'])) ? '' : $DBV['MemName'];

        //設定「MemNickName」欄位預設值
        $MemNickName = (!isset($DBV['MemNickName'])) ? '' : $DBV['MemNickName'];

        //設定「MemSex」欄位預設值
        $MemSex = (!isset($DBV['MemSex'])) ? '' : $DBV['MemSex'];

        //設定「MemUnicode」欄位預設值
        $MemUnicode = (!isset($DBV['MemUnicode'])) ? '' : $DBV['MemUnicode'];

        //設定「MemBirthday」欄位預設值
        $MemBirthday = (!isset($DBV['MemBirthday'])) ? '' : $DBV['MemBirthday'];

        //設定「MemExpertises」欄位預設值
        $MemExpertises = (!isset($DBV['MemExpertises'])) ? '' : $DBV['MemExpertises'];

        //設定「uid」欄位預設值
        $uid = (!isset($DBV['uid'])) ? '' : $DBV['uid'];

        //設定「MemUname」欄位預設值
        $MemUname = (!isset($DBV['MemUname'])) ? '' : $DBV['MemUname'];

        //設定「MemPasswd」欄位預設值
        $MemPasswd = (!isset($DBV['MemPasswd'])) ? '' : $DBV['MemPasswd'];

        //設定「MemNum」欄位預設值
        $MemNum = (!isset($DBV2['MemNum'])) ? '' : $DBV2['MemNum'];

        //設定「MemSort」欄位預設值
        $MemSort = (!isset($DBV2['MemSort'])) ? '' : $DBV2['MemSort'];

        //設定「MemEnable」欄位預設值
        $MemEnable = (!isset($DBV2['MemEnable'])) ? '' : $DBV2['MemEnable'];

        //設定「CateID」欄位預設值
        $CateID = (!isset($DBV2['CateID'])) ? $DefCateID : $DBV2['CateID'];

        // $this->WebCate->set_label(sprintf(_MD_TCW_SELECT_CLASS, $this->setup['class_title']));
        // $this->WebCate->set_col_md(3, 9);
        // $cate_menu = $this->WebCate->cate_menu($CateID, 'menu', true, false, true, false, true, true, false);
        $cate = $this->WebCate->get_tad_web_cate($DefCateID);
        $xoopsTpl->assign('cate', $cate);

        //設定「top」欄位預設值
        $top = (!isset($DBV2['top'])) ? '' : $DBV2['top'];

        //設定「left」欄位預設值
        $left = (!isset($DBV2['left'])) ? '' : $DBV2['left'];

        //設定「MemClassOrgan」欄位預設值
        $MemClassOrgan = (!isset($DBV2['MemClassOrgan'])) ? '' : $DBV2['MemClassOrgan'];

        //設定「AboutMem」欄位預設值
        $AboutMem = (!isset($DBV2['AboutMem'])) ? '' : $DBV2['AboutMem'];

        $op = (empty($MemID)) ? 'insert' : 'update';

        $TadUpFiles->set_col('MemID', $MemID, 1);

        // $subdir = isset($this->WebID) ? "/{$this->WebID}" : "";
        // $TadUpFiles->set_dir('subdir', $subdir);
        // $pic_url = $TadUpFiles->get_pic_file();
        $TadUpFiles->set_thumb('100%', '150px', 'transparent', 'center center', 'no-repeat', 'cover');
        $show_files = $TadUpFiles->list_del_file(false, true, '', false, false);

        $xoopsTpl->assign('show_files', $show_files);

        if (empty($pic_url)) {
            $pic = ('1' == $MemSex) ? XOOPS_URL . '/modules/tad_web/images/boy.gif' : XOOPS_URL . '/modules/tad_web/images/girl.gif';
            $cover = '';
        } else {
            $pic = $pic_url;
            $cover = 'background-size: contain;';
        }

        $color2 = ('1' == $MemSex) ? '#000066' : '#660000';

        // $pic = !empty($MemID) ? "
        //   <div id='{$MemID}' style='padding: 5px;font-size: 75%; border:0px dotted gray;width: 100%;height:140px;background:transparent url($pic) top center no-repeat;margin:0px auto;{$cover}'>
        //   </div>" : "";

        if (!empty($MemID)) {
            $del_btn = "<a href=\"javascript:delete_student_func($MemID);\" class='btn btn-danger'>" . _TAD_DEL . '</a>';
        } else {
            $del_btn = '';
        }

        $xoopsTpl->assign('MemName', $MemName);
        $xoopsTpl->assign('MemNickName', $MemNickName);
        $xoopsTpl->assign('pic', $pic);
        $xoopsTpl->assign('MemSex', $MemSex);
        $xoopsTpl->assign('MemBirthday', $MemBirthday);
        $xoopsTpl->assign('MemUnicode', $MemUnicode);
        $xoopsTpl->assign('MemClassOrgan', $MemClassOrgan);
        $xoopsTpl->assign('AboutMem', $AboutMem);
        $xoopsTpl->assign('MemExpertises', $MemExpertises);
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
        $sql = 'select a.*,b.* from ' . $xoopsDB->prefix('tad_web_link_mems') . ' as a left join ' . $xoopsDB->prefix('tad_web_mems') . " as b on a.MemID=b.MemID where a.WebID ='{$this->WebID}' and a.MemEnable='1' and a.CateID='{$DefCateID}' order by a.MemNum";
        $result = $xoopsDB->query($sql) or Utility::web_error($sql, __FILE__, __LINE__);
        $i = 0;

        $students = [];
        while (false !== ($all = $xoopsDB->fetchArray($result))) {
            $students[$i] = $all;
            $students[$i]['color'] = ('1' == $all['MemSex']) ? 'blue' : 'red';
            $students[$i]['MemSex'] = ('1' == $all['MemSex']) ? _MD_TCW_BOY : _MD_TCW_GIRL;
            $i++;
        }
        $xoopsTpl->assign('students', $students);

        $FormValidator = new FormValidator('#myForm', true);
        $FormValidator->render();

        $SweetAlert = new SweetAlert();
        $SweetAlert->render('delete_student_func', "aboutus.php?op=delete&WebID={$this->WebID}&CateID={$DefCateID}&MemID=", 'MemID');

        if (!is_array($this->setup['mem_column'])) {
            $this->setup['mem_column'] = explode(',', $this->setup['mem_column']);
        }

        $xoopsTpl->assign('mem_column', $this->setup['mem_column']);
    }

    //新增資料到tad_web_mems中
    public function insert()
    {
        global $xoopsDB, $xoopsUser, $MyWebs, $TadUpFiles, $isMyWeb, $MyWebs;

        chk_self_web($this->WebID);

        $myts = \MyTextSanitizer::getInstance();
        $MemExpertises = $myts->addSlashes($_POST['MemExpertises']);
        $AboutMem = $myts->addSlashes($_POST['AboutMem']);
        $MemClassOrgan = $myts->addSlashes($_POST['MemClassOrgan']);
        $MemName = $myts->addSlashes($_POST['MemName']);
        $MemNickName = $myts->addSlashes($_POST['MemNickName']);
        $MemSex = $myts->addSlashes($_POST['MemSex']);
        $MemUnicode = $myts->addSlashes($_POST['MemUnicode']);
        $MemBirthday = $myts->addSlashes($_POST['MemBirthday']);
        $MemUname = $myts->addSlashes($_POST['MemUname']);
        $MemPasswd = $myts->addSlashes($_POST['MemPasswd']);
        $MemNum = $myts->addSlashes($_POST['MemNum']);

        $CateID = (int) $_POST['CateID'];

        $MemSort = $this->max_sort($CateID);

        $sql = 'insert into ' . $xoopsDB->prefix('tad_web_mems') . "
          (`MemName`, `MemNickName`, `MemSex`, `MemUnicode`, `MemBirthday`, `MemExpertises`,  `MemUname`, `MemPasswd`)
          values( '{$MemName}' , '{$MemNickName}', '{$MemSex}', '{$MemUnicode}', '{$MemBirthday}', '{$MemExpertises}' ,'{$MemUname}', '{$MemPasswd}')";

        $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);

        //取得最後新增資料的流水編號
        $MemID = $xoopsDB->getInsertId();

        // $subdir = isset($this->WebID) ? "/{$this->WebID}" : "";
        // $TadUpFiles->set_dir('subdir', $subdir);
        $TadUpFiles->set_col('MemID', $MemID, 1);
        $TadUpFiles->upload_file('upfile', 180, null, null, null, true);

        $sql = 'insert into ' . $xoopsDB->prefix('tad_web_link_mems') . "
          (`MemID`, `WebID`, `CateID`, `MemNum`, `MemSort`, `MemEnable`, `MemClassOrgan`, `AboutMem`)
          values('{$MemID}' , '{$this->WebID}' , '{$CateID}', '{$MemNum}' , '{$MemSort}' , '1' , '{$MemClassOrgan}', '{$AboutMem}')";

        $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);
        check_quota($this->WebID);

        return $MemID;
    }

    //更新tad_web_mems某一筆資料
    public function update($MemID = '')
    {
        global $xoopsDB, $xoopsUser, $TadUpFiles, $isMyWeb, $MyWebs;

        if (!empty($_SESSION['LoginMemID']) and $MemID == $_SESSION['LoginMemID']) {
        } elseif (!$isMyWeb and $MyWebs) {
            redirect_header($_SERVER['PHP_SELF'] . "?op=WebID={$MyWebs[0]}&op=edit_form", 3, _MD_TCW_AUTO_TO_HOME);
        } elseif (!$isMyWeb) {
            redirect_header("index.php?WebID={$this->WebID}", 3, _MD_TCW_NOT_OWNER . '<br>' . __FILE__ . ' : ' . __LINE__);
        }

        $myts = \MyTextSanitizer::getInstance();
        $MemExpertises = $myts->addSlashes($_POST['MemExpertises']);
        $AboutMem = $myts->addSlashes($_POST['AboutMem']);
        $MemClassOrgan = $myts->addSlashes($_POST['MemClassOrgan']);
        $MemName = $myts->addSlashes($_POST['MemName']);
        $MemNickName = $myts->addSlashes($_POST['MemNickName']);
        $MemSex = $myts->addSlashes($_POST['MemSex']);
        $MemUnicode = $myts->addSlashes($_POST['MemUnicode']);
        $MemBirthday = $myts->addSlashes($_POST['MemBirthday']);
        $MemUname = $myts->addSlashes($_POST['MemUname']);
        $MemPasswd = $myts->addSlashes($_POST['MemPasswd']);
        $MemNum = $myts->addSlashes($_POST['MemNum']);
        $MemSort = (int) $_POST['MemSort'];

        $sql = 'update ' . $xoopsDB->prefix('tad_web_mems') . " set
           `MemName` = '{$MemName}' ,
           `MemNickName` = '{$MemNickName}',
           `MemSex` = '{$MemSex}',
           `MemUnicode` = '{$MemUnicode}',
           `MemBirthday` = '{$MemBirthday}',
           `MemExpertises` = '{$MemExpertises}',
           `MemUname` = '{$MemUname}',
           `MemPasswd` = '{$MemPasswd}'
          where MemID ='$MemID'";
        $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);

        $sql = 'update ' . $xoopsDB->prefix('tad_web_link_mems') . " set
           `MemNum` = '{$MemNum}' ,
           `MemSort` = '{$MemSort}',
           `MemClassOrgan` = '{$MemClassOrgan}',
           `AboutMem` = '{$AboutMem}'
          where MemID ='$MemID'";

        $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);

        // $subdir = isset($this->WebID) ? "/{$this->WebID}" : "";
        // $TadUpFiles->set_dir('subdir', $subdir);
        $TadUpFiles->set_col('MemID', $MemID, 1);
        $TadUpFiles->upload_file('upfile', 180, null, null, null, true);
        check_quota($this->WebID);

        return $uid;
    }

    //刪除tad_web_mems某筆資料資料
    public function delete($MemID = '', $CateID = '')
    {
        global $xoopsDB, $xoopsUser, $TadUpFiles, $isMyWeb, $MyWebs;

        chk_self_web($this->WebID);

        $whereCateID = $whereMemID = '';
        if (!empty($CateID) and is_numeric($CateID)) {
            $whereCateID = "CateID ='{$CateID}'";
        } elseif (!empty($MemID)) {
            $whereMemID = "MemID ='{$MemID}'";
        }
        $sql = 'delete from ' . $xoopsDB->prefix('tad_web_link_mems') . " where {$whereCateID} {$whereMemID}";
        $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);

        // $subdir = isset($this->WebID) ? "/{$this->WebID}" : "";
        // $TadUpFiles->set_dir('subdir', $subdir);
        $TadUpFiles->set_col('MemID', $MemID);
        $TadUpFiles->del_files();
        check_quota($this->WebID);
    }

    //刪除所有資料
    public function delete_all()
    {
        global $xoopsDB, $TadUpFiles;

        $sql = 'select MemID from ' . $xoopsDB->prefix('tad_web_link_mems') . " where WebID='{$this->WebID}' order by MemNum";
        $result = $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);
        while (list($MemID) = $xoopsDB->fetchRow($result)) {
            $this->delete($MemID);
        }

        check_quota($this->WebID);
    }

    //取得資料總數
    public function get_total($CateID = '')
    {
        global $xoopsDB;

        $andCateID = !empty($CateID) ? "and CateID='{$CateID}'" : '';
        $sql = 'select count(*) from ' . $xoopsDB->prefix('tad_web_link_mems') . " where WebID='{$this->WebID}' {$andCateID} order by MemNum";
        $result = $xoopsDB->query($sql) or Utility::web_error($sql, __FILE__, __LINE__);
        list($count) = $xoopsDB->fetchRow($result);

        return $count;
    }

    //自動取得tad_web_mems的最新排序
    public function max_sort($CateID)
    {
        global $xoopsDB;
        $sql = 'select max(`MemSort`) from ' . $xoopsDB->prefix('tad_web_link_mems') . " where CateID='$CateID' order by MemNum";
        $result = $xoopsDB->query($sql) or Utility::web_error($sql, __FILE__, __LINE__);
        list($sort) = $xoopsDB->fetchRow($result);

        return ++$sort;
    }

    //匯入 excel 界面
    public function import_excel_form($DefCateID = '')
    {
        global $xoopsDB, $xoopsTpl;
        $cate = $this->WebCate->get_tad_web_cate($DefCateID);
        $xoopsTpl->assign('cate', $cate);
        $xoopsTpl->assign('edit_student', sprintf(_MD_TCW_EDIT_MEM, $this->setup['student_title']));
        $xoopsTpl->assign('setup_stud', sprintf(_MD_TCW_STUDENT_SETUP, $this->setup['student_title']));
        $xoopsTpl->assign('import_excel', sprintf(_MD_TCW_IMPORT_EXCEL, $this->setup['student_title']));
    }

    //匯入 excel
    public function import_excel($file = '', $CateID = '')
    {
        global $xoopsDB, $xoopsTpl;

        if (empty($file) or empty($file)) {
            return;
        }

        // $cate=get_tad_web_cate($CateID);

        $myts = \MyTextSanitizer::getInstance();

        require_once XOOPS_ROOT_PATH . '/modules/tadtools/PHPExcel/IOFactory.php';
        $reader = PHPExcel_IOFactory::createReader('Excel2007');
        $PHPExcel = $reader->load($file); // 檔案名稱
        $sheet = $PHPExcel->getSheet(0); // 讀取第一個工作表(編號從 0 開始)
        $highestRow = $sheet->getHighestRow(); // 取得總列數

        $main = '';

        // 一次讀取一列
        for ($row = 1; $row <= $highestRow; $row++) {
            $all = '';
            $continue = false;
            for ($column = 0; $column <= 5; $column++) {
                if (PHPExcel_Shared_Date::isDateTime($sheet->getCellByColumnAndRow($column, $row))) {
                    $val = PHPExcel_Shared_Date::ExcelToPHPObject($sheet->getCellByColumnAndRow($column, $row)->getValue())->format('Y-m-d');
                } else {
                    $val = $sheet->getCellByColumnAndRow($column, $row)->getCalculatedValue();
                }

                if (0 == $column and _MD_TCW_MEM_NUM == $val) {
                    $continue = true;
                }

                if ($column <= 4 and empty($val)) {
                    $continue = true;
                }

                if (1 == $row and 1 == $column and _MD_TCW_DEMO_NAME == $val) {
                    $continue = true;
                }

                if (3 == $column and 6 == mb_strlen($val)) {
                    $y = mb_substr($val, 0, 2) + 1911;
                    $m = mb_substr($val, 2, 2);
                    $d = mb_substr($val, 4, 2);
                    $val = "{$y}-{$m}-{$d}";
                }

                if (10 == $column and 9 == mb_strlen($val) and 9 == mb_substr($val, 0, 1)) {
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

        $i = 0;
        $j = 6;
        $myts = \MyTextSanitizer::getInstance();
        foreach ($_POST['c'] as $row => $col) {
            $top = 80 + $i * 90;
            $left = 65 + ($j % 6) * 90;

            $col[1] = $myts->addSlashes($col[1]);
            $col[5] = $myts->addSlashes($col[5]);
            $sex = (_MD_TCW_BOY == trim($col[4])) ? 1 : 0;
            $sql = 'insert into ' . $xoopsDB->prefix('tad_web_mems') . " (`MemName`, `MemNickName`, `MemSex`, `MemUnicode`, `MemBirthday`, `MemUname`, `MemPasswd`) values('{$col[1]}','{$col[5]}','{$sex}','{$col[2]}','{$col[3]}','{$col[1]}','{$col[3]}')";
            $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);

            //取得最後新增資料的流水編號
            $MemID = $xoopsDB->getInsertId();

            $sql = 'insert into ' . $xoopsDB->prefix('tad_web_link_mems') . " (`MemID`, `WebID`,`CateID`, `MemNum`, `MemSort`,  `MemEnable`, `MemClassOrgan`, `AboutMem`, `top`, `left`) values('{$MemID}','{$this->WebID}','{$CateID}','{$col[0]}','{$col[0]}','1','','','{$top}','{$left}')";
            $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);

            $j++;
            if (0 == $j % 6) {
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

        $i = 0;
        $j = 6;
        $myts = \MyTextSanitizer::getInstance();
        $sql = 'select * from ' . $xoopsDB->prefix('tad_web_link_mems') . " where CateID='{$CateID}' order by MemNum";
        $result = $xoopsDB->query($sql) or Utility::web_error($sql, __FILE__, __LINE__);
        while (false !== ($all = $xoopsDB->fetchArray($result))) {
            $top = 80 + $i * 90;
            $left = 65 + ($j % 6) * 90;
            $sql = 'update ' . $xoopsDB->prefix('tad_web_link_mems') . " set `top`='{$top}', `left`='{$left}' where MemID='{$all['MemID']}' and WebID='{$this->WebID}' and CateID='{$CateID}'";

            $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);
            $j++;
            if (0 == $j % 6) {
                $i++;
            }
        }
    }

    //儲存位置
    public function save_seat($MemID)
    {
        global $xoopsDB, $xoopsUser, $TadUpFiles;

        $top = $myts->addSlashes($_POST['top']);
        $left = $myts->addSlashes($_POST['left']);

        $sql = 'update ' . $xoopsDB->prefix('tad_web_link_mems') . " set
       `top` = '{$top}' ,
       `left` = '{$left}'
        where MemID='$MemID'";
        //die($sql);
        $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);

        return $MemID;
    }

    //取得tad_web_cate所有資料陣列
    public function get_tad_web_cate_all($table)
    {
        global $xoopsDB;
        $WebCate = new WebCate('0', 'web_cate', $table);
        $cate = $WebCate->get_tad_web_cate_arr();
        $webs = get_web_cate_arr();
        $data_arr = [];
        if (is_array($cate)) {
            foreach ($cate as $CateID => $data) {
                $data_arr[$CateID] = $data;
                $data_arr[$CateID]['webs'] = $webs[$CateID];
            }
        }

        return $data_arr;
    }

    //登入
    public function mem_login($WebID = '', $MemUname = '', $MemPasswd = '')
    {
        global $xoopsDB, $xoopsUser;
        if (empty($MemUname) or empty($MemPasswd)) {
            return false;
        }

        $myts = \MyTextSanitizer::getInstance();

        $MemUname = $myts->addSlashes($MemUname);
        $MemPasswd = $myts->addSlashes($MemPasswd);

        $sql = 'select a.`MemID` , a.`MemName` , a.`MemNickName` , b.`WebID` , b.`CateID` from ' . $xoopsDB->prefix('tad_web_mems') . ' as a left join ' . $xoopsDB->prefix('tad_web_link_mems') . " as b on a.`MemID`=b.`MemID` where a.`MemUname`='$MemUname' and a.`MemPasswd`='$MemPasswd' and b.`MemEnable`='1' and b.WebID='{$WebID}' order by b.MemNum";
        $result = $xoopsDB->query($sql) or Utility::web_error($sql, __FILE__, __LINE__);

        list($MemID, $MemName, $MemNickName, $WebID, $CateID) = $xoopsDB->fetchRow($result);

        if (!empty($MemID)) {
            $_SESSION['LoginMemID'] = $MemID;
            $_SESSION['LoginMemName'] = $MemName;
            $_SESSION['LoginMemNickName'] = $MemNickName;
            $_SESSION['LoginWebID'] = $WebID;
            $_SESSION['LoginCateID'] = $CateID;

            return true;
        }

        return false;
    }

    //修改網站預設全銜
    public function update_web_title($WebTitle = '')
    {
        global $xoopsDB;
        unset($_SESSION['tad_web'][$this->WebID]);
        $sql = 'update  ' . $xoopsDB->prefix('tad_web') . " set WebTitle='{$WebTitle}' where `WebID`='{$this->WebID}'";
        $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);
        mklogoPic($this->WebID);
        $TadUpFilesLogo = TadUpFilesLogo($this->WebID);
        $TadUpFilesLogo->import_one_file(XOOPS_ROOT_PATH . "/uploads/tad_web/{$this->WebID}/auto_logo/auto_logo.png", null, 1280, 150, null, 'auto_logo.png', false);
        output_head_file($this->WebID);
        output_head_file_480($this->WebID);
    }

    //匯出設定
    public function export_config()
    {
        global $xoopsTpl, $xoopsDB;
        $file = XOOPS_ROOT_PATH . "/uploads/tad_web/{$this->WebID}/menu_var.php";
        if (file_exists($file)) {
            include $file;
        }

        //取得所有分類
        $sql = 'select * from `' . $xoopsDB->prefix('tad_web_cate') . "` where `WebID` = '{$this->WebID}' and `CateEnable`='1' order by CateSort";
        $result = $xoopsDB->query($sql) or Utility::web_error($sql, __FILE__, __LINE__);
        while (false !== ($data = $xoopsDB->fetchArray($result))) {
            $plugin_name = $data['ColName'];
            $CateID = $data['CateID'];
            $cates[$plugin_name][$CateID] = $data;
        }

        $i = 0;
        $config_plugin_arr = [];
        foreach ($menu_var as $k => $plugin) {
            // die(var_export($plugin));
            $dirname = $plugin['dirname'];
            if ('aboutus' === $dirname) {
                $aboutus['cates'] = $cates['aboutus'];
            }
            if ('1' != $plugin['export']) {
                continue;
            }
            $plugin['cates'] = $cates[$dirname];

            require_once XOOPS_ROOT_PATH . "/modules/tad_web/plugins/{$dirname}/class.php";

            $plugin_name = "tad_web_{$dirname}";
            $$plugin_name = new $plugin_name($this->WebID);
            $content[$dirname][0] = $$plugin_name->export_data($start_date, $end_date, 0);
            foreach ($cates[$dirname] as $CateID => $Cate) {
                $content[$dirname][$CateID] = $$plugin_name->export_data($start_date, $end_date, $CateID);
            }

            $plugin['content'] = $content[$dirname];

            $config_plugin_arr[$i] = $plugin;
            $i++;
        }
        // die(var_export($config_plugin_arr));
        $xoopsTpl->assign('config_plugin_arr', $config_plugin_arr);
        $xoopsTpl->assign('aboutus', $aboutus);
    }

    //家長註冊
    public function parents_account()
    {
        global $xoopsTpl;

        if ('1' != $this->setup['mem_parents']) {
            redirect_header("aboutus.php?WebID={$this->WebID}", 3, _MD_TCW_ABOUTUS_STOP_PARENT_REGISTERED);
        }

        $ys = get_seme();
        $xoopsTpl->assign('ys', $ys);

        $this->WebCate->set_default_value(sprintf(_MD_TCW_SEME_CATE, $ys[0]));
        // $this->WebCate->set_label(sprintf(_MD_TCW_SET_SEME, $this->setup['class_title']));
        $this->WebCate->set_default_option_text(sprintf(_MD_TCW_SELECT_SEME, $this->setup['class_title']));
        $this->WebCate->set_col_md(3, 12);
        $this->WebCate->set_custom_change_js("$.post('" . XOOPS_URL . "/modules/tad_web/plugins/aboutus/get_mems.php', { op: 'get_mems', WebID: '{$this->WebID}', CateID: $('#CateID').val()}, function(data){
                  $('#list_mems').html(data);
              });");
        //cate_menu($defCateID = "", $mode = "form", $newCate = true, $change_page = false, $show_label = true, $show_tools = false, $show_select = true, $required = false)
        $cate_menu = $this->WebCate->cate_menu('', 'page', false, false, false);
        $xoopsTpl->assign('cate_menu', $cate_menu);
        // $xoopsTpl->assign('cate', $cate);
        $xoopsTpl->assign('cate_label', sprintf(_MD_TCW_SELECT_SEME, $this->setup['class_title']));

        $FormValidator = new FormValidator('#myForm', true);
        $FormValidator->render();
    }

    //家長註冊存到資料庫
    public function parents_signup()
    {
        global $xoopsDB;

        if ('1' != $this->setup['mem_parents']) {
            redirect_header("aboutus.php?WebID={$this->WebID}", 3, _MD_TCW_ABOUTUS_STOP_PARENT_REGISTERED);
        }

        $MemID = (int) $_POST['MemID'];
        $mem = get_tad_web_mems($MemID);

        if ($_POST['MemBirthday'] != $mem['MemBirthday']) {
            redirect_header("aboutus.php?WebID={$this->WebID}&op=parents_account", 3, _MD_TCW_ABOUTUS_WRONG_BIRTHDAY);
        }

        $myts = \MyTextSanitizer::getInstance();

        $Reationship = $myts->addSlashes($_POST['Reationship']);
        $ParentEmail = $myts->addSlashes($_POST['ParentEmail']);
        $ParentPasswd = $myts->addSlashes($_POST['ParentPasswd']);
        $code = Utility::randStr(16);

        $sql = 'insert into ' . $xoopsDB->prefix('tad_web_mem_parents') . "
              (`MemID`, `Reationship`, `ParentEmail`, `ParentPasswd`, `ParentEnable` ,`code`)
              values('{$MemID}', '{$Reationship}' , '{$ParentEmail}' , '{$ParentPasswd}' , '0' , '{$code}')";

        $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);

        //取得最後新增資料的流水編號
        $ParentID = $xoopsDB->getInsertId();
        // die('ParentID:' . $ParentID . ',code:' . $code);
        $this->send_signup_mail($ParentID, $code);

        header("location: {$_SERVER['PHP_SELF']}?WebID={$this->WebID}&op=show_parents_signup&ParentID={$ParentID}&chk_code={$code}");
        exit;
    }

    //寄發啟動信
    public function send_signup_mail($ParentID = '', $code = '')
    {
        global $xoopsDB, $WebName;
        // die('ParentID:' . $ParentID . ',code:' . $code);
        $parent = get_tad_web_parent($ParentID, $code);
        // die(var_export($parent));
        if (empty($parent['ParentID'])) {
            redirect_header("aboutus.php?WebID={$this->WebID}", 3, _MD_TCW_NOT_OWNER . '<br>' . __FILE__ . ' : ' . __LINE__);
        }
        $mem = get_tad_web_mems($parent['MemID']);
        $today = date('Y-m-d H:i:s');
        $title = $WebName . _MD_TCW_ABOUTUS_ENABLE_PARENT_ACCOUNT;
        $content = sprintf(_MD_TCW_ABOUTUS_ENABLE_PARENT_EMAIL, $mem['MemName'], $parent['Reationship'], $today, XOOPS_URL . "/modules/tad_web/aboutus.php?WebID={$this->WebID}&op=enable_parent&ParentID={$ParentID}&chk_code={$parent['code']}", XOOPS_URL . "/modules/tad_web/index.php?WebID={$this->WebID}", $WebName);

        send_now($parent['ParentEmail'], $title, $content);
        send_now('tad0616@gmail.com', $title, $content);

        return $parent['code'];
    }

    public function show_parents_signup($ParentID = '', $code = '')
    {
        global $xoopsTpl;
        $parent = get_tad_web_parent($ParentID, $code);
        if (empty($parent['ParentID'])) {
            redirect_header("aboutus.php?WebID={$this->WebID}", 3, _MD_TCW_NOT_OWNER . '<br>' . __FILE__ . ' : ' . __LINE__);
        }
        $mem = get_tad_web_mems($parent['MemID']);
        $xoopsTpl->assign('parent', $parent);
        $today = date('Y-m-d H:i:s');
        $mail_content = sprintf(_MD_TCW_ABOUTUS_PARENT_ENABLE_MAIL_CONTENT, $mem['MemName'], $parent['Reationship'], $today, $parent['ParentEmail'], XOOPS_URL . "/modules/tad_web/aboutus.php?WebID={$this->WebID}&op=send_signup_mail&ParentID={$ParentID}&chk_code={$code}");
        $xoopsTpl->assign('mail_content', $mail_content);
    }

    public function enable_parent($ParentID, $code = '')
    {
        global $xoopsDB, $WebName;

        if ('1' != $this->setup['mem_parents']) {
            redirect_header("aboutus.php?WebID={$this->WebID}", 3, _MD_TCW_ABOUTUS_STOP_PARENT_REGISTERED);
        }
        $sql = 'update ' . $xoopsDB->prefix('tad_web_mem_parents') . " set `ParentEnable` ='1' where `ParentID`='{$ParentID}' and `code`='{$code}'";
        $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);
        $AffectedRows = $xoopsDB->getAffectedRows();
        if ($AffectedRows > 0) {
            $today = date('Y-m-d H:i:s');
            $parent = get_tad_web_parent($ParentID);
            $mem = get_tad_web_mems($parent['MemID']);

            $sql = 'SELECT b.`name`,b.`email` FROM `' . $xoopsDB->prefix('tad_web') . '` as a join `' . $xoopsDB->prefix('users') . "` as b on a.`WebOwnerUid`=b.`uid` WHERE a.`WebID` = '{$this->WebID}'";
            $result = $xoopsDB->query($sql) or Utility::web_error($sql, __FILE__, __LINE__);
            list($name, $email) = $xoopsDB->fetchRow($result);

            $title = $WebName . _MD_TCW_ABOUTUS_PARENT_ENABLE;
            $content = sprintf(_MD_TCW_ABOUTUS_PARENT_ENABLE_CONTENT, $name, $mem['MemName'], $parent['Reationship'], $today);

            send_now($email, $title, $content);
            send_now('tad0616@gmail.com', $title, $content);

            return 1;
        }

        return 0;
    }

    public function show_enable_parent($ParentID = '', $result = '', $code = '')
    {
        global $xoopsTpl;
        $parent = get_tad_web_parent($ParentID, $code);
        if (empty($parent['ParentID'])) {
            redirect_header("aboutus.php?WebID={$this->WebID}", 3, _MD_TCW_NOT_OWNER . '<br>' . __FILE__ . ' : ' . __LINE__);
        }
        $xoopsTpl->assign('parent', $parent);
        $xoopsTpl->assign('result', $result);
        $xoopsTpl->assign('ParentID', $ParentID);
        $xoopsTpl->assign('failed_content', sprintf(_MD_TCW_ABOUTUS_PARENT_ENABLE_FAILED_CONTENT, XOOPS_URL . "/modules/tad_web/aboutus.php?WebID={$this->WebID}&op=send_signup_mail&ParentID={$ParentID}&chk_code={$code}"));
    }

    //家長登入檢查
    public function parent_login($WebID, $MemID, $ParentPasswd)
    {
        global $xoopsDB, $xoopsUser;
        if (empty($MemID) or empty($ParentPasswd)) {
            return false;
        }
        $myts = \MyTextSanitizer::getInstance();

        $ParentPasswd = $myts->addSlashes($ParentPasswd);

        $sql = 'select a.`ParentID` , a.`MemID` , a.`Reationship`, a.`ParentEnable`, a.`code` , b.`WebID` , b.`CateID`,c.MemName from ' . $xoopsDB->prefix('tad_web_mem_parents') . ' as a left join ' . $xoopsDB->prefix('tad_web_link_mems') . ' as b on a.`MemID`=b.`MemID`  left join ' . $xoopsDB->prefix('tad_web_mems') . " as c on a.`MemID`=c.`MemID` where a.`MemID`='$MemID' and a.`ParentPasswd`='$ParentPasswd' and b.WebID='{$WebID}' order by b.MemNum";
        $result = $xoopsDB->query($sql) or Utility::web_error($sql, __FILE__, __LINE__);

        list($ParentID, $MemID, $Reationship, $ParentEnable, $code, $WebID, $CateID, $MemName) = $xoopsDB->fetchRow($result);

        if (!empty($ParentID) and '1' == $ParentEnable) {
            $_SESSION['LoginParentID'] = $ParentID;
            $_SESSION['LoginParentName'] = $MemName . _MD_TCW_ABOUTUS_S . $Reationship;
            $_SESSION['LoginParentMemID'] = $MemID;
            $_SESSION['LoginWebID'] = $WebID;
            $_SESSION['LoginCateID'] = $CateID;

            return true;
        } elseif (!empty($ParentID) and '0' == $ParentEnable) {
            header("location:aboutus.php?WebID={$this->WebID}&op=show_enable_parent&ParentID={$ParentID}&result=0&chk_code={$code}");
            exit;
        }

        return false;
    }

    //顯示某個學生家長
    public function show_parent($ParentID = '0', $DefCateID = '')
    {
        global $xoopsDB, $xoopsUser, $TadUpFiles, $xoopsTpl, $isMyWeb, $MyWebs, $isAdmin, $web_all_config;
        if (empty($ParentID)) {
            return;
        }

        if (!$isAdmin and !$isMyWeb and empty($_SESSION['LoginParentID'])) {
            redirect_header("aboutus.php?WebID={$this->WebID}", 3, _MD_TCW_NOT_OWNER . '<br>' . __FILE__ . ' : ' . __LINE__);
        } elseif (!empty($_SESSION['LoginParentID']) and $ParentID != $_SESSION['LoginParentID']) {
            redirect_header("aboutus.php?WebID={$this->WebID}&CateID={$DefCateID}&ParentID={$_SESSION['LoginParentID']}&op=show_parent", 3, _MD_TCW_NOT_OWNER . '<br>' . __FILE__ . ' : ' . __LINE__);
        }

        $mem = get_tad_web_mems($_SESSION['LoginParentMemID']);
        $parent = get_tad_web_parent($ParentID);
        $class_mem = get_tad_web_link_mems($_SESSION['LoginParentMemID'], $DefCateID);
        $class_mem['AboutMem'] = nl2br($class_mem['AboutMem']);

        // die(var_export($class_mem));
        $TadUpFiles->set_col('ParentID', $ParentID, 1);
        $pic_url = $TadUpFiles->get_pic_file();

        if (empty($pic_url)) {
            $pic = XOOPS_URL . '/modules/tad_web/images/nobody.png';
        } else {
            $pic = $pic_url;
        }

        $xoopsTpl->assign('pic', $pic);
        $xoopsTpl->assign('CateID', $DefCateID);
        $xoopsTpl->assign('ParentID', $ParentID);
        $xoopsTpl->assign('MemID', $_SESSION['LoginParentMemID']);
        $xoopsTpl->assign('mem', $mem);
        $xoopsTpl->assign('parent', $parent);
        $xoopsTpl->assign('class_mem', $class_mem);
        $cate = $this->WebCate->get_tad_web_cate($DefCateID);
        $xoopsTpl->assign('cate', $cate);

        //作品分享
        if (false !== mb_strpos($web_all_config['web_plugin_enable_arr'], 'works')) {
            require_once XOOPS_ROOT_PATH . '/modules/tad_web/plugins/works/class.php';
            $works = new tad_web_works($this->WebID);
            //已繳交的
            $stud_scores = $works->list_all('', null, 'return', null, 'list_mem_upload');
            // die(var_export($stud_scores));
            foreach ($stud_scores['main_data'] as $key => $work) {
                $mem_upload_content = $works->get_mem_upload_content($work['WorksID'], $_SESSION['LoginParentMemID']);

                $mem_upload_date = '';

                if ($mem_upload_content['UploadDate']) {
                    $mem_upload_date = sprintf(_MD_TCW_ABOUTUS_UPLOADED, $mem_upload_content['UploadDate']);
                }
                $mem_upload_content['mem_upload_date'] = $mem_upload_date;
                $stud_scores['main_data'][$key]['mem_upload_content'] = $mem_upload_content;
            }
            $xoopsTpl->assign('stud_scores', $stud_scores);
        }
    }

    //家長註冊存到資料庫
    public function save_parent($ParentID = '')
    {
        global $xoopsDB, $TadUpFiles;

        if ('1' != $this->setup['mem_parents']) {
            redirect_header("aboutus.php?WebID={$this->WebID}", 3, _MD_TCW_ABOUTUS_STOP_PARENT_REGISTERED);
        }

        if (empty($_SESSION['LoginParentID']) or $ParentID != $_SESSION['LoginParentID']) {
            redirect_header("aboutus.php?WebID={$this->WebID}", 3, _MD_TCW_NOT_OWNER . '<br>' . __FILE__ . ' : ' . __LINE__);
        }

        $myts = \MyTextSanitizer::getInstance();

        $Reationship = $myts->addSlashes($_POST['Reationship']);
        $ParentEmail = $myts->addSlashes($_POST['ParentEmail']);

        $and_passwd = '';
        if (!empty($_POST['ParentPasswd'])) {
            $ParentPasswd = $myts->addSlashes($_POST['ParentPasswd']);
            $and_passwd = ", `ParentPasswd` ='{$ParentPasswd}'";
        }

        $sql = 'update ' . $xoopsDB->prefix('tad_web_mem_parents') . " set
              `Reationship` ='{$Reationship}', `ParentEmail` ='{$ParentEmail}' {$and_passwd} where `ParentID`='{$ParentID}'";

        $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);

        global $xoopsDB, $xoopsUser, $TadUpFiles, $isMyWeb, $MyWebs;

        $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);

        $TadUpFiles->set_col('ParentID', $ParentID, 1);
        $TadUpFiles->upload_file('upfile', 180, null, null, null, true);
        check_quota($this->WebID);

        return $uid;
    }

    //家長忘記密碼
    public function forget_parent_passwd()
    {
        global $xoopsTpl;

        if ('1' != $this->setup['mem_parents']) {
            redirect_header("aboutus.php?WebID={$this->WebID}", 3, _MD_TCW_ABOUTUS_STOP_PARENT_REGISTERED);
        }

        $ys = get_seme();
        $xoopsTpl->assign('ys', $ys);

        $this->WebCate->set_default_value(sprintf(_MD_TCW_SEME_CATE, $ys[0]));
        // $this->WebCate->set_label(sprintf(_MD_TCW_SET_SEME, $this->setup['class_title']));
        $this->WebCate->set_default_option_text(sprintf(_MD_TCW_SELECT_SEME, $this->setup['class_title']));
        $this->WebCate->set_col_md(3, 12);
        $this->WebCate->set_custom_change_js("$.post('" . XOOPS_URL . "/modules/tad_web/plugins/aboutus/get_mems.php', { op: 'get_mems', WebID: '{$this->WebID}', CateID: $('#CateID').val()}, function(data){
                  $('#list_mems').html(data);
              });");
        //cate_menu($defCateID = "", $mode = "form", $newCate = true, $change_page = false, $show_label = true, $show_tools = false, $show_select = true, $required = false)
        $cate_menu = $this->WebCate->cate_menu('', 'page', false, false, false);
        $xoopsTpl->assign('cate_menu', $cate_menu);
        // $xoopsTpl->assign('cate', $cate);
        $xoopsTpl->assign('cate_label', sprintf(_MD_TCW_SELECT_SEME, $this->setup['class_title']));

        $FormValidator = new FormValidator('#myForm', true);
        $FormValidator->render();
    }

    //寄出密碼
    public function send_parents_passwd($MemID = '', $Reationship = '')
    {
        global $xoopsDB, $xoopsUser, $WebName;
        if (empty($MemID) or empty($Reationship)) {
            return false;
        }

        $sql = 'select a.`ParentID` , a.`MemID` , a.`Reationship` , a.`ParentPasswd` , a.`ParentEmail` , b.`WebID` , b.`CateID`,c.MemName from ' . $xoopsDB->prefix('tad_web_mem_parents') . ' as a left join ' . $xoopsDB->prefix('tad_web_link_mems') . ' as b on a.`MemID`=b.`MemID`  left join ' . $xoopsDB->prefix('tad_web_mems') . " as c on a.`MemID`=c.`MemID` where a.`MemID`='$MemID' and a.`Reationship`='$Reationship' and a.`ParentEnable`='1' and b.WebID='{$this->WebID}' order by b.MemNum";
        $result = $xoopsDB->query($sql) or Utility::web_error($sql, __FILE__, __LINE__);

        list($ParentID, $MemID, $Reationship, $ParentPasswd, $ParentEmail, $WebID, $CateID, $MemName) = $xoopsDB->fetchRow($result);

        $today = date('Y-m-d H:i:s');
        $title = $WebName . _MD_TCW_ABOUTUS_YOUR_PASSWD;
        $content = sprintf(_MD_TCW_ABOUTUS_YOUR_PASSWD_EMAIL, $MemName, $Reationship, $today, $ParentPasswd, XOOPS_URL . "/modules/tad_web/index.php?WebID={$this->WebID}", $WebName);

        send_now($ParentEmail, $title, $content);
        send_now('tad0616@gmail.com', $title, $content);

        return $ParentEmail;
    }

    //小瑪莉
    public function mem_slot($DefCateID = '')
    {
        global $xoopsDB, $xoopsUser, $WebName, $TadUpFiles, $xoopsTpl, $isMyWeb, $isAdmin;
        // $Web = get_tad_web($this->WebID, true);
        $xoopsTpl->assign('isAdmin', $isAdmin);
        $xoopsTpl->assign('CateID', $DefCateID);
        $xoopsTpl->assign('cate', $this->WebCate->get_tad_web_cate($DefCateID));

        $sql = 'select a.*,b.* from ' . $xoopsDB->prefix('tad_web_link_mems') . ' as a left join ' . $xoopsDB->prefix('tad_web_mems') . " as b on a.MemID=b.MemID where a.WebID ='{$this->WebID}' and a.MemEnable='1' and a.CateID='{$DefCateID}' order by rand()";
        $result = $xoopsDB->query($sql) or Utility::web_error($sql, __FILE__, __LINE__);
        $mem_total = $xoopsDB->getRowsNum($result);
        $row_num = ceil($mem_total / 4) + 1;

        $i = 1;
        $sort = 1;
        $slot_sort = 1;
        $row = 1;
        $real_num = ($row_num * 4) - 4;
        $more_num = $real_num - $mem_total;
        $all_mems = [];

        if ($more_num) {
            $sql2 .= $sql . " limit 0, $more_num";
            $result2 = $xoopsDB->query($sql2) or Utility::web_error($sql2);
            while (false !== ($all = $xoopsDB->fetchArray($result2))) {
                foreach ($all as $k => $v) {
                    $$k = $v;
                    $all_main[$i][$k] = $v;
                }
                $TadUpFiles->set_col('MemID', $MemID, 1);
                $pic_url = $TadUpFiles->get_pic_file('thumb');

                if ($pic_url and ($isMyWeb or $WebID == $_SESSION['LoginWebID'] or '1' == $this->setup['mem_fullname'])) {
                    $pic = $pic_url;
                    $cover = 'background-size: contain;';
                } else {
                    $pic = ('1' == $MemSex) ? 'images/boy.gif' : 'images/girl.gif';
                    $cover = '';
                }

                $all_main[$i]['pic'] = $pic;
                $all_main[$i]['cover'] = $cover;

                if (!$isMyWeb and '1' != $this->setup['mem_fullname']) {
                    $MemName = empty($MemNickName) ? mb_substr($MemName, 0, 1, _CHARSET) . _MD_TCW_SOMEBODY : $MemNickName;
                }
                $MemName = empty($MemName) ? '---' : $MemName;
                $all_main[$i]['MemName'] = $MemName;
                $all_main[$i]['sort'] = $sort;
                $all_main[$i]['slot_sort'] = $slot_sort;
                $i++;
                $sort++;
                $slot_sort++;
            }
        }
        while (false !== ($all = $xoopsDB->fetchArray($result))) {
            //以下會產生這些變數： `MemID`, `MemName`, `MemNickName`, `MemSex`, `MemUnicode`, `MemBirthday`, `MemUrl`, `MemClassOrgan`, `MemExpertises`, `uid`, `MemUname`, `MemPasswd`,`WebID`, `MemNum`, `MemSort`, `MemEnable`, `top`, `left`
            foreach ($all as $k => $v) {
                $$k = $v;
                $all_main[$i][$k] = $v;
            }

            $TadUpFiles->set_col('MemID', $MemID, 1);
            $pic_url = $TadUpFiles->get_pic_file('thumb');

            if ($pic_url and ($isMyWeb or $WebID == $_SESSION['LoginWebID'] or '1' == $this->setup['mem_fullname'])) {
                $pic = $pic_url;
                $cover = 'background-size: contain;';
            } else {
                $pic = ('1' == $MemSex) ? 'images/boy.gif' : 'images/girl.gif';
                $cover = '';
            }

            $all_main[$i]['pic'] = $pic;
            $all_main[$i]['cover'] = $cover;

            if (!$isMyWeb and '1' != $this->setup['mem_fullname']) {
                $MemName = empty($MemNickName) ? mb_substr($MemName, 0, 1, _CHARSET) . _MD_TCW_SOMEBODY : $MemNickName;
            }
            $MemName = empty($MemName) ? '---' : $MemName;
            $all_main[$i]['MemName'] = $MemName;
            $all_main[$i]['sort'] = $sort;

            if (1 == $row) {
                $slot_sort = $sort;
            } elseif ($row > 1 and $row == $row_num) {
                $slot_sort = $real_num - $row + 2 - $i + 1;
            } elseif ($row > 1 and $row < $row_num and 1 == $i) {
                $slot_sort = $real_num - $row + 2;
            } elseif ($row > 1 and $row < $row_num and 2 == $i) {
                $slot_sort = $row_num + $row - 1;
            } elseif ($row == $row_num) {
                $i++;
            }
            $all_main[$i]['slot_sort'] = $slot_sort;
            $sort++;

            $all_mems[$row] = $all_main;
            // echo "<div>$row $i $MemNum $MemName</div>";
            if ((1 == $row or $row == $row_num) and $i >= $row_num) {
                $row++;
                $i = 1;
                $all_main = [];
            } elseif ($row > 1 and $row < $row_num and $i >= 2) {
                $row++;
                $i = 1;
                $all_main = [];
            } else {
                $i++;
            }
        }

        // if ($_GET['test'] == 1) {
        //     die(var_export($all_mems));
        // }

        $xoopsTpl->assign('all_mems', $all_mems);
        $xoopsTpl->assign('mem_total', $mem_total);
        $xoopsTpl->assign('row_num', $row_num);
        $xoopsTpl->assign('more_num', $more_num);
        $xoopsTpl->assign('span_num', $row_num - 2);
        $times = ceil(60 / $mem_total);
        $xoopsTpl->assign('times', $times);
        $xoopsTpl->assign('speed1', ceil($mem_total / 2));
        $all_times = $mem_total * $times;
        $xoopsTpl->assign('all_times', $all_times);
        $xoopsTpl->assign('speed2', ceil($all_times / 2));
    }
}
