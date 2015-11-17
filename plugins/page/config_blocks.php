<?php
$blocksArr = '';
$i         = 0;

$blocksArr[$i]['name']   = _MD_TCW_PAGE_BLOCK_LIST;
$blocksArr[$i]['func']   = 'list_page';
$blocksArr[$i]['tpl']    = 'list_page.html';
$blocksArr[$i]['config'] = array();

$i++;
$blocksArr[$i]['name']   = _MD_TCW_PAGE_BLOCK_MENU;
$blocksArr[$i]['func']   = 'page_menu';
$blocksArr[$i]['tpl']    = 'page_menu.html';
$blocksArr[$i]['config'] = array();

$blockConfig['page'] = $blocksArr;
