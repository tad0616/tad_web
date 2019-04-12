<?php
$blocksArr['list_link']['name']                = _MD_TCW_LINK_BLOCK_LIST;
$blocksArr['list_link']['plugin']              = 'link';
$blocksArr['list_link']['tpl']                 = 'list_link.tpl';
$blocksArr['list_link']['position']            = 'block4';
$blocksArr['list_link']['config']['limit']     = 5;
$blocksArr['list_link']['colset']['limit']     = ['label' => _MD_TCW_LINK_BLOCK_LIMIT, 'type' => 'text'];
$blocksArr['list_link']['config']['hide_link'] = 0;
$blocksArr['list_link']['colset']['hide_link'] = ['label' => _MD_TCW_LINK_HIDE_LINK, 'type' => 'radio', 'options' => [_YES => 1, _NO => 0]];
$blocksArr['list_link']['config']['hide_desc'] = 0;
$blocksArr['list_link']['colset']['hide_desc'] = ['label' => _MD_TCW_LINK_HIDE_DESC, 'type' => 'radio', 'options' => [_YES => 1, _NO => 0]];
//不能刪，否則會導致無法設定
$blockConfig['link'] = $blocksArr;
