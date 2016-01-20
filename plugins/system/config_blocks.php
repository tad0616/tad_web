<?php
$blocksArr = '';
global $WebID;

$blocksArr['my_menu']['name']     = _MD_TCW_SYSTEM_BLOCK_MY_MENU;
$blocksArr['my_menu']['tpl']      = 'my_menu.html';
$blocksArr['my_menu']['position'] = 'side';

// $login_method   = '';
$modhandler     = &xoops_gethandler('module');
$config_handler = &xoops_gethandler('config');

$TadLoginXoopsModule = &$modhandler->getByDirname("tad_login");
if ($TadLoginXoopsModule) {
    global $xoopsConfig;
    include_once XOOPS_ROOT_PATH . "/modules/tad_login/language/{$xoopsConfig['language']}/county.php";

    $config_handler = &xoops_gethandler('config');
    $modConfig      = &$config_handler->getConfigsByCat(0, $TadLoginXoopsModule->getVar('mid'));

    $auth_method = $modConfig['auth_method'];
    // if ($_GET['test'] == '1') {
    //     die(var_export($auth_method));
    // }
    foreach ($auth_method as $method) {
        $method_const = "_" . strtoupper($method);
        $loginTitle   = sprintf(_MD_TCW_OPENID_LOGIN, constant($method_const));
        // if ($_GET['test'] == '1') {
        //     echo _MD_TCW_OPENID_LOGIN . "-{$loginTitle}-{$method}-{$method_const}-<br>";
        // }
        $login_defval[]            = $method;
        $login_method[$loginTitle] = $method;

    }
}

$blocksArr['login']['name']     = _MD_TCW_SYSTEM_BLOCK_LOGIN;
$blocksArr['login']['tpl']      = 'login.html';
$blocksArr['login']['position'] = 'side';
if ($TadLoginXoopsModule) {
    $blocksArr['login']['config']['login_method'] = $login_defval;
    $blocksArr['login']['colset']['login_method'] = array('label' => _MD_TCW_NEWS_BLOCK_LOGIN_METHOD, 'type' => 'checkbox', 'options' => $login_method);
}

$blocksArr['search']['name']     = _MD_TCW_SYSTEM_BLOCK_SEARCH;
$blocksArr['search']['tpl']      = 'search.html';
$blocksArr['search']['position'] = 'side';

$blocksArr['qrcode']['name']     = _MD_TCW_SYSTEM_BLOCK_QRCODE;
$blocksArr['qrcode']['tpl']      = 'qrcode.html';
$blocksArr['qrcode']['position'] = 'side';

$blocksArr['web_list']['name']     = _MD_TCW_SYSTEM_BLOCK_WEBLIST;
$blocksArr['web_list']['tpl']      = 'web_list.html';
$blocksArr['web_list']['position'] = 'side';

$blocksArr['rrssb']['name']     = _MD_TCW_SYSTEM_BLOCK_RRSSB;
$blocksArr['rrssb']['tpl']      = 'rrssb.html';
$blocksArr['rrssb']['position'] = 'side';

$blocksArr['moedict']['name']     = _MD_TCW_SYSTEM_BLOCK_MOEDICT;
$blocksArr['moedict']['tpl']      = 'moedict.html';
$blocksArr['moedict']['position'] = 'side';

$blocksArr['dreye']['name']     = _MD_TCW_SYSTEM_BLOCK_DREYE;
$blocksArr['dreye']['tpl']      = 'dreye.html';
$blocksArr['dreye']['position'] = 'side';

$blocksArr['wiki']['name']     = _MD_TCW_SYSTEM_BLOCK_WIKI;
$blocksArr['wiki']['tpl']      = 'wiki.html';
$blocksArr['wiki']['position'] = 'side';

$blocksArr['pm25']['name']     = _MD_TCW_SYSTEM_BLOCK_PM25;
$blocksArr['pm25']['tpl']      = 'pm25.html';
$blocksArr['pm25']['position'] = 'side';

$blocksArr['psi']['name']     = _MD_TCW_SYSTEM_BLOCK_PSI;
$blocksArr['psi']['tpl']      = 'psi.html';
$blocksArr['psi']['position'] = 'side';

