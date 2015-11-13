<?php
$blocksArr = '';
$i         = 0;

$blocksArr[$i]['name'] = _MD_TCW_NEWS_BLOCK_LIST;
$blocksArr[$i]['func'] = 'list_news';
$blocksArr[$i]['tpl']  = 'list_news.html';

$blockConfig['news'] = $blocksArr;
