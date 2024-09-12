<?php
use XoopsModules\Tadtools\TadUpFiles;
use XoopsModules\Tadtools\Utility;

define('_EZCLASS', 'https://class.tn.edu.tw');
$is_ezclass = (XOOPS_URL == _EZCLASS) ? true : false;
define('_IS_EZCLASS', $is_ezclass);

require_once XOOPS_ROOT_PATH . '/modules/tadtools/TadUpFiles.php';
$TadUpFiles = new TadUpFiles('tad_web');

$subdir = isset($WebID) ? "/{$WebID}" : '';
$TadUpFiles->set_dir('subdir', $subdir);

//引入TadTools的函式庫
require_once __DIR__ . '/function_block.php';

require_once XOOPS_ROOT_PATH . '/modules/tad_web/class/WebCate.php';

//判斷是否對該模組有管理權限
$isAdmin = false;
$LoginMemID = $LoginMemName = $LoginMemNickName = $LoginWebID = $LoginParentID = $LoginParentName = $LoginParentMemID = '';
$MyWebs = [];
$isMyWeb = false;
if ($xoopsUser) {
    $moduleHandler = xoops_getHandler('module');
    $xoopsModule = $moduleHandler->getByDirname('tad_web');
    $module_id = $xoopsModule->getVar('mid');
    $isAdmin = $xoopsUser->isAdmin($module_id);
    if (!isset($_SESSION['tad_web_adm'])) {
        $_SESSION['tad_web_adm'] = $xoopsUser->isAdmin($module_id);
    }
    //我的班級ID（陣列）
    $MyWebs = MyWebID('all');

    //目前瀏覽的是否是我的班級？
    $isMyWeb = ($isAdmin) ? true : in_array($WebID, $MyWebs);
} else {
    $LoginMemID = isset($_SESSION['LoginMemID']) ? $_SESSION['LoginMemID'] : null;
    $LoginMemName = isset($_SESSION['LoginMemName']) ? $_SESSION['LoginMemName'] : null;
    $LoginMemNickName = isset($_SESSION['LoginMemNickName']) ? $_SESSION['LoginMemNickName'] : null;
    $LoginWebID = isset($_SESSION['LoginWebID']) ? $_SESSION['LoginWebID'] : null;

    $LoginParentID = isset($_SESSION['LoginParentID']) ? $_SESSION['LoginParentID'] : null;
    $LoginParentName = isset($_SESSION['LoginParentName']) ? $_SESSION['LoginParentName'] : null;
    $LoginParentMemID = isset($_SESSION['LoginParentMemID']) ? $_SESSION['LoginParentMemID'] : null;
}

//區塊位置
xoops_loadLanguage('main', 'tad_web');
$BlockPositionTitle = ['block1' => _MD_TCW_TOP_CENTER_BLOCK, 'block2' => _MD_TCW_TOP_LEFT_BLOCK, 'block3' => _MD_TCW_TOP_LEFT_BLOCK, 'block4' => _MD_TCW_BOTTOM_CENTER_BLOCK, 'block5' => _MD_TCW_BOTTOM_LEFT_BLOCK, 'block6' => _MD_TCW_BOTTOM_RIGHT_BLOCK, 'side' => _MD_TCW_SIDE_BLOCK, 'uninstall' => _MD_TCW_UNINSTALL_BLOCK];

/********************* 自訂函數 *********************/

// 清除多人網頁的內部區塊的值
function clear_block_cache($WebID)
{
    $web_blocks_file = XOOPS_VAR_PATH . "/tad_web/$WebID/web_blocks.json";
    unlink($web_blocks_file);
    clear_power_cache($WebID);
}

// 清除額外設定的儲存值
function clear_plugin_setup($WebID, $plugin)
{
    $plugin_setup_values_file = XOOPS_VAR_PATH . "/tad_web/$WebID/$plugin/setup_values.json";
    unlink($plugin_setup_values_file);
}

// 清除小幫手設定的儲存值
function clear_assistant_cache($WebID, $plugin)
{
    $assistant_cache_file = XOOPS_VAR_PATH . "/tad_web/$WebID/$plugin/assistants.json";
    unlink($assistant_cache_file);
}

// 清除所有網站設定值
function clear_tad_web_config($WebID)
{
    $tad_web_config_file = XOOPS_VAR_PATH . "/tad_web/$WebID/tad_web_config.json";
    unlink($tad_web_config_file);
}

// 清除通知
function clear_tad_web_notice()
{
    $tad_web_notice_file = XOOPS_VAR_PATH . "/tad_web/tad_web_notice.json";
    unlink($tad_web_notice_file);
}

// 清除我的網站資料
function clear_my_webs_data($WebID)
{
    global $xoopsUser;
    if ($xoopsUser) {
        $uid = $xoopsUser->uid();
        $my_webs_data_file = XOOPS_VAR_PATH . "/tad_web/my_webs_data/$uid.json";
        unlink($my_webs_data_file);
        unset($_SESSION['tad_web'][$WebID]);
    }
}

// 清除權限設定值
function clear_power_cache($WebID)
{
    $power_cache_file = XOOPS_VAR_PATH . "/tad_web/$WebID/web_power.json";
    unlink($power_cache_file);
}

//取得已安裝的區塊
function get_blocks($WebID)
{
    global $xoopsDB;
    $sql = 'select * from ' . $xoopsDB->prefix('tad_web_blocks') . " where `WebID`='{$WebID}' order by `BlockSort`";
    $result = $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);
    $Blocks = [];
    while (false !== ($all = $xoopsDB->fetchArray($result))) {
        $Blocks[] = $all;
    }

    return $Blocks;
}

//取得區塊
function get_block($BlockID)
{
    global $xoopsDB;
    $sql = 'select * from ' . $xoopsDB->prefix('tad_web_blocks') . " where `BlockID`='{$BlockID}'";
    $result = $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);
    $block = $xoopsDB->fetchArray($result);
    return $block;
}

//取得所有區塊設定
function get_dir_blocks($mode = '')
{
    global $xoopsConfig;
    $dir_blocks_file = XOOPS_ROOT_PATH . '/uploads/tad_web/dir_blocks.json';
    if (file_exists($dir_blocks_file) and '' == $mode) {
        $Config = get_json_file($dir_blocks_file);
    } else {
        unlink($dir_blocks_file);
        $Config = [];
        $plugins = get_dir_plugins();

        foreach ($plugins as $plugin) {
            $config_blocks_file = XOOPS_ROOT_PATH . "/modules/tad_web/plugins/{$plugin}/config_blocks.php";
            if (file_exists($config_blocks_file)) {
                require_once XOOPS_ROOT_PATH . "/modules/tad_web/plugins/{$plugin}/langs/{$xoopsConfig['language']}.php";
                include $config_blocks_file;
                $Config[$plugin] = $blocksArr;
                $blocksArr = [];
            }
        }

        put_json_file($dir_blocks_file, $Config);
    }

    return $Config;
}

//寫入 json 設定檔
function put_json_file($file, $array)
{
    unlink($file);
    if (PHP_VERSION_ID >= 50400) {
        $json = json_encode($array, JSON_UNESCAPED_UNICODE);
    } else {
        array_walk_recursive($array, function (&$value, $key) {
            if (is_string($value)) {
                $value = urlencode($value);
            }
        });
        $json = urldecode(json_encode($array));
    }

    file_put_contents($file, $json);
}

//讀出 json 設定檔陣列
function get_json_file($file)
{
    $array = json_decode(file_get_contents($file), true);

    return $array;
}

//取得各外掛及系統所有區塊(onUpdate.php)
function get_all_blocks($value = 'title')
{

    $myts = \MyTextSanitizer::getInstance();
    $block_option = [];
    //來自plugin的區塊
    $allBlockConfig = get_dir_blocks();

    foreach ($allBlockConfig as $plugin => $blockConfig) {
        foreach ($blockConfig as $func => $block) {
            if ('plugin' === $value) {
                $block_option[$func] = $plugin;
            } elseif (isset($block[$value]) and 'config' === $value) {
                $block_option[$func] = $block['config'];
            } elseif (isset($block[$value]) and 'tpl' === $value) {
                $block_option[$func] = $block['tpl'];
            } elseif (isset($block[$value]) and 'position' === $value) {
                $block_option[$func] = $block['position'];
            } else {
                $name = $myts->htmlSpecialChars($block['name']);
                $block_option[$func] = $name;
            }
        }
    }

    return $block_option;
}

