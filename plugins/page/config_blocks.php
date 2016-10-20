<?php
$blocksArr = '';

$blocksArr['list_page']['name']                 = _MD_TCW_PAGE_BLOCK_LIST;
$blocksArr['list_page']['tpl']                  = 'list_page.html';
$blocksArr['list_page']['position']             = 'block4';
$blocksArr['list_page']['config']['limit']      = 20;
$blocksArr['list_page']['colset']['limit']      = array('label' => _MD_TCW_BLOCK_LIMIT, 'type' => 'text');
$blocksArr['list_page']['config']['show_count'] = 0;
$blocksArr['list_page']['colset']['show_count'] = array('label' => _MD_TCW_PAGE_BLOCK_SHOW_COUNT, 'type' => 'radio', 'options' => array(_YES => 1, _NO => 0));

$blocksArr['page_menu']['name']                 = _MD_TCW_PAGE_BLOCK_MENU;
$blocksArr['page_menu']['tpl']                  = 'page_menu.html';
$blocksArr['page_menu']['position']             = 'side';
$blocksArr['page_menu']['config']['limit']      = 40;
$blocksArr['page_menu']['colset']['limit']      = array('label' => _MD_TCW_BLOCK_LIMIT, 'type' => 'text');
$blocksArr['page_menu']['config']['show_count'] = 0;
$blocksArr['page_menu']['colset']['show_count'] = array('label' => _MD_TCW_PAGE_BLOCK_SHOW_COUNT, 'type' => 'radio', 'options' => array(_YES => 1, _NO => 0));

$blockConfig['page'] = $blocksArr;
