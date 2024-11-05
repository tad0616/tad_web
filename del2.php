<?php
use XoopsModules\Tadtools\Utility;
$WebID = (int) $_GET['WebID'];

$dir = "/var/www/class/html/uploads/tad_web/$WebID/image/";

// Open a known directory, and proceed to read its contents
if (is_dir($dir)) {
    if ($dh = opendir($dir)) {
        while (($file = readdir($dh)) !== false) {
            $pic = $dir . $file;
            $filesize = filesize($pic);
            if ($filesize > 409600) {
                Utility::generateThumbnail($pic, $pic, 800);
                echo "<div>$pic => $filesize</div>";
            }
        }
        closedir($dh);
    }
}

$dir2 = "/var/www/class/html/uploads/tad_web/$WebID/image/.thumbs/";
if (is_dir($dir2)) {
    if ($dh = opendir($dir2)) {
        while (($file = readdir($dh)) !== false) {
            $pic = $dir2 . $file;
            $filesize = filesize($pic);
            if ($filesize > 40960) {
                Utility::generateThumbnail($pic, $pic, 240);
                echo "<div>$pic => $filesize</div>";
            }
        }
        closedir($dh);
    }
}