function get_position_blocks($WebID, $BlockPosition, $plugin = '')
{
    global $xoopsDB, $plugin_menu_var;
    if ('uninstall' === $BlockPosition) {
        //找出這個網站已經安裝的分享區塊
        if (empty($plugin)) {
            $share_blocks_id = get_share_blocks($WebID);
            $all_share_blocks = is_array($share_blocks_id) ? implode("','", $share_blocks_id) : '';
            $andShareBlocks = empty($all_share_blocks) ? '' : "and a.`BlockID` not in('{$all_share_blocks}')";
            // $andBlockPosition = "(a.`WebID`='{$WebID}' and (a.`BlockPosition`='uninstall' or a.`BlockPosition`='') and a.`plugin`!='share' ) or (a.`plugin`='share' and a.`WebID`!='{$WebID}' {$andShareBlocks})";
            $andBlockPosition = "(a.`WebID`='{$WebID}' and (a.`BlockPosition`='uninstall' or a.`BlockPosition`='')) or (a.`plugin`='share' and a.`WebID`!='{$WebID}' {$andShareBlocks})";
        } else {
            $andBlockPosition = "(a.`WebID`='{$WebID}' and (a.`BlockPosition`='uninstall' or a.`BlockPosition`='') and a.`plugin`='{$plugin}' )";
        }
    } else {
        // $andBlockPosition = empty($plugin) ? "a.`WebID`='{$WebID}' and a.`BlockPosition`='{$BlockPosition}' and a.`plugin`!='share'" : "a.`WebID`='{$WebID}' and a.`BlockPosition`='{$BlockPosition}' and a.`plugin`='{$plugin}'";
        $andBlockPosition = empty($plugin) ? "a.`WebID`='{$WebID}' and a.`BlockPosition`='{$BlockPosition}'" : "a.`WebID`='{$WebID}' and a.`BlockPosition`='{$BlockPosition}' and a.`plugin`='{$plugin}'";
    }

    $sql = 'select a.*, b.`PluginTitle` from ' . $xoopsDB->prefix('tad_web_blocks') . " as a
    left join " . $xoopsDB->prefix('tad_web_plugins') . " as b on a.`plugin` = b.`PluginDirname` and a.`WebID` = b.`WebID`
    where  $andBlockPosition order by a.`BlockSort`";
    // if ($_GET['test'] == 1) {
    //     die($sql);
    // }
    $result = $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);
    $Blocks = [];
    $i = 0;
    while (false !== ($all = $xoopsDB->fetchArray($result))) {
        $plugin = $all['plugin'];
        if ('custom' !== $plugin and 'share' !== $plugin and 'system' !== $plugin) {
            if (empty($plugin_menu_var[$plugin])) {
                continue;
            }
        }

        $Blocks[$i] = $all;
        $Blocks[$i]['BlockShare'] = !empty($all['ShareFrom']) ? 1 : 0;
        $BlockEnable = 1 == $all['BlockEnable'] ? '1' : '0';
        $config = json_decode($all['BlockConfig'], true);
        $Blocks[$i]['config'] = $config;

        $Blocks[$i]['icon'] = "<img src=\"images/show{$BlockEnable}.gif\" id=\"{$all['BlockID']}_icon\" alt=\"{$all['BlockTitle']}\" title=\"{$BlockEnable}\" style=\"cursor: pointer;\" onClick=\"enableBlock('{$all['BlockID']}')\" >";
        $i++;
    }

    return $Blocks;
}

//取得某網站有使用的分享區塊ID
function get_share_blocks($WebID)
{
    global $xoopsDB;
    $share_blocks = [];
    $sql = 'select ShareFrom from ' . $xoopsDB->prefix('tad_web_blocks') . " where `WebID`='{$WebID}' and `plugin`='custom' and `ShareFrom` > 0";
    $result = $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);
    while (list($ShareFromID) = $xoopsDB->fetchRow($result)) {
        $share_blocks[] = $ShareFromID;
    }

    if (empty($share_blocks)) {
        return;
    }

    return $share_blocks;
}

//取得所有顯示的區塊
function get_web_blocks($WebID, $plugin = '', $BlockEnable = 1)
{
    global $xoopsDB;
    $andBlockPlugin = empty($plugin) ? '' : "and plugin='{$plugin}'";
    $andBlockEnable = null === $BlockEnable ? '' : "and BlockEnable='{$BlockEnable}'";
    $sql = 'select * from ' . $xoopsDB->prefix('tad_web_blocks') . " where `WebID`='{$WebID}' {$andBlockEnable} {$andBlockPlugin}";
    $result = $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);

    $i = 0;
    $Blocks = [];
    while (false !== ($all = $xoopsDB->fetchArray($result))) {
        $Blocks[$i] = $all;
        $i++;
    }

    return $Blocks;
}

//自動取得tad_web_blocks的最新排序
function max_blocks_sort($WebID, $BlockPosition)
{
    global $xoopsDB;
    $sql = 'select max(`BlockSort`) from ' . $xoopsDB->prefix('tad_web_blocks') . " where WebID='$WebID' and BlockPosition='{$BlockPosition}'";
    $result = $xoopsDB->query($sql) or Utility::web_error($sql, __FILE__, __LINE__);
    list($sort) = $xoopsDB->fetchRow($result);

    return ++$sort;
}

//取得角色陣列
function get_web_roles($defWebID = '', $defRole = '')
{
    global $xoopsDB;

    $andWebID = empty($defWebID) ? '' : "and `WebID`='$defWebID'";
    $andRole = empty($defRole) ? '' : "and `role`='$defRole'";
    $sql = 'select uid from ' . $xoopsDB->prefix('tad_web_roles') . " where 1 $andRole $andWebID ";
    $result = $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);
    $users = [];
    $i = 0;
    while (list($uid) = $xoopsDB->fetchRow($result)) {
        $users[$i] = $uid;
        $i++;
    }

    return $users;
}

//取得所有網站設定值
function get_web_all_config($WebID = '')
{
    global $xoopsDB;

    if (empty($WebID)) {
        return;
    }

    $tad_web_config_file = XOOPS_VAR_PATH . "/tad_web/$WebID/tad_web_config.json";

    if (file_exists($tad_web_config_file)) {
        $tad_web_config = json_decode(file_get_contents($tad_web_config_file), true);
    } else {
        if (file_exists(XOOPS_ROOT_PATH . '/themes/for_tad_web_theme/theme_config.php') or file_exists(XOOPS_ROOT_PATH . '/themes/for_tad_web_theme_2/theme_config.php')) {
            if (file_exists(XOOPS_ROOT_PATH . '/themes/for_tad_web_theme/theme_config.php')) {
                require XOOPS_ROOT_PATH . '/themes/for_tad_web_theme/theme_config.php';
            } elseif (file_exists(XOOPS_ROOT_PATH . '/themes/for_tad_web_theme_2/theme_config.php')) {
                require XOOPS_ROOT_PATH . '/themes/for_tad_web_theme_2/theme_config.php';
            }
            Utility::mk_dir(XOOPS_VAR_PATH . "/tad_web");
            Utility::mk_dir(XOOPS_VAR_PATH . "/tad_web/my_webs_data");
            Utility::mk_dir(XOOPS_VAR_PATH . "/tad_web/{$WebID}");
            Utility::mk_dir(XOOPS_ROOT_PATH . "/uploads/tad_web/{$WebID}");
            Utility::mk_dir(XOOPS_ROOT_PATH . "/uploads/tad_web/{$WebID}/bg");
            Utility::mk_dir(XOOPS_ROOT_PATH . "/uploads/tad_web/{$WebID}/head");
            Utility::mk_dir(XOOPS_ROOT_PATH . "/uploads/tad_web/{$WebID}/logo");

            if (!file_exists(XOOPS_ROOT_PATH . "/uploads/tad_web/{$WebID}/logo/{$tad_web_config['web_logo']}")) {
                if (!file_exists(XOOPS_ROOT_PATH . "/uploads/tad_web/{$WebID}/auto_logo/auto_logo.png")) {
                    mklogoPic($WebID);
                }
                copy(XOOPS_ROOT_PATH . "/uploads/tad_web/{$WebID}/auto_logo/auto_logo.png", XOOPS_ROOT_PATH . "/uploads/tad_web/{$WebID}/logo/{$tad_web_config['web_logo']}");

                chmod(XOOPS_ROOT_PATH . "/uploads/tad_web/{$WebID}/logo/{$tad_web_config['web_logo']}", 0777);
            }
        } else {
            Utility::web_error("Need to install 'for_tad_web_theme' or 'for_tad_web_theme_2'theme.");
        }

        $sql = 'select `ConfigName`,`ConfigValue` from ' . $xoopsDB->prefix('tad_web_config') . " where `WebID`='$WebID'";
        $result = $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);
        while (list($ConfigName, $ConfigValue) = $xoopsDB->fetchRow($result)) {
            $tad_web_config[$ConfigName] = $ConfigValue;
        }

        file_put_contents($tad_web_config_file, json_encode($tad_web_config, 256));
    }

    return $tad_web_config;
}

