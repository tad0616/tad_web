<?php

if (system_onUpdate1_chk()) {
    system_onUpdate1_go();
}

if (system_onUpdate2_chk()) {
    system_onUpdate2_go();
}

if (system_onUpdate3_chk()) {
    system_onUpdate3_go();
}

//修改欄位
function system_onUpdate1_chk()
{
    global $xoopsDB;
    $sql = 'SELECT count(*) FROM ' . $xoopsDB->prefix('tad_web_blocks') . " WHERE `plugin`='xoops'";
    $result = $xoopsDB->query($sql);
    if (!empty($result)) {
        return true;
    }

    return false;
}

function system_onUpdate1_go()
{
    global $xoopsDB;
    $sql = 'SELECT bid,show_func FROM ' . $xoopsDB->prefix('newblocks') . " WHERE dirname='tad_web' ORDER BY weight";
    $result = $xoopsDB->query($sql) or web_error($sql, __FILE__, __LINE__);

    $myts = MyTextSanitizer::getInstance();
    //來自系統的區塊
    while (list($bid, $show_func) = $xoopsDB->fetchRow($result)) {
        $sql2 = 'select BlockID,BlockTitle,BlockEnable,BlockPosition,BlockSort,WebID from ' . $xoopsDB->prefix('tad_web_blocks') . " where `plugin`='xoops' and BlockName='{$bid}'";
        $result2 = $xoopsDB->queryF($sql2) or web_error($sql2);
        while (list($BlockID, $BlockTitle, $BlockEnable, $BlockPosition, $BlockSort, $WebID) = $xoopsDB->fetchRow($result2)) {
            $BlockTitle = $myts->addSlashes($BlockTitle);

            if ('tad_web_menu' == $show_func) {
                $sql3 = 'update ' . $xoopsDB->prefix('tad_web_blocks') . " set `BlockTitle`='{$BlockTitle}',BlockEnable='1',BlockPosition='{$BlockPosition}' ,BlockSort='{$BlockSort}'  where `plugin`='system' and BlockName='my_menu' and WebID='{$WebID}'";
                $xoopsDB->queryF($sql3) or web_error($sql3);

                $BlockSort = max_blocks_sort($WebID, 'side');
                $sql3 = 'update ' . $xoopsDB->prefix('tad_web_blocks') . " set `BlockTitle`='" . _MD_TCW_SYSTEM_BLOCK_LOGIN . "',BlockEnable='1',BlockPosition='side' ,BlockSort='{$BlockSort}'  where `plugin`='system' and BlockName='login' and WebID='{$WebID}'";
                $xoopsDB->queryF($sql3) or web_error($sql3);
            } elseif ('tad_web_list' == $show_func) {
                $sql3 = 'update ' . $xoopsDB->prefix('tad_web_blocks') . " set `BlockTitle`='{$BlockTitle}',BlockEnable='{$BlockEnable}',BlockPosition='{$BlockPosition}' ,BlockSort='{$BlockSort}'  where `plugin`='system' and BlockName='web_list' and WebID='{$WebID}'";
                $xoopsDB->queryF($sql3);
            } elseif ('tad_web_image' == $show_func) {
                $sql3 = 'update ' . $xoopsDB->prefix('tad_web_blocks') . " set `BlockTitle`='{$BlockTitle}',BlockEnable='{$BlockEnable}',BlockPosition='{$BlockPosition}' ,BlockSort='{$BlockSort}'  where `plugin`='action' and BlockName='action_slide' and WebID='{$WebID}'";
                $xoopsDB->queryF($sql3) or web_error($sql3);
            }
        }
        $sql2 = 'delete from ' . $xoopsDB->prefix('tad_web_blocks') . " where `plugin`='xoops' and BlockName='{$bid}'";
        $xoopsDB->queryF($sql2) or web_error($sql2);
    }

    return true;
}

//移除 login 及 my_menu 區塊
function system_onUpdate2_chk()
{
    global $xoopsDB;
    $sql = 'SELECT count(*) FROM ' . $xoopsDB->prefix('tad_web_blocks') . " WHERE `BlockName`='login' OR `BlockName`='my_menu'";
    $result = $xoopsDB->query($sql);
    if (!empty($result)) {
        return true;
    }

    return false;
}

//轉移設定後刪除
function system_onUpdate2_go()
{
    global $xoopsDB;

    $auth_method = get_sys_openid();
    $sql = 'SELECT `BlockConfig`,`WebID` FROM ' . $xoopsDB->prefix('tad_web_blocks') . " WHERE `BlockName`='login'";
    $result = $xoopsDB->query($sql);
    while (list($BlockConfig, $WebID) = $xoopsDB->fetchRow($result)) {
        $BlockConfig = json_decode($BlockConfig, true);
        $login_method = implode(';', $BlockConfig['login_method']);
        if (empty($login_method)) {
            $login_method = implode(';', $auth_method);
        }
        $sql = 'replace into ' . $xoopsDB->prefix('tad_web_config') . " (`ConfigName`, `ConfigValue`, `ConfigSort`, `CateID`, `WebID`) values('login_config' ,'{$login_method}',0,0,$WebID)";
        $xoopsDB->queryF($sql) or web_error($sql, __FILE__, __LINE__);
        $file = XOOPS_ROOT_PATH . "/uploads/tad_web/{$WebID}/web_config.php";
        unlink($file);
    }

    $sql = 'DELETE FROM ' . $xoopsDB->prefix('tad_web_blocks') . " WHERE `BlockName`='login' OR `BlockName`='my_menu'";
    $xoopsDB->queryF($sql) or web_error($sql, __FILE__, __LINE__);
}

//移除 flickrit 區塊
function system_onUpdate3_chk()
{
    global $xoopsDB;
    $sql = 'SELECT count(*) FROM ' . $xoopsDB->prefix('tad_web_blocks') . " WHERE `BlockName`='flickrit'";
    $result = $xoopsDB->query($sql);
    if (!empty($result)) {
        return true;
    }

    return false;
}

//轉移設定後刪除
function system_onUpdate3_go()
{
    global $xoopsDB;
    $sql = 'DELETE FROM ' . $xoopsDB->prefix('tad_web_blocks') . " WHERE `BlockName`='flickrit'";
    $xoopsDB->queryF($sql) or web_error($sql, __FILE__, __LINE__);
}
