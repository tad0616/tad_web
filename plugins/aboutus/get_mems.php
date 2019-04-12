<?php
include_once "../../../../mainfile.php";
include_once "../../function.php";
include_once XOOPS_ROOT_PATH . "/modules/tad_web/plugins/aboutus/langs/{$xoopsConfig['language']}.php";

include_once $GLOBALS['xoops']->path('/modules/system/include/functions.php');
$op      = system_CleanVars($_REQUEST, 'op', '', 'string');
$WebID   = system_CleanVars($_REQUEST, 'WebID', 0, 'int');
$CateID  = system_CleanVars($_REQUEST, 'CateID', 0, 'int');
$MemID   = system_CleanVars($_REQUEST, 'MemID', 0, 'int');
$MemName = system_CleanVars($_REQUEST, 'MemName', '', 'string');

if ($op == "get_reationship") {

    $sql = "select Reationship from " . $xoopsDB->prefix("tad_web_mem_parents") . " where  `MemID`='{$MemID}' and `ParentEnable`='1'";
    // die('<option>' . $sql . '</option>');
    $result = $xoopsDB->query($sql) or die($sql);
    $option = "";
    while (list($Reationship) = $xoopsDB->fetchRow($result)) {
        $option .= "<option vlaue='{$Reationship}'>{$Reationship}</option>";
    }
    die($option);
} elseif ($op == "chk_unable") {
    $sql = "select `ParentID`, `Reationship` ,`code` from " . $xoopsDB->prefix("tad_web_mem_parents") . " where  `MemID`='{$MemID}' and `ParentEnable`='0'";
    // die('<option>' . $sql . '</option>');
    $result                              = $xoopsDB->query($sql) or die($sql);
    $option                              = "";
    list($ParentID, $Reationship, $code) = $xoopsDB->fetchRow($result);
    die("<a href='" . XOOPS_URL . "/modules/tad_web/aboutus.php?WebID={$WebID}&op=send_signup_mail&ParentID={$ParentID}&chk_code={$code}' class='btn btn-success'>" . _MD_TCW_ABOUTUS_RE_SENDMAIL_TO . (string)($MemName) . _MD_TCW_ABOUTUS_S . "{$Reationship}</a>");
} elseif ($op == "get_parents") {
    if (empty($CateID)) {
        die(_MD_TCW_ABOUTUS_SELECT_CLASS);
    }

    $sql    = "select a.MemID, a.Reationship, b.MemNum, c.MemName,c.MemNickName from " . $xoopsDB->prefix("tad_web_mem_parents") . " as a join " . $xoopsDB->prefix("tad_web_link_mems") . " as b on a.MemID=b.MemID join " . $xoopsDB->prefix("tad_web_mems") . " as c on a.MemID=c.MemID where b.WebID ='{$WebID}' and b.CateID='{$CateID}' ";
    $result = $xoopsDB->query($sql) or die($sql);
    $i      = 0;
    $stud   = "<option value=''>" . _MD_TCW_ABOUTUS_SELECT_MEM . "</option>";

    while (list($MemID, $Reationship, $MemNum, $MemName, $MemNickName) = $xoopsDB->fetchRow($result)) {
        $MemName     = mb_substr($MemName, 0, 1, _CHARSET) . _MD_TCW_SOMEBODY;
        $showMemName = empty($MemNickName) ? $MemName : $MemName . ' (' . $MemNickName . ')';
        $num         = ($MemNum) ? '(' . $MemNum . ') ' : '';
        $option      = $num . $showMemName . _MD_TCW_ABOUTUS_S . $Reationship;

        $stud .= "<option value='{$MemID}'>{$option}</option>";
    }

    die($stud);
} else {

    if (empty($CateID)) {
        die(_MD_TCW_ABOUTUS_SELECT_CLASS);
    }

    $sql    = "select a.MemID,count(*) from " . $xoopsDB->prefix("tad_web_mem_parents") . " as a left join " . $xoopsDB->prefix("tad_web_link_mems") . " as b on a.MemID=b.MemID where b.WebID ='{$WebID}' and b.CateID='{$CateID}' group by a.MemID";
    $result = $xoopsDB->query($sql) or die($sql);
    while (list($MemID, $count) = $xoopsDB->fetchRow($result)) {
        $count_arr[$MemID] = $count;
    }

    $sql    = "select a.*,b.* from " . $xoopsDB->prefix("tad_web_link_mems") . " as a left join " . $xoopsDB->prefix("tad_web_mems") . " as b on a.MemID=b.MemID where a.WebID ='{$WebID}' and a.MemEnable='1' and a.CateID='{$CateID}'";
    $result = $xoopsDB->query($sql) or die($sql);
    $i      = 0;
    $stud   = "<option value=''>" . _MD_TCW_ABOUTUS_SELECT_MEM . "</option>";
    while ($all = $xoopsDB->fetchArray($result)) {

        //以下會產生這些變數： `MemID`, `MemName`, `MemNickName`, `MemSex`, `MemUnicode`, `MemBirthday`, `MemUrl`, `MemClassOrgan`, `MemExpertises`, `uid`, `MemUname`, `MemPasswd`,`WebID`, `MemNum`, `MemSort`, `MemEnable`, `top`, `left`
        foreach ($all as $k => $v) {
            $$k = $v;
        }

        $MemName     = mb_substr($MemName, 0, 1, _CHARSET) . _MD_TCW_SOMEBODY;
        $showMemName = empty($MemNickName) ? $MemName : $MemName . ' (' . $MemNickName . ')';
        $num         = ($MemNum) ? _MD_TCW_MEM_NUM . ' ' . $MemNum . ' ' : '';
        $option      = sprintf(_MD_TCW_ABOUTUS_IM_PARENT, $num . $showMemName);

        $show_count = isset($count_arr[$MemID]) ? ' (' . $count_arr[$MemID] . ')' : '';
        $stud .= "<option value='{$MemID}'>{$option}{$show_count}</option>";

    }
    die($stud);
}
