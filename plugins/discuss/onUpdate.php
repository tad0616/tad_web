<?php
use XoopsModules\Tadtools\Utility;
if (!class_exists('XoopsModules\Tadtools\Utility')) {
    require XOOPS_ROOT_PATH . '/modules/tadtools/preloads/autoloader.php';
}

/**
 * 討論區模組更新函數
 *
 * 整合檢查和執行更新的邏輯，減少資料庫存取次數
 */
function discuss_update_module()
{
    global $xoopsDB;
    $table_name        = $xoopsDB->prefix('tad_web_discuss');
    $updates_performed = false;

    // 檢查並新增 ParentID 欄位
    $sql    = "SHOW COLUMNS FROM {$table_name} LIKE 'ParentID'";
    $result = $xoopsDB->query($sql) or Utility::web_error($sql, __FILE__, __LINE__);

    if ($result && $xoopsDB->getRowsNum($result) == 0) {
        // ParentID 欄位不存在，新增它
        $sql = "ALTER TABLE {$table_name} ADD `ParentID` SMALLINT(6) UNSIGNED NOT NULL DEFAULT 0 COMMENT '家長'";
        $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);
        $updates_performed = true;
        echo "<p>已新增 ParentID 欄位到 tad_web_discuss 表</p>";
    }

    if (!$updates_performed) {
        return true;
    }

    return $updates_performed;
}

// 執行更新
discuss_update_module();
