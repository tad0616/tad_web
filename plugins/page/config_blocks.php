<?php
global $xoopsConfig;

$i = 0;

$blocksArr[$i]['name'] = _MD_TCW_PAGE_BLOCK_LIST;
$blocksArr[$i]['func'] = 'get_page_list';
$blocksArr[$i]['tpl']  = 'get_page_list.html';

$blockConfig['page'] = $blocksArr;
