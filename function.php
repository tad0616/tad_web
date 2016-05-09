<?php
define("TADTOOLS_PATH", XOOPS_ROOT_PATH . "/modules/tadtools");
define("TADTOOLS_URL", XOOPS_URL . "/modules/tadtools");

if (!file_exists(XOOPS_ROOT_PATH . "/modules/tadtools/TadUpFiles.php")) {
    redirect_header("http://campus-xoops.tn.edu.tw/modules/tad_modules/index.php?module_sn=1", 3, _TAD_NEED_TADTOOLS);
}

include_once XOOPS_ROOT_PATH . "/modules/tadtools/TadUpFiles.php";
$TadUpFiles = new TadUpFiles("tad_web");
$subdir     = isset($WebID) ? "/{$WebID}" : "";
$TadUpFiles->set_dir('subdir', $subdir);

//引入TadTools的函式庫
include_once TADTOOLS_PATH . "/tad_function.php";
require_once "function_block.php";

include_once XOOPS_ROOT_PATH . "/modules/tad_web/class/cate.php";
include_once XOOPS_ROOT_PATH . "/modules/tad_web/class/power.php";
include_once XOOPS_ROOT_PATH . "/modules/tad_web/class/tags.php";

//判斷是否對該模組有管理權限
$isAdmin    = false;
$LoginMemID = $LoginMemName = $LoginMemNickName = $LoginWebID = $LoginParentID = $LoginParentName = $LoginParentMemID = '';
$MyWebs     = array();
$isMyWeb    = false;
if ($xoopsUser) {
    if (!$xoopsModule) {
        $modhandler  = &xoops_gethandler('module');
        $xoopsModule = &$modhandler->getByDirname("tad_web");
    }
    $module_id = $xoopsModule->getVar('mid');
    $isAdmin   = $xoopsUser->isAdmin($module_id);
    //我的班級ID（陣列）
    $MyWebs = MyWebID('all');

    //目前瀏覽的是否是我的班級？
    $isMyWeb = ($isAdmin) ? true : in_array($WebID, $MyWebs);
} else {
    $LoginMemID       = isset($_SESSION['LoginMemID']) ? $_SESSION['LoginMemID'] : null;
    $LoginMemName     = isset($_SESSION['LoginMemName']) ? $_SESSION['LoginMemName'] : null;
    $LoginMemNickName = isset($_SESSION['LoginMemNickName']) ? $_SESSION['LoginMemNickName'] : null;
    $LoginWebID       = isset($_SESSION['LoginWebID']) ? $_SESSION['LoginWebID'] : null;

    $LoginParentID    = isset($_SESSION['LoginParentID']) ? $_SESSION['LoginParentID'] : null;
    $LoginParentName  = isset($_SESSION['LoginParentName']) ? $_SESSION['LoginParentName'] : null;
    $LoginParentMemID = isset($_SESSION['LoginParentMemID']) ? $_SESSION['LoginParentMemID'] : null;
}

/********************* 自訂函數 *********************/
//取得已安裝的區塊
function get_blocks($WebID)
{
    global $xoopsDB;
    $sql    = "select * from " . $xoopsDB->prefix("tad_web_blocks") . " where `WebID`='{$WebID}' order by `BlockSort`";
    $result = $xoopsDB->queryF($sql) or web_error($sql);
    $Blocks = '';
    while ($all = $xoopsDB->fetchArray($result)) {
        $Blocks[] = $all;
    }
    return $Blocks;
}

//取得所有區塊設定
function get_dir_blocks()
{
    global $xoopsConfig;
    $plugins = get_dir_plugins();
    foreach ($plugins as $plugin) {
        $config_blocks_file = XOOPS_ROOT_PATH . "/modules/tad_web/plugins/{$plugin}/config_blocks.php";
        if (file_exists($config_blocks_file)) {
            include_once XOOPS_ROOT_PATH . "/modules/tad_web/plugins/{$plugin}/langs/{$xoopsConfig['language']}.php";
            include $config_blocks_file;
        }
    }
    return $blockConfig;
}

//取得各外掛及系統所有區塊(onUpdate.php)
function get_all_blocks($value = 'title')
{
    global $xoopsDB;

    $myts = MyTextSanitizer::getInstance();

    //來自plugin的區塊
    $allBlockConfig = get_dir_blocks();
    foreach ($allBlockConfig as $plugin => $blockConfig) {
        foreach ($blockConfig as $func => $block) {
            if ($value == "plugin") {
                $block_option[$func] = $plugin;
            } elseif ($value == "config") {
                $block_option[$func] = $block['config'];
            } elseif ($value == "tpl") {
                $block_option[$func] = $block['tpl'];
            } elseif ($value == "position") {
                $block_option[$func] = $block['position'];
            } else {
                $name                = $myts->htmlSpecialChars($block['name']);
                $block_option[$func] = $name;

            }
        }
    }
    return $block_option;
}

function get_position_blocks($WebID, $BlockPosition)
{
    global $xoopsDB, $plugin_menu_var;
    if ($BlockPosition == 'uninstall') {
        //找出這個網站已經安裝的分享區塊
        $share_blocks_id  = get_share_blocks($WebID);
        $all_share_blocks = implode("','", $share_blocks_id);
        $andShareBlocks   = empty($all_share_blocks) ? '' : "and BlockID not in('{$all_share_blocks}')";
        $andBlockPosition = "(`WebID`='{$WebID}' and (`BlockPosition`='uninstall' or `BlockPosition`='') and plugin!='share' ) or (plugin='share' and WebID!='{$WebID}' {$andShareBlocks})";
    } else {
        $andBlockPosition = "`WebID`='{$WebID}' and `BlockPosition`='{$BlockPosition}' and `plugin`!='share'";
    }
    $sql    = "select * from " . $xoopsDB->prefix("tad_web_blocks") . " where  $andBlockPosition order by `BlockSort`";
    $result = $xoopsDB->queryF($sql) or web_error($sql);
    $Blocks = '';
    $i      = 0;
    while ($all = $xoopsDB->fetchArray($result)) {
        $plugin = $all['plugin'];
        if ($plugin != 'custom' and $plugin != 'share' and $plugin != 'system') {
            if (empty($plugin_menu_var[$plugin])) {
                continue;
            }

        }

        $Blocks[$i]               = $all;
        $Blocks[$i]['BlockShare'] = !empty($all['ShareFrom']) ? 1 : 0;
        $BlockEnable              = $all['BlockEnable'] == 1 ? '1' : '0';
        $config                   = json_decode($all['BlockConfig'], true);
        $Blocks[$i]['config']     = $config;

        $Blocks[$i]['icon'] = "<img src=\"images/show{$BlockEnable}.gif\" id=\"{$all['BlockID']}_icon\" alt=\"{$all['BlockTitle']}\" title=\"{$BlockEnable}\" style=\"cursor: pointer;\" onClick=\"enableBlock('{$all['BlockID']}')\" >";
        $i++;
    }
    return $Blocks;
}

//取得某網站有使用的分享區塊ID
function get_share_blocks($WebID)
{
    global $xoopsDB;
    $share_blocks = '';
    $sql          = "select ShareFrom from " . $xoopsDB->prefix("tad_web_blocks") . " where `WebID`='{$WebID}' and `plugin`='custom' and `ShareFrom` > 0";
    $result       = $xoopsDB->queryF($sql) or web_error($sql);
    while (list($ShareFromID) = $xoopsDB->fetchRow($result)) {
        $share_blocks[] = $ShareFromID;
    }

    if (empty($share_blocks)) {
        return;
    }

    return $share_blocks;
}

//取得所有顯示的區塊
function get_display_blocks($WebID, $BlockEnable = 1)
{
    global $xoopsDB;
    $andBlockEnable = is_null($BlockEnable) ? "" : "and BlockEnable='{$BlockEnable}'";
    $sql            = "select BlockName from " . $xoopsDB->prefix("tad_web_blocks") . " where `WebID`='{$WebID}' {$andBlockEnable}";
    $result         = $xoopsDB->queryF($sql) or web_error($sql);

    $i      = 0;
    $Blocks = '';
    while ($all = $xoopsDB->fetchArray($result)) {
        $Blocks[$i] = $all;
        $i++;
    }
    return $Blocks;
}

