<?php
use XoopsModules\Tadtools\Utility;
if (!class_exists('XoopsModules\Tadtools\Utility')) {
    require XOOPS_ROOT_PATH . '/modules/tadtools/preloads/autoloader.php';
}

/**
 * 行事曆模組更新函數
 *
 * 整合檢查和執行更新的邏輯，減少資料庫存取次數
 */
function schedule_update_module()
{
    global $xoopsDB;
    $table_name        = $xoopsDB->prefix('tad_web_schedule_data');
    $updates_performed = false;

    // 檢查並新增 Link 欄位
    $sql    = "SHOW COLUMNS FROM {$table_name} LIKE 'Link'";
    $result = $xoopsDB->query($sql) or Utility::web_error($sql, __FILE__, __LINE__);

    if ($result && $xoopsDB->getRowsNum($result) == 0) {
        // Link 欄位不存在，新增它
        $sql = "ALTER TABLE {$table_name} ADD `Link` varchar(1000) NOT NULL default '' COMMENT '連結' AFTER `Teacher`";
        $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);
        $updates_performed = true;
        echo "<p>已新增 Link 欄位到 tad_web_schedule_data 表</p>";
    }

    if (!$updates_performed) {
        return true;
    }

    return $updates_performed;
}

// 執行更新
schedule_update_module();