//儲存網站設定
function save_web_config($ConfigName, $ConfigValue, $WebID)
{
    global $xoopsDB;
    // if (!empty($WebID) and !$isMyWeb) { //這樣後台會有問題
    //     return;
    // }

    if (is_array($ConfigValue)) {
        $ConfigValue = implode(';', $ConfigValue);
    }

    $ConfigValue = $xoopsDB->escape($ConfigValue);

    $sql = 'replace into ' . $xoopsDB->prefix('tad_web_config') . "
    (`ConfigName`, `ConfigValue`, `WebID`)
    values('{$ConfigName}' , '{$ConfigValue}', '{$WebID}')";

    $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);
    $file = XOOPS_ROOT_PATH . "/uploads/tad_web/{$WebID}/web_config.php";
    unlink($file);
    clear_tad_web_config($WebID);
}

//取得資料庫中的外掛資料
function get_db_plugins($WebID = '', $only_enable = false)
{
    global $xoopsDB;
    $andEnable = ($only_enable) ? "and PluginEnable='1'" : '';

    //取得tad_web_plugins資料表中該網站所有設定值
    $sql = 'select * from ' . $xoopsDB->prefix('tad_web_plugins') . " where WebID='{$WebID}' {$andEnable} order by PluginSort";
    $result = $xoopsDB->query($sql) or Utility::web_error($sql, __FILE__, __LINE__);

    while (false !== ($all = $xoopsDB->fetchArray($result))) {
        $dirname = $all['PluginDirname'];
        $plugins[$dirname] = $all;
    }

    return $plugins;
}

function get_db_plugin($WebID = '', $dirname = '')
{
    global $xoopsDB;

    $sql = 'select * from ' . $xoopsDB->prefix('tad_web_plugins') . " where WebID='{$WebID}' and PluginDirname='{$dirname}'";
    $result = $xoopsDB->query($sql) or Utility::web_error($sql, __FILE__, __LINE__);

    $all = $xoopsDB->fetchArray($result);

    return $all;
}

//取得硬碟中外掛模組的名稱陣列
function get_dir_plugins($mode = '')
{
    $dir_plugins_file = XOOPS_ROOT_PATH . '/uploads/tad_web/dir_plugins.json';
    if (file_exists($dir_plugins_file) and '' == $mode) {
        $plugins = get_json_file($dir_plugins_file);
    } else {
        $dir = XOOPS_ROOT_PATH . '/modules/tad_web/plugins/';
        if (is_dir($dir)) {
            if ($dh = opendir($dir)) {
                while (false !== ($file = readdir($dh))) {
                    if ('dir' === filetype($dir . $file)) {
                        if ('.' === mb_substr($file, 0, 1)) {
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

        put_json_file($dir_plugins_file, $plugins);
    }

    return $plugins;
}

//取得所有外掛
function get_plugins($WebID = '', $mode = 'show', $only_enable = false)
{
    global $TadUpFiles, $xoopsDB;

    $pluginsVal = get_db_plugins($WebID, $only_enable);

    $dir = XOOPS_ROOT_PATH . '/modules/tad_web/plugins/';
    $dir_plugins = get_dir_plugins();
    foreach ($dir_plugins as $file) {
        if ($only_enable and empty($pluginsVal)) {
            continue;
        }

        $pluginVal = get_db_plugin($WebID, $file);
        include $dir . $file . '/config.php';

        //發現新外掛時，預設啟用之
        if (empty($pluginVal)) {
            $sort = plugins_max_sort($WebID, $file);
            $sql = 'replace into ' . $xoopsDB->prefix('tad_web_plugins') . " (`PluginDirname`, `PluginTitle`, `PluginSort`, `PluginEnable`, `WebID`) values('{$file}', '{$pluginConfig['name']}', '{$sort}', '1', '{$WebID}')";
            $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);
        }

        $pluginConfigs[$file] = $pluginConfig;
    }

    $new_pluginsVal = get_db_plugins($WebID, $only_enable);
    $i = 0;
    foreach ($new_pluginsVal as $dirname => $plugin) {
        $plugins[$i]['dirname'] = $dirname;
        $plugins[$i]['config'] = $pluginConfigs[$dirname];
        $plugins[$i]['db'] = $new_pluginsVal[$dirname];

        if ('edit' === $mode) {
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

    $sql = 'select max(PluginSort) from ' . $xoopsDB->prefix('tad_web_plugins') . " where WebID='{$WebID}' and PluginDirname='{$dirname}'";
    $result = $xoopsDB->query($sql) or Utility::web_error($sql, __FILE__, __LINE__);

    list($sort) = $xoopsDB->fetchRow($result);
    $sort++;

    return $sort;
}

//共同樣板部份
function common_template($WebID, $web_all_config = '')
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
            $sql = 'select max(`CateID`) from ' . $xoopsDB->prefix('tad_web_cate') . " where `ColName` = 'aboutus' AND `CateEnable` = '1' AND `WebID` = '{$WebID}'";
            $result = $xoopsDB->query($sql) or Utility::web_error($sql, __FILE__, __LINE__);
            list($default_class) = $xoopsDB->fetchRow($result);
            save_web_config('default_class', $default_class, $WebID);
            $web_all_config['default_class'] = $default_class;
        }
        // die(var_export($web_all_config));
        foreach ($web_all_config as $ConfigName => $ConfigValue) {
            if ('login_config' === $ConfigName) {
                $ConfigValue = explode(';', $ConfigValue);
            }
            $xoopsTpl->assign($ConfigName, $ConfigValue);
        }
    }

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
    $i = 1;
    foreach ($all_plugins as $plugin) {
        // die(var_export($plugin));
        $dirname = $plugin['dirname'];

        if ('system' === $dirname) {
            continue;
        }

        if ('1' != $plugin['db']['PluginEnable']) {
            $current .= "if(defined('_SHOW_UNABLE') and _SHOW_UNABLE=='1'){\n";
        }

        $plugin['db']['PluginTitle'] = $xoopsDB->escape($plugin['db']['PluginTitle']);

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

        if ('1' != $plugin['db']['PluginEnable']) {
            $current .= "}\n\n";
        } else {
            $current .= "\n";
        }

        if ('1' == $plugin['db']['PluginEnable']) {
            $plugin_enable_arr[] = $dirname;
        }

        $i++;
    }

    if (!empty($WebID)) {
        $file = XOOPS_ROOT_PATH . "/uploads/tad_web/{$WebID}/menu_var.php";
        save_web_config('web_plugin_enable_arr', implode(',', $plugin_enable_arr), $WebID);
    }

    file_put_contents($file, $current);

    $display_blocks_arr = get_web_blocks($WebID);

    if (empty($display_blocks_arr)) {
        //取得系統所有區塊
        $block_option = get_all_blocks();
        $block_plugin = get_all_blocks('plugin');
        $block_config = get_all_blocks('config');
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
            $sql = 'insert into `' . $xoopsDB->prefix('tad_web_blocks') . "` (`BlockName`, `BlockCopy`, `BlockTitle`, `BlockContent`, `BlockEnable`, `BlockConfig`, `BlockPosition`, `BlockSort`, `WebID`, `plugin`, `ShareFrom`) values('{$func}', 0, '{$name}', '', '{$BlockEnable}', '{$BlockConfig}', '{$block_position[$func]}', '{$sort}', '{$WebID}', '{$block_plugin[$func]}', 0)";
            $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);
            $sort++;
        }
    }
}

function get_tad_web_mems($MemID)
{
    global $xoopsDB;

    $sql = 'select * from ' . $xoopsDB->prefix('tad_web_mems') . " where MemID='{$MemID}'";
    $result = $xoopsDB->query($sql) or Utility::web_error($sql, __FILE__, __LINE__);
    $all = $xoopsDB->fetchArray($result);

    $sql = 'select MemNum,CateID from ' . $xoopsDB->prefix('tad_web_link_mems') . " where `MemID`='{$MemID}' limit 0,1";
    $result = $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);
    list($MemNum, $CateID) = $xoopsDB->fetchRow($result);
    $all['MemNum'] = $MemNum;
    $all['CateID'] = $CateID;

    return $all;
}

function get_tad_web_parent($ParentID = '', $code = '')
{
    global $xoopsDB;
    $andCode = !empty($code) ? "and `code`='{$code}'" : '';
    $sql = 'select * from ' . $xoopsDB->prefix('tad_web_mem_parents') . " where `ParentID`='{$ParentID}' {$andCode}";
    $result = $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);
    $all = $xoopsDB->fetchArray($result);

    return $all;
}

