<?php
include_once "header.php";

$op = (!isset($_REQUEST['op']))? "":$_REQUEST['op'];

if($op=="save_sort"){
  $sort = 1;
  foreach ($_POST['tr'] as $WebID) {
    $sql="update ".$xoopsDB->prefix("tad_web")." set `WebSort`='{$sort}' where `WebID`='{$WebID}'";
    $xoopsDB->queryF($sql) or die(_MA_TCW_UPDATE_FAIL." (".date("Y-m-d H:i:s").")");
    $sort++;
  }

  echo _MA_TCW_SAVE_SORT_OK." (".date("Y-m-d H:i:s").")";
}elseif($op=="save_teacher"){
  $WebOwnerUid=$_POST['value'];
  $WebID=$_POST['WebID'];

  //以uid取得使用者名稱
 $uid_name=XoopsUser::getUnameFromId($WebOwnerUid,1);
 $uname=XoopsUser::getUnameFromId($WebOwnerUid,0);

  $sql="update ".$xoopsDB->prefix("tad_web")." set `WebOwnerUid` ='{$WebOwnerUid}', `WebOwner`='$uid_name' where `WebID`='{$WebID}'";

  $xoopsDB->queryF($sql) or die(_MA_TCW_UPDATE_FAIL." (".date("Y-m-d H:i:s").")");
  echo $uid_name." ($uname)";
}

?>