//自動取得tad_web_blocks的最新排序
function max_blocks_sort($WebID, $BlockPosition)
{
    global $xoopsDB;
    $sql        = "select max(`BlockSort`) from " . $xoopsDB->prefix("tad_web_blocks") . " where WebID='$WebID' and BlockPosition='{$BlockPosition}'";
    $result     = $xoopsDB->query($sql) or web_error($sql);
    list($sort) = $xoopsDB->fetchRow($result);
    return ++$sort;
}

//取得角色陣列
function get_web_roles($defWebID = '', $defRole = '')
{

    global $xoopsDB;

    $andWebID = empty($defWebID) ? "" : "and `WebID`='$defWebID'";
    $andRole  = empty($defRole) ? "" : "and `role`='$defRole'";
    $sql      = "select uid from " . $xoopsDB->prefix("tad_web_roles") . " where 1 $andRole $andWebID ";
    $result   = $xoopsDB->queryF($sql) or web_error($sql);
    $users    = '';
    $i        = 0;
    while (list($uid) = $xoopsDB->fetchRow($result)) {
        $users[$i] = $uid;
        $i++;
    }
    // $sql = "select * from " . $xoopsDB->prefix("tad_web_roles") . " where 1 $andRole $andWebID ";
    // $result = $xoopsDB->queryF($sql) or web_error($sql);

    // $i = 0;
    // while ($all = $xoopsDB->fetchArray($result)) {
    //     $users[$i] = $all;
    //     $i++;
    // }

    return $users;

}

//取得所有網站設定值
function get_web_all_config($WebID = "")
{
    global $xoopsDB;
    //die('test...');
    if (empty($WebID)) {
        return;
    }
    if (file_exists(XOOPS_ROOT_PATH . "/themes/for_tad_web_theme/theme_config.php")) {
        include XOOPS_ROOT_PATH . "/themes/for_tad_web_theme/theme_config.php";
        mk_dir(XOOPS_ROOT_PATH . "/uploads/tad_web/{$WebID}");
        mk_dir(XOOPS_ROOT_PATH . "/uploads/tad_web/{$WebID}/bg");
        mk_dir(XOOPS_ROOT_PATH . "/uploads/tad_web/{$WebID}/head");
        mk_dir(XOOPS_ROOT_PATH . "/uploads/tad_web/{$WebID}/logo");

        if (!file_exists(XOOPS_ROOT_PATH . "/uploads/tad_web/{$WebID}/bg/{$tad_web_config['web_bg']}")) {
            copy(XOOPS_ROOT_PATH . "/modules/tad_web/images/bg/{$tad_web_config['web_bg']}", XOOPS_ROOT_PATH . "/uploads/tad_web/{$WebID}/bg/{$tad_web_config['web_bg']}");
        }

        if (!file_exists(XOOPS_ROOT_PATH . "/uploads/tad_web/{$WebID}/head/{$tad_web_config['web_head']}")) {
            copy(XOOPS_ROOT_PATH . "/modules/tad_web/images/head/{$tad_web_config['web_head']}", XOOPS_ROOT_PATH . "/uploads/tad_web/{$WebID}/head/{$tad_web_config['web_head']}");
        }

        if (!file_exists(XOOPS_ROOT_PATH . "/uploads/tad_web/{$WebID}/logo/{$tad_web_config['web_logo']}")) {
            copy(XOOPS_ROOT_PATH . "/uploads/tad_web/{$WebID}/auto_logo/auto_logo.png", XOOPS_ROOT_PATH . "/uploads/tad_web/{$WebID}/logo/{$tad_web_config['web_logo']}");
        }

    } else {
        web_error("Need to install 'for_tad_web_theme' theme.");
    }
    //die(var_export($tad_web_config));
    $sql = "select `ConfigName`,`ConfigValue` from " . $xoopsDB->prefix("tad_web_config") . " where `WebID`='$WebID'";

    $result = $xoopsDB->queryF($sql) or web_error($sql);

    while (list($ConfigName, $ConfigValue) = $xoopsDB->fetchRow($result)) {
        $tad_web_config[$ConfigName] = $ConfigValue;
    }

    return $tad_web_config;
}

//儲存網站設定
function save_web_config($ConfigName = "", $ConfigValue = "", $WebID)
{
    global $xoopsDB, $xoopsUser, $isMyWeb;
    // if (!empty($WebID) and !$isMyWeb) { //這樣後台會有問題
    //     return;
    // }

    if (is_array($ConfigValue)) {
        $ConfigValue = implode(';', $ConfigValue);
    }
    $myts        = MyTextSanitizer::getInstance();
    $ConfigValue = $myts->addSlashes($ConfigValue);

    $sql = "replace into " . $xoopsDB->prefix("tad_web_config") . "
      (`ConfigName`, `ConfigValue`, `WebID`)
      values('{$ConfigName}' , '{$ConfigValue}', '{$WebID}')";
    // die($sql);
    $xoopsDB->queryF($sql) or web_error($sql);

}

//取得資料庫中的外掛資料
function get_db_plugins($WebID = "", $only_enable = false)
{
    global $xoopsDB;
    $andEnable = ($only_enable) ? "and PluginEnable='1'" : "";

    //取得tad_web_plugins資料表中該網站所有設定值
    $sql    = "select * from " . $xoopsDB->prefix("tad_web_plugins") . " where WebID='{$WebID}' {$andEnable} order by PluginSort";
    $result = $xoopsDB->query($sql) or web_error($sql);

    while ($all = $xoopsDB->fetchArray($result)) {
        $dirname           = $all['PluginDirname'];
        $plugins[$dirname] = $all;
    }
    return $plugins;
}

function get_db_plugin($WebID = "", $dirname = "")
{
    global $xoopsDB;

    $sql    = "select * from " . $xoopsDB->prefix("tad_web_plugins") . " where WebID='{$WebID}' and PluginDirname='{$dirname}'";
    $result = $xoopsDB->query($sql) or web_error($sql);

    $all = $xoopsDB->fetchArray($result);
    return $all;
}

//取得硬碟中外掛模組的名稱陣列
function get_dir_plugins()
{
    $dir = XOOPS_ROOT_PATH . "/modules/tad_web/plugins/";
    if (is_dir($dir)) {
        if ($dh = opendir($dir)) {
            while (($file = readdir($dh)) !== false) {
                if (filetype($dir . $file) == "dir") {
                    if (substr($file, 0, 1) == '.') {
                        continue;
                    }
                    if (!empty($file)) {
                        $plugins[] = $file;
                    }
                }
            }
            closedir($dh);
        }
    }
    sort($plugins);
    return $plugins;
}

//取得所有外掛
function get_plugins($WebID = '', $mode = 'show', $only_enable = false)
{
    global $TadUpFiles, $xoopsDB;

    $pluginsVal = get_db_plugins($WebID, $only_enable);

    $dir         = XOOPS_ROOT_PATH . "/modules/tad_web/plugins/";
    $dir_plugins = get_dir_plugins();
    foreach ($dir_plugins as $file) {
        if ($only_enable and empty($pluginsVal)) {
            continue;
        }

        $pluginVal = get_db_plugin($WebID, $file);
        include $dir . $file . "/config.php";

        //發現新外掛時，預設啟用之
        if (empty($pluginVal)) {
            $sort = plugins_max_sort($WebID, $file);
            $sql  = "replace into " . $xoopsDB->prefix("tad_web_plugins") . " (`PluginDirname`, `PluginTitle`, `PluginSort`, `PluginEnable`, `WebID`) values('{$file}', '{$pluginConfig['name']}', '{$sort}', '1', '{$WebID}')";
            $xoopsDB->queryF($sql) or web_error($sql);
        }

        $pluginConfigs[$file] = $pluginConfig;
    }

    $new_pluginsVal = get_db_plugins($WebID, $only_enable);
    $i              = 0;
    foreach ($new_pluginsVal as $dirname => $plugin) {
        $plugins[$i]['dirname'] = $dirname;
        $plugins[$i]['config']  = $pluginConfigs[$dirname];
        $plugins[$i]['db']      = $new_pluginsVal[$dirname];

        if ($mode == 'edit') {
            // $plugins[$i]['upform'] = $TadUpFiles->upform(true, $dirname, '1', false);
        }

        $i++;
    }

    //die(var_export($plugins));
    return $plugins;
}

