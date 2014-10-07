<?php
//  ------------------------------------------------------------------------ //
// 本模組由 tad 製作
// 製作日期：2011-04-19
// $Id:$
// ------------------------------------------------------------------------- //

//區塊主函式 (班級選單(tad_webs))
function tad_web_list($options){
  global $xoopsDB;

	$sql = "select * from ".$xoopsDB->prefix("tad_web")." where WebEnable='1' order by WebSort";
	$result = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3, mysql_error());
	$i=0;
	while($all=$xoopsDB->fetchArray($result)){
    foreach($all as $k=>$v){
      $$k=$v;
    }

    $block['webs'][$i]['title']=$WebTitle;
    $block['webs'][$i]['name']=$WebName;
    $block['webs'][$i]['url']=preg_match('/modules\/tad_web/',$_SERVER['PHP_SELF'])?$_SERVER['PHP_SELF']."?WebID={$WebID}":XOOPS_URL."/modules/tad_web/index.php?WebID={$WebID}";


    $i++;
  }
	return $block;
}

?>