$blocksArr['tlkio']['name']                   = _MD_TCW_SYSTEM_BLOCK_TALKIO;
$blocksArr['tlkio']['tpl']                    = 'tlkio.html';
$blocksArr['tlkio']['position']               = 'side';
$blocksArr['tlkio']['config']['tlkio_name']   = "chat_room_{{WebID}}";
$blocksArr['tlkio']['colset']['tlkio_name']   = array('label' => _MD_TCW_SYSTEM_BLOCK_TLKIO_NAME, 'type' => 'text');
$blocksArr['tlkio']['config']['tlkio_theme']  = "day";
$blocksArr['tlkio']['colset']['tlkio_theme']  = array('label' => _MD_TCW_SYSTEM_BLOCK_TLKIO_THEME, 'type' => 'select', 'options' => array(_MD_TCW_SYSTEM_BLOCK_TLKIO_THEME_DAY => 'day', _MD_TCW_SYSTEM_BLOCK_TLKIO_THEME_NIGHT => 'night', _MD_TCW_SYSTEM_BLOCK_TLKIO_THEME_POP => 'pop', _MD_TCW_SYSTEM_BLOCK_TLKIO_THEME_MINIMAL => 'minimal'));
$blocksArr['tlkio']['config']['tlkio_height'] = "400";
$blocksArr['tlkio']['colset']['tlkio_height'] = array('label' => _MD_TCW_SYSTEM_BLOCK_TLKIO_HEIGHT, 'type' => 'text');

$blocksArr['countdown']['name']                      = _MD_TCW_SYSTEM_BLOCK_COUNTDOWN;
$blocksArr['countdown']['tpl']                       = 'countdown.html';
$blocksArr['countdown']['position']                  = 'side';
$blocksArr['countdown']['config']['countdown_title'] = _MD_TCW_SYSTEM_BLOCK_COUNTDOWN_TITLE_DEF;
$blocksArr['countdown']['colset']['countdown_title'] = array('label' => _MD_TCW_SYSTEM_BLOCK_COUNTDOWN_TITLE, 'type' => 'text');
$blocksArr['countdown']['config']['countdown_date']  = date("12/25/Y 00:00:00");
$blocksArr['countdown']['colset']['countdown_date']  = array('label' => _MD_TCW_SYSTEM_BLOCK_COUNTDOWN_DATE, 'type' => 'datetime');

$blocksArr['flickrit']['name']                     = _MD_TCW_SYSTEM_BLOCK_FLICKRIT;
$blocksArr['flickrit']['tpl']                      = 'flickrit.html';
$blocksArr['flickrit']['position']                 = 'side';
$blocksArr['flickrit']['config']['flickrit_type']  = 'slideshowholderpicasa';
$blocksArr['flickrit']['colset']['flickrit_type']  = array('label' => _MD_TCW_SYSTEM_BLOCK_FLICKRIT_TYPE, 'type' => 'select', 'options' => array(_MD_TCW_SYSTEM_BLOCK_FLICKRIT_TYPE_FLICKR => 'slideshowholder', _MD_TCW_SYSTEM_BLOCK_FLICKRIT_TYPE_PICASA => 'slideshowholderpicasa'));
$blocksArr['flickrit']['config']['flickrit_kind']  = 'setId';
$blocksArr['flickrit']['colset']['flickrit_kind']  = array('label' => _MD_TCW_SYSTEM_BLOCK_FLICKRIT_KIND, 'type' => 'select', 'options' => array(_MD_TCW_SYSTEM_BLOCK_FLICKRIT_KIND_SETID => 'setId', _MD_TCW_SYSTEM_BLOCK_FLICKRIT_KIND_USERID => 'userId'));
$blocksArr['flickrit']['config']['flickrit_setid'] = '110168492315217261022/Flickrit';
$blocksArr['flickrit']['colset']['flickrit_setid'] = array('label' => _MD_TCW_SYSTEM_BLOCK_FLICKRIT_SETID, 'type' => 'text');

$blockConfig['system'] = $blocksArr;
