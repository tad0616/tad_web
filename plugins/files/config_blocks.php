<?php
$blocksArr['list_files']['name'] = _MD_TCW_FILES_BLOCK_LIST;
$blocksArr['list_files']['plugin'] = 'files';
$blocksArr['list_files']['tpl'] = 'list_files.tpl';
$blocksArr['list_files']['position'] = 'block4';
$blocksArr['list_files']['config']['limit'] = 5;
$blocksArr['list_files']['colset']['limit'] = ['label' => _MD_TCW_FILES_BLOCK_LIMIT, 'type' => 'text'];

//不能刪，否則會導致無法設定
$blockConfig['files'] = $blocksArr;
