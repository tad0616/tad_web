<?php
use Xmf\Request;
use XoopsModules\Tadtools\Utility;
require_once dirname(dirname(dirname(dirname(__DIR__)))) . '/mainfile.php';
require_once dirname(dirname(__DIR__)) . '/function.php';
$xoopsLogger->activated = false;
$op = Request::getString('op');
$WebID = Request::getInt('WebID');
$ScheduleID = Request::getInt('ScheduleID');
$tag = Request::getString('tag');
$Subject = Request::getString('Subject');

list($SDWeek, $SDSort) = explode('-', $tag);

if ('delete' === $op) {
    $sql = 'DELETE FROM `' . $xoopsDB->prefix('tad_web_schedule_data') . '` WHERE `ScheduleID`=? AND `SDWeek`=? AND `SDSort`=?';
    Utility::query($sql, 'iii', [$ScheduleID, $SDWeek, $SDSort]) or die(_TAD_SORT_FAIL . ' (' . date('Y-m-d H:i:s') . ')' . $sql);

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
    $sql = 'REPLACE INTO `' . $xoopsDB->prefix('tad_web_schedule_data') . '` (`ScheduleID`, `SDWeek`, `SDSort`, `Subject`, `Teacher`, `Link`, `color`, `bg_color`) VALUES (?, ?, ?, ?, ?, ?, ?, ?)';
    Utility::query($sql, 'iiisssss', [$ScheduleID, $SDWeek, $SDSort, $Subject, $Teacher, (string) $Link, $color, $bg_color]) or die(_TAD_SORT_FAIL . ' (' . date('Y-m-d H:i:s') . ')' . $sql);
}
