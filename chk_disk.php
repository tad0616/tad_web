<?php

use XoopsModules\Tadtools\Utility;

require_once __DIR__ . '/header.php';

$from = isset($_GET['from']) ? (int) $_GET['from'] : 0;
$dir = XOOPS_ROOT_PATH . '/uploads/tad_web/';
$web = [];
$sql = 'select WebID from xx_tad_web';
$result = $xoopsDB->query($sql) or Utility::web_error($sql, __FILE__, __LINE__);
while (list($WebID) = $xoopsDB->fetchRow($result)) {
    $web[] = $WebID;
}

if (!function_exists("array_key_last")) {
    function array_key_last($array)
    {
        if (!is_array($array) || empty($array)) {
            return null;
        }

        return array_keys($array)[count($array) - 1];
    }
}

$bad = $no = 0;
// Open a known directory, and proceed to read its contents
if (is_dir($dir)) {
    if ($dh = opendir($dir)) {
        while (false !== ($file = readdir($dh))) {
            $type = filetype($dir . $file);

            if ('dir' === $type and '.' !== substr($file, 0, 1) and 0 !== $file) {
                $clean_dir = $file;
                if (XOOPS_ROOT_PATH . "/uploads/tad_web/{$clean_dir}" != $dir . $file) {
                    $del = $dir . $file . '無效資料夾';
                    $color = 'red';
                    $bad++;
                    Utility::delete_directory($dir . $file);
                    echo "<div style='color: $color;'>$file (" . filetype($dir . $file) . ") {$del}</div>";
                } elseif (!in_array($clean_dir, $web)) {
                    $del = $dir . $file . '不存在的網站';
                    $color = 'blue';
                    $no++;
                    Utility::delete_directory($dir . $file);
                    echo "<div style='color: $color;'>$file (" . filetype($dir . $file) . ") {$del}</div>";
                } else {
                    echo "<div style='color: #000;'>$file (" . filetype($dir . $file) . ") OK</div>";
                }

            }
        }
        closedir($dh);
    }
}
echo "無效資料夾 $bad 個，不存在網站 $no 個";

sort($web);
if (is_array($web)) {
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

            echo "<div>{$i} OK</div>";
        } else {
            $sql = "delete from xx_tad_web_files_center where `col_sn` = '$i' AND (`col_name` = 'bg' or `col_name` = 'head')";
            $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);
            echo "<div style='color:red;'>{$i} 不存在，已刪除</div>";
        }
    }
}
