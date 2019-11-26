<?php
$WebID = (int) $_GET['WebID'];

$dir = "/var/www/class/html/uploads/tad_web/$WebID/image/";

// Open a known directory, and proceed to read its contents
if (is_dir($dir)) {
    if ($dh = opendir($dir)) {
        while (($file = readdir($dh)) !== false) {
            $pic = $dir . $file;
            $filesize = filesize($pic);
            $mime_content_type = mime_content_type($pic);
            if ($filesize > 409600) {
                thumbnail($pic, $pic, $mime_content_type, 800);
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
            $mime_content_type = mime_content_type($pic);
            if ($filesize > 40960) {
                thumbnail($pic, $pic, $mime_content_type, 240);
                echo "<div>$pic => $filesize</div>";
            }
        }
        closedir($dh);
    }
}

function thumbnail($filename = '', $thumb_name = '', $type = 'image/jpeg', $width = '240', $angle = 0)
{
    set_time_limit(0);
    ini_set('memory_limit', '300M');
    // Get new sizes
    list($old_width, $old_height) = getimagesize($filename);

    if (0 != $angle) {
        $h = $old_height;
        $w = $old_width;

        $old_width = $h;
        $old_height = $w;
    }

    // die("$old_width, $old_height");

    if ($old_width > $width) {
        $percent = ($old_width > $old_height) ? round($width / $old_width, 2) : round($width / $old_height, 2);

        $newwidth = ($old_width > $old_height) ? $width : $old_width * $percent;
        $newheight = ($old_width > $old_height) ? $old_height * $percent : $width;

        // Load
        $thumb = imagecreatetruecolor($newwidth, $newheight);
        ob_start();
        if ('image/jpeg' === $type or 'image/jpg' === $type or 'image/pjpg' === $type or 'image/pjpeg' === $type) {
            $source = imagecreatefromjpeg($filename);

            $type = 'image/jpeg';
        } elseif ('image/png' === $type) {
            $source = imagecreatefrompng($filename);
            $type = 'image/png';
        } elseif ('image/gif' === $type) {
            $source = imagecreatefromgif($filename);
            $type = 'image/gif';
        }
        if (0 != $angle) {
            $source = imagerotate($source, $angle, 0);
        }
        // Resize
        imagecopyresampled($thumb, $source, 0, 0, 0, 0, $newwidth, $newheight, $old_width, $old_height);

        // header("Content-type: $type");
        if ('image/jpeg' === $type) {
            imagejpeg($thumb, $thumb_name);
        } elseif ('image/png' === $type) {
            imagepng($thumb, $thumb_name);
        } elseif ('image/gif' === $type) {
            imagegif($thumb, $thumb_name);
        }
        ob_end_clean();
        return;
    }
}
