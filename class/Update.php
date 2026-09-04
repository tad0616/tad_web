<?php

namespace XoopsModules\Tad_web;

use XoopsModules\Tadtools\Utility;

if (!class_exists('XoopsModules\Tadtools\Utility')) {
    require XOOPS_ROOT_PATH . '/modules/tadtools/preloads/autoloader.php';
}

/*
Update Class Definition

You may not change or alter any portion of this comment or credits of
supporting developers from this source code or any supporting source code
which is considered copyrighted (c) material of the original comment or credit
authors.

This program is distributed in the hope that it will be useful, but
WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */

/**
 * @license      http://www.fsf.org/copyleft/gpl.html GNU public license
 * @copyright    https://xoops.org 2001-2017 &copy; XOOPS Project
 * @author       Mamba <mambax7@gmail.com>
 */

/**
 * Class Update
 */
class Update
{

    public static function fix_sql_tables()
    {
        global $xoopsDB;

        $table  = $xoopsDB->prefix('tad_web');
        $sql    = "SHOW TABLE STATUS LIKE '{$table}'";
        $result = $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);
        $status = $xoopsDB->fetchArray($result);
        if (!$status) {
            return;
        }

        $sql           = "SHOW COLUMNS FROM {$table} LIKE 'CateID'";
        $result        = $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);
        $cate          = $xoopsDB->fetchArray($result);
        $sql           = "SHOW INDEX FROM {$table} WHERE Key_name = 'WebOwnerUid'";
        $result        = $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);
        $ownerIndex    = $xoopsDB->fetchArray($result);
        $hasOwnerIndex = is_array($ownerIndex) && 0 == $ownerIndex['Non_unique'];

        if ('InnoDB' === $status['Engine'] && 'mediumint' === strtolower($cate['Type']) && $hasOwnerIndex) {
            return;
        }

        $alter = static function ($name, $definition) use ($xoopsDB) {
            $fullTable = $xoopsDB->prefix($name);
            $sql       = "ALTER TABLE {$fullTable} {$definition}";
            $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);
        };
        $addIndex = static function ($name, $indexName, $columns, $unique = false) use ($xoopsDB) {
            $fullTable = $xoopsDB->prefix($name);
            $sql       = "SHOW INDEX FROM {$fullTable} WHERE Key_name = '{$indexName}'";
            $result    = $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);
            if (false === $xoopsDB->fetchArray($result)) {
                $indexType = $unique ? 'UNIQUE INDEX' : 'INDEX';
                $sql       = "ALTER TABLE {$fullTable} ADD {$indexType} `{$indexName}` ({$columns})";
                $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);
            }
        };
        $convert = static function ($name) use ($xoopsDB) {
            $sql = 'ALTER TABLE ' . $xoopsDB->prefix($name) . ' ENGINE=InnoDB';
            $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);
        };

        $alter('tad_web', "MODIFY `CateID` MEDIUMINT UNSIGNED NOT NULL DEFAULT 0, MODIFY `WebCounter` MEDIUMINT UNSIGNED NOT NULL DEFAULT 0, MODIFY `CreatDate` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00', MODIFY `last_accessed` DATETIME NOT NULL");
        $alter('tad_web_account', "MODIFY `AccountID` MEDIUMINT UNSIGNED NOT NULL AUTO_INCREMENT, MODIFY `CateID` MEDIUMINT UNSIGNED NOT NULL DEFAULT 0, MODIFY `AccountDate` DATE NOT NULL DEFAULT '0000-00-00'");
        $alter('tad_web_action', "MODIFY `ActionID` MEDIUMINT UNSIGNED NOT NULL AUTO_INCREMENT, MODIFY `CateID` MEDIUMINT UNSIGNED NOT NULL DEFAULT 0, MODIFY `ActionDate` DATE NOT NULL DEFAULT '0000-00-00'");
        $alter('tad_web_action_gphotos', "MODIFY `ActionID` MEDIUMINT UNSIGNED NOT NULL DEFAULT 0, MODIFY `image_id` VARCHAR(80) NOT NULL DEFAULT ''");
        $alter('tad_web_assistant_post', "MODIFY `plugin` VARCHAR(100) NOT NULL, MODIFY `ColSN` INT UNSIGNED NOT NULL AUTO_INCREMENT, MODIFY `CateID` MEDIUMINT UNSIGNED NOT NULL");
        $alter('tad_web_blocks', "MODIFY `plugin` VARCHAR(100) NOT NULL");
        $alter('tad_web_calendar', "MODIFY `CalendarID` INT UNSIGNED NOT NULL AUTO_INCREMENT, MODIFY `CateID` MEDIUMINT UNSIGNED NOT NULL DEFAULT 0, MODIFY `CalendarDate` DATE NOT NULL DEFAULT '0000-00-00'");
        $alter('tad_web_cate', "MODIFY `CateID` MEDIUMINT UNSIGNED NOT NULL AUTO_INCREMENT");
        $alter('tad_web_cate_assistant', "MODIFY `CateID` MEDIUMINT UNSIGNED NOT NULL, MODIFY `plugin` VARCHAR(100) NOT NULL DEFAULT ''");
        $alter('tad_web_config', "MODIFY `CateID` MEDIUMINT UNSIGNED NOT NULL DEFAULT 0");
        $alter('tad_web_discuss', "MODIFY `DiscussID` MEDIUMINT UNSIGNED NOT NULL AUTO_INCREMENT, MODIFY `ReDiscussID` MEDIUMINT UNSIGNED NOT NULL DEFAULT 0, MODIFY `CateID` MEDIUMINT UNSIGNED NOT NULL DEFAULT 0, MODIFY `MemID` MEDIUMINT UNSIGNED NOT NULL DEFAULT 0, MODIFY `ParentID` MEDIUMINT UNSIGNED NOT NULL DEFAULT 0, MODIFY `DiscussDate` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00', MODIFY `LastTime` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00'");
        $alter('tad_web_files', "MODIFY `fsn` MEDIUMINT UNSIGNED NOT NULL AUTO_INCREMENT, MODIFY `CateID` MEDIUMINT UNSIGNED NOT NULL DEFAULT 0, MODIFY `file_date` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00'");
        $alter('tad_web_files_center', "MODIFY `col_name` VARCHAR(100) NOT NULL DEFAULT '', MODIFY `col_sn` MEDIUMINT UNSIGNED NOT NULL DEFAULT 0, MODIFY `upload_date` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00'");
        $alter('tad_web_homework', "MODIFY `HomeworkID` INT UNSIGNED NOT NULL AUTO_INCREMENT, MODIFY `CateID` MEDIUMINT UNSIGNED NOT NULL DEFAULT 0, MODIFY `HomeworkDate` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00', MODIFY `toCal` DATE NOT NULL DEFAULT '0000-00-00', MODIFY `HomeworkPostDate` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00'");
        $alter('tad_web_homework_content', "MODIFY `HomeworkID` INT UNSIGNED NOT NULL");
        $alter('tad_web_link', "MODIFY `LinkID` MEDIUMINT UNSIGNED NOT NULL AUTO_INCREMENT, MODIFY `CateID` MEDIUMINT UNSIGNED NOT NULL DEFAULT 0");
        $alter('tad_web_link_mems', "MODIFY `CateID` MEDIUMINT UNSIGNED NOT NULL DEFAULT 0");
        $alter('tad_web_mail_log', "MODIFY `MailDate` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00'");
        $alter('tad_web_mem_parents', "MODIFY `Reationship` VARCHAR(50) NOT NULL DEFAULT '', MODIFY `ParentEmail` VARCHAR(80) NOT NULL DEFAULT ''");
        $alter('tad_web_mems', "MODIFY `MemBirthday` DATE NOT NULL DEFAULT '0000-00-00'");
        $alter('tad_web_news', "MODIFY `NewsID` MEDIUMINT UNSIGNED NOT NULL AUTO_INCREMENT, MODIFY `CateID` MEDIUMINT UNSIGNED NOT NULL DEFAULT 0, MODIFY `NewsDate` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00', MODIFY `toCal` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00'");
        $alter('tad_web_notice', "MODIFY `NoticeDate` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00'");
        $alter('tad_web_page', "MODIFY `PageID` MEDIUMINT UNSIGNED NOT NULL AUTO_INCREMENT, MODIFY `CateID` MEDIUMINT UNSIGNED NOT NULL DEFAULT 0, MODIFY `PageDate` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00', MODIFY `PageSort` SMALLINT NOT NULL DEFAULT 0");
        $alter('tad_web_plugins_setup', "MODIFY `plugin` VARCHAR(100) NOT NULL DEFAULT ''");
        $alter('tad_web_roles', "MODIFY `role` VARCHAR(50) NOT NULL, MODIFY `term` DATE NOT NULL DEFAULT '0000-00-00'");
        $alter('tad_web_schedule', "MODIFY `ScheduleID` MEDIUMINT UNSIGNED NOT NULL AUTO_INCREMENT, MODIFY `CateID` MEDIUMINT UNSIGNED NOT NULL DEFAULT 0, MODIFY `ScheduleTime` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00'");
        $alter('tad_web_schedule_data', "MODIFY `ScheduleID` MEDIUMINT UNSIGNED NOT NULL, MODIFY `Link` VARCHAR(1000) NOT NULL");
        $alter('tad_web_tags', "MODIFY `col_name` VARCHAR(100) NOT NULL DEFAULT '', MODIFY `tag_name` VARCHAR(100) NOT NULL DEFAULT ''");
        $alter('tad_web_video', "MODIFY `VideoID` MEDIUMINT UNSIGNED NOT NULL AUTO_INCREMENT, MODIFY `CateID` MEDIUMINT UNSIGNED NOT NULL DEFAULT 0, MODIFY `VideoDate` DATE NOT NULL DEFAULT '0000-00-00'");
        $alter('tad_web_works', "MODIFY `WorksID` MEDIUMINT UNSIGNED NOT NULL AUTO_INCREMENT, MODIFY `CateID` MEDIUMINT UNSIGNED NOT NULL DEFAULT 0, MODIFY `WorksDate` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00'");
        $alter('tad_web_works_content', "MODIFY `WorksID` MEDIUMINT UNSIGNED NOT NULL, MODIFY `MemID` MEDIUMINT UNSIGNED NOT NULL DEFAULT 0, MODIFY `UploadDate` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00'");

        $indexes = [
            ['tad_web', 'WebOwnerUid', '`WebOwnerUid`', true],
            ['tad_web', 'WebEnable', '`WebEnable`'], ['tad_web', 'CreatDate', '`CreatDate`'],
            ['tad_web_account', 'CateID', '`CateID`'], ['tad_web_account', 'WebID', '`WebID`'],
            ['tad_web_action', 'CateID', '`CateID`'], ['tad_web_action', 'WebID', '`WebID`'], ['tad_web_action', 'WebID_ActionDate', '`WebID`,`ActionDate`'],
            ['tad_web_action_gphotos', 'ActionID', '`ActionID`'], ['tad_web_assistant_post', 'ColSN', '`ColSN`'],
            ['tad_web_blocks', 'WebID', '`WebID`'], ['tad_web_blocks', 'ShareFrom', '`ShareFrom`'],
            ['tad_web_calendar', 'CateID', '`CateID`'], ['tad_web_calendar', 'WebID', '`WebID`'], ['tad_web_calendar', 'WebID_CalendarDate', '`WebID`,`CalendarDate`'],
            ['tad_web_cate', 'WebID', '`WebID`'], ['tad_web_cate', 'WebID_Enable', '`WebID`,`CateEnable`'], ['tad_web_config', 'WebID', '`WebID`'],
            ['tad_web_discuss', 'CateID', '`CateID`'], ['tad_web_discuss', 'WebID', '`WebID`'], ['tad_web_discuss', 'ParentID', '`ParentID`'], ['tad_web_discuss', 'ReDiscussID', '`ReDiscussID`'],
            ['tad_web_files', 'CateID', '`CateID`'], ['tad_web_files', 'WebID', '`WebID`'], ['tad_web_files_center', 'uid', '`uid`'], ['tad_web_files_center', 'kind', '`kind`'], ['tad_web_files_center', 'upload_date', '`upload_date`'],
            ['tad_web_homework', 'WebID', '`WebID`'], ['tad_web_homework', 'CateID', '`CateID`'], ['tad_web_homework', 'WebID_PostDate', '`WebID`,`HomeworkPostDate`'], ['tad_web_homework_content', 'WebID', '`WebID`'],
            ['tad_web_link', 'CateID', '`CateID`'], ['tad_web_link', 'WebID', '`WebID`'], ['tad_web_link_mems', 'WebID', '`WebID`'], ['tad_web_link_mems', 'CateID', '`CateID`'], ['tad_web_mail_log', 'ColSN', '`ColSN`'],
            ['tad_web_news', 'CateID', '`CateID`'], ['tad_web_news', 'WebID', '`WebID`'], ['tad_web_news', 'WebID_Enable_Date', '`WebID`,`NewsEnable`,`NewsDate`'], ['tad_web_page', 'CateID', '`CateID`'], ['tad_web_page', 'WebID', '`WebID`'],
            ['tad_web_plugins', 'WebID', '`WebID`'], ['tad_web_power', 'WebID', '`WebID`'], ['tad_web_schedule', 'CateID', '`CateID`'], ['tad_web_schedule', 'WebID', '`WebID`'],
            ['tad_web_video', 'CateID', '`CateID`'], ['tad_web_video', 'WebID', '`WebID`'], ['tad_web_works', 'CateID', '`CateID`'], ['tad_web_works', 'WebID', '`WebID`'],
        ];
        foreach ($indexes as $definition) {
            $addIndex($definition[0], $definition[1], $definition[2], $definition[3] ?? false);
        }

        foreach (['tad_web', 'tad_web_account', 'tad_web_action', 'tad_web_action_gphotos', 'tad_web_assistant_post', 'tad_web_blocks', 'tad_web_calendar', 'tad_web_cate', 'tad_web_cate_assistant', 'tad_web_config', 'tad_web_discuss', 'tad_web_files', 'tad_web_files_center', 'tad_web_homework', 'tad_web_homework_content', 'tad_web_link', 'tad_web_link_mems', 'tad_web_mail_log', 'tad_web_mem_parents', 'tad_web_mems', 'tad_web_news', 'tad_web_notice', 'tad_web_page', 'tad_web_plugins', 'tad_web_plugins_setup', 'tad_web_power', 'tad_web_roles', 'tad_web_schedule', 'tad_web_schedule_data', 'tad_web_tags', 'tad_web_video', 'tad_web_works', 'tad_web_works_content'] as $name) {
            $convert($name);
        }
    }

    // 檢查欄位是否存在
    public static function chk_col($table, $col)
    {
        global $xoopsDB;
        $sql    = "SHOW COLUMNS FROM {$table} LIKE '{$col}'";
        $result = $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);
        return $xoopsDB->getRowsNum($result) > 0;
    }

    // 檢查資料表是否存在
    public static function chk_table($table)
    {
        global $xoopsDB;
        $sql    = "SHOW TABLES LIKE '{$table}'";
        $result = $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);
        return $xoopsDB->getRowsNum($result) > 0;
    }

    public static function del_dir_plugins_json()
    {
        if (file_exists(XOOPS_ROOT_PATH . '/uploads/tad_web/dir_plugins.json')) {
            unlink(XOOPS_ROOT_PATH . '/uploads/tad_web/dir_plugins.json');
        }
    }

    public static function drop_menu_plugin()
    {
        global $xoopsDB;

        unlink(XOOPS_ROOT_PATH . '/modules/tad_web/menu.php');
        Utility::delete_directory(XOOPS_ROOT_PATH . '/modules/tad_web/plugins/menu');

        $sql = 'DROP TABLE IF EXISTS ' . $xoopsDB->prefix('tad_web_menu');
        $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);

        $sql = 'select ConfigValue, WebID from ' . $xoopsDB->prefix('tad_web_config') . '
        where ConfigName="web_plugin_enable_arr" and ConfigValue like "%,menu%"';
        $result = $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);
        while (list($ConfigValue, $WebID) = $xoopsDB->fetchRow($result)) {
            $ConfigValue = str_replace(',menu', '', $ConfigValue);
            $sql         = 'update ' . $xoopsDB->prefix('tad_web_config') . " set `ConfigValue`='$ConfigValue'
            where `WebID`='{$WebID}' and `ConfigName`='web_plugin_enable_arr' ";
            $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);
        }
        $sql = 'delete from ' . $xoopsDB->prefix('tad_web_plugins') . " where `PluginDirname`='menu'";
        $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);

        $sql = 'delete from ' . $xoopsDB->prefix('tad_web_plugins_setup') . " where `plugin`='menu'";
        $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);

        $sql = 'delete from ' . $xoopsDB->prefix('tad_web_power') . " where `col_name`='MenuID'";
        $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);

        $sql = 'delete from ' . $xoopsDB->prefix('tad_web_cate') . " where `ColName`='menu'";
        $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);

        $sql = 'select ConfigValue, WebID from ' . $xoopsDB->prefix('tad_web_config') . '
        where `ConfigName` = "menu_font_size"';
        $result = $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);
        while (list($ConfigValue, $WebID) = $xoopsDB->fetchRow($result)) {
            if (is_numeric($ConfigValue)) {
                if ($ConfigValue < 12) {
                    $ConfigValue = 12;
                } elseif ($ConfigValue > 30) {
                    $ConfigValue = 30;
                }

                $p = 100 + ($ConfigValue - 16);

                $sql = 'update ' . $xoopsDB->prefix('tad_web_config') . " set `ConfigValue`='{$p}%'
                where `WebID`='{$WebID}' and `ConfigName`='menu_font_size' ";
                $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);
                clear_tad_web_config($WebID);
            }
        }
    }

    public static function chk_sql_install()
    {
        global $xoopsDB;
        require_once XOOPS_ROOT_PATH . '/modules/tad_web/function.php';
        $dir_plugins = get_dir_plugins();
        //die(var_export($dir_plugins));
        $sort = 1;
        foreach ($dir_plugins as $dirname) {
            require XOOPS_ROOT_PATH . "/modules/tad_web/plugins/{$dirname}/config.php";
            if (!empty($pluginConfig['sql'])) {
                foreach ($pluginConfig['sql'] as $sql_name) {
                    $sql    = 'SELECT count(*) FROM ' . $xoopsDB->prefix($sql_name);
                    $result = $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);
                    if (empty($result)) {
                        $xoopsDB->queryFromFile(XOOPS_ROOT_PATH . "/modules/tad_web/plugins/{$dirname}/mysql.sql");
                    }
                }
            }
            $sql = 'replace into ' . $xoopsDB->prefix('tad_web_plugins') . " (`PluginDirname`, `PluginTitle`, `PluginSort`, `PluginEnable`, `WebID`) values('{$dirname}', '{$pluginConfig['name']}', '{$sort}', '1', '0')";
            $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);
            $sort++;
            $display_plugins[] = $dirname;
        }
        save_web_config('web_plugin_display_arr', implode(',', $display_plugins), 0);
    }

    //擷取網站網址、名稱、站長信箱、多人網頁版本、子網站數等資訊以供統計或日後更新通知
    public static function add_log($status)
    {
        global $xoopsConfig, $xoopsDB;
        $moduleHandler = xoops_getHandler('module');
        $xoopsModule   = $moduleHandler->getByDirname('tad_web');
        $version       = $xoopsModule->version();
        if ('install' === $status) {
            $web_amount = 0;
        } else {
            $sql    = 'SELECT * FROM ' . $xoopsDB->prefix('tad_web') . " WHERE `WebEnable`='1' ORDER BY WebSort";
            $result = $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);

            $web_amount = $xoopsDB->getRowsNum($result);
        }
        $sitename      = urlencode($xoopsConfig['sitename']);
        $add_count_url = 'https://class.tn.edu.tw/modules/apply/status.php?url=' . XOOPS_URL . "&web_name={$sitename}&version={$version}&web_amount={$web_amount}&email={$xoopsConfig['adminmail']}&status={$status}";
        if (function_exists('curl_init')) {
            $ch      = curl_init();
            $timeout = 5;
            curl_setopt($ch, CURLOPT_URL, $add_count_url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
            curl_exec($ch);
            curl_close($ch);
        } elseif (function_exists('file_get_contents')) {
            file_get_contents($add_count_url);
        } else {
            $handle = fopen($add_count_url, 'rb');
            stream_get_contents($handle);
            fclose($handle);
        }
    }

    public static function uninstall_sql()
    {
        global $xoopsDB;
        require XOOPS_ROOT_PATH . '/modules/tad_web/function.php';
        $dir_plugins = get_dir_plugins();
        foreach ($dir_plugins as $dirname) {
            require XOOPS_ROOT_PATH . "/modules/tad_web/plugins/{$dirname}/config.php";
            if (!empty($pluginConfig['sql'])) {
                foreach ($pluginConfig['sql'] as $sql_name) {
                    $sql = 'DROP TABLE ' . $xoopsDB->prefix($sql_name);
                    $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);
                }
            }
        }

        return true;
    }

    //修正區塊所屬plugin
    public static function fiexd_block()
    {
        global $xoopsDB;
        require_once XOOPS_ROOT_PATH . '/modules/tad_web/function.php';
        $allBlockConfig = get_dir_blocks();
        foreach ($allBlockConfig as $plugin => $BlockConfig) {
            foreach ($BlockConfig as $BlockName => $Block) {
                $sql = 'update  ' . $xoopsDB->prefix('tad_web_blocks') . " set plugin='{$plugin}' where `BlockName`='{$BlockName}' and plugin!='{$plugin}'";
                $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);
            }
        }
    }

    //新增外掛的sql檔
    public static function chk_sql_update()
    {
        global $xoopsDB;
        require_once XOOPS_ROOT_PATH . '/modules/tad_web/function.php';
        $dir_plugins = get_dir_plugins();
        foreach ($dir_plugins as $dirname) {
            require XOOPS_ROOT_PATH . "/modules/tad_web/plugins/{$dirname}/config.php";
            if (!empty($pluginConfig['sql'])) {
                foreach ($pluginConfig['sql'] as $sql_name) {
                    $sql    = 'SELECT count(*) FROM ' . $xoopsDB->prefix($sql_name);
                    $result = $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);
                    if (empty($result)) {
                        $xoopsDB->queryFromFile(XOOPS_ROOT_PATH . "/modules/tad_web/plugins/{$dirname}/mysql.sql");
                    }
                }
            }
        }
    }

    //檢查是否有新區塊，若有安裝之。
    public static function chk_newblock()
    {
        global $xoopsDB;

        //取得應有的所有區塊
        $all_blocks   = get_all_blocks();
        $block_plugin = get_all_blocks('plugin');
        $block_config = get_all_blocks('config');
        // die(var_export($block_config));

        //找出所有網站
        $allWebID = [];
        $sql      = 'SELECT WebID FROM `' . $xoopsDB->prefix('tad_web') . '` GROUP BY `WebID`';
        $result   = $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);
        while (list($WebID) = $xoopsDB->fetchRow($result)) {
            $allWebID[] = $WebID;
        }

        //修正自訂區塊名稱（並用序號排序）
        $sql    = 'SELECT BlockID,BlockName,BlockTitle,BlockContent,WebID FROM ' . $xoopsDB->prefix('tad_web_blocks') . " WHERE plugin='custom' ORDER BY BlockID";
        $result = $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);
        while (list($BlockID, $BlockName, $BlockTitle, $BlockContent, $WebID) = $xoopsDB->fetchRow($result)) {
            $BlockTitle   = $xoopsDB->escape($BlockTitle);
            $BlockContent = $xoopsDB->escape($BlockContent);

            $new_name = "custom_{$WebID}_{$BlockID}";
            if ($new_name != $BlockName) {
                //修改自己
                $sql = 'update `' . $xoopsDB->prefix('tad_web_blocks') . "` set `BlockName`='{$new_name}' where `BlockID`='{$BlockID}'";
                $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);
            }

            //搜尋該自訂區塊有無分享區塊
            $sql2    = 'select BlockID from ' . $xoopsDB->prefix('tad_web_blocks') . " where (BlockTitle='{$BlockTitle}' or BlockContent='{$BlockContent}') and WebID='{$WebID}' and plugin='share'";
            $result2 = $xoopsDB->queryF($sql2) or Utility::web_error($sql2);

            list($share_BlockID) = $xoopsDB->fetchRow($result2);

            //若有分享區塊
            if ($share_BlockID) {
                //修改分享區塊
                $sql = 'update `' . $xoopsDB->prefix('tad_web_blocks') . "` set `BlockName`='share_{$WebID}_{$share_BlockID}', `ShareFrom`='{$BlockID}' where BlockID='{$share_BlockID}'";
                $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);

                //修改其他網站已經使用該分享區塊的
                $sql = 'update `' . $xoopsDB->prefix('tad_web_blocks') . "` set `ShareFrom`='{$share_BlockID}' where (BlockTitle='{$BlockTitle}' or BlockContent='{$BlockContent}') and plugin='custom' and WebID!='{$WebID}'";
                $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);
            }
        }

        foreach ($allWebID as $WebID) {
            //找出目前已安裝的區塊
            $sql    = 'select BlockID,BlockName,BlockConfig from ' . $xoopsDB->prefix('tad_web_blocks') . " where WebID='{$WebID}' and  plugin!='custom' and plugin!='share'";
            $result = $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);
            while (list($BlockID, $BlockName, $BlockConfig) = $xoopsDB->fetchRow($result)) {
                $db_blocks[$WebID][$BlockName]                  = $BlockName;
                $db_blocks_config[$WebID][$BlockName][$BlockID] = $BlockConfig;
            }
        }

        //區塊的設定值
        // die(var_export($block_config));
        // array (
        //   'list_web_adm' => NULL,
        //   'list_web_student' => NULL,
        //   'list_account' => array ('limit' => 10)
        // )

        //現有區塊資料（含不在資料庫中的）
        // die(var_export($all_blocks));

        //所有的網站編號陣列
        // die(var_export($allWebID));

        //資料庫中，已經有哪些網站，有了哪些區塊資訊
        // die(var_export($db_blocks));

        //資料庫中，個個網站已存在的區塊設定
        // die(var_export($db_blocks_config));

        // echo "<h1>" . PHP_VERSION_ID . "</h1>";

        // die('$JSON_UNESCAPED_UNICODE=' . $JSON_UNESCAPED_UNICODE);
        //安裝新區塊
        foreach ($all_blocks as $BlockName => $BlockTitle) {
            foreach ($allWebID as $WebID) {
                //若該區塊還沒有安裝在該網站
                if (!in_array($BlockName, $db_blocks[$WebID])) {
                    if (is_array($block_config[$BlockName])) {
                        $config = json_encode($block_config[$BlockName], JSON_UNESCAPED_UNICODE);
                    } else {
                        $config = '';
                    }
                    $config = str_replace('{{WebID}}', $WebID, $config);
                    $sql    = 'insert into `'
                    . $xoopsDB->prefix('tad_web_blocks')
                        . "` (`BlockName`, `BlockCopy`, `BlockTitle`, `BlockContent`, `BlockEnable`, `BlockConfig`, `BlockPosition`, `BlockSort`, `WebID`, `plugin`, `ShareFrom`) values('{$BlockName}', '0', '{$BlockTitle}', '', '1', '{$config}', 'uninstall', 0, '{$WebID}', '{$block_plugin[$BlockName]}',0)";
                    $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);
                } else {
                    //檢查區塊設定值是否需要更新

                    //找出某區塊安裝在該網站的 $BlockID 以及現有設定
                    foreach ($db_blocks_config[$WebID][$BlockName] as $BlockID => $BlockConfig) {
                        $new_config = $db_config = [];

                        //已安裝區塊的設定值陣列
                        $db_config = json_decode($BlockConfig, true);

                        if (is_array($block_config[$BlockName])) {
                            // echo "<h3>$BlockName</h3>";
                            foreach ($block_config[$BlockName] as $config_name => $def_value) {
                                // echo "<h4>{$config_name}：{$def_value} (\$db_config[\$config_name]={$db_config[$config_name]})</h4>";
                                if (isset($db_config[$config_name]) and '' != $db_config[$config_name]) {
                                    // echo "有預設值：{$db_config[$config_name]}<br>";
                                    $new_config[$config_name] = $db_config[$config_name];
                                } else {
                                    // echo "沒有預設值：{$def_value}<br>";
                                    $new_config[$config_name] = $def_value;
                                }
                            }
                        }

                        // echo "新設定值為：" . var_export($new_config, true) . "<br>";
                        //更新設定值

                        $new_block_config = json_encode($new_config, JSON_UNESCAPED_UNICODE);

                        // echo "新設定：" . $new_block_config . "<br>";

                        $new_block_config = str_replace('{{WebID}}', $WebID, $new_block_config);
                        $sql              = 'update `' . $xoopsDB->prefix('tad_web_blocks') . "` set `BlockConfig`='{$new_block_config}' where `BlockID`='{$BlockID}'";
                        // echo "<div>$sql</div>";
                        $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);
                    }
                }
            }
        }
        // exit;
    }

    //檢查是否有新區塊，若有安裝之。
    public static function modify_share_block()
    {
        global $xoopsDB;

        //修正自訂區塊名稱（並用序號排序）
        if (!_IS_EZCLASS) {
            $sql    = 'SELECT BlockID,BlockName,BlockTitle,BlockContent,WebID FROM ' . $xoopsDB->prefix('tad_web_blocks') . " WHERE plugin='custom' ORDER BY BlockID";
            $result = $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);
            while (list($BlockID, $BlockName, $BlockTitle, $BlockContent, $WebID) = $xoopsDB->fetchRow($result)) {
                $BlockTitle   = $xoopsDB->escape($BlockTitle);
                $BlockContent = $xoopsDB->escape($BlockContent);

                $new_name = "custom_{$WebID}_{$BlockID}";
                if ($new_name != $BlockName) {
                    //修改自己
                    $sql = 'update `' . $xoopsDB->prefix('tad_web_blocks') . "` set `BlockName`='{$new_name}' where `BlockID`='{$BlockID}'";
                    $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);
                }

                //搜尋該自訂區塊有無分享區塊
                $sql2    = 'select BlockID from ' . $xoopsDB->prefix('tad_web_blocks') . " where (BlockTitle='{$BlockTitle}' or BlockContent='{$BlockContent}') and WebID='{$WebID}' and plugin='share'";
                $result2 = $xoopsDB->queryF($sql2) or Utility::web_error($sql2);

                list($share_BlockID) = $xoopsDB->fetchRow($result2);

                //若有分享區塊
                if ($share_BlockID) {
                    //修改分享區塊
                    $sql = 'update `' . $xoopsDB->prefix('tad_web_blocks') . "` set `BlockName`='share_{$WebID}_{$share_BlockID}', `ShareFrom`='{$BlockID}' where BlockID='{$share_BlockID}'";
                    $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);

                    //修改其他網站已經使用該分享區塊的
                    $sql = 'update `' . $xoopsDB->prefix('tad_web_blocks') . "` set `ShareFrom`='{$share_BlockID}' where (BlockTitle='{$BlockTitle}' or BlockContent='{$BlockContent}') and plugin='custom' and WebID!='{$WebID}'";
                    $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);
                }
            }
        }
    }

    //重作選單快取檔
    public static function go_update_var()
    {
        global $xoopsDB;

        $sql = 'update ' . $xoopsDB->prefix('tad_web_config') . " set `ConfigName`='web_plugin_display_arr' WHERE `ConfigName` = 'web_setup_show_arr'";
        $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);

        require_once XOOPS_ROOT_PATH . '/modules/tad_web/function.php';

        $Webs = getAllWebInfo('WebTitle');
        foreach ($Webs as $WebID => $WebTitle) {
            mk_menu_var_file($WebID);
        }
        clear_tad_web_config($WebID);
    }

    //執行各外掛更新
    public static function chk_plugin_update()
    {
        global $xoopsDB;
        require_once XOOPS_ROOT_PATH . '/modules/tad_web/function.php';
        $dir_plugins = get_dir_plugins();
        foreach ($dir_plugins as $dirname) {
            $update_file = XOOPS_ROOT_PATH . "/modules/tad_web/plugins/{$dirname}/onUpdate.php";
            if (file_exists($update_file)) {
                require_once $update_file;
            }
        }
    }

    //刪除錯誤的重複欄位及樣板檔
    public static function chk_tad_web_block()
    {
        global $xoopsDB;
        //die(var_export($xoopsConfig));
        require XOOPS_ROOT_PATH . '/modules/tad_web/xoops_version.php';

        //先找出該有的區塊以及對應樣板
        foreach ($modversion['blocks'] as $i => $block) {
            $show_func                = $block['show_func'];
            $tpl_file_arr[$show_func] = $block['template'];
            $tpl_desc_arr[$show_func] = $block['description'];
        }

        //找出目前所有的樣板檔
        $sql    = 'SELECT bid,name,visible,show_func,template FROM ' . $xoopsDB->prefix('newblocks') . " WHERE `dirname` = 'tad_web' ORDER BY `func_num`";
        $result = $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);
        while (list($bid, $name, $visible, $show_func, $template) = $xoopsDB->fetchRow($result)) {
            //假如現有的區塊和樣板對不上就刪掉
            if ($template != $tpl_file_arr[$show_func]) {
                $sql = 'delete from ' . $xoopsDB->prefix('newblocks') . " where bid='{$bid}'";
                $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);

                //連同樣板以及樣板實體檔案也要刪掉
                $sql = 'delete from ' . $xoopsDB->prefix('tplfile') . ' as a left join ' . $xoopsDB->prefix('tplsource') . "  as b on a.tpl_id=b.tpl_id where a.tpl_refid='$bid' and a.tpl_module='tad_web' and a.tpl_type='block'";
                $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);
            } else {
                $sql = 'update ' . $xoopsDB->prefix('tplfile') . " set tpl_file='{$template}' , tpl_desc='{$tpl_desc_arr[$show_func]}' where tpl_refid='{$bid}'";
                $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);
            }
        }
    }

    //修改討論區相關欄位
    public static function chk_tad_web_discuss()
    {
        global $xoopsDB;
        $table = $xoopsDB->prefix('tad_web_discuss');

        // 修改 DisscussCounter 為 DiscussCounter
        if (!self::chk_col($table, 'DiscussCounter')) {
            $sql = "ALTER TABLE `{$table}` CHANGE `DisscussCounter` `DiscussCounter` SMALLINT(6) UNSIGNED NOT NULL";
            $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);
        }

        // 新增 uid 欄位
        if (!self::chk_col($table, 'uid')) {
            $sql = "ALTER TABLE `{$table}` ADD `uid` SMALLINT(6) UNSIGNED NOT NULL AFTER `WebID`";
            $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);
        }

        // 新增 MemID 欄位
        if (!self::chk_col($table, 'MemID')) {
            $sql = "ALTER TABLE `{$table}` ADD `MemID` SMALLINT(6) UNSIGNED NOT NULL AFTER `uid`";
            $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);
        }

        // 新增 MemName 欄位
        if (!self::chk_col($table, 'MemName')) {
            $sql = "ALTER TABLE `{$table}` ADD `MemName` VARCHAR(255) NOT NULL DEFAULT '' AFTER `MemID`";
            $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);
        }
    }

    //整理 tad_web_files_center
    public static function chk_tad_web_files_center()
    {
        global $xoopsDB;
        $table = $xoopsDB->prefix('tad_web_files_center');

        //新增 original_filename, hash_filename, sub_dir 欄位
        if (!self::chk_col($table, 'original_filename')) {
            $sql = "ALTER TABLE {$table}
                ADD `original_filename` VARCHAR(255) NOT NULL DEFAULT '',
                ADD `hash_filename` VARCHAR(255) NOT NULL DEFAULT '',
                ADD `sub_dir` VARCHAR(255) NOT NULL DEFAULT ''";
            $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);

            $sql = "update {$table} set `original_filename`=`description`";
            $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);
        }

        //新增 upload_date, uid, tag 欄位
        if (!self::chk_col($table, 'tag')) {
            $sql = "ALTER TABLE {$table}
            ADD `upload_date` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '上傳時間',
            ADD `uid` MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT 0 COMMENT '上傳者',
            ADD `tag` VARCHAR(255) NOT NULL DEFAULT '' COMMENT '註記'
            ";
            $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);
        }

        //將各班檔案收攏到各個子目錄下
        self::move_files_to_subdir();
    }

    private static function move_files_to_subdir()
    {
        global $xoopsDB;

        $updir = XOOPS_ROOT_PATH . '/uploads/tad_web';
        $os    = (PATH_SEPARATOR === ':') ? 'linux' : 'win';

        //修正子目錄，並找出實體檔案沒有真的在子目錄下的
        $sql    = 'SELECT `files_sn`,`col_name`,`col_sn`,`kind`,`file_name`,`sub_dir` FROM ' . $xoopsDB->prefix('tad_web_files_center') . " WHERE `sub_dir` LIKE '//%'";
        $result = $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);
        while (list($files_sn, $col_name, $col_sn, $kind, $file_name, $sub_dir) = $xoopsDB->fetchRow($result)) {
            $sub_dir = str_replace('//', '/', $sub_dir);
            $typedir = 'img' === $kind ? 'image' : 'file';

            $sql = 'update  ' . $xoopsDB->prefix('tad_web_files_center') . " set `sub_dir`='{$sub_dir}'  where `files_sn`='{$files_sn}'";
            $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);

            if (!file_exists("{$updir}{$sub_dir}/{$typedir}/{$file_name}")) {
                Utility::mk_dir("{$updir}{$sub_dir}");
                Utility::mk_dir("{$updir}{$sub_dir}/{$typedir}");

                $from = "{$updir}/{$typedir}/{$file_name}";
                $to   = "{$updir}{$sub_dir}/{$typedir}/{$file_name}";

                if ('win' === $os and _CHARSET === 'UTF-8') {
                    $from = iconv(_CHARSET, 'Big5', $from);
                    $to   = iconv(_CHARSET, 'Big5', $to);
                } elseif ('linux' === $os and _CHARSET === 'Big5') {
                    $from = iconv(_CHARSET, 'UTF-8', $from);
                    $to   = iconv(_CHARSET, 'UTF-8', $to);
                }

                Utility::full_copy($from, $to);
                if ('image' === $typedir) {
                    Utility::mk_dir("{$updir}{$sub_dir}");
                    Utility::mk_dir("{$updir}{$sub_dir}/{$typedir}");
                    Utility::mk_dir("{$updir}{$sub_dir}/{$typedir}/.thumbs");
                    $from = "{$updir}/{$typedir}/.thumbs/{$file_name}";
                    $to   = "{$updir}{$sub_dir}/{$typedir}/.thumbs/{$file_name}";

                    if ('win' === $os and _CHARSET === 'UTF-8') {
                        $from = iconv(_CHARSET, 'Big5', $from);
                        $to   = iconv(_CHARSET, 'Big5', $to);
                    } elseif ('linux' === $os and _CHARSET === 'Big5') {
                        $from = iconv(_CHARSET, 'UTF-8', $from);
                        $to   = iconv(_CHARSET, 'UTF-8', $to);
                    }

                    Utility::full_copy($from, $to);
                }
            }
        }

        //找出沒有放到子目錄的
        $sql    = 'SELECT `files_sn`,`col_name`,`col_sn`,`kind`,`file_name`,`sub_dir` FROM ' . $xoopsDB->prefix('tad_web_files_center') . '';
        $result = $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);
        while (list($files_sn, $col_name, $col_sn, $kind, $file_name, $sub_dir) = $xoopsDB->fetchRow($result)) {
            $typedir = 'img' === $kind ? 'image' : 'file';
            $WebID   = (int) mb_substr($sub_dir, 1);
            if (empty($WebID)) {
                if ('WebOwner' === $col_name or 'WebLogo' === $col_name) {
                    $WebID = $col_sn;
                } elseif ('MemID' === $col_name) {
                    $sql         = 'select `WebID` from ' . $xoopsDB->prefix('tad_web_link_mems') . " where `MemID` = '{$col_sn}'";
                    $result2     = $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);
                    list($WebID) = $xoopsDB->fetchRow($result2);
                } elseif ('ActionID' === $col_name) {
                    $sql         = 'select `WebID` from ' . $xoopsDB->prefix('tad_web_action') . " where `ActionID` = '{$col_sn}'";
                    $result2     = $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);
                    list($WebID) = $xoopsDB->fetchRow($result2);
                } elseif ('fsn' === $col_name) {
                    $sql         = 'select `WebID` from ' . $xoopsDB->prefix('tad_web_files') . " where `fsn` = '{$col_sn}'";
                    $result2     = $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);
                    list($WebID) = $xoopsDB->fetchRow($result2);
                } elseif ('NewsID' === $col_name) {
                    $sql         = 'select `WebID` from ' . $xoopsDB->prefix('tad_web_news') . " where `NewsID` = '{$col_sn}'";
                    $result2     = $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);
                    list($WebID) = $xoopsDB->fetchRow($result2);
                }
            }

            if (!$WebID) {
                continue;
            }
            // Skip if no WebID found to avoid path error

            $sql = 'update ' . $xoopsDB->prefix('tad_web_files_center') . " set `sub_dir`='/{$WebID}'  where `files_sn`='{$files_sn}'";
            $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);

            Utility::mk_dir("{$updir}/{$WebID}");
            Utility::mk_dir("{$updir}/{$WebID}/{$typedir}");
            if ('image' === $typedir) {
                Utility::mk_dir("{$updir}/{$WebID}/{$typedir}");
                Utility::mk_dir("{$updir}/{$WebID}/{$typedir}/.thumbs");
            }

            $from = "{$updir}/{$typedir}/{$file_name}";
            $to   = "{$updir}/{$WebID}/{$typedir}/{$file_name}";

            if ('win' === $os && _CHARSET === 'UTF-8') {
                $from = iconv(_CHARSET, 'Big5', $from);
                $to   = iconv(_CHARSET, 'Big5', $to);
            } elseif ('linux' === $os && _CHARSET === 'Big5') {
                $from = iconv(_CHARSET, 'UTF-8', $from);
                $to   = iconv(_CHARSET, 'UTF-8', $to);
            }
            if (file_exists($from)) {
                Utility::full_copy($from, $to);
                if ('image' === $typedir) {
                    Utility::mk_dir("{$updir}/{$WebID}");
                    Utility::mk_dir("{$updir}/{$WebID}/{$typedir}");
                    Utility::mk_dir("{$updir}/{$WebID}/{$typedir}/.thumbs");

                    $from = "{$updir}/{$typedir}/.thumbs/{$file_name}";
                    $to   = "{$updir}/{$WebID}/{$typedir}/.thumbs/{$file_name}";

                    if ('win' === $os && _CHARSET === 'UTF-8') {
                        $from = iconv(_CHARSET, 'Big5', $from);
                        $to   = iconv(_CHARSET, 'Big5', $to);
                    } elseif ('linux' === $os && _CHARSET === 'Big5') {
                        $from = iconv(_CHARSET, 'UTF-8', $from);
                        $to   = iconv(_CHARSET, 'UTF-8', $to);
                    }
                    Utility::full_copy($from, $to);
                }
            }
        }
    }

    //修改分類名稱欄位名稱
    public static function chk_tad_web_cate()
    {
        global $xoopsDB;
        $table = $xoopsDB->prefix('tad_web_cate');
        if (!self::chk_col($table, 'CateName')) {
            $sql = "ALTER TABLE {$table} CHANGE `CateName` `CateName` VARCHAR(255) NOT NULL DEFAULT ''";
            $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);
        }
    }

    //新增作品分享表格
    public static function chk_chk8()
    {
        global $xoopsDB;
        $sql    = 'SELECT count(*) FROM ' . $xoopsDB->prefix('tad_web_works');
        $result = $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);
        if (empty($result)) {
            return true;
        }

        return false;
    }

    public static function go_update8()
    {
        global $xoopsDB;
        $sql = 'CREATE TABLE `' . $xoopsDB->prefix('tad_web_works') . "` (
      `WorksID` SMALLINT(5) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '檔案流水號',
      `CateID` SMALLINT(6) UNSIGNED NOT NULL DEFAULT 0,
      `WebID` SMALLINT(6) UNSIGNED NOT NULL DEFAULT 0 COMMENT '所屬班級',
      `WorkName` VARCHAR(255) NOT NULL DEFAULT '' COMMENT '活動名稱',
      `WorkDesc` TEXT NOT NULL COMMENT '活動說明',
      `uid` MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT 0 COMMENT '上傳者',
      `WorksDate` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '日期',
      `WorksCount` SMALLINT(6) UNSIGNED NOT NULL DEFAULT 0 COMMENT '人氣',
    PRIMARY KEY (`WorksID`)
    ) ENGINE=MyISAM";
        $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);
    }

    //新增聯絡簿
    public static function chk_chk9()
    {
        global $xoopsDB;
        $sql    = 'SELECT count(*) FROM ' . $xoopsDB->prefix('tad_web_homework');
        $result = $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);
        if (empty($result)) {
            return true;
        }

        return false;
    }

    public static function go_update9()
    {
        global $xoopsDB;
        $sql = 'CREATE TABLE `' . $xoopsDB->prefix('tad_web_homework') . "` (
      `HomeworkID` SMALLINT(6) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '編號',
      `CateID` SMALLINT(6) UNSIGNED NOT NULL DEFAULT 0,
      `WebID` SMALLINT(6) UNSIGNED NOT NULL DEFAULT 0 COMMENT '所屬班級',
      `HomeworkTitle` VARCHAR(255) NOT NULL DEFAULT '' COMMENT '標題',
      `HomeworkContent` TEXT NOT NULL COMMENT '內容',
      `HomeworkDate` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '發布日期',
      `toCal` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '加到行事曆',
      `HomeworkCounter` SMALLINT(6) UNSIGNED NOT NULL DEFAULT 0 COMMENT '人氣',
      `uid` MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT 0 COMMENT '發布者',
    PRIMARY KEY (`HomeworkID`)
    ) ENGINE=MyISAM";
        $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);

        $sql    = 'SELECT * FROM `' . $xoopsDB->prefix('tad_web_news') . "` WHERE NewsKind='homework'";
        $result = $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);
        while (false !== ($all = $xoopsDB->fetchArray($result))) {
            foreach ($all as $k => $v) {
                $$k = $v;
            }

            $sql = 'insert into `' . $xoopsDB->prefix('tad_web_homework') . "` (`WebID`, `HomeworkTitle`, `HomeworkContent`, `HomeworkDate`, `toCal`, `HomeworkCounter`, `uid`) values('{$WebID}', '{$NewsTitle}', '{$NewsContent}', '{$NewsDate}', '{$toCal}', '{$NewsCounter}', '{$uid}')";
            $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);

            $sql = 'delete from `' . $xoopsDB->prefix('tad_web_news') . "` where `NewsID`='{$NewsID}'";
            $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);
        }
    }

    //建立相關資料表
    public static function chk_tad_web_tables()
    {
        global $xoopsDB;

        // tad_web_plugins
        if (!self::chk_table($xoopsDB->prefix('tad_web_plugins'))) {
            $sql = "CREATE TABLE `" . $xoopsDB->prefix('tad_web_plugins') . "` (
              `PluginDirname` VARCHAR(100) NOT NULL COMMENT '目錄名稱',
              `PluginTitle` VARCHAR(255) NOT NULL COMMENT '外掛名稱',
              `PluginSort` SMALLINT(6) UNSIGNED NOT NULL DEFAULT 0 COMMENT '排序',
              `PluginEnable` ENUM('1','0') NOT NULL DEFAULT '1' COMMENT '狀態',
              `WebID` SMALLINT(6) UNSIGNED NOT NULL DEFAULT 0 COMMENT '所屬班級',
            PRIMARY KEY (`PluginDirname`,`WebID`)
            ) ENGINE=MyISAM;";
            $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);
        }

        // tad_web_roles
        if (!self::chk_table($xoopsDB->prefix('tad_web_roles'))) {
            $sql = "CREATE TABLE `" . $xoopsDB->prefix('tad_web_roles') . "` (
              `uid` MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT 0 COMMENT '使用者',
              `role` VARCHAR(255) NOT NULL COMMENT '角色',
              `term` DATE  NOT NULL DEFAULT '0000-00-00' COMMENT '期限',
              `enable` ENUM('1','0') NOT NULL DEFAULT '1' COMMENT '狀態',
              `WebID` SMALLINT(6) UNSIGNED NOT NULL DEFAULT 0 COMMENT '所屬班級',
            PRIMARY KEY (`WebID`,`uid`,`role`)
            ) ENGINE=MyISAM;";
            $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);
        }

        // tad_web_plugins_setup
        if (!self::chk_table($xoopsDB->prefix('tad_web_plugins_setup'))) {
            $sql = "CREATE TABLE `" . $xoopsDB->prefix('tad_web_plugins_setup') . "` (
            `WebID` SMALLINT(5) UNSIGNED NOT NULL DEFAULT 0 COMMENT '所屬網站',
            `plugin` VARCHAR(100) NOT NULL DEFAULT '' COMMENT '所屬外掛',
            `name` VARCHAR(100) NOT NULL DEFAULT '' COMMENT '設定名稱',
            `type` VARCHAR(255) NOT NULL DEFAULT '' COMMENT '欄位類型',
            `value` TEXT NOT NULL COMMENT '設定值',
            PRIMARY KEY  (`WebID`,`plugin`,`name`)
            ) ENGINE=MyISAM";
            $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);
        }

        // tad_web_power
        if (!self::chk_table($xoopsDB->prefix('tad_web_power'))) {
            $sql = "CREATE TABLE `" . $xoopsDB->prefix('tad_web_power') . "` (
            `WebID` SMALLINT(5) UNSIGNED NOT NULL DEFAULT 0 COMMENT '所屬網站',
            `col_name` VARCHAR(100) NOT NULL DEFAULT '' COMMENT '權限名稱',
            `col_sn` MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT 0 COMMENT '對應編號',
            `power_name` VARCHAR(100) NOT NULL DEFAULT '' COMMENT '權限名稱',
            `power_val` VARCHAR(255) NOT NULL COMMENT '權限設定',
            PRIMARY KEY  (`WebID`,`col_name`,`power_name`)
            ) ENGINE=MyISAM";
            $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);

            $sql = 'ALTER TABLE ' . $xoopsDB->prefix('tad_web_files_center') . " CHANGE `files_sn` `files_sn` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '檔案流水號'";
            $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);

            $sql = 'ALTER TABLE ' . $xoopsDB->prefix('tad_web_blocks') . " CHANGE `BlockID` `BlockID` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '區塊流水號'";
            $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);
        }

        // tad_web_tags
        if (!self::chk_table($xoopsDB->prefix('tad_web_tags'))) {
            $sql = "CREATE TABLE `" . $xoopsDB->prefix('tad_web_tags') . "` (
            `WebID` SMALLINT(5) UNSIGNED NOT NULL  COMMENT '所屬網站',
            `col_name` VARCHAR(100) NOT NULL DEFAULT '' COMMENT '權限名稱',
            `col_sn` MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT 0 COMMENT '對應編號',
            `tag_name` VARCHAR(100) NOT NULL DEFAULT '' COMMENT '權限名稱',
            PRIMARY KEY  (`col_name`,`col_sn`,`tag_name`)
            ) ENGINE=MyISAM";
            $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);
        }

        // tad_web_notice
        if (!self::chk_table($xoopsDB->prefix('tad_web_notice'))) {
            $sql = "CREATE TABLE `" . $xoopsDB->prefix('tad_web_notice') . "` (
            `NoticeID` SMALLINT(5) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '通知編號',
            `NoticeTitle` VARCHAR(255) NOT NULL DEFAULT '' COMMENT '通知標題',
            `NoticeContent` TEXT NOT NULL  COMMENT '通知內容',
            `NoticeWeb` TEXT NOT NULL COMMENT '通知網站',
            `NoticeWho` VARCHAR(255) NOT NULL DEFAULT '' COMMENT '通知對象',
            `NoticeDate` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '通知日期',
            PRIMARY KEY  (`NoticeID`)
            ) ENGINE=MyISAM";
            $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);
        }

        // tad_web_mail_log
        if (!self::chk_table($xoopsDB->prefix('tad_web_mail_log'))) {
            $sql = 'CREATE TABLE `' . $xoopsDB->prefix('tad_web_mail_log') . "` (
            `ColName` VARCHAR(100) NOT NULL DEFAULT '' COMMENT '欄位名稱',
            `ColSN` SMALLINT(5) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '欄位編號',
            `WebID` SMALLINT(5) UNSIGNED NOT NULL  COMMENT '所屬網站',
            `Mail` VARCHAR(100) NOT NULL DEFAULT '' COMMENT '信箱',
            `MailDate` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '寄信日期',
            PRIMARY KEY  (`ColName`,`ColSN`,`WebID`,`Mail`)
            ) ENGINE=MyISAM";
            $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);
        }

        // tad_web_cate_assistant
        if (!self::chk_table($xoopsDB->prefix('tad_web_cate_assistant'))) {
            $sql = 'CREATE TABLE `' . $xoopsDB->prefix('tad_web_cate_assistant') . "` (
            `CateID` SMALLINT(6) UNSIGNED NOT NULL COMMENT '編號',
            `AssistantType` VARCHAR(100) NOT NULL DEFAULT '' COMMENT '用戶種類',
            `AssistantID` MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT 0 COMMENT '用戶ID',
            PRIMARY KEY (`CateID`,`AssistantType`,`AssistantID`)
            ) ENGINE=MyISAM";
            $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);
        }

        // tad_web_assistant_post
        if (!self::chk_table($xoopsDB->prefix('tad_web_assistant_post'))) {
            $sql = 'CREATE TABLE `' . $xoopsDB->prefix('tad_web_assistant_post') . "` (
            `plugin` VARCHAR(100) NOT NULL COMMENT '所屬外掛',
            `ColName` VARCHAR(100) NOT NULL DEFAULT '' COMMENT '欄位名稱',
            `ColSN` SMALLINT(5) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '欄位編號',
            `CateID` SMALLINT(6) UNSIGNED NOT NULL COMMENT '編號',
            `AssistantType` VARCHAR(100) NOT NULL DEFAULT '' COMMENT '用戶種類',
            `AssistantID` MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT 0 COMMENT '用戶ID',
            PRIMARY KEY (`plugin`,`ColName`,`ColSN`)
            ) ENGINE=MyISAM";
            $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);
        }
    }

    //建立與更新區塊資料表
    public static function chk_tad_web_blocks()
    {
        global $xoopsDB;
        $table = $xoopsDB->prefix('tad_web_blocks');

        // ck12: Create table if not exists
        if (!self::chk_table($table)) {
            require_once XOOPS_ROOT_PATH . '/modules/tad_web/function.php';
            $sql = "CREATE TABLE `" . $table . "` (
            `BlockID` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '區塊流水號',
            `BlockName` VARCHAR(100) NOT NULL COMMENT '區塊名稱',
            `BlockCopy` TINYINT(3) NOT NULL COMMENT '區塊份數',
            `BlockTitle` VARCHAR(255) NOT NULL COMMENT '區塊標題',
            `BlockContent` TEXT NOT NULL COMMENT '區塊內容',
            `BlockEnable` ENUM('1','0') NOT NULL DEFAULT '1' COMMENT '狀態',
            `BlockConfig` text NULL COMMENT '區塊設定值',
            `BlockPosition` VARCHAR(255) NOT NULL COMMENT '區塊位置',
            `BlockSort` SMALLINT(6) UNSIGNED NOT NULL DEFAULT 0 COMMENT '排序',
            `WebID` SMALLINT(6) UNSIGNED NOT NULL DEFAULT 0 COMMENT '所屬網站',
            `plugin` VARCHAR(100) NOT NULL COMMENT '所屬外掛',
            `ShareFrom` INT(10) UNSIGNED NOT NULL COMMENT '分享自',
            PRIMARY KEY (`BlockID`),
            UNIQUE KEY `BlockName_BlockCopy_WebID_plugin` (`BlockName`,`BlockCopy`,`WebID`,`plugin`)
            ) ENGINE=MyISAM;";
            $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);

            // Seed initial data
            self::seed_tad_web_blocks();
        }

        // ck18: Fix index if needed
        $sql    = "show keys from {$table} where Key_name='BlockName_BlockCopy_WebID_plugin'";
        $result = $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);
        if ($xoopsDB->getRowsNum($result) == 0) {
            $sql = "ALTER TABLE `{$table}` CHANGE `plugin` `plugin` VARCHAR(100) COLLATE 'utf8_general_ci' NOT NULL COMMENT '所屬外掛' AFTER `WebID`;";
            $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);

            $sql = "ALTER TABLE `{$table}` ADD UNIQUE `BlockName_BlockCopy_WebID_plugin` (`BlockName`, `BlockCopy`, `WebID`, `plugin`);";
            $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);
        }

        // ck19: Drop BlockShare
        if (self::chk_col($table, 'BlockShare')) {
            $sql = "ALTER TABLE {$table} DROP `BlockShare`";
            $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);
        }

        // ck19_1: Add ShareFrom
        if (!self::chk_col($table, 'ShareFrom')) {
            $sql = "ALTER TABLE {$table} ADD `ShareFrom` INT(10) UNSIGNED NOT NULL COMMENT '分享自'";
            $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);
        }
    }

    private static function seed_tad_web_blocks()
    {
        global $xoopsDB;
        //取得系統所有區塊
        $block_option = get_all_blocks();
        $block_plugin = get_all_blocks('plugin');
        $block_config = get_all_blocks('config');

        //存入既有設定
        $sql    = 'SELECT ConfigValue, WebID FROM `' . $xoopsDB->prefix('tad_web_config') . "` WHERE ConfigName='display_blocks'";
        $result = $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);
        while (list($ConfigValue, $WebID) = $xoopsDB->fetchRow($result)) {
            $Config = explode(',', $ConfigValue);
            $sort   = 1;
            foreach ($block_option as $func => $name) {
                if ($func === "list_{$block_plugin[$func]}") {
                    $BlockPosition = 'block4';
                } else {
                    $BlockPosition = 'side';
                }
                $BlockEnable = in_array($func, $Config) ? 1 : 0;

                if (isset($block_config[$func])) {
                    $config = json_encode($block_config[$func], JSON_UNESCAPED_UNICODE);
                } else {
                    $config = '';
                }
                $config = str_replace('{{WebID}}', $WebID, $config);
                $sql    = 'insert into `'
                . $xoopsDB->prefix('tad_web_blocks')
                    . "` (`BlockName`, `BlockCopy`, `BlockTitle`, `BlockContent`, `BlockEnable`, `BlockConfig`, `BlockPosition`, `BlockSort`, `WebID`, `plugin`, `ShareFrom`) values('{$func}', 0, '{$name}', '', '{$BlockEnable}', '{$config}', 'side', '{$sort}', '{$WebID}', '{$block_plugin[$func]}',0)";
                $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);
                $sort++;
            }
        }

        //將首頁轉為區塊
        $sql    = 'SELECT ConfigValue, WebID FROM `' . $xoopsDB->prefix('tad_web_config') . "` WHERE ConfigName='web_plugin_display_arr'";
        $result = $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);
        while (list($ConfigValue, $WebID) = $xoopsDB->fetchRow($result)) {
            $web_plugin_display_arr = explode(',', $ConfigValue);

            $sort = 1;
            foreach ($web_plugin_display_arr as $plugin) {
                if (isset($block_config["list_{$plugin}"])) {
                    $config = json_encode($block_config["list_{$plugin}"], JSON_UNESCAPED_UNICODE);
                } else {
                    $config = '';
                }

                $sql = 'update `' . $xoopsDB->prefix('tad_web_blocks') . "` set `BlockEnable`='1',`BlockPosition`='block4',`BlockConfig`='{$config}',`BlockSort`='{$sort}' where `BlockName`='list_{$plugin}' and `WebID`='{$WebID}'";
                $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);
                $sort++;
            }
        }

        $sql = 'DELETE FROM ' . $xoopsDB->prefix('tad_web_config') . " WHERE `ConfigName` LIKE '%_display'";
        $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);
        $sql = 'DELETE FROM ' . $xoopsDB->prefix('tad_web_config') . " WHERE `ConfigValue` = '活動剪影,網頁列表選單,選單,文章選單'";
        $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);
        $sql = 'DELETE FROM `' . $xoopsDB->prefix('tad_web_config') . "` WHERE ConfigName='display_blocks'";
        $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);
        $sql = 'DELETE FROM `' . $xoopsDB->prefix('tad_web_config') . "` WHERE ConfigName LIKE '%_limit' AND WebID!=0";
        $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);
        $sql = 'DELETE FROM `' . $xoopsDB->prefix('tad_web_config') . "` WHERE ConfigName='hide_function'";
        $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);
        $sql = 'DELETE FROM `' . $xoopsDB->prefix('tad_web_config') . "` WHERE ConfigName='web_setup_show_arr'";
        $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);
        $sql = 'DELETE FROM `' . $xoopsDB->prefix('tad_web_config') . "` WHERE ConfigName='web_plugin_display_arr' AND WebID!=0";
        $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);
        $sql = 'DELETE FROM `' . $xoopsDB->prefix('tad_web_config') . "` WHERE ConfigName='web_plugin_enable_arr' AND WebID=0";
        $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);
    }

    //更新 tad_web 資料表
    public static function chk_tad_web()
    {
        global $xoopsDB;
        $table = $xoopsDB->prefix('tad_web');
        // chk15: used_size
        if (!self::chk_col($table, 'used_size')) {
            $sql = "ALTER TABLE {$table} ADD `used_size` INT(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '已使用空間', ADD `last_accessed` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '最後被拜訪時間'";
            $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);

            // Initial calculation
            self::calc_used_size();
        }
    }

    private static function calc_used_size()
    {
        global $xoopsDB;
        $dir = XOOPS_ROOT_PATH . '/uploads/tad_web/';

        require_once XOOPS_ROOT_PATH . '/modules/tad_web/function.php';
        $sql    = 'SELECT WebID FROM `' . $xoopsDB->prefix('tad_web') . '`';
        $result = $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);
        while (list($WebID) = $xoopsDB->fetchRow($result)) {
            $dir_size = get_dir_size("{$dir}{$WebID}/");

            $sql = 'update `' . $xoopsDB->prefix('tad_web') . "` set `used_size`='{$dir_size}' where `WebID`='{$WebID}'";
            $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);
        }
    }

    //更新 tad_web_power 資料表
    public static function chk_tad_web_power()
    {
        global $xoopsDB;
        $table = $xoopsDB->prefix('tad_web_power');
        if (!self::chk_col($table, 'plugin')) { // chk20
            $sql = "ALTER TABLE {$table} ADD `plugin` varchar(100) default ''";
            $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);

            $sql = "ALTER TABLE `{$table}`
                ADD PRIMARY KEY `power_primary` (`col_name`, `col_sn`, `power_name`, `plugin`),
                DROP INDEX `PRIMARY`";
            $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);
        }
    }

    //更新小幫手相關資料表
    public static function chk_tad_web_assistant()
    {
        global $xoopsDB;
        $table = $xoopsDB->prefix('tad_web_cate_assistant');
        if (!self::chk_col($table, 'plugin')) {
            $sql = "ALTER TABLE {$table} ADD `plugin` varchar(100) NOT NULL default ''";
            $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);

            $sql = "ALTER TABLE `{$table}`
                ADD PRIMARY KEY `Cate_Assistant_ID` (`CateID`, `AssistantType`, `AssistantID`, `plugin`),
                DROP INDEX `PRIMARY`";
            $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);

            // Update plugin
            $sql_sel = "select a.CateID,b.ColName from " . $xoopsDB->prefix('tad_web_cate_assistant') . ' as a
                left join ' . $xoopsDB->prefix('tad_web_cate') . " as b on a.CateID=b.CateID
                where a.`plugin`='' ";
            $result = $xoopsDB->queryF($sql_sel);
            while (list($CateID, $plugin) = $xoopsDB->fetchRow($result)) {
                $sql_up = "update {$table} set `plugin`='$plugin' where `CateID`='{$CateID}' and `plugin`='' ";
                $xoopsDB->queryF($sql_up);
            }
        }

        // chk26
        $table_post = $xoopsDB->prefix('tad_web_assistant_post');
        $sql        = "delete from {$table_post} where AssistantID = 0";
        $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);

        // chk27
        $sql         = "SELECT count(*) FROM {$table_post} where `plugin` = `ColName`";
        $result      = $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);
        list($count) = $xoopsDB->fetchRow($result);
        if ($count > 0) {
            $cols['AccountID']  = 'account';
            $cols['ActionID']   = 'action';
            $cols['fsn']        = 'files';
            $cols['HomeworkID'] = 'homework';
            $cols['NewsID']     = 'news';
            $cols['PageID']     = 'page';
            $cols['ScheduleID'] = 'schedule';
            $cols['VideoID']    = 'video';
            $cols['WorksID']    = 'works';
            foreach ($cols as $ColName => $plugin) {
                $sql = "update {$table_post} set `plugin`='$plugin' where `ColName` = '$ColName'";
                $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);
            }
        }
    }
}
