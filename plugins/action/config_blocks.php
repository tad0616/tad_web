<?php
$blocksArr = '';
$i         = 0;

$blocksArr[$i]['name'] = _MD_TCW_ACTION_BLOCK_LIST;
$blocksArr[$i]['func'] = 'list_action';
$blocksArr[$i]['tpl']  = 'list_action.html';

$blockConfig['action'] = $blocksArr;
