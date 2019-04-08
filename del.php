<?php
/*-----------引入檔案區--------------*/
include_once "header.php";
$sql = "SELECT a.HomeworkID, a.WebID, b.WebOwnerUid FROM " . $xoopsDB->prefix("tad_web_homework") . " as a
left join " . $xoopsDB->prefix("tad_web") . " as b on a.WebID=b.WebID
where a.uid=0";
$result = $xoopsDB->queryF($sql) or web_error($sql, __FILE__, __LINE__);

$main           = '';
$today_homework = $bring = $teacher_say = false;
while (list($HomeworkID, $WebID, $WebOwnerUid) = $xoopsDB->fetchRow($result)) {
    $sql = "update " . $xoopsDB->prefix("tad_web_homework") . " set uid='$WebOwnerUid' where HomeworkID='$HomeworkID'";
    // $xoopsDB->queryF($sql) or web_error($sql, __FILE__, __LINE__);
    $main .= "<div>{$HomeworkID}=>{$WebID}=><b>$WebOwnerUid</b></div>$sql";
}
echo html5($main);
