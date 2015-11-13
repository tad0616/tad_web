<?php
$blocksArr = '';
$i         = 0;

$blocksArr[$i]['name'] = _MD_TCW_SCHEDULE_BLOCK_LIST;
$blocksArr[$i]['func'] = 'list_schedule';
$blocksArr[$i]['tpl']  = 'list_schedule.html';

$blockConfig['schedule'] = $blocksArr;
