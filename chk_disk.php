<?php

use XoopsModules\Tadtools\Utility;

require_once __DIR__ . '/header.php';
$all_web = [];
$sql = 'SELECT `WebID` FROM `' . $xoopsDB->prefix('tad_web') . '`';
$result = Utility::query($sql) or Utility::web_error($sql, __FILE__, __LINE__);

while (list($WebID) = $xoopsDB->fetchRow($result)) {
    $all_web[$WebID] = $WebID;
}

$dir = XOOPS_ROOT_PATH . '/uploads/tad_web/';
$dir2 = XOOPS_VAR_PATH . "/tad_web/";
clean_dir($dir, 'tmp');
clean_dir($dir2, 'my_webs_data');

function clean_dir($path, $ok_dir)
{
    global $all_web;
    $directories = [];
// 確認目錄是否存在
    if (is_dir($path)) {
        // 掃描目錄內容
        $items = scandir($path);

        foreach ($items as $item) {
            // 忽略當前目錄 (.) 和上層目錄 (..)
            if ($item != '.' && $item != '..') {
                // 確認該項目是目錄
                if (is_dir($path . $item)) {
                    $directories[] = $item;
                }
            }
        }

        // 列出所有目錄
        $del = $count = 0;
        echo '<h3>Directories in ' . $path . ':</h3>';
        foreach ($directories as $directory) {
            $count++;
            if (!in_array($directory, $all_web) && $directory != $ok_dir) {
                $ok = deleteDirectory($path . $directory) ? " (已刪)" : "";
                echo "<span style='color:red'>{$directory}{$ok}</span>, ";
                $del++;
                // } else {
                //     echo "<span>$directory</span>, ";
            }
        }
        echo "<div>現有目錄共 $count 個，可刪除目錄共 $del 個</div>";
    } else {
        echo 'Directory does not exist.';
    }
}

function deleteDirectory($dir)
{
    // 確認目錄是否存在
    if (!file_exists($dir)) {
        return true; // 目錄不存在，視為成功
    }

    // 如果不是目錄，則直接刪除文件
    if (!is_dir($dir)) {
        return unlink($dir); // 刪除文件
    }

    // 使用 scandir 列出目錄下的所有文件和子目錄
    $items = scandir($dir);
    foreach ($items as $item) {
        // 忽略當前目錄 (.) 和上層目錄 (..)
        if ($item == '.' || $item == '..') {
            continue;
        }

        // 遞迴刪除子目錄或文件
        if (!deleteDirectory($dir . DIRECTORY_SEPARATOR . $item)) {
            return false; // 如果有一個刪除失敗，則返回 false
        }
    }

    // 刪除當前目錄
    return rmdir($dir);
}
