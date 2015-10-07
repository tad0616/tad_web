<?php
include_once "header.php";
$CateID = intval($_GET['CateID']);
$sql    = "select * from " . $xoopsDB->prefix("tad_web") . " where `WebEnable`='1' and CateID='{$CateID}' order by WebSort";
$result = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'], 3, mysql_error());

$web_tr = '';
while ($web = $xoopsDB->fetchArray($result)) {
    $other_web_url = get_web_config('other_web_url', $web['WebID']);
    $web_url       = !empty($other_web_url) ? "<a href='{$other_web_url}'>{$web['WebTitle']}</a>" : "<a href='index.php?WebID={$web['WebID']}'>{$web['WebTitle']}</a>";

    $label = in_array($web['WebID'], $MyWebs) ? "label-info" : "label-success";

    $tool = $isMyWeb ? "<a href='" . XOOPS_URL . "/modules/tad_web/config.php?WebID={$web['WebID']}'><i class='fa fa-wrench text-danger'></i></a>" : "";

    $web_name = !empty($other_web_url) ? "<a href='{$other_web_url}'>{$web['WebName']}</a> $tool" : "<a href='index.php?WebID={$web['WebID']}'>{$web['WebName']}</a>";

    $web_counter = !empty($other_web_url) ? "<a href='{$other_web_url}'>{$web['WebCounter']}</a>" : "<a href='index.php?WebID={$web['WebID']}'>{$web['WebCounter']}</a>";

    $web_tr .= "
      <tr>
        <td>
          {$web_url}
        </td>
        <td>
          <span class='label $label'>{$web['WebOwner']}</span>
        </td>
        <td>
          {$web_name}
        </td>
        <td>
          {$web_counter}
        </td>
      </tr>
      ";
}

echo '
<table class="table footable">
  <thead>
    <tr>
      <th data-class="expand">' . _MD_TCW_ALL_WEB_TITLE . '</th>
      <th>' . _MD_TCW_ALL_WEB_OWNER . '</th>
      <th data-hide="phone">' . _MD_TCW_ALL_WEB_NAME . '</th>
      <th data-hide="phone">' . _MD_TCW_ALL_WEB_COUNTER . '</th>
    </tr>
  </thead>
  ' . $web_tr . '
</table>';
