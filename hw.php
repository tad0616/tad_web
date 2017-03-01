<?php
/*-----------引入檔案區--------------*/
include_once "header.php";
$sql    = "select HomeworkID,HomeworkContent from " . $xoopsDB->prefix("tad_web_homework") . " order by HomeworkID desc limit 0,150";
$result = $xoopsDB->queryF($sql) or web_error($sql);
// $main   = "<table border=1>";
$r_start = '<table class="table"><tbody><tr>';

// $r2 = '<td><img alt="今日作業" src="http://class.tn.edu.tw/modules/tad_web/images/today_homework.png" /></td>';
// $r3 = '<td><img alt="明日準備事項" src="http://class.tn.edu.tw/modules/tad_web/images/bring.png" /></td>';
// $r4 = '<td><img alt="老師的叮嚀" src="http://class.tn.edu.tw/modules/tad_web/images/teacher_say.png" /></td>';

$r_end = '</td></tr></tbody></table>';

$today_homework = $bring = $teacher_say = false;
while (list($HomeworkID, $HomeworkContent) = $xoopsDB->fetchRow($result)) {
    if (strpos($HomeworkContent, '<table class="table">') === false) {
        continue;
    }

    $newHomeworkContent                 = preg_replace('/(?![ ])\s+/', '', trim($HomeworkContent));
    list($table, $other)                = explode('</tbody></table>', $newHomeworkContent);
    list($content_title, $content_body) = explode('</tr><tr>', $table);

    $tr_content_title = str_replace($r_start, '', $content_title);
    $tr_content_body  = str_replace($r_end, '', $content_body);

    //有無今日作業
    $today_homework = strpos($tr_content_title, 'today_homework.png') ? true : false;

    //有無明日準備事項
    $bring = strpos($tr_content_title, 'bring.png') ? true : false;

    //有無老師的叮嚀
    $teacher_say = strpos($tr_content_title, 'teacher_say.png') ? true : false;

    $tr_content_body = str_replace('<pre>', '<div class="well">', $tr_content_body);
    $tr_content_body = str_replace('</pre>', '</div>', $tr_content_body);

    $content = '';
    $content = explode('</td><td>', $tr_content_body);

    if ($today_homework and $bring and $teacher_say) {
        $data = '<div class="col-sm-4 col-sm-4 span4 col-1"><p><img alt="今日作業" src="http://class.tn.edu.tw/modules/tad_web/images/today_homework.png" /></p>' . $content[0] . '</div>';
        $data .= '<div class="col-sm-4 col-sm-4 span4 col-2"><p><img alt="明日準備事項" src="http://class.tn.edu.tw/modules/tad_web/images/bring.png" /></p>' . $content[1] . '</div>';
        $data .= '<div class="col-sm-4 col-sm-4 span4 col-3"><p><img alt="老師的叮嚀" src="http://class.tn.edu.tw/modules/tad_web/images/teacher_say.png" /></p>' . $content[2] . '</div>';
    } elseif ($today_homework and !$bring and $teacher_say) {
        $data = '<div class="col-sm-4 col-sm-4 span4 col-1"><p><img alt="今日作業" src="http://class.tn.edu.tw/modules/tad_web/images/today_homework.png" /></p>' . $content[0] . '</div>';
        $data .= '<div class="col-sm-4 col-sm-4 span4 col-2"><p><img alt="老師的叮嚀" src="http://class.tn.edu.tw/modules/tad_web/images/teacher_say.png" /></p>' . $content[1] . '</div>';
    } elseif ($today_homework and $bring and !$teacher_say) {
        $data = '<div class="col-sm-4 col-sm-4 span4 col-1"><p><img alt="今日作業" src="http://class.tn.edu.tw/modules/tad_web/images/today_homework.png" /></p>' . $content[0] . '</div>';
        $data .= '<div class="col-sm-4 col-sm-4 span4 col-2"><p><img alt="明日準備事項" src="http://class.tn.edu.tw/modules/tad_web/images/bring.png" /></p>' . $content[1] . '</div>';
    } elseif (!$today_homework and $bring and $teacher_say) {
        $data = '<div class="col-sm-4 col-sm-4 span4 col-1"><p><img alt="明日準備事項" src="http://class.tn.edu.tw/modules/tad_web/images/bring.png" /></p>' . $content[0] . '</div>';
        $data .= '<div class="col-sm-4 col-sm-4 span4 col-2"><p><img alt="老師的叮嚀" src="http://class.tn.edu.tw/modules/tad_web/images/teacher_say.png" /></p>' . $content[1] . '</div>';
    } elseif ($today_homework and !$bring and !$teacher_say) {
        $data = '<div class="col-sm-4 col-sm-4 span4 col-1"><p><img alt="今日作業" src="http://class.tn.edu.tw/modules/tad_web/images/today_homework.png" /></p>' . $content[0] . '</div>';
    } elseif (!$today_homework and $bring and !$teacher_say) {
        $data = '<div class="col-sm-4 col-sm-4 span4 col-1"><p><img alt="明日準備事項" src="http://class.tn.edu.tw/modules/tad_web/images/bring.png" /></p>' . $content[0] . '</div>';
    } elseif (!$today_homework and !$bring and $teacher_say) {
        $data = '<div class="col-sm-4 col-sm-4 span4 col-1"><p><img alt="老師的叮嚀" src="http://class.tn.edu.tw/modules/tad_web/images/teacher_say.png" /></p>' . $content[0] . '</div>';
    }

    $tr_content_title = nl2br(htmlspecialchars($tr_content_title));
    $main .= "<div class='alert alert-info'>{$today_homework}-{$bring}-{$teacher_say}<br>{$tr_content_title}</div>";
    $main .= $HomeworkContent;
    if (empty($other)) {
        $other = "<p>&nbsp;</p>";
    }
    $main .= "<div class=\"row three-col\">{$data}</div>{$other}";

}
// $main .= "</table>";
echo html5($main);
