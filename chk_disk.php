<?php
include_once "header.php";

$dir    = XOOPS_ROOT_PATH . "/uploads/tad_web/";
$web    = [];
$sql    = "select WebID from xx_tad_web";
$result = $xoopsDB->query($sql) or web_error($sql, __FILE__, _LINE__);
while (list($WebID) = $xoopsDB->fetchRow($result)) {
    $web[] = $WebID;
}
$bad = $no = 0;
// Open a known directory, and proceed to read its contents
if (is_dir($dir)) {
    if ($dh = opendir($dir)) {
        while (($file = readdir($dh)) !== false) {
            $type = filetype($dir . $file);

            if ($type == "dir" and substr($file, 0, 1) != '.' and $file != 0) {
                $clean_dir = (int) $file;
                if (XOOPS_ROOT_PATH . "/uploads/tad_web/{$clean_dir}" != $dir . $file) {
                    $del   = $dir . $file . "無效資料夾";
                    $color = 'red';
                    $bad++;
                    delete_directory($dir . $file);
                } elseif (!in_array($clean_dir, $web)) {
                    $del   = $dir . $file . "不存在的網站";
                    $color = 'blue';
                    $no++;
                    delete_directory($dir . $file);
                } else {
                    continue;
                }
                echo "<div style='color: $color;'>$file (" . filetype($dir . $file) . ") {$del}</div>";
            }
        }
        closedir($dh);
    }
}
echo "無效資料夾 $bad 個，不存在網站 $no 個";

function delete_directory($dirname)
{
    if (is_dir($dirname)) {
        $dir_handle = opendir($dirname);
    }

    if (!$dir_handle) {
        return false;
    }

    while ($file = readdir($dir_handle)) {
        if ($file !== '.' && $file !== '..') {
            if (!is_dir($dirname . '/' . $file)) {
                unlink($dirname . '/' . $file);
            } else {
                delete_directory($dirname . '/' . $file);
            }
        }
    }
    closedir($dir_handle);
    rmdir($dirname);
    return true;
}
