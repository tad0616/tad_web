<?php
include_once "header.php";

/* 連資料庫檢查 */
echo get_event();

//取得事件
function get_event(){
	global $xoopsDB,$xoopsUser;
  $andWebID=(empty($_REQUEST['WebID']))?"":"and `WebID`='{$_REQUEST['WebID']}'";
	$start=date("Y-m-d",$_REQUEST['start']/1000);
	$andEnd="";
	if($_REQUEST['end']){
    $end=date("Y-m-d",$_REQUEST['end']/1000);
    $andEnd="and toCal <= '$end'";
  }
	$andNewsKind=empty($_REQUEST['NewsKind'])?"":"and NewsKind='{$_REQUEST['NewsKind']}'";

	$sql = "select * from ".$xoopsDB->prefix("tad_web_news")." where toCal >= '$start' $andEnd $andWebID $andNewsKind order by toCal";
	//$sql = "select * from ".$xoopsDB->prefix("tad_web_news")." where 1 $andWebID $andNewsKind order by toCal";
	//die($sql);
	$result = $xoopsDB->queryF($sql) or redirect_header($_SERVER['PHP_SELF'],3, mysql_error());
	$i=0;
	while($all=$xoopsDB->fetchArray($result)){
	//以下會產生這些變數： $out_sn , $inspector_name , $out_date , $location , $job , $job_content
    foreach($all as $k=>$v){
      $$k=$v;
    }
    $toCal=userTimeToServerTime(strtotime($toCal));

		$myEvents[$i]['id']=$NewsID;
		$myEvents[$i]['title']=$NewsTitle;
		$myEvents[$i]['rel']=XOOPS_URL."/modules/tad_web/{$NewsKind}.php?WebID=$WebID&NewsID={$NewsID}";
		$myEvents[$i]['start']=$toCal;
    $myEvents[$i]['allDay']=true;
    $myEvents[$i]['className']="fc-event";

		$i++;
	}
	return json_encode($myEvents);
}


if(!function_exists('json_encode')){
  function json_encode($a=false){
      if (is_null($a)) return 'null';
      if ($a === false) return 'false';
      if ($a === true) return 'true';
      if (is_scalar($a)){
        if (is_float($a))
        {
          // Always use "." for floats.
          return floatval(str_replace(",", ".", strval($a)));
        }

        if (is_string($a))
        {
          static $jsonReplaces = array(array("\\", "/", "\n", "\t", "\r", "\b", "\f", '"'), array('\\\\', '\\/', '\\n', '\\t', '\\r', '\\b', '\\f', '\"'));
          return '"' . str_replace($jsonReplaces[0], $jsonReplaces[1], $a) . '"';
        }
        else
          return $a;
      }
      $isList = true;
      for ($i = 0, reset($a); $i < count($a); $i++, next($a))
      {
        if (key($a) !== $i)
        {
          $isList = false;
          break;
        }
      }
      $result = array();
      if ($isList)
      {
        foreach ($a as $v) $result[] = json_encode($v);
        return '[' . join(',', $result) . ']';
      }
      else
      {
        foreach ($a as $k => $v) $result[] = json_encode($k).':'.json_encode($v);
        return '{' . join(',', $result) . '}';
      }
  }
}
?>
