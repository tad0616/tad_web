<?php
//  ------------------------------------------------------------------------ //
// 本模組由 tad 製作
// 製作日期：2011-04-15
// $Id:$
// ------------------------------------------------------------------------- //
include_once "../../mainfile.php";
include_once "function.php";

//判斷是否對該模組有管理權限
$isAdmin=false;
if ($xoopsUser) {
  $module_id = $xoopsModule->getVar('mid');
  $isAdmin=$xoopsUser->isAdmin($module_id);
}

  //目前觀看的班級
if(!empty($_REQUEST['WebID'])){
  $WebID=intval($_REQUEST['WebID']);
  $_SESSION['WebID']=$WebID;
}else{
  $WebID=$_SESSION['WebID'];
}


if($WebID){
  $Web=getWebInfo($WebID);
  $WebName=$Web['WebTitle'];
  $WebTitle=$Web['WebTitle'];
  $WebOwner=$Web['WebOwner'];
}else{
  $Web="";
  $WebName="";
  $WebTitle="";
  $WebOwner="";
}


$interface_menu[_MD_TCW_HOME]="home.php";

if($xoopsModuleConfig['web_mode']=="class"){
  $_MD_TCW_ABOUTUS=empty($WebID)?_MD_TCW_ALL_CLASS:_MD_TCW_MY_CLASS;
}else{
  $_MD_TCW_ABOUTUS=empty($WebID)?_MD_TCW_ALL_WEB:_MD_TCW_ABOUTUS;
}

$hide_function=array();
if(!empty($WebID)){
	$ConfigValue=get_web_config("hide_function",$WebID);
	$hide_function=explode(';',$ConfigValue);
}

$interface_menu[$_MD_TCW_ABOUTUS]="aboutus.php?WebID={$WebID}";
if(!in_array('news',$hide_function))$interface_menu[_MD_TCW_NEWS]="news.php?WebID={$WebID}";
if($xoopsModuleConfig['web_mode']=="class"){
  if(!in_array('homework',$hide_function))$interface_menu[_MD_TCW_HOMEWORK]="homework.php?WebID={$WebID}";
}
if(!in_array('files',$hide_function))$interface_menu[_MD_TCW_FILES]="files.php?WebID={$WebID}";
if(!in_array('action',$hide_function))$interface_menu[_MD_TCW_ACTION]="action.php?WebID={$WebID}";
if(!in_array('video',$hide_function))$interface_menu[_MD_TCW_VIDEO]="video.php?WebID={$WebID}";
if(!in_array('link',$hide_function))$interface_menu[_MD_TCW_LINK]="link.php?WebID={$WebID}";
if(!in_array('discuss',$hide_function))$interface_menu[_MD_TCW_DISCUSS]="discuss.php?WebID={$WebID}";
$interface_menu[_MD_TCW_CALENDA]="calenda.php?WebID={$WebID}";

if($isAdmin){
  $interface_menu[_MD_TCW_ADMIN]="admin/index.php";
}

//模組前台選單
$module_menu=supser_fish($interface_menu,null,"class='sf-menu'","class='current'","tad_web");

//圖案
$web_logo=$upfile->get_pic_file("WebLogo",$WebID,"1","images");

//我的班級ID（陣列）
$MyWebs=MyWebID();
//目前瀏覽的是否是我的班級？
$isMyWeb=in_array($WebID,$MyWebs);


?>