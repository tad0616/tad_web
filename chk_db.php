<?php

// use XoopsModules\Tadtools\Utility;

// require_once __DIR__ . '/header.php';

// $from = isset($_GET['from']) ? (int) $_GET['from'] : 0;

// $sql = 'SELECT `WebID` FROM `' . $xoopsDB->prefix('tad_web') . '`';
// $result = Utility::query($sql) or Utility::web_error($sql, __FILE__, __LINE__);

// while (list($WebID) = $xoopsDB->fetchRow($result)) {
//     $web[] = $WebID;
// }

// sort($web);
// // $firstKey = array_key_first($web);
// // $min = $web[$firstKey];
// $lastKey = array_key_last($web);
// $max = $web[$lastKey];

// for ($i = $from; $i < $max; $i++) {
//     if (in_array($i, $web)) {

//         $bg_user_path = XOOPS_ROOT_PATH . "/uploads/tad_web/{$i}/bg";
//         $TadUpFilesBg = TadUpFilesBg($i);
//         fixed_img($TadUpFilesBg, $bg_user_path, 'bg', $i);

//         $head_user_path = XOOPS_ROOT_PATH . "/uploads/tad_web/{$i}/head";
//         $TadUpFilesHead = TadUpFilesHead($i);
//         fixed_img($TadUpFilesHead, $head_user_path, 'head', $i);

//         echo "<div>{$i} 已清除</div>";
//     } else {
// $sql = 'DELETE FROM `' . $xoopsDB->prefix('tad_web_files_center') . '` WHERE `col_sn` = ? AND (`col_name` = ? OR `col_name` = ?)';
// Utility::query($sql, 'iss', [$i, 'bg', 'head']) or Utility::web_error($sql, __FILE__, __LINE__);
//         echo "<div style='color:red;'>{$i} 不存在，已刪除</div>";
//     }
// }
