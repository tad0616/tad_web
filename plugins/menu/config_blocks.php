<?php
$blocksArr['list_menu']['name'] = _MD_TCW_MENU_BLOCK_LIST;
$blocksArr['list_menu']['plugin'] = 'menu';
$blocksArr['list_menu']['tpl'] = 'list_menu.tpl';
$blocksArr['list_menu']['position'] = 'block4';
$blocksArr['list_menu']['config']['limit'] = 10;
$blocksArr['list_menu']['colset']['limit'] = ['label' => _MD_TCW_MENU_BLOCK_LIMIT, 'type' => 'text'];

//不能刪，否則會導致無法設定
$blockConfig['menu'] = $blocksArr;
