<?php
$blocksArr = '';
$i         = 0;

$blocksArr[$i]['name']   = _MD_TCW_ACTION_BLOCK_LIST;
$blocksArr[$i]['func']   = 'list_action';
$blocksArr[$i]['tpl']    = 'list_action.html';
$blocksArr[$i]['config'] = array('limit' => 10);

$blockConfig['action'] = $blocksArr;
