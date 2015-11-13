<?php
$blocksArr = '';
$i         = 0;

$blocksArr[$i]['name'] = _MD_TCW_VIDEO_BLOCK_LIST;
$blocksArr[$i]['func'] = 'list_video';
$blocksArr[$i]['tpl']  = 'list_video.html';

$i++;
$blocksArr[$i]['name'] = _MD_TCW_VIDEO_BLOCK_RANDOM;
$blocksArr[$i]['func'] = 'random_video';
$blocksArr[$i]['tpl']  = 'random_video.html';

$i++;
$blocksArr[$i]['name'] = _MD_TCW_VIDEO_BLOCK_LATEST;
$blocksArr[$i]['func'] = 'latest_video';
$blocksArr[$i]['tpl']  = 'latest_video.html';

$blockConfig['video'] = $blocksArr;
