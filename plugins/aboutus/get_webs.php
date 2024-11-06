<?php
use XoopsModules\Tadtools\FooTable;
use XoopsModules\Tadtools\Utility;
use XoopsModules\Tad_web\Tools as TadWebTools;

require_once dirname(dirname(dirname(dirname(__DIR__)))) . '/mainfile.php';
require_once dirname(dirname(__DIR__)) . '/function.php';
require_once "langs/{$xoopsConfig['language']}.php";
// 關閉除錯訊息
$xoopsLogger->activated = false;
$FooTable = new FooTable();
$FooTable->render();

$moduleHandler = xoops_getHandler('module');
$xoopsModule = $moduleHandler->getByDirname('tad_web');
$configHandler = xoops_getHandler('config');
$xoopsModuleConfig = $configHandler->getConfigsByCat(0, $xoopsModule->getVar('mid'));

$CateID = (int) $_GET['CateID'];
$today = date('Y-m-d');
$now = date('Y-m-d H:i:s');
//我的班級ID（陣列）
$MyWebs = TadWebTools::MyWebID();

//找出各班最新聯絡簿
$sql = 'SELECT `WebID`, MAX(`HomeworkID`), MAX(`toCal`) FROM `' . $xoopsDB->prefix('tad_web_homework') . '` WHERE `HomeworkPostDate` <= ? GROUP BY `WebID`';
$result = Utility::query($sql, 's', [$now]) or Utility::web_error($sql, __FILE__, __LINE__);
while (list($WebID, $HomeworkID, $toCal) = $xoopsDB->fetchRow($result)) {
    $homework[$WebID] = $HomeworkID;
    $homework_date[$WebID] = substr($toCal, 0, 10);
}

//找出各班功課表
$sql = 'SELECT `WebID`, `ScheduleID`, `ScheduleName` FROM `' . $xoopsDB->prefix('tad_web_schedule') . '` WHERE `ScheduleDisplay` = ?';
$result = Utility::query($sql, 's', ['1']) or Utility::web_error($sql, __FILE__, __LINE__);
while (list($WebID, $ScheduleID, $ScheduleName) = $xoopsDB->fetchRow($result)) {
    $schedule[$WebID] = $ScheduleID;
    $schedule_title[$WebID] = $ScheduleName;
}

$list_web_order = $xoopsModuleConfig['list_web_order'];
if (empty($list_web_order)) {
    $list_web_order = 'WebSort';
}

$sql = 'SELECT * FROM `' . $xoopsDB->prefix('tad_web') . '` WHERE `WebEnable`=? AND `CateID`=? ORDER BY ' . $list_web_order;
$result = Utility::query($sql, 'si', ['1', $CateID]) or Utility::web_error($sql, __FILE__, __LINE__);