function plugins_max_sort($WebID, $dirname)
{
    global $xoopsDB;

    $sql    = "select max(PluginSort) from " . $xoopsDB->prefix("tad_web_plugins") . " where WebID='{$WebID}' and PluginDirname='{$dirname}'";
    $result = $xoopsDB->query($sql) or web_error($sql);

    list($sort) = $xoopsDB->fetchRow($result);
    $sort++;
    return $sort;
}

//共同樣板部份
function common_template($WebID, $web_all_config = "")
{
    global $xoopsTpl, $xoopsDB;

    if ($WebID) {
        $xoopsTpl->assign('WebID', $WebID);

        if (!file_exists(XOOPS_ROOT_PATH . "/uploads/tad_web/{$WebID}/header.png")) {
            output_head_file($WebID);
        }
        if (!file_exists(XOOPS_ROOT_PATH . "/uploads/tad_web/{$WebID}/header_480.png")) {
            output_head_file_480($WebID);
        }
        if (empty($web_all_config)) {
            $web_all_config = get_web_all_config($WebID);
        }

        if (empty($web_all_config['default_class'])) {

            $sql                 = "select max(`CateID`) from " . $xoopsDB->prefix("tad_web_cate") . " where `ColName` = 'aboutus' AND `CateEnable` = '1' AND `WebID` = '{$WebID}'";
            $result              = $xoopsDB->query($sql) or web_error($sql);
            list($default_class) = $xoopsDB->fetchRow($result);
            save_web_config("default_class", $default_class, $WebID);
            $web_all_config['default_class'] = $default_class;

        }
        // die(var_export($web_all_config));
        foreach ($web_all_config as $ConfigName => $ConfigValue) {
            if ($ConfigName == "login_config") {
                $ConfigValue = explode(';', $ConfigValue);
            }
            $xoopsTpl->assign($ConfigName, $ConfigValue);
        }
    }

    $xoopsTpl->assign('_IS_EZCLASS', _IS_EZCLASS);
    // return $web_all_config;
}

//製作選單
function mk_menu_var_file($WebID = null)
{
    global $xoopsDB;
    if (empty($WebID)) {
        return;
    }

    $all_plugins = get_plugins($WebID, 'show');

    $current = "<?php\n";
    $i       = 1;
    foreach ($all_plugins as $plugin) {
        // die(var_export($plugin));
        $dirname = $plugin['dirname'];

        if ($dirname == "system") {
            continue;
        }

        if ($plugin['db']['PluginEnable'] != '1') {
            $current .= "if(defined('_SHOW_UNABLE') and _SHOW_UNABLE=='1'){\n";
        }

        $current .= "\$menu_var['{$dirname}']['id']     = $i;\n";
        $current .= "\$menu_var['{$dirname}']['title']  = '{$plugin['db']['PluginTitle']}';\n";
        $current .= "\$menu_var['{$dirname}']['url']    = '{$dirname}.php?WebID={$WebID}';\n";
        $current .= "\$menu_var['{$dirname}']['target'] = '_self';\n";
        $current .= "\$menu_var['{$dirname}']['WebID']  = '{$WebID}';\n";
        $current .= "\$menu_var['{$dirname}']['dirname']  = '{$dirname}';\n";
        $current .= "\$menu_var['{$dirname}']['cate'] = '{$plugin['config']['cate']}';\n";
        $current .= "\$menu_var['{$dirname}']['cate_table'] = '{$plugin['config']['cate_table']}';\n";
        $current .= "\$menu_var['{$dirname}']['short']  = '{$plugin['config']['short']}';\n";
        $current .= "\$menu_var['{$dirname}']['icon']   = '{$plugin['config']['icon']}';\n";
        $current .= "\$menu_var['{$dirname}']['enable']     = {$plugin['db']['PluginEnable']};\n";
        $current .= "\$menu_var['{$dirname}']['setup']   = '{$plugin['config']['setup']}';\n";
        $current .= "\$menu_var['{$dirname}']['add']   = '{$plugin['config']['add']}';\n";
        $current .= "\$menu_var['{$dirname}']['menu']   = '{$plugin['config']['menu']}';\n";
        $current .= "\$menu_var['{$dirname}']['export']   = '{$plugin['config']['export']}';\n";
        $current .= "\$menu_var['{$dirname}']['tag']   = '{$plugin['config']['tag']}';\n";
        $current .= "\$menu_var['{$dirname}']['top_score']   = '{$plugin['config']['top_score']}';\n";
        $current .= "\$menu_var['{$dirname}']['assistant']   = '{$plugin['config']['assistant']}';\n";

        if ($plugin['db']['PluginEnable'] != '1') {
            $current .= "}\n\n";
        } else {
            $current .= "\n";
        }

        if ($plugin['db']['PluginEnable'] == '1') {
            $plugin_enable_arr[] = $dirname;
        }

        $i++;
    }

    if (!empty($WebID)) {
        $file = XOOPS_ROOT_PATH . "/uploads/tad_web/{$WebID}/menu_var.php";
        save_web_config('web_plugin_enable_arr', implode(',', $plugin_enable_arr), $WebID);

    }

    file_put_contents($file, $current);

    $display_blocks_arr = get_display_blocks($WebID);

    if (empty($display_blocks_arr)) {
        //取得系統所有區塊
        $block_option   = get_all_blocks();
        $block_plugin   = get_all_blocks('plugin');
        $block_config   = get_all_blocks('config');
        $block_position = get_all_blocks('position');

        //存入既有設定
        $sort = 1;
        foreach ($block_option as $func => $name) {

            $BlockEnable = 1;

            if (isset($block_config[$func]) and !empty($block_config[$func])) {
                if (PHP_VERSION_ID >= 50400) {
                    $BlockConfig = json_encode($block_config[$func], JSON_UNESCAPED_UNICODE);
                } else {
                    array_walk_recursive($block_config[$func], function (&$value, $key) {
                        if (is_string($value)) {
                            $value = urlencode($value);
                        }
                    });
                    $BlockConfig = urldecode(json_encode($block_config[$func]));
                }
            } else {
                $BlockConfig = '';
            }

            $BlockConfig = str_replace('{{WebID}}', $WebID, $BlockConfig);
            $sql         = "insert into `" . $xoopsDB->prefix("tad_web_blocks") . "` (`BlockName`, `BlockCopy`, `BlockTitle`, `BlockContent`, `BlockEnable`, `BlockConfig`, `BlockPosition`, `BlockSort`, `WebID`, `plugin`) values('{$func}', '0', '{$name}', '', '{$BlockEnable}', '{$BlockConfig}', '{$block_position[$func]}', '{$sort}', '{$WebID}', '{$block_plugin[$func]}')";
            $xoopsDB->queryF($sql) or web_error($sql);
            $sort++;
        }
    }
}

function get_tad_web_mems($MemID)
{
    global $xoopsDB;

    $sql    = "select * from " . $xoopsDB->prefix("tad_web_mems") . " where MemID='{$MemID}'";
    $result = $xoopsDB->query($sql) or web_error($sql);
    $all    = $xoopsDB->fetchArray($result);

    $sql                   = "select MemNum,CateID from " . $xoopsDB->prefix("tad_web_link_mems") . " where `MemID`='{$MemID}' limit 0,1";
    $result                = $xoopsDB->queryF($sql) or web_error($sql);
    list($MemNum, $CateID) = $xoopsDB->fetchRow($result);
    $all['MemNum']         = $MemNum;
    $all['CateID']         = $CateID;
    return $all;
}

