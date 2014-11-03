<?php
//  ------------------------------------------------------------------------ //
// 本模組由 tad 製作
// 製作日期：2011-04-15
// $Id:$
// ------------------------------------------------------------------------- //
define("TADTOOLS_PATH",XOOPS_ROOT_PATH."/modules/tadtools");
define("TADTOOLS_URL",XOOPS_URL."/modules/tadtools");

if(!file_exists(XOOPS_ROOT_PATH."/modules/tadtools/TadUpFiles.php")){
 redirect_header("http://www.tad0616.net/modules/tad_uploader/index.php?of_cat_sn=50",3, _TAD_NEED_TADTOOLS);
}

include_once XOOPS_ROOT_PATH."/modules/tadtools/TadUpFiles.php" ;
$TadUpFiles=new TadUpFiles("tad_web");
$subdir=isset($WebID)?"/{$WebID}":"";
$TadUpFiles->set_dir('subdir',$subdir);


//引入TadTools的函式庫
include_once TADTOOLS_PATH."/tad_function.php";
include_once "function_list.php";


/********************* 自訂函數 *********************/

//取得網站設定值
function get_web_config($ConfigName="",$WebID=""){
  global $xoopsDB,$WebID;
  if(empty($WebID))return;
  $sql = "select `ConfigValue` from ".$xoopsDB->prefix("tad_web_config")." where `WebID`='$WebID' and `ConfigName`='{$ConfigName}'";
  $result=$xoopsDB->queryF($sql) or redirect_header($_SERVER['PHP_SELF'],3, mysql_error());
  list($ConfigValue)=$xoopsDB->fetchRow($result);
  return $ConfigValue;
}


//共同樣板部份
function common_template($interface_menu,$WebID){
  global $xoopsTpl,$TadUpFiles;
  $xoopsTpl->assign( "toolbar" , supser_fish($interface_menu,null,"class='sf-menu'","class='current'","tad_web")) ;
  $xoopsTpl->assign( "bootstrap" , get_bootstrap()) ;
  $xoopsTpl->assign( "jquery" , get_jquery(true)) ;

  //if(empty($WebID))$WebID=$_SESSION['LoginWebID'];

  if($WebID){
    $xoopsTpl->assign('WebID',$WebID);
    $bg_name=get_web_config("web_background",$WebID);
    $background_position=get_web_config("background_position",$WebID);
    if($bg_name){
      $xoopsTpl->assign('WebBackground',$bg_name);
    }else{
      $xoopsTpl->assign('WebBackground',rand (1 , 32 ).".jpg");
    }

    if($background_position){
      $xoopsTpl->assign('WebBackground_position',$background_position);
    }else{
      $xoopsTpl->assign('WebBackground_position',"center top");
    }
  }



  if(file_exists(XOOPS_ROOT_PATH."/modules/tadtools/FooTable.php")){
    include_once XOOPS_ROOT_PATH."/modules/tadtools/FooTable.php";

    $FooTable = new FooTable();
    $FooTableJS=$FooTable->render();
  }
  $xoopsTpl->assign('FooTableJS',$FooTableJS);
  $TadUpFiles->set_col("WebOwner",$WebID,1);
  $teacher_pic=$TadUpFiles->get_pic_file('thumb');
  $xoopsTpl->assign('teacher_thumb_pic',$teacher_pic);
}


//取得回覆數量
function get_re_num($DiscussID=""){
  global $xoopsDB,$xoopsUser;
  if(empty($DiscussID)) return 0;
  $sql = "select count(*) from ".$xoopsDB->prefix("tad_web_discuss")." where ReDiscussID='$DiscussID'";
  $result = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3, mysql_error());
  list($counter)=$xoopsDB->fetchRow($result);
  return $counter;
}


function get_tad_web_mems($MemID){
  global $xoopsDB;

  $sql = "select * from ".$xoopsDB->prefix("tad_web_mems")." where MemID='{$MemID}'";
  $result = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3, mysql_error());
  $all=$xoopsDB->fetchArray($result);
  return $all;
}

function get_tad_web_link_mems($MemID){
  global $xoopsDB;

  $sql = "select * from ".$xoopsDB->prefix("tad_web_link_mems")." where MemID='{$MemID}'";
  $result = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3, mysql_error());
  $all=$xoopsDB->fetchArray($result);
  return $all;
}


