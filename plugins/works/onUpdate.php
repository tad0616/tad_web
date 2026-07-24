<?php
use XoopsModules\Tadtools\Utility;
if (!class_exists('XoopsModules\Tadtools\Utility')) {
    require XOOPS_ROOT_PATH . '/modules/tadtools/preloads/autoloader.php';
}

/**
 * 作品模組更新函數
 *
 * 整合所有更新步驟，減少資料庫存取次數
 * 按照資料表結構變更的邏輯順序執行
 */
function works_update_module()
{
    global $xoopsDB;
    $updates_performed = false;

    // 1. 檢查並修改 tad_web_works 表，新增 WorksKind 和 WorksEnable 欄位
    $sql    = "SHOW COLUMNS FROM " . $xoopsDB->prefix('tad_web_works') . " LIKE 'WorksKind'";
    $result = $xoopsDB->query($sql) or Utility::web_error($sql, __FILE__, __LINE__);
    if ($xoopsDB->getRowsNum($result) == 0) {
        $sql = 'ALTER TABLE ' . $xoopsDB->prefix('tad_web_works') . "
                ADD `WorksKind` VARCHAR(255) NOT NULL DEFAULT '' COMMENT '上傳方式',
                ADD `WorksEnable` ENUM('1','0') NOT NULL DEFAULT '1' COMMENT '是否啟用'";
        $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);
        $updates_performed = true;
        echo "<p>已新增 WorksKind 和 WorksEnable 欄位到 tad_web_works 表</p>";
    }

    // 2. 檢查並建立 tad_web_works_content 表格
    $sql    = "SHOW TABLES LIKE '" . $xoopsDB->prefix('tad_web_works_content') . "'";
    $result = $xoopsDB->query($sql) or Utility::web_error($sql, __FILE__, __LINE__);
    if ($xoopsDB->getRowsNum($result) == 0) {
        $sql = 'CREATE TABLE `' . $xoopsDB->prefix('tad_web_works_content') . "` (
          `WorksID` SMALLINT(5) UNSIGNED NOT NULL COMMENT '作品主題流水號',
          `MemID` SMALLINT(6) UNSIGNED NOT NULL DEFAULT 0,
          `MemName` VARCHAR(255) NOT NULL DEFAULT '' COMMENT '上傳者',
          `WebID` SMALLINT(6) UNSIGNED NOT NULL DEFAULT 0 COMMENT '所屬班級',
          `WorkDesc` TEXT NOT NULL COMMENT '作品說明',
          `UploadDate` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '上傳日期',
          `WorkScore` VARCHAR(255) NOT NULL DEFAULT '' COMMENT '分數',
          `WorkJudgment` TEXT NOT NULL COMMENT '評語',
          `all_files_sn` VARCHAR(255) NOT NULL DEFAULT '' COMMENT '檔案流水號',
        PRIMARY KEY (`WorksID`,`MemID`,`WebID`)
        ) ENGINE=MyISAM";
        $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);
        $updates_performed = true;
        echo "<p>已建立 tad_web_works_content 表格</p>";
    }

    if (!$updates_performed) {
        return true;
    }

    return $updates_performed;
}

// 執行更新
works_update_module();
