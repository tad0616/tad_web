<?php
//搜尋
function files_search($WebID, $queryarray, $limit = 10)
{
    global $xoopsDB;

    $plugin = 'files';
    $plugin_tbl1 = 'tad_web_files';
    $plugin_tbl2 = 'tad_web_files_center';
    $id_col1 = 'fsn';
    $id_col2 = 'files_sn';
    $title_col = 'original_filename';
    $date_col = 'file_date';
    $content_col = 'description';

    $myts = \MyTextSanitizer::getInstance();
    foreach ($queryarray as $k => $v) {
        $arr[$k] = $myts->addSlashes($v);
    }
    $queryarray = $arr;

    // die(var_export($queryarray));
    $sql = "SELECT b.`{$id_col2}`,b.`{$title_col}`,a.`{$date_col}`, a.`WebID` FROM " . $xoopsDB->prefix($plugin_tbl1) . ' as a left join ' . $xoopsDB->prefix($plugin_tbl2) . " as b on b.col_name='{$id_col1}' and a.`{$id_col1}`=b.`col_sn` WHERE 1";

    if (is_array($queryarray) && $count = count($queryarray)) {
        $sql .= " AND ((b.`{$title_col}` LIKE '%{$queryarray[0]}%'  OR b.`{$content_col}` LIKE '%{$queryarray[0]}%' )";
        for ($i = 1; $i < $count; $i++) {
            $sql .= " $andor ";
            $sql .= "(b.`{$title_col}` LIKE '%{$queryarray[$i]}%' OR  b.`{$content_col}` LIKE '%{$queryarray[$i]}%' )";
        }
        $sql .= ') ';
    }
    $sql .= " ORDER BY  a.`{$date_col}` DESC";
    // die($sql);
    $result = $xoopsDB->query($sql, $limit);
    $ret = [];
    $i = 0;
    while ($myrow = $xoopsDB->fetchArray($result)) {
        $ret[$i]['link'] = "{$plugin}.php?WebID=" . $myrow['WebID'] . "&op=tufdl&{$id_col2}=" . $myrow[$id_col2];
        $ret[$i]['title'] = $myrow[$title_col];
        $ret[$i]['time'] = mb_substr($myrow[$date_col], 0, 10);
        $i++;
    }

    return $ret;
}
