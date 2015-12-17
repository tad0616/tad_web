<?php
$blocksArr = '';

$blocksArr['list_files']['name']            = _MD_TCW_FILES_BLOCK_LIST;
$blocksArr['list_files']['tpl']             = 'list_files.html';
$blocksArr['list_files']['position']        = 'block4';
$blocksArr['list_files']['config']['limit'] = 5;
$blocksArr['list_files']['colset']['limit'] = array('label' => _MD_TCW_FILES_BLOCK_LIMIT, 'type' => 'text');

$blockConfig['files'] = $blocksArr;
