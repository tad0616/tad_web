<?php
//  ------------------------------------------------------------------------ //
// 本模組由 tad 製作
// 製作日期：2011-04-15
// $Id:$
// ------------------------------------------------------------------------- //
/*-----------引入檔案區--------------*/
include_once "header.php";
$xoopsOption['template_main'] = "tad_web_index_tpl.html";
include_once XOOPS_ROOT_PATH."/header.php";
/*-----------function區--------------*/

//首頁
function ClassHome($WebID=""){
  global $xoopsDB,$xoopsUser,$xoopsTpl,$MyWebs;

	$ConfigValue=get_web_config("hide_function",$WebID);
	$hide_function=explode(';',$ConfigValue);
	foreach($hide_function as $function_name){
    $xoopsTpl->assign("hide_{$function_name}",1);
  }

  if(!in_array('news',$hide_function))list_tad_web_news($WebID,'news',5,'NewsDate');
  if(!in_array('homework',$hide_function))list_tad_web_news($WebID,'homework',5,'NewsDate');
  if(!in_array('discuss',$hide_function))list_tad_web_discuss($WebID,5);
  if(!in_array('files',$hide_function))list_tad_web_files($WebID,5);
  if(!in_array('action',$hide_function))list_tad_web_action($WebID,5);
  if(!in_array('video',$hide_function))list_tad_web_video($WebID,5);
  if(!in_array('link',$hide_function))list_tad_web_link($WebID,5);

	$sql = "update ".$xoopsDB->prefix("tad_web")." set `WebCounter` = `WebCounter` +1	where WebID ='{$WebID}'";
	$xoopsDB->queryF($sql);

  $xoopsTpl->assign('op','ClassHome');
  $xoopsTpl->assign('MyWebs',$MyWebs);
}


//取得所有班級
function list_all_class(){

  list_all_tad_webs();
  list_tad_web_news('','news',5,'NewsDate');
  list_tad_web_news('','homework',5,'NewsDate');
  list_tad_web_discuss('',5);
  list_tad_web_files('',5);
  list_tad_web_action('',5);
  list_tad_web_video('',5);
  list_tad_web_link('',5);

}

/*-----------執行動作判斷區----------*/
$op=(empty($_REQUEST['op']))?"":$_REQUEST['op'];
$WebID=(empty($_REQUEST['WebID']))?"":intval($_REQUEST['WebID']);

common_template($interface_menu,$WebID);

switch($op){

  case "list_tad_web_news":
  $xoopsTpl->assign( "op" , $op) ;
  list_tad_web_news("",'news');
	break;


  case "list_tad_web_homework":
  $xoopsTpl->assign( "op" , $op) ;
  list_tad_web_news("",'homework');
	break;

  case "list_tad_web_files":
  $xoopsTpl->assign( "op" , $op) ;
  list_tad_web_files("");
	break;

  case "list_tad_web_action":
  $xoopsTpl->assign( "op" , $op) ;
  list_tad_web_files("");
	break;

  case "list_tad_web_video":
  $xoopsTpl->assign( "op" , $op) ;
  list_tad_web_video("");
	break;

  case "list_tad_web_link":
  $xoopsTpl->assign( "op" , $op) ;
  list_tad_web_link("");
	break;

  case "list_tad_web_discuss":
  $xoopsTpl->assign( "op" , $op) ;
  list_tad_web_discuss("");
	break;


	default:
	if(!empty($WebID)){
    ClassHome($WebID);
	}else{
    list_all_class();
  }
	break;
}

/*-----------秀出結果區--------------*/
include_once XOOPS_ROOT_PATH.'/footer.php';
?>