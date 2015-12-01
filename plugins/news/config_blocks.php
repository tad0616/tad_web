<?php
$blocksArr = '';

$blocksArr['list_news']['name']            = _MD_TCW_NEWS_BLOCK_LIST;
$blocksArr['list_news']['tpl']             = 'list_news.html';
$blocksArr['list_news']['config']['limit'] = 5;
$blocksArr['list_news']['colset']['limit'] = array('label' => _MD_TCW_NEWS_BLOCK_LIMIT, 'type' => 'text');

$blockConfig['news'] = $blocksArr;
