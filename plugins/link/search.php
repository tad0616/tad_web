<?php
//搜尋
function link_search($WebID, $queryarray, $limit = 10)
{
    global $xoopsDB;

    $plugin = 'link';
    $plugin_tbl = 'tad_web_link';
    $id_col = 'LinkID';
    $title_col = 'LinkTitle';
    $date_col = '';
    $content_col = 'LinkDesc';

    $myts = \MyTextSanitizer::getInstance();
    foreach ($queryarray as $k => $v) {
        $arr[$k] = $myts->addSlashes($v);
    }
    $queryarray = $arr;

    // die(var_export($queryarray));
    $sql = "SELECT `{$id_col}`,`{$title_col}`, `WebID` FROM " . $xoopsDB->prefix($plugin_tbl) . ' WHERE 1';

    if (is_array($queryarray) && $count = count($queryarray)) {
        $sql .= " AND ((`{$title_col}` LIKE '%{$queryarray[0]}%'  OR `{$content_col}` LIKE '%{$queryarray[0]}%' )";
        for ($i = 1; $i < $count; $i++) {
            $sql .= " $andor ";
            $sql .= "(`{$title_col}` LIKE '%{$queryarray[$i]}%' OR  `{$content_col}` LIKE '%{$queryarray[$i]}%' )";
        }
        $sql .= ') ';
    }
    $sql .= " ORDER BY  `{$id_col}` DESC";

    $result = $xoopsDB->query($sql, $limit);
    $ret = [];
    $i = 0;
    while ($myrow = $xoopsDB->fetchArray($result)) {
        $ret[$i]['link'] = "{$plugin}.php?WebID=" . $myrow['WebID'] . "&{$id_col}=" . $myrow[$id_col];
        $ret[$i]['title'] = $myrow[$title_col];
        $ret[$i]['time'] = '';
        $i++;
    }

    return $ret;
}
