<?php
$blocksArr = '';

$blocksArr['list_account']['name']            = _MD_TCW_ACCOUNT_BLOCK_LIST;
$blocksArr['list_account']['tpl']             = 'list_account.html';
$blocksArr['list_account']['position']        = 'block4';
$blocksArr['list_account']['config']['limit'] = 10;
$blocksArr['list_account']['colset']['limit'] = array('label' => _MD_TCW_ACCOUNT_BLOCK_LIMIT, 'type' => 'text');

$blockConfig['account'] = $blocksArr;
