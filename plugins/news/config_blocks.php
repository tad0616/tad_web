<?php
$blocksArr = '';

$blocksArr['list_news']['name']                = _MD_TCW_NEWS_BLOCK_LIST;
$blocksArr['list_news']['tpl']                 = 'list_news.html';
$blocksArr['list_news']['position']            = 'block1';
$blocksArr['list_news']['config']['limit']     = 5;
$blocksArr['list_news']['colset']['limit']     = array('label' => _MD_TCW_NEWS_BLOCK_LIMIT, 'type' => 'text');
$blocksArr['list_news']['config']['show_mode'] = 'one_big';
$blocksArr['list_news']['colset']['show_mode'] = array('label' => _MD_TCW_NEWS_BLOCK_SHOW_MODE, 'type' => 'select', 'options' => array(_MD_TCW_NEWS_BLOCK_SHOW_MODE1 => 'one_big', _MD_TCW_NEWS_BLOCK_SHOW_MODE2 => 'full', _MD_TCW_NEWS_BLOCK_SHOW_MODE3 => 'list'));

$blockConfig['news'] = $blocksArr;