function get_tad_web_link_mems($MemID = '', $CateID = '')
{
    global $xoopsDB;

    $sql = 'select * from ' . $xoopsDB->prefix('tad_web_link_mems') . " where MemID='{$MemID}' and CateID='{$CateID}'";
    // die($sql);
    $result = $xoopsDB->query($sql) or Utility::web_error($sql, __FILE__, __LINE__);
    $all = $xoopsDB->fetchArray($result);
    // die(var_export($all));
    return $all;
}

//取得網頁下成員的人數
function memAmount($WebID = '')
{
    global $xoopsDB;

    $sql = 'select count(*) from ' . $xoopsDB->prefix('tad_web_link_mems') . " where WebID='{$WebID}'";
    $result = $xoopsDB->query($sql) or Utility::web_error($sql, __FILE__, __LINE__);
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
        $isAdmin = $xoopsUser->isAdmin($module_id);
    }

    return $isAdmin;
}

//登出按鈕
function logout_button($interface_menu = [])
{
    return $interface_menu;
}

//取得目前的學年學期陣列
function get_seme()
{
    global $xoopsDB;
    $y = date('Y');
    $m = date('n');
    $d = date('j');
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

function getAllCateName($ColName = '', $WebID = '', $CateID = '')
{
    return [];
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

//以流水號取得某筆tad_web資料
function get_tad_web($WebID = '', $enable = false, $use_session = true)
{
    global $xoopsDB, $isMyWeb, $isAdmin;
    if (empty($WebID)) {
        return;
    }

    if (isset($_SESSION['tad_web'][$WebID]['WebID']) and $use_session) {
        if ($enable and (empty($_SESSION['tad_web'][$WebID]))) {
            redirect_header('index.php', 3, _MD_TCW_WEB_NOT_EXIST);
        }

        return $_SESSION['tad_web'][$WebID];
    }

    $andEnable = ($enable and !$isMyWeb and !$isAdmin) ? "and WebEnable='1'" : '';

    $sql = 'select * from ' . $xoopsDB->prefix('tad_web') . " where WebID='$WebID' {$andEnable}";
    $result = $xoopsDB->query($sql) or Utility::web_error($sql, __FILE__, __LINE__);

    $data = $xoopsDB->fetchArray($result);
    if (_IS_EZCLASS) {
        $used_size = redis_do($WebID, 'get', '', 'used_size');
        $data['used_size'] = $used_size;
    }

    $_SESSION['tad_web'][$WebID] = $data;

    if ($enable and (empty($data))) {
        redirect_header('index.php', 3, _MD_TCW_WEB_NOT_EXIST);
    }

    return $data;
}

function get_web_uid($WebID)
{
    global $xoopsDB;
    $sql = 'select WebOwnerUid from ' . $xoopsDB->prefix('tad_web') . " where WebID='$WebID'";
    $result = $xoopsDB->query($sql) or Utility::web_error($sql, __FILE__, __LINE__);
    list($WebOwnerUid) = $xoopsDB->fetchRow($result);
    return $WebOwnerUid;
}

//取得網站資訊
function getAllWebInfo($get_col = 'WebTitle')
{
    global $xoopsDB;

    $sql = "select `WebID`, `{$get_col}` from " . $xoopsDB->prefix('tad_web') . ' order by WebSort';
    $result = $xoopsDB->query($sql) or Utility::web_error($sql, __FILE__, __LINE__);
    $Webs = [];
    while (list($WebID, $data) = $xoopsDB->fetchRow($result)) {
        $Webs[$WebID] = $data;
    }

    return $Webs;
}

//取得分類名稱
function getLevelName($WebID = '')
{
    global $xoopsDB;
    $sql = 'select `WebTitle` from ' . $xoopsDB->prefix('tad_web') . " where WebID='$WebID'";
    $result = $xoopsDB->query($sql) or Utility::web_error($sql, __FILE__, __LINE__);
    list($WebTitle) = $xoopsDB->fetchRow($result);

    return $main;
}

//立即寄出
function send_now($email = '', $title = '', $content = '', $ColName = '', $ColSN = '', $WebID = '')
{
    global $xoopsConfig, $xoopsDB, $xoopsModuleConfig;

    $xoopsMailer = &getMailer();
    $xoopsMailer->multimailer->ContentType = 'text/html';
    $xoopsMailer->addHeaders('MIME-Version: 1.0');
    $msg = ($xoopsMailer->sendMail($email, $title, $content, $headers)) ? true : false;

    if ($ColName and $msg) {
        $now = date('Y-m-d H:i:s');
        $sql = 'insert into ' . $xoopsDB->prefix('tad_web_mail_log') . " (`ColName`, `ColSN`, `WebID`, `Mail`, `MailDate`) values('{$ColName}', '{$ColSN}', '{$WebID}', '{$email}', '{$now}')";
        $xoopsDB->query($sql) or Utility::web_error($sql, __FILE__, __LINE__);
    }

    return $msg;
}

//製作logo圖
function mklogoPic($WebID = '')
{
    $Class = get_tad_web($WebID);
    $WebName = $Class['WebName'];
    $WebTitle = $Class['WebTitle'];

    if (function_exists('mb_strwidth')) {
        $n = mb_strwidth($WebName) / 2;
        $n2 = mb_strwidth($WebTitle) / 2;
    } else {
        $n = mb_strlen($WebName) / 3;
        $n2 = mb_strlen($WebTitle) / 3;
    }
    // die('$n:' . $n);
    //$width=50*$n+35;
    // $size = round(800 / $n, 0);
    // if ($size > 70) {
    $size = 60;
    $pic_size1 = ($size + 24) * $n;
    $x = $size + 10;
    $size2 = 20;
    $pic_size2 = ($size2 + 8) * $n2;
    // } else {
    //     $x     = round(800 / $n, 0) + 10;
    //     $size2 = 17;
    // }
    $y = $size + 65;

    $pic_size = ($pic_size1 > $pic_size2) ? $pic_size1 : $pic_size2;

    // header('Content-type: image/png');
    $im = @imagecreatetruecolor($pic_size, 140) or die(_MD_TCW_MKPIC_ERROR);
    imagesavealpha($im, true);

    $white = imagecolorallocate($im, 255, 255, 255);

    //$trans_colour = imagecolorallocatealpha($im, 157,211,223, 127);
    $trans_colour = imagecolorallocatealpha($im, 255, 255, 255, 127);
    imagefill($im, 0, 0, $trans_colour);

    $text_color = imagecolorallocate($im, 0, 0, 0);
    $text_color2 = imagecolorallocatealpha($im, 255, 255, 255, 50);

    $gd = gd_info();
    if ($gd['JIS-mapped Japanese Font Support']) {
        $WebTitle = iconv('UTF-8', 'shift_jis', $WebTitle);
        $WebName = iconv('UTF-8', 'shift_jis', $WebName);
    }

    imagettftext($im, $size, 0, 0, $x, $text_color, XOOPS_ROOT_PATH . '/modules/tad_web/class/font.ttf', $WebName);
    imagettftextoutline(
        $im, // image location ( you should use a variable )
        $size, // font size
        0, // angle in °
        0, // x
        $x, // y
        $text_color,
        $white,
        XOOPS_ROOT_PATH . '/modules/tad_web/class/font.ttf',
        $WebName, // pattern
        2// outline width
    );

    imagettftext($im, $size2, 0, 0, $y, $text_color, XOOPS_ROOT_PATH . '/modules/tad_web/class/font.ttf', $WebTitle);
    imagettftextoutline(
        $im, // image location ( you should use a variable )
        $size2, // font size
        0, // angle in °
        0, // x
        $y, // y
        $text_color,
        $white,
        XOOPS_ROOT_PATH . '/modules/tad_web/class/font.ttf',
        $WebTitle, // pattern
        1// outline width
    );

    Utility::mk_dir(XOOPS_ROOT_PATH . "/uploads/tad_web/{$WebID}");
    Utility::mk_dir(XOOPS_ROOT_PATH . "/uploads/tad_web/{$WebID}/auto_logo");

    imagepng($im, XOOPS_ROOT_PATH . "/uploads/tad_web/{$WebID}/auto_logo/auto_logo.png");
    imagedestroy($im);
}

//製作logo圖
function mkTitlePic($WebID = '', $filename = '', $title = '', $color = '#ABBF6B', $border_color = '#FFFFFF', $size = '30', $font = 'font.ttf')
{
    if (empty($title)) {
        return;
    }

    if (function_exists('mb_strlen')) {
        $n = mb_strlen($title, _CHARSET);
    } else {
        $n = mb_strlen($title) / 3;
    }
    if (empty($size)) {
        return;
    }
    $width = $size * 1.5 * $n;
    $height = $size * 3;

    $x = 2;
    $y = $size * 2;

    list($color_r, $color_g, $color_b) = sscanf($color, '#%02x%02x%02x');
    list($border_color_r, $border_color_g, $border_color_b) = sscanf($border_color, '#%02x%02x%02x');

    // header('Content-type: image/png');
    $im = @imagecreatetruecolor($width, $height) or die(_MD_TCW_MKPIC_ERROR . "({$title}->{$size} , {$width} x {$height})");
    imagesavealpha($im, true);

    $trans_colour = imagecolorallocatealpha($im, 255, 255, 255, 127);
    imagefill($im, 0, 0, $trans_colour);

    $text_color = imagecolorallocate($im, $color_r, $color_g, $color_b);
    $text_border_color = imagecolorallocatealpha($im, $border_color_r, $border_color_g, $border_color_b, 50);

    $gd = gd_info();
    if ($gd['JIS-mapped Japanese Font Support']) {
        $title = iconv('UTF-8', 'shift_jis', $title);
    }

    imagettftext($im, $size, 0, $x, $y, $text_color, XOOPS_ROOT_PATH . "/modules/tad_web/class/{$font}", $title);
    if ('transparent' !== $border_color) {
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
    Utility::mk_dir(XOOPS_ROOT_PATH . "/uploads/tad_web/{$WebID}/");
    Utility::mk_dir(XOOPS_ROOT_PATH . "/uploads/tad_web/{$WebID}/image/");

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
function import_img($path = '', $col_name = 'logo', $col_sn = '', $desc = '', $safe_name = false)
{
    global $xoopsDB;
    if (false !== mb_strpos($path, 'http')) {
        $path = str_replace(XOOPS_URL, XOOPS_ROOT_PATH, $path);
    }
    if (empty($path)) {
        return;
    }

    if (!is_dir($path) and !is_file($path)) {
        return;
    }

    $db_files = [];

    $sql = 'select files_sn,file_name,original_filename from ' . $xoopsDB->prefix('tad_web_files_center') . " where col_name='{$col_name}' and col_sn='{$col_sn}'";

    $result = $xoopsDB->query($sql) or Utility::web_error($sql, __FILE__, __LINE__);
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
            while (false !== ($file = readdir($dh))) {
                if ('.' === $file or '..' === $file or 'Thumbs.db' === $file) {
                    continue;
                }

                $type = filetype($path . '/' . $file);

                if ('dir' !== $type) {
                    if (!in_array($file, $db_files)) {
                        import_file($path . '/' . $file, $col_name, $col_sn, null, null, $desc, $safe_name);
                    }
                }
            }
            closedir($dh);
        }
    } elseif (is_file($path)) {
        import_file($path, $col_name, $col_sn, null, null, $desc, $safe_name);
    }
}

//移除預設圖片
function fixed_img($uploads_path = '', $col_name = 'logo', $col_sn = '', $TadUpFiles)
{
    global $xoopsDB;

    if (false !== mb_strpos($uploads_path, 'http')) {
        $uploads_path = str_replace(XOOPS_URL, XOOPS_ROOT_PATH, $uploads_path);
    }

    if (empty($uploads_path)) {
        return;
    }

    if (!is_dir($uploads_path)) {
        return;
    }

    $TadUpFiles->set_col($col_name, $col_sn);

    $db_files = [];

    $sql = 'update ' . $xoopsDB->prefix('tad_web_files_center') . " set `sub_dir`= '/{$col_sn}/{$col_name}' WHERE `col_name` = '{$col_name}' and col_sn='{$col_sn}'";
    $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);

    $sql = 'select files_sn,file_name,original_filename from ' . $xoopsDB->prefix('tad_web_files_center') . " where col_name='{$col_name}' and col_sn='{$col_sn}'";

    $result = $xoopsDB->query($sql) or Utility::web_error($sql, __FILE__, __LINE__);
    $db_files_amount = 0;
    while (list($files_sn, $file_name, $original_filename) = $xoopsDB->fetchRow($result)) {
        $db_files[$files_sn] = $original_filename;
        $db_files_amount++;
    }

    if (empty($db_files_amount)) {
        return;
    }

    if (is_dir($uploads_path)) {
        if ($dh = opendir($uploads_path)) {
            while (false !== ($file = readdir($dh))) {
                if ('.' === $file or '..' === $file or 'Thumbs.db' === $file or 'thumbs.db' === $file) {
                    continue;
                }
                $pic = "$uploads_path/$file";
                $thumb_pic = "$uploads_path/thumbs/$file";
                $type = filetype($pic);

                if ('dir' !== $type) {
                    if (in_array($file, $db_files)) {
                        $files_sn = array_search($file, $db_files);
                        $TadUpFiles->del_files($files_sn);
                        unlink($pic);
                    } elseif (is_link($pic)) {
                        unlink($pic);
                    } elseif (!file_exists($thumb_pic)) {
                        $mime_content_type = mime_content_type($pic);
                        // die("thumbnail($pic, $thumb_pic)");
                        thumbnail($pic, $thumb_pic, $mime_content_type);
                    }
                }
            }
            closedir($dh);
        }
    }

    if (is_link($uploads_path . '/thumbs')) {
        delete_tad_web_directory($uploads_path . '/thumbs');
        Utility::mk_dir($uploads_path . '/thumbs');
    }

}

function thumbnail($filename = '', $thumb_name = '', $type = 'image/jpeg', $width = '240', $angle = 0)
{
    set_time_limit(0);
    ini_set('memory_limit', '300M');
    // Get new sizes
    list($old_width, $old_height) = getimagesize($filename);

    if (0 != $angle) {
        $h = $old_height;
        $w = $old_width;

        $old_width = $h;
        $old_height = $w;
    }

    // die("$old_width, $old_height");

    if ($old_width > $width) {
        $percent = ($old_width > $old_height) ? round($width / $old_width, 2) : round($width / $old_height, 2);

        $newwidth = ($old_width > $old_height) ? $width : $old_width * $percent;
        $newheight = ($old_width > $old_height) ? $old_height * $percent : $width;

        // Load
        $thumb = imagecreatetruecolor($newwidth, $newheight);
        ob_start();
        if ('image/jpeg' === $type or 'image/jpg' === $type or 'image/pjpg' === $type or 'image/pjpeg' === $type) {
            $source = imagecreatefromjpeg($filename);

            $type = 'image/jpeg';
        } elseif ('image/png' === $type) {
            $source = imagecreatefrompng($filename);
            $type = 'image/png';
        } elseif ('image/gif' === $type) {
            $source = imagecreatefromgif($filename);
            $type = 'image/gif';
        }
        if (0 != $angle) {
            $source = imagerotate($source, $angle, 0);
        }
        // Resize
        imagecopyresampled($thumb, $source, 0, 0, 0, 0, $newwidth, $newheight, $old_width, $old_height);

        // header("Content-type: $type");
        if ('image/jpeg' === $type) {
            imagejpeg($thumb, $thumb_name);
        } elseif ('image/png' === $type) {
            imagepng($thumb, $thumb_name);
        } elseif ('image/gif' === $type) {
            imagegif($thumb, $thumb_name);
        }
        ob_end_clean();
        return;
    }
}

// 取得背景或標題圖的預設圖片
function get_default_img($dir)
{

    if (substr($dir, -1) !== '/') {
        $dir .= '/';
    }
    $files = [];
    if (is_dir($dir)) {
        if ($dh = opendir($dir)) {
            while (false !== ($file = readdir($dh))) {
                $type = filetype($dir . $file);

                if ('dir' != $type and '.' != substr($file, 0, 1) and strpos($file, '.db') === false) {
                    $data['file_name'] = $file;
                    $data['tb_path'] = str_replace(XOOPS_ROOT_PATH, XOOPS_URL, "{$dir}thumbs/{$file}");
                    $files[$file] = $data;
                }
            }
            closedir($dh);
        }
    }

    ksort($files, SORT_NATURAL);
    // if (strpos($dir, 'head') !== false and $_GET['test'] == 1) {
    //     Utility::dd($files);
    // }
    return $files;
}

//匯入圖檔
function import_file($file_name = '', $col_name = '', $col_sn = '', $main_width = '', $thumb_width = '90', $desc = '', $safe_name = false)
{
    global $xoopsDB, $xoopsUser, $xoopsModule, $xoopsConfig;

    $slink = (PATH_SEPARATOR === ':') ? true : false;
    if ('bg' === $col_name) {
        $TadUpFilesBg = TadUpFilesBg($col_sn);
        if (is_object($TadUpFilesBg)) {
            $TadUpFilesBg->set_col($col_name, $col_sn);
            $TadUpFilesBg->import_one_file($file_name, null, $main_width, $thumb_width, null, $desc, $safe_name, false, $slink);
        } else {
            die('Need TadUpFilesBg Object!');
        }
    } elseif ('logo' === $col_name) {
        $TadUpFilesLogo = TadUpFilesLogo($col_sn);
        if (is_object($TadUpFilesLogo)) {
            $TadUpFilesLogo->set_col($col_name, $col_sn);
            $TadUpFilesLogo->import_one_file($file_name, null, $main_width, $thumb_width, null, $desc, $safe_name, false, $slink);
        } else {
            die('Need TadUpFilesLogo Object!');
        }
    } elseif ('head' === $col_name) {
        $TadUpFilesHead = TadUpFilesHead($col_sn);
        if (is_object($TadUpFilesHead)) {
            $TadUpFilesHead->set_col($col_name, $col_sn);
            $TadUpFilesHead->import_one_file($file_name, null, $main_width, $thumb_width, null, $desc, $safe_name, false, $slink);
        } else {
            die('Need TadUpFilesHead Object!');
        }
    }
}

function TadUpFilesBg($WebID)
{
    $TadUpFilesBg = new TadUpFiles('tad_web', "/{$WebID}/bg", null, '', '/thumbs');

    $TadUpFilesBg->set_thumb('100px', '60px', '#000', 'center center', 'no-repeat', 'contain');

    return $TadUpFilesBg;
}

function TadUpFilesLogo($WebID)
{
    $TadUpFilesLogo = new TadUpFiles('tad_web', "/{$WebID}/logo", null, '', '/thumbs');

    $TadUpFilesLogo->set_thumb('100px', '60px', '#000', 'center center', 'no-repeat', 'contain');

    return $TadUpFilesLogo;
}

function TadUpFilesHead($WebID)
{
    $TadUpFilesHead = new TadUpFiles('tad_web', "/{$WebID}/head", null, '', '/thumbs');

    $TadUpFilesHead->set_thumb('100px', '60px', '#000', 'center center', 'no-repeat', 'contain');

    return $TadUpFilesHead;
}

//取得tad_web_cate分類選單的選項（單層選單）
function get_tad_web_cate_menu_options($default_CateID = '0')
{
    global $xoopsDB, $xoopsModule;
    $sql = 'select `CateID`, `CateName`
    from `' . $xoopsDB->prefix('tad_web_cate') . '` order by `CateSort`';
    $result = $xoopsDB->query($sql) or Utility::web_error($sql, __FILE__, __LINE__);

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

    $sql = 'select * from `' . $xoopsDB->prefix('tad_web') . "` where WebEnable='1' and WebID > 0 order by WebSort,WebTitle";
    $result = $xoopsDB->query($sql) or Utility::web_error($sql, __FILE__, __LINE__);
    $data_arr = [];
    while (false !== ($all = $xoopsDB->fetchArray($result))) {
        foreach ($all as $k => $v) {
            $$k = $v;
        }
        if (empty($WebID)) {
            continue;
        }
        $all['other_web_url'] = isset($other_web_url_arr[$WebID]) ? $other_web_url_arr[$WebID] : '';
        $all['isMyWeb'] = ($isAdmin) ? true : in_array($WebID, $MyWebs);
        $data_arr[$CateID][$WebID] = $all;
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

    $width = 1140;
    $height = 200;
    //die('test2=' . $WebID);
    $all_config = get_web_all_config($WebID);
    //die("<h2>$WebID</h2>" . var_export($all_config));
    foreach ($all_config as $k => $v) {
        $$k = $v;
    }

    $bg_filename = strpos($web_head, "head_{$WebID}_") !== false ? XOOPS_ROOT_PATH . "/uploads/tad_web/{$WebID}/head/{$web_head}" : XOOPS_ROOT_PATH . "/modules/tad_web/images/head/{$web_head}";

    list($bg_width, $bg_height) = getimagesize($bg_filename);

    $type = mb_strtolower(mb_substr(mb_strrchr($bg_filename, '.'), 1));
    if ('jpeg' === $type) {
        $type = 'jpg';
    }

    switch ($type) {
        case 'bmp':
            $bg_im = imagecreatefromwbmp($bg_filename);
            $mimetype = 'image/bmp';
            break;
        case 'gif':
            $bg_im = imagecreatefromgif($bg_filename);
            $mimetype = 'image/gif';
            break;
        case 'jpg':
            $bg_im = imagecreatefromjpeg($bg_filename);
            $mimetype = 'image/jpeg';
            break;
        case 'png':
            $bg_im = imagecreatefrompng($bg_filename);
            $mimetype = 'image/png';
            break;
        default:return 'Unsupported picture type!';
    }

    if ($width != $bg_width) {
        thumbnail(XOOPS_ROOT_PATH . "/uploads/tad_web/{$WebID}/head/{$web_head}", XOOPS_ROOT_PATH . "/uploads/tad_web/{$WebID}/head/{$web_head}", $mimetype, $width);
    }

    $im = @imagecreatetruecolor($width, $height);
    imagecolortransparent($im, imagecolorallocatealpha($im, 0, 0, 0, 127));
    imagealphablending($im, true);
    imagesavealpha($im, true);
    if (file_exists($bg_filename)) {
        //縮放比例
        $rate = round($bg_width / $width, 4);
        $head_top = abs($head_top);
        $bg_top = round($head_top * $rate, 0);

        //背景圖
        imagecopyresampled($im, $bg_im, 0, 0, 0, $bg_top, $width, $bg_height, $bg_width, $bg_height);
    }

    $logo_filename = XOOPS_ROOT_PATH . "/uploads/tad_web/{$WebID}/logo/{$web_logo}";
    if (file_exists($logo_filename)) {
        list($logo_width, $logo_height) = getimagesize($logo_filename);

        $type = mb_strtolower(mb_substr(mb_strrchr($logo_filename, '.'), 1));
        if ('jpeg' === $type) {
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
            default:return 'Unsupported picture type!';
        }

        //logo圖
        imagecopyresampled($im, $logo_im, $logo_left, $logo_top, 0, 0, $logo_width, $logo_height, $logo_width, $logo_height);
    }

    // header('Content-type: image/png');
    $save = XOOPS_ROOT_PATH . "/uploads/tad_web/{$WebID}/header.png";
    imagepng($im, $save);

    // imagepng($im, XOOPS_ROOT_PATH . "/uploads/tad_web/{$WebID}/header.png");
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

    $width = 400;
    $height = 200;
    $all_config = get_web_all_config($WebID);
    foreach ($all_config as $k => $v) {
        $$k = $v;
    }

    $im = @imagecreatetruecolor($width, $height);
    imagecolortransparent($im, imagecolorallocatealpha($im, 0, 0, 0, 127));
    imagealphablending($im, true);
    imagesavealpha($im, true);

    $bg_filename = strpos($web_head, "head_{$WebID}_") !== false ? XOOPS_ROOT_PATH . "/uploads/tad_web/{$WebID}/head/{$web_head}" : XOOPS_ROOT_PATH . "/modules/tad_web/images/head/{$web_head}";
    if (file_exists($bg_filename)) {
        list($bg_width, $bg_height) = getimagesize($bg_filename);

        //縮放比例
        $rate = round($width / $bg_width, 2);
        $new_bg_height = round($bg_height * $rate, 0);
        $bg_top = round($head_top * $rate, 0);
        $new_bg_width = $width;

        $type = mb_strtolower(mb_substr(mb_strrchr($bg_filename, '.'), 1));
        if ('jpeg' === $type) {
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
            default:return 'Unsupported picture type!';
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
        $new_logo_width = 380;

        $type = mb_strtolower(mb_substr(mb_strrchr($logo_filename, '.'), 1));
        if ('jpeg' === $type) {
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
            default:return 'Unsupported picture type!';
        }

        $logo_left = ($width - $new_logo_width) / 2;
        $logo_top = ($height - $new_logo_height) / 2;

        //logo圖
        imagecopyresampled($im, $logo_im, $logo_left, $logo_top, 0, 0, $new_logo_width, $new_logo_height, $logo_width, $logo_height);
    }

    // header('Content-type: image/png');

    // imagepng($im, XOOPS_ROOT_PATH . "/uploads/tad_web/{$WebID}/header_480.png");
    $save = XOOPS_ROOT_PATH . "/uploads/tad_web/{$WebID}/header_480.png";
    imagepng($im, $save);
    chmod($save, 0777);

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
        if ('.' !== $file && '..' !== $file) {
            if (!is_dir($dirname . '/' . $file)) {
                unlink($dirname . '/' . $file);
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
function check_quota($WebID = '')
{
    global $xoopsDB;
    $data = '';
    $dir = XOOPS_ROOT_PATH . '/uploads/tad_web/';

    $dir_size = get_dir_size("{$dir}{$WebID}/");
    $size = size2mb($dir_size);
    save_web_config('used_size', $size, $WebID);

    if (_IS_EZCLASS) {
        redis_do($WebID, 'set', '', 'used_size', $dir_size);
    } else {
        $sql = 'update `' . $xoopsDB->prefix('tad_web') . "` set `used_size`='{$dir_size}' where `WebID`='{$WebID}'";
        $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);
    }

    $_SESSION['tad_web'][$WebID]['used_size'] = $dir_size;
    clear_my_webs_data($WebID);
}

//檢查已使用空間
function get_quota($WebID = '')
{
    global $xoopsModuleConfig, $Web;
    $Web = get_tad_web($WebID);

    $defalt_used_size = round((int) $Web['used_size'] / 1048576, 2);

    $user_default_quota = empty($xoopsModuleConfig['user_space_quota']) ? 500 : (int) $xoopsModuleConfig['user_space_quota'];

    $space_quota = get_web_config('space_quota', $WebID, 'db');

    $user_space_quota = (empty($space_quota) or 'default' === $space_quota) ? $user_default_quota : (int) $space_quota;

    if ($defalt_used_size >= $user_space_quota) {
        redirect_header("index.php?WebID={$WebID}", 3, sprintf(_MD_TCW_NO_SPACE, $WebID, $defalt_used_size, $user_space_quota));
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
    $i = 0;
    $iec = ['B', 'KB', 'MB', 'GB', 'TB', 'EB', 'ZB', 'YB'];
    while (($size / 1024) > 1) {
        $size = $size / 1024;
        $i++;
    }

    return (round($size, 1) . ' ' . $iec[$i]);
}

function get_dir_size($dir_name)
{
    $dir_size = 0;
    if (is_dir($dir_name)) {
        if ($dh = opendir($dir_name)) {
            while (false !== ($file = readdir($dh))) {
                if ('.' !== $file && '..' !== $file) {
                    if (is_file($dir_name . '/' . $file)) {
                        $dir_size += filesize($dir_name . '/' . $file);
                    }
                    /* check for any new directory inside this directory */
                    if (is_dir($dir_name . '/' . $file)) {
                        $dir_size += get_dir_size($dir_name . '/' . $file);
                    }
                }
            }
        }
    }
    closedir($dh);

    return $dir_size;
}

//取得額外設定的儲存值
function get_plugin_setup_values($WebID = '', $plugin = '')
{
    global $xoopsDB, $xoopsConfig;

    Utility::mk_dir(XOOPS_VAR_PATH . "/tad_web");
    Utility::mk_dir(XOOPS_VAR_PATH . "/tad_web/$WebID/");
    Utility::mk_dir(XOOPS_VAR_PATH . "/tad_web/$WebID/$plugin/");
    $plugin_setup_values_file = XOOPS_VAR_PATH . "/tad_web/$WebID/$plugin/setup_values.json";

    if (file_exists($plugin_setup_values_file)) {
        $values = json_decode(file_get_contents($plugin_setup_values_file), true);
    } else {
        $myts = \MyTextSanitizer::getInstance();
        $setup_file = XOOPS_ROOT_PATH . "/modules/tad_web/plugins/{$plugin}/setup.php";
        if (file_exists($setup_file)) {
            require_once XOOPS_ROOT_PATH . "/modules/tad_web/plugins/{$plugin}/langs/{$xoopsConfig['language']}.php";
            require $setup_file;
        }

        $sql = 'select `name`, `type`, `value` from ' . $xoopsDB->prefix('tad_web_plugins_setup') . " where `WebID`='{$WebID}' and plugin='{$plugin}'";
        $result = $xoopsDB->query($sql) or Utility::web_error($sql, __FILE__, __LINE__);
        $setup_db_values = [];
        //`theme_id`, `name`, `type`, `value`
        while (list($name, $type, $value) = $xoopsDB->fetchRow($result)) {
            $setup_db_values[$name] = $value;
        }

        foreach ($plugin_setup as $k => $setup) {
            $name = $setup['name'];
            $value = isset($setup_db_values[$name]) ? $myts->htmlSpecialChars($setup_db_values[$name]) : $setup['default'];
            $values[$name] = $value;
        }

        file_put_contents($plugin_setup_values_file, json_encode($values, 256));
    }

    return $values;
}

//刪除tad_web某筆資料資料確認
function delete_tad_web_chk($WebID = '', $g2p = 0)
{
    global $xoopsDB, $xoopsTpl;
    if (empty($WebID)) {
        return;
    }

    $pluginsVal = get_db_plugins($WebID);
    $i = 0;
    foreach ($pluginsVal as $dirname => $plugin) {
        $plugins[$i]['dirname'] = $dirname;
        $plugins[$i]['PluginTitle'] = $plugin['PluginTitle'];

        require_once XOOPS_ROOT_PATH . "/modules/tad_web/plugins/{$dirname}/class.php";
        $plugin_name = "tad_web_{$dirname}";
        $$plugin_name = new $plugin_name($WebID);
        $plugins[$i]['total'] = $$plugin_name->get_total();

        $i++;
    }

    $xoopsTpl->assign('plugins', $plugins);
    $xoopsTpl->assign('WebID', $WebID);
    $xoopsTpl->assign('g2p', $g2p);
}

//刪除tad_web某筆資料資料
function delete_tad_web($WebID = '')
{
    global $xoopsDB, $TadUpFiles;
    if (empty($WebID)) {
        return;
    }

    $pluginsVal = get_db_plugins($WebID);
    $i = 0;
    foreach ($pluginsVal as $dirname => $plugin) {
        $plugins[$i]['dirname'] = $dirname;
        $plugins[$i]['PluginTitle'] = $plugin['PluginTitle'];

        require_once XOOPS_ROOT_PATH . "/modules/tad_web/plugins/{$dirname}/class.php";
        $plugin_name = "tad_web_{$dirname}";
        $$plugin_name = new $plugin_name($WebID);
        $$plugin_name->delete_all();

        $i++;
    }
    $sql = 'delete from ' . $xoopsDB->prefix('tad_web_tags') . " where WebID='{$WebID}'";
    $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);

    $sql = 'delete from ' . $xoopsDB->prefix('tad_web_power') . " where WebID='{$WebID}'";
    $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);

    $sql = 'delete from ' . $xoopsDB->prefix('tad_web_blocks') . " where WebID='{$WebID}'";
    $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);

    $sql = 'delete from ' . $xoopsDB->prefix('tad_web_plugins_setup') . " where WebID='{$WebID}'";
    $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);

    $sql = 'delete from ' . $xoopsDB->prefix('tad_web_plugins') . " where WebID='{$WebID}'";
    $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);

    $sql = 'delete from ' . $xoopsDB->prefix('tad_web_roles') . " where WebID='{$WebID}'";
    $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);

    $sql = 'delete from ' . $xoopsDB->prefix('tad_web_config') . " where WebID='{$WebID}'";
    $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);

    $sql = 'delete from ' . $xoopsDB->prefix('tad_web_cate') . " where WebID='{$WebID}'";
    $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);

    $sql = 'delete from ' . $xoopsDB->prefix('tad_web') . " where WebID='{$WebID}'";
    $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);
    unset($_SESSION['tad_web'][$WebID]);

    $file = XOOPS_ROOT_PATH . "/uploads/tad_web/{$WebID}/web_config.php";
    unlink($file);

    $TadUpFiles->set_col('WebOwner', $WebID);
    $TadUpFiles->del_files();

    //刪除所有附檔
    if (!delete_tad_web_directory(XOOPS_ROOT_PATH . "/uploads/tad_web/{$WebID}")) {
        Utility::web_error('無法刪除資料夾' . XOOPS_ROOT_PATH . "/uploads/tad_web/{$WebID}");
    }
    if (!delete_tad_web_directory(XOOPS_VAR_PATH . "/tad_web/{$WebID}")) {
        Utility::web_error('無法刪除資料夾' . XOOPS_VAR_PATH . "/tad_web/{$WebID}");
    }
}

