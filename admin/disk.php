<?php
/*-----------引入檔案區--------------*/
$xoopsOption['template_main'] = "tad_web_adm_disk.html";
include_once 'header.php';
include_once "../function.php";
include_once "../class/cate.php";
/*-----------function區--------------*/

//取得所有班級
function list_all_web($defCateID = '')
{
    global $xoopsDB, $xoopsTpl;

    $sql = "select * from " . $xoopsDB->prefix("tad_web") . "  order by WebSort";

    $result = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'], 3, mysql_error());

    $data = "";
    $dir  = XOOPS_ROOT_PATH . "/uploads/tad_web/";

    while ($all = $xoopsDB->fetchArray($result)) {
        //以下會產生這些變數： $WebID , $WebName , $WebSort , $WebEnable , $WebCounter
        $WebID = $all['WebID'];

        $dir_size = get_dir_size("{$dir}{$WebID}/");

        $data[$WebID]                     = $all;
        $data[$WebID]['disk_total_space'] = roundsize($dir_size);
        $data[$WebID]['disk_space']       = "{$dir}{$WebID}/";
        $data[$WebID]['memAmount']        = memAmount($WebID);
        $data[$WebID]['uname']            = XoopsUser::getUnameFromId($all['WebOwnerUid'], 0);

        $space[$WebID] = $dir_size;
        $i++;
    }

    //sort($space);
    arsort($space);
    $xoopsTpl->assign('WebYear', $WebYear);
    $xoopsTpl->assign('data', $data);
    $xoopsTpl->assign('space', $space);

}

function get_dir_size($dir_name)
{
    $dir_size = 0;
    if (is_dir($dir_name)) {
        if ($dh = opendir($dir_name)) {
            while (($file = readdir($dh)) !== false) {
                if ($file != "." && $file != "..") {
                    if (is_file($dir_name . "/" . $file)) {
                        $dir_size += filesize($dir_name . "/" . $file);
                    }
                    /* check for any new directory inside this directory */
                    if (is_dir($dir_name . "/" . $file)) {
                        $dir_size += get_dir_size($dir_name . "/" . $file);
                    }
                }
            }
        }
    }
    closedir($dh);
    return $dir_size;
}

function roundsize($size)
{
    $i   = 0;
    $iec = array("B", "Kb", "Mb", "Gb", "Tb");
    while (($size / 1024) > 1) {
        $size = $size / 1024;
        $i++;}
    return (round($size, 1) . " " . $iec[$i]);
}

/*-----------執行動作判斷區----------*/
include_once $GLOBALS['xoops']->path('/modules/system/include/functions.php');
$op     = system_CleanVars($_REQUEST, 'op', '', 'string');
$WebID  = system_CleanVars($_REQUEST, 'WebID', 0, 'int');
$CateID = system_CleanVars($_REQUEST, 'CateID', 0, 'int');

$xoopsTpl->assign('op', $_REQUEST['op']);

switch ($op) {
    /*---判斷動作請貼在下方---*/

    //預設動作
    default:
        list_all_web($CateID);
        break;

        /*---判斷動作請貼在上方---*/
}

/*-----------秀出結果區--------------*/
include_once 'footer.php';
