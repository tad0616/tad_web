<?php
/*-----------引入檔案區--------------*/
$xoopsOption['template_main'] = "tad_web_adm_disk.tpl";
include_once 'header.php';
include_once "../function.php";
include_once "../class/cate.php";
/*-----------function區--------------*/

//取得所有班級
function list_all_web($defCateID = '')
{
    global $xoopsDB, $xoopsTpl, $xoopsModuleConfig;

    $sql = "SELECT * FROM " . $xoopsDB->prefix("tad_web") . "  ORDER BY used_size DESC";

    //getPageBar($原sql語法, 每頁顯示幾筆資料, 最多顯示幾個頁數選項);
    $PageBar = getPageBar($sql, 50, 10);
    $bar     = $PageBar['bar'];
    $sql     = $PageBar['sql'];
    $total   = $PageBar['total'];

    $result            = $xoopsDB->query($sql) or web_error($sql);
    $_SESSION['quota'] = '';
    $data              = array();
    $dir               = XOOPS_ROOT_PATH . "/uploads/tad_web/";

    $user_default_quota = empty($xoopsModuleConfig['user_space_quota']) ? 1 : (int)$xoopsModuleConfig['user_space_quota'];
    while ($all = $xoopsDB->fetchArray($result)) {
        //以下會產生這些變數： $WebID , $WebName , $WebSort , $WebEnable , $WebCounter
        $WebID    = $all['WebID'];
        $dir_size = $all['used_size'];
        // $dir_size = get_dir_size("{$dir}{$WebID}/");

        $data[$WebID] = $all;
        $size         = size2mb($dir_size);
        // save_web_config("used_size", $size, $WebID);

        $space_quota      = get_web_config("space_quota", $WebID);
        $user_space_quota = (empty($space_quota) or $space_quota == 'default') ? $user_default_quota : (int)$space_quota;

        $data[$WebID]['space_quota']     = $user_space_quota;
        $data[$WebID]['disk_used_space'] = $size;
        $data[$WebID]['disk_space']      = "{$dir}{$WebID}/";
        $data[$WebID]['memAmount']       = memAmount($WebID);
        $data[$WebID]['uname']           = XoopsUser::getUnameFromId($all['WebOwnerUid'], 0);
        $percentage                      = round(($size / $user_space_quota), 2) * 100;
        $data[$WebID]['quota']           = $percentage;
        if ($percentage <= 70) {
            $data[$WebID]['progress_color'] = 'success';
        } elseif ($percentage <= 90) {
            $data[$WebID]['progress_color'] = 'warning';
        } elseif ($percentage > 90) {
            $data[$WebID]['progress_color'] = 'danger';
        }

        $space[$WebID] = $dir_size;
    }

    //sort($space);
    // arsort($space);
    $xoopsTpl->assign('bar', $bar);
    $xoopsTpl->assign('data', $data);
    $xoopsTpl->assign('space', $space);
    $xoopsTpl->assign('free_space', get_free_space());

    $xoopsTpl->assign('total_space', roundsize(get_all_dir_size()));
    $xoopsTpl->assign('user_space_quota', $xoopsModuleConfig['user_space_quota']);
}

function get_all_dir_size()
{
    global $xoopsDB;
    $sql             = "SELECT sum(`used_size`) FROM " . $xoopsDB->prefix("tad_web") . " ";
    $result          = $xoopsDB->query($sql) or web_error($sql);
    list($used_size) = $xoopsDB->fetchRow($result);
    return $used_size;
}

//目前硬碟空間
function get_free_space()
{
    $bytes     = disk_free_space(".");
    $si_prefix = array('B', 'KB', 'MB', 'GB', 'TB', 'EB', 'ZB', 'YB');
    $base      = 1024;
    $class     = min((int) log($bytes, $base), count($si_prefix) - 1);
    $space     = sprintf('%1.2f', $bytes / pow($base, $class)) . ' ' . $si_prefix[$class];
    return $space;
}

function view_file($WebID = '')
{
    global $xoopsTpl;
    $dir = XOOPS_ROOT_PATH . "/uploads/tad_web/{$WebID}";
    // $files = dirToArray($dir);

    $json = "{ id:0, pId:0, name:'{$dir}', url:'', target:'_self', open:'true'}, \n";
    $json .= dirToJson($dir);
    // die($json);
    if (!file_exists(XOOPS_ROOT_PATH . "/modules/tadtools/ztree.php")) {
        redirect_header("index.php", 3, _MA_NEED_TADTOOLS);
    }
    include_once XOOPS_ROOT_PATH . "/modules/tadtools/ztree.php";
    $ztree      = new ztree("link_tree", $json, "save_drag.php", "save_sort.php", "of_cate_sn", "cate_sn");
    $ztree_code = $ztree->render();
    $xoopsTpl->assign('ztree_code', $ztree_code);

    // $xoopsTpl->assign('files', $files);
    $xoopsTpl->assign('dir', $dir);
}

