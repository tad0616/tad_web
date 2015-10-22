<?php
include_once "../../../../mainfile.php";
include_once "../../header.php";
include_once "langs/{$xoopsConfig['language']}.php";
if (file_exists(XOOPS_ROOT_PATH . "/modules/tadtools/FooTable.php")) {
    include_once XOOPS_ROOT_PATH . "/modules/tadtools/FooTable.php";
    $FooTable   = new FooTable();
    $FooTableJS = $FooTable->render();
}
$CateID = intval($_GET['CateID']);
$today  = date("Y-m-d");

$sql    = "select `WebID`,max(`HomeworkID`),max(`toCal`) from " . $xoopsDB->prefix("tad_web_homework") . " group by `WebID`";
$result = $xoopsDB->query($sql) or web_error($sql);
while (list($WebID, $HomeworkID, $toCal) = $xoopsDB->fetchRow($result)) {
    $homework[$WebID]      = $HomeworkID;
    $homework_date[$WebID] = substr($toCal, 0, 10);
}

$sql    = "select * from " . $xoopsDB->prefix("tad_web") . " where `WebEnable`='1' and CateID='{$CateID}' order by WebSort";
$result = $xoopsDB->query($sql) or web_error($sql);

$web_tr = '';
while ($web = $xoopsDB->fetchArray($result)) {
    $WebID         = $web['WebID'];
    $other_web_url = get_web_config('other_web_url', $WebID);
    $web_url       = !empty($other_web_url) ? "<a href=\"{$other_web_url}\">{$web['WebTitle']}</a>" : "<a href=\"" . XOOPS_URL . "/modules/tad_web/index.php?WebID={$WebID}\">{$web['WebTitle']}</a>";

    $label = in_array($WebID, $MyWebs) ? "label-info" : "label-success";

    $tool = $isMyWeb ? "<a href=\"" . XOOPS_URL . "/modules/tad_web/config.php?WebID={$WebID}\"><i class=\"fa fa-wrench text-danger\"></i></a>" : "";

    $web_name = !empty($other_web_url) ? "<a href=\"{$other_web_url}\">{$web['WebName']}</a> $tool" : "<a href=\"" . XOOPS_URL . "/modules/tad_web/index.php?WebID={$WebID}\">{$web['WebName']}</a>";

    $web_counter = !empty($other_web_url) ? "<a href=\"{$other_web_url}\"><span class='label label-info'>{$web['WebCounter']}</span></a>" : "<a href=\"" . XOOPS_URL . "/modules/tad_web/index.php?WebID={$WebID}\"><span class='label label-info'>{$web['WebCounter']}</span></a>";

    $have_homework = (isset($homework[$WebID]) and !empty($homework[$WebID])) ? "<a href=\"" . XOOPS_URL . "/modules/tad_web/homework.php?WebID={$WebID}&HomeworkID={$homework[$WebID]}\" target=\"_blank\"><i class='fa fa-pencil-square-o'> {$homework_date[$WebID]} " . _MD_TCW_ABOUTUS_HOMEWORK . "</i></a>" : "<span class='placeholder'>本日尚無聯絡簿</span>";

    $show_title = $web_url == $web_name ? $web_url : $web_url . _TAD_FOR . $web_name;
    $web_tr .= "
      <tr>
        <td>
          {$web_counter}
        </td>
        <td>
          {$show_title}
        </td>
        <td>
          <i class='fa fa-table'></i>
        </td>
        <td>
          {$have_homework}
        </td>
      </tr>
      ";
}

$content = $FooTableJS . '
<table class="table footable">
  <thead>
    <tr>
      <th data-hide="phone">' . _MD_TCW_ALL_WEB_COUNTER . '</th>
      <th data-class="expand">' . _MD_TCW_ALL_WEB_TITLE . '</th>
      <th data-hide="phone">' . _MD_TCW_ABOUTUS_SCHEDULE . '</th>
      <th data-hide="phone">' . _MD_TCW_ABOUTUS_HOMEWORK . '</th>
    </tr>
  </thead>
  <tbody>
  ' . $web_tr . '
  </tbody>
</table>';

echo html5($content, false, false, $_SESSION['bootstrap']);
