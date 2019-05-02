<?php
use XoopsModules\Tadtools\Utility;

include_once 'header.php';

$dir = XOOPS_ROOT_PATH . '/uploads/tad_web/';
$web = [];
$sql = 'select WebID from xx_tad_web';
$result = $xoopsDB->query($sql) or Utility::web_error($sql, __FILE__, __LINE__);
while (list($WebID) = $xoopsDB->fetchRow($result)) {
    $web[] = $WebID;
}
$bad = $no = 0;
// Open a known directory, and proceed to read its contents
if (is_dir($dir)) {
    if ($dh = opendir($dir)) {
        while (false !== ($file = readdir($dh))) {
            $type = filetype($dir . $file);

            if ('dir' === $type and '.' !== mb_substr($file, 0, 1) and 0 != $file) {
                $clean_dir = (int) $file;
                if (XOOPS_ROOT_PATH . "/uploads/tad_web/{$clean_dir}" != $dir . $file) {
                    $del = $dir . $file . '無效資料夾';
                    $color = 'red';
                    $bad++;
                    Utility::delete_directory($dir . $file);
                } elseif (!in_array($clean_dir, $web)) {
                    $del = $dir . $file . '不存在的網站';
                    $color = 'blue';
                    $no++;
                    Utility::delete_directory($dir . $file);
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
