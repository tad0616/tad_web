<?php

use XoopsModules\Tadtools\Utility;

require_once __DIR__ . '/header.php';

$from = isset($_GET['from']) ? (int) $_GET['from'] : 0;

$sql = 'select WebID from xx_tad_web';
$result = $xoopsDB->query($sql) or Utility::web_error($sql, __FILE__, __LINE__);
while (list($WebID) = $xoopsDB->fetchRow($result)) {
    $web[] = $WebID;
}

sort($web);
// $firstKey = array_key_first($web);
// $min = $web[$firstKey];
$lastKey = array_key_last($web);
$max = $web[$lastKey];

for ($i = $from; $i < $max; $i++) {
    if (in_array($i, $web)) {

        $bg_user_path = XOOPS_ROOT_PATH . "/uploads/tad_web/{$i}/bg";
        $TadUpFilesBg = TadUpFilesBg($i);
        fixed_img($bg_user_path, 'bg', $i, $TadUpFilesBg);

        $head_user_path = XOOPS_ROOT_PATH . "/uploads/tad_web/{$i}/head";
        $TadUpFilesHead = TadUpFilesHead($i);
        fixed_img($head_user_path, 'head', $i, $TadUpFilesHead);

        echo "<div>{$i} 已清除</div>";
    } else {
        $sql = "delete from xx_tad_web_files_center where `col_sn` = '$i' AND (`col_name` = 'bg' or `col_name` = 'head')";
        $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);
        echo "<div style='color:red;'>{$i} 不存在，已刪除</div>";
    }
}