//更新最後被拜訪日期
function update_last_accessed($WebID = '')
{
    global $xoopsDB;

    $last_accessed = date('Y-m-d H:i:s');

    if (_IS_EZCLASS) {
        redis_do($WebID, 'set', '', 'last_accessed', $last_accessed);
    } else {
        $sql = 'update `' . $xoopsDB->prefix('tad_web') . "` set `last_accessed`='{$last_accessed}' where `WebID`='{$WebID}'";
        $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);
    }

}

function redis_do($WebID, $act = 'set', $plugin = '', $key = '', $value = '')
{
    if (class_exists('Redis')) {
        $redis = new Redis();
        // $redis->connect('120.115.2.85', 6379);
        $redis->connect('127.0.0.1', 6379);
        $KEY = !empty($plugin) ? "$WebID:$plugin:$key" : "$WebID:$key";
        if ($_GET['test'] == 1) {
            echo "$KEY<br>";
        }

        if ($act == 'set') {
            return $redis->set($KEY, $value);
        } elseif ($act == 'incr') {
            return $redis->incr($KEY);
        } else {

            return $redis->get($KEY);
        }
    }
}

/**  * 獲取文章內容(當前分頁)
 * @param string $content 文章內容
 * @param int $page 頁數
 * @return array
 */
function get_article_content($content, $page = 1)
{
    $page = $page ? (int) $page :

    $article = ['info' => [], 'pages' => 1];

    if (!empty($content)) {
        $pattern = "/<div style=\"page-break-after: always;?\">\s*<span style=\"display: none;?\">&nbsp;<\/span>\s*<\/div>/";
        $contents = preg_split($pattern, $content);

        $article['pages'] = count($contents);

        ($page > $article['pages']) && $page = $article['pages'];

        $article['info'] = $contents[$page - 1];
    }

    return $article;
}

