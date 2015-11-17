<?php
$blocksArr = '';
$i         = 0;
$i++;
$blocksArr[$i]['name']   = _MD_TCW_WORK_BLOCK_LIST;
$blocksArr[$i]['func']   = 'list_work';
$blocksArr[$i]['tpl']    = 'list_work.html';
$blocksArr[$i]['config'] = array('limit' => 5);

$i++;
$blocksArr[$i]['name']   = _MD_TCW_WORK_BLOCK_RANDOM;
$blocksArr[$i]['func']   = 'random_work';
$blocksArr[$i]['tpl']    = 'random_work.html';
$blocksArr[$i]['config'] = array();

$i++;
$blocksArr[$i]['name']   = _MD_TCW_WORK_BLOCK_LATEST;
$blocksArr[$i]['func']   = 'latest_work';
$blocksArr[$i]['tpl']    = 'latest_work.html';
$blocksArr[$i]['config'] = array();

$blockConfig['works'] = $blocksArr;
