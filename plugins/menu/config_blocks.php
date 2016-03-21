<?php
$blocksArr = '';

$blocksArr['list_menu']['name']            = _MD_TCW_MENU_BLOCK_LIST;
$blocksArr['list_menu']['tpl']             = 'list_menu.html';
$blocksArr['list_menu']['position']        = 'block4';
$blocksArr['list_menu']['config']['limit'] = 10;
$blocksArr['list_menu']['colset']['limit'] = array('label' => _MD_TCW_MENU_BLOCK_LIMIT, 'type' => 'text');

$blockConfig['menu'] = $blocksArr;
