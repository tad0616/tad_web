<?php
$blocksArr = '';

$blocksArr['list_link']['name']                = _MD_TCW_LINK_BLOCK_LIST;
$blocksArr['list_link']['tpl']                 = 'list_link.html';
$blocksArr['list_link']['position']            = 'block4';
$blocksArr['list_link']['config']['limit']     = 5;
$blocksArr['list_link']['colset']['limit']     = array('label' => _MD_TCW_LINK_BLOCK_LIMIT, 'type' => 'text');
$blocksArr['list_link']['config']['hide_link'] = 0;
$blocksArr['list_link']['colset']['hide_link'] = array('label' => _MD_TCW_LINK_HIDE_LINK, 'type' => 'radio', 'options' => array(_YES => 1, _NO => 0));
$blocksArr['list_link']['config']['hide_desc'] = 0;
$blocksArr['list_link']['colset']['hide_desc'] = array('label' => _MD_TCW_LINK_HIDE_DESC, 'type' => 'radio', 'options' => array(_YES => 1, _NO => 0));

$blockConfig['link'] = $blocksArr;
