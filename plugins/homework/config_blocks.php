<?php
$blocksArr['list_homework']['name']            = _MD_TCW_HOMEWORK_BLOCK_LIST;
$blocksArr['list_homework']['plugin']          = 'homework';
$blocksArr['list_homework']['tpl']             = 'list_homework.tpl';
$blocksArr['list_homework']['position']        = 'block1';
$blocksArr['list_homework']['config']['limit'] = 5;
$blocksArr['list_homework']['colset']['limit'] = ['label' => _MD_TCW_HOMEWORK_BLOCK_LIMIT, 'type' => 'text'];

//不能刪，否則會導致無法設定
$blockConfig['homework'] = $blocksArr;
