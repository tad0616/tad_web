<?php
$blocksArr = '';

$blocksArr['list_action']['name']            = _MD_TCW_ACTION_BLOCK_LIST;
$blocksArr['list_action']['tpl']             = 'list_action.html';
$blocksArr['list_action']['config']['limit'] = 10;
$blocksArr['list_action']['colset']['limit'] = array('label' => _MD_TCW_ACTION_BLOCK_LIMIT, 'type' => 'text');

$blockConfig['action'] = $blocksArr;
