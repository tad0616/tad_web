<?php
$blocksArr = '';
$i         = 0;

$blocksArr[$i]['name'] = _MD_TCW_FILES_BLOCK_LIST;
$blocksArr[$i]['func'] = 'list_files';
$blocksArr[$i]['tpl']  = 'list_files.html';

$blockConfig['files'] = $blocksArr;
