<?php
use XoopsModules\Tadtools\Utility;
if (!class_exists('XoopsModules\Tadtools\Utility')) {
    require XOOPS_ROOT_PATH . '/modules/tadtools/preloads/autoloader.php';
}

/**
 * 活動模組更新函數
 *
 * 整合所有更新步驟，減少資料庫存取次數
 * 按照資料表結構變更的邏輯順序執行
 */
function action_update_module()
{
    global $xoopsDB;
    $updates_performed = false;

    // 1. 檢查並刪除 ActionKind 欄位
    $sql    = "SHOW COLUMNS FROM " . $xoopsDB->prefix('tad_web_action') . " LIKE 'ActionKind'";
    $result = $xoopsDB->query($sql) or Utility::web_error($sql, __FILE__, __LINE__);
    if ($xoopsDB->getRowsNum($result) > 0) {
        $sql = 'ALTER TABLE ' . $xoopsDB->prefix('tad_web_action') . ' DROP `ActionKind`';
        $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);
        $updates_performed = true;
        echo "<p>已刪除 ActionKind 欄位</p>";
    }

    // 2. 檢查並新增 gphoto_link 欄位
    $sql    = "SHOW COLUMNS FROM " . $xoopsDB->prefix('tad_web_action') . " LIKE 'gphoto_link'";
    $result = $xoopsDB->query($sql) or Utility::web_error($sql, __FILE__, __LINE__);
    if ($xoopsDB->getRowsNum($result) == 0) {
        $sql = 'ALTER TABLE ' . $xoopsDB->prefix('tad_web_action') . " ADD `gphoto_link` varchar(1000) default '' COMMENT 'Google Photo共享相簿'";
        $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);
        $updates_performed = true;
        echo "<p>已新增 gphoto_link 欄位</p>";
    }

    // 3. 檢查並建立 tad_web_action_gphotos 表格
    $sql          = "SHOW TABLES LIKE '" . $xoopsDB->prefix('tad_web_action_gphotos') . "'";
    $result       = $xoopsDB->query($sql) or Utility::web_error($sql, __FILE__, __LINE__);
    $table_exists = ($xoopsDB->getRowsNum($result) > 0);

    if (!$table_exists) {
        $sql = "CREATE TABLE `" . $xoopsDB->prefix('tad_web_action_gphotos') . "` (
        `ActionID` smallint(6) unsigned NOT NULL default '0' COMMENT '相簿編號',
        `image_id` varchar(255) NOT NULL default '' COMMENT '相片ID',
        `image_width` smallint(6) unsigned NOT NULL default '0' COMMENT '相片寬度',
        `image_height` smallint(6) unsigned NOT NULL default '0' COMMENT '相片高度',
        `image_url` varchar(1000) NOT NULL default '' COMMENT '相片網址',
        `image_description` text NOT NULL COMMENT '相片說明',
        PRIMARY KEY  (`image_id`, `ActionID`)
        ) ENGINE=MyISAM DEFAULT CHARSET=utf8;";
        $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);
        $updates_performed = true;
        echo "<p>已建立 tad_web_action_gphotos 表格</p>";
    } else {
        // 4. 檢查並修改 tad_web_action_gphotos 表格的主索引
        $sql          = "SHOW KEYS FROM " . $xoopsDB->prefix('tad_web_action_gphotos') . " WHERE Key_name = 'PRIMARY'";
        $result       = $xoopsDB->query($sql) or Utility::web_error($sql, __FILE__, __LINE__);
        $primary_keys = [];
        while ($row = $xoopsDB->fetchArray($result)) {
            $primary_keys[] = $row['Column_name'];
        }

        // 如果主索引只有 image_id 一個欄位，則需要修改
        if (count($primary_keys) == 1 && in_array('image_id', $primary_keys)) {
            $sql = "ALTER TABLE `" . $xoopsDB->prefix('tad_web_action_gphotos') . "`
            DROP PRIMARY KEY,
            ADD PRIMARY KEY `image_id_ActionID` (`image_id`, `ActionID`)";
            $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);
            $updates_performed = true;
            echo "<p>已修改 tad_web_action_gphotos 表格的主索引</p>";
        }
    }

    if (!$updates_performed) {
        return true;
    }

    return $updates_performed;
}

// 執行更新
action_update_module();
