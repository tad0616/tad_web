<?php
use XoopsModules\Tadtools\Utility;
function list_page($WebID, $config = [])
{
    global $xoopsDB, $xoopsTpl, $TadUpFiles;
    if (empty($WebID)) {
        return;
    }
    require_once __DIR__ . '/class.php';

    $tad_web_page = new tad_web_page($WebID);
    $limit = isset($config['limit']) ? $config['limit'] : '';
    $show_count = isset($config['show_count']) ? $config['show_count'] : '';
    $block = $tad_web_page->list_all($config['CateID'], $limit, 'return', '', $show_count);

    return $block;
}

function page_menu($WebID, $config = [])
{
    global $xoopsDB;
    if (empty($WebID)) {
        return;
    }

    $limit = (isset($config['limit']) and !empty($config['limit'])) ? "LIMIT 0, {$config['limit']}" : '';
    $show_count = isset($config['show_count']) ? $config['show_count'] : '';

    $sql = 'SELECT `CateName`, `CateID`
    FROM `' . $xoopsDB->prefix('tad_web_cate') . '`
    WHERE `WebID` =? AND `ColName` = ? AND `CateEnable` = ?
    ORDER BY `CateSort` ' . $limit;

    $result = Utility::query($sql, 'iss', [$WebID, 'page', '1']) or Utility::web_error($sql, __FILE__, __LINE__);

    $main = [];

    $i = 0;

    while (list($CateName, $CateID) = $xoopsDB->fetchRow($result)) {
        $sql2 = 'SELECT `PageID`, `PageTitle`, `PageCount` FROM `' . $xoopsDB->prefix('tad_web_page') . '` WHERE `CateID` = ? ORDER BY `PageSort`';
        $result2 = Utility::query($sql2, 'i', [$CateID]) or Utility::web_error($sql2);
        $content = [];
        $j = 0;
        while (list($PageID, $PageTitle, $PageCount) = $xoopsDB->fetchRow($result2)) {
            $content[$j]['PageCount'] = $PageCount;
            $content[$j]['WebID'] = $WebID;
            $content[$j]['PageID'] = $PageID;
            $content[$j]['PageTitle'] = $PageTitle;
            $j++;
        }

        $main[$i]['CateID'] = $CateID;
        $main[$i]['CateName'] = $CateName;
        $main[$i]['CateAmount'] = $j;
        $main[$i]['content'] = $content;
        $main[$i]['show_count'] = $show_count;
        $i++;
    }
    $block['main_data'] = true;
    $block['page_list'] = $main;

    return $block;
}