//以流水號取得某筆tad_web資料
function get_tad_web($WebID=""){
  global $xoopsDB;
  if(empty($WebID))return;
  $sql = "select * from ".$xoopsDB->prefix("tad_web")." where WebID='$WebID'";
  $result = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3, mysql_error());
  $data=$xoopsDB->fetchArray($result);
  return $data;
}


//取得網頁下成員的人數
function memAmount($WebID=""){
  global $xoopsDB;

  $sql = "select count(*) from ".$xoopsDB->prefix("tad_web_link_mems")." where WebID='{$WebID}'";
  $result = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3, mysql_error());
  list($count)=$xoopsDB->fetchRow($result);
  return $count;
}


//判斷是否為管理員
function isAdmin(){
  global $xoopsUser,$xoopsModule;
  $isAdmin=false;
  if ($xoopsUser) {
    $module_id = $xoopsModule->getVar('mid');
    $isAdmin=$xoopsUser->isAdmin($module_id);
  }
  return $isAdmin;
}

//登出按鈕
function logout_button($interface_menu=array()){
  return $interface_menu;
}



//下拉選單
function supser_fish($interface_menu=array(),$interface_logo=array(),$id="id='menu'",$li="",$moduleName=""){
  if(empty($interface_menu))return;
  $options="";

  if(is_array($interface_menu)){
    foreach($interface_menu as $title => $url){
      if(is_array($url)){
        $options.="<li><a href='#'>{$title}</a>
        <ul>";
        foreach($url as $title => $url){
          $urlPath=(empty($moduleName) or substr($url,0,7)=="http://")?$url:XOOPS_URL."/modules/{$moduleName}/{$url}";
          $basename=basename($_SERVER['SCRIPT_NAME']);
          $baseurl=basename($url);
          $li_class=preg_match("/^{$basename}/",$baseurl)?$li:"";
          $options.="<li><a href='{$urlPath}'>{$title}</a></li>\n";
        }
        $options.="</ul>
        </li>\n";
      }else{
        $urlPath=(empty($moduleName) or substr($url,0,7)=="http://")?$url:XOOPS_URL."/modules/{$moduleName}/{$url}";
        $basename=basename($_SERVER['SCRIPT_NAME']);
        $baseurl=basename($url);
        $li_class=preg_match("/^{$basename}/",$baseurl)?$li:"";
        $options.="<li><a href='{$urlPath}'>{$title}</a></li>\n";
      }
    }
  }else{
    return;
  }

  if(!empty($id)){
    $jid=substr($id,0,-1);
    $jid=str_replace("id='","#",$jid);
    $jid=str_replace("class='",".",$jid);
  }

  $jquery=get_jquery();

  $main="
  $jquery
  <link rel='stylesheet' type='text/css' href='class/superfish/css/superfish.css' media='screen'>
  <style type='text/css'>
  .sf-menu a {
    color: white;
  }
  .sf-menu li {
    background: #7F9EA3;
  }
  .sf-menu ul li {
    background: #7F9EA3;
  }
  .sf-menu ul ul li {
    background: #7F9EA3;
  }
  .sf-menu li:hover,
  .sf-menu li.sfHover {
    background: #8FBBC1;
  }
  </style>
  <script type='text/javascript' src='class/superfish/js/hoverIntent.js'></script>
  <script type='text/javascript' src='class/superfish/js/superfish.js'></script>
  <script type='text/javascript'>

    $(document).ready(function(){
      $('ul{$jid}').superfish({
          delay:       500,                            // one second delay on mouseout
          animation:   {opacity:'show',height:'show'},  // fade-in and slide-down animation
          speed:       'fast',                          // faster animation speed
          disableHI:   true,
          autoArrows:  true,                           // disable generation of arrow mark-up
          dropShadows: true                            // disable drop shadows
      });
    });
  </script>
  <ul $id>
    $options
  </ul>
  <div class='clearfix'></div>
  ";
  return $main;
}


//取得目前的學年學期陣列
function get_seme(){
  global $xoopsDB;
  $y=date("Y");
  $m=date("n");
  if($m >= 8){
    $ys[0]=$y-1911;
    //$ys[1]=1;
  }elseif($m >= 2){
    $ys[0]=$y-1912;
    //$ys[1]=2;
  }else{
    $ys[0]=$y-1912;
    //$ys[1]=1;
  }
  return $ys[0];
}



