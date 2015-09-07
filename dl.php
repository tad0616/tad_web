<?php
include_once "header.php";
include_once "upfile.php";

$files_sn = (empty($_REQUEST['files_sn'])) ? "" : intval($_REQUEST['files_sn']);

add_file_counter($files_sn);

//下載並新增計數器
function add_file_counter($files_sn = "")
{
    global $xoopsDB;

    $file = upfile::get_one_file($files_sn);

    $file_type     = $file['file_type'];
    $file_size     = $file['file_size'];
    $real_filename = $file['description'];

    $sql = "update " . $xoopsDB->prefix("tad_web_files_center") . " set `counter`=`counter`+1 where `files_sn`='{$files_sn}'";
    $xoopsDB->queryF($sql) or redirect_header($_SERVER['PHP_SELF'], 3, mysql_error());

    if ($file['kind'] == "img") {
        //header("location:"._FILES_CENTER_IMAGE_URL."/{$file['file_name']}");
        $file_saved = _FILES_CENTER_IMAGE_URL . "/{$file['file_name']}";
    } else {
        //header("location:"._FILES_CENTER_URL."/{$file['file_name']}");
        $file_saved = _FILES_CENTER_URL . "/{$file['file_name']}";
    }

    $file_display = mb_convert_encoding($real_filename, "BIG-5", "UTF-8");
    $mimetype     = $file_type;
    if (function_exists('mb_http_output')) {
        mb_http_output('pass');
    }

    header('Expires: 0');
    header('Content-Type: ' . $mimetype);
    if (preg_match("/MSIE ([0-9]\.[0-9]{1,2})/", $HTTP_USER_AGENT)) {
        header('Content-Disposition: inline; filename="' . $file_display . '"');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Pragma: public');
    } else {
        header('Content-Disposition: attachment; filename="' . $file_display . '"');
        header('Pragma: no-cache');
    }
    header("Content-Type: application/force-download");
    header("Content-Transfer-Encoding: binary");
    $handle = fopen($file_saved, "rb");
    while (!feof($handle)) {
        $buffer = fread($handle, 4096);
        echo $buffer;
    }
    fclose($handle);
}