function get_tad_web_parent($ParentID = "", $code = "")
{
    global $xoopsDB;
    $andCode = !empty($code) ? "and `code`='{$code}'" : "";
    $sql     = "select * from " . $xoopsDB->prefix("tad_web_mem_parents") . " where `ParentID`='{$ParentID}' {$andCode}";
    $result  = $xoopsDB->queryF($sql) or web_error($sql);
    $all     = $xoopsDB->fetchArray($result);
    return $all;
}

function get_tad_web_link_mems($MemID = "", $CateID = "")
{
    global $xoopsDB;

    $sql = "select * from " . $xoopsDB->prefix("tad_web_link_mems") . " where MemID='{$MemID}' and CateID='{$CateID}'";
    // die($sql);
    $result = $xoopsDB->query($sql) or web_error($sql);
    $all    = $xoopsDB->fetchArray($result);
    // die(var_export($all));
    return $all;
}

//以流水號取得某筆tad_web資料
function get_tad_web($WebID = "", $enable = false)
{
    global $xoopsDB, $isMyWeb, $isAdmin;
    if (empty($WebID)) {
        return;
    }

    $andEnable = ($enable and !$isMyWeb and !$isAdmin) ? "and WebEnable='1'" : "";

    $sql = "select * from " . $xoopsDB->prefix("tad_web") . " where WebID='$WebID' {$andEnable}";

    $result = $xoopsDB->query($sql) or web_error($sql);
    $data   = $xoopsDB->fetchArray($result);

    if ($enable and (empty($data))) {
        redirect_header("index.php", 3, _MD_TCW_WEB_NOT_EXIST);
    }
    return $data;
}

//取得網頁下成員的人數
function memAmount($WebID = "")
{
    global $xoopsDB;

    $sql         = "select count(*) from " . $xoopsDB->prefix("tad_web_link_mems") . " where WebID='{$WebID}'";
    $result      = $xoopsDB->query($sql) or web_error($sql);
    list($count) = $xoopsDB->fetchRow($result);
    return $count;
}

//判斷是否為管理員
function isAdmin()
{
    global $xoopsUser, $xoopsModule;
    $isAdmin = false;
    if ($xoopsUser) {
        $module_id = $xoopsModule->getVar('mid');
        $isAdmin   = $xoopsUser->isAdmin($module_id);
    }
    return $isAdmin;
}

//登出按鈕
function logout_button($interface_menu = array())
{
    return $interface_menu;
}

//取得目前的學年學期陣列
function get_seme()
{
    global $xoopsDB;
    $y = date("Y");
    $m = date("n");
    $d = date("j");
    if ($m >= 8) {
        $ys[0] = $y - 1911;
        $ys[1] = 1;
    } elseif ($m >= 2) {
        $ys[0] = $y - 1912;
        $ys[1] = 2;
    } else {
        $ys[0] = $y - 1912;
        $ys[1] = 1;
    }
    return $ys;
}

//
function getAllCateName($ColName = "", $WebID = "", $CateID = "")
{
    return array();
}

//更新刪除時是否限制身份
function onlyMine($uid_col = 'uid')
{
    global $xoopsUser, $isAdmin, $MyWebs, $WebID;
    if ($isAdmin) {
        return;
    } elseif (in_array($WebID, $MyWebs)) {
        return;
    }
    $uid = $xoopsUser->uid();
    return "and `{$uid_col}`='$uid'";
}

//取得網站資訊
function getWebInfo($WebID = null)
{
    global $xoopsDB;
    $WebID = intval($WebID);

    $sql    = "select * from " . $xoopsDB->prefix("tad_web") . " where WebID='{$WebID}'";
    $result = $xoopsDB->query($sql) or web_error($sql);

    $Web = $xoopsDB->fetchArray($result);
    return $Web;
}

//取得網站資訊
function getAllWebInfo($get_col = 'WebTitle')
{
    global $xoopsDB;

    $sql    = "select `WebID`, `{$get_col}` from " . $xoopsDB->prefix("tad_web") . " order by WebSort";
    $result = $xoopsDB->query($sql) or web_error($sql);
    $Webs   = '';
    while (list($WebID, $data) = $xoopsDB->fetchRow($result)) {
        $Webs[$WebID] = $data;
    }
    return $Webs;
}

//取得分類名稱
function getLevelName($WebID = "")
{
    global $xoopsDB;
    $sql            = "select `WebTitle` from " . $xoopsDB->prefix("tad_web") . " where WebID='$WebID'";
    $result         = $xoopsDB->query($sql) or web_error($sql);
    list($WebTitle) = $xoopsDB->fetchRow($result);

    return $main;
}

//立即寄出
function send_now($email = "", $title = "", $content = "", $ColName = "", $ColSN = "", $WebID = '')
{
    global $xoopsConfig, $xoopsDB, $xoopsModuleConfig;

    $xoopsMailer                           = &getMailer();
    $xoopsMailer->multimailer->ContentType = "text/html";
    $xoopsMailer->addHeaders("MIME-Version: 1.0");
    $msg = ($xoopsMailer->sendMail($email, $title, $content, $headers)) ? true : false;

    if ($ColName and $msg) {
        $now = date("Y-m-d H:i:s");
        $sql = "insert into " . $xoopsDB->prefix("tad_web_mail_log") . " (`ColName`, `ColSN`, `WebID`, `Mail`, `MailDate`) values('{$ColName}', '{$ColSN}', '{$WebID}', '{$email}', '{$now}')";
        $xoopsDB->query($sql) or web_error($sql);
    }
    return $msg;
}

//製作logo圖
function mklogoPic($WebID = "")
{
    $Class    = getWebInfo($WebID);
    $WebName  = $Class['WebName'];
    $WebTitle = $Class['WebTitle'];

    if (function_exists('mb_strwidth')) {
        $n = mb_strwidth($WebName) / 2;
    } else {
        $n = strlen($WebName) / 3;
    }
    //$width=50*$n+35;
    $size = round(600 / $n, 0);
    if ($size > 70) {
        $size  = 70;
        $x     = $size + 10;
        $size2 = 20;
    } else {
        $x     = round(600 / $n, 0) + 10;
        $size2 = 17;
    }
    $y = $size + 55;

    header('Content-type: image/png');
    $im = @imagecreatetruecolor(600, 140) or die(_MD_TCW_MKPIC_ERROR);
    imagesavealpha($im, true);

    $white = imagecolorallocate($im, 255, 255, 255);

    //$trans_colour = imagecolorallocatealpha($im, 157,211,223, 127);
    $trans_colour = imagecolorallocatealpha($im, 255, 255, 255, 127);
    imagefill($im, 0, 0, $trans_colour);

    $text_color  = imagecolorallocate($im, 0, 0, 0);
    $text_color2 = imagecolorallocatealpha($im, 255, 255, 255, 50);

    $gd = gd_info();
    if ($gd['JIS-mapped Japanese Font Support']) {
        $WebTitle = iconv("UTF-8", "shift_jis", $WebTitle);
        $WebName  = iconv("UTF-8", "shift_jis", $WebName);
    }

    imagettftext($im, $size, 0, 0, $x, $text_color, XOOPS_ROOT_PATH . "/modules/tad_web/class/font.ttf", $WebName);
    imagettftextoutline(
        $im, // image location ( you should use a variable )
        $size, // font size
        0, // angle in °
        0, // x
        $x, // y
        $text_color,
        $white,
        XOOPS_ROOT_PATH . "/modules/tad_web/class/font.ttf",
        $WebName, // pattern
        2// outline width
    );

    imagettftext($im, $size2, 0, 0, $y, $text_color, XOOPS_ROOT_PATH . "/modules/tad_web/class/font.ttf", $WebTitle);
    imagettftextoutline(
        $im, // image location ( you should use a variable )
        $size2, // font size
        0, // angle in °
        0, // x
        $y, // y
        $text_color,
        $white,
        XOOPS_ROOT_PATH . "/modules/tad_web/class/font.ttf",
        $WebTitle, // pattern
        1// outline width
    );

    mk_dir(XOOPS_ROOT_PATH . "/uploads/tad_web/{$WebID}");
    mk_dir(XOOPS_ROOT_PATH . "/uploads/tad_web/{$WebID}/auto_logo");

    imagepng($im, XOOPS_ROOT_PATH . "/uploads/tad_web/{$WebID}/auto_logo/auto_logo.png");
    imagedestroy($im);

}