//是否有管理權（或由自己發布的），判斷是否要秀出管理工具
function isMine($uid=null){
  global $xoopsUser,$isAdmin,$MyWebs,$WebID;
  if(empty($xoopsUser))return false;

  $uid=empty($uid)?$xoopsUser->uid():$uid;
  /*
  if($isAdmin){
    return true;
  }else
  */
  if(in_array($WebID,$MyWebs) and !empty($MyWebs)){
    return true;
  }
  return false;
}

//
function getAllCateName($ColName="",$WebID="",$CateID=""){
  return array();
}

//更新刪除時是否限制身份
function onlyMine(){
  global $xoopsUser,$isAdmin,$MyWebs,$WebID;
    if($isAdmin){
      return;
    }elseif(in_array($WebID,$MyWebs)){
      return;
    }
    $uid=$xoopsUser->uid();
    return "and uid='$uid'";
}





//是否為網站擁有者
function MyWebID(){
  global $xoopsUser,$xoopsDB;
  if($xoopsUser){
    $uid=$xoopsUser->uid();
    $sql="select WebID from ".$xoopsDB->prefix("tad_web")." where WebOwnerUid='$uid'";
    $result = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3, mysql_error());
    $total=$xoopsDB->getRowsNum($result);
    if(empty($total))return;
    while(list($WebID)=$xoopsDB->fetchRow($result)){
      $MyWebs[]=$WebID;
    }
  }
  return $MyWebs;
}


//取得網站資訊
function getWebInfo($WebID=null){
  global $xoopsDB;
  $WebID=intval($WebID);

  $sql = "select * from ".$xoopsDB->prefix("tad_web")." where WebID='{$WebID}'";
  $result = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3, mysql_error());

  $Web=$xoopsDB->fetchArray($result);
  return $Web;
}


//取得分類名稱
function getLevelName($WebID=""){
  global $xoopsDB;
  $sql = "select `WebTitle` from ".$xoopsDB->prefix("tad_web")." where WebID='$WebID'";
  $result = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3, mysql_error());
  list($WebTitle)=$xoopsDB->fetchRow($result);



  return $main;
}

//把模組設定項目轉為選項
function mc2arr($name="",$def="",$type='option',$other=""){
    global $xoopsModuleConfig;
    $arr=explode(",",$xoopsModuleConfig[$name]);
    foreach($arr as $item){
      if(ereg("=",$item)){
        $vv=explode("=",$item);
        $k=$vv[0];
        $v=$vv[1];
        $new_arr[$k]=$v;
        $v_as_k=false;
      }else{
        $new_arr[]=$item;
        $v_as_k=true;
      }
    }

    if($type=="checkbox"){
        $opt=arr2chk($name,$new_arr,$def,$def,$v_as_k,$other);
    }elseif($type=="radio"){
        $opt=arr2radio($name,$new_arr,$def,$v_as_k,$other);
    }elseif($type=="array"){
        $opt=$new_arr;
    }else{
        $opt=arr2opt($new_arr,$def,$v_as_k,$other);
    }
    return $opt;
}


//把陣列轉為選項
function arr2opt($arr,$def="",$v_as_k=false,$other=""){
    if(is_array($def)){
      $def_arr=$def;
    }else{
      $def_arr=array($def);
    }
    foreach($arr as $k=>$v){
      if($v_as_k)$k=$v;
      $selected=(in_array($k,$def_arr))?"selected":"";
        $main.="<option value='$k' $selected $other>$v</option>";
    }
    return $main;
}


//把陣列轉為選項
function arr2chk($name,$arr,$def="",$v_as_k=false,$other=""){
    if(is_array($def)){
    $def_arr=$def;
    }else{
    $def_arr=array($def);
    }
    $i=1;
    foreach($arr as $k=>$v){
      if($v_as_k)$k=$v;
      $checked=(in_array($k,$def_arr))?"checked":"";
        $main.="<span style='white-space:nowrap;'><input type='checkbox' name='{$name}[]' value='$k' id='{$name}_{$i}' $checked $other>
        <label for='{$name}_{$i}'>$v</label></span> ";
        $i++;
    }
    return $main;
}


//把陣列轉為單選項
function arr2radio($name,$arr,$def="",$v_as_k=false,$other=""){
    $i=1;
    foreach($arr as $k=>$v){
      if($v_as_k)$k=$v;
      $checked=($def==$k)?"checked":"";
      $main.="<span style='white-space:nowrap;'><input type='radio' name='{$name}' value='$k' id='{$name}_{$i}' $checked $other>
      <label for='{$name}_{$i}'>$v</label></span> ";
      $i++;
    }
    return $main;
}



