<?php
use XoopsModules\Tadtools\Utility;
if (!class_exists('XoopsModules\Tadtools\Utility')) {
    require XOOPS_ROOT_PATH . '/modules/tadtools/preloads/autoloader.php';
}

/**
 * 作業模組更新函數
 *
 * 整合所有更新步驟，減少資料庫存取次數
 * 按照資料表結構變更的邏輯順序執行
 */
function homework_update_module()
{
    global $xoopsDB;
    $updates_performed = false;

    // 1. 檢查並修改 tad_web_homework 表，新增 HomeworkPostDate 欄位
    $sql    = "SHOW COLUMNS FROM " . $xoopsDB->prefix('tad_web_homework') . " LIKE 'HomeworkPostDate'";
    $result = $xoopsDB->query($sql) or Utility::web_error($sql, __FILE__, __LINE__);
    if ($xoopsDB->getRowsNum($result) == 0) {
        // 修改 toCal 欄位註解並新增 HomeworkPostDate 欄位
        $sql = 'ALTER TABLE ' . $xoopsDB->prefix('tad_web_homework') .
            " CHANGE `toCal` `toCal` DATE NOT NULL DEFAULT '0000-00-00' COMMENT '加到行事曆',
                 ADD `HomeworkPostDate` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '顯示日期' AFTER `uid`";
        $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);

        // 將 HomeworkDate 的值複製到 HomeworkPostDate
        $sql = 'UPDATE ' . $xoopsDB->prefix('tad_web_homework') . ' SET `HomeworkPostDate` = `HomeworkDate`';
        $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);

        $updates_performed = true;
        echo "<p>已修改 toCal 欄位註解並新增 HomeworkPostDate 欄位到 tad_web_homework 表</p>";
    }

    // 2. 檢查並建立 tad_web_homework_content 表格
    $sql    = "SHOW TABLES LIKE '" . $xoopsDB->prefix('tad_web_homework_content') . "'";
    $result = $xoopsDB->query($sql) or Utility::web_error($sql, __FILE__, __LINE__);
    if ($xoopsDB->getRowsNum($result) == 0) {
        $sql = 'CREATE TABLE `' . $xoopsDB->prefix('tad_web_homework_content') . "` (
          `HomeworkID` SMALLINT(6) UNSIGNED NOT NULL COMMENT '編號',
          `HomeworkCol` VARCHAR(100) NOT NULL DEFAULT '' COMMENT '欄位',
          `Content` TEXT NOT NULL COMMENT '內容',
          `WebID` SMALLINT(6) UNSIGNED NOT NULL DEFAULT 0 COMMENT '所屬班級',
          PRIMARY KEY (`HomeworkID`,`HomeworkCol`)
        ) ENGINE=MyISAM";
        $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);

        $updates_performed = true;
        echo "<p>已建立 tad_web_homework_content 表格</p>";
    }

    if (!$updates_performed) {
        return true;
    }

    return $updates_performed;
}

// 執行更新
homework_update_module();
