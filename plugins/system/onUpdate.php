<?php
use XoopsModules\Tadtools\Utility;
if (!class_exists('XoopsModules\Tadtools\Utility')) {
    require XOOPS_ROOT_PATH . '/modules/tadtools/preloads/autoloader.php';
}

/**
 * 系統模組更新函數
 *
 * 整合所有更新步驟，減少資料庫存取次數
 * 按照邏輯順序執行更新操作
 */
function system_update_module()
{
    global $xoopsDB;
    $updates_performed = false;

    // 1. 檢查並處理 xoops 區塊轉移
    $sql         = 'SELECT count(*) FROM ' . $xoopsDB->prefix('tad_web_blocks') . " WHERE `plugin`='xoops'";
    $result      = $xoopsDB->query($sql) or Utility::web_error($sql, __FILE__, __LINE__);
    list($count) = $xoopsDB->fetchRow($result);

    if ($count > 0) {
        // 獲取所有需要處理的 xoops 區塊
        $sql    = 'SELECT bid, show_func FROM ' . $xoopsDB->prefix('newblocks') . " WHERE dirname='tad_web' ORDER BY weight";
        $result = $xoopsDB->query($sql) or Utility::web_error($sql, __FILE__, __LINE__);

        $myts = \MyTextSanitizer::getInstance();

        // 處理來自系統的區塊
        while (list($bid, $show_func) = $xoopsDB->fetchRow($result)) {
            $sql2 = 'SELECT BlockID, BlockTitle, BlockEnable, BlockPosition, BlockSort, WebID FROM ' .
            $xoopsDB->prefix('tad_web_blocks') . " WHERE `plugin`='xoops' AND BlockName='{$bid}'";
            $result2 = $xoopsDB->queryF($sql2) or Utility::web_error($sql2, __FILE__, __LINE__);

            while (list($BlockID, $BlockTitle, $BlockEnable, $BlockPosition, $BlockSort, $WebID) = $xoopsDB->fetchRow($result2)) {
                $BlockTitle = addslashes($BlockTitle);

                if ('tad_web_menu' === $show_func) {
                    // 更新 my_menu 區塊
                    $sql3 = 'UPDATE ' . $xoopsDB->prefix('tad_web_blocks') .
                        " SET `BlockTitle`='{$BlockTitle}', BlockEnable='1', BlockPosition='{$BlockPosition}', BlockSort='{$BlockSort}' " .
                        " WHERE `plugin`='system' AND BlockName='my_menu' AND WebID='{$WebID}'";
                    $xoopsDB->queryF($sql3) or Utility::web_error($sql3, __FILE__, __LINE__);

                    // 更新 login 區塊
                    $BlockSort = max_blocks_sort($WebID, 'side');
                    $sql3      = 'UPDATE ' . $xoopsDB->prefix('tad_web_blocks') .
                        " SET `BlockTitle`='" . _MD_TCW_SYSTEM_BLOCK_LOGIN . "', BlockEnable='1', BlockPosition='side', BlockSort='{$BlockSort}' " .
                        " WHERE `plugin`='system' AND BlockName='login' AND WebID='{$WebID}'";
                    $xoopsDB->queryF($sql3) or Utility::web_error($sql3, __FILE__, __LINE__);
                } elseif ('tad_web_list' === $show_func) {
                    // 更新 web_list 區塊
                    $sql3 = 'UPDATE ' . $xoopsDB->prefix('tad_web_blocks') .
                        " SET `BlockTitle`='{$BlockTitle}', BlockEnable='{$BlockEnable}', BlockPosition='{$BlockPosition}', BlockSort='{$BlockSort}' " .
                        " WHERE `plugin`='system' AND BlockName='web_list' AND WebID='{$WebID}'";
                    $xoopsDB->queryF($sql3) or Utility::web_error($sql3, __FILE__, __LINE__);
                } elseif ('tad_web_image' === $show_func) {
                    // 更新 action_slide 區塊
                    $sql3 = 'UPDATE ' . $xoopsDB->prefix('tad_web_blocks') .
                        " SET `BlockTitle`='{$BlockTitle}', BlockEnable='{$BlockEnable}', BlockPosition='{$BlockPosition}', BlockSort='{$BlockSort}' " .
                        " WHERE `plugin`='action' AND BlockName='action_slide' AND WebID='{$WebID}'";
                    $xoopsDB->queryF($sql3) or Utility::web_error($sql3, __FILE__, __LINE__);
                }
            }

            // 刪除已處理的 xoops 區塊
            $sql2 = 'DELETE FROM ' . $xoopsDB->prefix('tad_web_blocks') . " WHERE `plugin`='xoops' AND BlockName='{$bid}'";
            $xoopsDB->queryF($sql2) or Utility::web_error($sql2, __FILE__, __LINE__);
        }

        $updates_performed = true;
        echo "<p>已完成 xoops 區塊轉移</p>";
    }

    // 2. 檢查並處理 login 和 my_menu 區塊
    $sql         = 'SELECT count(*) FROM ' . $xoopsDB->prefix('tad_web_blocks') . " WHERE `BlockName`='login' OR `BlockName`='my_menu'";
    $result      = $xoopsDB->query($sql) or Utility::web_error($sql, __FILE__, __LINE__);
    list($count) = $xoopsDB->fetchRow($result);

    if ($count > 0) {
        // 獲取登入方式設定
        $auth_method = get_sys_openid();

        // 處理 login 區塊配置
        $sql    = 'SELECT `BlockConfig`, `WebID` FROM ' . $xoopsDB->prefix('tad_web_blocks') . " WHERE `BlockName`='login'";
        $result = $xoopsDB->query($sql) or Utility::web_error($sql, __FILE__, __LINE__);

        while (list($BlockConfig, $WebID) = $xoopsDB->fetchRow($result)) {
            $BlockConfig  = json_decode($BlockConfig, true);
            $login_method = !empty($BlockConfig['login_method']) ? implode(';', $BlockConfig['login_method']) : implode(';', $auth_method);

            // 將登入設定轉移到 tad_web_config 表
            $sql = 'REPLACE INTO ' . $xoopsDB->prefix('tad_web_config') .
                " (`ConfigName`, `ConfigValue`, `ConfigSort`, `CateID`, `WebID`) VALUES ('login_config', '{$login_method}', 0, 0, {$WebID})";
            $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);

            // 刪除配置文件
            $file = XOOPS_ROOT_PATH . "/uploads/tad_web/{$WebID}/web_config.php";
            if (file_exists($file)) {
                unlink($file);
            }

            // 清除配置緩存
            if (function_exists('clear_tad_web_config')) {
                clear_tad_web_config($WebID);
            }
        }

        // 刪除 login 和 my_menu 區塊
        $sql = 'DELETE FROM ' . $xoopsDB->prefix('tad_web_blocks') . " WHERE `BlockName`='login' OR `BlockName`='my_menu'";
        $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);

        $updates_performed = true;
        echo "<p>已完成 login 和 my_menu 區塊處理</p>";
    }

    // 3. 檢查並刪除 flickrit 區塊
    $sql         = 'SELECT count(*) FROM ' . $xoopsDB->prefix('tad_web_blocks') . " WHERE `BlockName`='flickrit'";
    $result      = $xoopsDB->query($sql) or Utility::web_error($sql, __FILE__, __LINE__);
    list($count) = $xoopsDB->fetchRow($result);

    if ($count > 0) {
        $sql = 'DELETE FROM ' . $xoopsDB->prefix('tad_web_blocks') . " WHERE `BlockName`='flickrit'";
        $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);

        $updates_performed = true;
        echo "<p>已刪除 flickrit 區塊</p>";
    }

    if (!$updates_performed) {
        return true;
    }

    return $updates_performed;
}

// 執行更新
system_update_module();
