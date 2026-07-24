<?php
use XoopsModules\Tadtools\Utility;
if (!class_exists('XoopsModules\Tadtools\Utility')) {
    require XOOPS_ROOT_PATH . '/modules/tadtools/preloads/autoloader.php';
}

/**
 * 頁面模組更新函數
 *
 * 整合了所有更新步驟，減少資料庫存取次數
 * 按照資料表結構變更的邏輯順序執行
 */
function page_update_module()
{
    global $xoopsDB;
    $table_name        = $xoopsDB->prefix('tad_web_page');
    $updates_performed = false;

    // 1. 檢查並修改 PageContent 欄位類型 (text -> longtext)
    $sql    = "SELECT DATA_TYPE FROM INFORMATION_SCHEMA.COLUMNS WHERE table_name = '{$table_name}' AND COLUMN_NAME = 'PageContent'";
    $result = $xoopsDB->query($sql) or Utility::web_error($sql, __FILE__, __LINE__);
    if ($result) {
        list($type) = $xoopsDB->fetchRow($result);
        if ('text' === $type) {
            $sql = "ALTER TABLE {$table_name} CHANGE `PageContent` `PageContent` LONGTEXT COLLATE 'utf8_general_ci' NOT NULL COMMENT '文章內容'";
            $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);
            $updates_performed = true;
            echo "<p>已將 PageContent 欄位從 TEXT 修改為 LONGTEXT</p>";
        }
    }

    // 2. 檢查並修改 PageSort 欄位類型
    $sql    = "SHOW Fields FROM {$table_name} where `Field`='PageSort' and `Type` like 'smallint%'";
    $result = $xoopsDB->query($sql) or Utility::web_error($sql, __FILE__, __LINE__);
    $all    = $xoopsDB->fetchRow($result);
    if (false === $all) {
        $sql = "ALTER TABLE {$table_name} CHANGE `PageSort` `PageSort` SMALLINT NOT NULL DEFAULT 0 COMMENT '排序'";
        $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);
        $updates_performed = true;
        echo "<p>已將 PageSort 欄位修改為 SMALLINT 類型</p>";
    }

    // 3. 檢查並新增 PageCSS 欄位
    $sql    = "SHOW COLUMNS FROM {$table_name} LIKE 'PageCSS'";
    $result = $xoopsDB->query($sql) or Utility::web_error($sql, __FILE__, __LINE__);
    if (!$xoopsDB->getRowsNum($result)) {
        $sql = "ALTER TABLE {$table_name} ADD `PageCSS` TEXT NOT NULL COMMENT '文章樣式'";
        $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);
        $updates_performed = true;
        echo "<p>已新增 PageCSS 欄位</p>";
    }

    if (!$updates_performed) {
        return true;
    }

    return $updates_performed;
}

// 執行更新
page_update_module();
