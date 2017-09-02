<?php
include_once "../../../../mainfile.php";
include_once "../../function.php";
include_once "langs/{$xoopsConfig['language']}.php";
if (file_exists(XOOPS_ROOT_PATH . "/modules/tadtools/FooTable.php")) {
    include_once XOOPS_ROOT_PATH . "/modules/tadtools/FooTable.php";
    $FooTable   = new FooTable();
    $FooTableJS = $FooTable->render();
}

$modhandler        = xoops_getHandler('module');
$xoopsModule       = $modhandler->getByDirname("tad_web");
$config_handler    = xoops_getHandler('config');
$xoopsModuleConfig = $config_handler->getConfigsByCat(0, $xoopsModule->getVar('mid'));

$CateID = (int)$_GET['CateID'];
$today  = date("Y-m-d");
$now    = date("Y-m-d H:i:s");
//我的班級ID（陣列）
$MyWebs = MyWebID();

//找出各班最新聯絡簿
$sql    = "select `WebID`,max(`HomeworkID`),max(`toCal`) from " . $xoopsDB->prefix("tad_web_homework") . " where HomeworkPostDate <= '$now' group by `WebID`";
$result = $xoopsDB->query($sql) or web_error($sql);
while (list($WebID, $HomeworkID, $toCal) = $xoopsDB->fetchRow($result)) {
    $homework[$WebID]      = $HomeworkID;
    $homework_date[$WebID] = substr($toCal, 0, 10);
}

//找出各班功課表
$sql = "SELECT `WebID`,`ScheduleID`,`ScheduleName` FROM " . $xoopsDB->prefix("tad_web_schedule") . " WHERE `ScheduleDisplay` = '1'";
$result = $xoopsDB->query($sql) or web_error($sql);
while (list($WebID, $ScheduleID, $ScheduleName) = $xoopsDB->fetchRow($result)) {
    $schedule[$WebID]       = $ScheduleID;
    $schedule_title[$WebID] = $ScheduleName;
}

$list_web_order = $xoopsModuleConfig['list_web_order'];
if (empty($list_web_order)) {
    $list_web_order = 'WebSort';
}

$sql    = "select * from " . $xoopsDB->prefix("tad_web") . " where `WebEnable`='1' and CateID='{$CateID}' order by {$list_web_order}";
$result = $xoopsDB->query($sql) or web_error($sql);

$web_tr = '';
while ($web = $xoopsDB->fetchArray($result)) {
    $WebID   = $web['WebID'];
    $isMyWeb = in_array($WebID, $MyWebs);

    $web_plugin_enable_arr = get_web_config("web_plugin_enable_arr", $WebID);

    $other_web_url = get_web_config('other_web_url', $WebID);

    $web_url = !empty($other_web_url) ? "<a href=\"{$other_web_url}\" target=\"_blank\">{$web['WebTitle']}</a>" : "<a href=\"" . XOOPS_URL . "/modules/tad_web/index.php?WebID={$WebID}\" target=\"_blank\">{$web['WebTitle']}</a>";

    $label = in_array($WebID, $MyWebs) ? "label-info" : "label-success";

    $tool = $isMyWeb ? "<a href=\"" . XOOPS_URL . "/modules/tad_web/config.php?WebID={$WebID}\" target=\"_blank\"><i class=\"fa fa-wrench text-danger\"></i></a>" : "";

    $web_name = !empty($other_web_url) ? "<a href=\"{$other_web_url}\" target=\"_blank\">{$web['WebName']}</a> $tool" : "<a href=\"" . XOOPS_URL . "/modules/tad_web/index.php?WebID={$WebID}\" target=\"_blank\">{$web['WebName']}</a>";

    $web_counter = !empty($other_web_url) ? "<a href=\"{$other_web_url}\" target=\"_blank\"><span class='label label-info'>{$web['WebCounter']}</span></a>" : "<a href=\"" . XOOPS_URL . "/modules/tad_web/index.php?WebID={$WebID}\" target=\"_blank\"><span class='label label-info'>{$web['WebCounter']}</span></a>";

    if (empty($web_plugin_enable_arr) or strpos($web_plugin_enable_arr, 'homework') !== false) {
        $no_homework   = $isMyWeb ? "<a href=\"" . XOOPS_URL . "/modules/tad_web/homework.php?WebID={$WebID}&op=edit_form\" class=\"btn btn-success\" style=\"color:white;\" target=\"_blank\">" . _MD_TCW_ABOUTUS_NO_HOMEWORK . "</a>" : "<span  style='color: #CFCFCF;'>" . _MD_TCW_ABOUTUS_NO_HOMEWORK . "</span>";
        $have_homework = (isset($homework[$WebID]) and !empty($homework[$WebID])) ? "<a href=\"" . XOOPS_URL . "/modules/tad_web/homework.php?WebID={$WebID}&HomeworkID={$homework[$WebID]}\" target=\"_blank\"><i class='fa fa-pencil-square-o' style='color: #AA6A31;'> {$homework_date[$WebID]} " . _MD_TCW_ABOUTUS_HOMEWORK . "</i></a>" : $no_homework;
    } else {
        $have_homework = '';
    }

    if (empty($web_plugin_enable_arr) or strpos($web_plugin_enable_arr, 'schedule') !== false) {
        $no_schedule   = $isMyWeb ? "<a href=\"" . XOOPS_URL . "/modules/tad_web/schedule.php?WebID={$WebID}&op=edit_form\" class=\"btn btn-success\" style=\"color:white;\" target=\"_blank\">" . _MD_TCW_ABOUTUS_NO_SCHEDULE . "</a>" : "<span  style='color: #CFCFCF;'>" . _MD_TCW_ABOUTUS_NO_SCHEDULE . "</span>";
        $have_schedule = (isset($schedule[$WebID]) and !empty($schedule[$WebID])) ? "<a href=\"" . XOOPS_URL . "/modules/tad_web/schedule.php?WebID={$WebID}&ScheduleID={$schedule[$WebID]}\" target=\"_blank\" style='color: #6F8232;'><i class='fa fa-table'> " . _MD_TCW_ABOUTUS_SCHEDULE . "</i></a>" : $no_schedule;
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
</table>';

die($content);
