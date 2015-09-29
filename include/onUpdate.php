<?php

function xoops_module_update_tad_web(&$module, $old_version)
{
    global $xoopsDB;

    mk_dir(XOOPS_ROOT_PATH . "/uploads/tad_web");
    if (!chk_chk1()) {
        go_update1();
    }

    if (!chk_chk2()) {
        go_update2();
    }

    if (!chk_chk3()) {
        go_update3();
    }

    if (!chk_chk4()) {
        go_update4();
    }

    if (!chk_chk5()) {
        go_update5();
    }

    go_update6();
    chk_tad_web_block();

    if (!chk_chk7()) {
        go_update7();
    }

    if (chk_chk8()) {
        go_update8();
    }

    if (chk_chk9()) {
        go_update9();
    }

    if (chk_chk10()) {
        go_update10();
    }

    chk_sql();
    go_update_var();

    return true;
}

function go_update_var()
{
    include_once XOOPS_ROOT_PATH . '/modules/tad_web/function.php';
    $Webs = getAllWebInfo('WebTitle');
    foreach ($Webs as $WebID => $WebTitle) {
        mk_menu_var_file($WebID);
    }
}

function chk_sql()
{
    global $xoopsDB;
    include_once XOOPS_ROOT_PATH . '/modules/tad_web/function.php';
    $dir_plugins = get_dir_plugins();
    foreach ($dir_plugins as $dirname) {
        include XOOPS_ROOT_PATH . "/modules/tad_web/plugins/{$dirname}/config.php";
        if (!empty($pluginConfig['sql'])) {
            foreach ($pluginConfig['sql'] as $sql_name) {
                $sql    = "select count(*) from " . $xoopsDB->prefix($sql_name);
                $result = $xoopsDB->query($sql);
                if (empty($result)) {
                    $xoopsDB->queryFromFile(XOOPS_ROOT_PATH . "/modules/tad_web/plugins/{$dirname}/mysql.sql");
                }
            }
        }
    }
}

//刪除錯誤的重複欄位及樣板檔
function chk_tad_web_block()
{
    global $xoopsDB;
    //die(var_export($xoopsConfig));
    include XOOPS_ROOT_PATH . '/modules/tad_web/xoops_version.php';

    //先找出該有的區塊以及對應樣板
    foreach ($modversion['blocks'] as $i => $block) {
        $show_func                = $block['show_func'];
        $tpl_file_arr[$show_func] = $block['template'];
        $tpl_desc_arr[$show_func] = $block['description'];
    }

    //找出目前所有的樣板檔
    $sql    = "SELECT bid,name,visible,show_func,template FROM `" . $xoopsDB->prefix("newblocks") . "` WHERE `dirname` = 'tad_web' ORDER BY `func_num`";
    $result = $xoopsDB->query($sql);
    while (list($bid, $name, $visible, $show_func, $template) = $xoopsDB->fetchRow($result)) {
        //假如現有的區塊和樣板對不上就刪掉
        if ($template != $tpl_file_arr[$show_func]) {
            $sql = "delete from " . $xoopsDB->prefix("newblocks") . " where bid='{$bid}'";
            $xoopsDB->queryF($sql);

            //連同樣板以及樣板實體檔案也要刪掉
            $sql = "delete from " . $xoopsDB->prefix("tplfile") . " as a left join " . $xoopsDB->prefix("tplsource") . "  as b on a.tpl_id=b.tpl_id where a.tpl_refid='$bid' and a.tpl_module='tad_web' and a.tpl_type='block'";
            $xoopsDB->queryF($sql);
        } else {
            $sql = "update " . $xoopsDB->prefix("tplfile") . " set tpl_file='{$template}' , tpl_desc='{$tpl_desc_arr[$show_func]}' where tpl_refid='{$bid}'";
            $xoopsDB->queryF($sql);
        }
    }

}

//修改討論區計數欄位名稱
function chk_chk1()
{
    global $xoopsDB;
    $sql    = "select count(`DiscussCounter`) from " . $xoopsDB->prefix("tad_web_discuss");
    $result = $xoopsDB->query($sql);
    if (empty($result)) {
        return false;
    }

    return true;
}

