<?php
$blocksArr = '';

$blocksArr['list_calendar']['name'] = _MD_TCW_CALENDAR_BLOCK_LIST;
$blocksArr['list_calendar']['tpl']  = 'list_calendar.html';
//$blocksArr['list_calendar']['config']['limit'] = 5;
//$blocksArr['list_calendar']['colset']['limit'] = array('label' => _MD_TCW_BLOCK_LIMIT, 'type' => 'text');

$blockConfig['calendar'] = $blocksArr;
