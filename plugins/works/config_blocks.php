<?php
$blocksArr['list_work']['name']            = _MD_TCW_WORK_BLOCK_LIST;
$blocksArr['list_work']['plugin']          = 'works';
$blocksArr['list_work']['tpl']             = 'list_work.tpl';
$blocksArr['list_work']['position']        = 'block4';
$blocksArr['list_work']['config']['limit'] = 5;
$blocksArr['list_work']['colset']['limit'] = array('label' => _MD_TCW_WORK_BLOCK_LIMIT, 'type' => 'text');

$blocksArr['random_work']['name']            = _MD_TCW_WORK_BLOCK_RANDOM;
$blocksArr['random_work']['plugin']          = 'works';
$blocksArr['random_work']['tpl']             = 'random_work.tpl';
$blocksArr['random_work']['position']        = 'block2';
$blocksArr['random_work']['config']['limit'] = 9;
$blocksArr['random_work']['colset']['limit'] = array('label' => _MD_TCW_WORK_BLOCK_LIMIT, 'type' => 'text');

$blocksArr['latest_work']['name']            = _MD_TCW_WORK_BLOCK_LATEST;
$blocksArr['latest_work']['plugin']          = 'works';
$blocksArr['latest_work']['tpl']             = 'latest_work.tpl';
$blocksArr['latest_work']['position']        = 'block3';
$blocksArr['latest_work']['config']['limit'] = 9;
$blocksArr['latest_work']['colset']['limit'] = array('label' => _MD_TCW_WORK_BLOCK_LIMIT, 'type' => 'text');


//不能刪，否則會導致無法設定
$blockConfig['works'] = $blocksArr;
