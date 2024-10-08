<?php
use Xmf\Request;
use XoopsModules\Tadtools\Utility;

require_once dirname(dirname(__DIR__)) . '/mainfile.php';
require_once __DIR__ . '/function.php';

$op = Request::getString('op');
$plugin = Request::getString('plugin');
$WebID = Request::getInt('WebID');
$default_class = Request::getInt('default_class');
header('HTTP/1.1 200 OK');
switch ($op) {

    case 'get_cate_options':
        get_cate_options($WebID, $plugin);
        break;
    case 'get_default_class_mems':
        get_default_class_mems($WebID, $default_class);
        break;
}

function get_cate_options($WebID = '', $plugin = '')
{
    global $xoopsDB;
    $sql = 'select CateID, CateName from ' . $xoopsDB->prefix('tad_web_cate') . " where `ColName` = 'aboutus' AND `CateEnable` = '1' AND `WebID` = '{$WebID}' order by CateSort";
    $result = $xoopsDB->query($sql) or Utility::web_error($sql, __FILE__, __LINE__);
    $option = '';
    while (list($CateID, $CateName) = $xoopsDB->fetchRow($result)) {
        $option .= "<option value='{$CateID}'>{$CateName}</option>";
    }

    if ($plugin != 'aboutus') {
        $sql = 'select CateID, CateName from ' . $xoopsDB->prefix('tad_web_cate') . " where `ColName` = '{$plugin}' AND `CateEnable` = '1' AND `WebID` = '{$WebID}' order by CateSort";
        $result = $xoopsDB->query($sql) or Utility::web_error($sql, __FILE__, __LINE__);
        while (list($CateID, $CateName) = $xoopsDB->fetchRow($result)) {
            $option .= "<option value='{$CateID}'>{$CateName}</option>";
        }
    }

    if (empty($option)) {
        $options = "<option value=''>無任何分類，無法設定小幫手</option>";
    } else {
        $options = "<option value=''>" . _MD_TCW_SELECT_CATE . "</option>{$option}";
    }
    die($options);
}

function get_default_class_mems($WebID = '', $default_class = '')
{
    global $xoopsDB;
    $sql = 'select a.MemID, a.MemNum ,b.MemName from ' . $xoopsDB->prefix('tad_web_link_mems') . ' as a left join ' . $xoopsDB->prefix('tad_web_mems') . " as b on a.MemID=b.MemID where a.`CateID` = '{$default_class}'  order by a.MemNum";
    $result = $xoopsDB->query($sql) or Utility::web_error($sql, __FILE__, __LINE__);
    $options = "<option value=''>" . _MD_TCW_CATE_SET_ASSISTANT . '</option>';
    while (list($MemID, $MemNum, $MemName) = $xoopsDB->fetchRow($result)) {
        $options .= "<option value='{$MemID}'>{$MemNum} {$MemName}</option>";
    }
    die($options);
}