//製作logo圖
function mkTitlePic($WebID = "", $filename = "", $title = "", $color = "#ABBF6B", $border_color = "#FFFFFF", $size = "30", $font = "font.ttf")
{
    if (function_exists('mb_strlen')) {
        $n = mb_strlen($title, _CHARSET);
    } else {
        $n = strlen($title) / 3;
    }
    if (empty($size)) {
        return;
    }
    $width  = $size * 1.5 * $n;
    $height = $size * 3;

    $x = 2;
    $y = $size * 2;

    list($color_r, $color_g, $color_b)                      = sscanf($color, "#%02x%02x%02x");
    list($border_color_r, $border_color_g, $border_color_b) = sscanf($border_color, "#%02x%02x%02x");

    header('Content-type: image/png');
    $im = @imagecreatetruecolor($width, $height) or die(_MD_TCW_MKPIC_ERROR . "({$title}->{$size} , {$width} x {$height})");
    imagesavealpha($im, true);

    $trans_colour = imagecolorallocatealpha($im, 255, 255, 255, 127);
    imagefill($im, 0, 0, $trans_colour);

    $text_color        = imagecolorallocate($im, $color_r, $color_g, $color_b);
    $text_border_color = imagecolorallocatealpha($im, $border_color_r, $border_color_g, $border_color_b, 50);

    $gd = gd_info();
    if ($gd['JIS-mapped Japanese Font Support']) {
        $title = iconv("UTF-8", "shift_jis", $title);
    }

    imagettftext($im, $size, 0, $x, $y, $text_color, XOOPS_ROOT_PATH . "/modules/tad_web/class/{$font}", $title);
    if ($border_color != "transparent") {
        imagettftextoutline(
            $im, // image location ( you should use a variable )
            $size, // font size
            0, // angle in °
            $x, // x
            $y, // y
            $text_color,
            $text_border_color,
            XOOPS_ROOT_PATH . "/modules/tad_web/class/{$font}",
            $title, // pattern
            2// outline width
        );
    }
    mk_dir(XOOPS_ROOT_PATH . "/uploads/tad_web/{$WebID}/");
    mk_dir(XOOPS_ROOT_PATH . "/uploads/tad_web/{$WebID}/image/");

    imagepng($im, XOOPS_ROOT_PATH . "/uploads/tad_web/{$WebID}/image/{$filename}.png");
    imagedestroy($im);

}

function imagettftextoutline(&$im, $size, $angle, $x, $y, &$col, &$outlinecol, $fontfile, $text, $width)
{
    // For every X pixel to the left and the right
    for ($xc = $x - abs($width); $xc <= $x + abs($width); $xc++) {
        // For every Y pixel to the top and the bottom
        for ($yc = $y - abs($width); $yc <= $y + abs($width); $yc++) {
            // Draw the text in the outline color
            $text1 = imagettftext($im, $size, $angle, $xc, $yc, $outlinecol, $fontfile, $text);
        }
    }
    // Draw the main text
    $text2 = imagettftext($im, $size, $angle, $x, $y, $col, $fontfile, $text);
}

//取得圖片選項
function import_img($path = '', $col_name = "logo", $col_sn = '', $desc = "", $safe_name = false)
{
    global $xoopsDB;
    if (strpos($path, "http") !== false) {
        $path = str_replace(XOOPS_URL, XOOPS_ROOT_PATH, $path);
    }
    if (empty($path)) {
        return;
    }

    if (!is_dir($path) and !is_file($path)) {
        return;
    }

    $db_files = array();

    $sql = "select files_sn,file_name,original_filename from " . $xoopsDB->prefix("tad_web_files_center") . " where col_name='{$col_name}' and col_sn='{$col_sn}'";

    $result          = $xoopsDB->query($sql) or web_error($sql);
    $db_files_amount = 0;
    while (list($files_sn, $file_name, $original_filename) = $xoopsDB->fetchRow($result)) {
        $db_files[$files_sn] = $original_filename;
        $db_files_amount++;
    }
    if (!empty($db_files_amount)) {
        return;
    }

    if (is_dir($path)) {
        if ($dh = opendir($path)) {
            while (($file = readdir($dh)) !== false) {
                if ($file == "." or $file == ".." or $file == "Thumbs.db") {
                    continue;
                }

                $type = filetype($path . "/" . $file);

                if ($type != "dir") {
                    if (!in_array($file, $db_files)) {
                        import_file($path . "/" . $file, $col_name, $col_sn, null, null, $desc, $safe_name);
                    }
                }
            }
            closedir($dh);
        }
    } elseif (is_file($path)) {
        import_file($path, $col_name, $col_sn, null, null, $desc, $safe_name);
    }
}

//匯入圖檔
function import_file($file_name = '', $col_name = "", $col_sn = "", $main_width = "", $thumb_width = "90", $desc = "", $safe_name = false)
{
    global $xoopsDB, $xoopsUser, $xoopsModule, $xoopsConfig;

    if ($col_name == "bg") {
        $TadUpFilesBg = TadUpFilesBg($col_sn);
        if (is_object($TadUpFilesBg)) {
            $TadUpFilesBg->set_col($col_name, $col_sn);
            $TadUpFilesBg->import_one_file($file_name, null, $main_width, $thumb_width, null, $desc, $safe_name);
        } else {
            die('Need TadUpFilesBg Object!');
        }
    } elseif ($col_name == "logo") {
        $TadUpFilesLogo = TadUpFilesLogo($col_sn);
        if (is_object($TadUpFilesLogo)) {
            $TadUpFilesLogo->set_col($col_name, $col_sn);
            $TadUpFilesLogo->import_one_file($file_name, null, $main_width, $thumb_width, null, $desc, $safe_name);
        } else {
            die('Need TadUpFilesLogo Object!');
        }
    } elseif ($col_name == "head") {
        $TadUpFilesHead = TadUpFilesHead($col_sn);
        if (is_object($TadUpFilesHead)) {
            $TadUpFilesHead->set_col($col_name, $col_sn);
            $TadUpFilesHead->import_one_file($file_name, null, $main_width, $thumb_width, null, $desc, $safe_name);
        } else {
            die('Need TadUpFilesHead Object!');
        }
    }
}

function TadUpFilesBg($WebID)
{
    include_once XOOPS_ROOT_PATH . "/modules/tadtools/TadUpFiles.php";
    $TadUpFilesBg = new TadUpFiles("tad_web", "/{$WebID}/bg", null, "", "/thumbs");
    $TadUpFilesBg->set_thumb("100px", "60px", "#000", "center center", "no-repeat", "contain");
    return $TadUpFilesBg;
}

function TadUpFilesLogo($WebID)
{
    include_once XOOPS_ROOT_PATH . "/modules/tadtools/TadUpFiles.php";
    $TadUpFilesLogo = new TadUpFiles("tad_web", "/{$WebID}/logo", null, "", "/thumbs");
    $TadUpFilesLogo->set_thumb("100px", "60px", "#000", "center center", "no-repeat", "contain");
    return $TadUpFilesLogo;
}

