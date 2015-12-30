<?php
$blocksArr = '';

$blocksArr['my_menu']['name']     = _MD_TCW_SYSTEM_BLOCK_MY_MENU;
$blocksArr['my_menu']['tpl']      = 'my_menu.html';
$blocksArr['my_menu']['position'] = 'side';

$blocksArr['login']['name']     = _MD_TCW_SYSTEM_BLOCK_LOGIN;
$blocksArr['login']['tpl']      = 'login.html';
$blocksArr['login']['position'] = 'side';

$blocksArr['search']['name']     = _MD_TCW_SYSTEM_BLOCK_SEARCH;
$blocksArr['search']['tpl']      = 'search.html';
$blocksArr['search']['position'] = 'side';

$blocksArr['qrcode']['name']     = _MD_TCW_SYSTEM_BLOCK_QRCODE;
$blocksArr['qrcode']['tpl']      = 'qrcode.html';
$blocksArr['qrcode']['position'] = 'side';

$blocksArr['web_list']['name']     = _MD_TCW_SYSTEM_BLOCK_WEBLIST;
$blocksArr['web_list']['tpl']      = 'web_list.html';
$blocksArr['web_list']['position'] = 'side';

$blockConfig['system'] = $blocksArr;