//以流水號取得某筆tad_web_notice資料
function get_tad_web_notice($NoticeID = '')
{
    global $xoopsDB;

    if (empty($NoticeID)) {
        return;
    }

    $sql = 'select * from `' . $xoopsDB->prefix('tad_web_notice') . "`
    where `NoticeID` = '{$NoticeID}'";
    $result = $xoopsDB->query($sql)
    or Utility::web_error($sql, __FILE__, __LINE__);
    $data = $xoopsDB->fetchArray($result);

    return $data;
}

//取得系統預設的OpenID登入方式
function get_sys_openid()
{
    global $xoopsConfig;
    $auth_method = [];
    $moduleHandler = xoops_getHandler('module');
    $configHandler = xoops_getHandler('config');
    $TadLoginXoopsModule = $moduleHandler->getByDirname('tad_login');
    if ($TadLoginXoopsModule) {
        // require_once XOOPS_ROOT_PATH . '/modules/tad_login/function.php';
        // xoops_loadLanguage('county', 'tad_login');
        // if (function_exists('facebook_login')) {
        //     $tad_login['facebook'] = facebook_login('return');
        // }

        // if (function_exists('google_login')) {
        //     $tad_login['google'] = google_login('return');
        // }

        $configHandler = xoops_getHandler('config');
        $modConfig = $configHandler->getConfigsByCat(0, $TadLoginXoopsModule->getVar('mid'));

        $auth_method = $modConfig['auth_method'];
    }

    return $auth_method;
}