//立即寄出
function send_now($email="",$title="",$content="",$address="",$name=""){
    global $xoopsConfig,$xoopsDB,$xoopsModuleConfig;

    $xoopsMailer =& getMailer();
    $xoopsMailer->multimailer->ContentType="text/html";
    $xoopsMailer->addHeaders("MIME-Version: 1.0");
    if(!empty($address)){
      $xoopsMailer->AddReplyTo($address, $name);
    }
    $msg=($xoopsMailer->sendMail($email,$title, $content,$headers))?true:false;
    return $msg;
}

//製作logo圖
function mklogoPic($WebID=""){
  $Class=getWebInfo($WebID);
  $WebName=$Class['WebName'];
  $WebTitle=$Class['WebTitle'];

  if(function_exists('mb_strwidth')){
    $n=mb_strwidth($WebName)/2;
  }else{
    $n=strlen($WebName)/3;
  }
  //$width=50*$n+35;
  $size=round(350/$n,0);
  if($size > 70){
    $size=70;
    $x=$size+10;
    $size2=20;
  }else{
    $x=round(350/$n,0)+10;
    $size2=17;
  }
  $y=$size+55;

  header ('Content-type: image/png');
  $im = @imagecreatetruecolor(520, 140)
        or die(_MD_TCW_MKPIC_ERROR);
  imagesavealpha($im, true);


  $white = imagecolorallocate($im, 255, 255, 255);


  //$trans_colour = imagecolorallocatealpha($im, 157,211,223, 127);
  $trans_colour = imagecolorallocatealpha($im, 255,255,255,127);
  imagefill($im, 0, 0, $trans_colour);

  $text_color = imagecolorallocate($im, 0,0,0);
  $text_color2 = imagecolorallocatealpha($im, 255,255,255,50);

  $gd=gd_info();
  if($gd['JIS-mapped Japanese Font Support']){
    $WebTitle = iconv("UTF-8", "shift_jis",$WebTitle);
    $WebName = iconv("UTF-8", "shift_jis",$WebName);
  }

  /*
  $insert = @imagecreatefrompng("images/shadow.png");
  imagecolortransparent($insert,imagecolorat($insert,0,0));
  $insert_x = imagesx($insert);
  $insert_y = imagesy($insert);
  imagecopymerge($im,$insert,0,0,0,0,$insert_x,$insert_y,100);
  */


  imagettftext($im, $size, 0 , 0, $x, $text_color,XOOPS_ROOT_PATH."/modules/tad_web/class/font.ttf",$WebName);
  imagettftextoutline(
          $im,           // image location ( you should use a variable )
          $size,            // font size
          0,             // angle in °
          0,             // x
          $x,            // y
          $text_color,
          $white,
          XOOPS_ROOT_PATH."/modules/tad_web/class/font.ttf",
          $WebName,       // pattern
          2              // outline width
  );


  imagettftext($im, $size2, 0 , 0, $y, $text_color,XOOPS_ROOT_PATH."/modules/tad_web/class/font.ttf",$WebTitle);
  imagettftextoutline(
          $im,           // image location ( you should use a variable )
          $size2,            // font size
          0,             // angle in °
          0,             // x
          $y,            // y
          $text_color,
          $white,
          XOOPS_ROOT_PATH."/modules/tad_web/class/font.ttf",
          $WebTitle,       // pattern
          1              // outline width
  );

  imagepng($im,XOOPS_ROOT_PATH."/uploads/tad_web_logos/{$WebID}.png");
  imagedestroy($im);
}

function imagettftextoutline(&$im,$size,$angle,$x,$y,&$col,&$outlinecol,$fontfile,$text,$width) {
    // For every X pixel to the left and the right
    for ($xc=$x-abs($width);$xc<=$x+abs($width);$xc++) {
        // For every Y pixel to the top and the bottom
        for ($yc=$y-abs($width);$yc<=$y+abs($width);$yc++) {
            // Draw the text in the outline color
            $text1 = imagettftext($im,$size,$angle,$xc,$yc,$outlinecol,$fontfile,$text);
        }
    }
    // Draw the main text
    $text2 = imagettftext($im,$size,$angle,$x,$y,$col,$fontfile,$text);
}


?>