<?php
$blocksArr = '';
$i         = 0;

$blocksArr[$i]['name'] = _MD_TCW_DISCUSS_BLOCK_LIST;
$blocksArr[$i]['func'] = 'list_discuss';
$blocksArr[$i]['tpl']  = 'list_discuss.html';

$blockConfig['discuss'] = $blocksArr;