function TadUpFilesHead($WebID)
{
    include_once XOOPS_ROOT_PATH . "/modules/tadtools/TadUpFiles.php";
    $TadUpFilesHead = new TadUpFiles("tad_web", "/{$WebID}/head", null, "", "/thumbs");
    $TadUpFilesHead->set_thumb("100px", "60px", "#000", "center center", "no-repeat", "contain");
    return $TadUpFilesHead;
}

//取得tad_web_cate分類選單的選項（單層選單）
function get_tad_web_cate_menu_options($default_CateID = "0")
{
    global $xoopsDB, $xoopsModule;
    $sql = "select `CateID`, `CateName`
    from `" . $xoopsDB->prefix("tad_web_cate") . "` order by `CateSort`";
    $result = $xoopsDB->query($sql)
    or web_error($sql);

    $option = '';
    while (list($CateID, $CateName) = $xoopsDB->fetchRow($result)) {
        $selected = ($CateID == $default_CateID) ? 'selected = "selected"' : '';
        $option .= "<option value='{$CateID}' $selected>{$CateName}</option>";
    }
    return $option;
}

//取得所有分類下的網站
function get_web_cate_arr()
{
    global $xoopsDB, $isAdmin, $MyWebs;

    if (!isset($MyWebs)) {
        $MyWebs = MyWebID();
    }

    $other_web_url_arr = get_web_config('other_web_url');

    $sql    = "select * from `" . $xoopsDB->prefix("tad_web") . "` where WebEnable='1' and WebID > 0 order by WebSort,WebTitle";
    $result = $xoopsDB->query($sql)
    or web_error($sql);
    $data_arr = '';
    while ($all = $xoopsDB->fetchArray($result)) {
        foreach ($all as $k => $v) {
            $$k = $v;
        }
        if (empty($WebID)) {
            continue;
        }
        $all['other_web_url']               = isset($other_web_url_arr[$WebID]) ? $other_web_url_arr[$WebID] : '';
        $all['isMyWeb']                     = ($isAdmin) ? true : in_array($WebID, $MyWebs);
        $data_arr[$CateID][$WebID]          = $all;
        $data_arr[$CateID]['WebID'][$WebID] = $WebID;
    }
    //die(var_export($data_arr));
    return $data_arr;
}

//製作文字圖片
function output_head_file($WebID)
{
    global $xoopsUser;
    if (empty($WebID)) {
        return;
    }
    //先刪掉舊檔
    $filename = XOOPS_ROOT_PATH . "/uploads/tad_web/{$WebID}/header.png";
    if (file_exists($filename)) {
        unlink($filename);
    }

    $width  = 1140;
    $height = 200;
    //die('test2=' . $WebID);
    $all_config = get_web_all_config($WebID);
    //die("<h1>$WebID</h1>" . var_export($all_config));
    foreach ($all_config as $k => $v) {
        $$k = $v;
    }

    $im = @imagecreatetruecolor($width, $height);
    imagecolortransparent($im, imagecolorallocatealpha($im, 0, 0, 0, 127));
    imagealphablending($im, true);
    imagesavealpha($im, true);

    $bg_filename = XOOPS_ROOT_PATH . "/uploads/tad_web/{$WebID}/head/{$web_head}";
    if (file_exists($bg_filename)) {
        list($bg_width, $bg_height) = getimagesize($bg_filename);

        //縮放比例
        $rate = round($bg_width / $width, 2);

        $type = strtolower(substr(strrchr($bg_filename, "."), 1));
        if ($type == 'jpeg') {
            $type = 'jpg';
        }

        switch ($type) {
            case 'bmp':$bg_im = imagecreatefromwbmp($bg_filename);
                break;
            case 'gif':$bg_im = imagecreatefromgif($bg_filename);
                break;
            case 'jpg':$bg_im = imagecreatefromjpeg($bg_filename);
                break;
            case 'png':$bg_im = imagecreatefrompng($bg_filename);
                break;
            default:return "Unsupported picture type!";
        }

        $head_top = abs($head_top);
        $bg_top   = round($head_top * $rate, 0);

        //背景圖
        imagecopyresampled($im, $bg_im, 0, 0, 0, $bg_top, $width, $bg_height, $bg_width, $bg_height);
    }

    $logo_filename = XOOPS_ROOT_PATH . "/uploads/tad_web/{$WebID}/logo/{$web_logo}";
    if (file_exists($logo_filename)) {
        list($logo_width, $logo_height) = getimagesize($logo_filename);

        $type = strtolower(substr(strrchr($logo_filename, "."), 1));
        if ($type == 'jpeg') {
            $type = 'jpg';
        }

        switch ($type) {
            case 'bmp':$logo_im = imagecreatefromwbmp($logo_filename);
                break;
            case 'gif':$logo_im = imagecreatefromgif($logo_filename);
                break;
            case 'jpg':$logo_im = imagecreatefromjpeg($logo_filename);
                break;
            case 'png':$logo_im = imagecreatefrompng($logo_filename);
                imagecolortransparent($logo_im, imagecolorallocatealpha($logo_im, 0, 0, 0, 127));
                imagealphablending($logo_im, true);
                imagesavealpha($logo_im, true);
                break;
            default:return "Unsupported picture type!";
        }

        //logo圖
        imagecopyresampled($im, $logo_im, $logo_left, $logo_top, 0, 0, $logo_width, $logo_height, $logo_width, $logo_height);
    }

    header('Content-type: image/png');
    imagepng($im, XOOPS_ROOT_PATH . "/uploads/tad_web/{$WebID}/header.png");
    imagedestroy($im);
}

//製作文字圖片
function output_head_file_480($WebID)
{
    global $xoopsUser;
    if (empty($WebID)) {
        return;
    }
    //先刪掉舊檔
    $filename = XOOPS_ROOT_PATH . "/uploads/tad_web/{$WebID}/header_480.png";
    if (file_exists($filename)) {
        unlink($filename);
    }

    $width  = 400;
    $height = 200;
    //die('test2=' . $WebID);
    $all_config = get_web_all_config($WebID);
    //die("<h1>$WebID</h1>" . var_export($all_config));
    foreach ($all_config as $k => $v) {
        $$k = $v;
    }

    $im = @imagecreatetruecolor($width, $height);
    imagecolortransparent($im, imagecolorallocatealpha($im, 0, 0, 0, 127));
    imagealphablending($im, true);
    imagesavealpha($im, true);

    $bg_filename = XOOPS_ROOT_PATH . "/uploads/tad_web/{$WebID}/head/{$web_head}";
    if (file_exists($bg_filename)) {
        list($bg_width, $bg_height) = getimagesize($bg_filename);

        //縮放比例
        $rate          = round($width / $bg_width, 2);
        $new_bg_height = round($bg_height * $rate, 0);
        $bg_top        = round($head_top * $rate, 0);
        $new_bg_width  = $width;

        $type = strtolower(substr(strrchr($bg_filename, "."), 1));
        if ($type == 'jpeg') {
            $type = 'jpg';
        }

        switch ($type) {
            case 'bmp':$bg_im = imagecreatefromwbmp($bg_filename);
                break;
            case 'gif':$bg_im = imagecreatefromgif($bg_filename);
                break;
            case 'jpg':$bg_im = imagecreatefromjpeg($bg_filename);
                break;
            case 'png':$bg_im = imagecreatefrompng($bg_filename);
                break;
            default:return "Unsupported picture type!";
        }

        // $head_top = abs($head_top);
        // $bg_top   = round($head_top * $rate, 0);

        //背景圖
        // die("$rate, $bg_top, $bg_width, $bg_height, $bg_width, $bg_height");
        imagecopyresampled($im, $bg_im, 0, 0, 0, 0, $new_bg_width, $new_bg_height, $bg_width, $bg_height);
    }

    $logo_filename = XOOPS_ROOT_PATH . "/uploads/tad_web/{$WebID}/logo/{$web_logo}";
    if (file_exists($logo_filename)) {
        list($logo_width, $logo_height) = getimagesize($logo_filename);

        $new_logo_height = round($logo_height * (380 / $logo_width), 0);
        $new_logo_width  = 380;

        $type = strtolower(substr(strrchr($logo_filename, "."), 1));
        if ($type == 'jpeg') {
            $type = 'jpg';
        }

        switch ($type) {
            case 'bmp':$logo_im = imagecreatefromwbmp($logo_filename);
                break;
            case 'gif':$logo_im = imagecreatefromgif($logo_filename);
                break;
            case 'jpg':$logo_im = imagecreatefromjpeg($logo_filename);
                break;
            case 'png':$logo_im = imagecreatefrompng($logo_filename);
                imagecolortransparent($logo_im, imagecolorallocatealpha($logo_im, 0, 0, 0, 127));
                imagealphablending($logo_im, true);
                imagesavealpha($logo_im, true);
                break;
            default:return "Unsupported picture type!";
        }

        $logo_left = ($width - $new_logo_width) / 2;
        $logo_top  = ($height - $new_logo_height) / 2;

        //logo圖
        imagecopyresampled($im, $logo_im, $logo_left, $logo_top, 0, 0, $new_logo_width, $new_logo_height, $logo_width, $logo_height);
    }

    header('Content-type: image/png');
    imagepng($im, XOOPS_ROOT_PATH . "/uploads/tad_web/{$WebID}/header_480.png");
    imagedestroy($im);
}

