<?php
include_once "../../../../mainfile.php";
include_once "../../header.php";
include_once "langs/{$xoopsConfig['language']}.php";

$CateID = intval($_GET['CateID']);
$sql    = "select * from " . $xoopsDB->prefix("tad_web") . " where `WebEnable`='1' and CateID='{$CateID}' order by WebSort";
$result = $xoopsDB->query($sql) or web_error($sql);

$web_tr = '';
while ($web = $xoopsDB->fetchArray($result)) {
    $other_web_url = get_web_config('other_web_url', $web['WebID']);
    $web_url       = !empty($other_web_url) ? "<a href=\"{$other_web_url}\">{$web['WebTitle']}</a>" : "<a href=\"" . XOOPS_URL . "/modules/tad_web/index.php?WebID={$web['WebID']}\">{$web['WebTitle']}</a>";

    $label = in_array($web['WebID'], $MyWebs) ? "label-info" : "label-success";

    $tool = $isMyWeb ? "<a href=\"" . XOOPS_URL . "/modules/tad_web/config.php?WebID={$web['WebID']}\"><i class=\"fa fa-wrench text-danger\"></i></a>" : "";

    $web_name = !empty($other_web_url) ? "<a href=\"{$other_web_url}\">{$web['WebName']}</a> $tool" : "<a href=\"" . XOOPS_URL . "/modules/tad_web/index.php?WebID={$web['WebID']}\">{$web['WebName']}</a>";

    $web_counter = !empty($other_web_url) ? "<a href=\"{$other_web_url}\">{$web['WebCounter']}</a>" : "<a href=\"" . XOOPS_URL . "/modules/tad_web/index.php?WebID={$web['WebID']}\">{$web['WebCounter']}</a>";

    $web_tr .= "
      <tr>
        <td>
          {$web_url}
        </td>
        <td>
          <span class=\"label {$label}\">{$web['WebOwner']}</span>
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

$content = '
<table id="list_all_webs" class="table" data-sorting="true" data-filtering="true">
  <thead>
    <tr>
      <th>' . _MD_TCW_ALL_WEB_TITLE . '</th>
      <th>' . _MD_TCW_ALL_WEB_OWNER . '</th>
      <th data-breakpoints="xs sm">' . _MD_TCW_ALL_WEB_NAME . '</th>
      <th data-breakpoints="xs sm">' . _MD_TCW_ALL_WEB_COUNTER . '</th>
    </tr>
  </thead>
  <tbody>
  ' . $web_tr . '
  </tbody>
</table>';

echo html5($content, false, false, $_SESSION['bootstrap']);
