<?php
use Xmf\Request;
use XoopsModules\Tadtools\Utility;
require_once dirname(dirname(dirname(dirname(__DIR__)))) . '/mainfile.php';
require_once dirname(dirname(__DIR__)) . '/function.php';
require_once XOOPS_ROOT_PATH . "/modules/tad_web/plugins/aboutus/langs/{$xoopsConfig['language']}.php";

$op = Request::getString('op');
$WebID = Request::getInt('WebID');
$CateID = Request::getInt('CateID');
$MemID = Request::getInt('MemID');
$MemName = Request::getString('MemName');

if ('get_reationship' === $op) {
    $sql = 'SELECT `Reationship` FROM `' . $xoopsDB->prefix('tad_web_mem_parents') . '` WHERE `MemID`=? AND `ParentEnable`=?';
    $result = Utility::query($sql, 'is', [$MemID, '1']) or die($sql);

    $option = '';
    while (list($Reationship) = $xoopsDB->fetchRow($result)) {
        $option .= "<option vlaue='{$Reationship}'>{$Reationship}</option>";
    }
    die($option);
} elseif ('chk_unable' === $op) {
    $sql = 'SELECT `ParentID`, `Reationship`, `code` FROM `' . $xoopsDB->prefix('tad_web_mem_parents') . '` WHERE `MemID`=? AND `ParentEnable`=?';
    $result = Utility::query($sql, 'is', [$MemID, '0']) or die($sql);

    $option = '';
    list($ParentID, $Reationship, $code) = $xoopsDB->fetchRow($result);
    die("<a href='" . XOOPS_URL . "/modules/tad_web/aboutus.php?WebID={$WebID}&op=send_signup_mail&ParentID={$ParentID}&chk_code={$code}' class='btn btn-success'>" . _MD_TCW_ABOUTUS_RE_SENDMAIL_TO . (string) ($MemName) . _MD_TCW_ABOUTUS_S . "{$Reationship}</a>");
} elseif ('get_parents' === $op) {
    if (empty($CateID)) {
        die(_MD_TCW_ABOUTUS_SELECT_CLASS);
    }

    $sql = 'SELECT a.`MemID`, a.`Reationship`, b.`MemNum`, c.`MemName`, c.`MemNickName` FROM `' . $xoopsDB->prefix('tad_web_mem_parents') . '` AS a JOIN `' . $xoopsDB->prefix('tad_web_link_mems') . '` AS b ON a.`MemID`=b.`MemID` JOIN `' . $xoopsDB->prefix('tad_web_mems') . '` AS c ON a.`MemID`=c.`MemID` WHERE b.`WebID`=? AND b.`CateID`=?';
    $result = Utility::query($sql, 'ii', [$WebID, $CateID]) or die($sql);

    $i = 0;
    $stud = "<option value=''>" . _MD_TCW_ABOUTUS_SELECT_MEM . '</option>';

    while (list($MemID, $Reationship, $MemNum, $MemName, $MemNickName) = $xoopsDB->fetchRow($result)) {
        $MemName = mb_substr($MemName, 0, 1, _CHARSET) . _MD_TCW_SOMEBODY;
        $showMemName = empty($MemNickName) ? $MemName : $MemName . ' (' . $MemNickName . ')';
        $num = ($MemNum) ? '(' . $MemNum . ') ' : '';
        $option = $num . $showMemName . _MD_TCW_ABOUTUS_S . $Reationship;

        $stud .= "<option value='{$MemID}'>{$option}</option>";
    }

    die($stud);
}

if (empty($CateID)) {
    die(_MD_TCW_ABOUTUS_SELECT_CLASS);
}

$sql = 'SELECT a.`MemID`, COUNT(*) FROM `' . $xoopsDB->prefix('tad_web_mem_parents') . '` AS a LEFT JOIN `' . $xoopsDB->prefix('tad_web_link_mems') . '` AS b ON a.`MemID`=b.`MemID` WHERE b.`WebID`=? AND b.`CateID`=? GROUP BY a.`MemID`';
$result = Utility::query($sql, 'ii', [$WebID, $CateID]) or die($sql);

while (list($MemID, $count) = $xoopsDB->fetchRow($result)) {
    $count_arr[$MemID] = $count;
}

$sql = 'SELECT a.*, b.* FROM `' . $xoopsDB->prefix('tad_web_link_mems') . '` AS a LEFT JOIN `' . $xoopsDB->prefix('tad_web_mems') . '` AS b ON a.`MemID` = b.`MemID` WHERE a.`WebID` = ? AND a.`MemEnable` = ? AND a.`CateID` = ?';
$result = Utility::query($sql, 'isi', [$WebID, '1', $CateID]) or die($sql);
$i = 0;
$stud = "<option value=''>" . _MD_TCW_ABOUTUS_SELECT_MEM . '</option>';
while (false !== ($all = $xoopsDB->fetchArray($result))) {
    //以下會產生這些變數： `MemID`, `MemName`, `MemNickName`, `MemSex`, `MemUnicode`, `MemBirthday`, `MemUrl`, `MemClassOrgan`, `MemExpertises`, `uid`, `MemUname`, `MemPasswd`,`WebID`, `MemNum`, `MemSort`, `MemEnable`, `top`, `left`
    foreach ($all as $k => $v) {
        $$k = $v;
    }

    $MemName = mb_substr($MemName, 0, 1, _CHARSET) . _MD_TCW_SOMEBODY;
    $showMemName = empty($MemNickName) ? $MemName : $MemName . ' (' . $MemNickName . ')';
    $num = ($MemNum) ? _MD_TCW_MEM_NUM . ' ' . $MemNum . ' ' : '';
    $option = sprintf(_MD_TCW_ABOUTUS_IM_PARENT, $num . $showMemName);

    $show_count = isset($count_arr[$MemID]) ? ' (' . $count_arr[$MemID] . ')' : '';
    $stud .= "<option value='{$MemID}'>{$option}{$show_count}</option>";
}
die($stud);
