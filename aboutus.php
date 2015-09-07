<?php
/*-----------引入檔案區--------------*/
include_once "header.php";
if (!empty($_GET['WebID'])) {
    $xoopsOption['template_main'] = 'tad_web_aboutus_b3.html';
} else {
    $xoopsOption['template_main'] = set_bootstrap('tad_web_aboutus.html');
}
include_once XOOPS_ROOT_PATH . "/header.php";
/*-----------function區--------------*/

//以流水號秀出某筆tad_web_mems資料內容
function show_one_tad_web($WebID = "")
{
    global $xoopsDB, $xoopsTpl, $MyWebs, $op, $TadUpFiles;

    $Web = get_tad_web($WebID);

    $sql    = "select a.*,b.* from " . $xoopsDB->prefix("tad_web_link_mems") . " as a left join " . $xoopsDB->prefix("tad_web_mems") . " as b on a.MemID=b.MemID where a.WebID ='{$WebID}' and a.MemEnable='1'";
    $result = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'], 3, mysql_error());
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

        if (empty($pic_url) or !$MyWebs) {
            $pic   = ($MemSex == '1') ? "images/boy.gif" : "images/girl.gif";
            $cover = "";
        } else {
            $pic   = $pic_url;
            $cover = "background-size: cover;";
        }

        if (!$MyWebs) {
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

        $StuID    = ($_REQUEST['op'] == "tad_web_adm") ? $MemID : $MemNum;
        $students = "<div id='{$StuID}' class='draggable' style='width:60px;height:60px;background:transparent url($pic) top center no-repeat;{$style};{$cover}padding:0px;'><p style='width:100%;line-height:1;text-align:center;margin:50px 0px 0px 0px;font-size:11px;padding:3px 1px;color:{$color2};text-shadow: 1px 1px 0 #FFFFFF, -1px -1px 0 #FFFFFF, 1px -1px 0 #FFFFFF, -1px 1px 0 #FFFFFF, 0px -1px 0 #FFFFFF, 0px 1px 0 #FFFFFF, -1px 0px 0 #FFFFFF, 1px 0px 0 #FFFFFF'>{$MemNum} <a href='javascript:edit_stu({$MemID});' style='font-weight:normal;color:{$color2};text-shadow: 1px 1px 0 #FFFFFF, -1px -1px 0 #FFFFFF, 1px -1px 0 #FFFFFF, -1px 1px 0 #FFFFFF, 0px -1px 0 #FFFFFF, 0px 1px 0 #FFFFFF, -1px 0px 0 #FFFFFF, 1px 0px 0 #FFFFFF;'>{$MemName}</a></p></div>";

        //$students = "<div id='{$StuID}' class='draggable'>{$MemName}</a></p></div>";

        if (empty($top) and empty($left)) {
            $students2 .= $students;
        } else {
            $students1 .= $students;
        }
        $class_total++;

        $i++;
    }
    $TadUpFiles->set_col("WebOwner", $WebID, 1);
    $teacher_pic = $TadUpFiles->get_pic_file();

    //$teacher_pic=empty($pic_url)?"":"<div style='background:transparent url($pic_url) no-repeat center center;width:325px;height:249px;'><img src='images/photo.png'></div>";

    if ($_REQUEST['op'] == "tad_web_adm") {
        $xoopsTpl->assign('op', 'tad_web_adm');
    } else {
        $xoopsTpl->assign('op', 'show_one_tad_web');
    }

    $xoopsTpl->assign('all_mems', $all_main);
    $xoopsTpl->assign('WebOwner', $Web['WebOwner']);
    $xoopsTpl->assign('isMine', isMine());
    $xoopsTpl->assign('teacher_pic', $teacher_pic);
    $xoopsTpl->assign('class_total', $class_total);
    $xoopsTpl->assign('class_boy', $class_boy);
    $xoopsTpl->assign('class_girl', $class_girl);
    $xoopsTpl->assign('students1', $students1);
    $xoopsTpl->assign('students2', $students2);

    $sql             = "select min(`MemNum`) as min , max(`MemNum`) as max from " . $xoopsDB->prefix("tad_web_link_mems") . " where `WebID` = '$WebID' and MemEnable='1'";
    $result          = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'], 3, mysql_error());
    list($min, $max) = $xoopsDB->fetchRow($result);

    $xoopsTpl->assign('min', $min);
    $xoopsTpl->assign('max', $max);

}

//匯入 excel
function import_excel($WebID = "", $file = "")
{
    global $xoopsDB, $xoopsTpl;
    if (empty($file) or empty($file)) {
        return;
    }

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

    $xoopsTpl->assign('op', 'import_excel');
    $xoopsTpl->assign('main', $main);
}

