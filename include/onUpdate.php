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
    go_update6();

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



function go_update6(){
  global $xoopsDB;

  $updir=XOOPS_ROOT_PATH."/uploads/tad_web";
  $os=(PATH_SEPARATOR==':')?"linux":"win";


  //修正子目錄，並找出實體檔案沒有真的在子目錄下的
  $sql="select `files_sn`,`col_name`,`col_sn`,`kind`,`file_name`,`sub_dir` from ".$xoopsDB->prefix("tad_web_files_center")." where `sub_dir` like '//%'";
  $result=$xoopsDB->queryF($sql) or die($sql);
  while(list($files_sn,$col_name,$col_sn,$kind,$file_name,$sub_dir)=$xoopsDB->fetchRow($result)){
    $sub_dir=str_replace("//", "/", $sub_dir);
    $typedir=$kind=='img'?"image":"file";

    $sql="update  ".$xoopsDB->prefix("tad_web_files_center")." set `sub_dir`='{$sub_dir}'  where `files_sn`='{$files_sn}'";
    $xoopsDB->queryF($sql) or die($sql);

    if(!file_exists("{$updir}{$sub_dir}/{$typedir}/{$file_name}")){
      mk_dir("{$updir}{$sub_dir}");
      mk_dir("{$updir}{$sub_dir}/{$typedir}");

      $from="{$updir}/{$typedir}/{$file_name}";
      $to="{$updir}{$sub_dir}/{$typedir}/{$file_name}";

      if($os=="win" and _CHARSET=="UTF-8"){
        $from=iconv(_CHARSET,"Big5",$from);
        $to=iconv(_CHARSET,"Big5",$to);
      }elseif($os=="linux" and _CHARSET=="Big5"){
        $from=iconv(_CHARSET,"UTF-8",$from);
        $to=iconv(_CHARSET,"UTF-8",$to);
      }

      rename($from,$to);
      if($typedir=="image"){
        mk_dir("{$updir}{$sub_dir}");
        mk_dir("{$updir}{$sub_dir}/{$typedir}");
        mk_dir("{$updir}{$sub_dir}/{$typedir}/.thumbs");
        $from="{$updir}/{$typedir}/.thumbs/{$file_name}";
        $to="{$updir}{$sub_dir}/{$typedir}/.thumbs/{$file_name}";

        if($os=="win" and _CHARSET=="UTF-8"){
          $from=iconv(_CHARSET,"Big5",$from);
          $to=iconv(_CHARSET,"Big5",$to);
        }elseif($os=="linux" and _CHARSET=="Big5"){
          $from=iconv(_CHARSET,"UTF-8",$from);
          $to=iconv(_CHARSET,"UTF-8",$to);
        }

        rename($from,$to);
      }
    }
  }

  //找出沒有放到子目錄的
  $sql="select `files_sn`,`col_name`,`col_sn`,`kind`,`file_name`,`sub_dir` from ".$xoopsDB->prefix("tad_web_files_center")."";
  $result=$xoopsDB->queryF($sql) or die($sql);
  while(list($files_sn,$col_name,$col_sn,$kind,$file_name,$sub_dir)=$xoopsDB->fetchRow($result)){

    $typedir=$kind=='img'?"image":"file";
    $WebID=intval(substr($sub_dir, 1));
    if(empty($WebID)){
      if($col_name=="WebOwner" or $col_name=="WebLogo"){
        $WebID=$col_sn;
      }elseif($col_name=="MemID"){
        $sql="select `WebID` from ".$xoopsDB->prefix("tad_web_link_mems")." where `MemID` = '{$col_sn}'";
        $result2=$xoopsDB->queryF($sql) or die($sql);
        list($WebID)=$xoopsDB->fetchRow($result2);
      }elseif($col_name=="ActionID"){
        $sql="select `WebID` from ".$xoopsDB->prefix("tad_web_action")." where `ActionID` = '{$col_sn}'";
        $result2=$xoopsDB->queryF($sql) or die($sql);
        list($WebID)=$xoopsDB->fetchRow($result2);
      }elseif($col_name=="fsn"){
        $sql="select `WebID` from ".$xoopsDB->prefix("tad_web_files")." where `fsn` = '{$col_sn}'";
        $result2=$xoopsDB->queryF($sql) or die($sql);
        list($WebID)=$xoopsDB->fetchRow($result2);
      }elseif($col_name=="NewsID"){
        $sql="select `WebID` from ".$xoopsDB->prefix("tad_web_news")." where `NewsID` = '{$col_sn}'";
        $result2=$xoopsDB->queryF($sql) or die($sql);
        list($WebID)=$xoopsDB->fetchRow($result2);
      }
    }

    $sql="update ".$xoopsDB->prefix("tad_web_files_center")." set `sub_dir`='/{$WebID}'  where `files_sn`='{$files_sn}'";
    $xoopsDB->queryF($sql) or die($sql);

    mk_dir("{$updir}/{$WebID}");
    mk_dir("{$updir}/{$WebID}/{$typedir}");
    if($typedir=="image"){
      mk_dir("{$updir}/{$WebID}/{$typedir}");
      mk_dir("{$updir}/{$WebID}/{$typedir}/.thumbs");
    }

    $from="{$updir}/{$typedir}/{$file_name}";
    $to="{$updir}/{$WebID}/{$typedir}/{$file_name}";

    if($os=="win" and _CHARSET=="UTF-8"){
      $from=iconv(_CHARSET,"Big5",$from);
      $to=iconv(_CHARSET,"Big5",$to);
    }elseif($os=="linux" and _CHARSET=="Big5"){
      $from=iconv(_CHARSET,"UTF-8",$from);
      $to=iconv(_CHARSET,"UTF-8",$to);
    }

    rename($from,$to);
    if($typedir=="image"){
      mk_dir("{$updir}/{$WebID}");
      mk_dir("{$updir}/{$WebID}/{$typedir}");
      mk_dir("{$updir}/{$WebID}/{$typedir}/.thumbs");

      $from="{$updir}/{$typedir}/.thumbs/{$file_name}";
      $to="{$updir}/{$WebID}/{$typedir}/.thumbs/{$file_name}";

      if($os=="win" and _CHARSET=="UTF-8"){
        $from=iconv(_CHARSET,"Big5",$from);
        $to=iconv(_CHARSET,"Big5",$to);
      }elseif($os=="linux" and _CHARSET=="Big5"){
        $from=iconv(_CHARSET,"UTF-8",$from);
        $to=iconv(_CHARSET,"UTF-8",$to);
      }
      rename($from,$to);

    }

  }

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
