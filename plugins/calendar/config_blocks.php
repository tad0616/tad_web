<?php
$blocksArr['list_calendar']['name']     = _MD_TCW_CALENDAR_BLOCK_LIST;
$blocksArr['list_calendar']['plugin']   = 'calendar';
$blocksArr['list_calendar']['tpl']      = 'list_calendar.tpl';
$blocksArr['list_calendar']['position'] = 'block4';
//$blocksArr['list_calendar']['config']['limit'] = 5;
//$blocksArr['list_calendar']['colset']['limit'] = array('label' => _MD_TCW_BLOCK_LIMIT, 'type' => 'text');

//不能刪，否則會導致無法設定
$blockConfig['calendar'] = $blocksArr;
