<?php
//  ------------------------------------------------------------------------ //
// 本模組由 tad 製作
// 製作日期：2011-04-19
// $Id:$
// ------------------------------------------------------------------------- //

//區塊主函式 (班級選單(tad_web_menu))
function tad_web_menu($options){
  global $xoopsUser,$xoopsDB,$MyWebs;

  if($xoopsUser){
    $uid=$xoopsUser->uid();
  }else{
    return;
  }

  $sql="select * from ".$xoopsDB->prefix("tad_web")." where WebOwnerUid='$uid' and WebEnable='1'";
	$result = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3, mysql_error());
	$i=0;
	while($all=$xoopsDB->fetchArray($result)){

    foreach($all as $k=>$v){
      $$k=$v;
      $block['web'][$i][$k]=$v;
    }

    $block['web'][$i]['my_web']=mkMenuOpt(sprintf(_MB_TCW_TO_MY_WEB,$WebName),"index.php?WebID={$WebID}","icon-home");
  	$block['web'][$i]['news_add']=mkMenuOpt(_MB_TCW_NEWS_ADD,"news.php?WebID={$WebID}&op=tad_web_news_form","icon-volume-up");
  	$block['web'][$i]['homework_add']=mkMenuOpt(_MB_TCW_HOMEWORK_ADD,"homework.php?WebID={$WebID}&op=tad_web_news_form","icon-pencil");
  	$block['web'][$i]['files_add']=mkMenuOpt(_MB_TCW_FILES_ADD,"files.php?WebID={$WebID}&op=tad_web_files_form","icon-arrow-up");
  	$block['web'][$i]['action_add']=mkMenuOpt(_MB_TCW_ACTION_ADD,"action.php?WebID={$WebID}&op=tad_web_action_form","icon-picture");
  	$block['web'][$i]['class_setup']=mkMenuOpt(_MB_TCW_WEB_SETUP,"aboutus.php?op=tad_web_adm&WebID={$WebID}","icon-wrench");
  	$block['web'][$i]['video_add']=mkMenuOpt(_MB_TCW_VIDEO_ADD,"video.php?WebID={$WebID}&op=tad_web_video_form","icon-film");
  	$block['web'][$i]['link_add']=mkMenuOpt(_MB_TCW_LINK_ADD,"link.php?WebID={$WebID}&op=tad_web_link_form","icon-globe");
  	$block['web'][$i]['logout']=mkMenuOpt(_MB_TCW_LOGOUT,"/user.php?op=logout","icon-ban-circle");
  	$block['web'][$i]['web_config']=mkMenuOpt(_MB_TCW_WEB_CONFIG,"aboutus.php?WebID={$WebID}&op=tad_web_config","icon-check");
  	$i++;
  }

  if(empty($block))return "";

	return $block;
}

function mkMenuOpt($title="",$url="",$icon="icon-volume-up"){
  if(substr($url,0,1)=="/"){
    $path=XOOPS_URL.$url;
  }else{
    $path=XOOPS_URL."/modules/tad_web/{$url}";
  }

  $opt="
		<i class='{$icon}'></i>
		<a href='{$path}'>$title</a>
	";
  return $opt;
}
?>