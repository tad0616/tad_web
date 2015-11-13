<?php
$blocksArr = '';
$i         = 0;

$blocksArr[$i]['name'] = _MD_TCW_CALENDAR_BLOCK_LIST;
$blocksArr[$i]['func'] = 'list_calendar';
$blocksArr[$i]['tpl']  = 'list_calendar.html';

$blockConfig['calendar'] = $blocksArr;