$web_tr = '';
while (false !== ($web = $xoopsDB->fetchArray($result))) {
    $WebID = $web['WebID'];
    $isMyWeb = in_array($WebID, $MyWebs);

    $web_plugin_enable_arr = TadWebTools::get_web_config('web_plugin_enable_arr', $WebID);

    $other_web_url = TadWebTools::get_web_config('other_web_url', $WebID);

    $web_url = !empty($other_web_url) ? "<a href=\"{$other_web_url}\" target=\"_blank\">{$web['WebTitle']}</a>" : '<a href="' . XOOPS_URL . "/modules/tad_web/index.php?WebID={$WebID}\" target=\"_blank\">{$web['WebTitle']}</a>";

    $label = in_array($WebID, $MyWebs) ? 'label-info' : 'label-success';

    $tool = $isMyWeb ? '<a href="' . XOOPS_URL . "/modules/tad_web/config.php?WebID={$WebID}\" target=\"_blank\"><i class=\"fa fa-wrench text-danger\"></i></a>" : '';

    $web_name = !empty($other_web_url) ? "<a href=\"{$other_web_url}\" target=\"_blank\">{$web['WebName']}</a> $tool" : '<a href="' . XOOPS_URL . "/modules/tad_web/index.php?WebID={$WebID}\" target=\"_blank\">{$web['WebName']}</a>";

    $web_counter = !empty($other_web_url) ? "<a href=\"{$other_web_url}\" target=\"_blank\"><span class='badge badge-info bg-info'>{$web['WebCounter']}</span></a>" : '<a href="'
        . XOOPS_URL . "/modules/tad_web/index.php?WebID={$WebID}\" target=\"_blank\"><span class='badge badge-info bg-info'>{$web['WebCounter']}</span></a>";

    if (empty($web_plugin_enable_arr) or false !== strpos($web_plugin_enable_arr, 'homework')) {
        $no_homework = $isMyWeb ? '<a href="' . XOOPS_URL . "/modules/tad_web/homework.php?WebID={$WebID}&op=edit_form\" class=\"btn btn-success\" style=\"color:white;\" target=\"_blank\">" . _MD_TCW_ABOUTUS_NO_HOMEWORK . '</a>' : "<span  style='color: #CFCFCF;'>" . _MD_TCW_ABOUTUS_NO_HOMEWORK . '</span>';
        $have_homework = (isset($homework[$WebID]) and !empty($homework[$WebID])) ? '<a href="'
        . XOOPS_URL . "/modules/tad_web/homework.php?WebID={$WebID}&HomeworkID={$homework[$WebID]}\" target=\"_blank\"><i class='fa fa-pencil-square-o' style='color: #AA6A31;'> {$homework_date[$WebID]} " . _MD_TCW_ABOUTUS_HOMEWORK . '</i></a>' : $no_homework;
    } else {
        $have_homework = '';
    }

    if (empty($web_plugin_enable_arr) or false !== strpos($web_plugin_enable_arr, 'schedule')) {
        $no_schedule = $isMyWeb ? '<a href="' . XOOPS_URL . "/modules/tad_web/schedule.php?WebID={$WebID}&op=edit_form\" class=\"btn btn-success\" style=\"color:white;\" target=\"_blank\">" . _MD_TCW_ABOUTUS_NO_SCHEDULE . '</a>' : "<span  style='color: #CFCFCF;'>" . _MD_TCW_ABOUTUS_NO_SCHEDULE . '</span>';
        $have_schedule = (isset($schedule[$WebID]) and !empty($schedule[$WebID])) ? '<a href="' . XOOPS_URL . "/modules/tad_web/schedule.php?WebID={$WebID}&ScheduleID={$schedule[$WebID]}\" target=\"_blank\" style='color: #6F8232;'><i class='fa fa-table'> " . _MD_TCW_ABOUTUS_SCHEDULE . '</i></a>' : $no_schedule;
    } else {
        $have_schedule = '';
    }
    $show_title = ($web_url == $web_name) ? $web_url : $web_name . ' ( ' . $web_url . ' ) ';

    $td1 = in_array('counter', $xoopsModuleConfig['aboutus_cols']) ? "<td>{$web_counter}</td>" : '';
    $td3 = in_array('schedule', $xoopsModuleConfig['aboutus_cols']) ? "<td>{$have_schedule}</td>" : '';
    $td4 = in_array('homework', $xoopsModuleConfig['aboutus_cols']) ? "<td>{$have_homework}</td>" : '';

    $web_tr .= "
      <tr>
        {$td1}
        <td>
          {$show_title}
        </td>
        {$td3}
        {$td4}
      </tr>
      ";
}

$th1 = in_array('counter', $xoopsModuleConfig['aboutus_cols']) ? '<th data-hide="phone">' . _MD_TCW_ALL_WEB_COUNTER . '</th>' : '';
$th3 = in_array('schedule', $xoopsModuleConfig['aboutus_cols']) ? '<th data-hide="phone">' . _MD_TCW_ABOUTUS_SCHEDULE . '</th>' : '';
$th4 = in_array('homework', $xoopsModuleConfig['aboutus_cols']) ? '<th data-hide="phone">' . _MD_TCW_ABOUTUS_HOMEWORK . '</th>' : '';

$content = $FooTableJS . '
<html lang="zh-TW">
<meta charset="utf-8">
<head>
<title>Web List</title>
<style>
body{
  font-size: 1em;
}
</style>
</head>
<body>
<h2 style="display:none;">Web List</h2>
<table class="footable">
  <thead>
    <tr>
      ' . $th1 . '
      <th data-class="expand">' . _MD_TCW_ALL_WEB_NAME . '</th>
      ' . $th3 . '
      ' . $th4 . '
    </tr>
  </thead>
  <tbody>
  ' . $web_tr . '
  </tbody>
</table>
</body>
</html>';

die($content);

// Utility::html5($content = "", $ui = false, $bootstrap = true, $bootstrap_version = 3, $use_jquery = true)
// die(html5($content, false, true, 3, false));
