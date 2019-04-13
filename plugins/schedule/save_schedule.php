<?php
include_once '../../../../mainfile.php';
include_once '../../function.php';

include_once $GLOBALS['xoops']->path('/modules/system/include/functions.php');
$op = system_CleanVars($_REQUEST, 'op', '', 'string');
$WebID = system_CleanVars($_REQUEST, 'WebID', 0, 'int');
$ScheduleID = system_CleanVars($_REQUEST, 'ScheduleID', 0, 'int');
$tag = system_CleanVars($_REQUEST, 'tag', '', 'string');
$Subject = system_CleanVars($_REQUEST, 'Subject', '', 'string');

list($SDWeek, $SDSort) = explode('-', $tag);

if ('delete' == $op) {
    $sql = 'delete from ' . $xoopsDB->prefix('tad_web_schedule_data') . " where `ScheduleID`='{$ScheduleID}'and `SDWeek`='{$SDWeek}' and `SDSort`='{$SDSort}' ";
} else {
    $my_subject_file = XOOPS_ROOT_PATH . "/uploads/tad_web/{$WebID}/my_subject.json";
    if (file_exists($my_subject_file)) {
        $subjects = json_decode(file_get_contents($my_subject_file), true);
        foreach ($subjects as $key => $subject) {
            if ($subject['Subject'] == $Subject) {
                $Teacher = $subject['Teacher'];
                $color = $subject['color'];
                $bg_color = $subject['bg_color'];
                break;
            }
        }
    } else {
        $color = '#000000';
        $bg_color = '#FFFFFF';
    }
    $sql = 'replace into ' . $xoopsDB->prefix('tad_web_schedule_data') . " (`ScheduleID`, `SDWeek`, `SDSort`, `Subject`, `Teacher`, `color`, `bg_color`) values('{$ScheduleID}', '{$SDWeek}', '{$SDSort}', '{$Subject}', '{$Teacher}', '{$color}', '{$bg_color}') ";
}
$xoopsDB->queryF($sql) or die(_TAD_SORT_FAIL . ' (' . date('Y-m-d H:i:s') . ')' . $sql);