//匯入資料庫
function import2DB($WebID = '')
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
        $xoopsDB->queryF($sql) or redirect_header($_SERVER['PHP_SELF'], 3, mysql_error());

        //取得最後新增資料的流水編號
        $MemID = $xoopsDB->getInsertId();

        $sql = "insert into " . $xoopsDB->prefix("tad_web_link_mems") . " (`MemID`, `WebID`, `MemNum`, `MemSort`, `MemEnable`, `top`, `left`) values('{$MemID}','{$WebID}','{$col[0]}','{$col[0]}','1','{$top}','{$left}')";
        $xoopsDB->queryF($sql) or redirect_header($_SERVER['PHP_SELF'], 3, mysql_error());

        $j++;
        if ($j % 6 == 0) {
            $i++;
        }

    }

    redirect_header($_SERVER['PHP_SELF'] . "?WebID={$WebID}", 3, _MD_TCW_IMPORT_OK);
}

//儲存位置
function save_seat($MemID)
{
    global $xoopsDB, $xoopsUser, $TadUpFiles;

    $sql = "update " . $xoopsDB->prefix("tad_web_link_mems") . " set
   `top` = '{$_POST['top']}' ,
   `left` = '{$_POST['left']}'
    where MemID='$MemID'";
    //die($sql);
    $xoopsDB->queryF($sql) or redirect_header($_SERVER['PHP_SELF'], 3, mysql_error());
    return $MemID;
}

