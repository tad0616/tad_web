<?php
$blocksArr = '';

$blocksArr['list_discuss']['name']            = _MD_TCW_DISCUSS_BLOCK_LIST;
$blocksArr['list_discuss']['tpl']             = 'list_discuss.tpl';
$blocksArr['list_discuss']['position']        = 'block4';
$blocksArr['list_discuss']['config']['limit'] = 5;
$blocksArr['list_discuss']['colset']['limit'] = array('label' => _MD_TCW_DISCUSS_BLOCK_LIMIT, 'type' => 'text');

$blockConfig['discuss'] = $blocksArr;
