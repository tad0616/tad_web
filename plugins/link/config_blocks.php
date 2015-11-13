<?php
$blocksArr = '';
$i         = 0;

$blocksArr[$i]['name'] = _MD_TCW_LINK_BLOCK_LIST;
$blocksArr[$i]['func'] = 'list_link';
$blocksArr[$i]['tpl']  = 'list_link.html';

$blockConfig['link'] = $blocksArr;