//設定小幫手
function set_assistant($WebID, $CateID = '', $MemID = '', $plugin = '')
{
    global $xoopsDB;
    if (empty($CateID) or empty($MemID)) {
        return;
    }

    $plugin = $xoopsDB->escape($plugin);

    $sql = 'insert into `' . $xoopsDB->prefix('tad_web_cate_assistant') . "` (`CateID`, `AssistantType`, `AssistantID`, `plugin`) values('{$CateID}', 'MemID', '{$MemID}', '{$plugin}')";
    $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);
}

//儲存小幫手的編輯紀錄
function save_assistant_post($plugin = '', $CateID = '', $ColName = '', $ColSN = '')
{
    global $xoopsDB, $xoopsUser;

    if (empty($ColName) or empty($ColSN)) {
        return;
    }

    $AssistantID = (int) $_SESSION['AssistantID'][$CateID];

    if (empty($AssistantID)) {
        return;
    }

    $sql = 'replace into `' . $xoopsDB->prefix('tad_web_assistant_post') . "` (
            `plugin`,
            `ColName`,
            `ColSN`,
            `CateID`,
            `AssistantType`,
            `AssistantID`
        ) values(
            '{$plugin}',
            '{$ColName}',
            '{$ColSN}',
            '{$CateID}',
            '{$_SESSION['AssistantType'][$CateID]}',
            '{$AssistantID}'
        )";
    $xoopsDB->query($sql) or Utility::web_error($sql, __FILE__, __LINE__);
    clear_assistant_cache($WebID, $plugin);
}

