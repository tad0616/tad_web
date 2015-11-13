<?php
$blocksArr = '';
$i         = 0;

$blocksArr[$i]['name'] = _MD_TCW_HOMEWORK_BLOCK_LIST;
$blocksArr[$i]['func'] = 'list_homework';
$blocksArr[$i]['tpl']  = 'list_homework.html';

$blockConfig['homework'] = $blocksArr;