//tad_students編輯表單
function tad_web_mems_form($WebID = "0", $MemID = "0")
{
    global $xoopsDB, $xoopsUser, $TadUpFiles;

    //抓取預設值
    if (!empty($MemID)) {
        $DBV  = get_tad_web_mems($MemID);
        $DBV2 = get_tad_web_link_mems($MemID);
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

    //設定「top」欄位預設值
    $top = (!isset($DBV['top'])) ? "" : $DBV['top'];

    //設定「left」欄位預設值
    $left = (!isset($DBV['left'])) ? "" : $DBV['left'];

    $op = (empty($MemID)) ? "insert_tad_web_mems" : "update_tad_web_mems";

    $TadUpFiles->set_col("MemID", $MemID, 1);
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

    if (!file_exists(TADTOOLS_PATH . "/formValidator.php")) {
        redirect_header("index.php", 3, _MA_NEED_TADTOOLS);
    }
    include_once TADTOOLS_PATH . "/formValidator.php";
    $formValidator      = new formValidator("#myForm", true);
    $formValidator_code = $formValidator->render();

    if (!empty($MemID)) {
        $del_btn = "<a href=\"javascript:delete_student_func($MemID);\" class='btn btn-danger'>" . _TAD_DEL . "</a>
    ";
    } else {
        $del_btn = "";
    }

    $row          = $_SESSION['bootstrap'] == 3 ? 'row' : 'row-fluid';
    $span         = $_SESSION['bootstrap'] == 3 ? 'col-md-' : 'span';
    $form_control = $_SESSION['bootstrap'] == 3 ? 'form-control' : 'span12';

    $main = "
      $formValidator_code
      <script type='text/javascript' src='" . TADTOOLS_URL . "/My97DatePicker/WdatePicker.js'></script>
      <script type='text/javascript'>
      function delete_student_func(MemID){
        var sure = window.confirm('" . _TAD_DEL_CONFIRM . "');
        if (!sure)  return;
        location.href=\"{$_SERVER['PHP_SELF']}?op=delete_tad_web_mems&MemID=\" + MemID;
      }
      </script>
      <form action='{$_SERVER['PHP_SELF']}' method='post' id='myForm' enctype='multipart/form-data'>
      <fieldset>
        <legend>" . _MD_TCW_STUDENT_SETUP . "</legend>

        <!--學生姓名-->
        <div class='{$row}'>
          <label class='{$span}1'>" . _MD_TCW_MEM_NAME . "</label>
          <div class='{$span}3'>
            <input type='text' name='MemName' value='{$MemName}' id='MemName' class='validate[required] {$form_control}' placeholder='" . _MD_TCW_MEM_NAME . "'>
          </div>

          <!--學生暱稱-->
          <label class='{$span}1'>" . _MD_TCW_MEM_NICKNAME . "</label>
          <div class='{$span}3'>
            <input type='text' name='MemNickName' value='{$MemNickName}' id='MemNickName' class='{$form_control}' placeholder='" . _MD_TCW_MEM_NICKNAME . "'>
          </div>

          <div class='{$span}4'>
            $pic
          </div>
        </div>


        <div class='{$row}'>
          <!--性別-->
          <label class='{$span}1'>" . _MD_TCW_MEM_SEX . "</label>
          <div class='{$span}3'>
            <select name='MemSex' class='{$form_control}'>
              <option value='1' " . chk($MemSex, '1', '1', 'selected') . ">" . _MD_TCW_BOY . "</option>
              <option value='0' " . chk($MemSex, '0', '0', 'selected') . ">" . _MD_TCW_GIRL . "</option>
            </select>
          </div>

          <!--生日-->
          <label class='{$span}1'>" . _MD_TCW_MEM_BIRTHDAY . "</label>
          <div class='{$span}3'>
            <input type='text' name='MemBirthday' value='{$MemBirthday}' id='MemBirthday' class='{$form_control}' onClick=\"WdatePicker({dateFmt:'yyyy-MM-dd' , startDate:'%y-%M-%d}'})\" placeholder='" . _MD_TCW_MEM_BIRTHDAY . "'>
          </div>

          <label class='{$span}1'>" . _MD_TCW_UPLOAD_MEM_PHOTO . "</label>
          <div class='{$span}3'>
            <input type='file' name='upfile[]' class='{$form_control}' maxlength='1' accept='gif|jpg|png|GIF|JPG|PNG'>
          </div>
        </div>


        <div class='{$row}'>

          <!--學號-->
          <label class='{$span}1'>" . _MD_TCW_MEM_UNICODE . "</label>
          <div class='{$span}3'>
            <input type='text' name='MemUnicode' value='{$MemUnicode}' id='MemUnicode' class='validate[required] {$form_control}' placeholder='" . _MD_TCW_MEM_UNICODE . "'>
          </div>

          <!--座號-->
          <label class='{$span}1'>" . _MD_TCW_MEM_NUM . "</label>
          <div class='{$span}3'>
            <input type='text' name='MemNum' value='{$MemNum}' id='MemNum' class='validate[required] {$form_control}' placeholder='" . _MD_TCW_MEM_NUM . "'>
          </div>


          <!--是否還在班上-->
          <label class='{$span}1'>" . _MD_TCW_MEM_STATUS . "</label>
          <div class='{$span}3'>
            <select name='MemEnable' id='MemEnable' class='{$form_control}'>
              <option value='1'  " . chk($MemEnable, '1', '1', 'selected') . ">" . _MD_TCW_MEM_ENABLE . "</option>
              <option value='0'  " . chk($MemEnable, '0', '', 'selected') . ">" . _MD_TCW_MEM_UNABLE . "</option>
            </select>
          </div>

        </div>


        <div class='{$row}'>

          <!--帳號-->
          <label class='{$span}1'>" . _MD_TCW_MEM_UNAME . "</label>
          <div class='{$span}3'>
            <input type='text' name='MemUname' value='{$MemUname}' id='MemUname' class='validate[required] {$form_control}' placeholder='" . _MD_TCW_MEM_UNAME . "'>
          </div>

          <!--密碼-->
          <label class='{$span}1'>" . _MD_TCW_MEM_PASSWD . "</label>
          <div class='{$span}3'>
            <input type='text' name='MemPasswd' value='{$MemPasswd}' id='MemPasswd' class='validate[required] {$form_control}' placeholder='" . _MD_TCW_MEM_PASSWD . "'>
          </div>

          <div class='{$span}4'>
            $del_btn
            <input type='hidden' name='WebID' value='{$WebID}'>
            <input type='hidden' name='MemID' value='{$MemID}'>
            <input type='hidden' name='op' value='{$op}'>
            <button type='submit' class='btn btn-primary'>" . _TAD_SAVE . "</button>
          </div>
        </div>

      </fieldset>
    </form>
    ";

    return $main;
}

//刪除tad_web_mems某筆資料資料
function delete_tad_web_mems($MemID = "")
{
    global $xoopsDB;
    $sql = "update " . $xoopsDB->prefix("tad_web_link_mems") . " set MemEnable='0' where MemID ='$MemID'";
    $xoopsDB->queryF($sql) or redirect_header($_SERVER['PHP_SELF'], 3, mysql_error());
}

//更新tad_web_mems某一筆資料
function update_tad_web_mems($MemID = "")
{
    global $xoopsDB, $xoopsUser, $TadUpFiles;
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
    $xoopsDB->queryF($sql) or redirect_header($_SERVER['PHP_SELF'], 3, mysql_error());

    $sql = "update " . $xoopsDB->prefix("tad_web_link_mems") . " set
   `MemNum` = '{$_POST['MemNum']}' ,
   `MemSort` = '{$_POST['MemSort']}'
  where MemID ='$MemID'";
    $xoopsDB->queryF($sql) or redirect_header($_SERVER['PHP_SELF'], 3, mysql_error());

    $TadUpFiles->set_col("MemID", $MemID);
    $TadUpFiles->upload_file("upfile", 180, null, null, null, true);
    return $uid;
}

//新增資料到tad_web_mems中
function insert_tad_web_mems()
{
    global $xoopsDB, $xoopsUser, $WebID, $MyWebs, $TadUpFiles;

    //tad_web_link_mems:`MemID`, `WebID`, `MemNum`, `MemSort`, `MemEnable`, `top`, `left`
    //tad_web_mems:`MemID`, `MemName`, `MemNickName`, `MemSex`, `MemUnicode`, `MemBirthday`, `MemUrl`, `MemClassOrgan`, `MemExpertises`, `uid`, `MemUname`, `MemPasswd`

    $myts                   = MyTextSanitizer::getInstance();
    $_POST['MemExpertises'] = $myts->addSlashes($_POST['MemExpertises']);
    $_POST['MemUrl']        = $myts->addSlashes($_POST['MemUrl']);
    $_POST['MemClassOrgan'] = $myts->addSlashes($_POST['MemClassOrgan']);
    $_POST['MemName']       = $myts->addSlashes($_POST['MemName']);
    $_POST['MemNickName']   = $myts->addSlashes($_POST['MemNickName']);

    $MemSort = tad_web_mems_max_sort($MyWebs);

    $sql = "insert into " . $xoopsDB->prefix("tad_web_mems") . "
  (`MemName`, `MemNickName`, `MemSex`, `MemUnicode`, `MemBirthday`, `MemUrl`, `MemClassOrgan`, `MemExpertises`,  `MemUname`, `MemPasswd`)
  values('{$_POST['MemName']}' , '{$_POST['MemNickName']}', '{$_POST['MemSex']}', '{$_POST['MemUnicode']}', '{$_POST['MemBirthday']}', '{$_POST['MemUrl']}', '{$_POST['MemClassOrgan']}', '{$_POST['MemExpertises']}' ,'{$_POST['MemUname']}', '{$_POST['MemPasswd']}')";

    $xoopsDB->queryF($sql) or redirect_header($_SERVER['PHP_SELF'], 3, mysql_error());

    //取得最後新增資料的流水編號
    $MemID = $xoopsDB->getInsertId();

    $TadUpFiles->set_col("MemID", $MemID, 1);
    $TadUpFiles->upload_file("upfile", 180, null, null, null, true);

    $sql = "insert into " . $xoopsDB->prefix("tad_web_link_mems") . "
  (`MemID`, `WebID`, `MemNum`, `MemSort`, `MemEnable`)
  values('{$MemID}' , '{$WebID}' , '{$_POST['MemNum']}' , '{$MemSort}' , '1' )";

    $xoopsDB->queryF($sql) or redirect_header($_SERVER['PHP_SELF'], 3, mysql_error());

    return $MemID;
}

//自動取得tad_web_mems的最新排序
function tad_web_mems_max_sort($WebID)
{
    global $xoopsDB;
    $sql        = "select max(`MemSort`) from " . $xoopsDB->prefix("tad_web_link_mems") . " where WebID='$WebID'";
    $result     = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'], 3, mysql_error());
    list($sort) = $xoopsDB->fetchRow($result);
    return ++$sort;
}

/*-----------執行動作判斷區----------*/
include_once $GLOBALS['xoops']->path('/modules/system/include/functions.php');
$op    = system_CleanVars($_REQUEST, 'op', '', 'string');
$MemID = system_CleanVars($_REQUEST, 'MemID', 0, 'int');

common_template($WebID);

switch ($op) {

    case "import_excel":
        import_excel($WebID, $_FILES['importfile']['tmp_name']);
        break;

    case "import2DB":
        import2DB($WebID);
        break;

    //儲存座位
    case "save_seat":
        save_seat($MemID);
        header("location: {$_SERVER['PHP_SELF']}?WebID={$WebID}");
        exit;
        break;

    //輸入表格
    case "tad_web_mems_form":
        $main = tad_web_mems_form($WebID, $MemID);
        die($main);
        break;

    //刪除資料
    case "delete_tad_web_mems":
        delete_tad_web_mems($MemID);
        header("location: {$_SERVER['PHP_SELF']}?WebID={$WebID}");
        exit;
        break;

    //更新團員資料
    case "update_tad_web_mems":
        update_tad_web_mems($MemID);
        header("location: {$_SERVER['PHP_SELF']}?WebID={$WebID}&op=tad_web_adm");
        exit;
        break;

    //新增資料
    case "insert_tad_web_mems":
        insert_tad_web_mems();
        header("location: {$_SERVER['PHP_SELF']}?WebID={$WebID}");
        exit;
        break;

    //預設動作
    default:
        if (empty($WebID)) {
            list_all_tad_webs();
        } else {
            show_one_tad_web($WebID);
        }
        break;

}

/*-----------秀出結果區--------------*/
include_once XOOPS_ROOT_PATH . '/footer.php';