function go_update1()
{
    global $xoopsDB;
    $sql = "ALTER TABLE " . $xoopsDB->prefix("tad_web_discuss") . " CHANGE `DisscussCounter` `DiscussCounter` SMALLINT( 6 ) UNSIGNED NOT NULL";
    $xoopsDB->queryF($sql) or redirect_header(XOOPS_URL . "/modules/system/admin.php?fct=modulesadmin", 30, mysql_error());
    return true;
}

//修改討論區發布者uid編號
function chk_chk2()
{
    global $xoopsDB;
    $sql    = "select count(`uid`) from " . $xoopsDB->prefix("tad_web_discuss");
    $result = $xoopsDB->query($sql);
    if (empty($result)) {
        return false;
    }

    return true;
}

function go_update2()
{
    global $xoopsDB;
    $sql = "ALTER TABLE " . $xoopsDB->prefix("tad_web_discuss") . " ADD `uid` SMALLINT( 6 ) UNSIGNED NOT NULL AFTER `WebID`";
    $xoopsDB->queryF($sql) or redirect_header(XOOPS_URL . "/modules/system/admin.php?fct=modulesadmin", 30, mysql_error());
    return true;
}

//修改討論區發布者編號
function chk_chk3()
{
    global $xoopsDB;
    $sql    = "select count(`MemID`) from " . $xoopsDB->prefix("tad_web_discuss");
    $result = $xoopsDB->query($sql);
    if (empty($result)) {
        return false;
    }

    return true;
}

function go_update3()
{
    global $xoopsDB;
    $sql = "ALTER TABLE " . $xoopsDB->prefix("tad_web_discuss") . " ADD `MemID` SMALLINT( 6 ) UNSIGNED NOT NULL AFTER `uid`";
    $xoopsDB->queryF($sql) or redirect_header(XOOPS_URL . "/modules/system/admin.php?fct=modulesadmin", 30, mysql_error());
    return true;
}

//新增討論區發布者姓名欄位
function chk_chk4()
{
    global $xoopsDB;
    $sql    = "select count(`MemName`) from " . $xoopsDB->prefix("tad_web_discuss");
    $result = $xoopsDB->query($sql);
    if (empty($result)) {
        return false;
    }

    return true;
}

function go_update4()
{
    global $xoopsDB;
    $sql = "ALTER TABLE " . $xoopsDB->prefix("tad_web_discuss") . " ADD `MemName` varchar(255) NOT NULL default '' AFTER `MemID`";
    $xoopsDB->queryF($sql) or redirect_header(XOOPS_URL . "/modules/system/admin.php?fct=modulesadmin", 30, mysql_error());
    return true;
}

//新增original_filename欄位
function chk_chk5()
{
    global $xoopsDB;
    $sql    = "select count(`original_filename`) from " . $xoopsDB->prefix("tad_web_files_center");
    $result = $xoopsDB->query($sql);
    if (empty($result)) {
        return false;
    }

    return true;
}

function go_update5()
{
    global $xoopsDB;
    $sql = "ALTER TABLE " . $xoopsDB->prefix("tad_web_files_center") . "
      ADD `original_filename` varchar(255) NOT NULL default '',
      ADD `hash_filename` varchar(255) NOT NULL default '',
      ADD `sub_dir` varchar(255) NOT NULL default ''";
    $xoopsDB->queryF($sql) or redirect_header(XOOPS_URL . "/modules/system/admin.php?fct=modulesadmin", 30, mysql_error());

    $sql = "update " . $xoopsDB->prefix("tad_web_files_center") . " set
    `original_filename`=`description`";
    $xoopsDB->queryF($sql) or redirect_header(XOOPS_URL . "/modules/system/admin.php?fct=modulesadmin", 30, mysql_error());
}

