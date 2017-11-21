<?php
$blocksArr['list_homework']['name']            = _MD_TCW_HOMEWORK_BLOCK_LIST;
$blocksArr['list_homework']['tpl']             = 'list_homework.tpl';
$blocksArr['list_homework']['position']        = 'block1';
$blocksArr['list_homework']['config']['limit'] = 5;
$blocksArr['list_homework']['colset']['limit'] = array('label' => _MD_TCW_HOMEWORK_BLOCK_LIMIT, 'type' => 'text');

$blockConfig['homework'] = $blocksArr;
