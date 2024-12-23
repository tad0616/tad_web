<?php
use Xmf\Request;
use XoopsModules\Tadtools\Utility;
use XoopsModules\Tadtools\Ztree;
use XoopsModules\Tad_web\Tools as TadWebTools;

/*-----------引入檔案區--------------*/
$xoopsOption['template_main'] = 'tad_web_adm_disk.tpl';
require_once __DIR__ . '/header.php';
require_once dirname(__DIR__) . '/function.php';
require_once dirname(__DIR__) . '/class/WebCate.php';

/*-----------執行動作判斷區----------*/
$op = Request::getString('op');
$WebID = Request::getInt('WebID');
$CateID = Request::getInt('CateID');
$g2p = Request::getInt('g2p', 1);

$xoopsTpl->assign('op', $op);

switch ($op) {

    //重新計算空間
    case 'check_quota':
        check_quota($WebID);
        header("location: {$_SERVER['PHP_SELF']}?WebID=$WebID&g2p=$g2p");
        exit;

    case 'view_file':
        view_file($WebID);
        break;

    case 'save_disk_setup':
        save_disk_setup();
        header("location: {$_SERVER['PHP_SELF']}?g2p=$g2p");
        exit;

    //預設動作
    default:
        list_all_web($WebID);
        break;

}

/*-----------秀出結果區--------------*/
require_once __DIR__ . '/footer.php';

/*-----------function區--------------*/

//取得所有班級
function list_all_web($defWebID = '')
{
    global $xoopsDB, $xoopsTpl, $xoopsModuleConfig;

    if ($defWebID) {
        $sql = "SELECT * FROM " . $xoopsDB->prefix('tad_web') . " where WebID='$defWebID'";
    } else {
        $sql = 'SELECT * FROM ' . $xoopsDB->prefix('tad_web') . '  ORDER BY used_size DESC';

        //getPageBar($原sql語法, 每頁顯示幾筆資料, 最多顯示幾個頁數選項);
        $PageBar = Utility::getPageBar($sql, 50, 10);
        $bar = $PageBar['bar'];
        $sql = $PageBar['sql'];
        $total = $PageBar['total'];
    }

    $result = $xoopsDB->query($sql) or Utility::web_error($sql, __FILE__, __LINE__);
    $_SESSION['quota'] = '';
    $data = [];
    $dir = XOOPS_ROOT_PATH . '/uploads/tad_web/';

    $user_default_quota = empty($xoopsModuleConfig['user_space_quota']) ? 1 : (int) $xoopsModuleConfig['user_space_quota'];
    while (false !== ($all = $xoopsDB->fetchArray($result))) {
        //以下會產生這些變數： $WebID , $WebName , $WebSort , $WebEnable , $WebCounter
        $WebID = $all['WebID'];
        if (_IS_EZCLASS) {
            $used_size = redis_do($WebID, 'get', '', 'used_size');
            $dir_size = $used_size;
        } else {
            $dir_size = $all['used_size'];
        }
        // $dir_size = get_dir_size("{$dir}{$WebID}/");

        $data[$WebID] = $all;
        $size = size2mb($dir_size);

        $space_quota = TadWebTools::get_web_config('space_quota', $WebID);
        $user_space_quota = (empty($space_quota) or 'default' === $space_quota) ? $user_default_quota : (int) $space_quota;

        $data[$WebID]['space_quota'] = $user_space_quota;
        $data[$WebID]['disk_used_space'] = $size;
        $data[$WebID]['disk_space'] = "{$dir}{$WebID}/";
        $data[$WebID]['memAmount'] = memAmount($WebID);
        $data[$WebID]['uname'] = \XoopsUser::getUnameFromId($all['WebOwnerUid'], 0);
        $percentage = round(($size / $user_space_quota), 2) * 100;
        $data[$WebID]['quota'] = $percentage;
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
    $sql = 'SELECT SUM(`used_size`) FROM `' . $xoopsDB->prefix('tad_web') . '`';
    $result = Utility::query($sql) or Utility::web_error($sql, __FILE__, __LINE__);
    list($used_size) = $xoopsDB->fetchRow($result);

    return $used_size;
}

//目前硬碟空間
function get_free_space()
{
    $bytes = disk_free_space('.');
    $si_prefix = ['B', 'KB', 'MB', 'GB', 'TB', 'EB', 'ZB', 'YB'];
    $base = 1024;
    $class = min((int) log($bytes, $base), count($si_prefix) - 1);
    $space = sprintf('%1.2f', $bytes / pow($base, $class)) . ' ' . $si_prefix[$class];

    return $space;
}

function view_file($WebID = '')
{
    global $xoopsTpl;
    $dir = XOOPS_ROOT_PATH . "/uploads/tad_web/{$WebID}";
    // $files = dirToArray($dir);

    $json = "{ id:0, pId:0, name:'{$dir}', url:'', target:'_self', open:'true'}, \n";
    $json .= dirToJson($dir);

    $Ztree = new Ztree('link_tree', $json, 'save_drag.php', 'save_sort.php', 'of_cate_sn', 'cate_sn');
    $ztree_code = $Ztree->render();
    $xoopsTpl->assign('ztree_code', $ztree_code);

    // $xoopsTpl->assign('files', $files);
    $xoopsTpl->assign('dir', $dir);
}

function dirToJson($dir, $i = 1, $j = 0)
{
    $data = '';
    $result = [];

    $cdir = scandir($dir);
    foreach ($cdir as $key => $value) {
        if (!in_array($value, ['.', '..'])) {
            if (is_dir($dir . DIRECTORY_SEPARATOR . $value)) {
                $data .= "{ id:{$i}, pId:{$j}, name:'{$dir}/{$value}', url:'{$url}', target:'_self', open:'true'}, \n";
                $data .= dirToJson($dir . DIRECTORY_SEPARATOR . $value, $i, $i);
            } else {
                $filesize = filesize($dir . '/' . $value);
                $size = roundsize($filesize);
                $unit = mb_substr($size, -2);
                $url = str_replace(XOOPS_ROOT_PATH, XOOPS_URL, $dir . '/' . $value);

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
    $result = [];
    $i = 0;

    $cdir = scandir($dir);
    foreach ($cdir as $key => $value) {
        if (!in_array($value, ['.', '..'])) {
            if (is_dir($dir . DIRECTORY_SEPARATOR . $value)) {
                $result[$value]['name'] = dirToArray($dir . DIRECTORY_SEPARATOR . $value);
                $result[$value]['size'] = '';
            } else {
                $result[$i]['name'] = $value;
                $size = roundsize(filesize($dir . '/' . $value));
                $unit = mb_substr($size, -2);
                $result[$i]['size'] = $size;
                $result[$i]['url'] = str_replace(XOOPS_ROOT_PATH, XOOPS_URL, $dir . '/' . $value);
                if ('MB' === $unit) {
                    $result[$i]['color'] = 'red';
                } elseif ('GB' === $unit) {
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
    global $xoopsModuleConfig;
    foreach ($_POST['space_quota'] as $WebID => $user_space_quota) {
        $space_quota = ($user_space_quota == $xoopsModuleConfig['user_space_quota']) ? 'default' : (int) $user_space_quota;
        save_web_config('space_quota', $space_quota, $WebID);
    }
}
