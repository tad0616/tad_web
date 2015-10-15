<?php

//區塊主函式 (班級選單(tad_web_discuss))
function tad_web_discuss($options)
{
    global $xoopsDB;

    $sql    = "select * from " . $xoopsDB->prefix("tad_web_discuss") . " where ReDiscussID='0'  order by LastTime desc limit 0,10";
    $result = $xoopsDB->query($sql) or web_error($sql);

    $main_data = "";

    while ($all = $xoopsDB->fetchArray($result)) {
        //以下會產生這些變數： $DiscussID , $ReDiscussID , $uid , $DiscussTitle , $DiscussContent , $DiscussDate , $WebID , $LastTime , $DiscussCounter
        foreach ($all as $k => $v) {
            $$k = $v;
        }

        $renum       = get_block_re_num($DiscussID);
        $show_re_num = empty($renum) ? "" : "（{$renum}）";

        $LastTime = substr($LastTime, 0, 10);

        $main_data .= "<tr>
        <td><img src='images/right_icon4.png' width='6' height='10' hspace=4  /><a href='discuss.php?WebID=$WebID&DiscussID=$DiscussID'>{$DiscussTitle}</a>{$show_re_num}</td>
        </tr>";
    }
    if (empty($main_data)) {
        $main_data = "<tr><td colspan=4 class='c'>" . _MB_TCW_EMPTY_DISCUSS . "</td></tr>";
    }

    $block['main_data'] = $main_data;
    if (!empty($_GET['WebID'])) {
        $block['row']  = 'row';
        $block['span'] = 'col-md-';
    } else {
        $block['row']  = $_SESSION['web_bootstrap'] == '3' ? 'row' : 'row-fluid';
        $block['span'] = $_SESSION['web_bootstrap'] == '3' ? 'col-md-' : 'span';
    }
    return $block;
}

//取得回覆數量
function get_block_re_num($DiscussID = "")
{
    global $xoopsDB, $xoopsUser;
    if (empty($DiscussID)) {
        return 0;
    }

    $sql           = "select count(*) from " . $xoopsDB->prefix("tad_web_discuss") . " where ReDiscussID='$DiscussID'";
    $result        = $xoopsDB->query($sql) or web_error($sql);
    list($counter) = $xoopsDB->fetchRow($result);
    return $counter;
}
