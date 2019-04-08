<?php

$blocksArr['list_web_adm']['name']     = _MD_TCW_ABOUT_BLOCK_LIST_ADM;
$blocksArr['list_web_adm']['plugin']   = 'aboutus';
$blocksArr['list_web_adm']['tpl']      = 'list_web_adm.tpl';
$blocksArr['list_web_adm']['position'] = 'side';

$blocksArr['list_web_student']['name']     = _MD_TCW_ABOUT_BLOCK_LIST_STUD;
$blocksArr['list_web_student']['plugin']   = 'aboutus';
$blocksArr['list_web_student']['tpl']      = 'list_web_student.tpl';
$blocksArr['list_web_student']['position'] = 'block1';

//不能刪，否則會導致無法設定
$blockConfig['aboutus'] = $blocksArr;
