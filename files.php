<?php
//  ------------------------------------------------------------------------ //
// 本模組由 tad 製作
// 製作日期：2011-04-15
// $Id:$
// ------------------------------------------------------------------------- //
/*-----------引入檔案區--------------*/
include_once "header.php";
$xoopsOption['template_main'] = "tad_web_file_tpl.html";

define("_ONLY_USER",false);
//if(!$xoopsUser and _ONLY_USER) redirect_header("index.php",3, "登入後才能使用此功能。");
include_once XOOPS_ROOT_PATH."/header.php";
/*-----------function區--------------*/

//tad_web_files編輯表單
function tad_web_files_form($fsn="",$WebID=""){
  global $xoopsDB,$xoopsUser,$xoopsModuleConfig,$isAdmin,$MyWebs,$xoopsTpl,$isMyWeb,$TadUpFiles;

  if(!$isMyWeb and $MyWebs){
    redirect_header($_SERVER['PHP_SELF']."?op=tad_web_files_form&WebID={$MyWebs[0]}",3, _MD_TCW_AUTO_TO_HOME);
  }elseif(empty($MyWebs)){
    redirect_header("index.php",3, _MD_TCW_NOT_OWNER);
  }

  //抓取預設值
  if(!empty($fsn)){
    $DBV=get_tad_web_files($fsn);
  }else{
    $DBV=array();
  }

  //預設值設定


  //設定「fsn」欄位預設值
  $fsn=(!isset($DBV['fsn']))?"":$DBV['fsn'];

  //設定「uid」欄位預設值
  $user_uid=($xoopsUser)?$xoopsUser->getVar('uid'):"";

  $uid=(!isset($DBV['uid']))?$user_uid:$DBV['uid'];

  //設定「CateID」欄位預設值
  $CateID=(!isset($DBV['CateID']))?"":$DBV['CateID'];

  //設定「file_date」欄位預設值
  $file_date=(!isset($DBV['file_date']))?date("Y-m-d H:i:s"):$DBV['file_date'];

  //設定「WebID」欄位預設值
  $WebID=(!isset($DBV['WebID']))?$WebID:$DBV['WebID'];

  $op=(empty($fsn))?"insert_tad_web_files":"update_tad_web_files";


  $xoopsTpl->assign('WebID',$WebID);
  $xoopsTpl->assign('fsn',$fsn);

  $xoopsTpl->assign('next_op',$op);
  $xoopsTpl->assign('op','tad_web_files_form');


  $TadUpFiles->set_col("fsn",$fsn);
  $upform=$TadUpFiles->upform();
  $xoopsTpl->assign('upform',$upform);
}




//新增資料到tad_web_files中
function insert_tad_web_files(){
  global $xoopsDB,$xoopsUser,$TadUpFiles;

  //取得使用者編號
  $uid=($xoopsUser)?$xoopsUser->getVar('uid'):"";

  $myts =& MyTextSanitizer::getInstance();

  $_POST['CateID']=intval($_POST['CateID']);
  $_POST['WebID']=intval($_POST['WebID']);

  $sql = "insert into ".$xoopsDB->prefix("tad_web_files")."
  (`uid` , `CateID` , `file_date`  , `WebID`)
  values('{$uid}' , '{$_POST['CateID']}' , now()  , '{$_POST['WebID']}')";

  $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3, mysql_error());

  //取得最後新增資料的流水編號
  $fsn=$xoopsDB->getInsertId();

  $TadUpFiles->set_col('fsn',$fsn);
  $TadUpFiles->upload_file('upfile',640,NULL,NULL,NULL,true);
  return $fsn;
}

//更新tad_web_files某一筆資料
function update_tad_web_files($fsn=""){
  global $xoopsDB,$xoopsUser,$TadUpFiles;


  $myts =& MyTextSanitizer::getInstance();

  $anduid=onlyMine();

  $_POST['CateID']=intval($_POST['CateID']);
  $_POST['WebID']=intval($_POST['WebID']);
  $sql = "update ".$xoopsDB->prefix("tad_web_files")." set
   `CateID` = '{$_POST['CateID']}' ,
   `file_date` = now() ,
   `WebID` = '{$_POST['WebID']}'
  where fsn='$fsn' $anduid";
  $xoopsDB->queryF($sql) or redirect_header($_SERVER['PHP_SELF'],3, mysql_error());

  $TadUpFiles->set_col('fsn',$fsn);
  $TadUpFiles->upload_file('upfile',640,NULL,NULL,NULL,true);
  return $fsn;
}



//以流水號取得某筆tad_web_files資料
function get_tad_web_files($fsn=""){
  global $xoopsDB;
  if(empty($fsn))return;
  $sql = "select * from ".$xoopsDB->prefix("tad_web_files")." where fsn='$fsn'";
  $result = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3, mysql_error());
  $data=$xoopsDB->fetchArray($result);
  return $data;
}



//刪除tad_web_files某筆資料資料
function delete_tad_web_files($fsn=""){
  global $xoopsDB,$xoopsUser,$TadUpFiles;
  $anduid=onlyMine();
  $sql = "delete from ".$xoopsDB->prefix("tad_web_files")." where fsn='$fsn' $anduid";
  $xoopsDB->queryF($sql) or redirect_header($_SERVER['PHP_SELF'],3, mysql_error());
  $TadUpFiles->set_col("fsn",$fsn);
  $TadUpFiles->del_files();
}


/*-----------執行動作判斷區----------*/
$op=(empty($_REQUEST['op']))?"":$_REQUEST['op'];
$files_sn=(empty($_REQUEST['files_sn']))?"":intval($_REQUEST['files_sn']);
$fsn=(empty($_REQUEST['fsn']))?"":intval($_REQUEST['fsn']);

common_template($interface_menu,$WebID);

switch($op){

  //新增資料
  case "insert_tad_web_files":
  $fsn=insert_tad_web_files();
  header("location: {$_SERVER['PHP_SELF']}");
  break;

  //更新資料
  case "update_tad_web_files":
  update_tad_web_files($fsn);
  header("location: {$_SERVER['PHP_SELF']}");
  break;

  //輸入表格
  case "tad_web_files_form":
  $main=tad_web_files_form($fsn,$WebID);
  break;

  //刪除資料
  case "delete_tad_web_files":
  delete_tad_web_files($fsn);
  header("location: {$_SERVER['PHP_SELF']}");
  break;

  //下載檔案
  case "tufdl":
  $files_sn=isset($_GET['files_sn'])?intval($_GET['files_sn']):"";
  $TadUpFiles->add_file_counter($files_sn);
  exit;
  break;

  //預設動作
  default:
  if(empty($fsn)){
    list_tad_web_files($WebID);
  }
  break;

}

/*-----------秀出結果區--------------*/
include_once XOOPS_ROOT_PATH.'/footer.php';
?>