function dirToJson($dir, $i = 1, $j = 0)
{
    $data   = "";
    $result = array();

    $cdir = scandir($dir);
    foreach ($cdir as $key => $value) {
        if (!in_array($value, array(".", ".."))) {
            if (is_dir($dir . DIRECTORY_SEPARATOR . $value)) {
                $data .= "{ id:{$i}, pId:{$j}, name:'{$dir}/{$value}', url:'{$url}', target:'_self', open:'true'}, \n";
                $data .= dirToJson($dir . DIRECTORY_SEPARATOR . $value, $i, $i);
            } else {
                $filesize = filesize($dir . '/' . $value);
                $size     = roundsize($filesize);
                $unit     = substr($size, -2);
                $url      = str_replace(XOOPS_ROOT_PATH, XOOPS_URL, $dir . '/' . $value);

                $font_style = ", font:{'color':'#89A3C4'}";
                if ($filesize >= 1073741824) {
                    $font_style = ", font:{'background-color':'#FF0000', 'color':'black'}";
                } elseif ($filesize >= 104857600) {
                    $font_style = ", font:{'background-color':'#F9BBBB', 'color':'black'}";
                } elseif ($filesize >= 10485760) {
                    $font_style = ", font:{'background-color':'#FA6CFC', 'color':'black'}";
                } elseif ($filesize >= 1048576) {
                    $font_style = ", font:{'background-color':'#F6ADF7', 'color':'black'}";
                } elseif ($filesize >= 512000) {
                    $font_style = ", font:{'background-color':'#FFFB1C', 'color':'black'}";
                } elseif ($filesize >= 102400) {
                    $font_style = ", font:{'background-color':'#EFF9E0', 'color':'black'}";
                }

                $data .= "{ id:{$i}, pId:{$j}, name:'{$dir}/{$value} ({$size})', url:'{$url}', target:'_blank', open:'true' $font_style}, \n";
            }
        }
        $i++;
    }

    return $data;
}

function dirToArray($dir)
{
    $result = array();
    $i      = 0;

    $cdir = scandir($dir);
    foreach ($cdir as $key => $value) {
        if (!in_array($value, array(".", ".."))) {
            if (is_dir($dir . DIRECTORY_SEPARATOR . $value)) {
                $result[$value]['name'] = dirToArray($dir . DIRECTORY_SEPARATOR . $value);
                $result[$value]['size'] = '';
            } else {
                $result[$i]['name'] = $value;
                $size               = roundsize(filesize($dir . '/' . $value));
                $unit               = substr($size, -2);
                $result[$i]['size'] = $size;
                $result[$i]['url']  = str_replace(XOOPS_ROOT_PATH, XOOPS_URL, $dir . '/' . $value);
                if ($unit == "MB") {
                    $result[$i]['color'] = 'red';
                } elseif ($unit == "GB") {
                    $result[$i]['color'] = '#C32DCE';
                } else {
                    $result[$i]['color'] = '#afafaf';
                }
            }
        }
        $i++;
    }

    return $result;
}

function save_disk_setup()
{
    global $xoopsDB, $xoopsTpl, $xoopsModuleConfig;
    foreach ($_POST['space_quota'] as $WebID => $user_space_quota) {
        $space_quota = ($user_space_quota == $xoopsModuleConfig['user_space_quota']) ? 'default' : (int)$user_space_quota;
        save_web_config("space_quota", $space_quota, $WebID);
    }
}

/*-----------執行動作判斷區----------*/
include_once $GLOBALS['xoops']->path('/modules/system/include/functions.php');
$op     = system_CleanVars($_REQUEST, 'op', '', 'string');
$WebID  = system_CleanVars($_REQUEST, 'WebID', 0, 'int');
$CateID = system_CleanVars($_REQUEST, 'CateID', 0, 'int');

$xoopsTpl->assign('op', $op);

switch ($op) {
    /*---判斷動作請貼在下方---*/

    case "view_file":
        view_file($WebID);
        break;

    case "save_disk_setup":
        save_disk_setup();
        header("location: {$_SERVER['PHP_SELF']}");
        exit;
        break;

    //預設動作
    default:
        list_all_web($CateID);
        break;

        /*---判斷動作請貼在上方---*/
}

/*-----------秀出結果區--------------*/
include_once 'footer.php';
