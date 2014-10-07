<?php

//區塊主函式 (相簿(tad_web_image))
function tad_web_image(){
  global $xoopsDB;


  $sql = "select a.ActionName,a.ActionID,b.WebTitle,a.WebID from ".$xoopsDB->prefix("tad_web_action")." as a join ".$xoopsDB->prefix("tad_web")." as b on a.WebID=b.WebID order by rand() limit 0,1";

  $result = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3, mysql_error());
  list($ActionName,$ActionID,$WebTitle,$WebID)=$xoopsDB->fetchRow($result);
  if(empty($ActionID))return;

  $block['WebTitle']=$WebTitle;
  $block['WebID']=$WebID;
  $block['ActionID']=$ActionID;
  $block['ActionName']=$ActionName;

  include_once XOOPS_ROOT_PATH."/modules/tadtools/TadUpFiles.php" ;
  $tad_web_action_image=new TadUpFiles("tad_web");

  $tad_web_action_image->set_dir('subdir',"/{$WebID}");
  $tad_web_action_image->set_col("ActionID",$ActionID);
  $block['photos']=$tad_web_action_image->show_files('upfile',true,NULL,false,false);  //是否縮圖,顯示模式
  return $block;
}



?>
