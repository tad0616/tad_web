<?php
//  ------------------------------------------------------------------------ //
// 本模組由 tad 製作
// 製作日期：2011-04-19
// $Id:$
// ------------------------------------------------------------------------- //

//區塊主函式 (班級選單(tad_web_discuss))
function tad_web_discuss($options){
  global $xoopsDB;

	$sql = "select * from ".$xoopsDB->prefix("tad_web_discuss")." where ReDiscussID='0'  order by LastTime desc limit 0,10";
	$result = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3, mysql_error());

	$main_data="";

	while($all=$xoopsDB->fetchArray($result)){
	  //以下會產生這些變數： $DiscussID , $ReDiscussID , $uid , $DiscussTitle , $DiscussContent , $DiscussDate , $WebID , $LastTime , $DiscussCounter
    foreach($all as $k=>$v){
      $$k=$v;
    }

    $renum=get_block_re_num($DiscussID);
    $show_re_num=empty($renum)?"":"（{$renum}）";


    $LastTime=substr($LastTime,0,10);


		$main_data.="<tr>
		<td><img src='images/right_icon4.png' width='6' height='10' hspace=4  /><a href='discuss.php?DiscussID=$DiscussID&WebID=$WebID'>{$DiscussTitle}</a>{$show_re_num}</td>
		</tr>";
	}
	if(empty($main_data))$main_data="<tr><td colspan=4 class='c'>尚無任何討論主題</td></tr>";



	$block="
	<div><img src='images/right_icon3.png' width='274' height='83'/></div>
	<div class='right_type5'>
	<table summary='list_table' style='width:100%;'  cellpadding='6'>
	<tbody>
	$main_data
	</tbody>
	</table>
  </div>";
	return $block;
}


//取得回覆數量
function get_block_re_num($DiscussID=""){
	global $xoopsDB,$xoopsUser;
  if(empty($DiscussID)) return 0;
	$sql = "select count(*) from ".$xoopsDB->prefix("tad_web_discuss")." where ReDiscussID='$DiscussID'";
	$result = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3, mysql_error());
  list($counter)=$xoopsDB->fetchRow($result);
  return $counter;
}
?>