function go_update6()
{
    global $xoopsDB;

    $updir = XOOPS_ROOT_PATH . "/uploads/tad_web";
    $os    = (PATH_SEPARATOR == ':') ? "linux" : "win";

    //修正子目錄，並找出實體檔案沒有真的在子目錄下的
    $sql    = "select `files_sn`,`col_name`,`col_sn`,`kind`,`file_name`,`sub_dir` from " . $xoopsDB->prefix("tad_web_files_center") . " where `sub_dir` like '//%'";
    $result = $xoopsDB->queryF($sql) or die($sql);
    while (list($files_sn, $col_name, $col_sn, $kind, $file_name, $sub_dir) = $xoopsDB->fetchRow($result)) {
        $sub_dir = str_replace("//", "/", $sub_dir);
        $typedir = $kind == 'img' ? "image" : "file";

        $sql = "update  " . $xoopsDB->prefix("tad_web_files_center") . " set `sub_dir`='{$sub_dir}'  where `files_sn`='{$files_sn}'";
        $xoopsDB->queryF($sql) or die($sql);

        if (!file_exists("{$updir}{$sub_dir}/{$typedir}/{$file_name}")) {
            mk_dir("{$updir}{$sub_dir}");
            mk_dir("{$updir}{$sub_dir}/{$typedir}");

            $from = "{$updir}/{$typedir}/{$file_name}";
            $to   = "{$updir}{$sub_dir}/{$typedir}/{$file_name}";

            if ($os == "win" and _CHARSET == "UTF-8") {
                $from = iconv(_CHARSET, "Big5", $from);
                $to   = iconv(_CHARSET, "Big5", $to);
            } elseif ($os == "linux" and _CHARSET == "Big5") {
                $from = iconv(_CHARSET, "UTF-8", $from);
                $to   = iconv(_CHARSET, "UTF-8", $to);
            }

            rename($from, $to);
            if ($typedir == "image") {
                mk_dir("{$updir}{$sub_dir}");
                mk_dir("{$updir}{$sub_dir}/{$typedir}");
                mk_dir("{$updir}{$sub_dir}/{$typedir}/.thumbs");
                $from = "{$updir}/{$typedir}/.thumbs/{$file_name}";
                $to   = "{$updir}{$sub_dir}/{$typedir}/.thumbs/{$file_name}";

                if ($os == "win" and _CHARSET == "UTF-8") {
                    $from = iconv(_CHARSET, "Big5", $from);
                    $to   = iconv(_CHARSET, "Big5", $to);
                } elseif ($os == "linux" and _CHARSET == "Big5") {
                    $from = iconv(_CHARSET, "UTF-8", $from);
                    $to   = iconv(_CHARSET, "UTF-8", $to);
                }

                rename($from, $to);
            }
        }
    }

    //找出沒有放到子目錄的
    $sql    = "select `files_sn`,`col_name`,`col_sn`,`kind`,`file_name`,`sub_dir` from " . $xoopsDB->prefix("tad_web_files_center") . "";
    $result = $xoopsDB->queryF($sql) or die($sql);
    while (list($files_sn, $col_name, $col_sn, $kind, $file_name, $sub_dir) = $xoopsDB->fetchRow($result)) {

        $typedir = $kind == 'img' ? "image" : "file";
        $WebID   = intval(substr($sub_dir, 1));
        if (empty($WebID)) {
            if ($col_name == "WebOwner" or $col_name == "WebLogo") {
                $WebID = $col_sn;
            } elseif ($col_name == "MemID") {
                $sql         = "select `WebID` from " . $xoopsDB->prefix("tad_web_link_mems") . " where `MemID` = '{$col_sn}'";
                $result2     = $xoopsDB->queryF($sql) or die($sql);
                list($WebID) = $xoopsDB->fetchRow($result2);
            } elseif ($col_name == "ActionID") {
                $sql         = "select `WebID` from " . $xoopsDB->prefix("tad_web_action") . " where `ActionID` = '{$col_sn}'";
                $result2     = $xoopsDB->queryF($sql) or die($sql);
                list($WebID) = $xoopsDB->fetchRow($result2);
            } elseif ($col_name == "fsn") {
                $sql         = "select `WebID` from " . $xoopsDB->prefix("tad_web_files") . " where `fsn` = '{$col_sn}'";
                $result2     = $xoopsDB->queryF($sql) or die($sql);
                list($WebID) = $xoopsDB->fetchRow($result2);
            } elseif ($col_name == "NewsID") {
                $sql         = "select `WebID` from " . $xoopsDB->prefix("tad_web_news") . " where `NewsID` = '{$col_sn}'";
                $result2     = $xoopsDB->queryF($sql) or die($sql);
                list($WebID) = $xoopsDB->fetchRow($result2);
            }
        }

        $sql = "update " . $xoopsDB->prefix("tad_web_files_center") . " set `sub_dir`='/{$WebID}'  where `files_sn`='{$files_sn}'";
        $xoopsDB->queryF($sql) or die($sql);

        mk_dir("{$updir}/{$WebID}");
        mk_dir("{$updir}/{$WebID}/{$typedir}");
        if ($typedir == "image") {
            mk_dir("{$updir}/{$WebID}/{$typedir}");
            mk_dir("{$updir}/{$WebID}/{$typedir}/.thumbs");
        }

        $from = "{$updir}/{$typedir}/{$file_name}";
        $to   = "{$updir}/{$WebID}/{$typedir}/{$file_name}";

        if ($os == "win" and _CHARSET == "UTF-8") {
            $from = iconv(_CHARSET, "Big5", $from);
            $to   = iconv(_CHARSET, "Big5", $to);
        } elseif ($os == "linux" and _CHARSET == "Big5") {
            $from = iconv(_CHARSET, "UTF-8", $from);
            $to   = iconv(_CHARSET, "UTF-8", $to);
        }

        rename($from, $to);
        if ($typedir == "image") {
            mk_dir("{$updir}/{$WebID}");
            mk_dir("{$updir}/{$WebID}/{$typedir}");
            mk_dir("{$updir}/{$WebID}/{$typedir}/.thumbs");

            $from = "{$updir}/{$typedir}/.thumbs/{$file_name}";
            $to   = "{$updir}/{$WebID}/{$typedir}/.thumbs/{$file_name}";

            if ($os == "win" and _CHARSET == "UTF-8") {
                $from = iconv(_CHARSET, "Big5", $from);
                $to   = iconv(_CHARSET, "Big5", $to);
            } elseif ($os == "linux" and _CHARSET == "Big5") {
                $from = iconv(_CHARSET, "UTF-8", $from);
                $to   = iconv(_CHARSET, "UTF-8", $to);
            }
            rename($from, $to);
        }
    }
}

