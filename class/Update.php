<?php

namespace XoopsModules\Tad_web;

use XoopsModules\Tadtools\Utility;

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
                    $sql = 'SELECT count(*) FROM ' . $xoopsDB->prefix($sql_name);
                    $result = $xoopsDB->query($sql);
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
        $xoopsModule = $moduleHandler->getByDirname('tad_web');
        $version = $xoopsModule->version();
        if ('install' === $status) {
            $web_amount = 0;
        } else {
            $sql = 'SELECT * FROM ' . $xoopsDB->prefix('tad_web') . " WHERE `WebEnable`='1' ORDER BY WebSort";
            $result = $xoopsDB->query($sql) or Utility::web_error($sql, __FILE__, __LINE__);

            $web_amount = $xoopsDB->getRowsNum($result);
        }
        $sitename = urlencode($xoopsConfig['sitename']);
        $add_count_url = 'http://120.115.2.99/modules/apply/status.php?url=' . XOOPS_URL . "&web_name={$sitename}&version={$version}&web_amount={$web_amount}&email={$xoopsConfig['adminmail']}&status={$status}";
        if (function_exists('curl_init')) {
            $ch = curl_init();
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
                    $xoopsDB->queryF($sql);
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

    //新增檔案欄位
    public static function chk_fc_tag()
    {
        global $xoopsDB;
        $sql = 'SELECT count(`tag`) FROM ' . $xoopsDB->prefix('tad_web_files_center');
        $result = $xoopsDB->query($sql);
        if (empty($result)) {
            return true;
        }

        return false;
    }

    public static function go_fc_tag()
    {
        global $xoopsDB;
        $sql = 'ALTER TABLE ' . $xoopsDB->prefix('tad_web_files_center') . "
    ADD `upload_date` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '上傳時間',
    ADD `uid` MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT 0 COMMENT '上傳者',
    ADD `tag` VARCHAR(255) NOT NULL DEFAULT '' COMMENT '註記'
    ";
        $xoopsDB->queryF($sql) or redirect_header(XOOPS_URL . '/modules/system/admin.php?fct=modulesadmin', 30, $xoopsDB->error());
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
                    $sql = 'SELECT count(*) FROM ' . $xoopsDB->prefix($sql_name);
                    $result = $xoopsDB->query($sql);
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

        $myts = \MyTextSanitizer::getInstance();

        //取得應有的所有區塊
        $all_blocks = get_all_blocks();
        $block_plugin = get_all_blocks('plugin');
        $block_config = get_all_blocks('config');
        // die(var_export($block_config));

        //找出所有網站
        $allWebID = '';
        $sql = 'SELECT WebID FROM `' . $xoopsDB->prefix('tad_web') . '` GROUP BY `WebID`';
        $result = $xoopsDB->queryF($sql) or Utility::web_error($sql);
        while (list($WebID) = $xoopsDB->fetchRow($result)) {
            $allWebID[] = $WebID;
        }

        //修正自訂區塊名稱（並用序號排序）
        $sql = 'SELECT BlockID,BlockName,BlockTitle,BlockContent,WebID FROM ' . $xoopsDB->prefix('tad_web_blocks') . " WHERE plugin='custom' ORDER BY BlockID";
        $result = $xoopsDB->queryF($sql) or Utility::web_error($sql);
        while (list($BlockID, $BlockName, $BlockTitle, $BlockContent, $WebID) = $xoopsDB->fetchRow($result)) {
            $BlockTitle = $myts->addSlashes($BlockTitle);
            $BlockContent = $myts->addSlashes($BlockContent);

            $new_name = "custom_{$WebID}_{$BlockID}";
            if ($new_name != $BlockName) {
                //修改自己
                $sql = 'update `' . $xoopsDB->prefix('tad_web_blocks') . "` set `BlockName`='{$new_name}' where `BlockID`='{$BlockID}'";
                $xoopsDB->queryF($sql) or Utility::web_error($sql);
            }

            //搜尋該自訂區塊有無分享區塊
            $sql2 = 'select BlockID from ' . $xoopsDB->prefix('tad_web_blocks') . " where (BlockTitle='{$BlockTitle}' or BlockContent='{$BlockContent}') and WebID='{$WebID}' and plugin='share'";
            $result2 = $xoopsDB->queryF($sql2) or Utility::web_error($sql2);

            list($share_BlockID) = $xoopsDB->fetchRow($result2);

            //若有分享區塊
            if ($share_BlockID) {
                //修改分享區塊
                $sql = 'update `' . $xoopsDB->prefix('tad_web_blocks') . "` set `BlockName`='share_{$WebID}_{$share_BlockID}', `ShareFrom`='{$BlockID}' where BlockID='{$share_BlockID}'";
                $xoopsDB->queryF($sql) or Utility::web_error($sql);

                //修改其他網站已經使用該分享區塊的
                $sql = 'update `' . $xoopsDB->prefix('tad_web_blocks') . "` set `ShareFrom`='{$share_BlockID}' where (BlockTitle='{$BlockTitle}' or BlockContent='{$BlockContent}') and plugin='custom' and WebID!='{$WebID}'";
                $xoopsDB->queryF($sql) or Utility::web_error($sql);
            }
        }

        foreach ($allWebID as $WebID) {
            //找出目前已安裝的區塊
            $sql = 'select BlockID,BlockName,BlockConfig from ' . $xoopsDB->prefix('tad_web_blocks') . " where WebID='{$WebID}' and  plugin!='custom' and plugin!='share'";
            $result = $xoopsDB->queryF($sql) or Utility::web_error($sql);
            while (list($BlockID, $BlockName, $BlockConfig) = $xoopsDB->fetchRow($result)) {
                $db_blocks[$WebID][$BlockName] = $BlockName;
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
                        if (PHP_VERSION_ID >= 50400) {
                            $config = json_encode($block_config[$BlockName], JSON_UNESCAPED_UNICODE);
                        } else {
                            array_walk_recursive($block_config[$BlockName], function (&$value, $key) {
                                if (is_string($value)) {
                                    $value = urlencode($value);
                                }
                            });
                            $config = urldecode(json_encode($block_config[$BlockName]));
                        }
                    } else {
                        $config = '';
                    }
                    $config = str_replace('{{WebID}}', $WebID, $config);
                    $sql = 'insert into `'
                    . $xoopsDB->prefix('tad_web_blocks')
                        . "` (`BlockName`, `BlockCopy`, `BlockTitle`, `BlockContent`, `BlockEnable`, `BlockConfig`, `BlockPosition`, `BlockSort`, `WebID`, `plugin`) values('{$BlockName}', '0', '{$BlockTitle}', '', '1', '{$config}', 'uninstall', '', '{$WebID}', '{$block_plugin[$BlockName]}')";
                    $xoopsDB->queryF($sql) or Utility::web_error($sql);
                } else {
                    //檢查區塊設定值是否需要更新

                    //找出某區塊安裝在該網站的 $BlockID 以及現有設定
                    foreach ($db_blocks_config[$WebID][$BlockName] as $BlockID => $BlockConfig) {
                        $new_config = $db_config = '';

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

                        if (PHP_VERSION_ID >= 50400) {
                            $new_block_config = json_encode($new_config, JSON_UNESCAPED_UNICODE);
                        } else {
                            array_walk_recursive($new_config, function (&$value, $key) {
                                if (is_string($value)) {
                                    $value = urlencode($value);
                                }
                            });
                            $new_block_config = urldecode(json_encode($new_config));
                        }

                        // echo "新設定：" . $new_block_config . "<br>";

                        $new_block_config = str_replace('{{WebID}}', $WebID, $new_block_config);
                        $sql = 'update `' . $xoopsDB->prefix('tad_web_blocks') . "` set `BlockConfig`='{$new_block_config}' where `BlockID`='{$BlockID}'";
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

        $myts = \MyTextSanitizer::getInstance();

        //修正自訂區塊名稱（並用序號排序）
        if (!_IS_EZCLASS) {
            $sql = 'SELECT BlockID,BlockName,BlockTitle,BlockContent,WebID FROM ' . $xoopsDB->prefix('tad_web_blocks') . " WHERE plugin='custom' ORDER BY BlockID";
            $result = $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);
            while (list($BlockID, $BlockName, $BlockTitle, $BlockContent, $WebID) = $xoopsDB->fetchRow($result)) {
                $BlockTitle = $myts->addSlashes($BlockTitle);
                $BlockContent = $myts->addSlashes($BlockContent);

                $new_name = "custom_{$WebID}_{$BlockID}";
                if ($new_name != $BlockName) {
                    //修改自己
                    $sql = 'update `' . $xoopsDB->prefix('tad_web_blocks') . "` set `BlockName`='{$new_name}' where `BlockID`='{$BlockID}'";
                    $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);
                }

                //搜尋該自訂區塊有無分享區塊
                $sql2 = 'select BlockID from ' . $xoopsDB->prefix('tad_web_blocks') . " where (BlockTitle='{$BlockTitle}' or BlockContent='{$BlockContent}') and WebID='{$WebID}' and plugin='share'";
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
        $xoopsDB->queryF($sql);

        require_once XOOPS_ROOT_PATH . '/modules/tad_web/function.php';

        $Webs = getAllWebInfo('WebTitle');
        foreach ($Webs as $WebID => $WebTitle) {
            mk_menu_var_file($WebID);
        }
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
            $show_func = $block['show_func'];
            $tpl_file_arr[$show_func] = $block['template'];
            $tpl_desc_arr[$show_func] = $block['description'];
        }

        //找出目前所有的樣板檔
        $sql    = 'SELECT bid,name,visible,show_func,template FROM ' . $xoopsDB->prefix('newblocks') . " WHERE `dirname` = 'tad_web' ORDER BY `func_num`";
        $result = $xoopsDB->query($sql);
        while (list($bid, $name, $visible, $show_func, $template) = $xoopsDB->fetchRow($result)) {
            //假如現有的區塊和樣板對不上就刪掉
            if ($template != $tpl_file_arr[$show_func]) {
                $sql = 'delete from ' . $xoopsDB->prefix('newblocks') . " where bid='{$bid}'";
                $xoopsDB->queryF($sql);

                //連同樣板以及樣板實體檔案也要刪掉
                $sql = 'delete from ' . $xoopsDB->prefix('tplfile') . ' as a left join ' . $xoopsDB->prefix('tplsource') . "  as b on a.tpl_id=b.tpl_id where a.tpl_refid='$bid' and a.tpl_module='tad_web' and a.tpl_type='block'";
                $xoopsDB->queryF($sql);
            } else {
                $sql = 'update ' . $xoopsDB->prefix('tplfile') . " set tpl_file='{$template}' , tpl_desc='{$tpl_desc_arr[$show_func]}' where tpl_refid='{$bid}'";
                $xoopsDB->queryF($sql);
            }
        }
    }

    //修改討論區計數欄位名稱
    public static function chk_chk1()
    {
        global $xoopsDB;
        $sql = 'SELECT count(`DiscussCounter`) FROM ' . $xoopsDB->prefix('tad_web_discuss');
        $result = $xoopsDB->query($sql);
        if (empty($result)) {
            return false;
        }

        return true;
    }

    public static function go_update1()
    {
        global $xoopsDB;
        $sql = 'ALTER TABLE ' . $xoopsDB->prefix('tad_web_discuss') . ' CHANGE `DisscussCounter` `DiscussCounter` SMALLINT( 6 ) UNSIGNED NOT NULL';
        $xoopsDB->queryF($sql) or redirect_header(XOOPS_URL . '/modules/system/admin.php?fct=modulesadmin', 30, $xoopsDB->error());

        return true;
    }

    //修改討論區發布者uid編號
    public static function chk_chk2()
    {
        global $xoopsDB;
        $sql = 'SELECT count(`uid`) FROM ' . $xoopsDB->prefix('tad_web_discuss');
        $result = $xoopsDB->query($sql);
        if (empty($result)) {
            return false;
        }

        return true;
    }

    public static function go_update2()
    {
        global $xoopsDB;
        $sql = 'ALTER TABLE ' . $xoopsDB->prefix('tad_web_discuss') . ' ADD `uid` SMALLINT( 6 ) UNSIGNED NOT NULL AFTER `WebID`';
        $xoopsDB->queryF($sql) or redirect_header(XOOPS_URL . '/modules/system/admin.php?fct=modulesadmin', 30, $xoopsDB->error());

        return true;
    }

    //修改討論區發布者編號
    public static function chk_chk3()
    {
        global $xoopsDB;
        $sql = 'SELECT count(`MemID`) FROM ' . $xoopsDB->prefix('tad_web_discuss');
        $result = $xoopsDB->query($sql);
        if (empty($result)) {
            return false;
        }

        return true;
    }

    public static function go_update3()
    {
        global $xoopsDB;
        $sql = 'ALTER TABLE ' . $xoopsDB->prefix('tad_web_discuss') . ' ADD `MemID` SMALLINT( 6 ) UNSIGNED NOT NULL AFTER `uid`';
        $xoopsDB->queryF($sql) or redirect_header(XOOPS_URL . '/modules/system/admin.php?fct=modulesadmin', 30, $xoopsDB->error());

        return true;
    }

    //新增討論區發布者姓名欄位
    public static function chk_chk4()
    {
        global $xoopsDB;
        $sql = 'SELECT count(`MemName`) FROM ' . $xoopsDB->prefix('tad_web_discuss');
        $result = $xoopsDB->query($sql);
        if (empty($result)) {
            return false;
        }

        return true;
    }

    public static function go_update4()
    {
        global $xoopsDB;
        $sql = 'ALTER TABLE ' . $xoopsDB->prefix('tad_web_discuss') . " ADD `MemName` VARCHAR(255) NOT NULL DEFAULT '' AFTER `MemID`";
        $xoopsDB->queryF($sql) or redirect_header(XOOPS_URL . '/modules/system/admin.php?fct=modulesadmin', 30, $xoopsDB->error());

        return true;
    }

    //新增original_filename欄位
    public static function chk_chk5()
    {
        global $xoopsDB;
        $sql = 'SELECT count(`original_filename`) FROM ' . $xoopsDB->prefix('tad_web_files_center');
        $result = $xoopsDB->query($sql);
        if (empty($result)) {
            return false;
        }

        return true;
    }

    public static function go_update5()
    {
        global $xoopsDB;
        $sql = 'ALTER TABLE ' . $xoopsDB->prefix('tad_web_files_center') . "
      ADD `original_filename` VARCHAR(255) NOT NULL DEFAULT '',
      ADD `hash_filename` VARCHAR(255) NOT NULL DEFAULT '',
      ADD `sub_dir` VARCHAR(255) NOT NULL DEFAULT ''";
        $xoopsDB->queryF($sql) or redirect_header(XOOPS_URL . '/modules/system/admin.php?fct=modulesadmin', 30, $xoopsDB->error());

        $sql = 'update ' . $xoopsDB->prefix('tad_web_files_center') . ' set
    `original_filename`=`description`';
        $xoopsDB->queryF($sql) or redirect_header(XOOPS_URL . '/modules/system/admin.php?fct=modulesadmin', 30, $xoopsDB->error());
    }

    public static function go_update6()
    {
        global $xoopsDB;

        $updir = XOOPS_ROOT_PATH . '/uploads/tad_web';
        $os = (PATH_SEPARATOR === ':') ? 'linux' : 'win';

        //修正子目錄，並找出實體檔案沒有真的在子目錄下的
        $sql = 'SELECT `files_sn`,`col_name`,`col_sn`,`kind`,`file_name`,`sub_dir` FROM ' . $xoopsDB->prefix('tad_web_files_center') . " WHERE `sub_dir` LIKE '//%'";
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
                $to = "{$updir}{$sub_dir}/{$typedir}/{$file_name}";

                if ('win' === $os and _CHARSET === 'UTF-8') {
                    $from = iconv(_CHARSET, 'Big5', $from);
                    $to = iconv(_CHARSET, 'Big5', $to);
                } elseif ('linux' === $os and _CHARSET === 'Big5') {
                    $from = iconv(_CHARSET, 'UTF-8', $from);
                    $to = iconv(_CHARSET, 'UTF-8', $to);
                }

                rename($from, $to);
                if ('image' === $typedir) {
                    Utility::mk_dir("{$updir}{$sub_dir}");
                    Utility::mk_dir("{$updir}{$sub_dir}/{$typedir}");
                    Utility::mk_dir("{$updir}{$sub_dir}/{$typedir}/.thumbs");
                    $from = "{$updir}/{$typedir}/.thumbs/{$file_name}";
                    $to = "{$updir}{$sub_dir}/{$typedir}/.thumbs/{$file_name}";

                    if ('win' === $os and _CHARSET === 'UTF-8') {
                        $from = iconv(_CHARSET, 'Big5', $from);
                        $to = iconv(_CHARSET, 'Big5', $to);
                    } elseif ('linux' === $os and _CHARSET === 'Big5') {
                        $from = iconv(_CHARSET, 'UTF-8', $from);
                        $to = iconv(_CHARSET, 'UTF-8', $to);
                    }

                    rename($from, $to);
                }
            }
        }

        //找出沒有放到子目錄的
        $sql = 'SELECT `files_sn`,`col_name`,`col_sn`,`kind`,`file_name`,`sub_dir` FROM ' . $xoopsDB->prefix('tad_web_files_center') . '';
        $result = $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);
        while (list($files_sn, $col_name, $col_sn, $kind, $file_name, $sub_dir) = $xoopsDB->fetchRow($result)) {
            $typedir = 'img' === $kind ? 'image' : 'file';
            $WebID = (int) mb_substr($sub_dir, 1);
            if (empty($WebID)) {
                if ('WebOwner' === $col_name or 'WebLogo' === $col_name) {
                    $WebID = $col_sn;
                } elseif ('MemID' === $col_name) {
                    $sql = 'select `WebID` from ' . $xoopsDB->prefix('tad_web_link_mems') . " where `MemID` = '{$col_sn}'";
                    $result2 = $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);
                    list($WebID) = $xoopsDB->fetchRow($result2);
                } elseif ('ActionID' === $col_name) {
                    $sql = 'select `WebID` from ' . $xoopsDB->prefix('tad_web_action') . " where `ActionID` = '{$col_sn}'";
                    $result2 = $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);
                    list($WebID) = $xoopsDB->fetchRow($result2);
                } elseif ('fsn' === $col_name) {
                    $sql = 'select `WebID` from ' . $xoopsDB->prefix('tad_web_files') . " where `fsn` = '{$col_sn}'";
                    $result2 = $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);
                    list($WebID) = $xoopsDB->fetchRow($result2);
                } elseif ('NewsID' === $col_name) {
                    $sql = 'select `WebID` from ' . $xoopsDB->prefix('tad_web_news') . " where `NewsID` = '{$col_sn}'";
                    $result2 = $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);
                    list($WebID) = $xoopsDB->fetchRow($result2);
                }
            }

            $sql = 'update ' . $xoopsDB->prefix('tad_web_files_center') . " set `sub_dir`='/{$WebID}'  where `files_sn`='{$files_sn}'";
            $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);

            Utility::mk_dir("{$updir}/{$WebID}");
            Utility::mk_dir("{$updir}/{$WebID}/{$typedir}");
            if ('image' === $typedir) {
                Utility::mk_dir("{$updir}/{$WebID}/{$typedir}");
                Utility::mk_dir("{$updir}/{$WebID}/{$typedir}/.thumbs");
            }

            $from = "{$updir}/{$typedir}/{$file_name}";
            $to = "{$updir}/{$WebID}/{$typedir}/{$file_name}";

            if ('win' === $os && _CHARSET === 'UTF-8') {
                $from = iconv(_CHARSET, 'Big5', $from);
                $to = iconv(_CHARSET, 'Big5', $to);
            } elseif ('linux' === $os && _CHARSET === 'Big5') {
                $from = iconv(_CHARSET, 'UTF-8', $from);
                $to = iconv(_CHARSET, 'UTF-8', $to);
            }
            if (file_exists($from)) {
                rename($from, $to);
                if ('image' === $typedir) {
                    Utility::mk_dir("{$updir}/{$WebID}");
                    Utility::mk_dir("{$updir}/{$WebID}/{$typedir}");
                    Utility::mk_dir("{$updir}/{$WebID}/{$typedir}/.thumbs");

                    $from = "{$updir}/{$typedir}/.thumbs/{$file_name}";
                    $to = "{$updir}/{$WebID}/{$typedir}/.thumbs/{$file_name}";

                    if ('win' === $os && _CHARSET === 'UTF-8') {
                        $from = iconv(_CHARSET, 'Big5', $from);
                        $to = iconv(_CHARSET, 'Big5', $to);
                    } elseif ('linux' === $os && _CHARSET === 'Big5') {
                        $from = iconv(_CHARSET, 'UTF-8', $from);
                        $to = iconv(_CHARSET, 'UTF-8', $to);
                    }
                    rename($from, $to);
                }
            }
        }
    }

    //修改分類名稱欄位名稱
    public static function chk_chk7()
    {
        global $xoopsDB;
        $sql = 'SHOW Fields FROM ' . $xoopsDB->prefix('tad_web_cate') . " where `Field`='CateName' and `Type` = 'varchar(255)'";
        $result = $xoopsDB->query($sql);
        if (empty($result)) {
            return true;
        }

        return false;
    }

    public static function go_update7()
    {
        global $xoopsDB;
        $sql = 'ALTER TABLE ' . $xoopsDB->prefix('tad_web_cate') . " CHANGE `CateName` `CateName` VARCHAR(255) NOT NULL DEFAULT ''";
        $xoopsDB->queryF($sql) or redirect_header(XOOPS_URL . '/modules/system/admin.php?fct=modulesadmin', 30, $xoopsDB->error());

        return true;
    }

    //新增作品分享表格
    public static function chk_chk8()
    {
        global $xoopsDB;
        $sql = 'SELECT count(*) FROM ' . $xoopsDB->prefix('tad_web_works');
        $result = $xoopsDB->query($sql);
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
        $xoopsDB->queryF($sql);
    }

    //新增聯絡簿
    public static function chk_chk9()
    {
        global $xoopsDB;
        $sql = 'SELECT count(*) FROM ' . $xoopsDB->prefix('tad_web_homework');
        $result = $xoopsDB->query($sql);
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
        $xoopsDB->queryF($sql);

        $sql = 'SELECT * FROM `' . $xoopsDB->prefix('tad_web_news') . "` WHERE NewsKind='homework'";
        $result = $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);
        while (false !== ($all = $xoopsDB->fetchArray($result))) {
            foreach ($all as $k => $v) {
                $$k = $v;
            }

            $sql = 'insert into `' . $xoopsDB->prefix('tad_web_homework') . "` (`WebID`, `HomeworkTitle`, `HomeworkContent`, `HomeworkDate`, `toCal`, `HomeworkCounter`, `uid`) values('{$WebID}', '{$NewsTitle}', '{$NewsContent}', '{$NewsDate}', '{$toCal}', '{$NewsCounter}', '{$uid}')";
            $xoopsDB->queryF($sql);

            $sql = 'delete from `' . $xoopsDB->prefix('tad_web_news') . "` where `NewsID`='{$NewsID}'";
            $xoopsDB->queryF($sql);
        }
    }

    //新增外掛表格
    public static function chk_chk10()
    {
        global $xoopsDB;
        $sql = 'SELECT count(*) FROM ' . $xoopsDB->prefix('tad_web_plugins');
        $result = $xoopsDB->query($sql);
        if (empty($result)) {
            return true;
        }

        return false;
    }

    public static function go_update10()
    {
        global $xoopsDB;
        $sql = 'CREATE TABLE `' . $xoopsDB->prefix('tad_web_plugins') . "` (
      `PluginDirname` VARCHAR(100) NOT NULL COMMENT '目錄名稱',
      `PluginTitle` VARCHAR(255) NOT NULL COMMENT '外掛名稱',
      `PluginSort` SMALLINT(6) UNSIGNED NOT NULL DEFAULT 0 COMMENT '排序',
      `PluginEnable` ENUM('1','0') NOT NULL DEFAULT '1' COMMENT '狀態',
      `WebID` SMALLINT(6) UNSIGNED NOT NULL DEFAULT 0 COMMENT '所屬班級',
    PRIMARY KEY (`PluginDirname`,`WebID`)
    ) ENGINE=MyISAM;";
        $xoopsDB->queryF($sql);
    }

    //新增角色表格
    public static function chk_chk11()
    {
        global $xoopsDB;
        $sql = 'SELECT count(*) FROM ' . $xoopsDB->prefix('tad_web_roles');
        $result = $xoopsDB->query($sql);
        if (empty($result)) {
            return true;
        }

        return false;
    }

    public static function go_update11()
    {
        global $xoopsDB;
        $sql = 'CREATE TABLE `' . $xoopsDB->prefix('tad_web_roles') . "` (
      `uid` MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT 0 COMMENT '使用者',
      `role` VARCHAR(255) NOT NULL COMMENT '角色',
      `term` DATE  NOT NULL DEFAULT '0000-00-00' COMMENT '期限',
      `enable` ENUM('1','0') NOT NULL DEFAULT '1' COMMENT '狀態',
      `WebID` SMALLINT(6) UNSIGNED NOT NULL DEFAULT 0 COMMENT '所屬班級',
    PRIMARY KEY (`WebID`,`uid`,`role`)
    ) ENGINE=MyISAM;";
        $xoopsDB->queryF($sql);
    }

    //新增區塊設定表格
    public static function chk_chk12()
    {
        global $xoopsDB;
        $sql = 'SELECT count(*) FROM ' . $xoopsDB->prefix('tad_web_blocks');
        $result = $xoopsDB->query($sql);
        if (empty($result)) {
            return true;
        }

        return false;
    }

    public static function go_update12()
    {
        global $xoopsDB;
        require_once XOOPS_ROOT_PATH . '/modules/tad_web/function.php';
        $sql = 'CREATE TABLE `' . $xoopsDB->prefix('tad_web_blocks') . "` (
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
        $xoopsDB->queryF($sql);

        //取得系統所有區塊
        $block_option = get_all_blocks();
        $block_plugin = get_all_blocks('plugin');
        $block_config = get_all_blocks('config');

        //存入既有設定
        $sql = 'SELECT ConfigValue, WebID FROM `' . $xoopsDB->prefix('tad_web_config') . "` WHERE ConfigName='display_blocks'";
        $result = $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);
        while (list($ConfigValue, $WebID) = $xoopsDB->fetchRow($result)) {
            $Config = explode(',', $ConfigValue);
            $sort = 1;
            foreach ($block_option as $func => $name) {
                if ($func === "list_{$block_plugin[$func]}") {
                    $BlockPosition = 'block4';
                } else {
                    $BlockPosition = 'side';
                }
                $BlockEnable = in_array($func, $Config) ? 1 : 0;

                if ($block_config[$func]) {
                    if (PHP_VERSION_ID >= 50400) {
                        $config = json_encode($block_config[$func], JSON_UNESCAPED_UNICODE);
                    } else {
                        array_walk_recursive($block_config[$func], function (&$value, $key) {
                            if (is_string($value)) {
                                $value = urlencode($value);
                            }
                        });
                        $config = urldecode(json_encode($block_config[$func]));
                    }
                } else {
                    $config = '';
                }
                $config = str_replace('{{WebID}}', $WebID, $config);
                $sql = 'insert into `'
                . $xoopsDB->prefix('tad_web_blocks')
                    . "` (`BlockName`, `BlockCopy`, `BlockTitle`, `BlockContent`, `BlockEnable`, `BlockConfig`, `BlockPosition`, `BlockSort`, `WebID`, `plugin`) values('{$func}', '0', '{$name}', '', '{$BlockEnable}', '{$config}', 'side', '{$sort}', '{$WebID}', '{$block_plugin[$func]}')";
                $xoopsDB->queryF($sql) or Utility::web_error($sql);
                $sort++;
            }
        }

        //將首頁轉為區塊
        $sql = 'SELECT ConfigValue, WebID FROM `' . $xoopsDB->prefix('tad_web_config') . "` WHERE ConfigName='web_plugin_display_arr'";
        $result = $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);
        while (list($ConfigValue, $WebID) = $xoopsDB->fetchRow($result)) {
            $web_plugin_display_arr = explode(',', $ConfigValue);

            $sort = 1;
            foreach ($web_plugin_display_arr as $plugin) {
                if ($block_config["list_{$plugin}"]) {
                    if (PHP_VERSION_ID >= 50400) {
                        $config = json_encode($block_config["list_{$plugin}"], JSON_UNESCAPED_UNICODE);
                    } else {
                        array_walk_recursive($block_config["list_{$plugin}"], function (&$value, $key) {
                            if (is_string($value)) {
                                $value = urlencode($value);
                            }
                        });
                        $config = urldecode(json_encode($block_config["list_{$plugin}"]));
                    }
                } else {
                    $config = '';
                }

                $sql = 'update `' . $xoopsDB->prefix('tad_web_blocks') . "` set `BlockEnable`='1',`BlockPosition`='block4',`BlockConfig`='{$config}',`BlockSort`='{$sort}' where `BlockName`='list_{$plugin}' and `WebID`='{$WebID}'";
                $xoopsDB->queryF($sql) or Utility::web_error($sql);
                $sort++;
            }
        }

        $sql = 'DELETE FROM ' . $xoopsDB->prefix('tad_web_config') . " WHERE `ConfigName` LIKE '%_display'";
        $xoopsDB->queryF($sql);
        $sql = 'DELETE FROM ' . $xoopsDB->prefix('tad_web_config') . " WHERE `ConfigValue` = '活動剪影,網頁列表選單,選單,文章選單'";
        $xoopsDB->queryF($sql);
        $sql = 'DELETE FROM `' . $xoopsDB->prefix('tad_web_config') . "` WHERE ConfigName='display_blocks'";
        $xoopsDB->queryF($sql);
        $sql = 'DELETE FROM `' . $xoopsDB->prefix('tad_web_config') . "` WHERE ConfigName LIKE '%_limit' AND WebID!=0";
        $xoopsDB->queryF($sql);
        $sql = 'DELETE FROM `' . $xoopsDB->prefix('tad_web_config') . "` WHERE ConfigName='hide_function'";
        $xoopsDB->queryF($sql);
        $sql = 'DELETE FROM `' . $xoopsDB->prefix('tad_web_config') . "` WHERE ConfigName='web_setup_show_arr'";
        $xoopsDB->queryF($sql);
        $sql = 'DELETE FROM `' . $xoopsDB->prefix('tad_web_config') . "` WHERE ConfigName='web_plugin_display_arr' AND WebID!=0";
        $xoopsDB->queryF($sql);
        $sql = 'DELETE FROM `' . $xoopsDB->prefix('tad_web_config') . "` WHERE ConfigName='web_plugin_enable_arr' AND WebID=0";
        $xoopsDB->queryF($sql);
    }

    //新增分享區塊設訂
    // function chk_chk13()
    // {
    //     global $xoopsDB;
    //     $sql    = "select count(`BlockShare`) from " . $xoopsDB->prefix("tad_web_blocks");
    //     $result = $xoopsDB->query($sql);
    //     if (empty($result)) {
    //         return true;
    //     }

    //     return false;
    // }

    // function go_update13()
    // {
    //     global $xoopsDB;
    //     $sql = "ALTER TABLE " . $xoopsDB->prefix("tad_web_blocks") . " ADD `BlockShare` varchar(255) NOT NULL COMMENT '分享區塊' AFTER `BlockSort`";
    //     $xoopsDB->queryF($sql) or redirect_header(XOOPS_URL . "/modules/system/admin.php?fct=modulesadmin", 30, $xoopsDB->error());
    //     return true;
    // }

    //新增外掛偏好設定表格
    public static function chk_chk14()
    {
        global $xoopsDB;
        $sql = 'SELECT count(*) FROM ' . $xoopsDB->prefix('tad_web_plugins_setup');
        $result = $xoopsDB->query($sql);
        if (empty($result)) {
            return true;
        }

        return false;
    }

    public static function go_update14()
    {
        global $xoopsDB;
        $sql = 'CREATE TABLE `' . $xoopsDB->prefix('tad_web_plugins_setup') . "` (
      `WebID` SMALLINT(5) UNSIGNED NOT NULL DEFAULT 0 COMMENT '所屬網站',
      `plugin` VARCHAR(100) NOT NULL DEFAULT '' COMMENT '所屬外掛',
      `name` VARCHAR(100) NOT NULL DEFAULT '' COMMENT '設定名稱',
      `type` VARCHAR(255) NOT NULL DEFAULT '' COMMENT '欄位類型',
      `value` TEXT NOT NULL COMMENT '設定值',
      PRIMARY KEY  (`WebID`,`plugin`,`name`)
    ) ENGINE=MyISAM";
        $xoopsDB->queryF($sql);
    }

    //新增已使用空間
    public static function chk_chk15()
    {
        global $xoopsDB;
        $sql = 'SELECT count(`used_size`) FROM ' . $xoopsDB->prefix('tad_web');
        $result = $xoopsDB->query($sql);
        if (empty($result)) {
            return true;
        }

        return false;
    }

    public static function go_update15()
    {
        global $xoopsDB;
        $sql = 'ALTER TABLE ' . $xoopsDB->prefix('tad_web') . " ADD `used_size` INT(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '已使用空間', ADD `last_accessed` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '最後被拜訪時間'";
        $xoopsDB->queryF($sql) or redirect_header(XOOPS_URL . '/modules/system/admin.php?fct=modulesadmin', 30, $xoopsDB->error());

        $dir = XOOPS_ROOT_PATH . '/uploads/tad_web/';

        require_once XOOPS_ROOT_PATH . '/modules/tad_web/function.php';
        $sql = 'SELECT WebID FROM `' . $xoopsDB->prefix('tad_web') . '`';
        $result = $xoopsDB->queryF($sql) or Utility::web_error($sql);
        while (list($WebID) = $xoopsDB->fetchRow($result)) {
            $dir_size = get_dir_size("{$dir}{$WebID}/");

            $sql = 'update `' . $xoopsDB->prefix('tad_web') . "` set `used_size`='{$dir_size}' where `WebID`='{$WebID}'";
            $xoopsDB->queryF($sql) or Utility::web_error($sql);
        }

        return true;
    }

    //新增權限表格
    public static function chk_chk16()
    {
        global $xoopsDB;
        $sql = 'SELECT count(*) FROM ' . $xoopsDB->prefix('tad_web_power');
        $result = $xoopsDB->query($sql);
        if (empty($result)) {
            return true;
        }

        return false;
    }

    public static function go_update16()
    {
        global $xoopsDB;
        $sql = 'CREATE TABLE `' . $xoopsDB->prefix('tad_web_power') . "` (
      `WebID` SMALLINT(5) UNSIGNED NOT NULL DEFAULT 0 COMMENT '所屬網站',
      `col_name` VARCHAR(100) NOT NULL DEFAULT '' COMMENT '權限名稱',
      `col_sn` MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT 0 COMMENT '對應編號',
      `power_name` VARCHAR(100) NOT NULL DEFAULT '' COMMENT '權限名稱',
      `power_val` VARCHAR(255) NOT NULL COMMENT '權限設定',
      PRIMARY KEY  (`WebID`,`col_name`,`power_name`)
    ) ENGINE=MyISAM";
        $xoopsDB->queryF($sql);

        //修改欄位大小
        $sql = 'ALTER TABLE ' . $xoopsDB->prefix('tad_web_files_center') . " CHANGE `files_sn` `files_sn` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '檔案流水號'";
        $xoopsDB->queryF($sql) or redirect_header(XOOPS_URL . '/modules/system/admin.php?fct=modulesadmin', 30, $xoopsDB->error());

        $sql = 'ALTER TABLE ' . $xoopsDB->prefix('tad_web_blocks') . " CHANGE `BlockID` `BlockID` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '區塊流水號'";
        $xoopsDB->queryF($sql) or redirect_header(XOOPS_URL . '/modules/system/admin.php?fct=modulesadmin', 30, $xoopsDB->error());

        return true;
    }

    //新增權限表格
    public static function chk_chk17()
    {
        global $xoopsDB;
        $sql = 'SELECT count(*) FROM ' . $xoopsDB->prefix('tad_web_tags');
        $result = $xoopsDB->query($sql);
        if (empty($result)) {
            return true;
        }

        return false;
    }

    public static function go_update17()
    {
        global $xoopsDB;
        $sql = 'CREATE TABLE `' . $xoopsDB->prefix('tad_web_tags') . "` (
      `WebID` SMALLINT(5) UNSIGNED NOT NULL  COMMENT '所屬網站',
      `col_name` VARCHAR(100) NOT NULL DEFAULT '' COMMENT '權限名稱',
      `col_sn` MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT 0 COMMENT '對應編號',
      `tag_name` VARCHAR(100) NOT NULL DEFAULT '' COMMENT '權限名稱',
      PRIMARY KEY  (`col_name`,`col_sn`,`tag_name`)
    ) ENGINE=MyISAM";
        $xoopsDB->queryF($sql);

        return true;
    }

    //修正區塊索引
    public static function chk_chk18()
    {
        global $xoopsDB;
        $sql = 'show keys from ' . $xoopsDB->prefix('tad_web_blocks') . " where Key_name='BlockName_BlockCopy_WebID_plugin'";
        $result = $xoopsDB->query($sql);
        if (empty($result)) {
            return true;
        }

        return false;
    }

    public static function go_update18()
    {
        global $xoopsDB;
        $sql = 'ALTER TABLE `' . $xoopsDB->prefix('tad_web_blocks') . "` CHANGE `plugin` `plugin` VARCHAR(100) COLLATE 'utf8_general_ci' NOT NULL COMMENT '所屬外掛' AFTER `WebID`;";
        $xoopsDB->queryF($sql) or Utility::web_error($sql);

        $sql = 'ALTER TABLE `' . $xoopsDB->prefix('tad_web_blocks') . '` ADD UNIQUE `BlockName_BlockCopy_WebID_plugin` (`BlockName`, `BlockCopy`, `WebID`, `plugin`);';
        $xoopsDB->queryF($sql) or Utility::web_error($sql);

        return true;
    }

    //刪除分享區塊設訂
    public static function chk_chk19()
    {
        global $xoopsDB;
        $sql = 'SELECT count(`BlockShare`) FROM ' . $xoopsDB->prefix('tad_web_blocks');
        $result = $xoopsDB->query($sql);
        if (!empty($result)) {
            return true;
        }

        return false;
    }

    public static function go_update19()
    {
        global $xoopsDB;
        $sql = 'ALTER TABLE ' . $xoopsDB->prefix('tad_web_blocks') . ' DROP `BlockShare`';
        $xoopsDB->queryF($sql) or redirect_header(XOOPS_URL . '/modules/system/admin.php?fct=modulesadmin', 30, $xoopsDB->error());

        return true;
    }

    //刪除分享區塊設訂
    public static function chk_chk19_1()
    {
        global $xoopsDB;
        $sql = 'SELECT count(`ShareFrom`) FROM ' . $xoopsDB->prefix('tad_web_blocks');
        $result = $xoopsDB->query($sql);
        if (empty($result)) {
            return true;
        }

        return false;
    }

    public static function go_update19_1()
    {
        global $xoopsDB;
        $sql = 'ALTER TABLE ' . $xoopsDB->prefix('tad_web_blocks') . " ADD `ShareFrom` INT(10) UNSIGNED NOT NULL COMMENT '分享自'";
        $xoopsDB->queryF($sql) or redirect_header(XOOPS_URL . '/modules/system/admin.php?fct=modulesadmin', 30, $xoopsDB->error());

        return true;
    }

    //修正區塊索引
    public static function chk_chk20()
    {
        global $xoopsDB;
        $sql = 'show keys from ' . $xoopsDB->prefix('tad_web_power') . " where Column_name='WebID'";
        $result = $xoopsDB->query($sql);
        if (!empty($result)) {
            return true;
        }

        return false;
    }

    public static function go_update20()
    {
        global $xoopsDB;

        $sql = 'ALTER TABLE `' . $xoopsDB->prefix('tad_web_power') . '` ADD PRIMARY KEY `power_primary` (`col_name`, `col_sn`, `power_name`), DROP INDEX `PRIMARY`;';
        $xoopsDB->queryF($sql) or Utility::web_error($sql);

        return true;
    }

    //新增通知表格
    public static function chk_chk21()
    {
        global $xoopsDB;
        $sql = 'SELECT count(*) FROM ' . $xoopsDB->prefix('tad_web_notice');
        $result = $xoopsDB->query($sql);
        if (empty($result)) {
            return true;
        }

        return false;
    }

    public static function go_update21()
    {
        global $xoopsDB;
        $sql = 'CREATE TABLE `' . $xoopsDB->prefix('tad_web_notice') . "` (
      `NoticeID` SMALLINT(5) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '通知編號',
      `NoticeTitle` VARCHAR(255) NOT NULL DEFAULT '' COMMENT '通知標題',
      `NoticeContent` TEXT NOT NULL  COMMENT '通知內容',
      `NoticeWeb` TEXT NOT NULL COMMENT '通知網站',
      `NoticeWho` VARCHAR(255) NOT NULL DEFAULT '' COMMENT '通知對象',
      `NoticeDate` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '通知日期',
      PRIMARY KEY  (`NoticeID`)
    ) ENGINE=MyISAM";
        $xoopsDB->queryF($sql);

        return true;
    }

    //新增通知表格
    public static function chk_chk22()
    {
        global $xoopsDB;
        $sql = 'SELECT count(*) FROM ' . $xoopsDB->prefix('tad_web_mail_log');
        $result = $xoopsDB->query($sql);
        if (empty($result)) {
            return true;
        }

        return false;
    }

    public static function go_update22()
    {
        global $xoopsDB;
        $sql = 'CREATE TABLE `' . $xoopsDB->prefix('tad_web_mail_log') . "` (
      `ColName` VARCHAR(100) NOT NULL DEFAULT '' COMMENT '欄位名稱',
      `ColSN` SMALLINT(5) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '欄位編號',
      `WebID` SMALLINT(5) UNSIGNED NOT NULL  COMMENT '所屬網站',
      `Mail` VARCHAR(100) NOT NULL DEFAULT '' COMMENT '信箱',
      `MailDate` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '寄信日期',
      PRIMARY KEY  (`ColName`,`ColSN`,`WebID`,`Mail`)
    ) ENGINE=MyISAM";
        $xoopsDB->queryF($sql);

        return true;
    }

    //新增小幫手
    public static function chk_chk23()
    {
        global $xoopsDB;
        $sql = 'SELECT count(*) FROM ' . $xoopsDB->prefix('tad_web_cate_assistant');
        $result = $xoopsDB->query($sql);
        if (empty($result)) {
            return true;
        }

        return false;
    }

    public static function go_update23()
    {
        global $xoopsDB;
        $sql = 'CREATE TABLE `' . $xoopsDB->prefix('tad_web_cate_assistant') . "` (
      `CateID` SMALLINT(6) UNSIGNED NOT NULL COMMENT '編號',
      `AssistantType` VARCHAR(100) NOT NULL DEFAULT '' COMMENT '用戶種類',
      `AssistantID` MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT 0 COMMENT '用戶ID',
      PRIMARY KEY (`CateID`,`AssistantType`,`AssistantID`)
    ) ENGINE=MyISAM";
        $xoopsDB->queryF($sql);

        return true;
    }

    //新增小幫手
    public static function chk_chk24()
    {
        global $xoopsDB;
        $sql = 'SELECT count(*) FROM ' . $xoopsDB->prefix('tad_web_assistant_post');
        $result = $xoopsDB->query($sql);
        if (empty($result)) {
            return true;
        }

        return false;
    }

    public static function go_update24()
    {
        global $xoopsDB;
        $sql = 'CREATE TABLE `' . $xoopsDB->prefix('tad_web_assistant_post') . "` (
      `plugin` VARCHAR(100) NOT NULL COMMENT '所屬外掛',
      `ColName` VARCHAR(100) NOT NULL DEFAULT '' COMMENT '欄位名稱',
      `ColSN` SMALLINT(5) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '欄位編號',
      `CateID` SMALLINT(6) UNSIGNED NOT NULL COMMENT '編號',
      `AssistantType` VARCHAR(100) NOT NULL DEFAULT '' COMMENT '用戶種類',
      `AssistantID` MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT 0 COMMENT '用戶ID',
      PRIMARY KEY (`plugin`,`ColName`,`ColSN`)
    ) ENGINE=MyISAM";
        $xoopsDB->queryF($sql);

        return true;
    }
}
