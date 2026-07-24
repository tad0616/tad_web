<?php
use XoopsModules\Tadtools\Utility;
if (!class_exists('XoopsModules\Tadtools\Utility')) {
    require XOOPS_ROOT_PATH . '/modules/tadtools/preloads/autoloader.php';
}

/**
 * 檔案模組更新函數
 *
 * 整合檢查和執行更新的邏輯，減少資料庫存取次數
 */
function files_update_module()
{
    global $xoopsDB;
    $table_name        = $xoopsDB->prefix('tad_web_files');
    $updates_performed = false;

    // 檢查並新增檔案連結欄位
    $sql    = "SHOW COLUMNS FROM {$table_name} LIKE 'file_link'";
    $result = $xoopsDB->query($sql) or Utility::web_error($sql, __FILE__, __LINE__);
    if (!$xoopsDB->getRowsNum($result)) {
        $sql = "ALTER TABLE {$table_name} ADD `file_link` VARCHAR(255) NOT NULL DEFAULT '' COMMENT '檔案連結',
               ADD `file_description` VARCHAR(255) NOT NULL DEFAULT '' COMMENT '檔案說明或檔名' AFTER `file_date`";
        $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);
        $updates_performed = true;
        echo "<p>已新增 file_link 和 file_description 欄位</p>";
    }

    if (!$updates_performed) {
        return true;
    }

    return $updates_performed;
}

// 執行更新
files_update_module();