//檢查某個內容是否是小幫手發的
function is_assistant($WebID, $plugin = '', $DefCateID = '', $DefColName = '', $DefColSN = '')
{
    global $xoopsDB;
    $assistant_cache_file = XOOPS_VAR_PATH . "/tad_web/$WebID/$plugin/assistants.json";

    if (file_exists($assistant_cache_file)) {
        $mems = json_decode(file_get_contents($assistant_cache_file), true);
    } else {
        $sql = "select b.ColName, b.ColSN, b.CateID, b.AssistantType, b.AssistantID from `" . $xoopsDB->prefix('tad_web_cate') . "` as a
        join  `" . $xoopsDB->prefix('tad_web_assistant_post') . "` as b on a.CateID =b.CateID
        where a.`WebID`='{$WebID}' and b.`plugin`='$plugin'";

        $result = $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);
        while (list($ColName, $ColSN, $CateID, $AssistantType, $AssistantID) = $xoopsDB->fetchRow($result)) {
            if ('MemID' === $AssistantType) {
                $mem = get_tad_web_mems($AssistantID);
            } elseif ('ParentID' === $AssistantType) {
                $mem = get_tad_web_parent($AssistantID);
            }
            $mems[$CateID][$ColName][$ColSN] = $mem;
        }
        file_put_contents($assistant_cache_file, json_encode($mems, 256));
    }

    return $mems[$DefCateID][$DefColName][$DefColSN];
}

//是否有管理權（或由自己發布的），判斷是否要秀出管理工具
function isCanEdit($WebID = null, $plugin = '', $CateID = '', $ColName = '', $ColSN = '')
{
    global $isMyWeb, $isAdmin;

    // $_SESSION['isAssistant'][$plugin] = $CateID;
    // $_SESSION['AssistantType'][$CateID]   = 'MemID';
    // $_SESSION['AssistantID'][$CateID]     = $_SESSION['LoginMemID'];

    if (!empty($WebID) and $isMyWeb) {
        return true;
    } elseif ($isAdmin) {
        return true;
    } elseif ($ColName and $ColSN) {
        $mem = is_assistant($WebID, $plugin, $CateID, $ColName, $ColSN);
        // die(var_export($mem));
        // array(
        //     'MemID' => '1',
        //     'MemName' => 'stu1',
        //     'MemNickName' => 'stu1',
        //     'MemSex' => '1',
        //     'MemUnicode' => '106001',
        //     'MemBirthday' => '2001-10-18',
        //     'MemExpertises' => '',
        //     'uid' => '0',
        //     'MemUname' => 'stu1',
        //     'MemPasswd' => 'stu1',
        //     'MemNum' => '1',
        //     'CateID' => '1',
        // )
        if (!empty($mem['MemID']) and $_SESSION['isAssistant'][$plugin] == $CateID) {
            return true;
        }

        return false;
    } elseif ($CateID and $plugin) {
        if ($CateID == $_SESSION['isAssistant'][$plugin]) {
            return true;
        }

        return false;
    }

    return false;
}

function chk_self_web($WebID, $other = null)
{
    global $isMyWeb, $MyWebs;
    if (!$isMyWeb and $MyWebs) {
        redirect_header($_SERVER['PHP_SELF'] . "?op=WebID={$MyWebs[0]}&op=edit_form", 3, _MD_TCW_AUTO_TO_HOME);
    } elseif (!$isMyWeb) {
        if (null !== $other and !$other) {
            redirect_header("index.php?WebID={$WebID}", 3, _MD_TCW_NOT_OWNER . '<br>' . __FILE__ . ' : ' . __LINE__);
        } elseif (empty($MyWebs) and null === $other) {
            redirect_header("index.php?WebID={$WebID}", 3, _MD_TCW_NOT_OWNER . '<br>' . __FILE__ . ' : ' . __LINE__);
        } else {
            return true;
        }
        redirect_header("index.php?WebID={$WebID}", 3, _MD_TCW_NOT_OWNER . '<br>' . __FILE__ . ' : ' . __LINE__);
    }
}
