<?php
$blocksArr['list_video']['name']            = _MD_TCW_VIDEO_BLOCK_LIST;
$blocksArr['list_video']['plugin']          = 'video';
$blocksArr['list_video']['tpl']             = 'list_video.tpl';
$blocksArr['list_video']['position']        = 'block4';
$blocksArr['list_video']['config']['limit'] = 5;
$blocksArr['list_video']['colset']['limit'] = array('label' => _MD_TCW_VIDEO_BLOCK_LIMIT, 'type' => 'text');
$blocksArr['list_video']['config']['mode']  = 'list';
$blocksArr['list_video']['colset']['mode']  = array('label' => _MD_TCW_VIDEO_BLOCK_MODE, 'type' => 'radio', 'options' => array(_MD_TCW_VIDEO_BLOCK_LIST_MODE => 'list', _MD_TCW_VIDEO_BLOCK_THUMB_MODE => 'thumb'));

$blocksArr['random_video']['name']     = _MD_TCW_VIDEO_BLOCK_RANDOM;
$blocksArr['random_video']['plugin']   = 'video';
$blocksArr['random_video']['tpl']      = 'random_video.tpl';
$blocksArr['random_video']['position'] = 'block2';
//$blocksArr['random_video']['config']['limit'] = 5;
//$blocksArr['random_video']['colset']['limit'] = array('label' => _MD_TCW_VIDEO_BLOCK_LIMIT, 'type' => 'text');

$blocksArr['latest_video']['name']     = _MD_TCW_VIDEO_BLOCK_LATEST;
$blocksArr['latest_video']['plugin']   = 'video';
$blocksArr['latest_video']['tpl']      = 'latest_video.tpl';
$blocksArr['latest_video']['position'] = 'block3';
//$blocksArr['latest_video']['config']['limit'] = 5;
//$blocksArr['latest_video']['colset']['limit'] = array('label' => _MD_TCW_VIDEO_BLOCK_LIMIT, 'type' => 'text');

//不能刪，否則會導致無法設定
$blockConfig['video'] = $blocksArr;