//修改分類名稱欄位名稱
function chk_chk7()
{
    global $xoopsDB;
    $sql    = "select count(`ActionKind`) from " . $xoopsDB->prefix("tad_web_action");
    $result = $xoopsDB->query($sql);
    if (empty($result)) {
        return false;
    }

    return true;
}

function go_update7()
{
    global $xoopsDB;
    $sql = "ALTER TABLE " . $xoopsDB->prefix("tad_web_action") . "
      ADD `ActionKind` varchar(255) NOT NULL default 'action'";
    $xoopsDB->queryF($sql) or redirect_header(XOOPS_URL . "/modules/system/admin.php?fct=modulesadmin", 30, mysql_error());

    $sql = "ALTER TABLE " . $xoopsDB->prefix("tad_web_cate") . " CHANGE `CateName` `CateName` varchar(255) NOT NULL DEFAULT '' NOT NULL";
    $xoopsDB->queryF($sql) or redirect_header(XOOPS_URL . "/modules/system/admin.php?fct=modulesadmin", 30, mysql_error());

    return true;
}

//新增作品分享表格
function chk_chk8()
{
    global $xoopsDB;
    $sql    = "select count(*) from " . $xoopsDB->prefix("tad_web_works");
    $result = $xoopsDB->query($sql);
    if (empty($result)) {
        return true;
    }

    return false;
}

function go_update8()
{
    global $xoopsDB;
    $sql = "CREATE TABLE `" . $xoopsDB->prefix("tad_web_works") . "` (
      `WorksID` smallint(5) unsigned NOT NULL auto_increment COMMENT '檔案流水號',
      `CateID` smallint(6) unsigned NOT NULL default 0,
      `WebID` smallint(6) unsigned NOT NULL default 0 COMMENT '所屬班級',
      `WorkName` varchar(255) NOT NULL default '' COMMENT '活動名稱',
      `WorkDesc` text NOT NULL COMMENT '活動說明',
      `uid` mediumint(8) unsigned NOT NULL default 0 COMMENT '上傳者',
      `WorksDate` datetime NOT NULL default '0000-00-00 00:00:00' COMMENT '日期',
      `WorksCount` smallint(6) unsigned NOT NULL default 0 COMMENT '人氣',
    PRIMARY KEY (`WorksID`)
    ) ENGINE=MyISAM";
    $xoopsDB->queryF($sql);
}

//新增聯絡簿
function chk_chk9()
{
    global $xoopsDB;
    $sql    = "select count(*) from " . $xoopsDB->prefix("tad_web_homework");
    $result = $xoopsDB->query($sql);
    if (empty($result)) {
        return true;
    }

    return false;
}

