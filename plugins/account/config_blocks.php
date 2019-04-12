<?php

$blocksArr['list_account']['name']            = _MD_TCW_ACCOUNT_BLOCK_LIST;
$blocksArr['list_account']['plugin']          = 'account';
$blocksArr['list_account']['tpl']             = 'list_account.tpl';
$blocksArr['list_account']['position']        = 'block4';
$blocksArr['list_account']['config']['limit'] = 10;
$blocksArr['list_account']['colset']['limit'] = ['label' => _MD_TCW_ACCOUNT_BLOCK_LIMIT, 'type' => 'text'];

//不能刪，否則會導致無法設定
$blockConfig['account'] = $blocksArr;
