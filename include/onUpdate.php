<?php

function xoops_module_update_tad_web(&$module, $old_version) {
    GLOBAL $xoopsDB;

    mk_dir(XOOPS_ROOT_PATH."/uploads/tad_web_logos");
    mk_dir(XOOPS_ROOT_PATH."/uploads/tad_web");
    if(!chk_chk1()) go_update1();
    if(!chk_chk2()) go_update2();
    if(!chk_chk3()) go_update3();
    if(!chk_chk4()) go_update4();
    if(!chk_chk5()) go_update5();

    return true;
}

//修改討論區計數欄位名稱
function chk_chk1(){
  global $xoopsDB;
  $sql="select count(`DiscussCounter`) from ".$xoopsDB->prefix("tad_web_discuss");
  $result=$xoopsDB->query($sql);
  if(empty($result)) return false;
  return true;
}

function go_update1(){
  global $xoopsDB;
  $sql="ALTER TABLE ".$xoopsDB->prefix("tad_web_discuss")." CHANGE `DisscussCounter` `DiscussCounter` SMALLINT( 6 ) UNSIGNED NOT NULL";
  $xoopsDB->queryF($sql) or redirect_header(XOOPS_URL."/modules/system/admin.php?fct=modulesadmin",30,  mysql_error());
  return true;
}

//修改討論區發布者uid編號
function chk_chk2(){
  global $xoopsDB;
  $sql="select count(`uid`) from ".$xoopsDB->prefix("tad_web_discuss");
  $result=$xoopsDB->query($sql);
  if(empty($result)) return false;
  return true;
}

function go_update2(){
  global $xoopsDB;
  $sql="ALTER TABLE ".$xoopsDB->prefix("tad_web_discuss")." ADD `uid` SMALLINT( 6 ) UNSIGNED NOT NULL AFTER `WebID`";
  $xoopsDB->queryF($sql) or redirect_header(XOOPS_URL."/modules/system/admin.php?fct=modulesadmin",30,  mysql_error());
  return true;
}

//修改討論區發布者編號
function chk_chk3(){
  global $xoopsDB;
  $sql="select count(`MemID`) from ".$xoopsDB->prefix("tad_web_discuss");
  $result=$xoopsDB->query($sql);
  if(empty($result)) return false;
  return true;
}

function go_update3(){
  global $xoopsDB;
  $sql="ALTER TABLE ".$xoopsDB->prefix("tad_web_discuss")." ADD `MemID` SMALLINT( 6 ) UNSIGNED NOT NULL AFTER `uid`";
  $xoopsDB->queryF($sql) or redirect_header(XOOPS_URL."/modules/system/admin.php?fct=modulesadmin",30,  mysql_error());
  return true;
}


//新增討論區發布者姓名欄位
function chk_chk4(){
  global $xoopsDB;
  $sql="select count(`MemName`) from ".$xoopsDB->prefix("tad_web_discuss");
  $result=$xoopsDB->query($sql);
  if(empty($result)) return false;
  return true;
}

function go_update4(){
  global $xoopsDB;
  $sql="ALTER TABLE ".$xoopsDB->prefix("tad_web_discuss")." ADD `MemName` varchar(255) NOT NULL default '' AFTER `MemID`";
  $xoopsDB->queryF($sql) or redirect_header(XOOPS_URL."/modules/system/admin.php?fct=modulesadmin",30,  mysql_error());
  return true;
}

//新增original_filename欄位
function chk_chk5(){
  global $xoopsDB;
  $sql="select count(`original_filename`) from ".$xoopsDB->prefix("tad_web_files_center");
  $result=$xoopsDB->query($sql);
  if(empty($result)) return false;
  return true;
}


function go_update5(){
  global $xoopsDB;
  $sql="ALTER TABLE ".$xoopsDB->prefix("tad_web_files_center")."
  ADD `original_filename` varchar(255) NOT NULL default '',
  ADD `hash_filename` varchar(255) NOT NULL default '',
  ADD `sub_dir` varchar(255) NOT NULL default ''";
  $xoopsDB->queryF($sql) or redirect_header(XOOPS_URL."/modules/system/admin.php?fct=modulesadmin",30,  mysql_error());

  $sql="update ".$xoopsDB->prefix("tad_web_files_center")." set
  `original_filename`=`description`";
  $xoopsDB->queryF($sql) or redirect_header(XOOPS_URL."/modules/system/admin.php?fct=modulesadmin",30,  mysql_error());
}

//建立目錄
function mk_dir($dir=""){
    //若無目錄名稱秀出警告訊息
    if(empty($dir))return;
    //若目錄不存在的話建立目錄
    if (!is_dir($dir)) {
        umask(000);
        //若建立失敗秀出警告訊息
        mkdir($dir, 0777);
    }
}

//拷貝目錄
function full_copy( $source="", $target=""){
  if ( is_dir( $source ) ){
    @mkdir( $target );
    $d = dir( $source );
    while ( FALSE !== ( $entry = $d->read() ) ){
      if ( $entry == '.' || $entry == '..' ){
        continue;
      }

      $Entry = $source . '/' . $entry;
      if ( is_dir( $Entry ) ) {
        full_copy( $Entry, $target . '/' . $entry );
        continue;
      }
      copy( $Entry, $target . '/' . $entry );
    }
    $d->close();
  }else{
    copy( $source, $target );
  }
}


function rename_win($oldfile,$newfile) {
   if (!rename($oldfile,$newfile)) {
      if (copy ($oldfile,$newfile)) {
         unlink($oldfile);
         return TRUE;
      }
      return FALSE;
   }
   return TRUE;
}
?>
