<?php
$blocksArr = '';

$blocksArr['list_link']['name']            = _MD_TCW_LINK_BLOCK_LIST;
$blocksArr['list_link']['tpl']             = 'list_link.html';
$blocksArr['list_link']['position']        = 'block4';
$blocksArr['list_link']['config']['limit'] = 5;
$blocksArr['list_link']['colset']['limit'] = array('label' => _MD_TCW_LINK_BLOCK_LIMIT, 'type' => 'text');

$blockConfig['link'] = $blocksArr;
