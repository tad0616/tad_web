<?php
use XoopsModules\Tadtools\Utility;
if (!class_exists('XoopsModules\Tadtools\Utility')) {
    require XOOPS_ROOT_PATH . '/modules/tadtools/preloads/autoloader.php';
}

/**
 * 新聞模組更新函數
 *
 * 整合所有更新步驟，減少資料庫存取次數
 * 按照資料表結構變更的邏輯順序執行
 */
function news_update_module()
{
    global $xoopsDB;
    $table_name        = $xoopsDB->prefix('tad_web_news');
    $updates_performed = false;

    // 取得資料表結構資訊，只查詢一次資料庫
    $sql              = "SHOW COLUMNS FROM {$table_name}";
    $result           = $xoopsDB->query($sql) or Utility::web_error($sql, __FILE__, __LINE__);
    $existing_columns = [];
    while ($row = $xoopsDB->fetchArray($result)) {
        $existing_columns[$row['Field']] = $row;
    }

    // 1. 檢查並修改 NewsContent 欄位類型，並刪除 NewsPlace 和 NewsMaster 欄位
    if (isset($existing_columns['NewsPlace'])) {
        $sql = "ALTER TABLE {$table_name} CHANGE `NewsContent` `NewsContent` LONGTEXT COLLATE 'utf8_general_ci' NOT NULL COMMENT '內容',
        DROP `NewsPlace`,
        DROP `NewsMaster`";
        $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);
        $updates_performed = true;
        echo "<p>已將 NewsContent 欄位修改為 LONGTEXT 並刪除 NewsPlace 和 NewsMaster 欄位</p>";
    }

    // 2. 檢查並刪除 NewsKind 欄位
    if (isset($existing_columns['NewsKind'])) {
        $sql = "ALTER TABLE {$table_name} DROP `NewsKind`";
        $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);
        $updates_performed = true;
        echo "<p>已刪除 NewsKind 欄位</p>";
    }

    // 3. 檢查並新增 NewsEnable 欄位
    if (!isset($existing_columns['NewsEnable'])) {
        $sql = "ALTER TABLE {$table_name} ADD `NewsEnable` ENUM('1','0') NOT NULL DEFAULT '1' COMMENT '狀態' AFTER `NewsCounter`";
        $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);
        $updates_performed = true;
        echo "<p>已新增 NewsEnable 欄位</p>";
    }

    if (!$updates_performed) {
        return true;
    }

    return $updates_performed;
}

// 執行更新
news_update_module();