function go_update9()
{
    global $xoopsDB;
    $sql = "CREATE TABLE `" . $xoopsDB->prefix("tad_web_homework") . "` (
      `HomeworkID` smallint(6) unsigned NOT NULL auto_increment COMMENT '編號',
      `CateID` smallint(6) unsigned NOT NULL default 0,
      `WebID` smallint(6) unsigned NOT NULL default 0 COMMENT '所屬班級',
      `HomeworkTitle` varchar(255) NOT NULL default '' COMMENT '標題',
      `HomeworkContent` text NOT NULL COMMENT '內容',
      `HomeworkDate` datetime NOT NULL default '0000-00-00 00:00:00' COMMENT '發布日期',
      `toCal` datetime NOT NULL default '0000-00-00 00:00:00' COMMENT '加到行事曆',
      `HomeworkCounter` smallint(6) unsigned NOT NULL default 0 COMMENT '人氣',
      `uid` mediumint(8) unsigned NOT NULL default 0 COMMENT '發布者',
    PRIMARY KEY (`HomeworkID`)
    ) ENGINE=MyISAM";
    $xoopsDB->queryF($sql);

    $sql    = "select * from `" . $xoopsDB->prefix("tad_web_news") . "` where NewsKind='homework'";
    $result = $xoopsDB->queryF($sql) or die($sql);
    while ($all = $xoopsDB->fetchArray($result)) {
        foreach ($all as $k => $v) {
            $$k = $v;
        }

        $sql = "insert into `" . $xoopsDB->prefix("tad_web_homework") . "` (`WebID`, `HomeworkTitle`, `HomeworkContent`, `HomeworkDate`, `toCal`, `HomeworkCounter`, `uid`) values('{$WebID}', '{$NewsTitle}', '{$NewsContent}', '{$NewsDate}', '{$toCal}', '{$NewsCounter}', '{$uid}')";
        $xoopsDB->queryF($sql);

        $sql = "delete from `" . $xoopsDB->prefix("tad_web_news") . "` where `NewsID`='{$NewsID}'";
        $xoopsDB->queryF($sql);
    }
}

//新增外掛表格
function chk_chk10()
{
    global $xoopsDB;
    $sql    = "select count(*) from " . $xoopsDB->prefix("tad_web_plugins");
    $result = $xoopsDB->query($sql);
    if (empty($result)) {
        return true;
    }

    return false;
}

function go_update10()
{
    global $xoopsDB;
    $sql = "CREATE TABLE `" . $xoopsDB->prefix("tad_web_plugins") . "` (
      `PluginDirname` varchar(100) NOT NULL COMMENT '目錄名稱',
      `PluginTitle` varchar(255) NOT NULL COMMENT '外掛名稱',
      `PluginSort` smallint(6) unsigned NOT NULL default 0 COMMENT '排序',
      `PluginEnable` enum('1','0') NOT NULL default '1' COMMENT '狀態',
      `WebID` smallint(6) unsigned NOT NULL default 0 COMMENT '所屬班級',
    PRIMARY KEY (`PluginDirname`,`WebID`)
    ) ENGINE=MyISAM;";
    $xoopsDB->queryF($sql);
}

//建立目錄
function mk_dir($dir = "")
{
    //若無目錄名稱秀出警告訊息
    if (empty($dir)) {
        return;
    }

    //若目錄不存在的話建立目錄
    if (!is_dir($dir)) {
        umask(000);
        //若建立失敗秀出警告訊息
        mkdir($dir, 0777);
    }
}

//拷貝目錄
function full_copy($source = "", $target = "")
{
    if (is_dir($source)) {
        @mkdir($target);
        $d = dir($source);
        while (false !== ($entry = $d->read())) {
            if ($entry == '.' || $entry == '..') {
                continue;
            }

            $Entry = $source . '/' . $entry;
            if (is_dir($Entry)) {
                full_copy($Entry, $target . '/' . $entry);
                continue;
            }
            copy($Entry, $target . '/' . $entry);
        }
        $d->close();
    } else {
        copy($source, $target);
    }
}

function rename_win($oldfile, $newfile)
{
    if (!rename($oldfile, $newfile)) {
        if (copy($oldfile, $newfile)) {
            unlink($oldfile);
            return true;
        }
        return false;
    }
    return true;
}
