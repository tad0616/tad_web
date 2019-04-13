<?php
$blocksArr['list_schedule']['name'] = _MD_TCW_SCHEDULE_BLOCK_LIST;
$blocksArr['list_schedule']['plugin'] = 'schedule';
$blocksArr['list_schedule']['tpl'] = 'list_schedule.tpl';
$blocksArr['list_schedule']['position'] = 'block4';
//$blocksArr['list_schedule']['config']['limit'] = 5;
//$blocksArr['list_schedule']['colset']['limit'] = array('label' => _MD_TCW_BLOCK_LIMIT, 'type' => 'text');

//不能刪，否則會導致無法設定
$blockConfig['schedule'] = $blocksArr;
