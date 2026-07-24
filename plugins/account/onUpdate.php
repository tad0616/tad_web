<?php
use XoopsModules\Tadtools\Utility;
if (!class_exists('XoopsModules\Tadtools\Utility')) {
    require XOOPS_ROOT_PATH . '/modules/tadtools/preloads/autoloader.php';
}

/**
 * 帳務模組更新函數
 *
 * 整合檢查和執行更新的邏輯，減少資料庫存取次數
 */
function account_update_module()
{
    global $xoopsDB;
    $table_name        = $xoopsDB->prefix('tad_web_account');
    $updates_performed = false;

    // 檢查並修改 AccountIncome 和 AccountOutgoings 欄位類型
    $sql    = "SHOW COLUMNS FROM {$table_name} WHERE `Field` = 'AccountIncome'";
    $result = $xoopsDB->query($sql) or Utility::web_error($sql, __FILE__, __LINE__);
    if ($result && $xoopsDB->getRowsNum($result) > 0) {
        $row = $xoopsDB->fetchArray($result);
        if ($row['Type'] == 'smallint(6)') {
            // 將 AccountIncome 和 AccountOutgoings 欄位從 smallint 修改為 mediumint
            $sql = "ALTER TABLE {$table_name}
                   CHANGE `AccountIncome` `AccountIncome` mediumint NOT NULL DEFAULT '0' COMMENT '收入' AFTER `AccountDate`,
                   CHANGE `AccountOutgoings` `AccountOutgoings` mediumint NOT NULL DEFAULT '0' COMMENT '支出' AFTER `AccountIncome`";
            $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);
            $updates_performed = true;
            echo "<p>已將 AccountIncome 和 AccountOutgoings 欄位從 smallint 修改為 mediumint</p>";
        }
    }

    if (!$updates_performed) {
        return true;
    }

    return $updates_performed;
}

// 執行更新
account_update_module();