function delete_tad_web_directory($dirname)
{
    if (is_dir($dirname)) {
        $dir_handle = opendir($dirname);
    }

    if (!$dir_handle) {
        return false;
    }

    while ($file = readdir($dir_handle)) {
        if ($file != "." && $file != "..") {
            if (!is_dir($dirname . "/" . $file)) {
                unlink($dirname . "/" . $file);
            } else {
                delete_tad_web_directory($dirname . '/' . $file);
            }

        }
    }
    closedir($dir_handle);
    rmdir($dirname);
    return true;
}

//寫入已使用空間
function check_quota($WebID = "")
{
    global $xoopsModuleConfig, $xoopsDB;
    $data = "";
    $dir  = XOOPS_ROOT_PATH . "/uploads/tad_web/";

    $dir_size = get_dir_size("{$dir}{$WebID}/");
    $size     = size2mb($dir_size);
    save_web_config("used_size", $size, $WebID);

    $sql = "update `" . $xoopsDB->prefix("tad_web") . "` set `used_size`='{$dir_size}' where `WebID`='{$WebID}'";
    $xoopsDB->queryF($sql) or web_error($sql);

}

//檢查已使用空間
function get_quota($WebID = "")
{
    global $xoopsModuleConfig;
    $size               = get_web_config("used_size", $WebID);
    $user_default_quota = empty($xoopsModuleConfig['user_space_quota']) ? 1 : intval($xoopsModuleConfig['user_space_quota']);
    $space_quota        = get_web_config("space_quota", $WebID);
    $user_space_quota   = (empty($space_quota) or $space_quota == 'default') ? $user_default_quota : intval($space_quota);

    if ($size >= $user_space_quota) {
        redirect_header("index.php?WebID={$WebID}", 3, sprintf(_MD_TCW_NO_SPACE, $size, $user_space_quota));
        exit;
    }
}

function size2mb($size)
{
    $mb = round($size / (1024 * 1024), 0);
    return $mb;
}

function roundsize($size)
{
    $i   = 0;
    $iec = array('B', 'KB', 'MB', 'GB', 'TB', 'EB', 'ZB', 'YB');
    while (($size / 1024) > 1) {
        $size = $size / 1024;
        $i++;}
    return (round($size, 1) . " " . $iec[$i]);
}

function get_dir_size($dir_name)
{
    $dir_size = 0;
    if (is_dir($dir_name)) {
        if ($dh = opendir($dir_name)) {
            while (($file = readdir($dh)) !== false) {
                if ($file != "." && $file != "..") {
                    if (is_file($dir_name . "/" . $file)) {
                        $dir_size += filesize($dir_name . "/" . $file);
                    }
                    /* check for any new directory inside this directory */
                    if (is_dir($dir_name . "/" . $file)) {
                        $dir_size += get_dir_size($dir_name . "/" . $file);
                    }
                }
            }
        }
    }
    closedir($dh);
    return $dir_size;
}

//取得額外設定的儲存值
function get_plugin_setup_values($WebID = "", $plugin = "")
{
    global $xoopsDB, $xoopsConfig;
    $myts       = MyTextSanitizer::getInstance();
    $setup_file = XOOPS_ROOT_PATH . "/modules/tad_web/plugins/{$plugin}/setup.php";
    if (file_exists($setup_file)) {
        require_once XOOPS_ROOT_PATH . "/modules/tad_web/plugins/{$plugin}/langs/{$xoopsConfig['language']}.php";
        require $setup_file;
    }

    $sql = "select `name`, `type`, `value` from " . $xoopsDB->prefix("tad_web_plugins_setup") . " where `WebID`='{$WebID}' and plugin='{$plugin}'";
    // die($sql);
    $result          = $xoopsDB->query($sql) or web_error($sql);
    $setup_db_values = array();
    //`theme_id`, `name`, `type`, `value`
    while (list($name, $type, $value) = $xoopsDB->fetchRow($result)) {

        $setup_db_values[$name] = $value;
    }

    // die(var_export($plugin_setup));

    foreach ($plugin_setup as $k => $setup) {
        $name          = $setup['name'];
        $value         = isset($setup_db_values[$name]) ? $myts->htmlSpecialChars($setup_db_values[$name]) : $setup['default'];
        $values[$name] = $value;
    }

    // if (isset($_GET['test'])) {
    //     die(var_export($values));
    // }

    return $values;
}

//刪除tad_web某筆資料資料確認
function delete_tad_web_chk($WebID = "")
{
    global $xoopsDB, $xoopsTpl;
    if (empty($WebID)) {
        return;
    }

    $pluginsVal = get_db_plugins($WebID);
    $i          = 0;
    foreach ($pluginsVal as $dirname => $plugin) {
        $plugins[$i]['dirname']     = $dirname;
        $plugins[$i]['PluginTitle'] = $plugin['PluginTitle'];

        include_once XOOPS_ROOT_PATH . "/modules/tad_web/plugins/{$dirname}/class.php";
        $plugin_name          = "tad_web_{$dirname}";
        $$plugin_name         = new $plugin_name($WebID);
        $plugins[$i]['total'] = $$plugin_name->get_total();

        $i++;
    }

    $xoopsTpl->assign('plugins', $plugins);
    $xoopsTpl->assign('WebID', $WebID);

}

