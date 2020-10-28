<?php
//搜尋
function calendar_search($WebID, $queryarray, $limit = 10)
{
    global $xoopsDB;

    $plugin = 'calendar';
    $plugin_tbl = 'tad_web_calendar';
    $id_col = 'CalendarID';
    $title_col = 'CalendarName';
    $date_col = 'CalendarDate';
    $content_col = 'CalendarDesc';

    $myts = \MyTextSanitizer::getInstance();
    foreach ($queryarray as $k => $v) {
        $arr[$k] = $myts->addSlashes($v);
    }
    $queryarray = $arr;

    $and_web = $WebID ? " and `WebID`='{$WebID}'" : '';
    $sql = "SELECT `{$id_col}`,`{$title_col}`,`{$date_col}`, `WebID` FROM " . $xoopsDB->prefix($plugin_tbl) . ' WHERE 1' . $and_web;

    if (is_array($queryarray) && $count = count($queryarray)) {
        $sql .= " AND ((`{$title_col}` LIKE '%{$queryarray[0]}%'  OR `{$content_col}` LIKE '%{$queryarray[0]}%' )";
        for ($i = 1; $i < $count; $i++) {
            $sql .= " $andor ";
            $sql .= "(`{$title_col}` LIKE '%{$queryarray[$i]}%' OR  `{$content_col}` LIKE '%{$queryarray[$i]}%' )";
        }
        $sql .= ') ';
    }
    $sql .= " ORDER BY  `{$date_col}` DESC";

    $result = $xoopsDB->query($sql, $limit);
    $ret = [];
    $i = 0;
    while (false !== ($myrow = $xoopsDB->fetchArray($result))) {
        $ret[$i]['link'] = "{$plugin}.php?WebID=" . $myrow['WebID'] . "&{$id_col}=" . $myrow[$id_col];
        $ret[$i]['title'] = $myrow[$title_col];
        $ret[$i]['time'] = mb_substr($myrow[$date_col], 0, 10);
        $i++;
    }

    return $ret;
}
