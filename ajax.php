<?php
use Xmf\Request;
use XoopsModules\Tadtools\Utility;

require_once dirname(dirname(__DIR__)) . '/mainfile.php';
require_once __DIR__ . '/function.php';
header('HTTP/1.1 200 OK');
$xoopsLogger->activated = false;

$op = Request::getString('op');
$plugin = Request::getString('plugin');
$WebID = Request::getInt('WebID');
$default_class = Request::getInt('default_class');

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
    $sql = 'SELECT `CateID`, `CateName` FROM `' . $xoopsDB->prefix('tad_web_cate') . '` WHERE `ColName` = ? AND `CateEnable` = ? AND `WebID` = ? ORDER BY `CateSort`';
    $result = Utility::query($sql, 'ssi', ['aboutus', '1', $WebID]) or Utility::web_error($sql, __FILE__, __LINE__);

    $option = '';
    while (list($CateID, $CateName) = $xoopsDB->fetchRow($result)) {
        $option .= "<option value='{$CateID}'>{$CateName}</option>";
    }

    if ($plugin != 'aboutus') {
        $sql = 'SELECT `CateID`, `CateName` FROM `' . $xoopsDB->prefix('tad_web_cate') . '` WHERE `ColName` =? AND `CateEnable` = ? AND `WebID` =? ORDER BY `CateSort`';
        $result = Utility::query($sql, 'ssi', [$plugin, '1', $WebID]) or Utility::web_error($sql, __FILE__, __LINE__);

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
    $sql = 'SELECT a.`MemID`, a.`MemNum`, b.`MemName` FROM `' . $xoopsDB->prefix('tad_web_link_mems') . '` AS a LEFT JOIN `' . $xoopsDB->prefix('tad_web_mems') . '` AS b ON a.`MemID`=b.`MemID` WHERE a.`CateID` = ? ORDER BY a.`MemNum`';
    $result = Utility::query($sql, 'i', [$default_class]) or Utility::web_error($sql, __FILE__, __LINE__);

    $options = "<option value=''>" . _MD_TCW_CATE_SET_ASSISTANT . '</option>';
    while (list($MemID, $MemNum, $MemName) = $xoopsDB->fetchRow($result)) {
        $options .= "<option value='{$MemID}'>{$MemNum} {$MemName}</option>";
    }
    die($options);
}
