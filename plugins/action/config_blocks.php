<?php
$blocksArr = '';

$blocksArr['list_action']['name']            = _MD_TCW_ACTION_BLOCK_LIST;
$blocksArr['list_action']['tpl']             = 'list_action.tpl';
$blocksArr['list_action']['position']        = 'block4';
$blocksArr['list_action']['config']['limit'] = 10;
$blocksArr['list_action']['colset']['limit'] = array('label' => _MD_TCW_ACTION_BLOCK_LIMIT, 'type' => 'text');

$blocksArr['action_slide']['name']     = _MD_TCW_ACTION_BLOCK_SLIDE;
$blocksArr['action_slide']['tpl']      = 'action_slide.tpl';
$blocksArr['action_slide']['position'] = 'side';
// $blocksArr['action_slide']['config']['limit'] = 10;
// $blocksArr['action_slide']['colset']['limit'] = array('label' => _MD_TCW_ACTION_BLOCK_LIMIT, 'type' => 'text');

$blockConfig['action'] = $blocksArr;
