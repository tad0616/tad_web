<?php
$blocksArr = '';

$blocksArr['list_video']['name']            = _MD_TCW_VIDEO_BLOCK_LIST;
$blocksArr['list_video']['tpl']             = 'list_video.html';
$blocksArr['list_video']['config']['limit'] = 5;
$blocksArr['list_video']['colset']['limit'] = array('label' => _MD_TCW_VIDEO_BLOCK_LIMIT, 'type' => 'text');

$blocksArr['random_video']['name'] = _MD_TCW_VIDEO_BLOCK_RANDOM;
$blocksArr['random_video']['tpl']  = 'random_video.html';
//$blocksArr['random_video']['config']['limit'] = 5;
//$blocksArr['random_video']['colset']['limit'] = array('label' => _MD_TCW_VIDEO_BLOCK_LIMIT, 'type' => 'text');

$blocksArr['latest_video']['name'] = _MD_TCW_VIDEO_BLOCK_LATEST;
$blocksArr['latest_video']['tpl']  = 'latest_video.html';
//$blocksArr['latest_video']['config']['limit'] = 5;
//$blocksArr['latest_video']['colset']['limit'] = array('label' => _MD_TCW_VIDEO_BLOCK_LIMIT, 'type' => 'text');

$blockConfig['video'] = $blocksArr;