//刪除tad_web某筆資料資料
function delete_tad_web($WebID = "")
{
    global $xoopsDB, $TadUpFiles;
    if (empty($WebID)) {
        return;
    }

    $pluginsVal = get_db_plugins($WebID);
    $i          = 0;
    foreach ($pluginsVal as $dirname => $plugin) {
        $plugins[$i]['dirname']     = $dirname;
        $plugins[$i]['PluginTitle'] = $plugin['PluginTitle'];

        include_once XOOPS_ROOT_PATH . "/modules/tad_web/plugins/{$dirname}/class.php";
        $plugin_name  = "tad_web_{$dirname}";
        $$plugin_name = new $plugin_name($WebID);
        $$plugin_name->delete_all();

        $i++;
    }
    $sql = "delete from " . $xoopsDB->prefix("tad_web_tags") . " where WebID='{$WebID}'";
    $xoopsDB->queryF($sql) or web_error($sql);

    $sql = "delete from " . $xoopsDB->prefix("tad_web_power") . " where WebID='{$WebID}'";
    $xoopsDB->queryF($sql) or web_error($sql);

    $sql = "delete from " . $xoopsDB->prefix("tad_web_blocks") . " where WebID='{$WebID}'";
    $xoopsDB->queryF($sql) or web_error($sql);

    $sql = "delete from " . $xoopsDB->prefix("tad_web_plugins_setup") . " where WebID='{$WebID}'";
    $xoopsDB->queryF($sql) or web_error($sql);

    $sql = "delete from " . $xoopsDB->prefix("tad_web_plugins") . " where WebID='{$WebID}'";
    $xoopsDB->queryF($sql) or web_error($sql);

    $sql = "delete from " . $xoopsDB->prefix("tad_web_roles") . " where WebID='{$WebID}'";
    $xoopsDB->queryF($sql) or web_error($sql);

    $sql = "delete from " . $xoopsDB->prefix("tad_web_config") . " where WebID='{$WebID}'";
    $xoopsDB->queryF($sql) or web_error($sql);

    $sql = "delete from " . $xoopsDB->prefix("tad_web_cate") . " where WebID='{$WebID}'";
    $xoopsDB->queryF($sql) or web_error($sql);

    $sql = "delete from " . $xoopsDB->prefix("tad_web") . " where WebID='{$WebID}'";
    $xoopsDB->queryF($sql) or web_error($sql);

    $TadUpFiles->set_col("WebOwner", $WebID);
    $TadUpFiles->del_files();

    //刪除所有附檔
    if (!delete_tad_web_directory(XOOPS_ROOT_PATH . "/uploads/tad_web/{$WebID}")) {
        web_error('無法刪除資料夾' . XOOPS_ROOT_PATH . "/uploads/tad_web/{$WebID}");
    }
}

//更新最後被拜訪日期
function update_last_accessed($WebID = "")
{
    global $xoopsDB;
    $last_accessed = date("Y-m-d H:i:s");
    $sql           = "update `" . $xoopsDB->prefix("tad_web") . "` set `last_accessed`='{$last_accessed}' where `WebID`='{$WebID}'";
    $xoopsDB->queryF($sql) or web_error($sql);
}

/**  * 獲取文章內容(當前分頁)
 * @param string $content 文章內容
 * @param integer $page 頁數
 * @return array
 */

function get_article_content($content, $page = 1)
{

    $page = $page ? intval($page) :

    $article = array('info' => array(), 'pages' => 1);

    if (!empty($content)) {

        $pattern  = "/<div style=\"page-break-after: always;?\">\s*<span style=\"display: none;?\">&nbsp;<\/span>\s*<\/div>/";
        $contents = preg_split($pattern, $content);

        $article['pages'] = count($contents);

        ($page > $article['pages']) && $page = $article['pages'];

        $article['info'] = $contents[$page - 1];

    }

    return $article;

}

function fb_comments($use = true)
{
    if (!$use) {
        return;
    }
    $url = "http://{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}";

    $main = "
    <div id=\"fb-root\"></div>
    <script>(function(d, s, id) {
      var js, fjs = d.getElementsByTagName(s)[0];
      if (d.getElementById(id)) return;
      js = d.createElement(s); js.id = id;
      js.src = \"//connect.facebook.net/zh_TW/sdk.js#xfbml=1&version=v2.5&appId=536429359858958\";
      fjs.parentNode.insertBefore(js, fjs);
    }(document, 'script', 'facebook-jssdk'));</script>
    <div class=\"fb-comments\" data-href=\"{$url}\" data-width=\"100%\" data-numposts=\"10\"></div>
    ";

    return $main;

}

//以流水號取得某筆tad_web_notice資料
function get_tad_web_notice($NoticeID = '')
{
    global $xoopsDB;

    if (empty($NoticeID)) {
        return;
    }

    $sql = "select * from `" . $xoopsDB->prefix("tad_web_notice") . "`
    where `NoticeID` = '{$NoticeID}'";
    $result = $xoopsDB->query($sql)
    or web_error($sql);
    $data = $xoopsDB->fetchArray($result);
    return $data;
}

//取得系統預設的OpenID登入方式
function get_sys_openid()
{
    $auth_method         = array();
    $modhandler          = &xoops_gethandler('module');
    $config_handler      = &xoops_gethandler('config');
    $TadLoginXoopsModule = &$modhandler->getByDirname("tad_login");
    if ($TadLoginXoopsModule) {
        include_once XOOPS_ROOT_PATH . "/modules/tad_login/function.php";
        include_once XOOPS_ROOT_PATH . "/modules/tad_login/language/{$xoopsConfig['language']}/county.php";
        if (function_exists('facebook_login')) {
            $tad_login['facebook'] = facebook_login('return');
        }

        if (function_exists('google_login')) {
            $tad_login['google'] = google_login('return');
        }

        $config_handler = &xoops_gethandler('config');
        $modConfig      = &$config_handler->getConfigsByCat(0, $TadLoginXoopsModule->getVar('mid'));

        $auth_method = $modConfig['auth_method'];
    }
    return $auth_method;
}

//設定小幫手
function set_assistant($CateID = "", $MemID = "")
{
    global $xoopsDB;
    if (empty($CateID) or empty($MemID)) {
        return;
    }

    $sql = "delete from `" . $xoopsDB->prefix('tad_web_cate_assistant') . "` where `CateID`='{$CateID}' and `AssistantType`='MemID'";
    $xoopsDB->queryF($sql) or web_error($sql);

    $sql = "insert into `" . $xoopsDB->prefix('tad_web_cate_assistant') . "` (`CateID`, `AssistantType`, `AssistantID`) values('{$CateID}', 'MemID', '{$MemID}')";
    $xoopsDB->queryF($sql) or web_error($sql);
}

//取得小幫手
function get_assistant($CateID = "")
{
    global $xoopsDB;
    if (empty($CateID)) {
        return;
    }

    $sql    = "select `AssistantType`, `AssistantID` from `" . $xoopsDB->prefix('tad_web_cate_assistant') . "` where `CateID`='{$CateID}'";
    $result = $xoopsDB->queryF($sql) or web_error($sql);
    $all    = $xoopsDB->fetchArray($result);
    // die(var_export($all));
    if ($all['AssistantType'] == "MemID") {
        $mem = get_tad_web_mems($all['AssistantID']);
    } elseif ($all['AssistantType'] == "ParentID") {
        $mem = get_tad_web_parent($all['AssistantID']);
    }
    return $mem;
}

//儲存小幫手的編輯紀錄
function save_assistant_post($CateID = '', $ColName = '', $ColSN = '')
{
    global $xoopsDB, $xoopsUser;

    if (empty($ColName) or empty($ColSN)) {
        return;
    }

    $sql = "delete from `" . $xoopsDB->prefix("tad_web_assistant_post") . "` where `plugin`='{$ColName}' and `ColName`='{$ColName}' and `ColSN`='{$ColSN}'";
    $xoopsDB->queryF($sql) or web_error($sql);

    $sql = "insert into `" . $xoopsDB->prefix("tad_web_assistant_post") . "` (
              `plugin`,
              `ColName`,
              `ColSN`,
              `CateID`,
              `AssistantType`,
              `AssistantID`
            ) values(
              '{$ColName}',
              '{$ColName}',
              '{$ColSN}',
              '{$CateID}',
              '{$_SESSION['AssistantType'][$CateID]}',
              '{$_SESSION['AssistantID'][$CateID]}'
            )";
    $xoopsDB->query($sql) or web_error($sql);

}

//檢查某個內容是否是小幫手發的
function is_assistant($CateID = '', $ColName = '', $ColSN = '')
{
    global $xoopsDB;

    $sql    = "select `AssistantType`,`AssistantID` from `" . $xoopsDB->prefix('tad_web_assistant_post') . "` where `ColName`='{$ColName}' and `ColSN`='{$ColSN}' and `CateID`='{$CateID}'";
    $result = $xoopsDB->queryF($sql) or web_error($sql);
    $all    = $xoopsDB->fetchArray($result);
    if ($all['AssistantType'] == "MemID") {
        $mem = get_tad_web_mems($all['AssistantID']);
    } elseif ($all['AssistantType'] == "ParentID") {
        $mem = get_tad_web_parent($all['AssistantID']);
    }
    return $mem;
}
