<?php
//搜尋
function menu_search($WebID, $queryarray, $limit = 10)
{
    global $xoopsDB;

    //起始函數
    include_once XOOPS_ROOT_PATH . "/class/power.php";
    $power = new power($WebID);

    $plugin      = 'menu';
    $plugin_tbl  = 'tad_web_menu';
    $id_col      = 'MenuID';
    $title_col   = 'MenuName';
    $date_col    = 'MenuDate';
    $content_col = 'MenuDesc';

    $myts = &MyTextSanitizer::getInstance();
    foreach ($queryarray as $k => $v) {
        $arr[$k] = $myts->addSlashes($v);
    }
    $queryarray = $arr;

    // die(var_export($queryarray));
    $sql = "SELECT `{$id_col}`,`{$title_col}`,`{$date_col}`, `WebID` FROM " . $xoopsDB->prefix($plugin_tbl) . " WHERE 1";

    if (is_array($queryarray) && $count = count($queryarray)) {
        $sql .= " AND ((`{$title_col}` LIKE '%{$queryarray[0]}%'  OR `{$content_col}` LIKE '%{$queryarray[0]}%' )";
        for ($i = 1; $i < $count; $i++) {
            $sql .= " $andor ";
            $sql .= "(`{$title_col}` LIKE '%{$queryarray[$i]}%' OR  `{$content_col}` LIKE '%{$queryarray[$i]}%' )";
        }
        $sql .= ") ";
    }
    $sql .= " ORDER BY  `{$date_col}` DESC";

    $result = $xoopsDB->query($sql, $limit);
    $ret    = array();
    $i      = 0;
    while ($myrow = $xoopsDB->fetchArray($result)) {
        $power_result = $power->check_power("read", $id_col, $myrow[$id_col]);
        if (!$power_result) {
            continue;
        }
        $ret[$i]['link']  = "{$plugin}.php?WebID=" . $myrow['WebID'] . "&{$id_col}=" . $myrow[$id_col];
        $ret[$i]['title'] = $myrow[$title_col];
        $ret[$i]['time']  = substr($myrow[$date_col], 0, 10);
        $i++;
    }
    return $ret;
}