<?php
$blocksArr['list_discuss']['name'] = _MD_TCW_DISCUSS_BLOCK_LIST;
$blocksArr['list_discuss']['plugin'] = 'discuss';
$blocksArr['list_discuss']['tpl'] = 'list_discuss.tpl';
$blocksArr['list_discuss']['position'] = 'block4';
$blocksArr['list_discuss']['config']['limit'] = 5;
$blocksArr['list_discuss']['colset']['limit'] = ['label' => _MD_TCW_DISCUSS_BLOCK_LIMIT, 'type' => 'text'];

//不能刪，否則會導致無法設定
$blockConfig['discuss'] = $blocksArr;
